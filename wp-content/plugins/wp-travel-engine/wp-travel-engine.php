<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wptravelengine.com/
 * @since             1.0.0
 * @package           WP_Travel_Engine
 *
 * @wordpress-plugin
 * Plugin Name:       WP Travel Engine - Travel and Tour Booking Plugin
 * Plugin URI:        https://wordpress.org/plugins/wp-travel-engine/
 * Description:       WP Travel Engine is a free travel booking WordPress plugin to create travel and tour packages for tour operators and travel agencies. It is a complete travel management system and includes plenty of useful features. You can create your travel booking website using WP Travel Engine in less than 5 minutes.
 * Version:           5.5.9
 * Author:            WP Travel Engine
 * Author URI:        https://wptravelengine.com/
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       wp-travel-engine
 * Domain Path:       /languages
 * Requires at least: 5.8
 * Requires PHP: 7.2
 * Tested up to: 6.1
 */

defined( 'ABSPATH' ) || exit;

define( 'WP_TRAVEL_ENGINE_FILE_PATH', __FILE__ );
define( 'WP_TRAVEL_ENGINE_VERSION', '5.5.9' );

/**
 * Load plugin updater file
 */
if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) ) {
	add_action(
		'admin_notices',
		function() {
			echo wp_kses(
				sprintf(
					'<div class="wte-admin-notice error">%1$s</div>',
					__( 'The PHP version doesn\'t meet requirement of WP Travel Engine, plugin is currently NOT RUNNING.', 'wp-travel-engine' )
				),
				array( 'div' => array( 'class' => array() ) )
			);
		}
	);
} elseif ( ! version_compare( get_bloginfo( 'version' ), '5.2', '>=' ) ) {
	add_action(
		'admin_notices',
		function() {
			echo wp_kses(
				sprintf(
					'<div class="wte-admin-notice error">%1$s</div>',
					__( 'The WordPress version is earlier than the minimum requirement to run WP Travel Engine, the plugin is NOT RUNNING.', 'wp-travel-engine' )
				),
				array( 'div' => array( 'class' => array() ) )
			);
		}
	);
} else {
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	if ( ! class_exists( 'Wp_Travel_Engine', false ) ) {
		require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
		require plugin_dir_path( __FILE__ ) . 'includes/class-wp-travel-engine.php';
		/**
		 * Returns singleton Instance of the main Class.
		 *
		 * @since 5.0
		 *
		 * @return WPTravelEngine\Plugin
		 */
		function WPTravelEngine() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
			return WPTravelEngine\Plugin::instance();
		}
		/**
		 * Engine starts.
		 */
		WPTravelEngine();

		/**
		 * Backward Comaptibility - This function may be used on some extensions.
		 */
		function run_Wp_Travel_Engine() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
			return WPTravelEngine()->run();
		}
	}
}
