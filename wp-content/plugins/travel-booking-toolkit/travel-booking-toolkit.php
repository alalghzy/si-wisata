<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wptravelengine.com
 * @since             1.0.0
 * @package           Travel_Booking_Toolkit
 *
 * @wordpress-plugin
 * Plugin Name:       Travel Booking Toolkit
 * Plugin URI:        https://wordpress.org/plugins/travel-booking-toolkit/
 * Description:       Travel Booking Toolkit allows you to add extra functionality to the Customizer, Widgets Section in your WordPress admin area.
 * Version:           1.2.0
 * Author:            wptravelengine
 * Author URI:        https://wptravelengine.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       travel-booking-toolkit
 * Domain Path:       /languages
 * Tested up to:     6.0.1
 * Requires at least: 4.4.0
 *
 * WTE Tested up to: 5.0.2
 * WTE requires at least: 4.3.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TBT_VERSION', '1.2.0' );
define( 'TBT_BASE_PATH', dirname( __FILE__ ) );
define( 'TBT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TBT_FILE_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-travel-booking-toolkit-activator.php
 */
function activate_travel_booking_toolkit() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-travel-booking-toolkit-activator.php';
	Travel_Booking_Toolkit_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-travel-booking-toolkit-deactivator.php
 */
function deactivate_travel_booking_toolkit() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-travel-booking-toolkit-deactivator.php';
	Travel_Booking_Toolkit_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_travel_booking_toolkit' );
register_deactivation_hook( __FILE__, 'deactivate_travel_booking_toolkit' );

require plugin_dir_path( __FILE__ ) . 'includes/helper-functions.php';
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-travel-booking-toolkit.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_travel_booking_toolkit() {

	$plugin = new Travel_Booking_Toolkit();
	$plugin->run();

}
run_travel_booking_toolkit();
