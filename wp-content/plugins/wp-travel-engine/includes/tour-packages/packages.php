<?php
/**
 * WP Travel Engine Pricing Packages.
 *
 * @package wte/pricing
 * @since 5.3.0
 */

namespace WPTravelEngine;

use WPTravelEngine\Plugin;
use WPTravelEngine\Packages;
use WPTravelEngine\Traits\Singleton;

/**
 * WPTravelEngine\Pricing_Packages.
 *
 * @since 5.3.0
 */
class TourPackages {

	use Singleton;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->constants();
		$this->init();
	}

	/**
	 * Defines constants.
	 */
	private function constants() {

		$admin_dir = plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'admin/';

		foreach ( array( 'WTE_PRICING_TAB_PARTIALS_DIR' => "{$admin_dir}partials/trip/edit/tab-pricing/" ) as $constant => $value ) {
			defined( $constant ) || define( $constant, $value );
		}
	}

	/**
	 * Init.
	 */
	private function init() {
		add_action( 'init', array( $this, 'use_legacy_version_trip' ), 9 );
		add_action( 'init', array( $this, 'register_packages_post_types' ) );

		add_action(
			'init',
			function() {
				if ( get_option( 'wte_migrated_to_multiple_pricing', false ) !== 'done' ) {
					if ( ! function_exists( '\WTE\Upgrade500\wte_process_migration' ) ) {
						include_once sprintf( '%s/upgrade/500.php', WP_TRAVEL_ENGINE_BASE_PATH );
						\WTE\Upgrade500\wte_process_migration();
					}
				}
			}
		);

		add_action(
			'admin_menu',
			function() {
				global $submenu;

				$submenu['edit.php?post_type=trip'][20] = array(
					__( 'Pricing Categories', 'wp-travel-engine' ),
					'manage_categories',
					'edit-tags.php?taxonomy=trip-packages-categories&amp;post_type=trip',
				);
				return $submenu;

			}
		);

		add_action( 'rest_api_init', array( $this, 'register_rest_fields' ) );

		add_filter(
			'wp_travel_engine_admin_trip_meta_tabs',
			function( $trip_edit_tabs ) {
				global $post;

				if ( ! defined( 'USE_WTE_LEGACY_VERSION' ) && ( get_post_meta( $post->ID, 'trip_version', true ) === '2.0.0' ) || 'draft' === $post->post_status ) {
					wp_enqueue_script( 'wte-rxjs' );
					wp_enqueue_script( 'wte-redux' );
					unset( $trip_edit_tabs['wpte-availability'] );
					$trip_edit_tabs['wpte-pricing']['tab_label']    = __( 'Pricings and Dates', 'wp-travel-engine' );
					$trip_edit_tabs['wpte-pricing']['content_path'] = WTE_PRICING_TAB_PARTIALS_DIR . 'tab-pricing.php';
				}
				return $trip_edit_tabs;
			},
			11
		);

		add_filter(
			'wte_admin_localize_data',
			function( $data ) {
				$screen = get_current_screen();
				if ( $screen && $screen->id !== 'trip' ) {
					return $data;
				}
				global $post;

				if ( ! isset( $post->ID ) ) {
					return $data;
				}

				$trip_version = get_post_meta( $post->ID, 'trip_version', true );

				$data['wteEdit'] = array(
					'handle' => 'wp-travel-engine',
					'l10n'   => array(
						'tripID'                        => $post->ID,
						'tripMigratedToMultiplePricing' => version_compare( $trip_version, '2.0.0', '>=' ) && ( ! defined( 'USE_WTE_LEGACY_VERSION' ) || ! USE_WTE_LEGACY_VERSION ),
						'tripVersion'                   => $trip_version,
					),
				);

				return $data;
			}
		);

		add_action( 'wpte_save_and_continue_additional_meta_data', '\WPTravelEngine\Packages\update_trip_packages', 10, 2 );
		add_action(
			'save_post_' . \WP_TRAVEL_ENGINE_POST_TYPE,
			function( $post_ID, $post, $update = false ) {
				if ( ! $update ) {
					$trip_version = get_post_meta( $post_ID, 'trip_version', true );
					if ( empty( $trip_version ) ) {
						update_post_meta( $post_ID, 'trip_version', '2.0.0' );
					}
				}
				if ( \WP_TRAVEL_ENGINE_POST_TYPE === $post->post_type ) {
					$posted_data = array();
					foreach ( array(
						'trip-edit-tab__dates-pricings' => array(),
						'packages_ids'                  => array(),
						'packages_titles'               => array(),
						'packages_descriptions'         => array( 'sanitize_callback' => 'wp_kses_post' ),
						'categories'                    => array(),
						'packages_primary_category'     => array(),
					) as $key => $args ) {
						if ( isset( $_POST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
							if ( isset( $args['sanitize_callback'] ) ) {
								if ( is_array( $_POST[ $key ] ) ) {
									$posted_data[ $key ] = array_map( $args['sanitize_callback'], $_POST[ $key ] );
								} else {
									if ( is_scalar( $_POST[ $key ] ) ) {
										$posted_data[ $key ] = call_user_func( $args['sanitize_callback'], wp_unslash( $_POST[ $key ] ) );
									} else {
										$posted_data[ $key ] = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
									}
								}
							} else {
								$posted_data[ $key ] = wte_clean( wp_unslash( $_POST[ $key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
							}
						}
					}
					\WPTravelEngine\Packages\update_trip_packages( $post_ID, array_merge( $_POST, $posted_data ) );
				}
			},
			10,
			3
		);

		add_action(
			'wte_after_single_trip',
			function() {
				\WPTravelEngine\Assets::append_dependency( 'wp-travel-engine', 'wte-redux' );
				wte_get_template( 'script-templates/booking-process/wte-booking.php' );
			}
		);

		add_action(
			'trip-packages-categories_edit_form_fields',
			function( $tag, $taxonomy ) {
				$meta_value = get_option( 'primary_pricing_category', '' );
				$age_group  = get_term_meta( $tag->term_id, 'age_group', true );
				?>
				<tr class="form-field">
					<th class="row">
						<label for="package-primary-catgory"><?php esc_html_e( 'Set as Primary Pricing Catgeory', 'wp-travel-engine' ); ?></label>
					</th>
					<td>
						<input type="checkbox" <?php checked( $tag->term_id, $meta_value ); ?> name="is_primary_pricing_catgory" value="1" id="package-primary-catgory">
						<p><?php esc_html_e( 'If checked, this category will be treated as primary pricing category in packages and trip price will be the price of this category.', 'wp-travel-engine' ); ?></p>
					</td>
				</tr>
				<tr class="form-field">
					<th class="row">
						<label for="category-age-group"><?php esc_html_e( 'Age Group', 'wp-travel-engine' ); ?></label>
					</th>
					<td>
						<input type="text" placeholder="16-30" name="age_group" value="<?php echo esc_attr( $age_group ); ?>" id="category-age-group">
						<p><?php esc_html_e( 'Age Group of the category.', 'wp-travel-engine' ); ?></p>
					</td>
				</tr>
				<?php
				if ( function_exists( 'pll_current_language' ) ) {
					$language        = \pll_current_language();
					$translated_name = get_term_meta( $tag->term_id, 'pll_category_name', true );
					$translated_name = ! empty( $translated_name[ $language ] ) ? $translated_name[ $language ] : $tag->name;
					if ( $language ) {
						?>
						<tr class="form-field">
							<th class="row">
								<label for="category-age-group"><?php esc_html_e( 'Translated Category Name - ' . $language, 'wp-travel-engine' ); ?></label>
							</th>
							<td>
								<input type="text" name="pll_category_name[<?php echo esc_attr( $language ); ?>]" value="<?php echo esc_attr( $translated_name ); ?>" id="category-pll-name">
								<p><?php esc_html_e( 'Translated name for the category.', 'wp-travel-engine' ); ?></p>
							</td>
						</tr>
						<?php
					}
				}
			},
			999999,
			2
		);

		add_action(
			'trip-packages-categories_add_form_fields',
			function ( $taxonomy ) {
				?>
				<div class="form-field">
					<label for="package-primary-catgory"><?php esc_html_e( 'Set as Primary Pricing Catgeory', 'wp-travel-engine' ); ?></label>
					<input type="checkbox" name="is_primary_pricing_catgory" value="1" id="package-primary-catgory">
					<p><?php esc_html_e( 'If checked, this category will be treated as primary pricing category in packages and trip price will be the price of this category.', 'wp-travel-engine' ); ?></p>
				</div>
				<div class="form-field">
					<label for="category-age-group"><?php esc_html_e( 'Age Group', 'wp-travel-engine' ); ?></label>
					<input type="text" placeholder="16-30" name="age_group" value="" id="category-age-group">
					<p><?php esc_html_e( 'Age Group of the category.', 'wp-travel-engine' ); ?></p>
				</div>
				<?php
			},
			11,
			2
		);

		add_action( 'saved_trip-packages-categories', array( $this, 'save_trip_categories_meta' ) );
		add_action( 'update_trip-packages-categories', array( $this, 'save_trip_categories_meta' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

	}

	/**
	 * Admin notices.
	 */
	public function admin_notices() {
		$admin_notices = apply_filters(
			'wte_admin_notices',
			array(
				'notice'  => array(),
				'warning' => array(),
				'error'   => array(),
			)
		);
		foreach ( $admin_notices  as $type => $messages ) {
			if ( $messages && count( $messages ) > 0 ) {
				echo '<div class="notice notice-' . esc_attr( $type ) . '">';
				foreach ( $messages as $message ) {
					echo '<p>' . wp_kses(
						$message,
						array(
							'code' => array(),
							'a'    => array(
								'href'   => array(),
								'class'  => array(),
								'id'     => array(),
								'target' => array(),
							),
						)
					) . '</p>';
				}
				echo '</div>';
			}
		}
	}

	/**
	 * Saves trip categories meta.
	 */
	public function save_trip_categories_meta( $term_id ) {
		// phpcs:disable
		$value = '';
		if ( isset( $_REQUEST['is_primary_pricing_catgory'] ) ) {
			$value = $_REQUEST['is_primary_pricing_catgory'] === '1' ? '1' : '';
			update_option( 'primary_pricing_category', $term_id );
		}
		if ( isset( $_REQUEST['age_group'] ) ) {
			$value = wp_kses( wp_unslash( $_REQUEST['age_group'] ), array() );
			update_term_meta( $term_id, 'age_group', $value );
		}
		if ( isset( $_REQUEST['pll_category_name'] ) ) {
			$value = wp_unslash( $_REQUEST['pll_category_name'] );
			$previous_data = get_term_meta( $term_id, 'pll_category_name', true );
			if ( is_array( $previous_data ) ) {
				$value = array_merge( $previous_data, $value );
			}
			update_term_meta( $term_id, 'pll_category_name', $value );
		}
		update_term_meta( $term_id, 'is_primary_pricing_catgory', (bool) $value );
		// phpcs:enable
	}

	/**
	 * Register rest fields.
	 */
	public function register_rest_fields( $wp_rest_server ) {
		$trip_meta = null;

		// Posttype: trip-packages.
		$packages_post_type = 'trip-packages';

		$trip_packages_rest_field = apply_filters(
			"wte_rest_fields__{$packages_post_type}",
			array(
				'package-dates'            => array(
					'type'         => 'array',
					'schema'       => array(
						'items' => array(
							'type'       => 'object',
							'properties' => array(
								'dtstart'           => array(
									'type' => 'string',
								),
								'dtend'             => array(
									'type' => 'string',
								),
								'seats'             => array(
									'type' => 'string',
								),
								'availability_type' => array(
									'type' => 'string',
								),
								'rrule'             => array(
									'type'       => 'object',
									'properties' => array(
										'enable'      => array(
											'type' => 'boolean',
										),
										'r_dtstart'   => array(
											'type' => 'string',
										),
										'r_frequency' => array(
											'type' => 'string',
										),
										'r_weekdays'  => array(
											'type'   => 'array',
											'schema' => array(
												'items' => array(
													'type' => 'string',
												),
											),
										),
										'r_until'     => array(
											'type' => 'string',
										),
										'r_months'    => array(
											'type'   => 'array',
											'schema' => array(
												'items' => array(
													'type' => 'number',
												),
											),
										),
										'r_count'     => array(
											'type' => 'number',
										),
									),
								),
							),
						),
					),
					'get_callback' => function( $prepared, $field ) {
						$trip_id = get_post_meta( $prepared['id'], 'trip_ID', true );
						$trip_duration_unit = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
						if ( ! empty( $trip_duration_unit['trip_duration_unit'] ) ) {
							$trip_duration_unit = $trip_duration_unit['trip_duration_unit'];
						} else {
							$trip_duration_unit = 'days';
						}
						$enabled = get_post_meta( $prepared['id'], 'enable_weekly_time_slots', true ) === 'yes' && 'hours' === $trip_duration_unit;
						if ( ! $enabled ) {
							return array();
						}
						$dates = get_post_meta( $prepared['id'], $field, true );
						return empty( $dates ) ? array() : $dates;
					},
				),
				'package-categories'       => array(
					'type'         => 'array',
					'schema'       => array(
						'type' => 'array',
					),
					'get_callback' => function( $prepared, $field ) {
						global $wpdb;

						if ( ! empty( $_REQUEST['lang'] ) ) {
							$locale = sanitize_text_field( wp_unslash( $_REQUEST['lang'] ) );
						}

						$categories = Packages\get_packages_pricing_categories();

						$package_categories = get_post_meta( $prepared['id'], $field, true );
						$new_categories = array();
						foreach ( $categories as $category ) {
							if ( ! isset( $package_categories['c_ids'][ $category->term_id ] ) ) {
								$new_categories[ $category->term_id ] = array(
									'label'                => $category->name,
									'price'                => '',
									'enabledSale'          => ! 1,
									'salePrice'            => '',
									'priceType'            => 'per-person',
									'minPax'               => '',
									'maxPax'               => '',
									'groupPricing'         => array(),
									'enabledGroupDiscount' => ! 1,
								);
								continue;
							}
							foreach ( array(
								'labels'                 => array( 'label', '' ),
								'prices'                 => array( 'price', '' ),
								'enabled_sale'           => array( 'enabledSale', ! 1 ),
								'sale_prices'            => array( 'salePrice', 0 ),
								'min_paxes'              => array( 'minPax', 0 ),
								'max_paxes'              => array( 'maxPax', '' ),
								'group-pricings'         => array( 'groupPricing', array() ),
								'enabled_group_discount' => array( 'enabledGroupDiscount', ! 1 ),
								'pricing_types'          => array( 'pricingType', 'per-person' ),
							) as $key => $args ) {
								if ( isset( $package_categories[ $key ][ $category->term_id ] ) ) {
									$value = $package_categories[ $key ][ $category->term_id ];
									if ( in_array( $key, array( 'prices', 'sale_prices', 'min_paxes', 'max_paxes' ), true ) ) {
										$value = '' === $value ? '' : (float) $value;
									} elseif ( 'enabled_group_discount' === $key ) {
										$value = 1 === (int) $value;
									} elseif ( 'labels' === $key ) {
										if ( ! empty( $locale ) ) {
											$term_meta = get_term_meta( $category->term_id, 'pll_category_name', true );
											if ( isset( $term_meta[ $locale ] ) ) {
												$value = $term_meta[ $locale ];
											} else {
												$value = $category->name;
											}
										} else {
											$value = $category->name;
										}
									}
									$new_categories[ $category->term_id ][ $args[0] ] = $value;
								} else {
									$new_categories[ $category->term_id ][ $args[0] ] = $args[1];
								}
							}
						}

						return $new_categories;
					},
				),
				'group-pricing'            => array(
					'type'         => 'array',
					'schema'       => array(
						'type' => 'array',
					),
					'get_callback' => function( $prepared, $field ) {
						return new \stdClass();
					},
				),
				'primary_pricing_category' => array(
					'type'         => 'array',
					'schema'       => array(
						'type' => 'array',
					),
					'get_callback' => function( $prepared, $field ) {
						return (int) get_post_meta( $prepared['id'], $field, true );
					},
				),
				'trip_ID'                  => array(
					'type'         => 'number',
					'get_callback' => function( $prepared, $field ) {
						return (int) get_post_meta( $prepared['id'], $field, true );
					},
					'schema'       => array(
						'type' => 'number',
					),
				),
				'weekly_time_slots'        => array(
					'type'         => 'array',
					'schema'       => array(
						'type' => 'array',
					),
					'get_callback' => function( $prepared, $field ) {
						$value = get_post_meta( $prepared['id'], $field, true );
						if ( empty( $value ) ) {
							$value = array(
								1 => array(),
								2 => array(),
								3 => array(),
								4 => array(),
								5 => array(),
								6 => array(),
								7 => array(),
							);
						}

						return $value;
					},
				),
				'has_weekly_time_slots'    => array(
					'type'         => 'boolean',
					'schema'       => array(
						'type' => 'boolean',
					),
					'get_callback' => function( $prepared, $field ) {
						$enabled = get_post_meta( $prepared['id'], 'enable_weekly_time_slots', true ) === 'yes';
						return true && $enabled;
					},
				),
			)
		);

		foreach ( $trip_packages_rest_field as $attribute => $args ) {

			register_rest_field(
				$packages_post_type,
				$attribute,
				array(
					'get_callback'    => wte_array_get( $args, 'get_callback', array( '\WPTravelEngine\Core\REST_API', 'default_rest_field_get_callback' ) ),
					'update_callback' => function() {},
					'schema'          => $args['schema'],
				)
			);
		}

		// Posttype: trip.
		$fields = apply_filters(
			'wte_rest_field__' . \WP_TRAVEL_ENGINE_POST_TYPE,
			array(
				'packages_ids' => array(
					'schema' => array(
						'type'   => 'array',
						'schema' => array( 'items' => 'number' ),
					),
				),
				'trip_extras'  => array(
					'schema'       => array(
						'type'   => 'array',
						'schema' => array( 'items' => 'number' ),
					),
					'get_callback' => function( $object, $field_name, $default ) {
						return array();
					},
				),
				'cut_off_time' => array(
					'schema'       => array(
						'type'   => 'array',
						'schema' => array( 'items' => 'number' ),
					),
					'get_callback' => function( $object, $field_name, $default ) {
						$trip_settings = get_post_meta( $object['id'], 'wp_travel_engine_setting', true );
						return array(
							'enabled'       => (bool) wte_array_get( $trip_settings, 'trip_cutoff_enable', ! 1 ),
							'duration'      => (int) wte_array_get( $trip_settings, 'trip_cut_off_time', 0 ),
							'duration_unit' => wte_array_get( $trip_settings, 'trip_cut_off_unit', 'days' ),
						);
					},
				),
				'booked-seats' => array(
					'type'         => 'array',
					'get_callback' => function( $prepared, $field ) {
						if ( ! defined( 'WTE_FIXED_DEPARTURE_VERSION' ) ) {
							return array();
						}
						$booked_seats = \WPTravelEngine\Core\Booking_Inventory::booking_inventory( null, $prepared['id'] );
						return ! empty( $booked_seats ) ? $booked_seats : array();
					},
					'schema'       => array(
						'type' => 'array',
					),
				),
			)
		);

		foreach ( $fields as $attribute => $args ) {
			register_rest_field(
				\WP_TRAVEL_ENGINE_POST_TYPE,
				$attribute,
				array(
					'get_callback'    => wte_array_get( $args, 'get_callback', array( '\WPTravelEngine\Core\REST_API', 'default_rest_field_get_callback' ) ),
					'update_callback' => function() {

					},
					'schema'          => $args['schema'],
				)
			);
		}

		// Taxonomy: Pricing Categories
		register_rest_field(
			'trip-packages-categories',
			'is-primary',
			array(
				'get_callback' => function( $object, $field_name, $default ) {
					return get_option( 'primary_pricing_category', 0 ) == $object['id'];
				},
			)
		);
	}

	/**
	 * Check if legacy trip requests and defines a constant.
	 */
	public function use_legacy_version_trip() {
		if ( ! wte_nonce_verify( 'nonce', 'wpte_global_tabs_save_data' ) ) {
			return;
		}
		// phpcs:disable
		if ( isset( $_POST['enable_legacy_trip'] ) ) {
			update_option( 'enable_legacy_trip', sanitize_text_field( wp_unslash( $_POST['enable_legacy_trip'] ) ), true );
		}
		// phpcs:enable

		if ( get_option( 'enable_legacy_trip' ) === 'yes' ) {
			! defined( 'USE_WTE_LEGACY_VERSION' ) && define( 'USE_WTE_LEGACY_VERSION', true );
		}
	}

	/**
	 * Register post types and taxonomies for packages.
	 */
	public function register_packages_post_types() {
		$post_type = 'trip-packages';
		register_post_type(
			$post_type,
			array(
				'label'        => __( 'Trip Packages', 'wp-travel-engine' ),
				'public'       => false,
				'show_in_rest' => true,
				'rest_base'    => 'packages',
				'supports'     => array( 'title', 'editor', 'custom-fields' ),
				'show_in_menu' => false,
			)
		);

		register_taxonomy(
			'trip-packages-categories',
			$post_type,
			array(
				'public'       => true,
				'show_in_rest' => true,
				'rest_base'    => 'package-categories',
				'hierarchical' => true,
				'show_in_menu' => true,
			)
		);

		register_term_meta(
			'trip-packages-categories',
			'is_primary_pricing_catgory',
			array(
				'type'         => 'boolean',
				'description'  => __( 'If the term is set as primary category for pricing.', 'wp-travel-engine' ),
				'single'       => true,
				'show_in_rest' => array(
					'schema' => array(
						'type' => 'boolean',
					),
				),
			)
		);

		register_term_meta(
			'trip-packages-categories',
			'age_group',
			array(
				'type'         => 'string',
				'description'  => __( 'The age group/range for the category.', 'wp-travel-engine' ),
				'single'       => true,
				'show_in_rest' => array(
					'schema' => array(
						'type' => 'string',
					),
				),
			)
		);

		foreach ( array(
			'min_pax'    => array(
				'type'     => 'number',
				'callback' => 'absint',
				'default'  => 1,
				'single'   => true,
			),
			'max_pax'    => array(
				'type'     => 'number',
				'callback' => 'absint',
				'default'  => -1,
				'single'   => true,
			),
			'order'      => array(
				'type'     => 'number',
				'callback' => 'absint',
				'default'  => 1,
				'single'   => true,
			),
			'categories' => array(
				'type' => array(),
			),
		) as $meta_key => $args ) {
			$_args = array(
				'object_subtype'    => $post_type,
				'type'              => wte_array_get( $args, 'type', 'string' ),
				'sanitize_callback' => wte_array_get( $args, 'callback', 'wte_default_sanitize_callback' ),
				'single'            => wte_array_get( $args, 'type', true ),
				'show_in_rest'      => wte_array_get( $args, 'show_in_rest', true ),
			);
			if ( isset( $args['default'] ) ) {
				$_args['default'] = $args['default'];
			}
			register_meta(
				'post',
				$meta_key,
				$_args
			);
		}
	}

}

TourPackages::instance();
