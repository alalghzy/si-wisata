<?php
/**
 * Upgrade Notices.
 *
 * @since 5.0.0
 */
namespace WPTravelEngine\Core;

class Updates {

	/**
	 * This is the header used by extensions to show requirements.
	 *
	 * @var string
	 */
	const VERSION_REQUIRED_HEADER = 'WTE requires at least';

	/**
	 * This is the header used by extensions to show testing.
	 *
	 * @var string
	 */
	const VERSION_TESTED_HEADER = 'WTE tested up to';

	/**
	 * Array of plugins lacking testing with the major version.
	 *
	 * @var array
	 */
	protected $major_untested_plugins = array();

	/**
	 * Array of plugins lacking testing with the minor version.
	 *
	 * @var array
	 */
	protected $minor_untested_plugins = array();

	public function __construct() {
		add_action( 'in_plugin_update_message-wte/wp-travel-engine.php', array( $this, 'in_plugin_update_message' ), 10, 2 );
	}

	public function in_plugin_update_message( $file, $plugin_data ) {
		$plugins = wte_get_engine_extensions();

		$current_version_parts = explode( '.', WP_TRAVEL_ENGINE_VERSION );
		$new_version_parts     = explode( '.', $plugin_data->new_version );

		if ( version_compare( $current_version_parts[0] . '.' . $current_version_parts[1], $new_version_parts[0] . '.' . $new_version_parts[1], '>=' ) ) {
			return;
		}

		$untested_plugins       = array();
		$major_untested_plugins = array();
		$minor_untested_plugins = array();
		$upgrade_type           = '';

		$version = $new_version_parts[0];

		foreach ( $plugins as $file => $plugin ) {
			if ( empty( $plugin[ self::VERSION_TESTED_HEADER ] ) ) {
				$plugin[ self::VERSION_TESTED_HEADER ] = 'N/A'; // Because header has been added since 5.0.0.
			}

			$plugin_version_parts = explode( '.', $plugin[ self::VERSION_TESTED_HEADER ] );
			$plugin_version       = $plugin_version_parts[0];

			if ( $plugin_version === 'N/A' || version_compare( $plugin_version . '.0', $version . '.0', '<' ) ) {
				$upgrade_type                    = 'major';
				$major_untested_plugins[ $file ] = $plugin;
			} elseif ( version_compare( $plugin_version . '.' . $plugin_version_parts[1], $version . '.' . $new_version_parts[1], '<' ) ) {
				$upgrade_type                    = 'minor';
				$minor_untested_plugins[ $file ] = $plugin;
			}
		}

		wp_enqueue_style( 'wte-plugins-php' );

		$new_version = $new_version_parts[0] . '.' . $new_version_parts[1];

		if ( ! empty( $major_untested_plugins ) ) {
			$untested_plugins = $major_untested_plugins;
			echo '</p>';
			$message = sprintf( __( "<strong>Heads up!</strong> The versions of the following plugins you're running haven't been tested with WP Travel Engine %s. <br/><b>Please update them or confirm compatibility before updating WP Travel Engine, or you may experience issues,</b>", 'wp-travel-engine' ), $new_version );
			include plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'admin/partials/plugin-updates/notice-untested-extensions.php';
			echo '<p style="display:none">';
		}

		if ( ! empty( $minor_untested_plugins ) ) {
			$untested_plugins = $minor_untested_plugins;
			echo '</p>';
			$message = sprintf( __( "<strong>Heads up!</strong> The versions of the following plugins you're running haven't been tested with WP Travel Engine %s.", 'wp-travel-engine' ), $new_version );
			include plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'admin/partials/plugin-updates/notice-untested-extensions.php';
			echo '<p style="display:none">';
		}

	}

}

new Updates();
