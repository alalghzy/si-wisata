<?php
/**
 * WTE Trip Sidebar
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/trip-sidebar.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php do_action( 'wp_travel_engine_trip_before_secondary_content' ); ?>

<?php
	/**
	 * wp_travel_engine_trip_secondary_wrap hook.
	 *
	 * @hooked trip_secondary_wrap_start - 5 (secondary wrapper open)
	 */
	do_action( 'wp_travel_engine_trip_secondary_wrap' );
?>

<?php
	/**
	 * wp_travel_engine_trip_price hook.
	 *
	 * @hooked display_trip_price - 5 (shows trip price)
	 */
	do_action( 'wp_travel_engine_trip_price' );
?>

<?php
	/**
	 * wp_travel_engine_trip_facts hook.
	 *
	 * @hooked display_trip_facts - 5 (shows trip facts)
	 */
	do_action( 'wp_travel_engine_trip_facts' );
?>

<?php
	/**
	 * wp_travel_engine_trip_gallery hook.
	 *
	 * @hooked wp_travel_engine_trip_gallery - 10 (shows trip gallery)
	 */
	do_action( 'wp_travel_engine_trip_gallery' );
?>

<?php
	/**
	 * wp_travel_engine_trip_map hook.
	 *
	 * @hooked wp_travel_engine_trip_map - 10 (shows trip map)
	 */
	do_action( 'wp_travel_engine_trip_map' );
?>

<?php
	/**
	 * wp_travel_engine_wte_sidebar.
	 *
	 * @hooked wte_widget_sidebar - 10 (shows trip map)
	 */
	do_action( 'wp_travel_engine_wte_sidebar' );
?>

<?php
do_action( 'wp_travel_engine_trip_after_secondary_content' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
