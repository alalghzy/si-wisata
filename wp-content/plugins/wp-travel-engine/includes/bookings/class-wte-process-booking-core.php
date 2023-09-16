<?php
/**
 * Core Booking Process controls.
 *
 * @package wp-travel-engine
 */

namespace WPTravelEngine\Core;

/**
 * Process the booking flow in WP Travel Engine.
 *
 * @package WP_Travel_Engine
 * @since 2.2.8
 */

/**
 * Main Booking process handler class.
 */
class Booking {

	/**
	 * Global WTE_Cart Instance.
	 *
	 * @var null|WTE_Cart
	 */
	public $cart = null;

	/**
	 * Singleton instance.
	 *
	 * @var null|WPTravelEngine\Core\Booking
	 */
	protected static $instance = null;

	/**
	 * Constructor.
	 *
	 * @since 4.3.0
	 */
	public function __construct() {
		$this->includes();

		$this->init_hooks();
	}

	/**
	 * Return singleton instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Loads booking related files.
	 *
	 * @since 4.3.0
	 *
	 * @return void
	 */
	private function includes() {

	}

	/**
	 * Undocumented function
	 *
	 * @since 4.3.0
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'init', array( __CLASS__, 'payment_gateway_callback_listener' ) );

		/**
		 * IPN Response catcher.
		 *
		 * @TODO: Remove this when payment addons updates.
		 */
		add_action( 'init', array( __CLASS__, 'payment_gateway_callback_backward_compatibility' ), 9 );

		/**
		 * Process Save Post request.
		 *
		 * @TODO: Take somewhere good place.
		 */
		add_action(
			'save_post_booking',
			function( $post_id, $post, $update = false ) {

				if ( isset( $_POST['_wpnonce'] ) ) {
					if ( ! \wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'update-post_' . $post_id ) ) {
						return;
					}
				}

				$post_data = wte_clean( wp_unslash( $_REQUEST ) );

				$place_order = array();

				$order_trips = isset( $post_data['order_trips'] ) ? $post_data['order_trips'] : array();
				if ( isset( $post->order_trips ) && is_array( $post->order_trips ) && key( $post->order_trips ) !== null ) {
					$order_trips = wp_parse_args( $order_trips, $post->order_trips );
				}

				$_order_trips = array();
				foreach ( $order_trips as $cart_id => $cart_info ) {
					if ( isset( $order_trips[ $cart_id ] ) && is_array( $order_trips[ $cart_id ] ) ) {
						$_order_trips[ $cart_id ] = wp_parse_args( $order_trips[ $cart_id ], isset( $post->order_trips[ $cart_id ] ) ? $post->order_trips[ $cart_id ] : array() );
					}
				}
				update_post_meta( $post_id, 'order_trips', $_order_trips );

				if ( isset( $post_data['cart_info'] ) && is_array( $post_data['cart_info'] ) ) {
					update_post_meta( $post_id, 'cart_info', wp_parse_args( wte_clean( wp_unslash( $post_data['cart_info'] ) ), $post->cart_info ) );
				}

				if ( isset( $post_data['billing_info'] ) ) {
					update_post_meta( $post_id, 'billing_info', wte_clean( wp_unslash( $post_data['billing_info'] ) ) );
					$place_order['booking'] = wte_clean( wp_unslash( $post_data['billing_info'] ) );
				}
				if ( isset( $post_data['paid_amount'] ) ) {
					update_post_meta( $post_id, 'paid_amount', wte_clean( wp_unslash( $post_data['paid_amount'] ) ) );
				}
				if ( isset( $post_data['due_amount'] ) ) {
					update_post_meta( $post_id, 'due_amount', wte_clean( wp_unslash( $post_data['due_amount'] ) ) );
					$place_order['due'] = wte_clean( wp_unslash( $post_data['due_amount'] ) );
				}

				$booking = get_post( $post );

				$order_trips = $booking->order_trips;
				if ( is_array( $order_trips ) && key( $order_trips ) !== null ) {
					$ot = (object) array_shift( $order_trips );
					// tid.
					$place_order['tid'] = $ot->ID;
					// tname.
					$place_order['tname'] = isset( $ot->title ) ? $ot->title : get_the_title( $ot->ID );
					// datetime.
					$place_order['datetime'] = $ot->datetime;
					// cost.
					$place_order['cost'] = $ot->cost;
					// due.
					$place_order['due'] = $booking->due_amount;
					// $traveler.
					$place_order['traveler'] = array_sum( $ot->pax );
				}
				// booking.
				$place_order['booking'] = $post->billing_info;

				$additional_fields = get_post_meta( $post_id, 'additional_fields', true );

				// if ( isset( $post_data['billing_info'] ) ) {
				// update_post_meta( $post_id, 'billing_info', wte_clean( wp_unslash( $post_data['billing_info'] ) ) );
				// $place_order['booking'] = wte_clean( wp_unslash( $post_data['billing_info'] ) );
				// }
				// if ( isset( $post_data['paid_amount'] ) ) {
				// update_post_meta( $post_id, 'paid_amount', wte_clean( wp_unslash( $post_data['paid_amount'] ) ) );
				// }
				// if ( isset( $post_data['due_amount'] ) ) {
				// update_post_meta( $post_id, 'due_amount', wte_clean( wp_unslash( $post_data['due_amount'] ) ) );
				// $place_order['due'] = wte_clean( wp_unslash( $post_data['due_amount'] ) );
				// }

				$payments = array();
				if ( isset( $post_data['payments'] ) ) {
					$payments = $post_data['payments'];
					update_post_meta( $post_id, 'payments', array_keys( $payments ) );
				}

				if ( isset( $payments ) && is_array( $payments ) ) {
					foreach ( $payments as $pid => $payment ) {
						$payment_obj = get_post( +$pid );
						foreach ( array( 'payment_status', 'payment_gateway', 'payable' ) as $meta_key ) {
							if ( isset( $payment[ $meta_key ] ) ) {
								$meta_value = is_array( $payment_obj->{$meta_key} ) ? wp_parse_args( $payment[ $meta_key ], $payment_obj->{$meta_key} ) : $payment[ $meta_key ];
								update_post_meta( +$pid, $meta_key, $meta_value );
							}
						}
					}
				}

				// $order_trips = array();
				// if ( isset( $post_data['order_trips'] ) ) {
				// $order_trips = $post_data['order_trips'];
				// }

				// if ( isset( $order_trips ) && is_array( $order_trips ) ) {
				// $order_trips = is_array( $post->order_trips ) ? $post->order_trips : $order_trips;
				// foreach ( $order_trips as $cart_id => $cart_info ) {
				// if ( isset( $order_trips[ $cart_id ] ) && is_array( $order_trips[ $cart_id ] ) ) {
				// $order_trips[ $cart_id ] = wp_parse_args( $order_trips[ $cart_id ], $order_trips[ $cart_id ] );
				// }
				// }
				// update_post_meta( $post_id, 'order_trips', $order_trips );
				// }

				// if ( isset( $post_data['cart_info'] ) && is_array( $post_data['cart_info'] ) ) {
				// update_post_meta( $post_id, 'cart_info', wp_parse_args( wte_clean( wp_unslash( $post_data['cart_info'] ) ), $post->cart_info ) );
				// }

				if ( isset( $post_data['wp_travel_engine_booking_setting']['place_order']['travelers'] ) ) {
					$place_order['travelers'] = ( ( $post_data['wp_travel_engine_booking_setting']['place_order']['travelers'] ) );
				}
				if ( isset( $post_data['wp_travel_engine_booking_setting']['place_order']['relation'] ) ) {
					$place_order['relation'] = ( ( $post_data['wp_travel_engine_booking_setting']['place_order']['relation'] ) );
				}
				if ( isset( $post_data['wp_travel_engine_booking_setting']['place_order']['travelers'] ) && ! empty( $place_order ) ) {
					update_post_meta(
						$post_id,
						'wp_travel_engine_placeorder_setting',
						array(
							'place_order' => array(
								'travelers' => $place_order['travelers'],
								'relation'  => $place_order['relation'],
							),
						)
					);
				}

				$settings  = get_post_meta( $post_id, 'wp_travel_engine_booking_setting', true );
				$post_meta = is_array( $settings ) ? $settings : array();

				if ( isset( $post_data['wp_travel_engine_booking_setting']['place_order'] ) ) {
					$post_meta['place_order'] = wp_parse_args( ( ( $post_data['wp_travel_engine_booking_setting']['place_order'] ) ), $place_order );
				}

				// To resolve Issue because of overrided by other save post hooks.
				$_REQUEST['wp_travel_engine_booking_setting'] = $post_meta;
				$_POST['wp_travel_engine_booking_setting']    = $post_meta;
			},
			9,
			3
		);

		add_action(
			'init',
			function() {

				// Save Travellers Info.
				if ( isset( $_REQUEST['wp_travel_engine_placeorder_setting'] ) ) { // phpcs:ignore
					global $wte_cart;

					$place_order_setting = wte_clean( wp_unslash( $_REQUEST['wp_travel_engine_placeorder_setting'] ) ); // phpcs:ignore

					$temp_tf_redirection = WTE()->session->get( 'temp_tf_direction' );
					if ( ! empty( $temp_tf_redirection ) ) {
						$data = array_combine( array( 'bid', 'pid', '_gateway' ), explode( '|', $temp_tf_redirection ) );
						WTE()->session->delete( 'temp_tf_direction' );
					}

					if ( ! $data ) {
						return __( 'Thank you for booking the trip. Please check your email for confirmation.', 'wp-travel-engine' );
					}

					if ( is_array( $data ) && isset( $data['bid'] ) ) {
						$booking_id = $data['bid'];
						$payment_id = $data['pid'];
						$gateway    = $data['_gateway'];
					}
					if ( ! $booking_id ) {
						return;
					}

					// Update hook for addons
					do_action( 'wp_travel_engine_before_traveller_information_save' );

					// Update travellers information to booking id.
					update_post_meta( $booking_id, 'wp_travel_engine_placeorder_setting', $place_order_setting );

					// Update hook for addons
					do_action( 'wp_travel_engine_after_traveller_information_save' );

					$wte_settings = get_option( 'wp_travel_engine_settings', array() );
					wp_redirect(
						self::get_tokened_url(
							'thankyou',
							array(
								'bid'      => $booking_id,
								'pid'      => $payment_id,
								'_gateway' => $gateway,
							),
							empty( wte_array_get( $wte_settings, 'pages.wp_travel_engine_thank_you', '' ) ) ? home_url( '/' ) : get_permalink( wte_array_get( $wte_settings, 'pages.wp_travel_engine_thank_you', '' ) )
						)
					);
					exit();
				}
			}
		);

		// Triggered by payment addons.
		add_action( 'wte_redirect_after_payment_success', array( __CLASS__, 'redirect' ), 90 );
		add_action( 'wte_redirect_after_payment_error', array( __CLASS__, 'error' ), 90 );

		// Default payment Handler.
		add_action( 'wte_payment_gateway_booking_only', array( $this, 'booking_only' ), 10, 3 );
		add_action( 'wte_payment_gateway_direct_bank_transfer', array( $this, 'direct_bank_transfer' ), 10, 3 );
		add_action( 'wte_payment_gateway_check_payments', array( $this, 'check_payments' ), 10, 3 );

		/**
		 * Booking support until all payment addon updates. This function applies to all payment addons.
		 *
		 * @since 4.3.0
		 * @TODO: Add notice for removal of this support and remove on later updates.
		 */
		// add_action( 'wte_payment_gateway_paypalexpress_enable', array( $this, 'map_payment_data_to_new_booking_structure' ), 10, 3 );
		// add_action( 'wte_payment_gateway_payhere_payment', array( $this, 'map_payment_data_to_new_booking_structure' ), 10, 3 );
		// add_action( 'wte_payment_gateway_stripe_payment', array( $this, 'map_payment_data_to_new_booking_structure' ), 10, 3 );
		// add_action( 'wte_payment_gateway_payu_money_enable', array( $this, 'map_payment_data_to_new_booking_structure' ), 10, 3 );
		add_action( 'wte_payment_gateway_payu_enable', array( $this, 'map_payment_data_to_new_booking_structure' ), 10, 3 );
		// payu...

		add_action(
			'wte_booking_cleanup',
			function( $payment_id, $action = '' ) {
				global $wte_cart;
				$wte_cart->clear();
				if ( $action ) {
					delete_transient( "wte_token_{$action}" );
				}
			},
			11,
			2
		);

		add_action(
			'wte_after_thankyou_booking_details_direct_bank_transfer',
			function( $payment_id ) {
				$settings     = get_option( 'wp_travel_engine_settings', array() );
				$instructions = isset( $settings['bank_transfer']['instruction'] ) ? $settings['bank_transfer']['instruction'] : '';
				?>
					<div class="wte-bank-transfer-instructions">
						<?php echo wp_kses_post( $instructions ); ?>
					</div>
					<h4 class="bank-details"><?php echo esc_html__( 'Bank Details:', 'wp-travel-engine' ); ?></h4>
				<?php
				$bank_details = isset( $settings['bank_transfer']['accounts'] ) && is_array( $settings['bank_transfer']['accounts'] ) ? $settings['bank_transfer']['accounts'] : array();
				foreach ( $bank_details as $bank_detail ) :
					$details = array(
						'bank_name'      => array(
							'label' => __( 'Bank:', 'wp-travel-engine' ),
							'value' => $bank_detail['bank_name'],
						),
						'account_name'   => array(
							'label' => __( 'Account Name:', 'wp-travel-engine' ),
							'value' => $bank_detail['account_name'],
						),
						'account_number' => array(
							'label' => __( 'Account Number:', 'wp-travel-engine' ),
							'value' => $bank_detail['account_number'],
						),
						'sort_code'      => array(
							'label' => __( 'Sort Code:', 'wp-travel-engine' ),
							'value' => $bank_detail['sort_code'],
						),
						'iban'           => array(
							'label' => __( 'IBAN:', 'wp-travel-engine' ),
							'value' => $bank_detail['iban'],
						),
						'swift'          => array(
							'label' => __( 'BIC/SWIFT:', 'wp-travel-engine' ),
							'value' => $bank_detail['swift'],
						),
					);
					?>
					<div class="detail-container">
					<?php
					foreach ( $details as $detail ) :
						?>
						<div class="detail-item">
							<strong class="item-label"><?php echo esc_html( $detail['label'] ); ?></strong>
							<span class="value"><?php echo esc_html( $detail['value'] ); ?></span>
						</div>
					<?php endforeach; ?>
					</div>
					<?php
				endforeach;
			}
		);

		add_action(
			'wte_after_thankyou_booking_details_check_payments',
			function() {
				$settings     = get_option( 'wp_travel_engine_settings', array() );
				$instructions = wte_array_get( $settings, 'check_payment.instruction', '' );
				?>
				<div class="wte-bank-transfer-instructions">
					<?php echo wp_kses_post( $instructions ); ?>
				</div>
				<?php
			}
		);

		/**
		 * Adds {trip_code} booking mail tags.
		 *
		 * @TODO: Remove later.
		 */
		add_filter(
			'wte_booking_mail_tags',
			function( $tags, $payment_id ) {
				if ( ! function_exists( 'wte_tc_get_trip_code' ) ) {
					return $tags;
				}
				$payment    = wte_get_payment( $payment_id );
				$booking_id = get_post_meta( $payment->ID, 'booking_id', true );
				$booking    = get_post( $booking_id );
				if ( isset( $booking->order_trips ) ) {
					$trips = $booking->order_trips;
					$trip  = array_shift( $trips );
				}
				if ( $payment ) {
					$tags['{trip_code}'] = wte_tc_get_trip_code( $trip['ID'] );
				}
				return $tags;
			},
			11,
			2
		);
		$this->init_shortcodes();
	}

	/**
	 * Init Shortcode for Order Confirmation
	 *
	 */
	public function init_shortcodes() {
		$plugin_shortcode = new \Wp_Travel_Engine_Order_Confirmation();
		$plugin_shortcode->init();
	}

	/**
	 * Inject remove query params script into head.
	 *
	 * @since 5.2.0
	 */
	public static function remove_tokened_query_params() {
		?>
		<script type="text/javascript">
			;(function() {
				if(window.history) {
					history.replaceState( null, window.title, window.location.href.replace(window.location.search, '') )
				}
			})();
		</script>
		<?php
	}

	/**
	 * Gets booking Info by booking ID.
	 *
	 * @param null|int $booking_id Booking ID.
	 */
	public static function get_booking_info_by_id( $booking_id = null ) {
		if ( ! $booking_id ) {
			return $booking_id;
		}

		$booking = get_post( $booking_id );

		if ( ! is_null( $booking ) && 'booking' === $booking->post_type ) {
			return $booking;
		}
		return null;
	}

	/**
	 * Gets Token URL.
	 *
	 * @param string $action Action.
	 * @param array  $data Data.
	 * @param string $base_url Base URL.
	 *
	 * @return string URL.
	 */
	public static function get_tokened_url( string $action, array $data, $base_url = '' ) {
		if ( empty( $base_url ) ) {
			$base_url = apply_filters( "wte_tokened_{$action}_base_url", home_url( '/' ) );
		}
		$nonce = 'wtetests';

		if ( ! WP_TRAVEL_ENGINE_PAYMENT_DEBUG ) {
			$nonce = wp_create_nonce( "wte_{$action}_nonce" );
		}

		$token = wte_jwt( $data, $nonce );

		set_transient( "wte_token_{$action}", $nonce );

		return add_query_arg(
			array(
				'_action' => $action,
				'_token'  => $token,
			),
			$base_url
		);
	}

	/**
	 * Compatibility Callbacks for Payement Gateways.
	 *
	 * @return void
	 */
	public static function payment_gateway_callback_backward_compatibility() {

		// Callback requests from payment gateways
		$payload = wte_clean( wp_unslash( $_REQUEST ) );

		// Midtrans
		if ( wte_array_get( $payload, 'custom_field1', false ) === 'midtrans' ) {

			try {
				$notif              = new \Midtrans\Notification();
				$transaction_status = $notif->transaction_status;
				$transaction_id     = $notif->transaction_id;
				$type               = $notif->payment_type;
				$order_id           = $notif->order_id;
				$fraud_status       = $notif->fraud_status;

				$booking_id = $notif->order_id;

				$booking = get_post( $booking_id );
				if ( is_null( $booking ) ) {
					return;
				}
				$payments = $booking->payments;
				if ( ! is_array( $payments ) ) {
					return;
				}
				$payment_id = $payments[0];
				if ( count( $payments ) > 1 ) {
					$payment_id = $payments[1];
				}

				update_post_meta( $payment_id, 'payment_status', $transaction_status );
				update_post_meta( $payment_id, 'gateway_response', $notif );

				if ( 'capture' === $transaction_status && 'credit_card' === $type ) {
					if ( 'challenge' === $fraud_status ) {
						$payment_status = __( 'Challenge', 'wp-travel-engine' );
					} else {
						update_post_meta( $booking->ID, 'wp_travel_engine_booking_status', 'booked' );
						update_post_meta(
							$payment_id,
							'payment_amount',
							array(
								'value'    => $notif->gross_amount,
								'currency' => $notif->currency,
							)
						);
						$paid_amount = get_post_meta( $booking->ID, 'paid_amount', true );
						$due_amount  = get_post_meta( $booking->ID, 'due_amount', true );
						update_post_meta( $booking->ID, 'paid_amount', +$notif->gross_amount + +$paid_amount );
						update_post_meta( $booking->ID, 'due_amount', +$due_amount - +$notif->gross_amount );
						self::send_emails( $payment_id, 'order_confirmation', 'all' );
					}
				}
			} catch ( \Exception $e ) {

			}
		}

		// Payhere.
		if ( wte_array_get( $payload, 'wte_gateway', false ) && 'payfast' === $payload['wte_gateway'] && isset( $payload['booking_id'] ) ) {
			$booking_id = $payload['booking_id'];
			$booking    = get_post( $booking_id );
			if ( is_null( $booking ) ) {
				return;
			}
			$payments = $booking->payments;
			if ( ! is_array( $payments ) ) {
				return;
			}
			$payment_id = $payments[0];
			if ( count( $payments ) > 1 ) {
				$payment_id = $payments[1];
			}
			wp_redirect( self::get_thankyou_url( $booking->ID, $payment_id, 'payfast_enable' ) );
			exit;
		}

		if ( wte_array_get( $payload, 'wte_payment', false ) && 'wte_gateway_payfast' === $payload['wte_payment'] ) {
			if ( ! isset( $payload['custom_str3'] ) ) {
				return;
			}
			$booking_id = $payload['custom_str3'];
			$booking    = get_post( $booking_id );
			if ( is_null( $booking ) ) {
				return;
			}
			$payments   = $booking->payments;
			$payment_id = $payments[0];
			if ( count( $payments ) > 1 ) {
				$payment_id = $payments[1];
			}
			$payment = get_post( $payment_id );
			update_post_meta( $payment->ID, 'gateway_response', $payload );
			update_post_meta( $payment->ID, 'payment_status', strtolower( $payload['payment_status'] ) );
			if ( 'complete' === strtolower( $payload['payment_status'] ) ) {
				$amount      = $payload['amount_gross'];
				$due_amount  = get_post_meta( $booking->ID, 'due_amount', true );
				$paid_amount = get_post_meta( $booking->ID, 'paid_amount', true );
				update_post_meta( $booking->ID, 'wp_travel_engine_booking_status', 'booked' );
				update_post_meta( $booking->ID, 'due_amount', +$due_amount - +$amount );
				update_post_meta( $booking->ID, 'paid_amount', +$paid_amount + +$amount );
				update_post_meta(
					$payment->ID,
					'payment_amount',
					array(
						'value'    => $amount,
						'currency' => 'ZAR',
					)
				);
				self::send_emails( $payment->ID, 'order_confirmation', 'all' );
			}
		}

		if ( wte_array_get( $payload, 'wte_payment', false ) && 'wte_gateway_payhere' === $payload['wte_payment'] ) {
			$booking_id = $payload['order_id'];

			$verified = true;
			if ( $booking_id ) {
				try {
					$booking  = get_post( $booking_id );
					$payments = $booking->payments;

					$merchant_secret = wte_array_get( get_option( 'wp_travel_engine_settings', array() ), 'payhere_merchant_secret', false );

					$payment = $payments[0];
					if ( count( $payments ) > 1 ) {
						$payment = get_post( $payments[1] );
					}

					if ( $merchant_secret ) {
						$hash    = wte_array_get( $payload, 'merchant_id', '' );
						$hash   .= wte_array_get( $payload, 'order_id', '' );
						$hash   .= wte_array_get( $payload, 'payhere_amount', '' );
						$hash   .= wte_array_get( $payload, 'payhere_currency', '' );
						$hash   .= wte_array_get( $payload, 'status_code', '' );
						$hash   .= strtoupper( md5( $merchant_secret ) );
						$md5hash = strtoupper( md5( $hash ) );
						$md5sig  = $payload['md5sig'];

						$verified = ( $md5hash === $md5sig ) && ( strtolower( $payload['merchant_id'] ) === strtolower( $merchant_id ) ) && ( +$payload['payhere_amount'] === +( $payment->payable['amount'] ) );
					}

					if ( $verified ) {
						$status = $payload['status_code'];
						switch ( $status ) {
							case '2':
								self::update_booking(
									$payment->ID,
									array(
										'meta_input' => array(
											'payment_status'   => 'completed',
											'gateway_response' => $payload,
											'payment_amount'   => array(
												'value'    => wte_array_get( $payload, 'payhere_amount', 0 ),
												'currency' => wte_array_get( $payload, 'payhere_currency', '' ),
											),
										),
									)
								);

								self::update_booking(
									$booking->ID,
									array(
										'meta_input' => array(
											'wp_travel_engine_booking_status' => 'booked',
											'paid_amount' => +$booking->paid_amount + +wte_array_get( $payload, 'payhere_amount', 0 ),
											'due_amount'  => +$booking->due_amount - +wte_array_get( $payload, 'payhere_amount', 0 ),
										),
									)
								);
								self::send_emails( $payment->ID, 'order_confirmation', 'all' );
								break;
							case '0':
								update_post_meta( $payment->ID, 'payment_status', 'pending' );
								break;
							default:
								update_post_meta( $payment->ID, 'payment_status', 'canceled' );
								update_post_meta( $booking->ID, 'wp_travel_engine_booking_status', 'canceled' );
								break;
						}
					} else {
						update_post_meta( $payment->ID, 'payment_status', 'verification_failed' );
						update_post_meta( $payment->ID, 'payment_remarks', __( 'Failed to verify signature.', 'wp-travel-engine' ) );
					}
				} catch ( \Exception $e ) {
					$msg = 'Error';
				}
				update_post_meta( $payment->ID, 'gateway_response', $payload );
				return;
			}
			update_post_meta( $payment->ID, 'gateway_response', $payload );
		}

		if ( isset( $_GET['payu_in_callback'] ) && esc_attr( $_GET['payu_in_callback'] ) == '1' ) { // phpcs:ignore
			$booking_id = ! empty( $payload['booking_id'] ) ? wte_clean( wp_unslash( $payload['booking_id'] ) ) : 0;
			$booking    = get_post( $booking_id );
			if ( is_null( $booking ) ) {
				return;
			}
			$payments   = $booking->payments;
			$payment_id = $payments[0];
			if ( count( $payments ) > 1 ) {
				$payment_id = $payments[1];
			}
			$payment = get_post( $payment_id );

			update_post_meta( $payment->ID, 'gateway_response', wte_clean( wp_unslash( $payload ) ) );

			if ( wte_array_get( $payload, 'status', false ) ) {
				$status = wte_clean( wp_unslash( $payload['status'] ) );
				update_post_meta( $payment->ID, 'payment_status', $status );

				if ( 'success' === $status ) {
					update_post_meta(
						$payment->ID,
						'payment_amount',
						array(
							'value'    => wte_array_get( $payload, 'amount', 0 ),
							'currency' => 'INR',
						)
					);
					self::update_booking(
						$booking->ID,
						array(
							'meta_input' => array(
								'wp_travel_engine_booking_status' => 'booked',
								'paid_amount' => +$booking->paid_amount + (float) wte_array_get( $payload, 'amount', 0 ),
								'due_amount'  => +$booking->due_amount - (float) wte_array_get( $payload, 'amount', 0 ),
							),
						)
					);
					self::send_emails( $payment->ID, 'order_confirmation', 'all' );
				}
				wp_redirect( self::get_thankyou_url( $booking->ID, $payment->ID, 'payu_enable' ) );
				exit;
			}
		}
	}

	/**
	 * Get Success URL.
	 *
	 * @param int    $booking_id Booking ID.
	 * @param int    $payment_id Payemnt ID.
	 * @param string $gateway Gateway Name.
	 *
	 * @return string Success URL.
	 */
	public static function get_success_url( $booking_id, $payment_id, $gateway = '' ) {
		return self::get_tokened_url(
			'success',
			array(
				'bid'      => $booking_id,
				'pid'      => $payment_id,
				'_gateway' => $gateway,
			)
		);
	}

	/**
	 * Get Notification URL.
	 *
	 * @param int    $booking_id Booking ID.
	 * @param int    $payment_id Payemnt ID.
	 * @param string $gateway Gateway Name.
	 *
	 * @return string Notification URL.
	 */
	public static function get_notification_url( $booking_id, $payment_id, $gateway = '' ) {
		return self::get_tokened_url(
			'notification',
			array(
				'bid'      => $booking_id,
				'pid'      => $payment_id,
				'_gateway' => $gateway,
			)
		);
	}

	/**
	 * Get Return URL.
	 *
	 * @param int    $booking_id Booking ID.
	 * @param int    $payment_id Payemnt ID.
	 * @param string $gateway Gateway Name.
	 *
	 * @return string Return URL.
	 */
	public static function get_return_url( $booking_id, $payment_id, $gateway = '' ) {
		return self::get_thankyou_url( $booking_id, $payment_id, $gateway );
	}

	/**
	 * Get Cancel URL.
	 *
	 * @param int    $booking_id Booking ID.
	 * @param int    $payment_id Payemnt ID.
	 * @param string $gateway Gateway Name.
	 *
	 * @return string Cancel URL.
	 */
	public static function get_cancel_url( $booking_id, $payment_id, $gateway = '' ) {
		return self::get_tokened_url(
			'cancel',
			array(
				'bid'      => $booking_id,
				'pid'      => $payment_id,
				'_gateway' => $gateway,
			)
		);
	}

	/**
	 * Get Thank You URL.
	 *
	 * @param int    $booking_id Booking ID.
	 * @param int    $payment_id Payemnt ID.
	 * @param string $gateway Gateway Name.
	 *
	 * @return string Thank You URL.
	 */
	public static function get_thankyou_url( $booking_id, $payment_id, $gateway = '' ) {
		$payment_mode = ( isset( $_POST['wp_travel_engine_payment_mode'] ) ) ? sanitize_text_field( wp_unslash( $_POST['wp_travel_engine_payment_mode'] ) ) : 'full_payment'; // phpcs:ignore

		if ( 'remaining_payment' === $payment_mode ) {
			return self::get_tokened_url(
				'thankyou',
				array(
					'bid'      => $booking_id,
					'pid'      => $payment_id,
					'_gateway' => $gateway,
				)
			);
		} else {
			return self::get_tokened_url(
				'thankyou',
				array(
					'bid'      => $booking_id,
					'pid'      => $payment_id,
					'_gateway' => $gateway,
				),
				wp_travel_engine_get_booking_confirm_url()
			);
		}

	}

	/**
	 * Migrate data to new booking data structure until payment addons updates.
	 *
	 * @param int    $payment_id Payment ID.
	 * @param string $type Payment Type.
	 * @param string $gateway Gateway Name.
	 * @return void
	 */
	public function map_payment_data_to_new_booking_structure( $payment_id, $type = 'full_payment', $gateway = '' ) {

		if ( ! $payment_id ) {
			return;
		}

		$booking = get_post( get_post_meta( $payment_id, 'booking_id', true ) );

		$payment_process = array(
			'paypalexpress_enable' => function() {
				if ( isset( $_POST['wte_paypal_express_payment_details'] ) ) { // phpcs:ignore
					$response = wte_clean( json_decode( wp_unslash( $_POST['wte_paypal_express_payment_details'] ),  true ) ); // phpcs:ignore
					$payment_data = array();
					if ( $response && isset( $response['status'] ) ) {
						$payment_data = array(
							'payment_status' => strtolower( $response['status'] ),
						);

						if ( isset( $response['intent'] ) ) {
							$payment_data['payment_intent'] = $response['intent'];
						}

						if ( isset( $response['purchase_units'][0] ) ) {
							$payment_amount['value'] = $response['purchase_units'][0]['amount']['value'];
							$payment_amount['currency'] = $response['purchase_units'][0]['amount']['currency_code'];
							$payment_data['payment_amount'] = $payment_amount;
						}
					}
					$payment_data['gateway_response'] = $response;
					return $payment_data;
				}
			},
			'payhere_payment'      => function() use ( $payment_id ) {
				return array(
					'payment_status'       => 'pending',
					'not_confirmed_amount' => get_post( $payment_id )->payable['amount'],
					'payment_remarks'      => 'Payment request has been set and need confirmation.',
				);
			},
			'stripe_payment'       => function() {
				$pi_id = '';

				if ( isset( $_REQUEST['stripeToken'] ) ) {
					$pi_id = sanitize_text_field( wp_unslash( $_REQUEST['stripeToken'] ) );

					if ( defined( 'WTE_STRIPE_GATEWAY_BASE_PATH' ) ) {
						require_once WTE_STRIPE_GATEWAY_BASE_PATH . '/includes/stripe-php/init.php';
					}

					$sk = wte_array_get( get_option( 'wp_travel_engine_settings' ), 'stripe_secret', '' );

					if ( empty( $sk ) ) {
						wp_die(
							new \WP_Error(
								'WTE_BOOKING_ERROR',
								__( 'Empty Stripe secret key.', 'wp-travel-engine' )
							)
						);
					}

					$stripe = \Stripe\Stripe::setApiKey( $sk );

					$response = \Stripe\PaymentIntent::retrieve( $pi_id );

					$response = $response->toArray();

					return array(
						'gateway_response' => $response,
						'payment_status'   => $response['amount'] === $response['amount_received'] ? 'captured' : 'completed',
						'payment_amount'   => array(
							'value'    => +$response['amount'] / 100,
							'currency' => $response['currency'],
						),
					);
				}

			},
			'payu_money_enable'    => function() {

				if ( ! empty( wte_array_get( $_REQUEST, 'wp_travel_engine_booking_setting.place_order.payment.status', '' ) ) ) {
					$response = wte_clean( wte_array_get( $_REQUEST, 'wp_travel_engine_booking_setting.place_order.payment', array() ) );
					$payment_status = sanitize_text_field( strtolower( wte_array_get( $_REQUEST, 'wp_travel_engine_booking_setting.place_order.payment.status', '' ) ) );
					$payment_amount = array(
						'value'         => get_post( $payment_id )->payable['amount'],
						'currency_code' => 'INR',
					);
					return array(
						'gateway_response' => $response,
						'payment_status'   => $payment_status,
						'payment_amount'   => $payment_amount,
					);
				}
			},
			'payu_enable'          => function() use ( $payment_id ) {
				return array(
					'payment_status'       => 'pending',
					'not_confirmed_amount' => get_post( $payment_id )->payable['amount'],
					'payment_remarks'      => 'Payment request has been set and need confirmation.',
				);
			},
		);

		if ( ! isset( $payment_process[ $gateway ] ) ) {
			return;
		}

		$payment_data = call_user_func( $payment_process[ $gateway ] );

		self::update_booking(
			$payment_id,
			array(
				'meta_input' => $payment_data,
			)
		);

		if ( in_array( $payment_data['payment_status'], array( 'completed', 'success', 'captured' ), false ) ) {
			$amount = $payment_data['payment_amount']['value'];
			$booking_meta['wp_travel_engine_booking_status'] = 'booked';
			$booking_meta['paid_amount']                     = +$booking->paid_amount + +$amount;
			$booking_meta['due_amount']                      = +$booking->due_amount - +$amount;
			self::update_booking( $booking->ID, array( 'meta_input' => $booking_meta ) );
			self::send_emails( $payment_id, 'order_confirmation', 'all' );
		}
	}

	/**
	 * Get callback token payload. Decode and parse token data.
	 *
	 * @param string $action Action Name.
	 */
	public static function get_callback_token_payload( $action ) {
		if ( ! isset( $_REQUEST['_action'] ) || ! isset( $_REQUEST['_token'] ) ) {
			return false; // Back off.
		}

		$types = array( // @TODO: apply_filters here.
			'success',
			'cancel',
			'notification',
			'thankyou',
		);

		$action = sanitize_text_field( wp_unslash( $_REQUEST['_action'] ) );
		$token  = sanitize_text_field( wp_unslash( $_REQUEST['_token'] ) );

		if ( ! isset( $action ) || ! in_array( $action, $types, true ) ) {
			return false;
		}
		$token_key = get_transient( "wte_token_{$action}" );

		if ( ! $token_key ) {
			return false;
		}

		$payload = array();
		try {
			return (array) wte_jwt_decode( $token, $token_key );
		} catch ( \Exception $e ) {
			return false;
		}
		return $payload;
	}

	/**
	 * Callback Listener.
	 */
	public static function payment_gateway_callback_listener() {

		if ( isset( $_REQUEST['action'], $_REQUEST['_gateway'] ) ) {
			do_action( 'wte_callback_for_' . $_REQUEST['_gateway'] . '_' . $_REQUEST['action'] );
		}



		if ( ! isset( $_REQUEST['_action'] ) || ! isset( $_REQUEST['_token'] ) ) {
			return; // Back off.
		}

		$action = sanitize_text_field( wp_unslash( $_REQUEST['_action'] ) );

		$payload = self::get_callback_token_payload( $action );

		if ( ! $payload ) {
			wp_redirect( home_url( '/' ) );
			exit;
		}

		$callbacks = array(
			'cancel'   => function( $data ) {
				$booking_id = isset( $data['bid'] ) ? $data['bid'] : 0;
				$payment_id = isset( $data['pid'] ) ? $data['pid'] : 0;

				$callback_function = apply_filters(
					'wte_gateway_cancel_callback',
					function( $booking_id, $payment_id ) {
						delete_transient( 'wte_token_cancel' );

						\WPTravelEngine\Core\Booking::update_booking(
							$booking_id,
							array(
								'meta_input' => array(
									'wp_travel_engine_booking_status' => 'canceled',
									'wp_travel_engine_booking_payment_status' => 'canceled',
								),
							)
						);
						update_post_meta( $payment_id, 'payment_status', 'canceled' );
					},
					$booking_id,
					$payment_id
				);

				if ( is_callable( $callback_function ) ) {
					call_user_func( $callback_function, $booking_id, $payment_id );
				}
			},
			'success'  => function( $data ) {
				$booking_id = isset( $data['bid'] ) ? $data['bid'] : 0;
				$payment_id = isset( $data['pid'] ) ? $data['pid'] : 0;

				$callback_function = apply_filters(
					'wte_gateway_success_callback',
					function( $booking_id, $payment_id ) {
						delete_transient( 'wte_token_success' );

						$payable = get_post_meta( $payment_id, 'payable', true );
						\WPTravelEngine\Core\Booking::update_booking(
							$booking_id,
							array(
								'meta_input' => array(
									'wp_travel_engine_booking_status' => 'completed',
									'wp_travel_engine_booking_payment_status' => 'completed',
								),
							)
						);
						\WPTravelEngine\Core\Booking::update_booking(
							$payment_id,
							array(
								'meta_input' => array(
									'payment_status' => 'success',
									'payment_amount' => array(
										'value'    => $payable['amount'],
										'currency' => $payable['currency'],
									),
								),
							)
						);
					},
					$booking_id,
					$payment_id
				);
				if ( is_callable( $callback_function ) ) {
					call_user_func( $callback_function, $booking_id, $payment_id );
				}
			},
			'thankyou' => function( $data ) {
			},
		);

		if ( ! isset( $callbacks[ $action ] ) ) {
			return;
		}

		add_action( 'wp_head', array( __CLASS__, 'remove_tokened_query_params' ) );

		call_user_func( $callbacks[ $action ], $payload );

		if ( isset( $payload['_gateway'] ) ) {
			/**
			 * Save response or update payment satuses if needed.
			 */
			do_action( "wte_callback_for_{$payload['_gateway']}_{$action}", $payload );
		}
	}

	/**
	 * Process booking only request.
	 *
	 * @param int    $payment_id Payment ID.
	 * @param string $type Type.
	 * @param string $payment_method Payment Method.
	 */
	public function booking_only( $payment_id, $type, $payment_method ) {
		$booking_id = get_post_meta( $payment_id, 'booking_id', true );
		// Payment Status
		update_post_meta( $booking_id, 'wp_travel_engine_booking_status', 'booked' );
		update_post_meta( $booking_id, 'wp_travel_engine_booking_payment_gateway', __( 'Booking Only', 'wp-travel-engine' ) );

		$cart_info = get_post_meta( $booking_id, 'cart_info', true );

		$payable = get_post_meta( $payment_id, 'payable', true );

		$payable['amount'] = $cart_info['total'];

		update_post_meta( $payment_id, 'payable', $payable );

		$cart_info['cart_partial'] = 0;
		self::update_booking(
			$booking_id,
			array(
				'meta_input' => array(
					'cart_info'  => $cart_info,
					'due_amount' => $cart_info['total'],
				),
			)
		);
	}

	/**
	 * Handle Direct Bank Transfer.
	 *
	 * @param int     $booking_id Booking ID.
	 * @param boolean $due_payment IS Due payment?
	 * @return void
	 */
	public function direct_bank_transfer( $payment_id, $type, $payment_method ) {
		$booking_id = get_post_meta( +$payment_id, 'booking_id', true );

		$booking = get_post( $booking_id );

		// Booking Metas.
		update_post_meta( $booking_id, 'wp_travel_engine_booking_payment_gateway', __( 'Direct Bank Transfer', 'wp-travel-engine' ) );
		update_post_meta( $booking_id, 'wp_travel_engine_booking_payment_status', 'voucher-waiting' );

		// Payment Metas.
		self::update_booking(
			+$payment_id,
			array(
				'meta_input' => array(
					'payment_status'  => 'voucher-waiting',
					'payment_gateway' => 'direct_bank_transfer',
				),
			)
		);
	}

	public function check_payments( $payment_id, $type, $method ) {
		$payment_ids = get_post_meta( +$booking_id, 'payments', true );
		if ( ! is_array( $payment_ids ) ) {
			$payment_ids = array();
		}

		update_post_meta( $booking_id, 'wp_travel_engine_booking_payment_gateway', __( 'Check Payment', 'wp-travel-engine' ) );
		update_post_meta( $booking_id, 'wp_travel_engine_booking_payment_status', 'check-waiting' );

		// payment Metas.
		self::update_booking(
			+$payment_id,
			array(
				'meta_input' => array(
					'payment_status'  => 'check-waiting',
					'payment_gateway' => 'check_payments',
				),
			)
		);

	}

	public static function create_payment( $booking_id, $meta_input = array(), $type = 'full_payment' ) {

		$booking = get_post( $booking_id );

		$amounts = array(
			'partial'      => $booking->cart_info['cart_partial'],
			'full_payment' => $booking->cart_info['total'],
			'due'          => +$booking->cart_info['total'] - +$booking->cart_info['cart_partial'],
		);

		$payment = array(
			'currency' => $booking->cart_info['currency'],
			'amount'   => $amounts[ $type ],
		);

		$postarr = new \stdClass();

		$postarr->meta_input = wp_parse_args(
			$meta_input,
			array(
				'payment_status' => 'pending',
				'billing_info'   => get_post_meta( $booking_id, 'billing_info', true ),
				'payable'        => $payment,
			)
		);

		$payment_types = apply_filters(
			'wte_payment_modes_and_titles',
			array(
				'full_payment' => "Payment for booking #{$booking_id}",
				'partial'      => "Partial payment of booking #{$booking_id}",
				'due'          => "Due payment of booking #{$booking_id}",
				'installment'  => "Payment of booking #{$booking_id} - #1",
			)
		);

		$payment_id = wp_insert_post(
			wp_parse_args(
				$postarr,
				array(
					'post_type'   => 'wte-payments',
					'post_status' => 'publish',
					'post_title'  => $payment_types[ $type ],
				)
			)
		);

		return $payment_id;

	}

	private static function redirect( $payment_id, $booking_id = null ) {
		if ( is_null( $booking_id ) ) {
			$booking_id = get_post_meta( $payment_id, 'booking_id', true );
		}

		// Redirect to the traveller's information page.
		wp_safe_redirect( self::get_thankyou_url( $booking_id, $payment_id ) );
		exit;
	}

	public static function error( $message, $title = '', $args = array(), $booking_id = null ) {
		if ( $booking_id ) {
			wp_trash_post( $booking_id );
		}
		if ( empty( $title ) ) {
			$title = __( 'WP Travel Engine - Booking Error', 'wp-travel-engine' );
		}
		wp_die( $message, $title, $args ); // phpcs:ignore
	}

	/**
	 * Send Booking Emails.
	 *
	 * @param int    $payment_id Payment ID.
	 * @param string $email_template_type Email Template Type [order|order_confirmation].
	 * @param string $to whom to send [admin|customer|all]
	 * @return void
	 */
	public static function send_emails( $payment_id, $email_template_type = 'order', $to = 'all' ) {

		if ( in_array( $to, array( 'customer', 'admin' ) ) ) {
			wte_booking_email()->prepare( +$payment_id, $email_template_type )->to( $to )->send();
		}
		if ( 'all' === $to ) {
			if ( wte_array_get( get_option( 'wp_travel_engine_settings', array() ), 'disable_notif', false ) !== '1' ) {
				wte_booking_email()->prepare( +$payment_id, $email_template_type )->to( 'admin' )->send();
			}
			wte_booking_email()->prepare( +$payment_id, $email_template_type )->to( 'customer' )->send();
		}
	}

	/**
	 * Verify Nonce.
	 *
	 * @since 4.3.0
	 * @return boolean
	 */
	private function is_valid_nonce() {
		return ( isset( $_POST['wp_travel_engine_new_booking_process_nonce'] ) ) && ( ! ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wp_travel_engine_new_booking_process_nonce'] ) ), 'wp_travel_engine_new_booking_process_nonce_action' ) );
	}

	/**
	 * Checks if the current request is Booking Request.
	 *
	 * @return boolean
	 */
	private function is_booking_request() {
		return $this->is_valid_nonce() && ( isset( $_POST['action'] ) ) && ( 'wp_travel_engine_new_booking_process_action' === $_POST['action'] ); // phpcs:ignore
	}

	/**
	 * Process booking made from checkout page.
	 *
	 * @return void
	 */
	public function booking_process() {
		// Booking Processing starts.
		if ( defined( 'WTE_BOOKING_PROCESSING' ) ) {
			return;
		}

		// Start booking process.
		define( 'WTE_BOOKING_PROCESSING', true );

		if ( $this->may_be_cart_is_empty() ) {
			return; // Nothing to process.
		}

		// Insert booking first as a reference.
		$this->trip_booking = new Trip\Booking();

		$this->trip_booking->create();

		if ( ! $this->trip_booking->ID ) {
			return; // Couldn't create reference booking post.
		}
		$booking_id = $this->trip_booking->ID;
		/**
		 * @action_hook wte_created_user_booking
		 *
		 * @since 2.2.0
		 */
		do_action( 'wte_after_booking_created', $booking_id );

		$this->booking = $this->trip_booking->post;

		global $wte_cart;

		$this->cart = $wte_cart;

		$payment_method = ( ! empty( $_POST['wpte_checkout_paymnet_method'] ) ) ? sanitize_text_field( wp_unslash( $_POST['wpte_checkout_paymnet_method'] ) ) : '';

		$payment_method = apply_filters( 'wptravelengine_checkout_payment_method', $payment_method, $booking_id );

		$billing = $this->prepare_billing_info();

		$this->update_billing_info( $billing );

		$this->update_order_items();

		wp_update_post(
			array(
				'ID'          => $this->booking->ID,
				'post_title'  => "Booking #{$this->booking->ID}",
				'post_status' => 'publish',
			)
		);

		$this->update_payment_info();

		$this->prepare_legacy_order_metas();

		$payment_mode = ( isset( $_POST['wp_travel_engine_payment_mode'] ) ) ? sanitize_text_field( wp_unslash( $_POST['wp_travel_engine_payment_mode'] ) ) : 'full_payment';

		$this->trip_booking->update_legacy_order_meta( $this->legacy_order_metas );

		// Save Customer.
		$wte_order_confirmation_instance = new \Wp_Travel_Engine_Order_Confirmation();
		$wte_order_confirmation_instance->insert_customer( $this->legacy_order_metas );

		// Set Payment.
		$payment_id = self::create_payment(
			$this->booking->ID,
			array(
				'booking_id'      => $this->booking->ID,
				'payment_gateway' => $payment_method,
			),
			$payment_mode
		);

		$this->trip_booking->update_booking_meta( 'payments', array( $payment_id ) );

		// Maybe update Coupon
		$discounts = $this->cart->get_discounts();
		if ( is_array( $discounts ) ) {
			foreach ( $discounts as $discount ) {
				$coupon_id = \WPTravelEngine\Modules\CouponCode::coupon_id_by_code( $discount['name'] );
				if ( $coupon_id ) {
					\WPTravelEngine\Modules\CouponCode::update_usage_count( $coupon_id );
				}
			}
		}

		/**
		 *
		 * Update Inventory.
		 *
		 * @since 5.5.2
		 */
		$inventory = new Booking_Inventory();

		$inventory->update_inventory_by_booking( $this->booking );

		// Send Notification Emails.
		self::send_emails( $payment_id );


		/**
		 * Recommended for WTE Payment Addons.
		 *
		 * @since 4.3.0
		 */
		do_action( "wte_payment_gateway_{$payment_method}", $payment_id, $payment_mode, $payment_method );

		/**
		 * Hook to handle payment process
		 *
		 * @since 2.2.8
		 * @TODO: Remove on later update.
		 */
		do_action( 'wp_travel_engine_after_booking_process_completed', $booking_id );
		do_action( 'wp_travel_engine_booking_completed_with_post_parameter', wte_clean( wp_unslash( $_POST ) ) ); // phpcs:ignore

		// Redirect if not redirected till this point.
		if ( apply_filters( 'wptravelengine_redirect_after_booking', true ) ) {
			self::redirect( $payment_id, $booking_id );
		}
	}

	/**
	 * Undocumented function
	 *
	 * @since 4.3.0
	 * @return void
	 */
	public function init() {

		// Add meta for bookings.
		foreach ( array(
			'wp_travel_engine_booking_status',
			'wp_travel_engine_booking_payment_status',
			'wp_travel_engine_booking_payment_method',
			'billing_info',
			'cart_info',
			'payments',
			'order_items',
			'paid_amount',
			'due_amount',
		) as $meta_key ) {
			register_meta(
				'post',
				$meta_key,
				array(
					'object_subtype' => 'booking',
				)
			);
		}

		register_post_type(
			'wte-payments',
			array(
				'label'               => __( 'Payments', 'wp-travel-engine' ),
				'public'              => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'show_in_menu'        => false,
				'show_in_nav_menus'   => false,
			)
		);

		foreach ( array(
			'payment_status',
			'billing_info',
			'payable',
			'billing_info',
			'payment_gateway',
			'payment_intent',
		) as $meta_key ) {
			register_meta(
				'post',
				$meta_key,
				array(
					'object_subtype' => 'wte-payments',
				)
			);
		}

		// Booking Processing starts.
		if ( $this->is_booking_request() ) {
			$this->booking_process();
			return;
		}

		// If Dashboard Payment.
		if ( $this->is_due_payment_request() ) {
			$this->due_payment_process();
		}

	}

	private function is_due_payment_request() {
		return isset( $_POST['nonce_checkout_partial_payment_remaining_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce_checkout_partial_payment_remaining_field'] ) ), 'nonce_checkout_partial_payment_remaining_action' );
	}

	public function due_payment_process() {

		// Booking Processing starts.
		if ( defined( 'WTE_PARTIAL_BOOKING_PROCESSING' ) ) {
			return;
		}

		// Start booking process.
		define( 'WTE_PARTIAL_BOOKING_PROCESSING', true );

		$booking_id = 0;
		if ( isset( $_REQUEST['booking_id'] ) ) {
			$booking_id = (int) $_REQUEST['booking_id'];
		}

		$this->booking = get_post( $booking_id );

		if ( is_null( $this->booking ) ) {
			self::error(
				new \WP_Error( 'WTE_INVALID_BOOKING_ID', __( 'Invalid Booking ID', 'wp-travel-engine' ) ),
				'',
				array(
					'back_link' => true,
				)
			);
			return;
		}

		do_action( 'wp_travel_engine_before_remaining_payment_process', $booking_id );

		$payment_method = ( ! empty( $_POST['wpte_checkout_paymnet_method'] ) ) ? sanitize_text_field( wp_unslash( $_POST['wpte_checkout_paymnet_method'] ) ) : '';

		$payments = (array) $this->booking->payments;

		$payment_id = self::create_payment(
			$this->booking->ID,
			array( 'booking_id' => $this->booking->ID ),
			'due'
		);

		$payments[] = $payment_id;

		update_post_meta( $this->booking->ID, 'payments', $payments );

		// Send Notification Emails.
		self::send_emails( $payment_id );

		/**
		 * Recommended for WTE Payment Addons.
		 *
		 * @since 4.3.0
		 */
		do_action( "wte_payment_gateway_{$payment_method}", $payment_id, 'due', $payment_method );

		do_action( 'wp_travel_engine_after_remaining_payment_process_completed', $booking_id );

		// Redirect if not redirected till this point.
		self::redirect( $payment_id, $booking_id );
	}

	public static function update_booking( $booking_id, $postarr ) {
		if ( isset( $postarr['meta_input'] ) && is_array( $postarr['meta_input'] ) ) {
			foreach ( $postarr['meta_input'] as $meta_key => $meta_value ) {
				update_post_meta( $booking_id, $meta_key, $meta_value );
			}
		}
	}

	/**
	 * Create new booking post for reference.
	 *
	 * @deprecated 5.4.3
	 *
	 * @return int New Post ID.
	 */
	private function insert_booking() {
		return wp_insert_post(
			array(
				'post_status' => 'publish',
				'post_type'   => 'booking',
				'post_title'  => 'booking',
				'meta_input'  => array(
					'wp_travel_engine_booking_payment_status' => 'pending',
					'wp_travel_engine_booking_payment_method' => __( 'N/A', 'wp-travel-engine' ),
					'billing_info'                    => array(),
					'cart_info'                       => array(
						'cart_total'   => 0,
						'cart_partial' => 0,
						'due'          => 0,
					),
					'payments'                        => array(),
					'paid_amount'                     => 0,
					'due_amount'                      => 0,
					'wp_travel_engine_booking_status' => 'pending',
				),
			)
		);

	}

	/**
	 *
	 * Create Booking.
	 *
	 * @since 5.4.0
	 */
	public function create_booking() {
		if ( ! isset( $this->trip_booking ) ) {
			$this->trip_booking = new Trip\Booking();
		}
		$this->trip_booking->create();
		return $this->trip_booking->ID;
	}

	private function may_be_cart_is_empty() {
		global $wte_cart;

		return empty( $wte_cart->getItems() );
	}


	public function update_order_items() {
		$items = $this->cart->getItems();

		$ordered_items = array();
		foreach ( $items as $cartid => $item ) {
			$item = (object) $item;
			$trip = get_post( $item->trip_id );
			$oi   = array();

			$oi['ID']                 = (int) $trip->ID;
			$oi['title']              = $trip->post_title;
			$oi['cost']               = $item->trip_price;
			$oi['partial_cost']       = $item->trip_price_partial;
			$oi['datetime']           = $item->trip_date;
			$oi['multi_pricing_used'] = $item->multi_pricing_used;
			$oi['pax']                = $item->pax;
			$oi['pax_cost']           = $item->pax_cost;
			$oi['has_time']           = false;
			if ( isset( $item->trip_extras ) ) {
				$oi['trip_extras'] = $item->trip_extras;
			}
			if ( ! empty( $item->trip_time ) ) {
				$oi['has_time'] = true;
				$oi['datetime'] = $item->trip_time;
			}
			$oi['_prev_cart_key'] = $cartid;
			$ordered_items[ $cartid ] = $oi;
		}

		$this->trip_booking->update_order_items( $ordered_items );

	}

	public function update_payment_info() {
		$payment_info = array();

		$cart_total = $this->cart->get_total();
		// echo "<pre>";
		// print_r($cart_total);
		// echo "</pre>";

		$payment_method = ( ! empty( $_POST['wpte_checkout_paymnet_method'] ) ) ? sanitize_text_field( wp_unslash( $_POST['wpte_checkout_paymnet_method'] ) ) : '';

		if ( wte_array_get( $_POST, "wte_pg_{$payment_method}_ref", false ) ) {
			update_post_meta( $this->booking->ID, "wte_pg_{$payment_method}_ref", wte_clean( wp_unslash( $_POST[ "wte_pg_{$payment_method}_ref" ] ) ) );
		}

		$payment_info['currency']     = wp_travel_engine_get_currency_code( true );
		$payment_info['subtotal']     = wte_array_get( $cart_total, 'cart_total', 0 );
		$payment_info['total']        = wte_array_get( $cart_total, 'total', 0 );
		$payment_info['cart_partial'] = wte_array_get( $cart_total, 'total_partial', 0 );
		$payment_info['discounts']    = $this->cart->get_discounts();
		$payment_info['tax_amount']  = wte_array_get( $cart_total, 'tax_amount', 0 );

		foreach( array(
			'wp_travel_engine_booking_payment_gateway' => sanitize_text_field( wp_unslash( wte_array_get( $_POST, 'wpte_checkout_paymnet_method', 'N/A' ) ) ),
			'wp_travel_engine_booking_payment_method' => sanitize_text_field( wp_unslash( wte_array_get( $_POST, 'wpte_checkout_paymnet_method', 'N/A' ) ) ),
			'cart_info'   => $payment_info,
			'paid_amount' => 0,
			'due_amount'  => $payment_info['total'],
		) as $meta_key => $value ) {
			$this->trip_booking->update_booking_meta( $meta_key, $value );
		}

	}

	/**
	 * Old meta data to support previous versions.
	 *
	 * @TODO: Doesn't supports multiple cart.
	 * @return array Order Meta.
	 */
	private function prepare_legacy_order_metas() {
		$cart_total = $this->cart->get_total();

		$payment_mode = isset( $_POST['wp_travel_engine_payment_mode'] ) ? sanitize_text_field( wp_unslash( $_POST['wp_travel_engine_payment_mode'] ) ) : 'full_payment';
		$due          = 'partial' === $payment_mode ? +( $cart_total['total'] - $cart_total['total_partial'] ) : 0;
		$total_paid   = 'partial' === $payment_mode ? +( $cart_total['total_partial'] ) : +( $cart_total['total'] );

		$cart_items = $this->cart->getItems();

		$cart_item = array_shift( $cart_items );
		$order_metas = array();

		if ( ! is_null( $cart_item ) ) {
			$pax  = isset( $cart_item['pax'] ) ? $cart_item['pax'] : array();
			$trip = get_post( $cart_item['trip_id'] );

			$order_metas = array(
				'place_order' => array(
					'traveler' => esc_attr( array_sum( $pax ) ),
					'cost'     => esc_attr( $total_paid ),
					'due'      => esc_attr( $due ),
					'tid'      => esc_attr( $cart_item['trip_id'] ),
					'tname'    => esc_attr( $trip->post_title ),
					'datetime' => esc_attr( $cart_item['trip_date'] ),
					'datewithtime' => esc_attr( $cart_item['trip_time'] ),
					'booking'  => $this->prepare_billing_info(),
				),
			);
		}

		$order_metas = array_merge_recursive( $order_metas, array( $this->booking->ID ) );

		$posted_data = wte_clean( wp_unslash( $_POST ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

		foreach ( array(
			'wp_travel_engine_booking_setting',
			'action',
			'_wp_http_referer',
			'wpte_checkout_paymnet_method',
			'wp_travel_engine_nw_bkg_submit',
			'wp_travel_engine_new_booking_process_nonce',
			'wp_travel_engine_payment_mode',
		) as $key ) {
			// Unset metas.
			if ( isset( $posted_data[ $key ] ) ) {
				unset( $posted_data[ $key ] );
			}
		}

		$order_metas['additional_fields'] = $posted_data;

		update_post_meta( $this->booking->ID, 'additional_fields', $posted_data );

		/**
		 * @hook wte_booking_meta
		 *
		 * @since 3.0.7
		 */
		$this->legacy_order_metas = apply_filters( 'wte_before_booking_meta_save', $order_metas, $this->booking->ID );

		return $this->legacy_order_metas;
	}

	/**
	 * Prepares Billing Info.
	 *
	 * @return array Billing info array.
	 */
	private function prepare_billing_info() {

		$current_billing_fields = apply_filters( 'wp_travel_engine_booking_fields_display', \WTE_Default_Form_Fields::booking() );

		$billing = array();

		// @todo Need some work.
		if ( isset( $_REQUEST['wp_travel_engine_booking_setting']['place_order']['booking'] ) ) {
			$billing = wte_clean( wp_unslash( $_REQUEST['wp_travel_engine_booking_setting']['place_order']['booking'] ) );

			foreach ( array_keys( $current_billing_fields ) as $index ) {
				if ( isset( $_REQUEST[ $index ] ) ) {
					$billing[ $index ] = wte_clean( wp_unslash( $_REQUEST[ $index ] ) );
				}
			}
		}

		return $billing;
	}

	/**
	 *
	 * @return void
	 */
	public function update_billing_info( $data = null ) {
		if ( is_null( $data ) ) {
			$data = $this->prepare_billing_info();
		}
		$this->trip_booking->update_billing_info( $data );
	}

	/**
	 * Returns booking cart instance.
	 *
	 * @return void
	 */
	public function get_booking_cart() {
		return $this->cart;
	}

}

\class_alias( 'WPTravelEngine\Core\Booking', 'WTE_Booking' );

function wte_booking_process() {
	return Booking::instance();
}

$GLOBALS['WTEB'] = wte_booking_process();
