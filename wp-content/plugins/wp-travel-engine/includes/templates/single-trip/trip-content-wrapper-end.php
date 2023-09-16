<?php
/**
 * Single Trip Content
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/trip-content-wrapper-end.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$settings = get_option( 'wp_travel_engine_settings', array() );
if ( empty( $settings['enquiry'] ) ) {
	do_action( 'wp_travel_engine_enquiry_form' );
}
?>
</div>
<!-- /#primary -->
<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
