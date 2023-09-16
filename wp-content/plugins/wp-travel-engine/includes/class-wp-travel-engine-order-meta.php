<?php
/**
 * Place order form metas.
 *
 * Responsible for creating metaboxes for order.
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 * @author
 */
class Wp_Travel_Engine_Order_Meta {

	public function __construct() {
		$this->init();
	}

	function init() {
		add_action( 'add_meta_boxes_booking', array( $this, 'wpte_booking_details_add_meta_boxes' ) );
		add_action( 'add_meta_boxes', array( $this, 'wpte_customer_add_meta_boxes' ) );
		add_action( 'add_meta_boxes', array( $this, 'wpte_customer_history_add_meta_boxes' ) );

		// Combined to update wp-travel-engine default meta.
		add_action( 'save_post', array( __CLASS__, 'save_post' ), 11, 3 );
	}

	public static function save_post( $post_id, $post, $update ) {

		if ( ! $post || ! in_array( $post->post_type, array( WP_TRAVEL_ENGINE_POST_TYPE, 'booking', 'customer', 'enquiry' ), ! 0 ) ) {
			return;
		}

		if ( 'booking' === $post->post_type ) {
			foreach ( array(
				'wp_travel_engine_booking_payment_status'  => array(
					'default'           => 'pending',
					'sanitize_callback' => function( $value ) {
						return sanitize_text_field( $value );
					},
				),
				'wp_travel_engine_booking_payment_gateway' => array(
					'sanitize_callback' => function( $value ) {
						return sanitize_text_field( $value );
					},
				),
				'wp_travel_engine_booking_payment_details' => array(
					'sanitize_callback' => function( $value ) {
						return wp_unslash( $value );
					},
				),
				'wp_travel_engine_booking_status'          => array(
					'sanitize_callback' => function( $value ) {
						return sanitize_text_field( $value );
					},
				),
			) as $meta_key => $args ) {
				if ( isset( $_POST[ $meta_key ] ) ) { // phpcs:ignore
					$meta_value = ( isset( $args['sanitize_callback'] ) ) ? call_user_func( $args['sanitize_callback'], $_POST[ $meta_key ] ) : wte_clean( wp_unslash( $_POST[ $meta_key ] ) ); // phpcs:ignore
					update_post_meta( $post_id, $meta_key, $meta_value );
				}
			}
		}

		if ( isset( $_POST['wp_travel_engine_booking_setting'] ) ) { // phpcs:ignore
			$settings = wte_clean( wp_unslash( $_POST['wp_travel_engine_booking_setting'] ) ); // phpcs:ignore
			update_post_meta( $post_id, 'wp_travel_engine_booking_setting', $settings );
		}
	}

	/**
	 * Place order form metabox.
	 *
	 * @since 1.0.0
	 */
	function wpte_booking_details_add_meta_boxes() {
		add_meta_box(
			'booking_details_id',
			__( 'Booking Details', 'wp-travel-engine' ),
			array( $this, 'wp_travel_engine_booking_details_metabox_callback' ),
			'booking',
			'normal',
			'high'
		);
	}

	// Tab for notice listing and settings
	public function wp_travel_engine_booking_details_metabox_callback() {
		global $post;
		$booking_status = get_post_meta( $post->ID, 'wp_travel_engine_booking_status', true );
		$_order_trips = get_post_meta( $post->ID, 'order_trips', true );
		if ( ! empty( $booking_status ) && ( ! isset( $_order_trips ) || ! is_array( $_order_trips ) ) ) {
			include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/backend/booking/booking-details.php';
		} else {
			$this->booking_details_mb_callback( $post );
		}
	}

	/**
	 *
	 * Default booking data for new booking.
	 *
	 * @since 5.4.1
	 */
	function wptravelengine_edit_booking_defaults( $_post ) {
		$booking_object = (object) get_post( $_post );

		$pricing_categories = get_terms(
			array(
				'taxonomy'   => 'trip-packages-categories',
				'hide_empty' => false,
				'orderby'    => 'term_id',
				'fields'     => 'id=>name',
			)
		);
		$pax = [];
		foreach ( array_keys( $pricing_categories ) as $term_id ) {
			$pax[ $term_id ] = 0;
		}

		$defaults = array(
			'order_trips' => [[
				'ID' => 0,
				'datetime' => date( 'Y-m-d' ),
				'cost' 	   => 0,
				'pax_cost' => [],
				'trip_extras' => [],
				'title'      => '',
				'partial_cost' => 0,
				'pax'          => $pax,
				'has_time' => false,
			]],
			'cart_info' => [
				"currency" => wp_travel_engine_get_settings( 'currency_code' ),
				"subtotal" => 0,
				"total" => 0,
				"cart_partial" => 0,
				"discounts" => [],
				"tax_amount" => 0,
			],
			'billing_info' => [
				"fname" => '',
				"lname" => '',
				"email" => '',
				"address" => '',
				"city" => '',
				"country" => '',
			]
		);

		foreach( $defaults as $meta_key => $meta_value ) {
			$booking_object->{$meta_key} = $meta_value;
		}

		// Payment Section.
		$postarr = new \stdClass();

		$postarr->meta_input = wp_parse_args(
			[],
			array(
				'payment_status' => 'pending',
				'billing_info'   => $booking_object->billing_info,
				'payable'        => array(
					'currency' => wp_travel_engine_get_settings( 'currency_code' ),
					'amount'   => 0,
				),
			)
		);
		$payment_id = wp_insert_post(
			wp_parse_args(
				$postarr,
				array(
					'post_type'   => 'wte-payments',
					'post_status' => 'publish',
					'post_title'  => "Payment for booking #{$booking_object->ID}",
				)
			)
		);

		$booking_object->payments = [ $payment_id ];
		$booking_object->due_amount = 0;

		return $booking_object;
	}

	/**
	 * New Meta box callback for booking since 5.4.1
	 *
	 * @since 5.4.1
	 */
	public function booking_details_mb_callback( $post ) {
		$booking_object     = get_post( $post );

		$_order_trips = get_post_meta( $booking_object->ID, 'order_trips', true );

		if ( empty( $_order_trips ) || key( $_order_trips ) === NULL ) {
			$booking_object = $this->wptravelengine_edit_booking_defaults( $booking_object->ID );
		}

		$_args = [ 'booking_details' => $booking_object ];

		require plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . "includes/backend/booking/booking-parts/booking-details.php";
	}

	/**
	 * Place order form metabox.
	 *
	 * @since 1.0.0
	 */
	function wpte_customer_add_meta_boxes() {
		$screens = array( 'customer' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'customer_id',
				__( 'Customer Details', 'wp-travel-engine' ),
				array( $this, 'wp_travel_engine_customer_metabox_callback' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	// Tab for notice listing and settings
	public function wp_travel_engine_customer_metabox_callback() {
		include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/backend/booking/customer.php';
	}

	/**
	 * Customer History Metabox
	 *
	 * @since 1.0.0
	 */
	function wpte_customer_history_add_meta_boxes() {
		$screens = array( 'customer' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'customer_history_id',
				__( 'Customer History', 'wp-travel-engine' ),
				array( $this, 'wp_travel_engine_customer_history_metabox_callback' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	// Tab for notice listing and settings
	public function wp_travel_engine_customer_history_metabox_callback() {
		include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/backend/booking/customer-history.php';
	}

}
$obj = new Wp_Travel_Engine_Order_Meta();
