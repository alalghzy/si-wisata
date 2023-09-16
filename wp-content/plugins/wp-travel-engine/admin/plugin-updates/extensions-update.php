<?php
/**
 * Extensions Updater.
 */

add_action(
	'admin_init',
	function() {
		if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
			// load our custom updater.
			include plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'admin/plugin-updates/EDD_SL_Plugin_Updater.php';
		}
		$extensions = wte_get_engine_extensions();

		if ( empty( $extensions ) ) {
			return;
		}

		$license_settings = get_option( 'wp_travel_engine_license', array() );

		foreach ( $extensions as $file_path => $extension ) {
			if ( empty( $extension['WTE'] ) ) {
				continue;
			}
			list( $item_id, $license_key_index ) = explode( ':', $extension['WTE'] );
			if ( ! isset( $license_settings[ $license_key_index ] ) ) {
				continue;
			}

			new EDD_SL_Plugin_Updater(
				WP_TRAVEL_ENGINE_STORE_URL,
				$file_path,
				array(
					'version' => $extension['Version'], // current version number
					'license' => $license_settings[ $license_key_index ], // license key (used get_option above to retrieve from DB)
					'item_id' => $item_id, // ID of the product
					'author'  => 'WP Travel Engine', // author of this plugin
					'beta'    => false,
				)
			);

		}
	}
);

add_action(
	'admin_init',
	function() {
		// creates our settings in the options table
		register_setting(
			'wp_travel_engine_license',
			'wp_travel_engine_license',
			array(
				'sanitize_callback' => 'wpte_sanitize_license',
			)
		);

		// If License activation request.
		if ( isset( $_REQUEST['edd_license_activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			wp_travel_engine_activate_license();
		}

		// If License deactivation request.
		if ( isset( $_REQUEST['edd_license_deactivate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			wp_travel_engine_deactivate_license();
		}
	}
);

function wpte_sanitize_license( $new ) {
	// phpcs:disable
	$value                      = wte_clean( wp_unslash( $_POST['addon_name'] ) );
	$option                     = get_option( 'wp_travel_engine_license', [] );
	$addon_name                 = apply_filters( 'wp_travel_engine_addons', array() );
	$wte_fixed_departure_status = isset( $option[ $value . '_license_status' ] ) ? esc_attr( $option[ $value . '_license_status' ] ) : false;
	$arr                        = array();
	if ( isset( $_POST['edd_license_activate'] ) && $_POST['edd_license_activate'] == 'Activate License' ) {
		$new[ $value . '_license_key' ]    = $option[ $value . '_license_key' ];
		$new[ $value . '_license_status' ] = 'valid';
	}
	if ( isset( $_POST['edd_license_deactivate'] ) && $_POST['edd_license_deactivate'] == 'Deactivate License' ) {

		$old = $option[ $value . '_license_key' ];
		if ( $old && $old != $new[ $value . '_license_key' ] ) {
			$arr[ $value . '_license_status' ] = '';
			$wte_fixed_departure_status_new    = array_merge_recursive( $option, $arr );
			update_option( 'wp_travel_engine_license', $wte_fixed_departure_status_new );
			$new[ $value . '_license_key' ]    = $option[ $value . '_license_key' ];
			$new[ $value . '_license_status' ] = '';
		}
	}
	if ( isset( $_POST['submit'] ) ) {
		foreach ( $addon_name as $key => $val ) {
			$license_key = '';
			if ( isset( $_POST['wp_travel_engine_license'][ $val . '_license_key' ] ) ) {
				$license_key = wp_filter_nohtml_kses( wp_unslash( $_POST['wp_travel_engine_license'][ $val . '_license_key' ] ) );
			}
			$new[ $val . '_license_key' ]    = $license_key;
			$new[ $val . '_license_status' ] = isset( $option[ $val . '_license_status' ] ) ? esc_attr( $option[ $val . '_license_status' ] ) : false;
		}
	}
	// phpcs:enable
	return $new;
}

function wptravelengine_response_messages( $code, $response = null ) {
	switch ( $code ) {
		case 'expired':
			$message = sprintf(
				__( 'Your license for %1$s extension expired on %2$s. To ensure you get features and security updates, having an active license is strongly recommended, and in some cases required.', 'wp-travel-engine' ),
				$response->item_name,
				wp_date( get_option( 'date_format' ), strtotime( $response->expires, current_time( 'timestamp' ) ) )
			);
			break;

		case 'disabled':
		case 'revoked':
			$message = esc_html__( 'Your license key has been disabled.', 'wp-travel-engine' );
			break;

		case 'missing':
			$message = esc_html__( 'Invalid license key supplied. Please check if you have entered correct license key.', 'wp-travel-engine' );
			break;

		case 'invalid':
		case 'site_inactive':
			$message = esc_html__( 'Your license is not active for this URL.', 'wp-travel-engine' );
			break;

		case 'item_name_mismatch':
			$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'wp-travel-engine' ), EDD_SAMPLE_ITEM_NAME );
			break;

		case 'no_activations_left':
			$message = esc_html__( 'Your license key has reached its activation limit.', 'wp-travel-engine' );
			break;

		default:
			$message = __( 'An error occurred, please try again.', 'wp-travel-engine' );
			break;
	}

	return $message;
}

function wp_travel_engine_activate_license() {
	// listen for our activate button to be clicked
	// run a quick security check
	// if( ! check_admin_referer( 'wp_travel_engine_license_nonce', 'wp_travel_engine_license_nonce' ) )
	// return; // get out if we didn't click the Activate button
	$wp_travel_engine = get_option( 'wp_travel_engine_license', array() );
	// phpcs:disable
	$addon_name = wte_clean( wp_unslash( $_POST['addon_name'] ) );
	$addon_id   = apply_filters( 'wp_travel_engine_addons_id', array() );
	// retrieve the license from the database
	$wte_fixed_departure_license = isset( $wp_travel_engine[ $addon_name . '_license_key' ] ) ? esc_attr( $wp_travel_engine[ $addon_name . '_license_key' ] ) : false;
	// data to send in our API request
	$api_params = array(
		'edd_action' => 'activate_license',
		'license'    => $wte_fixed_departure_license,
		'item_id'    => $addon_id[ $addon_name ], // The ID of the item in EDD
		'url'        => home_url(),
	);

	// Call the custom API.
	$response = wp_remote_post(
		WP_TRAVEL_ENGINE_STORE_URL,
		array(
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => $api_params,
		)
	);
	// make sure the response came back okay
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

		$message = ( is_wp_error( $response ) && ( $response->get_error_message() ) != '' ) ? $response->get_error_message() : __( 'An error occurred, please try again.', 'wp-travel-engine' );

	} else {

		$wte_fixed_departure_license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! $wte_fixed_departure_license_data->success ) {

			$message = wptravelengine_response_messages( $wte_fixed_departure_license_data->error, $wte_fixed_departure_license_data );

		}

		update_option( $addon_name . '_license_active', $wte_fixed_departure_license_data );

	}

	if ( isset( $wte_fixed_departure_license_data->license ) ) {
		$wp_travel_engine[ "{$addon_name}_licence_status" ] = $wte_fixed_departure_license_data->license;
		update_option( 'wp_travel_engine_license', $wp_travel_engine );
	}
	// Check if anything passed on a message constituting a failure
	if ( ! empty( $message ) ) {
		$base_url = admin_url( 'edit.php?post_type=booking&page=wp_travel_engine_license_page' );
		// $redirect = add_query_arg( array( 'sl_activation' => 'false' ), $base_url );

		$redirect_url = add_query_arg(
			array(
				'wte_license_error_msg' => urlencode( $message ),
				'wte_addon_name'        => $addon_name,
			),
			$base_url
		);
		wp_safe_redirect( $redirect_url );
		exit();
	}

	wp_redirect( admin_url( 'edit.php?post_type=booking&page=wp_travel_engine_license_page' ) );
	exit();
	// phpcs:enable
}

function wp_travel_engine_deactivate_license() {

	if ( isset( $_POST['edd_license_deactivate'] ) ) {
		// run a quick security check
		if ( ! check_admin_referer( 'wp_travel_engine_license_nonce', 'wp_travel_engine_license_nonce' ) ) {
			return;
		}
		// phpcs:disable
		$wp_travel_engine = get_option( 'wp_travel_engine_license' );

		$addon_name = wte_clean( wp_unslash( $_POST['addon_name'] ) );
		$addon_id   = apply_filters( 'wp_travel_engine_addons_id', array() );
		// retrieve the license from the database
		$wte_fixed_departure_license = isset( $wp_travel_engine[ $addon_name . '_license_key' ] ) ? esc_attr( $wp_travel_engine[ $addon_name . '_license_key' ] ) : false;

		// data to send in our API request
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $wte_fixed_departure_license,
			'item_id'    => $addon_id[ $addon_name ], // The ID of the item in EDD
			'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post(
			WP_TRAVEL_ENGINE_STORE_URL,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$message = ( is_wp_error( $response ) && ( $response->get_error_message() ) != '' ) ? $response->get_error_message() : __( 'An error occurred, please try again.', 'wp-travel-engine' );
		} else {
			$response = json_decode( wp_remote_retrieve_body( $response ) );

		}

		wp_redirect( admin_url( 'edit.php?post_type=booking&page=wp_travel_engine_license_page' ) );
		exit();
		// phpcs:enable
	}
}

/**
 * Admin notices related to the licenses.
 *
 * @return void
 */
add_action(
	'admin_notices',
	function() {
		// Show notices for each invalid licences.
		wptravelengine_show_invalid_notice();

		// phpcs:disable
		// If sl_activation.
		if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {
			switch ( $_GET['sl_activation'] ) {
				case 'false':
					$message = urldecode( wp_unslash( $_GET['message'] ) ); // phpcs:ignore
					?>
					<div class="error">
						<p><?php echo esc_html( $message ); ?></p>
					</div>
					<?php
					break;
				case 'true':
					?>
					<div id="message" class="updated inline">
						<p><?php esc_html_e( 'Your license has been activated.', 'wp-travel-engine' ); ?></p>
					</div>
					<?php
					break;
			}
		}

		// If License Page.
		if ( isset( $_GET['page'] ) && 'wp_travel_engine_license_page' === $_GET['page'] ) {
			wp_travel_engine_show_update_notification();
		}
		// phpcs:enable
	}
);

function wptravelengine_show_invalid_notice() {
	$option     = get_option( 'wp_travel_engine_license' );
	$addon_name = apply_filters( 'wp_travel_engine_addons', array() );
	$a_name     = apply_filters( 'wp_travel_engine_licenses', array() );
	$b_name     = apply_filters( 'wp_travel_engine_addons_id', array() );

	foreach ( $addon_name as $key => $value ) {
		if ( ! empty( $option[ $value . '_license_value' ] ) && isset( $option[ $value . '_license_status' ] ) && $option[ $value . '_license_status' ] != 'valid' ) {
			$message  = '<div class="error">';
			$message .= '<p>' . __( 'You have invalid or expired license keys for WP Travel Engine. Please go to the <a href="%s">Licenses page</a> to correct this issue.', 'wp-travel-engine' ) . '</p>';
			$message .= '</div>';
			echo sprintf(
				$message, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_url( admin_url( 'edit.php?post_type=booking&page=wp_travel_engine_license_page' ) )
			);
			break;
		}
	}
}

/**
 * Check License Validation.
 *
 * @return void
 */
function wptravelengine_addon_check_license( $addon ) {
	$verify_ssl      = wptravelengine_verify_ssl();
	$licence_options = get_option( 'wp_travel_engine_license', array() );

	$addon = (array) $addon;

	$addon_slug = $addon['slug'];

	$response = wp_remote_post(
		'https://wptravelengine.com/',
		array(
			'timeout'   => 15,
			'sslverify' => $verify_ssl,
			'body'      => array(
				'edd_action' => 'check_license',
				'license'    => $addon['license_key'],
				'item_name'  => $addon['name'],
				'item_id'    => $addon['item_id'],
				'version'    => $addon['version'],
				'slug'       => $addon['slug'],
				'author'     => 'WP Travel Engine',
				'url'        => home_url(),
				'beta'       => '',
			),
		)
	);

	if ( ! is_wp_error( $response ) ) {
		return json_decode( wp_remote_retrieve_body( $response ) );
	}

	return $response;
}

/**
 * Get Addon version remotely.
 *
 * @param [type] $addon
 * @return void
 */
function wptravelengine_addon_get_version( $addon ) {

	$update_cache    = get_site_transient( 'update_plugins' );
	$update_cache    = is_object( $update_cache ) ? $update_cache : new stdClass();
	$licence_options = get_option( 'wp_travel_engine_license', array() );

	$addon = (object) $addon;

	if ( ! empty( $update_cache->response[ $addon->filepath ] ) ) {
		$response = $update_cache->response[ $addon->filepath ];
	} else {
		$verify_ssl = wptravelengine_verify_ssl();

		$addon_slug = $addon->slug;
		// Request to get current version.
		$response = wp_remote_post(
			'https://wptravelengine.com/',
			array(
				'timeout'   => 15,
				'sslverify' => $verify_ssl,
				'body'      => array(
					'edd_action' => 'get_version',
					'license'    => $addon->{'license_key'},
					'item_name'  => $addon->name,
					'item_id'    => $addon->item_id,
					'version'    => $addon->version,
					'slug'       => $addon->slug,
					'author'     => 'WP Travel Engine',
					'url'        => home_url(),
					'beta'       => '',
				),
			)
		);
		if ( ! is_wp_error( $response ) ) {
			$response = json_decode( wp_remote_retrieve_body( $response ) );

			if ( version_compare( $addon->version, $response->new_version, '<' ) ) {
				$update_cache->response[ $addon->filepath ] = $response;
			}
			$update_cache->last_checked                = time();
			$update_cache->checked[ $addon->filepath ] = $addon->version;

			set_site_transient( 'update_plugins', $update_cache );
		}
	}

	return $response;
}

function wptravelengine_show_addon_expired_notice( $addon ) {
	$value = str_replace( '_', '-', $addon['slug'] );

	$table = '<tr class="plugin-update-tr" id="' . $value . '-update" data-slug="' . $value . '" data-plugin="' . $aaa . '">';

	$table .= '<td colspan="3" class="plugin-update colspanchange">';

	$changelog_link = self_admin_url( 'index.php?edd_sl_action=view_plugin_changelog&plugin=' . $value . '&slug=' . $value . '&TB_iframe=true&width=772&height=911' );
	if ( ! empty( $version_info->download_link ) ) {
		$table .= sprintf(
			__( 'The license of %1$s has expired. %2$sRenew Now%3$s', 'wp-travel-engine' ),
			esc_html( $version_info->name ),
			'<a href="https://wptravelengine.com/plugins/" target="_blank">',
			'</a>'
		);
	}

	$table .= '</td></tr>';
	echo '<div class="update-message notice inline notice-warning notice-alt">';
		echo '<p>' . $table . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</div>';
}

/**
 * Checks if license expired for the plugin.
 *
 * @return void
 * @since 5.0.0
 */
function wptravelengine_is_plugin_license_expired( $addon ) {
	$response = wptravelengine_addon_check_license( $addon );

	return isset( $response->license ) && 'expired' == $response->license;
}

/**
 * Checks if license active for the plugin.
 *
 * @return void
 * @since 5.0.0
 */
function wptravelengine_is_plugin_license_active( $addon ) {
	return ! wptravelengine_is_plugin_license_expired( $addon );
}

/**
 * Gets addons list.
 *
 * @return array
 */
function wptravelengine_get_licensed_addons( $addon_id = null, $key = 'id' ) {
	$plugins = wte_get_engine_extensions();

	$licenses        = get_option( 'wp_travel_engine_license', array() );
	$licensed_addons = array();
	foreach ( $plugins as $plugin_id => $plugin ) {
		$slug = str_replace( array( 'wp-travel-engine-', '-' ), array( 'wte_', '_' ), $plugin['TextDomain'] );

		if ( $slug === 'wte_payumoney_bolt' ) {
			$slug = 'wte_payu_money_bolt_checkout';
		}
		if ( $slug === 'wte_fixed_departure_dates' ) {
			$slug = 'wte_fixed_starting_dates';
		}
		if ( $slug === 'wte_paypalexpress' ) {
			$slug = 'wte_paypal_express';
		}
		if ( $slug === 'wte_hbl' ) {
			$slug = 'wte_hbl_payment';
		}
		$item_id = wte_get_extensions_ids( $slug );
		if ( ! is_plugin_active( $plugin_id ) || ! $item_id ) {
			continue;
		}

		$addon = array(
			'slug'        => $slug,
			'item_id'     => $item_id,
			'author'      => 'WP Travel Engine',
			'url'         => home_url(),
			'version'     => $plugin['Version'],
			'name'        => call_user_func(
				function( $name ) {
					$addon_name = $name;
					$addon_title = $addon_name;
					$addon_name = substr( $addon_name, strpos( $addon_name, '-' ) + 1 );
					$addon_name  = str_replace( ' ', '+', $addon_name );
					return ltrim( $addon_name, '+' );
				},
				$plugin['Name']
			),
			'title'       => substr( $plugin['Name'], strpos( $plugin['Name'], '-' ) + 1 ),
			'filepath'    => $plugin_id,
			'license_key' => ! empty( $licenses[ "{$slug}_license_key" ] ) ? $licenses[ "{$slug}_license_key" ] : '',
		);

		$licensed_addons[ $item_id ] = $addon;
	}

	return $licensed_addons;
}

/**
 * show update nofication row -- needed for multisite subsites, because WP won't tell you otherwise!
 *
 * @param string $file
 * @param array  $plugin
 */
function wp_travel_engine_show_update_notification() {

	$plugins = wptravelengine_get_licensed_addons();
	foreach ( $plugins as $plugin_id => $addon ) {
		$addon_versions = wptravelengine_addon_get_version( $addon );

		$plugin_license = wptravelengine_addon_check_license( $addon );

		$addon = (object) $addon;
		if ( isset( $plugin_license->license ) ) {
			switch ( $plugin_license->license ) {
				case 'invalid':
					break;
				case 'expired':
					$value = str_replace( '_', '-', $addon->slug );

					$table  = '<div class="update-message notice inline notice-warning notice-alt">';
					$table .= '<tr class="plugin-update-tr" id="' . $value . '-update" data-slug="' . $value . '" data-plugin="' . $addon->filepath . '">';
					$table .= '<td colspan="3" class="plugin-update colspanchange">';
					$table .= '%1$s';
					$table .= '</td></tr>';
					$table .= '</div>';

					$message = '<p>' . sprintf(
						__( 'Your license for %1$s extension expired on %2$s. To ensure you get features and security updates, having an active license is strongly recommended, and in some cases required. Click %3$shere%4$s to renew the license.', 'wp-travel-engine' ),
						"<strong>{$addon->title}</strong>",
						'<strong>' . wp_date( get_option( 'date_format', 'F j, Y' ), strtotime( $plugin_license->expires, current_time( 'timestamp' ) ) ) . '</strong>',
						'<a href="https://wptravelengine.com/plugins/" target="_blank">',
						'</a>'
					) . '</p>';

					if ( ! empty( $addon->version ) && version_compare( $addon->version, $addon_versions->new_version, '<' ) ) {
						$message .= '<p>' . __(
							sprintf(
								'There is a new version of %1$s available. To update please %2$srenew%3$s the license.',
								"<b>{$addon->title}</b>",
								'<a href="https://wptravelengine.com/plugins/" target="_blank">',
								'</a>'
							),
							'wp-travel-engine'
						) . '</p>';
					}

					echo sprintf( $table, $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

					break;
				case 'valid':
					if ( ! empty( $addon->version ) && version_compare( $addon->version, $addon_versions->new_version, '<' ) ) {
						$value = str_replace( '_', '-', $addon->slug );

						$table  = '<tr class="plugin-update-tr" id="' . $value . '-update" data-slug="' . $value . '" data-plugin="' . $addon->filepath . '">';
						$table .= '<td colspan="3" class="plugin-update colspanchange">';

						$changelog_link = self_admin_url( 'index.php?edd_sl_action=view_plugin_changelog&plugin=' . $value . '&slug=' . $value . '&TB_iframe=true&width=772&height=911' );
						if ( ! empty( $addon_versions->download_link ) ) {
							$table .= sprintf(
								__( 'There is a new version of %1$s available. Download and replace the older version. %2$sGet it Now%3$s', 'wp-travel-engine' ),
								esc_html( $addon_versions->name ),
								'<a href="' . esc_url( $addon_versions->download_link ) . '">',
								'</a>'
							);
						}

						$table .= '</td></tr>';
						echo '<div class="update-message notice inline notice-warning notice-alt">';
							echo '<p>' . $table . '</p>'; // phpcs:ignore
						echo '</div>';
					}
					break;
				default:
					break;
			}
		}
	}
}

/**
 * Should verify ssl or not.
 *
 * @return bool
 * @since 5.0.0
 */
function wptravelengine_verify_ssl() {
	return (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true );
}

if ( ! function_exists( 'verfiy_ssl' ) ) {
	function verify_ssl() {
		return wptravelengine_verify_ssl();
	}
}
