<?php
/**
 * Place order form.
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 * @author
 */
class WTE_Booking_Response {

	private $responses;

	public static function responses( $response = null ) {
		$responses = (object) array(
			'pending'   => __( 'Your booking order has been placed. You booking will be confirmed after payment confirmation/settlement.', 'wp-travel-engine' ),
			'completed' => __( 'The payment transaction has been completed.', 'wp-travel-engine' ),
			'success'   => __( 'The payment transaction has been successful.', 'wp-travel-engine' ),
			'failed'    => __( 'The payment transaction has been failed.', 'wp-travel-engine' ),
		);

		if ( is_null( $response ) ) {
			return $responses;
		}

		if ( isset( $responses->{$response} ) ) {
			return $responses->{$response};
		}

		return '';

	}

	public function __construct() {
		$this->responses = array(
			'pending'   => __( 'Your booking order has been placed. You booking will be confirmed after payment confirmation/settlement.', 'wp-travel-engine' ),
			'completed' => __( 'The payment transaction has been completed.', 'wp-travel-engine' ),
			'success'   => __( 'The payment transaction has been successful.', 'wp-travel-engine' ),
			'failed'    => __( 'The payment transaction has been failed.', 'wp-travel-engine' ),
		);
	}

	/**
	 * Get a data by key
	 *
	 * @param string The key data to retrieve
	 * @access public
	 */
	public function &__get( $key ) {
		$value = '';
		if ( isset( $this->responses()->{$key} ) ) {
			$value = $this->responses()->{$key};
		}

		return $value;
	}

	/**
	 * Whether or not an data exists by key
	 *
	 * @param string An data key to check for
	 * @access public
	 * @return boolean
	 * @abstracting ArrayAccess
	 */
	public function __isset( $key ) {
		return isset( $this->data[ $key ] );
	}

}
class Wp_Travel_Engine_Thank_You {

	/**
	 * Initialize the thank you form shortcode.
	 *
	 * @since 1.0.0
	 */
	function init() {
		add_shortcode( 'WP_TRAVEL_ENGINE_THANK_YOU', array( $this, 'wp_travel_engine_thank_you_shortcodes_callback' ) );
		add_filter( 'body_class', array( $this, 'add_thankyou_body_class' ) );
	}

	function add_thankyou_body_class( $classes ) {
		global $post;
		if ( is_object( $post ) ) {
			if ( has_shortcode( $post->post_content, 'WP_TRAVEL_ENGINE_THANK_YOU' ) ) {
				$classes[] = 'thank-you';
			}
		}

		return $classes;
	}

	public static function response() {
		return new WTE_Booking_Response();
	}

	public static function get_booking_details_html( $payment_id, $booking_id = null ) {
		if ( is_null( $booking_id ) ) {
			$booking_id = get_post_meta( $payment_id, 'booking_id', true );
		}
		do_action( 'wte_booking_cleanup', $payment_id, 'thankyou' );

		ob_start();
		wte_get_template(
			'thank-you/thank-you.php',
			array(
				'payment_id' => $payment_id,
				'booking_id' => $booking_id,
			)
		);

		return ob_get_clean();
	}

	/**
	 * Place order form shortcode callback function.
	 *
	 * @since 1.0.0
	 */
	function wp_travel_engine_thank_you_shortcodes_callback() {
		if ( is_admin() ) {
			return;
		}
		$data = WTE_Booking::get_callback_token_payload( 'thankyou' );

		if ( ! $data ) {
			return __( 'Thank you for booking the trip check. Please check your email for confirmation.', 'wp-travel-engine' );
		}
		
		if ( is_array( $data ) && isset( $data['bid'] ) ) {
			$booking_id = $data['bid'];
			$payment_id = $data['pid'];
		}

		return self::get_booking_details_html( $payment_id, $booking_id );

	}
}
