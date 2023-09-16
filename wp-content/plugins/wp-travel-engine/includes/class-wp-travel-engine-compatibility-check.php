<?php
/**
 * Addons compatibility Check.
 *
 * @package WP_Travel_Engine/includes
 * @author WP Travel Engine
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * class compatibility check
 *
 * @since 2.3.0
 */
class WP_Travel_Engine_Compatibility_Check {

	/**
	 * Constructor.
	 */
	function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hooks.
	 */
	public function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'define_backward_process' ) );
	}

	/**
	 * Define constant
	 */
	public function define_backward_process() {
		if ( $this->requires_backward_processs() ) :
			if ( ! defined( 'WTE_USE_OLD_BOOKING_PROCESS' ) ) :
				define( 'WTE_USE_OLD_BOOKING_PROCESS', true );
			endif;
		endif;
	}

	/**
	 * Addons List for compatibility check.
	 *
	 * @return void
	 */
	public function addons_list() {
		$addons = array(
			'group_discount'            => array(
				'plugin_path' => 'wp-travel-engine-group-discount/wp-travel-engine-group-discount.php',
				'min_version' => '1.0.9',
			),
			'trip_fixed_starting_dates' => array(
				'plugin_path' => 'wp-travel-engine-trip-fixed-starting-dates/wte-fixed-departure-dates.php',
				'min_version' => '1.2.2',
			),
			'extra_services'            => array(
				'plugin_path' => 'wp-travel-engine-extra-services/wte-extra-services.php',
				'min_version' => '1.0.2',
			),
			'stripe'                    => array(
				'plugin_path' => 'wp-travel-engine-stripe-payment-gateway/class-stripe.php',
				'min_version' => '1.0.6',
			),
			'paypal_express'            => array(
				'plugin_path' => 'wp-travel-engine-paypal-express-gateway/wte-paypalexpress.php',
				'min_version' => '1.0.2',
			),
			'payu'                      => array(
				'plugin_path' => 'wp-travel-engine-payu-payment-gateway/wte-payu.php',
				'min_version' => '1.0.4',
			),
		);
		return $addons;
	}

	/**
	 * Updated addons that require newer version of WTE.
	 *
	 * @return void
	 */
	public function updated_addons() {
		$updated_addons = array(
			'advanced_itinerary' => array(
				'plugin_path' => 'wte-advanced-itinerary/wte-advanced-itinerary.php',
				'min_version' => '1.0.1',
			),
			'advanced_search'    => array(
				'plugin_path' => 'wte-advanced-search/wte-advanced-search.php',
				'min_version' => '1.1.7',
			),
		);

		return $updated_addons;
	}

	/**
	 * Incompatible addons.
	 *
	 * @return void
	 */
	public function incompatibile_addons() {

		$incompatibile_addons = array_filter(
			$this->addons_list(),
			function( $addon ) {

				if ( ! function_exists( 'get_plugin_data' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				if ( ! file_exists( WP_PLUGIN_DIR . '/' . $addon['plugin_path'] ) ) :
					return false;
			endif;

				if ( is_plugin_active( $addon['plugin_path'] ) ) :
					$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $addon['plugin_path'] );

					return ! empty( $plugin_data['Version'] ) && ! version_compare( $plugin_data['Version'], $addon['min_version'], '>=' );

			endif;
				return false;

			}
		);

		return $incompatibile_addons;
	}

	/**
	 * Check if addons are active.
	 *
	 * @return void
	 */
	public function updated_addons_actives() {
		$incompatibile_addons = array_filter(
			$this->updated_addons(),
			function( $addon ) {

				if ( ! function_exists( 'get_plugin_data' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				if ( ! file_exists( WP_PLUGIN_DIR . '/' . $addon['plugin_path'] ) ) :
					return false;
			endif;

				if ( is_plugin_active( $addon['plugin_path'] ) ) :
					$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $addon['plugin_path'] );

					return ! empty( $plugin_data['Version'] ) && ! version_compare( $plugin_data['Version'], $addon['min_version'], '>=' );

			endif;
				return false;

			}
		);

		return $incompatibile_addons;
	}

	public function requires_backward_processs() {
		return ! empty( $this->incompatibile_addons() );
	}

	/**
	 * Generate update messages with plugins and version.
	 *
	 * @return void
	 */
	public function update_messages( $response ) {
		if ( $this->requires_backward_processs() || ! empty( $this->updated_addons_actives() ) ) {
			$incompatibile_addons   = $this->incompatibile_addons();
			$updated_addons_actives = $this->updated_addons_actives();

			$html  = '<div class="update-message">';
			$html .= "<b>Heads Up!</b> WP Travel Engine {$response->new_version} is a major update release and requires the following add-ons to be updated to the mentioned minimum versions to work properly.
			.<br />";
			$html .= 'Please update the addons first before updateing WP Travel Engine to avoid any issues.<br /><br />';
			$html .= '<span style="width: 300px; display: inline-block; padding-left: 70px;"><b>Plugins</b></span>';
			$html .= '<span><b>Minimum Version Required</b></span>';
			$html .= '<ol>';
			foreach ( $incompatibile_addons as $incompatibile_addon ) {
				$plugin_path = plugin_dir_path( \WP_TRAVEL_ENGINE_BASE_PATH ) . $incompatibile_addon['plugin_path'];
				$plugin_data = get_plugin_data( $plugin_path, false, true );
				$html       .= '<li>';
				$html       .= '<span style="width: 400px; display:inline-block;">' . $plugin_data['Name'] . '</span>';
				$html       .= '<span style="margin-left: 0px: display: inline-block; padding-left: 20px;">' . $incompatibile_addon['min_version'] . '</span>';
				$html       .= '</li>';
			}
			foreach ( $updated_addons_actives as $key => $updated_addon ) {
				$plugin_path = plugin_dir_path( \WP_TRAVEL_ENGINE_BASE_PATH ) . $updated_addon['plugin_path'];
				$plugin_data = get_plugin_data( $plugin_path, false, true );
				$html       .= '<li>';
				$html       .= '<span style="width: 400px; display:inline-block;">' . $plugin_data['Name'] . '</span>';
				$html       .= '<span style="margin-left: 0px: display: inline-block; padding-left: 20px;">' . $updated_addon['min_version'] . '</span>';
				$html       .= '</li>';
			}
			$html .= '</ol>';
			$html .= '</div>';
			$html  = trim( $html );
			printf( '%s', $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}
// Run the show.
new WP_Travel_Engine_Compatibility_Check();
