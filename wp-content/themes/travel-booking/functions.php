<?php
/**
 * Travel Booking functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Travel_Booking
 */

// Define theme version
$travel_booking_theme_data = wp_get_theme();
if( ! defined( 'TRAVEL_BOOKING_THEME_VERSION' ) ) define ( 'TRAVEL_BOOKING_THEME_VERSION', $travel_booking_theme_data->get( 'Version' ) );
if( ! defined( 'TRAVEL_BOOKING_THEME_NAME' ) ) define ( 'TRAVEL_BOOKING_THEME_NAME', $travel_booking_theme_data->get( 'Name' ) );
if( ! defined( 'TRAVEL_BOOKING_THEME_TEXTDOMAIN' ) ) define ( 'TRAVEL_BOOKING_THEME_TEXTDOMAIN', $travel_booking_theme_data->get( 'TextDomain' ) );

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Custom functions for selective refresh.
 */
require get_template_directory() . '/inc/partials.php';

/**
 * Custom Functions
 */
require get_template_directory() . '/inc/custom-functions.php';

/**
 * Template Functions
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer Custom controls.
 */
require get_template_directory() . '/inc/custom-controls/custom-control.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Load Sanitization functions.
 */
require get_template_directory() . '/inc/customizer/sanitization-functions.php';

/**
 * Widgets
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Metabox
 */
require get_template_directory() . '/inc/metabox.php';

/**
 * Plugin Recommendation
*/
require get_template_directory() . '/inc/tgmpa/recommended-plugins.php';

/**
 * Getting Started
*/
require get_template_directory() . '/inc/getting-started/getting-started.php';


if( tb_is_tbt_activated() ){
	/**
	 * Modify filter hooks of WPTEC plugin.
	 */
	require get_template_directory() . '/inc/wptec-filters.php';
}

if ( travel_booking_is_woocommerce_activated() ) :
	/**
	 * Load woocommerce
	 */
	require get_template_directory() . '/inc/woocommerce-functions.php';
endif;

/**
 * Implement Local Font Method functions.
 */
require get_template_directory() . '/inc/class-webfont-loader.php';
