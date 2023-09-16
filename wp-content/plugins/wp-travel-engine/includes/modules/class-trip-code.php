<?php
/**
 * WP Travel Engine Trip Code.
 *
 * @since __addonmigration__
 */
namespace WPTravelEngine\Modules;

class TripCode {

	public function __construct() {
		$this->init_hooks();
	}

	private function init_hooks() {
		add_action( 'wp_travel_engine_edit_trip_code', array( __CLASS__, 'trip_code_setion' ) );
		add_action( 'wte_additional_payment_email_tags', array( __CLASS__, 'trip_code_email_tag_info' ) );
		add_action( 'wte_booking_after_trip_name', array( __CLASS__, 'display_trip_code' ) );

		add_action( 'wp_head', array( __CLASS__, 'style' ) );

		// Public Hooks.
		add_action(
			'wte_bf_after_trip_name',
			function( $trip_id ) {
				$trip_code = self::get_trip_code( $trip_id );

				if ( $trip_code ) {
					echo sprintf(
						'<span class="wpte-bf-trip-code">%1$s: <span>%2$s</span></span>',
						__( 'Trip Code', 'wp-travel-engine' ),
						esc_html( $trip_code )
					);
				}
			}
		);

		add_action(
			'wte_email_after_trip_name',
			function() {
				print(
					'<tr>'
					. '<td class="content-block">'
					. '<h3 class="aligncenter">' . esc_html__( 'Trip Code: {trip_code}', 'wp-travel-engine' ) . '</h3>'
					. '</td>'
					. '</tr>'
				);
			}
		);
		add_filter( 'wte_booking_reciept_email_content', array( __CLASS__, 'replace_mail_tags' ), 10, 2 );
		add_filter( 'wte_purchase_reciept_email_content', array( __CLASS__, 'replace_mail_tags' ), 10, 2 );

		add_filter(
			'wte_booking_mail_tags',
			function( $tags, $payment_id ) {
				if ( $payment_id ) {
					$booking_id  = get_post_meta( $payment_id, 'booking_id', true );
					$order_trips = get_post_meta( $booking_id, 'order_trips', true );

					if ( ! empty( $order_trips ) && is_array( $order_trips ) ) {
						$trip                = array_shift( $order_trips );
						$tags['{trip_code}'] = self::get_trip_code( $trip['ID'] );
					}
				}

				return $tags;
			},
			10,
			2
		);

		add_action(
			'wte_thankyou_after_trip_name',
			function( $trip_id ) {
				if ( ! $trip_id ) {
					return;
				}

				$trip_code = self::get_trip_code( $trip_id );

				if ( $trip_code ) {
					?>
					<div class="detail-item">
						<strong class="item-label"><?php esc_html_e( 'Trip Code:', 'wp-travel-engine' ); ?></strong>
						<span class="value"><?php echo esc_html( $trip_code ); ?></span>
					</div>
					<?php
				}
			}
		);

		add_filter(
			'wte_trip_schema_sku',
			function( $sku, $trip_id ) {
				if ( ! $trip_id ) {
					return $sku;
				}

				$trip_code = self::get_trip_code( $trip_id );

				if ( $trip_code ) {
					$sku = $trip_code;
				}

				return $sku;
			},
			10,
			2
		);
		add_shortcode( 'wte_trip_code', array( __CLASS__, 'output_trip_code' ) );
	}

	public static function output_trip_code( $atts ) {
		if ( is_admin() ) {
			return;
		}

		ob_start();
		global $post;
		$post_id = is_object( $post ) && isset( $post->ID ) ? $post->ID : false;

		$atts = shortcode_atts(
			array(
				'id' => $post_id,
			),
			$atts,
			'wte_trip_code'
		);

		$trip_code = self::get_trip_code( $atts['id'] );

		if ( $trip_code ) {
			echo sprintf(
				'<span class="wpte-trip-code">%1$s: %2$s</span>',
				__( 'Trip Code', 'wp-travel-engine' ),
				esc_html( $trip_code )
			);
		}

		return ob_get_clean();
	}

	public static function style() {
		echo '<style>.wpte-bf-book-summary .wpte-bf-trip-name-wrap{display:flex;flex-direction:column;align-items:flex-start}.wpte-bf-trip-code{margin:15px 0 0 0;padding:3px 15px;font-size:15px;letter-spacing:.5px;line-height:1.7;background:var(--primary-color);color:rgba(255,255,255,.85);border-radius:3px;order:3;font-style:italic}.wpte-bf-trip-code span{font-style:normal;font-weight:600}</style>';
	}

	public static function replace_mail_tags( $body, $booking_id ) {
		global $wte_cart;

		$trip_ids = $wte_cart->get_cart_trip_ids();
		$trip_id  = $trip_ids['0'];

		if ( ! $trip_id ) {
			return;
		}

		$trip_code = self::get_trip_code( $trip_id );

		$body = str_replace( '{trip_code}', $trip_code, $body );

		return $body;
	}


	public static function get_trip_code( $trip_id = null ) {
		if ( ! $trip_id ) {
			return false;
		}

		$trip_metas = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );

		if ( ! $trip_metas ) {
			return false;
		}

		$trip_code = 'WTE-' . $trip_id;

		if ( isset( $trip_metas['trip_code'] ) && $trip_metas['trip_code'] != '' ) {
			$trip_code = $trip_metas['trip_code'];
		}

		return apply_filters( 'wp_travel_engine_trip_code', $trip_code, $trip_id );
	}

	/**
	 * Extend Trip Code meta box.
	 */
	public static function trip_code_setion() {
		global $post;

		// Get settings meta.
		$wp_travel_engine_setting = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

		// Get trip code meta.
		$trip_code           = isset( $wp_travel_engine_setting['trip_code'] ) ? $wp_travel_engine_setting['trip_code'] : false;
		$trip_code_shortcode = '[wte_trip_code id=' . "'" . $post->ID . "'" . ']';

		?>
			<div class="wpte-field wpte-trip-code wpte-floated">
				<label for="wp_travel_engine_setting[trip_code]" class="wpte-field-label"><?php esc_html_e( 'Trip Code', 'wp-travel-engine' ); ?></label>
				<span class="wpte-trip-code-box">
					<input type="text" id="wp_travel_engine_setting[trip_code]" name="wp_travel_engine_setting[trip_code]" value="<?php echo $trip_code ? esc_attr( $trip_code ) : esc_html( sprintf( __( 'WTE-%1$s', 'wp-travel-engine' ), $post->ID ) ); ?>" placeholder="<?php esc_html_e( 'Enter Trip Code', 'wp-travel-engine' ); ?>">
				</span>
				<span class="wpte-tooltip"><?php esc_html_e( 'Enter the Trip Code.', 'wp-travel-engine' ); ?></span>
				<!-- <div class="wpte-info-block">
					<p><?php _e( sprintf( 'Need to display Trip Code? Use the shortcode <b>%1$s</b> to display Trip Code in posts/pages/tabs and widget.', $trip_code_shortcode ), 'wp-travel-engine', 'wte-trip-code' ); ?></p>
				</div> -->
			</div>
		<?php
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public static function trip_code_email_tag_info() {
		print( '<h3>' . __( 'Trip Code', '' ) . '</h3>'
		. '<ul>'
		. '<li>' . __( '{trip_code} - Trip Code', 'wp-travel-engine' ) . '</li>'
		. '</ul>' );
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $trip_id
	 * @return void
	 */
	public static function display_trip_code( $trip_id ) {

		$trip_code = self::get_trip_code( $trip_id );

		if ( $trip_code ) {
			printf(
				'<li><b>%1$s</b><span>%2$s</span></li>',
				__( 'Trip Code', 'wp-travel-engine' ),
				esc_html( $trip_code )
			);
		}
	}



}

new TripCode();
