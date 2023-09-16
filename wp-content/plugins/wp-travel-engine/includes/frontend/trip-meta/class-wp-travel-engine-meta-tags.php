<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * The template for meta tags of the single trip.
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/frontend/trip-meta
 * @author     WP Travel Engine <https://wptravelengine.com/>
 */
class Wp_Travel_Engine_Meta_Tags {

	function __construct() {
		add_action( 'wp_travel_engine_trip_content_wrapper', array( $this, 'wp_travel_engine_trip_content_wrapper' ) );
		add_action( 'wp_travel_engine_trip_main_content', array( $this, 'wp_travel_engine_trip_content' ) );
		add_action( 'wp_travel_engine_trip_content_inner_wrapper_close', array( $this, 'wp_travel_engine_trip_content_inner_wrapper_close' ) );
		add_action( 'wp_travel_engine_trip_secondary_wrap', array( $this, 'wp_travel_engine_trip_secondary_wrap' ) );
		add_action( 'wp_travel_engine_trip_secondary_wrap_close', array( $this, 'wp_travel_engine_trip_secondary_wrap_close' ) );
		add_action( 'wp_travel_engine_trip_price', array( $this, 'wp_travel_engine_trip_price' ) );
		add_action( 'wp_travel_engine_trip_facts', array( $this, 'wp_travel_engine_trip_facts' ) );
		add_action( 'wp_travel_engine_trip_category', array( $this, 'wp_travel_engine_trip_category' ) );
		add_action( 'wp_travel_engine_primary_wrap_close', array( $this, 'wp_travel_engine_primary_wrap_close' ) );
		add_action( 'wp_travel_engine_trip_facts_content', array( $this, 'wte_trip_facts_front_end' ) );
	}

	/**
	 * Main wrap of the single trip.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_trip_content_wrapper() {
		?>
	<div id="wte-crumbs">
		<?php
		do_action( 'wp_travel_engine_breadcrumb_holder' );
		?>
	</div>
	<div id="wp-travel-trip-wrapper" class="trip-content-area">
		<div class="row">
		<div id="primary" class="content-area">
		<?php
	}

	/**
	 * Trip content inner wrap close.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_trip_content_inner_wrapper_close() {
		global $post;
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', array() );

		if ( ! empty( $wp_travel_engine_settings['enquiry'] ) ) {
			do_action( 'wp_travel_engine_enquiry_form' );
		}
		?>
		</div>
		<?php
	}

	/**
	 * Main content of the single trip.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_trip_content() {
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/frontend/trip-meta/trip-meta-parts/trip-tabs.php';
	}

	/**
	 * Secondary wrip open.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_trip_secondary_wrap() {
		do_action( 'wp_travel_engine_before_secondary' );
		?>
		<div id="secondary" class="widget-area">
		<?php
	}

	/**
	 * Secondary wrap close.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_trip_secondary_wrap_close() {
		?>
		</div><!-- #secondary -->
		<?php
	}


	/**
	 * Secondary content such as pricing for single trip.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_trip_price() {
		global $post;

		// Functions
		$functions     = \wte_functions();
		$currency_code = 'USD';
		$currency_code = $functions->trip_currency_code( $post );

		// Get global and post settings.
		$post_meta    = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
		$wte_settings = get_option( 'wp_travel_engine_settings', true );

		// Get trip price.
		$is_sale_price_enabled = wp_travel_engine_is_trip_on_sale( $post->ID );
		$sale_price            = trim( wp_travel_engine_get_sale_price( $post->ID ) );
		$regular_price         = trim( wp_travel_engine_get_prev_price( $post->ID ) );
		$price                 = trim( wp_travel_engine_get_actual_trip_price( $post->ID ) );
		// Don't load the trip price template, if the booking form hidden option is set.
		if ( isset( $wte_settings['booking'] ) ) {
			return;
		}

		// Don't load the template, if the regular price is not set.
		if ( '' === trim( $regular_price ) ) {
			return;
		}

		// Get booking steps.
		$booking_steps = array(
			__( 'Select a Date', 'wp-travel-engine' ),
			__( 'Travellers', 'wp-travel-engine' ),
		);
		$booking_steps = apply_filters( 'wte_trip_booking_steps', $booking_steps );

		// Get placeholder.
		$wte_placeholder = isset( $wte_settings['pages']['wp_travel_engine_place_order'] ) ? $wte_settings['pages']['wp_travel_engine_place_order'] : '';

		do_action( 'wp_travel_engine_before_trip_price' );
		if ( defined( 'WTE_USE_OLD_BOOKING_PROCESS' ) && WTE_USE_OLD_BOOKING_PROCESS ) {
			require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/frontend/trip-meta/trip-meta-parts/trip-price-bak.php';
		} else {
			require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/frontend/trip-meta/trip-meta-parts/trip-price.php';
		}
		do_action( 'wp_travel_engine_after_trip_price' );
	}

	/**
	 * Load trip facts frontend.
	 *
	 * @since 1.0.0
	 */
	function wte_trip_facts_front_end() {
		require_once WP_TRAVEL_ENGINE_BASE_PATH . '/includes/frontend/trip-meta/trip-meta-parts/trip-facts.php';
	}

	/**
	 * Secondary content such as trip facts for single trip.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_trip_facts() {
		do_action( 'wp_travel_engine_before_trip_facts' );
		do_action( 'wp_travel_engine_trip_facts_content' );
		do_action( 'wp_travel_engine_after_trip_facts' );
	}

	/**
	 * Primary wrap close.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_primary_wrap_close() {
		?>
		</div>
			</div>
		<?php
		do_action( 'wp_travel_engine_before_related_posts' );
		do_action( 'wp_travel_engine_related_posts' );
		do_action( 'wp_travel_engine_after_related_posts' );
		?>
	</div>
		<?php
	}
}
// new Wp_Travel_Engine_Meta_Tags();
