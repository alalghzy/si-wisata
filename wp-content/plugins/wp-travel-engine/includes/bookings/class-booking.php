<?php
/**
 *
 * Handles Booking Post-Type.
 *
 * @since 5.5.2
 */

namespace WPTravelEngine\Core\Trip;

class Booking {

	public $ID   = null;
	public $post = null;

	public function __construct( $booking_id = null ) {
		if ( ! is_null( $booking_id ) ) {
			$this->ID   = $booking_id;
			$this->post = get_post( $booking_id );
		}
	}

	protected static $instance = null;

	public function insert_post() {
		return \wp_insert_post(
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

	public function create() {
		$this->ID   = $this->insert_post();
		$this->post = get_post( $this->ID );
	}

	public function get_booking_object() {
		if ( is_null( $this->post ) ) {
			$this->post = get_post( $this->ID );
		}

		return $this->post;
	}

	public function update_booking_meta( $meta_key, $data, $booking_id = null ) {
		if ( is_null( $this->ID ) ) {
			$instance = new self( $booking_id );
		} else {
			$instance = $this;
		}

		update_post_meta( $instance->ID, $meta_key, apply_filters( "wte_before_update_{$meta_key}", $data, $instance->post ) );

		return $instance;

	}

	public static function save_post_booking( $booking_post_id, $booking_post, $update ) {
		$instance = new self( $booking_post_id );
		// Order Trips.
		if ( isset( $_POST['order_trips'] ) && is_array( $_POST['order_trips'] ) ) {

			// phpcs:ignore
			$instance->update_order_items( wp_unslash( $_POST['order_trips'] ), true ); // will be sanitized later.
		}

		// Cart Info.
		if ( isset( $_POST['cart_info'] ) && is_array( $_POST['cart_info'] ) ) {
			$instance->update_cart_info( wp_unslash( $_POST['cart_info'] ), true ); // will be sanitized later.
		}

		// Billing info.
		if ( isset( $_POST['billing_info'] ) && is_array( $_POST['billing_info'] ) ) {
			$instance->update_billing_info( wp_unslash( $_POST['billing_info'] ), true ); // will be sanitized later.
		}

		if ( isset( $_POST['wp_travel_engine_booking_status'] ) ) {
			$instance->update_booking_status( wp_unslash( $_POST['wp_travel_engine_booking_status'] ), true ); // will be sanitized later.
		}

		$instance->maybe_update_inventory();

	}

	public function update_booking_status( $status, $update ) {
		$this->update_booking_meta( '_prev_booking_status', get_post_meta( $this->ID, 'wp_travel_engine_booking_status', true ) );
		$this->update_booking_meta( 'wp_travel_engine_booking_status', $status );
		return $this;

	}

	public static function trashing_booking( $booking_id ) {
		$order_trips = get_post_meta( $booking_id, 'order_trips', true );
		update_post_meta( $booking_id, 'wp_travel_engine_booking_status', 'canceled' );

		if ( is_array( $order_trips ) ) {
			$inventory = new \WPTravelEngine\Core\Booking_Inventory();
			foreach( $order_trips as $cart_key => $data ) {
				$inventory->update_pax( $cart_key, 0, $data['ID'], $booking_id );
				if ( isset( $data['_prev_cart_key'] ) && $data['_prev_cart_key'] !== $cart_key ) {
					$inventory->update_pax( $cart_key, 0, $data['ID'], $booking_id );
				}
			}
		}
	}


	public function update_cart_info( $data, $update = false ) {
		$_data = array();

		if ( $update ) {
			$current_value = get_post_meta( $this->ID, 'cart_info', true );
			if ( is_array( $current_value ) ) {
				$_data = wp_parse_args( $data, $current_value );
			}
		}
		return $this->update_booking_meta( 'cart_info', $_data );
	}

	public function update_billing_info( $data, $update = false ) {
		$_data = array();

		if ( $update ) {
			$current_value = get_post_meta( $this->ID, 'billing_info', true );
			if ( is_array( $current_value ) ) {
				$_data = wp_parse_args( $data, $current_value );
			}
		}
		return $this->update_booking_meta( 'billing_info', $data );
	}

	public function maybe_update_inventory() {
		$status          = get_post_meta( $this->ID, 'wp_travel_engine_booking_status', true );
		$previous_status = get_post_meta( $this->ID, '_prev_booking_status', true );
		if ( $status !== $previous_status || isset( $_POST['order_trips'] ) ) {
			$inventory   = new \WPTravelEngine\Core\Booking_Inventory();
			$order_trips = get_post_meta( $this->ID, 'order_trips', true );

			foreach ( $order_trips as $cart_id => $cart_data ) {
				$_card_key = $cart_id;
				if ( isset( $cart_data['_prev_cart_key'] ) ) {
					$_card_key = $cart_data['_prev_cart_key'];
				}
				preg_match( '/(cart)_(\d+)_(\d+)_([\d-]+)_([\d-]+)/', $_card_key, $cart_key_chunk );
				$_cart_key_chunk = $cart_key_chunk;
				if ( isset( $cart_data['ID'] ) ) {
					$cart_key_chunk[2] = $cart_data['ID'];
				}
				if ( isset( $cart_data['datetime'] ) ) {
					try {
						$_datetime = new \DateTime( $cart_data['datetime'] );
					} catch ( \Exception $e ) {
						$_datetime = new \DateTime();
					}
					$cart_key_chunk[4] = $_datetime->format( 'Y-m-d' );
				}

				array_shift( $cart_key_chunk );
				$cart_key = implode( '_', $cart_key_chunk );
				if ( ! in_array( $status, array( 'refunded', 'canceled', 'N/A' ), true ) ) {
					$inventory->update_pax( $cart_key, array_sum( $cart_data['pax'] ), $cart_data['ID'], $this->ID );
				} else {
					$inventory->update_pax( $cart_key, 0, $cart_data['ID'], $this->ID );
				}

				if ( $_card_key !== $cart_key ) {
					if ( $cart_data['ID'] != $_cart_key_chunk[2] ) {
						$inventory->update_pax( $_card_key, 0, $_cart_key_chunk[2], $this->ID );
					} else {
						$inventory->update_pax( $_card_key, 0, $cart_data['ID'], $this->ID );
					}
				}
				$order_trips[ $cart_id ]['_prev_cart_key'] = $cart_key;
			}

			update_post_meta( $this->ID, 'order_trips', $order_trips );

		}
	}

	public function update_order_items( $data, $update = false ) {
		$_data = array();
		if ( $update ) {
			$current_order_trips = get_post_meta( $this->ID, 'order_trips', true );

			foreach ( array_keys( $current_order_trips ) as $cart_id ) {
				if ( ! isset( $data[ $cart_id ] ) ) {
					$_data[ $cart_id ] = $current_order_trips[ $cart_id ];
					continue;
				}
				$cart_data = $data[ $cart_id ];

				if ( isset( $cart_data['ID'] ) ) {
					$_data[ $cart_id ]['ID']    = sanitize_text_field( $cart_data['ID'] );
					$_data[ $cart_id ]['title'] = get_the_title( $_data[ $cart_id ]['ID'] );
				}
				if ( isset( $cart_data['datetime'] ) ) {
					$_data[ $cart_id ]['datetime'] = sanitize_text_field( $cart_data['datetime'] );
				}

				if ( isset( $cart_data['pax'] ) ) {
					$_data[ $cart_id ]['pax'] = array_map( 'absint', $cart_data['pax'] );
				}

				if ( isset( $cart_data['pax_cost'] ) ) {
					foreach( $cart_data['pax_cost'] as $_id => $pax_cost ) {
						if ( ! isset( $_data[ $cart_id ]['pax'][ $_id ] )  ) {
							continue;
						}
						$pax_count = (int) $_data[ $cart_id ]['pax'][ $_id ];
						$_data[ $cart_id ]['pax_cost'][ $_id ] = $pax_count * (float) $pax_cost;
					}
				}

				if ( isset( $cart_data['cost'] ) ) {
					$_data[ $cart_id ]['cost'] = sanitize_text_field( $cart_data['cost'] );
				}

				$_data[ $cart_id ] = wp_parse_args( $_data[ $cart_id ], $current_order_trips[ $cart_id ] );
			}
		} else {
			$_data = $data;
		}
		return $this->update_booking_meta( 'order_trips', $_data );
	}

	public function update_legacy_order_meta( $data ) {
		return $this->update_booking_meta( 'wp_travel_engine_booking_setting', $data );
	}

	public function update_payments( $data ) {
		return $this->update_booking_meta( 'payments', $data );
	}

}
