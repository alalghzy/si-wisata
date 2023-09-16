<?php
/**
 * The template for displaying trips archive page
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/archive-trip.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();
?>

<?php
	/**
	 * wp_travel_engine_trip_archive_outer_wrapper hook.
	 *
	 * @hooked wp_travel_engine_trip_archive_outer_wrapper - 10 (main wrapper)
	 */
	do_action( 'wp_travel_engine_trip_archive_outer_wrapper' );
?>

<?php
	/**
	 * wp_travel_engine_trip_archive_loop_start hook.
	 *
	 * @hooked wp_travel_engine_trip_archive_loop_start - 10 (loop starts)
	 */
	do_action( 'wp_travel_engine_trip_archive_loop_start' );
?>

<?php
	/**
	 * wp_travel_engine_trip_archive_wrap hook.
	 *
	 * @hooked wp_travel_engine_trip_archive_wrap - 10 (inner wrapper)
	 */
	do_action( 'wp_travel_engine_trip_archive_wrap' );
?>

<?php
	/**
	 * wp_travel_engine_trip_archive_loop_end hook.
	 *
	 * @hooked wp_travel_engine_trip_archive_loop_end - 10 (loop ends)
	 */
	do_action( 'wp_travel_engine_trip_archive_loop_end' );
?>

<?php
	/**
	 * wp_travel_engine_trip_archive_pagination hook.
	 *
	 * @hooked wp_travel_engine_trip_archive_pagination - 10 (pagination for archive)
	 */
	do_action( 'wp_travel_engine_trip_archive_pagination' );
?>

<?php
	/**
	 * wp_travel_engine_trip_archive_outer_wrapper_close hook.
	 *
	 * @hooked wp_travel_engine_trip_archive_outer_wrapper_close - 10 (main wrapper close)
	 */
	do_action( 'wp_travel_engine_trip_archive_outer_wrapper_close' );
?>

<?php
get_footer();

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
