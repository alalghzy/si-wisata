<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/admin
 */
class Wp_Travel_Engine_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		add_image_size( 'trip-thumb-size', 374, 226, true ); // 260 pixels wide by 210 pixels tall, hard crop mode
		add_image_size( 'destination-thumb-size', 600, 810, true ); // 260 pixels wide by 210 pixels tall, hard crop mode
		add_image_size( 'destination-thumb-trip-size', 410, 250, true );
		add_image_size( 'activities-thumb-size', 600, 810, true ); // 260 pixels wide by 210 pixels tall, hard crop mode
		add_image_size( 'trip-single-size', 990, 490, true ); // 800 pixels wide by 284 pixels tall, hard crop mode
		// remove_filter( 'the_content', 'wpautop' );
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->init_hooks();
	}

	/**
	 * Init Hooks.
	 *
	 * @since 5.1.1
	 */
	private function init_hooks() {
		add_action( 'wp_travel_engine_trip_code_display', array( $this, 'wpte_display_trip_code_section' ) );
		add_action( 'admin_init', array( $this, 'prepare_filter_params' ) );

		add_action(
			'admin_init',
			array( $this, 'handle_migrate_request' )
		);
		add_action(
			'wte_before_remaining_payment_form_close',
			function() {
				echo '<input type="radio" checked name="wp_travel_engine_payment_mode" value="remaining_payment" style="display:none"/>';
			}
		);

		add_filter(
			'bulk_actions-edit-trip',
			function( $actions ) {
				$actions['wte-migrate'] = esc_html__( 'Migrate Trip', 'wp-travel-engine' );
				return $actions;
			}
		);

		add_action(
			'wte_before_remaining_payment_form_close',
			function() {
				echo '<input type="radio" checked name="wp_travel_engine_payment_mode" value="remaining_payment" style="display:none"/>';
			}
		);

		add_action( 'admin_bar_menu', array( $this, 'wpte_admin_bar' ), 500 );

		add_action(
			'wte_before_remaining_payment_form_close',
			function() {
				echo '<input type="radio" checked name="wp_travel_engine_payment_mode" value="remaining_payment" style="display:none"/>';
			}
		);

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 15 );

		/**
		 *
		 * @since 5.5.7
		 */
		add_filter( 'manage_edit-difficulty_columns', array( $this, 'add_difficulty_columns' ), 10, 2 );
		add_action( 'manage_difficulty_custom_column', array( $this, 'difficulty_custom_columns_value' ), 10, 3 );

	}

	/**
	 *
	 * @since 5.5
	 */
	public function admin_menu() {
		global $submenu;
		unset( $submenu['edit.php?post_type=booking'][10] ); // Removes 'Add New'.
		$menus = array(
			'extensions'                         => array(
				'parent_slug' => 'edit.php?post_type=booking',
				'page_title'  => __( 'Extensions for WP Travel Engine.', 'wp-travel-engine' ),
				'menu_title'  => __( 'Extensions', 'wp-travel-engine' ),
				'capability'  => 'manage_options',
				'callback'    => array( $this, 'wp_travel_engine_extensions_callback_function' ),
				'position'    => 10,
			),
			'themes'                             => array(
				'parent_slug' => 'edit.php?post_type=booking',
				'page_title'  => __( 'Themes for WP Travel Engine.', 'wp-travel-engine' ),
				'menu_title'  => __( 'Themes', 'wp-travel-engine' ),
				'capability'  => 'manage_options',
				'callback'    => array( $this, 'wp_travel_engine_themes_callback_function' ),
				'position'    => 11,
			),
			WP_TRAVEL_ENGINE_PLUGIN_LICENSE_PAGE => array(
				'parent_slug' => 'edit.php?post_type=booking',
				'page_title'  => __( 'Plugin License', 'wp-travel-engine' ),
				'menu_title'  => __( 'Plugin License', 'wp-travel-engine' ),
				'capability'  => 'manage_options',
				'callback'    => function() {
					include_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . '/includes/backend/plugin-license/license.php';
				},
				'position'    => 12,
			),
			'class-wp-travel-engine-admin.php'   => array(
				'parent_slug' => 'edit.php?post_type=booking',
				'page_title'  => __( 'WP Travel Engine Admin Settings', 'wp-travel-engine' ),
				'menu_title'  => __( 'Settings <span class="wte_note_550 hidden"></span>', 'wp-travel-engine' ),
				'capability'  => 'manage_options',
				'callback'    => array( $this, 'wp_travel_engine_callback_function' ),
				'position'    => 13,
			),
			'whats-new'                          => array(
				'parent_slug' => 'edit.php?post_type=booking',
				'page_title'  => __( 'What\'s New?', 'wp-travel-engine' ),
				'menu_title'  => __( 'What\'s New?<span style="margin-left: 5px;" class="wte_new_550 hidden">NEW</span>', 'wp-travel-engine' ),
				'capability'  => 'manage_options',
				'callback'    => array( '\WTE_Getting_Started', 'setup_wizard_content' ),
				'position'    => 19,
				'condition'   => file_exists( WPTRAVELENGINE_UPDATES_DATA_PATH . '/data.json' ),
			),
		);

		foreach ( $menus as $slug => $menu ) {
			if ( isset( $menu['condition'] ) && ! $menu['condition'] ) {
				continue;
			}
			add_submenu_page(
				$menu['parent_slug'],
				$menu['page_title'],
				$menu['menu_title'],
				$menu['capability'],
				$slug,
				$menu['callback'],
				$menu['position']
			);
		}

	}

	/**
	 *
	 * Handle Migrate Request.
	 *
	 * @since 5.3.1
	 */
	public function handle_migrate_request() {

		if ( ! wte_nonce_verify( '_wpnonce', 'bulk-posts' ) ) {
			return;
		};
		// phpcs:disable
		if ( wte_array_get( $_REQUEST, 'action', '' ) === 'wte-migrate' && wte_array_get( $_REQUEST, 'post_type', '' ) === WP_TRAVEL_ENGINE_POST_TYPE ) {
			if ( ! empty( $_REQUEST['post'] ) && is_array( $_REQUEST['post'] ) ) {
				if ( ! function_exists( 'WTE\Upgrade500\migrate_trip_dates_and_pricings' ) ) {
					include_once sprintf( '%s/upgrade/500.php', \WP_TRAVEL_ENGINE_BASE_PATH );
				}
				$trips = wte_clean( wp_unslash( $_REQUEST['post'] ) );
				foreach ( $trips as $trip_id ) {
					\WTE\Upgrade500\migrate_trip_dates_and_pricings( get_post( $trip_id ), true );
				}
			}
		}
		// phpcs:enable
	}

	/**
	 * wpte_admin_bar
	 *
	 * @param  mixed $admin_bar
	 * @return void
	 */
	function wpte_admin_bar( \WP_Admin_Bar $admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$wpte_menu_svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_60_548)"><path d="M22.8963 12.1856C23.1956 11.7415 22.7501 11.3673 22.7501 11.3673C22.7501 11.3673 22.2301 11.1051 21.9322 11.5491C21.633 11.9932 20.8789 13.1159 20.8789 13.1159L17.8029 13.1871L17.287 13.954L19.8988 14.572L18.7272 15.9741C19.0916 16.1151 19.4014 16.3747 19.7525 16.5486L20.863 15.2085L22.4442 17.359L22.9602 16.5921L21.8418 13.7524C21.8431 13.7524 22.5984 12.6297 22.8963 12.1856Z" fill="white"></path><path d="M11.9222 11.5544C12.8513 11.5544 13.6045 10.8081 13.6045 9.88745C13.6045 8.96683 12.8513 8.22052 11.9222 8.22052C10.9931 8.22052 10.2399 8.96683 10.2399 9.88745C10.2399 10.8081 10.9931 11.5544 11.9222 11.5544Z" fill="white"></path><path d="M21.2379 13.4954C20.9587 13.3215 20.589 13.4045 20.4134 13.6825C18.7032 16.3733 16.9172 17.8439 15.2482 17.9335C13.1351 18.0495 11.744 16.011 10.5299 14.6498C9.8862 13.9276 9.30105 13.1568 8.79038 12.3371C8.3861 11.6901 7.93927 10.9166 7.93927 10.1339C7.93794 7.95699 9.72528 6.18596 11.9222 6.18596C14.1178 6.18596 15.9052 7.95699 15.9052 10.1339C15.9052 11.4371 14.3226 13.5244 12.9635 15.0477C12.7494 15.2875 12.7733 15.6525 13.0114 15.87C13.0154 15.8726 13.018 15.8766 13.022 15.8792C13.2641 16.1006 13.6444 16.0795 13.8625 15.8357C15.2668 14.2716 17.1034 11.8904 17.1034 10.1326C17.1021 7.30208 14.7788 5 11.9222 5C9.06567 5 6.74106 7.30208 6.74106 10.1339C6.74106 11.7876 8.36749 13.9935 9.73326 15.555L9.72927 15.5511C10.091 15.8897 10.4022 16.2996 10.744 16.6593C11.4076 17.3551 12.0858 18.0969 12.9382 18.5634C12.9396 18.5647 12.9422 18.5647 12.9475 18.5687C13.5181 18.877 14.2375 19.1235 15.0807 19.1235C15.1511 19.1235 15.223 19.1221 15.2961 19.1182C17.4039 19.0141 19.4666 17.3972 21.4255 14.3137C21.6023 14.037 21.5172 13.6707 21.2379 13.4954Z" fill="white"></path><path d="M10.6349 17.7979C10.4607 17.6345 10.2054 17.5937 9.98463 17.6859C9.58567 17.852 9.11889 17.9626 8.59625 17.9337C6.92727 17.844 5.14126 16.3735 3.4377 13.6919L2.11049 11.5137C1.94027 11.233 1.57189 11.1434 1.28996 11.312C1.0067 11.482 0.914938 11.8457 1.08649 12.1264L2.41902 14.3138C4.37791 17.3973 6.44054 19.0142 8.54838 19.1183C8.62152 19.1222 8.69333 19.1236 8.76381 19.1236C9.40082 19.1236 9.96867 18.9826 10.4541 18.7796C10.8544 18.6123 10.9528 18.0957 10.6376 17.7992L10.6349 17.7979Z" fill="white"></path></g></svg>';

		$menu_id = 'wpte-admin-menu';
		$admin_bar->add_menu(
			array(
				'id'     => $menu_id,
				'parent' => null,
				'group'  => null,
				'title'  => "<span class=\"wpte-admin-menu\">{$wpte_menu_svg}</span> WP Travel Engine",
				'href'   => admin_url( 'edit.php?post_type=booking&page=class-wp-travel-engine-admin.php' ),
				'meta'   => array(
					'title' => __( 'WP Travel Engine', 'wp-travel-engine' ),
				),
			)
		);
		$admin_bar->add_menu(
			array(
				'id'     => 'wpte-trips',
				'parent' => $menu_id,
				'group'  => null,
				'title'  => 'All Trips',
				'href'   => admin_url( 'edit.php?post_type=trip' ),
				'meta'   => array(
					'title' => __( 'All Trips', 'wp-travel-engine' ),
				),
			)
		);
		$admin_bar->add_menu(
			array(
				'id'     => 'wpte-settings',
				'parent' => $menu_id,
				'group'  => null,
				'title'  => 'Settings',
				'href'   => admin_url( 'edit.php?post_type=booking&page=class-wp-travel-engine-admin.php' ),
				'meta'   => array(
					'title' => __( 'WP Travel Engine Settings', 'wp-travel-engine' ),
				),
			)
		);
		$admin_bar->add_menu(
			array(
				'id'     => 'wpte-extensions',
				'parent' => $menu_id,
				'group'  => null,
				'title'  => 'Extensions',
				'href'   => admin_url( 'edit.php?post_type=booking&page=extensions' ),
				'meta'   => array(
					'title' => __( 'WP Travel Engine Extensions', 'wp-travel-engine' ),
				),
			)
		);
		$admin_bar->add_menu(
			array(
				'id'     => 'wpte-tutorials',
				'parent' => $menu_id,
				'group'  => null,
				'title'  => 'Tutorials',
				'href'   => 'https://wptravelengine.com/tutorials/?utm_source=admin_menu&utm_medium=admin_menu&utm_id=admin_menu',
				'meta'   => array(
					'title'  => __( 'WP Travel Engine Tutorials', 'wp-travel-engine' ),
					'target' => '__blank',
				),
			)
		);
		$admin_bar->add_menu(
			array(
				'id'     => 'wpte-support',
				'parent' => $menu_id,
				'group'  => null,
				'title'  => 'Support',
				'href'   => 'https://wptravelengine.com/support/?utm_source=admin_menu&utm_medium=admin_menu&utm_id=admin_menu',
				'meta'   => array(
					'title'  => __( 'WP Travel Engine Support', 'wp-travel-engine' ),
					'target' => '__blank',
				),
			)
		);

	}

	/**
	 *
	 * @since 5.1.1
	 */
	public function prepare_filter_params( $force = false ) {
		$version = str_replace( '.', '', WP_TRAVEL_ENGINE_VERSION );
		if ( 'done' === get_option( "wte_search_params_updated_{$version}", false ) && ! $force ) {
			return;
		}

		global $wpdb;
		$where  = $wpdb->prepare( "{$wpdb->posts}.post_type = %s", WP_TRAVEL_ENGINE_POST_TYPE );
		$where .= " AND {$wpdb->posts}.post_status IN ( 'publish','draft' )";

		$trip_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE {$where}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		if ( $trip_ids ) {
			global $wp_query;
			$wp_query->in_the_lopp = true;
			while ( $next_trips = array_splice( $trip_ids, 0, 20 ) ) { // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
				$where = 'WHERE ID IN (' . join( ',', $next_trips ) . ')';
				$trips = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} $where" ); // phpcs:ignore
				foreach ( $trips as $trip ) {
					self::update_search_params_meta( $trip );
				}
			}
		}

		update_option( "wte_search_params_updated_{$version}", 'done', true );
	}

	public static function update_search_params_meta( $trip ) {
		$price = \WPTravelEngine\Packages\get_trip_lowest_price( $trip->ID );
		// Price
		update_post_meta( $trip->ID, 'wp_travel_engine_setting_trip_actual_price', $price );
		update_post_meta( $trip->ID, '_s_price', $price );

		$trip_settings = get_post_meta( $trip->ID, 'wp_travel_engine_setting', true );
		// Duration
		$duration      = isset( $trip_settings['trip_duration'] ) ? (int) $trip_settings['trip_duration'] : 0;
		$duration_unit = isset( $trip_settings['trip_duration_unit'] ) ? $trip_settings['trip_duration_unit'] : 'days';

		if ( 'days' === $duration_unit ) {
			$duration = $duration * 24;
		}

		update_post_meta( $trip->ID, 'wp_travel_engine_setting_trip_duration', $duration );
		update_post_meta( $trip->ID, '_s_duration', $duration );

		update_post_meta( $trip->ID, '_s_min_pax', wte_array_get( $trip_settings, 'trip_minimum_pax', 1 ) );
		update_post_meta( $trip->ID, '_s_max_pax', wte_array_get( $trip_settings, 'trip_maximum_pax', -1 ) );
	}

	/**
	 * Register a Trip post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	function wp_travel_engine_register_trip() {
		$permalink = wp_travel_engine_get_permalink_structure();

		$labels = array(
			'name'               => _x( 'Trips', 'post type general name', 'wp-travel-engine' ),
			'singular_name'      => _x( 'Trip', 'post type singular name', 'wp-travel-engine' ),
			'menu_name'          => _x( 'Trips', 'admin menu', 'wp-travel-engine' ),
			'name_admin_bar'     => _x( 'Trip', 'add new on admin bar', 'wp-travel-engine' ),
			'add_new'            => _x( 'Add New', 'Trip', 'wp-travel-engine' ),
			'add_new_item'       => esc_html__( 'Add New Trip', 'wp-travel-engine' ),
			'new_item'           => esc_html__( 'New Trip', 'wp-travel-engine' ),
			'edit_item'          => esc_html__( 'Edit Trip', 'wp-travel-engine' ),
			'view_item'          => esc_html__( 'View Trip', 'wp-travel-engine' ),
			'all_items'          => esc_html__( 'All Trips', 'wp-travel-engine' ),
			'search_items'       => esc_html__( 'Search Trips', 'wp-travel-engine' ),
			'parent_item_colon'  => esc_html__( 'Parent Trips:', 'wp-travel-engine' ),
			'not_found'          => esc_html__( 'No Trips found.', 'wp-travel-engine' ),
			'not_found_in_trash' => esc_html__( 'No Trips found in Trash.', 'wp-travel-engine' ),
		);

		$wte_trip_svg = base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.45 22.48"><title>Asset 2</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1" fill="#fff"><path d="M6.71,9.25c-.09.65-.17,1.27-.27,1.89s-.28,1.54-.4,2.31a.36.36,0,0,0,.07.22c.47.73.93,1.47,1.42,2.18a2.27,2.27,0,0,1,.39,1c.18,1.43.38,2.86.57,4.29a1,1,0,1,1-2,.3C6.3,20.31,6.13,19.14,6,18a3.19,3.19,0,0,0-.59-1.62C5,15.76,4.6,15.11,4.18,14.5a.7.7,0,0,0-.26-.22,1.58,1.58,0,0,1-1-1.69q.5-3.54,1-7.06A1.61,1.61,0,0,1,7.19,6a.82.82,0,0,0,.09.41c.19.39.4.77.62,1.14a.82.82,0,0,0,.35.29c1,.37,2.06.71,3.09,1.07a1,1,0,0,1,.35,1.61.83.83,0,0,1-.85.22c-1.32-.44-2.62-.9-3.93-1.35Z"/><path d="M2.4,3.38A1.36,1.36,0,0,1,3.75,5c-.23,1.6-.46,3.2-.71,4.79a3,3,0,0,1-.26,1,1.3,1.3,0,0,1-1.57.63,1.33,1.33,0,0,1-1-1.5Q.61,7.22,1,4.58A1.38,1.38,0,0,1,2.4,3.38Z"/><path d="M3.05,14.2a2.41,2.41,0,0,1,.75.39,14.73,14.73,0,0,1,.91,1.32c-.07.32-.17.63-.22.95a8.43,8.43,0,0,1-1.11,2.42C2.92,20.15,2.43,21,2,21.87a1,1,0,1,1-1.8-1L2.29,17a1.74,1.74,0,0,0,.14-.38c.19-.78.38-1.55.58-2.33Z"/><path d="M8.34,2a2,2,0,0,1-4,0,2,2,0,0,1,4,0Z"/><path d="M10.6,10.94l.56.07c0,.36,0,.73-.06,1.1,0,.68-.11,1.37-.15,2.05-.14,2-.27,4-.4,6L10.43,22c0,.35-.11.51-.31.5s-.28-.16-.25-.52c.11-1.76.23-3.51.34-5.27.1-1.51.19-3,.28-4.53C10.52,11.76,10.56,11.36,10.6,10.94Z"/><path d="M11.31,8.57c-.54-.14-.54-.14-.52-.64s.06-.9.1-1.34c0-.19.1-.31.3-.3s.27.15.26.33C11.4,7.27,11.36,7.91,11.31,8.57Z"/><path d="M18.16,9.25c-.1.65-.17,1.27-.28,1.89s-.27,1.54-.4,2.31a.37.37,0,0,0,.08.22c.47.73.93,1.47,1.42,2.18a2.27,2.27,0,0,1,.39,1c.18,1.43.38,2.86.57,4.29a1,1,0,1,1-2,.3c-.16-1.17-.33-2.34-.47-3.51a3.18,3.18,0,0,0-.58-1.62c-.44-.59-.82-1.24-1.23-1.85a.7.7,0,0,0-.26-.22,1.58,1.58,0,0,1-1-1.69q.5-3.54,1-7.06A1.59,1.59,0,0,1,17.2,4.2,1.62,1.62,0,0,1,18.64,6a.82.82,0,0,0,.08.41q.3.59.63,1.14a.82.82,0,0,0,.35.29c1,.37,2.06.71,3.08,1.07a1,1,0,0,1,.35,1.61.83.83,0,0,1-.85.22c-1.31-.44-2.62-.9-3.92-1.35Z"/><path d="M13.84,3.38A1.36,1.36,0,0,1,15.2,5c-.23,1.6-.47,3.2-.71,4.79a3,3,0,0,1-.26,1,1.3,1.3,0,0,1-1.57.63,1.33,1.33,0,0,1-1-1.5q.38-2.65.77-5.29A1.37,1.37,0,0,1,13.84,3.38Z"/><path d="M14.49,14.2a2.36,2.36,0,0,1,.76.39c.34.41.61.88.91,1.32-.08.32-.17.63-.22.95a8.7,8.7,0,0,1-1.11,2.42c-.46.87-.95,1.72-1.43,2.59a1,1,0,0,1-1.44.47,1,1,0,0,1-.35-1.46L13.74,17a2.46,2.46,0,0,0,.14-.38c.19-.78.38-1.55.58-2.33A.19.19,0,0,1,14.49,14.2Z"/><path d="M19.79,2a2,2,0,1,1-2-2A2,2,0,0,1,19.79,2Z"/><path d="M22.05,10.94l.56.07-.06,1.1c-.05.68-.11,1.37-.16,2.05l-.39,6c-.05.61-.08,1.23-.12,1.85,0,.35-.11.51-.31.5s-.28-.16-.26-.52c.12-1.76.24-3.51.35-5.27.1-1.51.19-3,.28-4.53C22,11.76,22,11.36,22.05,10.94Z"/><path d="M22.76,8.57c-.54-.14-.55-.14-.52-.64s.06-.9.09-1.34c0-.19.11-.31.3-.3a.26.26,0,0,1,.26.33C22.85,7.27,22.8,7.91,22.76,8.57Z"/></g></g></svg>' );

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Description.', 'wp-travel-engine' ),
			'public'             => true,
			'menu_icon'          => 'data:image/svg+xml;base64,' . $wte_trip_svg,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => $permalink['wp_travel_engine_trip_base'],
				'with_front' => true,
			),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 31,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
		);

		register_post_type( 'trip', $args );
		flush_rewrite_rules();
	}

	/**
	 * Register a Enquiry post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	function wp_travel_engine_register_enquiry() {
		$labels = array(
			'name'               => _x( 'Enquiries', 'post type general name', 'wp-travel-engine' ),
			'singular_name'      => _x( 'Enquiry', 'post type singular name', 'wp-travel-engine' ),
			'menu_name'          => _x( 'Enquiries', 'admin menu', 'wp-travel-engine' ),
			'name_admin_bar'     => _x( 'Enquiry', 'add new on admin bar', 'wp-travel-engine' ),
			'add_new'            => _x( 'Add New', 'Enquiry', 'wp-travel-engine' ),
			'add_new_item'       => esc_html__( 'Add New Enquiry', 'wp-travel-engine' ),
			'new_item'           => esc_html__( 'New Enquiry', 'wp-travel-engine' ),
			'edit_item'          => esc_html__( 'Edit Enquiry', 'wp-travel-engine' ),
			'view_item'          => esc_html__( 'View Enquiry', 'wp-travel-engine' ),
			'all_items'          => esc_html__( 'All Enquiries', 'wp-travel-engine' ),
			'search_items'       => esc_html__( 'Search Enquiries', 'wp-travel-engine' ),
			'parent_item_colon'  => esc_html__( 'Parent Enquiries:', 'wp-travel-engine' ),
			'not_found'          => esc_html__( 'No Enquiries found.', 'wp-travel-engine' ),
			'not_found_in_trash' => esc_html__( 'No Enquiries found in Trash.', 'wp-travel-engine' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Description.', 'wp-travel-engine' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=booking',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'enquiry' ),
			'capability_type'    => 'post',
			'capabilities'       => array(
				'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
			),
			'map_meta_cap'       => true, // Set to `false`, if users are not allowed to edit/delete existing posts
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 40,
			'supports'           => array( 'title' ),
		);

		register_post_type( 'enquiry', $args );
		flush_rewrite_rules();
	}

	/**
	 * Register a Booking History post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	function wp_travel_engine_register_booking() {
		$labels = array(
			'name'               => _x( 'Bookings', 'post type general name', 'wp-travel-engine' ),
			'singular_name'      => _x( 'Booking', 'post type singular name', 'wp-travel-engine' ),
			'menu_name'          => _x( 'WP Travel Engine', 'admin menu', 'wp-travel-engine' ),
			'name_admin_bar'     => _x( 'Booking', 'add new on admin bar', 'wp-travel-engine' ),
			'add_new'            => _x( 'Add New', 'Booking', 'wp-travel-engine' ),
			'add_new_item'       => esc_html__( 'Add New Booking', 'wp-travel-engine' ),
			'new_item'           => esc_html__( 'New Booking', 'wp-travel-engine' ),
			'edit_item'          => esc_html__( 'Edit Booking', 'wp-travel-engine' ),
			'view_item'          => esc_html__( 'View Booking', 'wp-travel-engine' ),
			'all_items'          => esc_html__( 'All Bookings', 'wp-travel-engine' ),
			'search_items'       => esc_html__( 'Search Bookings', 'wp-travel-engine' ),
			'parent_item_colon'  => esc_html__( 'Parent Bookings:', 'wp-travel-engine' ),
			'not_found'          => esc_html__( 'No Bookings found.', 'wp-travel-engine' ),
			'not_found_in_trash' => esc_html__( 'No Bookings found in Trash.', 'wp-travel-engine' ),
		);

		$wte_svg = base64_encode( '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_60_548)"><path d="M22.8963 12.1856C23.1956 11.7415 22.7501 11.3673 22.7501 11.3673C22.7501 11.3673 22.2301 11.1051 21.9322 11.5491C21.633 11.9932 20.8789 13.1159 20.8789 13.1159L17.8029 13.1871L17.287 13.954L19.8988 14.572L18.7272 15.9741C19.0916 16.1151 19.4014 16.3747 19.7525 16.5486L20.863 15.2085L22.4442 17.359L22.9602 16.5921L21.8418 13.7524C21.8431 13.7524 22.5984 12.6297 22.8963 12.1856Z" fill="white"></path><path d="M11.9222 11.5544C12.8513 11.5544 13.6045 10.8081 13.6045 9.88745C13.6045 8.96683 12.8513 8.22052 11.9222 8.22052C10.9931 8.22052 10.2399 8.96683 10.2399 9.88745C10.2399 10.8081 10.9931 11.5544 11.9222 11.5544Z" fill="white"></path><path d="M21.2379 13.4954C20.9587 13.3215 20.589 13.4045 20.4134 13.6825C18.7032 16.3733 16.9172 17.8439 15.2482 17.9335C13.1351 18.0495 11.744 16.011 10.5299 14.6498C9.8862 13.9276 9.30105 13.1568 8.79038 12.3371C8.3861 11.6901 7.93927 10.9166 7.93927 10.1339C7.93794 7.95699 9.72528 6.18596 11.9222 6.18596C14.1178 6.18596 15.9052 7.95699 15.9052 10.1339C15.9052 11.4371 14.3226 13.5244 12.9635 15.0477C12.7494 15.2875 12.7733 15.6525 13.0114 15.87C13.0154 15.8726 13.018 15.8766 13.022 15.8792C13.2641 16.1006 13.6444 16.0795 13.8625 15.8357C15.2668 14.2716 17.1034 11.8904 17.1034 10.1326C17.1021 7.30208 14.7788 5 11.9222 5C9.06567 5 6.74106 7.30208 6.74106 10.1339C6.74106 11.7876 8.36749 13.9935 9.73326 15.555L9.72927 15.5511C10.091 15.8897 10.4022 16.2996 10.744 16.6593C11.4076 17.3551 12.0858 18.0969 12.9382 18.5634C12.9396 18.5647 12.9422 18.5647 12.9475 18.5687C13.5181 18.877 14.2375 19.1235 15.0807 19.1235C15.1511 19.1235 15.223 19.1221 15.2961 19.1182C17.4039 19.0141 19.4666 17.3972 21.4255 14.3137C21.6023 14.037 21.5172 13.6707 21.2379 13.4954Z" fill="white"></path><path d="M10.6349 17.7979C10.4607 17.6345 10.2054 17.5937 9.98463 17.6859C9.58567 17.852 9.11889 17.9626 8.59625 17.9337C6.92727 17.844 5.14126 16.3735 3.4377 13.6919L2.11049 11.5137C1.94027 11.233 1.57189 11.1434 1.28996 11.312C1.0067 11.482 0.914938 11.8457 1.08649 12.1264L2.41902 14.3138C4.37791 17.3973 6.44054 19.0142 8.54838 19.1183C8.62152 19.1222 8.69333 19.1236 8.76381 19.1236C9.40082 19.1236 9.96867 18.9826 10.4541 18.7796C10.8544 18.6123 10.9528 18.0957 10.6376 17.7992L10.6349 17.7979Z" fill="white"></path></g></svg>' );

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Description.', 'wp-travel-engine' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			// 'show_in_menu'       => 'edit.php?post_type=trip',
			'menu_icon'          => 'data:image/svg+xml;base64,' . $wte_svg,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'booking' ),
			'capability_type'    => 'post',
			// 'capabilities' => array(
			// 'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
			// ),
			'map_meta_cap'       => true, // Set to `false`, if users are not allowed to edit/delete existing posts
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 31,
			'supports'           => array( 'title' ),
		);

		register_post_type( 'booking', $args );
		flush_rewrite_rules();
	}


	/**
	 * Register a Customer History post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	function wp_travel_engine_register_customer() {
		 $labels = array(
			 'name'               => _x( 'Customers', 'post type general name', 'wp-travel-engine' ),
			 'singular_name'      => _x( 'Customer', 'post type singular name', 'wp-travel-engine' ),
			 'menu_name'          => _x( 'Customers', 'admin menu', 'wp-travel-engine' ),
			 'name_admin_bar'     => _x( 'Customer', 'add new on admin bar', 'wp-travel-engine' ),
			 'add_new'            => _x( 'Add New', 'Customer', 'wp-travel-engine' ),
			 'add_new_item'       => esc_html__( 'Add New Customer', 'wp-travel-engine' ),
			 'new_item'           => esc_html__( 'New Customer', 'wp-travel-engine' ),
			 'edit_item'          => esc_html__( 'Edit Customer', 'wp-travel-engine' ),
			 'view_item'          => esc_html__( 'View Customer', 'wp-travel-engine' ),
			 'all_items'          => esc_html__( 'All Customers', 'wp-travel-engine' ),
			 'search_items'       => esc_html__( 'Search Customers', 'wp-travel-engine' ),
			 'parent_item_colon'  => esc_html__( 'Parent Customers:', 'wp-travel-engine' ),
			 'not_found'          => esc_html__( 'No Customers found.', 'wp-travel-engine' ),
			 'not_found_in_trash' => esc_html__( 'No Customers found in Trash.', 'wp-travel-engine' ),
		 );

		 $args = array(
			 'labels'             => $labels,
			 'description'        => esc_html__( 'Description.', 'wp-travel-engine' ),
			 'public'             => false,
			 'menu_icon'          => 'dashicons-location-alt',
			 'publicly_queryable' => false,
			 'show_ui'            => true,
			 'show_in_menu'       => 'edit.php?post_type=booking',
			 'query_var'          => true,
			 'rewrite'            => array( 'slug' => 'customer' ),
			 'capability_type'    => 'post',
			 'capabilities'       => array(
				 'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
			 ),
			 'map_meta_cap'       => true, // Set to `false`, if users are not allowed to edit/delete existing posts
			 'has_archive'        => true,
			 'hierarchical'       => false,
			 'menu_position'      => 50,
			 'supports'           => array( 'title' ),
		 );

		 register_post_type( 'customer', $args );
		 flush_rewrite_rules();
	}

	/**
	 * Remove column author and date and add trip id, trip name, travelers and cost column.
	 *
	 * @since 1.0.0
	 */
	function wp_travel_engine_booking_cpt_columns( $columns ) {
		unset(
			$columns['author'],
			$columns['date']
		);
		$new_columns = array(
			'booking_date'   => esc_html__( 'Date', 'wp-travel-engine' ),
			'tname'          => esc_html__( 'Trip Name', 'wp-travel-engine' ),
			'travelers'      => esc_html__( 'Travellers', 'wp-travel-engine' ),
			'booking_status' => esc_html__( 'Booking Status', 'wp-travel-engine' ),
			'paid'           => esc_html__( 'Total Paid', 'wp-travel-engine' ),
			'remaining'      => esc_html__( 'Remaining Payment', 'wp-travel-engine' ),
			'cost'           => esc_html__( 'Total Cost', 'wp-travel-engine' ),
		);
		return array_merge( $columns, $new_columns );
	}

	/**
	 * Add Enquiry column in the enquiry list.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_enquiry_cpt_columns( $columns ) {
		 $new_columns = array(
			 'enquiry_date' => esc_html__( 'Date', 'wp-travel-engine' ),
			 'email'        => esc_html__( 'Email', 'wp-travel-engine' ),
			 'preview'      => esc_html__( 'Preview', 'wp-travel-engine' ),
		 );
		 unset( $columns['date'] );
		 return array_merge( $columns, $new_columns );
	}

	/**
	 * Remove enquiry edit links.
	 *
	 * @return void
	 */
	function enquiry_remove_row_actions( $actions ) {
		if ( get_post_type() === 'enquiry' ) {
			unset( $actions['edit'] );
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}

	/**
	 * Show value to the corresponsing columns for booking post type.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_enquiry_custom_columns( $column, $post_id ) {
		$wp_travel_engine_setting = get_post_meta( $post_id, 'wp_travel_engine_setting', true );
		$screen                   = get_current_screen();
		if ( $screen && 'enquiry' === $screen->post_type ) {
			switch ( $column ) {
				case 'email':
					echo isset( $wp_travel_engine_setting['enquiry']['email'] ) ? esc_attr( $wp_travel_engine_setting['enquiry']['email'] ) : '-';
					break;

				case 'preview':
					echo '<span data-enquiryid="' . (int) $post_id . '" class="wte-preview-enquiry dashicons dashicons-welcome-view-site" data-nonce="' . wp_create_nonce( 'wte_get_enquiry_preview' ) . '"></span>';
					break;
				case 'enquiry_date':
					$enquiry_date = wte_get_human_readable_diff_post_published_date( $post_id );
					// Show the date.
					echo wp_kses( $enquiry_date, array( 'time' => array( 'datetime' => array() ) ) );
					break;
			}
		}
	}

	/**
	 * Show value to the corresponsing columns for booking post type.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_booking_custom_columns( $column, $post_id ) {
		$terms = get_post_meta( $post_id, 'wp_travel_engine_booking_setting', true );

		$booking = get_post( $post_id );

		$order_trip = null;
		if ( 'booking' === $booking->post_type && isset( $booking->order_trips ) ) {
			$order_trips = $booking->order_trips;
			$order_trip  = (object) array_shift( $order_trips );
		}

		$wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', true );
		$screen                                  = get_current_screen();

		$column_actions = array(
			'cost'           => function( $booking ) use ( $order_trip ) {
				if ( empty( $booking->payments ) ) { // legacy.
					$terms = get_post_meta( $booking->ID, 'wp_travel_engine_booking_setting', true );
					$code = 'USD';
					if ( isset( $wp_travel_engine_setting_option_setting['currency_code'] ) && '' !== $wp_travel_engine_setting_option_setting['currency_code'] ) {
						$code = $wp_travel_engine_setting_option_setting['currency_code'];
					}
					$obj = \wte_functions();
					$currency = $obj->wp_travel_engine_currencies_symbol( $code );
					echo esc_attr( $currency ) . ' ';

					if ( isset( $terms['place_order']['cost'] ) ) {
						echo floatval( $terms['place_order']['cost'] ) + floatval( $terms['place_order']['due'] );
					}
				} else { // booking_version 2.0.0
					wte_the_formated_price( $booking->cart_info['total'], $booking->cart_info['currency'], '', true );
				}
			},
			'remaining'      => function( $booking ) use ( $order_trip ) {
				if ( ! empty( $booking->payments ) ) {
					wte_the_formated_price( $booking->due_amount, $booking->cart_info['currency'], '', true );
					return;
				}
				$terms = get_post_meta( $booking->ID, 'wp_travel_engine_booking_setting', true );

				if ( isset( $terms['place_order']['due'] ) && '' !== $terms['place_order']['due'] ) {
					$code = 'USD';
					if ( isset( $wp_travel_engine_setting_option_setting['currency_code'] ) && '' !== $wp_travel_engine_setting_option_setting['currency_code'] ) {
						$code = $wp_travel_engine_setting_option_setting['currency_code'];
					}
					$obj = \wte_functions();
					$currency = $obj->wp_travel_engine_currencies_symbol( $code );
					echo esc_attr( $currency ) . ' ';
					echo esc_attr( $terms['place_order']['due'] );
					return;
				}
				echo '-';
			},
			'paid'           => function( $booking ) use ( $order_trip ) {
				if ( ! empty( $booking->payments ) ) {
					wte_the_formated_price( $booking->paid_amount, $booking->cart_info['currency'], '', true );
					return;
				}
				$terms = get_post_meta( $booking->ID, 'wp_travel_engine_booking_setting', true );

				if ( isset( $terms['place_order']['due'] ) && '' !== $terms['place_order']['due'] ) {
					$code = 'USD';
					if ( isset( $wp_travel_engine_setting_option_setting['currency_code'] ) && '' !== $wp_travel_engine_setting_option_setting['currency_code'] ) {
						$code = $wp_travel_engine_setting_option_setting['currency_code'];
					}
					$obj = \wte_functions();
					$currency = $obj->wp_travel_engine_currencies_symbol( $code );
					echo esc_attr( $currency ) . ' ';
					echo floatval( $terms['place_order']['cost'] ) + floatval( $terms['place_order']['due'] ) - floatval( $terms['place_order']['due'] );
				} else {
					$code = 'USD';
					if ( isset( $wp_travel_engine_setting_option_setting['currency_code'] ) && '' !== $wp_travel_engine_setting_option_setting['currency_code'] ) {
						$code = $wp_travel_engine_setting_option_setting['currency_code'];
					}
					$obj = \wte_functions();
					$currency = $obj->wp_travel_engine_currencies_symbol( $code );
					echo esc_attr( $currency ) . ' ';
					$place_order_cost = isset( $terms['place_order']['cost'] ) ? $terms['place_order']['cost'] : '';
					echo esc_html( $place_order_cost );
				}
			},
			'booking_status' => function( $booking ) {
				$status = wp_travel_engine_get_booking_status();
				$label_key = get_post_meta( $booking->ID, 'wp_travel_engine_booking_status', true );
				$label_key = ! empty( $label_key ) ? $label_key : 'booked';
				?>
					<span style="margin:10px;padding:10px;font-weight:700;color:#ffffff;background-color:<?php echo esc_attr( $status[ $label_key ]['color'] ); ?>" ><?php echo esc_html( $status[ $label_key ]['text'] ); ?></span>
				<?php
			},
			'travelers'      => function( $booking ) use ( $order_trip ) {
				$terms = get_post_meta( $booking->ID, 'wp_travel_engine_booking_setting', true );

				if ( isset( $terms['place_order']['traveler'] ) ) {
					echo esc_attr( $terms['place_order']['traveler'] );
				} else {
					$total_pax = ! is_null( $order_trip ) && isset( $order_trip->pax ) && is_array( $order_trip->pax ) ? array_sum( $order_trip->pax ) : 0;
					echo (float) $total_pax;
				}
			},
			'tname'          => function( $booking ) use ( $order_trip ) {
				if ( ! is_null( $order_trip ) && isset( $order_trip->ID ) ) {
					$trip_title = get_the_title( $order_trip->ID );
					echo '<a href="' . esc_url( get_edit_post_link( $order_trip->ID, 'display' ) ) . '">' . esc_html( $trip_title ) . '</a>';
					echo count( $booking->order_trips ) > 1 ? esc_html( ' +' . ( count( $booking->order_trips ) - 1 ) ) : '';
					return;
				}
				$terms = get_post_meta( $booking->ID, 'wp_travel_engine_booking_setting', true );

				if ( isset( $terms['place_order']['tid'] ) ) {
					echo '<a href="' . esc_html( get_edit_post_link( $terms['place_order']['tid'], 'display' ) ) . '">' . esc_html( get_the_title( $terms['place_order']['tid'] ) ) . '</a>';
				}
			},
			'booking_date'   => function( $booking ) {
				echo wp_kses( wte_get_human_readable_diff_post_published_date( $booking->ID ), array( 'time' => array( 'datetime' => array() ) ) );
			},
		);

		if ( in_array( get_current_screen()->post_type, array( 'booking', 'customer' ) ) ) {
			if ( isset( $column_actions[ $column ] ) ) {
				call_user_func( $column_actions[ $column ], $booking );
			}
		}

	}

	/**
	 * Add column Thumbnail.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_trip_types_columns( $columns ) {
		$columns['thumb_id'] = esc_html__( 'Thumbnail', 'wp-travel-engine' );
		$columns['tax_id']   = esc_html__( 'Term ID', 'wp-travel-engine' );
		$columns['featured'] = esc_html__( 'Featured', 'wp-travel-engine' );
		return $columns;
	}

	/**
	 * Show thumbnail.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_trip_types_custom_columns( $content, $column_name, $term_id ) {
		switch ( $column_name ) {
			case 'thumb_id':
				$image_id = get_term_meta( $term_id, 'category-image-id', true );
				if ( $image_id ) {
					echo wp_get_attachment_image( $image_id, array( 120, 160 ) );
				}
				break;

			case 'tax_id':
				echo sprintf( '<p style="text-align:center;"><code>%d</code></p>', (int) $term_id );
				break;

			case 'featured':
				$featured = get_term_meta( $term_id, 'wte_trip_tax_featured', true );
				$featured = ( isset( $featured ) && '' != $featured ) ? $featured : 'no';

				$icon_class = ' dashicons-star-empty ';
				if ( ! empty( $featured ) && 'yes' === $featured ) {
					$icon_class = ' dashicons-star-filled ';
				}
				$nonce = wp_create_nonce( 'wp_travel_engine_featured_term' );
				printf( '<a href="#" class="wp-travel-engine-featured-term dashicons %s" data-term-id="%d"  data-nonce="%s"></a>', esc_attr( $icon_class ), (int) $term_id, esc_attr( $nonce ) );
				break;
		}
	}

	/**
	 * Add column Label.
	 *
	 * @since 5.5.7
	 */
	public function add_difficulty_columns( $columns ) {
		$columns['difficulty_level'] = esc_html__( 'Difficulty Level', 'wp-travel-engine' );
		return $columns;
	}
	/**
	 * Show label.
	 *
	 * @since 5.5.7
	 */
	public function difficulty_custom_columns_value( $content, $column_name, $term_id ) {
		switch ( $column_name ) {
			case 'difficulty_level':
				$taxonomy = 'difficulty';
				$terms    = get_terms(
					$taxonomy,
					array(
						'hide_empty' => false,
						'fields'     => 'ids',
					)
				);

				$levels_count = count( $terms );

				if ( $levels_count > 0 ) {
					$range            = range( 1, $levels_count );
					$difficulty_level = get_option( 'difficulty_level_by_terms', array() );
					echo "<select data-difficulty-term-id=\"{$term_id}\">";
					echo wp_kses(
						"<option value=''>Select Level</option>",
						array(
							'option' => array(
								'value'    => array(),
								'selected' => array(),
							),
						)
					);
					foreach ( $range as $level ) {
						$level    = (string) $level;
						$selected = '';

						if ( isset( $difficulty_level[ $level ]['term_id'] ) && $term_id == $difficulty_level[ $level ]['term_id'] ) {
							$selected = 'selected';
						}

						$option = sprintf( __( 'Level %s', 'wp-travel-engine' ), $level );
						printf(
							'<option value="%1$s" %2$s>%3$s</option>',
							esc_attr( $level ),
							esc_attr( $selected ),
							esc_html( $option )
						);
					}
					echo '</select>';
				}
				break;
		}
	}

	/**
	 * Remove column author and date and add customer id, country, bookings, total spent and created column.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_customer_cpt_columns( $columns ) {
		unset( $columns['date'] );
		$new_columns = array(
			'cid'      => esc_html__( 'Customer ID', 'wp-travel-engine' ),
			'country'  => esc_html__( 'Country', 'wp-travel-engine' ),
			'bookings' => esc_html__( 'Bookings', 'wp-travel-engine' ),
			'spent'    => esc_html__( 'Total Spent', 'wp-travel-engine' ),
			'created'  => esc_html__( 'Created', 'wp-travel-engine' ),
		);
		return array_merge( $columns, $new_columns );
	}

	/**
	 * Show value to the corresponsing columns for customer post type.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_customer_custom_columns( $column, $post_id ) {
		$screen = get_current_screen();
		if ( $screen && in_array( $screen->post_type, array( 'booking', 'customer' ), true ) ) {
			$terms = get_post_meta( $post_id, 'wp_travel_engine_booking_setting', true );
			$var   = false;
			if ( isset( $terms['place_order']['booking']['email'] ) ) {
				$var = get_page_by_title( $terms['place_order']['booking']['email'], OBJECT, 'customer' );
			}
			$size = 0;
			if ( $var && isset( $var->ID ) ) {
				$wp_travel_engine_booked_settings = get_post_meta( $var->ID, 'wp_travel_engine_booked_trip_setting', true );
				$size                             = isset( $wp_travel_engine_booked_settings['traveler'] ) ? sizeof( $wp_travel_engine_booked_settings['traveler'] ) : '';
			}
			$wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', array() );

			switch ( $column ) {
				case 'cid':
					echo esc_attr( $post_id );
					break;

				case 'country':
					if ( isset( $terms['place_order']['booking']['country'] ) ) {
						echo esc_attr( $terms['place_order']['booking']['country'] );
					}
					break;

				case 'bookings':
					echo (int) $size;
					break;

				case 'spent':
					(int) $tot = null;

					if ( ! $var ) {
						echo esc_html__( 'N/A', 'wp-travel-engine' );
						break;
					}
					foreach ( $wp_travel_engine_booked_settings['cost'] as $key => $value ) {
						$value = str_replace( ',', '', $value );
						$tot   = $tot + $value;
					}
					$code = 'USD';
					if ( isset( $wp_travel_engine_setting_option_setting['currency_code'] ) && '' !== $wp_travel_engine_setting_option_setting['currency_code'] ) {
						$code = $wp_travel_engine_setting_option_setting['currency_code'];
					}
					$obj      = \wte_functions();
					$currency = $obj->wp_travel_engine_currencies_symbol( $code );
					echo esc_attr( $currency . $obj->wp_travel_engine_price_format( $tot ) . ' ' . $code );
					break;

				case 'created':
					if ( ! $var ) {
						print( esc_html__( 'N/A', 'wp-travel-engine' ) );
						break;
					}
					echo esc_html( end( $wp_travel_engine_booked_settings['datetime'] ) );
					break;
			}
		}
	}

	/**
	 * Register a taxonomy, 'destination' for the post type "trip".
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	// create taxonomy, destination for the post type "trip"
	function wp_travel_engine_create_destination_taxonomies() {
		 $permalink = wp_travel_engine_get_permalink_structure();
		// Add new taxonomy, make it hierarchical (like destination)
		$labels = array(
			'name'              => _x( 'Destinations', 'taxonomy general name', 'wp-travel-engine' ),
			'singular_name'     => _x( 'Destinations', 'taxonomy singular name', 'wp-travel-engine' ),
			'search_items'      => esc_html__( 'Search Destinations', 'wp-travel-engine' ),
			'all_items'         => esc_html__( 'All Destinations', 'wp-travel-engine' ),
			'parent_item'       => esc_html__( 'Parent Destinations', 'wp-travel-engine' ),
			'parent_item_colon' => esc_html__( 'Parent Destinations', 'wp-travel-engine' ),
			'edit_item'         => esc_html__( 'Edit Destinations', 'wp-travel-engine' ),
			'update_item'       => esc_html__( 'Update Destinations', 'wp-travel-engine' ),
			'add_new_item'      => esc_html__( 'Add New Destinations', 'wp-travel-engine' ),
			'new_item_name'     => esc_html__( 'New Destinations Name', 'wp-travel-engine' ),
			'menu_name'         => esc_html__( 'Destinations', 'wp-travel-engine' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'         => $permalink['wp_travel_engine_destination_base'],
				'hierarchical' => true,
			),
		);

		register_taxonomy( 'destination', array( 'trip' ), $args );
	}

	/**
	 * Register a taxonomy, 'activities' for the post type "trip".
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	// create taxonomy, destination for the post type "trip"
	function wp_travel_engine_create_activities_taxonomies() {
		$permalink = wp_travel_engine_get_permalink_structure();
		// Add new taxonomy, make it hierarchical (like destination)
		$labels = array(
			'name'              => _x( 'Activities', 'taxonomy general name', 'wp-travel-engine' ),
			'singular_name'     => _x( 'Activities', 'taxonomy singular name', 'wp-travel-engine' ),
			'search_items'      => esc_html__( 'Search Activities', 'wp-travel-engine' ),
			'all_items'         => esc_html__( 'All Activities', 'wp-travel-engine' ),
			'parent_item'       => esc_html__( 'Parent Activities', 'wp-travel-engine' ),
			'parent_item_colon' => esc_html__( 'Parent Activities', 'wp-travel-engine' ),
			'edit_item'         => esc_html__( 'Edit Activities', 'wp-travel-engine' ),
			'update_item'       => esc_html__( 'Update Activities', 'wp-travel-engine' ),
			'add_new_item'      => esc_html__( 'Add New Activities', 'wp-travel-engine' ),
			'new_item_name'     => esc_html__( 'New Activities Name', 'wp-travel-engine' ),
			'menu_name'         => esc_html__( 'Activities', 'wp-travel-engine' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'         => $permalink['wp_travel_engine_activity_base'],
				'hierarchical' => true,
			),
		);

		register_taxonomy( 'activities', array( 'trip' ), $args );
	}


	/**
	 * Register a taxonomy, 'trip types' for the post type "trip".
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	// create taxonomy, destination for the post type "trip"
	function wp_travel_engine_create_trip_types_taxonomies() {
		$permalink = wp_travel_engine_get_permalink_structure();
		// Add new taxonomy, make it hierarchical (like destination)
		$labels = array(
			'name'              => _x( 'Trip Type', 'taxonomy general name', 'wp-travel-engine' ),
			'singular_name'     => _x( 'Trip Type', 'taxonomy singular name', 'wp-travel-engine' ),
			'search_items'      => esc_html__( 'Search Trip Type', 'wp-travel-engine' ),
			'all_items'         => esc_html__( 'All Trip Type', 'wp-travel-engine' ),
			'parent_item'       => esc_html__( 'Parent Trip Type', 'wp-travel-engine' ),
			'parent_item_colon' => esc_html__( 'Parent Trip Type', 'wp-travel-engine' ),
			'edit_item'         => esc_html__( 'Edit Trip Type', 'wp-travel-engine' ),
			'update_item'       => esc_html__( 'Update Trip Type', 'wp-travel-engine' ),
			'add_new_item'      => esc_html__( 'Add New Trip Type', 'wp-travel-engine' ),
			'new_item_name'     => esc_html__( 'New Trip Type Name', 'wp-travel-engine' ),
			'menu_name'         => esc_html__( 'Trip Type', 'wp-travel-engine' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'         => $permalink['wp_travel_engine_trip_type_base'],
				'hierarchical' => true,
			),
		);

		register_taxonomy( 'trip_types', array( 'trip' ), $args );
	}

	/**
	 * Register a taxonomy, 'difficulty' for the post type "trip".
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */

	public function create_difficulty_taxonomies() {
		$permalink = wp_travel_engine_get_permalink_structure();

		$labels = array(
			'name'              => _x( 'Difficulty', 'taxonomy general name', 'wp-travel-engine' ),
			'singular_name'     => _x( 'Difficulty', 'taxonomy singular name', 'wp-travel-engine' ),
			'search_items'      => esc_html__( 'Search Difficulty', 'wp-travel-engine' ),
			'all_items'         => esc_html__( 'All Difficulties', 'wp-travel-engine' ),
			'parent_item'       => esc_html__( 'Parent Difficulty', 'wp-travel-engine' ),
			'parent_item_colon' => esc_html__( 'Parent Difficulty', 'wp-travel-engine' ),
			'edit_item'         => esc_html__( 'Edit Difficulty', 'wp-travel-engine' ),
			'update_item'       => esc_html__( 'Update Difficulty', 'wp-travel-engine' ),
			'add_new_item'      => esc_html__( 'Add New Difficulty', 'wp-travel-engine' ),
			'new_item_name'     => esc_html__( 'New Difficulty Name', 'wp-travel-engine' ),
			'menu_name'         => esc_html__( 'Difficulty', 'wp-travel-engine' ),
		);
		$args   = array(
			'hierarchical'       => true,
			'labels'             => $labels,
			'show_ui'            => true,
			'show_in_rest'       => false,
			'show_in_quick_edit' => false,
			'show_admin_column'  => true,
			'rewrite'            => array(
				'slug'         => $permalink['wp_travel_engine_difficulty_base'],
				'hierarchical' => true,
			),
			'meta_box_cb'        => false,
		);
		register_taxonomy( 'difficulty', array( 'trip' ), $args );
	}

	/*
	 * @since 5.5.7
	 * Custom Metabox for Difficulty
	*/
	public function add_custom_wte_metabox() {
		add_meta_box( 'difficulty_custom_taxonomy_id', 'Difficulty', array( $this, 'wte_difficulty_custom_metabox' ), 'trip', 'side', 'core' );
	}

	/*
	 * @since 5.5.7
	 * Callback for Difficulty metabox
	*/
	public function wte_difficulty_custom_metabox( $post ) {
		$taxonomy  = 'difficulty';
		$terms     = get_terms( $taxonomy, array( 'hide_empty' => 0 ) );
		$name      = 'tax_input[' . $taxonomy . ']';
		$postterms = get_the_terms( $post->ID, $taxonomy );
		$current   = ( $postterms ? array_pop( $postterms ) : false );
		$current   = ( $current ? $current->term_id : 0 );
		?>
		<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
			<div id="<?php echo $taxonomy; ?>-all">
				<ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy; ?> categorychecklist form-no-clear">
					<?php
					$difficulty_level = get_option( 'difficulty_level_by_terms', array() );
					$new_arr          = array_column( $difficulty_level, 'level' );
					array_multisort( $new_arr, SORT_ASC, $difficulty_level );
					$sort_terms = array();
					foreach ( $difficulty_level as $level ) {
						$sort_terms[] = $level['term_id'];
					}
					$sorted = array_flip( $sort_terms );
					if ( empty( $difficulty_level ) ) {
						asort( $terms );
					}
					foreach ( $terms as $value ) {
						$id            = $value->term_id;
						$sorted[ $id ] = $value;
					}
					$terms = $sorted;
					foreach ( $terms as $term ) {
						echo sprintf( "<li id='%s'>", esc_attr( $term->term_id ) );
						echo sprintf(
							'<label class="selectit"><input type="radio" id="in-%1$s" name="%2$s" %3$s value="%4$s" />%5$s</label>',
							esc_attr( $term->term_id ),
							esc_attr( $name ),
							checked( $current, $term->term_id, false ),
							esc_attr( $term->term_id ),
							esc_attr( $term->name )
						);
						echo '<br /></li>';
					}

					?>
				</ul>
			</div>
		</div>
		<?php
	}


	/*
	 * Register terms for difficulty taxonomy
	 * @since 5.5.7
	*/
	public function register_terms_for_difficulty_taxonomies() {

		$terms_for_tax = array(
			array( 'Easy', 'easy', 'The trip is easy and suitable for everyone, including children and older people. It does not require any skills in mountain climbing or traversing difficult terrain.' ),
			array( 'Medium', 'medium', "You need to have good physical condition and be in top form. You'll also require the right clothes for weather conditions and food items such as snacks or lunch breaks from your hiking adventure; water is essential too! This type of activity takes time, so ensure you're physically able to get enough rest before starting any trips." ),
			array( 'Hard', 'hard', 'Physical training before the trip should begin no later than 2 months before, as you will have a height of 3,000 meters above sea level and more. Hiking is from 6 km daily with an average pace that can be maintained for hours. It is important to have physical fitness & endurance. You will also need the right gear and clothes according to the weather.' ),
			array( 'Extreme', 'extreme', 'This level is for mountaineering, backcountry and expeditions. It requires more serious physical preparation and previous experience in similar activities like climbing skills. The preliminary stage starts 3-6 months before heading out so that you can get acclimated by running exercises which will help increase endurance. You will need special clothes and gear.' ),
		);

		foreach ( $terms_for_tax as $term ) {
			wp_insert_term(
				$term[0],
				'difficulty',
				array(
					'description' => $term[2],
					'slug'        => $term[1],
				)
			);
		}
	}

	/**
	 * Register a taxonomy, 'tags' for the post type "trip".
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	public function create_tags_taxonomies() {
		$permalink = wp_travel_engine_get_permalink_structure();
		$labels    = array(
			'name'              => _x( 'Trip Tag', 'taxonomy general name', 'wp-travel-engine' ),
			'singular_name'     => _x( 'Tag', 'taxonomy singular name', 'wp-travel-engine' ),
			'search_items'      => esc_html__( 'Search Tag', 'wp-travel-engine' ),
			'all_items'         => esc_html__( 'All Tags', 'wp-travel-engine' ),
			'parent_item'       => esc_html__( 'Parent Tag', 'wp-travel-engine' ),
			'parent_item_colon' => esc_html__( 'Parent Tag', 'wp-travel-engine' ),
			'edit_item'         => esc_html__( 'Edit Tag', 'wp-travel-engine' ),
			'update_item'       => esc_html__( 'Update Tag', 'wp-travel-engine' ),
			'add_new_item'      => esc_html__( 'Add New Tag', 'wp-travel-engine' ),
			'new_item_name'     => esc_html__( 'New Tag Name', 'wp-travel-engine' ),
			'menu_name'         => esc_html__( 'Tag', 'wp-travel-engine' ),
		);
		$args      = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'         => $permalink['wp_travel_engine_tags_base'],
				'hierarchical' => false,
			),
		);
		register_taxonomy( 'trip_tag', array( 'trip' ), $args );
	}

	/*
	** @since 5.5.7
	** Register terms for trips taxonomy
	*/
	public function register_terms_for_tags_taxonomies() {
		$terms_for_tags = array(
			array( 'Guided Tour', 'guided', 'This is a guided tour. You will be accompanied by an expert guide.' ),
			array( 'Group Tour', 'group', 'This is a group tour. Other travellers with a common interest will join this tour and travel together on a schedule.' ),
			array( 'Private Tour', 'private', 'This is a private tour where you can travel at your own pace.' ),
		);

		foreach ( $terms_for_tags as $term ) {
			wp_insert_term(
				$term[0],
				'trip_tag',
				array(
					'description' => $term[2],
					'slug'        => $term[1],
				)
			);
		}
	}

	public function messages_page() {
		$menu_title = __( 'Messages', 'wp-travel-engine' );
		$args       = array(
			'timeout'     => 30,
			'httpversion' => '1.1',
		);
		$url        = 'https://wptravelengine.com/wp-json/wp/v2/wte_messages';
		$date       = get_option( 'wte_messages_latest_post_date' );

		if ( false !== $date ) {
			$url .= "?after={$date}";
		}

		$count    = 0;
		$response = wp_safe_remote_head( $url, $args );
		if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
			$count = wp_remote_retrieve_header( $response, 'x-wp-total' );
			if ( '0' !== $count ) {
				$menu_title .= " <span class=\"update-plugins count-{$count}\"><span class=\"plugin-count\">{$count}</span></span>";
				wte_purge_transients( 'wte_messages_' );
			}
		}

		add_submenu_page( 'edit.php?post_type=booking', 'WP Travel Engine Admin Messages', $menu_title, 'manage_options', 'wte-messages', array( $this, 'display_messages_page' ) );
	}

	public function display_messages_page() {
		$message_list = new Wp_Travel_Engine_Messages_List();
		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/backend/settings/messages.php';
	}

	/**
	 * Registers settings for WP travel Engine.
	 *
	 * @since 1.0.0
	 */
	public function wp_travel_engine_register_settings() {
		// The third parameter is a function that will validate input values.
		register_setting( 'wp_travel_engine_settings', 'wp_travel_engine_settings', '' );
	}

	/**
	 * Update actual prices.
	 *
	 * @return void
	 */
	public function wte_update_actual_prices_for_filter() {
		$updated_actual_price = get_option( 'wpte_updated_actual_price_for_filter', false );

		if ( $updated_actual_price ) {
			return false;
		}

		$wte_trp_args         = array(
			'post_type'      => 'trip',
			'posts_per_page' => -1,
			'order'          => 'ASC',
		);
		$wte_doc_tax_post_qry = new WP_Query( $wte_trp_args );
		$cost                 = 0;
		if ( $wte_doc_tax_post_qry->have_posts() ) :
			while ( $wte_doc_tax_post_qry->have_posts() ) :
				$wte_doc_tax_post_qry->the_post();

				$actual_price = wp_travel_engine_get_actual_trip_price( get_the_ID(), true );
				update_post_meta( get_the_ID(), 'wp_travel_engine_setting_trip_actual_price', $actual_price );
			endwhile;
			wp_reset_postdata();
		endif;
		wp_reset_query();

		// Update filter.
		update_option( 'wpte_updated_actual_price_for_filter', true );

		return;
	}

	/**
	 *
	 * Retrives saved settings from the database if settings are saved. Else, displays fresh forms for settings.
	 *
	 * @since 1.0.0
	 */
	function wp_travel_engine_callback_function() {
		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-settings.php';

		$wte_settings = new Wp_Travel_Engine_Settings();
		$wte_settings->wp_travel_engine_backend_settings();
	}

	/**
	 *
	 * HTML template for tabs
	 *
	 * @since 1.0.0
	 */
	function wp_travel_engine_tabs_template() {
		?>
		<div id="trip-template">
			<li id="trip-tabs{{index}}" data-id="{{index}}" class="trip-row">
				<span class="tabs-handle"><span></span></span>
				<span class="delete-icon delete-tab"><i class="far fa-trash-alt delete-icon" data-id="{{index}}"></i></span>

				<div class="tabs-content">
					<div class="tabs-id">
						<input type="hidden" class="trip-tabs-id" name="wp_travel_engine_settings[trip_tabs][id][{{index}}]" id="wp_travel_engine_settings[trip_tabs][id][{{index}}]" value="{{index}}">
					</div>
					<div class="tabs-field">
						<input type="hidden" class="trip-tabs-id" name="wp_travel_engine_settings[trip_tabs][field][{{index}}]" id="wp_travel_engine_settings[trip_tabs][field][{{index}}]" value="wp_editor">
					</div>
					<div class="tabs-name">
						<input type="text" class="trip-tabs-name" name="wp_travel_engine_settings[trip_tabs][name][{{index}}]" id="wp_travel_engine_settings[trip_tabs][name][{{index}}]" required>
					</div>
					<div class="tabs-icon">
						<input type="text" class="trip-tabs-icon" name="wp_travel_engine_settings[trip_tabs][icon][{{index}}]" id="wp_travel_engine_settings[trip_tabs][icon][{{index}}]" placeholder="search icon...">
					</div>
				</div>
			</li>
		</div>
		<style type="text/css">
			#trip-template {
				display: none;
			}
		</style>
		<?php
	}

	function hide_publishing_actions() {
		$my_post_type = 'customer';
		global $post;
		if ( $post->post_type == $my_post_type ) {
			echo '
                <style type="text/css">
                    #minor-publishing{
                        display:none;
                    }
                </style>
            ';
		}

		$my_post_type = 'booking';
		if ( $post->post_type == $my_post_type ) {
			echo '
                <style type="text/css">
					#visibility,#minor-publishing-actions, #misc-publishing-actions .misc-pub-section.misc-pub-post-status, #misc-publishing-actions .misc-pub-section.misc-pub-curtime {
						display:none;
					}
                </style>
            ';
		}

		$my_post_type = 'enquiry';
		if ( $post->post_type == $my_post_type ) {
			echo '
                <style type="text/css">
                    #postbox-container-1{
                        display:none;
                    }
                </style>
            ';
		}

		$my_post_type = 'customer';
		if ( $post->post_type == $my_post_type ) {
			echo '
                <style type="text/css">
                    #postbox-container-1{
                        display:none;
                    }
                </style>
            ';
		}
	}

	/**
	 * Booking publish metabox
	 *
	 * @return void
	 */
	public function wte_publish_metabox() {
		global $post;
		if ( get_post_type( $post ) === 'booking' ) {
			?>
			<div class="misc-pub-section misc-pub-booking-status">
				<?php
				$status    = wp_travel_engine_get_booking_status();
				$label_key = get_post_meta( $post->ID, 'wp_travel_engine_booking_status', true );
				$label_key = ! empty( $label_key ) ? $label_key : 'booked';

				if ( false && ( 'refunded' === $label_key || 'canceled' === $label_key ) ) {
					?>
						<label for="wp_travel_engine_booking_status"><?php esc_html_e( 'Booking Status', 'wp-travel-engine' ); ?></label>
						<span style="margin:10px;padding:10px;font-weight:700;color:#ffffff;background-color:<?php echo esc_attr( $status[ $label_key ]['color'] ); ?>" ><?php echo esc_html( $status[ $label_key ]['text'] ); ?></span>
						<input type="hidden" name="wp_travel_engine_booking_status" value="<?php echo esc_attr( $label_key ); ?>">
					<?php
				} else {
					?>
					<label for="wp_travel_engine_booking_status"><?php esc_html_e( 'Booking Status', 'wp-travel-engine' ); ?></label>
					<select id="wp_travel_engine_booking_status"  name="wp_travel_engine_booking_status" >
					<?php foreach ( $status as $value => $st ) : ?>
						<option value="<?php echo esc_html( $value ); ?>" <?php selected( $value, $label_key ); ?>>
							<?php echo esc_html( $status[ $value ]['text'] ); ?>
						</option>
					<?php endforeach; ?>
					</select>
					<?php
				}
				?>
			</div>
			<?php
		}
	}

	/**
	 * List out font awesome icon list
	 */
	function wp_travel_engine_get_icon_list() {
		$fontawesome = wptravelengine_get_fa_icons();
		echo '<div class="wp-travel-engine-font-awesome-list-template" style="display:none;">';
		// echo '<input class="wpte-ico-search" type="text" placeholder="Search icon" value="" />';
		echo '<div class="wpte-font-awesome-list"><input class="wpte-ico-search" type="text" placeholder="Search icon" value="" /><ul class="rara-font-group">';
		if ( isset( $fontawesome ) ) :
			foreach ( $fontawesome as $font ) {
				echo "<li>{$font}</li>";
			}
		endif;
		echo '</ul></div></div>';
	}

	/**
	 * Trip facts template.
	 */
	function trip_facts_template() {
		?>
		<div id="trip_facts_outer_template">
			<div id="trip_facts_inner_template">
				<li id="trip_facts_template-{{tripfactsindex}}" data-id="{{tripfactsindex}}" class="trip_facts">
					<span class="tabs-handle">
						<span></span>
					</span>
					<div class="form-builder">
						<div class="fid">
							<label for="wp_travel_engine_settings[trip_facts][fid][{{tripfactsindex}}]"></label>
							<input type="hidden" name="wp_travel_engine_settings[trip_facts][fid][{{tripfactsindex}}]" value="{{tripfactsindex}}">
						</div>
						<div class="field-id">
							<input type="text" name="wp_travel_engine_settings[trip_facts][field_id][{{tripfactsindex}}]" required>
						</div>
						<div class="field-icon">
							<input class="trip-tabs-icon" type="text" name="wp_travel_engine_settings[trip_facts][field_icon][{{tripfactsindex}}]" value="">
						</div>
						<div class="field-type custom-class">
							<div class="select-holder">
								<select id="wp_travel_engine_settings[trip_facts][field_type][{{tripfactsindex}}]" name="wp_travel_engine_settings[trip_facts][field_type][{{tripfactsindex}}]" data-placeholder="<?php esc_attr_e( 'Choose a field type&hellip;', 'wp-travel-engine' ); ?>" class="wc-enhanced-select" required>
									<option value=" "><?php esc_html_e( 'Choose input type&hellip;', 'wp-travel-engine' ); ?></option>
									<?php
									$obj    = \wte_functions();
									$fields = $obj->trip_facts_field_options();
									foreach ( $fields as $key => $val ) {
										echo '<option value="' . ( ! empty( $key ) ? esc_attr( $key ) : 'text' ) . '"' . selected( ' ', $val, false ) . '>' . esc_html( $key ) . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="select-options" style="display: none;">
							<textarea id="wp_travel_engine_settings[trip_facts][select_options][{{tripfactsindex}}]" name="wp_travel_engine_settings[trip_facts][select_options][{{tripfactsindex}}]" placeholder="<?php esc_html_e( 'Enter drop-down values separated by commas', 'wp-travel-engine' ); ?>" rows="2" cols="25" required></textarea>
						</div>
						<div class="input-placeholder">
							<input type="text" name="wp_travel_engine_settings[trip_facts][input_placeholder][{{tripfactsindex}}]" value="">
						</div>
					</div>
					<a href="#" class="del-li"><i class="far fa-trash-alt"></i></a>
				</li>
			</div>
		</div>
		<style>
			#trip_facts_outer_template {
				display: none !important;
			}
		</style>
		<?php
	}

	/**
	 * Trip facts ajax callback.
	 */
	public static function wp_add_trip_info( $post_data ) {

		// $wp_travel_engine_option_settings = wptravelengine_get_trip_facts_options();
		$trip_facts = wptravelengine_get_trip_facts_options();
		// phpcs:ignore
		$id                               = $post_data['val'];
		$key = array_search( $id, $trip_facts['field_id'] );

		$value = $trip_facts['field_type'][ $key ];

		$response = '<div class="wpte-repeater-block wpte-sortable wpte-trip-fact-row"><div class="wpte-field wpte-floated"><label for="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" class="wpte-field-label">' . $id . ' ' . '</label>';

		$response .= '<input type="hidden" name="wp_travel_engine_setting[trip_facts][field_id][' . $key . ']" value="' . $id . '">';
		$response .= '<input type="hidden" name="wp_travel_engine_setting[trip_facts][field_type][' . $key . ']" value="' . $value . '">';

		switch ( $value ) {
			case 'select':
				$options = $trip_facts['select_options'][ $key ];
				$options = explode( ',', $options );

				$response .= '<select id="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" name="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" data-placeholder="' . __( 'Choose a field type&hellip;', 'wp-travel-engine' ) . '">';
				$response .= '<option value=" ">' . __( 'Choose input type&hellip;', 'wp-travel-engine' ) . '</option>';
				foreach ( $options as $key => $val ) {
					$response .= '<option value="' . ( ! empty( $val ) ? esc_attr( $val ) : 'Please select' ) . '">' . esc_html( $val ) . '</option>';
				}
				$response .= '</select>';
				break;
			case 'duration':
				$response .= '<input type="number" min="1" placeholder = "' . esc_html__( 'Number of days', 'wp-travel-engine' ) . '" class="duration" id="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" name="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" value=""/>';

				break;
			case 'number':
				$placeholder = isset( $trip_facts['input_placeholder'][ $key ] ) ? esc_attr( $trip_facts['input_placeholder'][ $key ] ) : '';

				$response .= '<input  type="number" min="1" id="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" name="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" value="">';
				break;

			case 'text':
				$placeholder = isset( $trip_facts['input_placeholder'][ $key ] ) ? esc_attr( $trip_facts['input_placeholder'][ $key ] ) : '';

				$response .= '<input type="text" id="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" name="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" value="" placeholder="' . esc_attr( $placeholder ) . '">';
				break;

			case 'textarea':
				$placeholder = isset( $trip_facts['input_placeholder'][ $key ] ) ? esc_attr( $trip_facts['input_placeholder'][ $key ] ) : '';

				$response .= '<textarea id="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" name="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" placeholder="' . $placeholder . '"></textarea>';

				break;
			default:
				$placeholder = isset( $trip_facts['input_placeholder'][ $key ] ) ? esc_attr( $trip_facts['input_placeholder'][ $key ] ) : '';

				$response .= '<input type="text" id="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" name="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" value="" placeholder="' . esc_attr( $placeholder ) . '">';
				break;
		}
		$response .= '<button class="wpte-delete wpte-remove-trp-fact"></button></div></div>';
		echo $response; // phpcs:ignore
		die;
	}

	/**
	 * Destination template.
	 */
	function wpte_get_destination_template( $template ) {
		$post          = get_post();
		$page_template = get_post_meta( $post->ID, '_wp_page_template', true );
		if ( 'templates/template-destination.php' === $page_template ) {
			$template_path = wte_locate_template( 'template-destination.php' );
			return $template_path;
		}
		if ( 'templates/template-activities.php' === $page_template ) {
			$template_path = wte_locate_template( 'template-activities.php' );
			return $template_path;
		}
		if ( 'templates/template-trip_types.php' === $page_template ) {
			$template_path = wte_locate_template( 'template-trip_types.php' );
			return $template_path;
		}
		if ( 'templates/template-trip-listing.php' === $page_template ) {
			$template_path = wte_locate_template( 'template-trip-listing.php' );
			return $template_path;
		}
		return $template;
	}

	/**
	 * Destination template returned.
	 */
	function wpte_filter_admin_page_templates( $templates ) {
		$templates['templates/template-destination.php']  = esc_html__( 'Destination Template', 'wp-travel-engine' );
		$templates['templates/template-activities.php']   = esc_html__( 'Activities Template', 'wp-travel-engine' );
		$templates['templates/template-trip_types.php']   = esc_html__( 'Trip Types Template', 'wp-travel-engine' );
		$templates['templates/template-trip-listing.php'] = esc_html__( 'Trip Listing Template', 'wp-travel-engine' );
		return $templates;
	}

	/**
	 * Destination template added.
	 */
	function wpte_add_destination_templates() {
		 // If REST_REQUEST is defined (by WordPress) and is a TRUE, then it's a REST API request.
		$is_rest_route = ( defined( 'REST_REQUEST' ) && REST_REQUEST );
		if (
			( is_admin() && ! $is_rest_route ) || // admin and AJAX (via admin-ajax.php) requests
			( ! is_admin() && $is_rest_route )    // REST requests only
		) {
			add_filter( 'theme_page_templates', array( $this, 'wpte_filter_admin_page_templates' ) );
		} else {
			add_filter( 'page_template', array( $this, 'wpte_get_destination_template' ) );
		}
	}

	/*
	* Itinerary template
	*/
	function wpte_add_itinerary_template() {
		$screen = get_current_screen();
		if ( $screen && 'trip' === $screen->post_type ) {
			?>
			<div id="itinerary-template">
				<li id="itinerary-tabs{{index}}" data-id="{{index}}" class="itinerary-row">
					<span class="tabs-handle"><span></span></span>
					<i class="dashicons dashicons-no-alt delete-faq delete-icon" data-id="{{index}}"></i>
					<div class="itinerary-holder">
						<a class="accordion-tabs-toggle" href="javascript:void(0);">
							<span class="day-count">
								<?php echo esc_html( sprintf( __( 'Day-%s', 'wp-travel-engine' ), '{{index}}' ) ); ?>
							</span>
						</a>
						<div class="itinerary-content">
							<div class="title">
								<input placeholder="<?php esc_html_e( 'Itinerary Title:', 'wp-travel-engine' ); ?>" type="text" class="itinerary-title" name="wp_travel_engine_setting[itinerary][itinerary_title][{{index}}]" id="wp_travel_engine_setting[itinerary][itinerary_title][{{index}}]">
							</div>
							<div class="content">
								<textarea placeholder="<?php esc_html_e( 'Itinerary Content:', 'wp-travel-engine' ); ?>" rows="5" cols="32" class="itinerary-content" name="wp_travel_engine_setting[itinerary][itinerary_content][{{index}}]" id="wp_travel_engine_setting[itinerary][itinerary_content][{{index}}]"></textarea>
								<textarea rows="5" cols="32" class="itinerary-content-inner" name="wp_travel_engine_setting[itinerary][itinerary_content_inner][{{index}}]" id="wp_travel_engine_setting[itinerary][itinerary_content_inner][{{index}}]"></textarea>
							</div>
						</div>
					</div>
				</li>
			</div>
			<style type="text/css">
				#itinerary-template {
					display: none !important;
				}
			</style>
			<?php
		}
	}

	/*
	* Itinerary template
	*/
	function wpte_add_faq_template() {
		$screen = get_current_screen();
		if ( $screen && 'trip' === $screen->post_type ) {
			?>
			<div id="faq-template">
				<li id="faq-tabs{{index}}" data-id="{{index}}" class="faq-row">
					<span class="tabs-handle"><span></span></span>
					<i class="dashicons dashicons-no-alt delete-faq delete-icon" data-id="{{index}}"></i>
					<div class="content-holder">
						<a class="accordion-tabs-toggle" href="javascript:void(0);">
							<span class="day-count"><?php echo esc_html( sprintf( 'FAQ-%s', '{{index}}' ) ); ?></span>
						</a>
						<div class="faq-content">
							<div class="title">
								<input placeholder="<?php esc_html_e( 'Question:', 'wp-travel-engine' ); ?>" type="text" class="faq-title" name="wp_travel_engine_setting[faq][faq_title][{{index}}]" id="wp_travel_engine_setting[faq][faq_title][{{index}}]">
							</div>
							<div class="content">
								<textarea placeholder="<?php esc_html_e( 'Answer:', 'wp-travel-engine' ); ?>" rows="3" cols="78" name="wp_travel_engine_setting[faq][faq_content][{{index}}]" id="wp_travel_engine_setting[faq][faq_content][{{index}}]"></textarea>
							</div>
						</div>
					</div>
				</li>
			</div>
			<style type="text/css">
				#faq-template {
					display: none !important;
				}
			</style>
			<?php
		}
	}

	/**
	 * Paypal activation notice.
	 *
	 * @since 1.1.1
	 */
	function wp_travel_engine_rating_notice() {
		 global $current_user;
		$user_id = $current_user->ID;
		if ( get_user_meta( $user_id, 'wp-travel-engine-rating-notice', true ) != 'true' ) {
			$link_plugin = '<a href="https://wordpress.org/plugins/wp-travel-engine/" target="_blank">WP Travel Engine</a>';
			$link_rating = '<a href="https://wordpress.org/support/plugin/wp-travel-engine/reviews/#new-post" target="_blank">WordPress.org</a>';
			$message     = sprintf( esc_html__( 'Thank you for using %1$s. Please rate us on %2$s.', 'wp-travel-engine' ), $link_plugin, $link_rating );
			printf(
				'<div class="updated notice"><p>%1$s <a href="?wp-travel-engine-rating-notice=1">Dismiss</a></p></div>',
				wp_kses(
					$message,
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					)
				)
			);
		}
	}

	function wp_travel_engine_notice_ignore() {
		global $current_user;

		$user_id = $current_user->ID;
		if ( isset( $_GET['wp-travel-engine-rating-notice'] ) && $_GET['wp-travel-engine-rating-notice'] = '1' ) { // phpcs:ignore
			add_user_meta( $user_id, 'wp-travel-engine-rating-notice', 'true', true );
		}
	}

	/**
	 * Paypal settings form.
	 *
	 * @since 1.1.1
	 */
	function wte_paypal_form() {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
		?>
		<div class="wte-paypal-gateway-form">
			<label for="wp_travel_engine_settings[paypal_id]"><?php esc_html_e( 'PayPal ID : ', 'wp-travel-engine' ); ?> <span class="tooltip" title="Enter a valid Merchant account ID (strongly recommend) or PayPal account email address. All payments will go to this account."><i class="fas fa-question-circle"></i></span></label>
			<input type="text" id="wp_travel_engine_settings[paypal_id]" name="wp_travel_engine_settings[paypal_id]" value="<?php echo isset( $wp_travel_engine_settings['paypal_id'] ) ? esc_attr( $wp_travel_engine_settings['paypal_id'] ) : ''; ?>">
		</div>
		<?php
	}

	/**
	 * Payment Details.
	 *
	 * @since 1.1.1
	 */
	function wpte_trip_pay_add_meta_boxes() {
		$screens = array( 'booking' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'pay_id',
				__( 'Paypal Payment Details', 'wp-travel-engine' ),
				array( $this, 'wp_travel_engine_pay_metabox_callback' ),
				$screen,
				'side',
				'high'
			);
		}
	}

	// Tab for notice listing and settings
	public function wp_travel_engine_pay_metabox_callback() {
		include \WP_TRAVEL_ENGINE_BASE_PATH . '/includes/backend/booking/pay.php';
	}

	/**
	 * Dashboard page.
	 *
	 * @return void
	 */
	public function wp_travel_engine_dashboard_menu() {
		// add_menu_page( __( 'WP Travel Engine', 'wte' ), __( 'WP Travel Engine', 'wte' ), 'manage_options', 'wp-travel-engine-dashboard', array( $this, 'wp_travel_engine_dashboard' ), null, 40 );
		global $submenu;
		unset( $submenu['edit.php?post_type=booking'][10] ); // Removes 'Add New'.
	}

	/**
	 * Dashboard page.
	 *
	 * @return void
	 */
	public function wp_travel_engine_dashboard() {
		?>
			<div id="wte-dashbard-analytics"></div>
		<?php
	}

	/**
	 *
	 * Displays themes.
	 *
	 * @since 1.1.7
	 */
	function wp_travel_engine_extensions_callback_function() {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/backend/submenu/extensions.php';
	}

	/**
	 *
	 * Displays extensions.
	 *
	 * @since 1.1.7
	 */
	function wp_travel_engine_themes_callback_function() {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/backend/submenu/themes.php';
	}

	function wte_tinymce_config( $init ) {
		// Don't remove line breaks
		$init['remove_linebreaks'] = false;
		// Convert newline characters to BR tags
		$init['convert_newlines_to_brs'] = true;
		// Do not remove redundant BR tags
		$init['remove_redundant_brs'] = false;

		// Pass $init back to WordPress
		return $init;
	}

	/**
	 * Add Enquiry column in the enquiry list.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_trip_cpt_columns( $columns ) {
		$new_columns = array(
			'tid' => esc_html__( 'Trip ID', 'wp-travel-engine' ),
		);
		return array_merge( $columns, $new_columns );
	}

	/**
	 * Show value to the corresponsing columns for booking post type.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_trip_custom_columns( $column, $post_id ) {
		$wp_travel_engine_setting = get_post_meta( $post_id, 'wp_travel_engine_setting', true );
		$screen                   = get_current_screen();
		if ( $screen && 'trip' === $screen->post_type ) {
			switch ( $column ) {

				case 'tid':
					echo (int) $post_id;
					break;
			}
		}
	}

	/**
	 * Display incompatible plugins list in the plugin update message.
	 *
	 * @param [type] $plugin_data
	 * @param [type] $response
	 * @return void
	 */
	public function in_plugin_update_message( $plugin_data, $response ) {
		$compatibility_check = new WP_Travel_Engine_Compatibility_Check();
		if ( $compatibility_check->requires_backward_processs() || ! empty( $compatibility_check->updated_addons_actives() ) ) {
			echo '<style>#wp-travel-engine-update .update-message.notice.inline.notice-warning.notice-alt p:last-child {display: none;}</style>';
			$update_messages = $compatibility_check->update_messages( $response );
		}
	}

	/**
	 * Display admin notices.
	 *
	 * @return void
	 */
	public function admin_notices() {
		$this->display_opt_in_notice_for_message_feature();
	}

	/**
	 * Display opt-in notice for message feature.
	 *
	 * @return void
	 */
	private function display_opt_in_notice_for_message_feature() {
		// Bail early if the messages is feature is set.
		$messages_enabled = get_option( 'wte_messages_enabled' );
		if ( false !== $messages_enabled ) {
			return;
		}
		// phpcs:disable
		// Set the message to enable or dismiss. '1' for enable and '0' for dismiss
		if ( isset( $_GET['wte-message-enabled'] ) && in_array( $_GET['wte-message-enabled'], array( '0', '1' ) ) ) {
			update_option( 'wte_messages_enabled', wte_clean( wp_unslash( $_GET['wte-message-enabled'] ) ) );
			return;
		}

		// Construct agree and dismiss url based on the query string.
		$request_uri = wte_clean( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		if ( empty( $_SERVER['QUERY_STRING'] ) ) {
			$agree_url   = $request_uri . '?wte-message-enabled=1';
			$dismiss_url = $request_uri . '?wte-message-enabled=0';
		} else {
			$agree_url   = $request_uri . '&wte-message-enabled=1';
			$dismiss_url = $request_uri . '&wte-message-enabled=0';
		}
		// phpcs:enable
		?>

		<div class="wte-admin-notice notice notice-info is-dismissible" style="padding-bottom: 10px;">
			<p><strong><?php esc_html_e( 'WP Travel Engine Message: ', 'wp-travel-engine' ); ?></strong><?php esc_html_e( 'Get messages about new update releases, upcoming features, and exciting offers from WP Travel Engine?', 'wp-travel-engine' ); ?></p>
			<p><i><?php esc_html_e( 'Note: By clicking yes, you will get an additional messages menu inside Trips menu that shows release notes, update notifications and new offers with helpful links. This will also let the plugin anonymously collect usage information to help WP Travel Engine team improve the product.', 'wp-travel-engine' ); ?></i></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'wp-travel-engine' ); ?></span>
			</button>
			<a href="<?php echo esc_url( $agree_url ); ?>" class="button button-primary">
				<?php esc_html_e( 'Yes, I\'m in', 'wp-travel-engine' ); ?>
			</a>
			<a href="<?php echo esc_url( $dismiss_url ); ?>" class="button">
				<?php esc_html_e( 'No Thanks', 'wp-travel-engine' ); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Function to call Advanced itinerary template up on front or default parent template
	 *
	 * @return void
	 */
	public function wte_itinerary_setting() {
		$itinerary_settings = apply_filters( 'wte_trip_itinerary_setting_path', \WP_TRAVEL_ENGINE_BASE_PATH . '/admin/meta-parts/tabs-inner/itinerary-setting.php' );
		include $itinerary_settings;
	}

	/**
	 * Add customer bulk action for migration from post type to users.
	 *
	 * @return void
	 */
	public function wte_add_customer_bulk_actions( $bulk_array ) {
		$bulk_array['wte_migrate_customers'] = esc_html__( 'Migrate to users', 'wp-travel-engine' );
		return $bulk_array;
	}

	/**
	 * Handle customers to users bulk action.
	 *
	 * @param [type] $redirect
	 * @param [type] $doaction
	 * @param [type] $object_ids
	 * @return void
	 */
	public function wte_add_customer_bulk_action_handler( $redirect, $doaction, $object_ids ) {

		// let's remove query args first
		$redirect = remove_query_arg( array( 'wte_bulk_customers_to_users_done' ), $redirect );

		// do something for "Migrate to users" bulk action.
		if ( 'wte_migrate_customers' === $doaction ) {
			foreach ( $object_ids as $post_id ) {

				$customer_bookings = get_post_meta( $post_id, 'wp_travel_engine_bookings', true );
				$booking_details   = get_post_meta( $post_id, 'wp_travel_engine_booking_setting', true );
				$user_email        = get_the_title( $post_id );
				$username          = sanitize_user( current( explode( '@', $user_email ) ), true );

				// Ensure username is unique.
				$append     = 1;
				$o_username = $username;

				while ( username_exists( $username ) ) {
					$username = $o_username . $append;
					$append++;
				}

				// Bail if user already exists.
				if ( username_exists( $username ) || email_exists( $user_email ) ) {
					continue;
				}

				$password_generated = wp_generate_password();

				$new_customer_data = apply_filters(
					'wp_travel_engine_new_customer_data',
					array(
						'user_login' => $username,
						'user_pass'  => $password_generated,
						'user_email' => $user_email,
						'role'       => 'wp-travel-engine-customer',
					)
				);
				$customer_id       = wp_insert_user( $new_customer_data );

				update_user_meta( $customer_id, 'wp_travel_engine_user_bookings', $customer_bookings );

				update_user_meta( $customer_id, 'wp_travel_engine_customer_booking_details', $booking_details );

				do_action( 'wp_travel_engine_created_customer', $customer_id, $new_customer_data, $password_generated, $template = 'emails/customer-migrated.php' );
			}
			// do not forget to add query args to URL because we will show notices later
			$redirect = add_query_arg(
				'wte_bulk_customers_to_users_done', // just a parameter for URL (we will use $_GET['wte_bulk_customers_to_users_done'] )
				count( $object_ids ), // parameter value - how much posts have been affected
				$redirect
			);
		}
		return $redirect;
	}

	/**
	 * Add notice after completion of user migration.
	 *
	 * @return void
	 */
	public function customer_bulk_action_notices() {
		// first of all we have to make a message,
		// of course it could be just "Posts updated." like this:
		if ( ! empty( $_REQUEST['wte_bulk_customers_to_users_done'] ) ) { // phpcs:ignore
			echo '<div id="message" class="updated notice is-dismissible">
				<p>' . esc_html__( 'Selected users migrated.', 'wp-travel-engine' ) . '</p>
			</div>';
		}
	}

	/**
	 * Add data to custom column.
	 *
	 * @param  String $column_name Custom column name.
	 * @param  int    $id          Post ID.
	 */
	public function wte_itineraries_manage_columns( $column_name, $id ) {
		switch ( $column_name ) {
			case 'featured':
				$featured = get_post_meta( $id, 'wp_travel_engine_featured_trip', true );
				$featured = ( isset( $featured ) && '' != $featured ) ? $featured : 'no';

				$icon_class = ' dashicons-star-empty ';
				if ( ! empty( $featured ) && 'yes' === $featured ) {
					$icon_class = ' dashicons-star-filled ';
				}
				$nonce = wp_create_nonce( 'wp_travel_engine_featured_trip' );
				printf( '<a href="#" class="wp-travel-engine-featured-trip dashicons %s" data-post-id="%d"  data-nonce="%s"></a>', esc_attr( $icon_class ), esc_attr( $id ), esc_attr( $nonce ) );
				break;
			default:
				break;
		} // end switch
	}

	/**
	 * Customize Admin column.
	 *
	 * @param  Array $booking_columns List of columns.
	 * @return Array                  [description]
	 */
	function wp_travel_engine_trips_columns( $itinerary_columns ) {
		$itinerary_columns['featured'] = esc_html__( 'Featured', 'wp-travel-engine' );
		return $itinerary_columns;
	}

	/**
	 * Ajax for adding featured trip meta
	 * */
	public static function wp_travel_engine_featured_trip_admin_ajax( $post_data ) {

		header( 'Content-Type: application/json' );
		$post_id         = intval( $post_data['post_id'] );
		$featured_status = esc_attr( get_post_meta( $post_id, 'wp_travel_engine_featured_trip', true ) );
		$new_status      = 'yes' === $featured_status ? 'no' : 'yes';
		update_post_meta( $post_id, 'wp_travel_engine_featured_trip', $new_status );
		echo wp_json_encode(
			array(
				'ID'         => $post_id,
				'new_status' => $new_status,
			)
		);
		die();
	}

	/**
	 * Ajax for adding featured trip meta
	 * */
	public static function wp_travel_engine_featured_term_admin_ajax( $post_data ) {
		header( 'Content-Type: application/json' );
		$post_id         = intval( $post_data['post_id'] );
		$featured_status = esc_attr( get_term_meta( $post_id, 'wte_trip_tax_featured', true ) );
		$new_status      = 'yes' === $featured_status ? 'no' : 'yes';
		update_term_meta( $post_id, 'wte_trip_tax_featured', $new_status );
		echo wp_json_encode(
			array(
				'ID'         => $post_id,
				'new_status' => $new_status,
			)
		);
		die();
	}

	/**
	 * Get Enquiry preview.
	 *
	 * @return void
	 */
	public static function wte_get_enquiry_preview_action( $post_data ) {
		if ( isset( $post_data['enquiry_id'] ) ) {
			$enquiry_id                        = (int) $post_data['enquiry_id'];
			$wp_travel_engine_setting          = get_post_meta( $enquiry_id, 'wp_travel_engine_setting', true );
			$wp_travel_engine_enquiry_formdata = get_post_meta( $enquiry_id, 'wp_travel_engine_enquiry_formdata', true );
			$wte_old_enquiry_details           = isset( $wp_travel_engine_setting['enquiry'] ) ? $wp_travel_engine_setting['enquiry'] : array();
			ob_start();
			?>
				<div style="background-color:#ffffff" class="wpte-main-wrap wpte-edit-enquiry">
					<div class="wpte-block-wrap">
						<div class="wpte-block">
							<div class="wpte-block-content">
								<ul class="wpte-list">
									<?php
									if ( ! empty( $wp_travel_engine_enquiry_formdata ) ) :
										foreach ( $wp_travel_engine_enquiry_formdata as $key => $data ) :
											$data       = is_array( $data ) ? implode( ', ', $data ) : $data;
											$data_label = wp_travel_engine_get_enquiry_field_label_by_name( $key );

											if ( 'package_name' === $key ) {
												$data_label = esc_html__( 'Package Name', 'wp-travel-engine' );
											}
											?>
												<li>
													<b><?php echo esc_html( $data_label ); ?></b>
													<span>
													<?php echo wp_kses_post( $data ); ?>
													</span>
												</li>
											<?php
											endforeach;
										else :
											if ( ! empty( $wte_old_enquiry_details ) ) :
												if ( isset( $wte_old_enquiry_details['pname'] ) ) :
													?>
														<li>
															<b><?php esc_html_e( 'Package Name', 'wp-travel-engine' ); ?></b>
															<span>
																<?php echo wp_kses_post( $wte_old_enquiry_details['pname'] ); ?>
															</span>
														</li>
													<?php
												endif;
												if ( isset( $wte_old_enquiry_details['name'] ) ) :
													?>
														<li>
															<b><?php esc_html_e( 'Name', 'wp-travel-engine' ); ?></b>
															<span>
																<?php echo wp_kses_post( $wte_old_enquiry_details['name'] ); ?>
															</span>
														</li>
													<?php
												endif;
												if ( isset( $wte_old_enquiry_details['email'] ) ) :
													?>
														<li>
															<b><?php esc_html_e( 'Email', 'wp-travel-engine' ); ?></b>
															<span>
																<?php echo wp_kses_post( $wte_old_enquiry_details['email'] ); ?>
															</span>
														</li>
													<?php
												endif;
												if ( isset( $wte_old_enquiry_details['country'] ) ) :
													?>
														<li>
															<b><?php esc_html_e( 'Country', 'wp-travel-engine' ); ?></b>
															<span>
																<?php echo wp_kses_post( $wte_old_enquiry_details['country'] ); ?>
															</span>
														</li>
													<?php
												endif;
												if ( isset( $wte_old_enquiry_details['contact'] ) ) :
													?>
														<li>
															<b><?php esc_html_e( 'Contact', 'wp-travel-engine' ); ?></b>
															<span>
																<?php echo wp_kses_post( $wte_old_enquiry_details['contact'] ); ?>
															</span>
														</li>
													<?php
												endif;
												if ( isset( $wte_old_enquiry_details['adults'] ) ) :
													?>
														<li>
															<b><?php esc_html_e( 'Adults', 'wp-travel-engine' ); ?></b>
															<span>
																<?php echo wp_kses_post( $wte_old_enquiry_details['adults'] ); ?>
															</span>
														</li>
													<?php
												endif;
												if ( isset( $wte_old_enquiry_details['children'] ) ) :
													?>
														<li>
															<b><?php esc_html_e( 'Children', 'wp-travel-engine' ); ?></b>
															<span>
																<?php echo wp_kses_post( $wte_old_enquiry_details['children'] ); ?>
															</span>
														</li>
													<?php
												endif;
												if ( isset( $wte_old_enquiry_details['message'] ) ) :
													?>
														<li>
															<b><?php esc_html_e( 'Message', 'wp-travel-engine' ); ?></b>
															<span>
																<?php echo wp_kses_post( $wte_old_enquiry_details['message'] ); ?>
															</span>
														</li>
													<?php
												endif;
											endif;
										endif;
										?>
								</ul>
							</div>
						</div> <!-- .wpte-block -->
					</div> <!-- .wpte-block-wrap -->
				</div><!-- .wpte-main-wrap -->
			<?php
			$data = ob_get_clean();

			wp_send_json_success(
				array(
					'message' => esc_html__( 'Data Fetched', 'wp-travel-engine' ),
					'html'    => $data,
				)
			);
		}
		wp_send_json_error( array( 'message' => esc_html__( 'Enquiry ID is missing', 'wp-travel-engine' ) ) );
	}

	/**
	 * Load tab ajax callback.
	 *
	 * @return void
	 */
	public static function wpte_admin_load_tab_content_callback( $post_data ) {
		// phpcs:disable
		$tab_details = isset( $post_data['tab_details'] ) ? $post_data['tab_details'] : false;

		if ( $tab_details ) {

			$content_path = isset( $tab_details['content_path'] ) ? base64_decode( $tab_details['content_path'] ) : '';

			ob_start();
			if ( file_exists( $content_path ) ) {
				?>
				<div data-trigger="<?php echo esc_attr( $tab_details['content_key'] ); ?>" class="wpte-tab-content <?php echo esc_attr( $tab_details['content_key'] ); ?>-content ">
					<div class="wpte-title-wrap">
						<h2 class="wpte-title"><?php echo esc_html( $tab_details['tab_heading'] ); ?></h2>
					</div> <!-- .wpte-title-wrap -->
					<div class="wpte-block-content">
					<?php
						$args['post_id'] = wte_clean( wp_unslash( $post_data['post_id'] ) );
						$args['next_tab'] = wte_clean( wp_unslash( $post_data['next_tab'] ) );
						$args['tab_details'] = wte_clean( wp_unslash( $post_data['tab_details'] ) );
						// load template.
						include $content_path;
					?>
					</div>
				</div>
			<?php
			}
			$data = ob_get_clean();

			wp_send_json_success(
				array(
					'message' => esc_html__( 'Data Fetched', 'wp-travel-engine' ),
					'html'    => $data,
				)
			);
		}
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid Tab Data', 'wp-travel-engine' ) ) );
		// phpcs:enable
	}

	/**
	 * Load global settings tab ajax callback.
	 *
	 * @return void
	 */
	public static function wpte_global_settings_load_tab_content_callback( $post_data ) {

		if ( ! class_exists( '\Wp_Travel_Engine_Settings' ) ) {
			require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-settings.php';
		}

		$tab_details     = isset( $post_data['tab_details'] ) ? $post_data['tab_details'] : false;
		$tab_content_key = isset( $post_data['content_key'] ) ? $post_data['content_key'] : false;

		if ( $tab_details ) {
			ob_start();
			?>
			<div class="wpte-tab-content <?php echo esc_attr( $tab_content_key ); ?>-content wpte-global-settngstab">
				<div class="wpte-block-content">
					<?php
					$sub_tabs = isset( $tab_details['sub_tabs'] ) && ! empty( $tab_details['sub_tabs'] ) ? $tab_details['sub_tabs'] : array();
					if ( ! empty( $sub_tabs ) ) :
						?>
							<div class="wpte-tab-sub wpte-horizontal-tab">
								<div class="wpte-tab-wrap">
							<?php
								$current = 1;
							foreach ( $sub_tabs as $key => $tab ) :
								$has_updates = isset( $tab['has_updates'] ) ? $tab['has_updates'] : '';
								?>
									<a href="javascript:void(0);" data-wte-update="<?php echo esc_attr( $has_updates ); ?>" class="wpte-tab <?php echo esc_attr( $key ); ?> <?php echo 1 === $current ? 'current' : ''; ?>"><?php echo esc_html( $tab['label'] ); ?></a>
								<?php
								$current++;
								endforeach;
							?>
								</div>
								<div class="wpte-tab-content-wrap">
								<?php
								$current = 1;
								foreach ( $sub_tabs as $key => $tab ) :
									$tab_content_class  = isset( $tab['has_sidebar'] ) && $tab['has_sidebar'] ? 'wpte-tab-content-sidebar' : '';
									$tab_content_class .= " {$key}-content";
									$tab_content_class .= 1 === $current ? ' current' : '';
									$tab_aside_content  = '';
									?>
									<div class="wpte-tab-content <?php echo esc_attr( $tab_content_class ); ?>">
										<div class="wpte-block-content">
										<?php
										if ( file_exists( $tab['content_path'] ) ) {
											include $tab['content_path'];
										}
										?>
										</div>
										<?php
										if ( ! empty( $tab_aside_content ) ) {
											printf( '<div class="wpte-block-content-aside">%s</div>', wp_kses_post( $tab_aside_content ) );
										}
										?>
									</div>
									<?php
									$current++;
								endforeach;
								?>
								</div>
							</div>
						<?php
						else :
							?>
							<div class="wpte-alert">
								<?php
								echo wp_kses(
									// Translators: %1$s: Addon download link.
									sprintf(
										__( 'There are no <b>WP Travel Engine Addons</b> installed on your site currently. To extend features and get additional functionality settings,  <a target="_blank" href="%1$s">Get Addons Here</a>', 'wp-travel-engine' ),
										WP_TRAVEL_ENGINE_STORE_URL . '/plugins/'
									),
									array(
										'a' => array(
											'target' => array(),
											'href'   => array(),
										),
									)
								);
								?>
								</div>
							<?php
						endif;
						?>
					<div class="wpte-field wpte-submit">
						<input data-tab="<?php echo esc_attr( $tab_content_key ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_global_tabs_save_data' ) ); ?>" class="wpte-save-global-settings" type="submit" name="wpte_save_global_settings" value="<?php esc_attr_e( 'Save & Continue', 'wp-travel-engine' ); ?>">
					</div>
				</div> <!-- .wpte-block-content -->
			</div>
			<?php
			$data = ob_get_clean();

			wp_send_json_success(
				array(
					'message' => esc_html__( 'Data Fetched', 'wp-travel-engine' ),
					'html'    => $data,
				)
			);
		}
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid Tab Data', 'wp-travel-engine' ) ) );
	}

	public static function sanitize_post_data( $posted_data ) {

		$special_fields = array(
			'type'       => 'array',
			'properties' => array(
				'wp_travel_engine_setting' => array(
					'type'       => 'array',
					'properties' => array(
						'tab_content' => array(
							'type'  => 'array',
							'items' => array(
								'type'              => 'string',
								'sanitize_callback' => 'wp_kses_post',
							),
						),
						'itinerary'   => array(
							'type'       => 'array',
							'properties' => array(
								'itinerary_content' => array(
									'type'  => 'array',
									'items' => array(
										'type' => 'string',
										'sanitize_callback' => 'wp_kses_post',
									),
								),
							),
						),
						'cost'        => array(
							'type'       => 'array',
							'properties' => array(
								'cost_includes' => array(
									'type'              => 'string',
									'sanitize_callback' => 'sanitize_textarea_field',
								),
								'cost_excludes' => array(
									'type'              => 'string',
									'sanitize_callback' => 'sanitize_textarea_field',
								),
							),
						),
						'map'         => array(
							'type'       => 'array',
							'properties' => array(
								'iframe' => array(
									'type'              => 'string',
									'sanitize_callback' => function( $value ) {
										return wp_kses( $value, 'wte_iframe' );
									},
								),
							),
						),
						'faq'         => array(
							'type'       => 'array',
							'properties' => array(
								'faq_content' => array(
									'type'  => 'array',
									'items' => array(
										'type' => 'string',
										'sanitize_callback' => 'sanitize_textarea_field',
									),
								),
							),
						),
					),
				),
				'packages_descriptions'    => array(
					'type'  => 'array',
					'items' => array(
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_textarea_field',
					),
				),

			),
		);

		// Handle Trip facts differently.
		$_trip_facts = array();
		if ( isset( $posted_data['wp_travel_engine_setting']['trip_facts'] ) && is_array( $posted_data['wp_travel_engine_setting']['trip_facts'] ) ) {
			$trip_facts = wp_unslash( $posted_data['wp_travel_engine_setting']['trip_facts'] );
			foreach ( $trip_facts as $key => $_trip_fact ) {
				if ( in_array( $key, array( 'field_id', 'field_type' ), true ) ) {
					$_trip_facts[ $key ] = wte_input_clean( $_trip_fact );
				} else {
					array_walk_recursive(
						$_trip_fact,
						function( $value, $k ) use ( $key, &$_trip_facts ) {
							$_trip_facts[ $key ][ $k ] = wp_kses_post( $value );
						}
					);
				}
			}
		}

		$sanitized_data = wte_input_clean( $posted_data, $special_fields );

		$sanitized_data['wp_travel_engine_setting']['trip_facts'] = $_trip_facts;

		return $sanitized_data;
	}

	/**
	 * Save and continue button callback.
	 *
	 * @return void
	 */
	public static function wpte_tab_trip_save_and_continue_callback( $post_data ) {

		if ( empty( $post_data['post_id'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Post ID not found', 'wp-travel-engine' ) ) );
		}
		$post_id = $post_data['post_id'];

		if ( isset( $post_data['action'] ) && 'wpte_tab_trip_save_and_continue' === $post_data['action'] ) {
			$obj                            = \wte_functions();
			$wp_travel_engine_setting_saved = get_post_meta( $post_id, 'wp_travel_engine_setting', true );

			if ( empty( $wp_travel_engine_setting_saved ) ) {
				$wp_travel_engine_setting_saved = array();
			}
			$wp_travel_engine_setting_saved = $obj->recursive_html_entity_decode( $wp_travel_engine_setting_saved );

			$meta_to_save = array();
			if ( isset( $post_data['wp_travel_engine_setting'] ) ) {
				$meta_to_save = $post_data['wp_travel_engine_setting'];
			}

			// Merge data.
			$metadata_merged_with_saved = array_merge( $wp_travel_engine_setting_saved, $meta_to_save );

			$checkboxes_array = array(
				'general' => array(
					'trip_cutoff_enable',
					'min_max_age_enable',
					'minmax_pax_enable',
				),
				'pricing' => array(
					'sale',
				),
				'gallery' => array(
					'enable_video_gallery',
				),
			);

			$trip_meta_checkboxes = apply_filters( 'wp_travel_engine_trip_meta_checkboxes', $checkboxes_array );

			if ( isset( $post_data['tab'] ) ) {
				$active_tab = $post_data['tab'];

				if ( isset( $trip_meta_checkboxes[ $active_tab ] ) ) {
					foreach ( $trip_meta_checkboxes[ $active_tab ] as $checkbox ) {
						if ( isset( $metadata_merged_with_saved[ $checkbox ] ) && ! isset( $meta_to_save[ $checkbox ] ) ) {
							unset( $metadata_merged_with_saved[ $checkbox ] );
						}
					}
				}
			}

			$arrays_in_meta = array(
				'itinerary',
				'faq',
				'trip_facts',
				'trip_highlights',
			);

			$arrays_in_meta = apply_filters( 'wpte_trip_meta_array_key_bases', $arrays_in_meta );

			foreach ( $arrays_in_meta as $arr_key ) {
				if ( isset( $meta_to_save[ $arr_key ] ) && ! is_array( $meta_to_save[ $arr_key ] ) ) {
					unset( $metadata_merged_with_saved[ $arr_key ] );
				}
			}

			update_post_meta( $post_id, 'wp_travel_engine_setting', $metadata_merged_with_saved );

			/**
			 * Hook for Save& Continue support on addons.
			 */
			do_action( 'wpte_save_and_continue_additional_meta_data', $post_id, $post_data );

			if ( isset( $metadata_merged_with_saved['trip_price'] ) ) {
				update_post_meta( $post_id, 'wp_travel_engine_setting_trip_price', $metadata_merged_with_saved['trip_price'] );
			}

			if ( isset( $metadata_merged_with_saved['trip_prev_price'] ) ) {
				update_post_meta( $post_id, 'wp_travel_engine_setting_trip_prev_price', $metadata_merged_with_saved['trip_prev_price'] );
			}

			if ( isset( $metadata_merged_with_saved['trip_duration'] ) ) {
				update_post_meta( $post_id, 'wp_travel_engine_setting_trip_duration', $metadata_merged_with_saved['trip_duration'] );
			}

			if ( isset( $post_data['wpte_gallery_id'] ) ) {
				update_post_meta( $post_id, 'wpte_gallery_id', $post_data['wpte_gallery_id'] );
			}

			// Update / Save gallery metas.
			if ( isset( $post_data['wp_travel_engine_setting']['enable_video_gallery'] ) ) {
				update_post_meta( $post_id, 'wpte_vid_gallery', $post_data['wpte_vid_gallery'] );
			}

			if ( isset( $post_data['wp_travel_engine_trip_min_age'] ) ) {
				update_post_meta( $post_id, 'wp_travel_engine_trip_min_age', $post_data['wp_travel_engine_trip_min_age'] );
			}

			if ( isset( $post_data['wp_travel_engine_trip_max_age'] ) ) {
				update_post_meta( $post_id, 'wp_travel_engine_trip_max_age', $post_data['wp_travel_engine_trip_max_age'] );
			}

			wp_send_json_success( array( 'message' => 'Trip settings saved successfully.' ) );
		}
	}

	/**
	 * Callback for global tabs data save action.
	 *
	 * @return void
	 */
	function wpte_global_tabs_save_data_callback() {

		if ( ! class_exists( '\Wp_Travel_Engine_Settings' ) ) {
			require_once plugin_dir_path( \WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-settings.php';
		}

		\Wp_Travel_Engine_Settings::save_settings();

		exit;
	}

	/**
	 * Display Trip Code Section
	 */
	function wpte_display_trip_code_section() {
		global $post;

		// Edit Trip Code filter
		$trip_code_edit = apply_filters( 'wpte_edit_trip_code', true );

		if ( $trip_code_edit ) {

			/**
			 * wp_travel_engine_edit_trip_code hook
			 *
			 * @hooked wte_edit_trip_code_section - Trip Code Addon
			 */
			do_action( 'wp_travel_engine_edit_trip_code' );

		} else {
			?>
				<div class="wpte-field wpte-trip-code wpte-floated">
					<label class="wpte-field-label"><?php esc_html_e( 'Trip Code', 'wp-travel-engine' ); ?></label>
					<span class="wpte-trip-code-box"><?php echo esc_html( sprintf( __( 'WTE-%1$s', 'wp-travel-engine' ), $post->ID ) ); ?></span>
					<div class="wpte-info-block">
						<p>
							<?php
								echo wp_kses(
									sprintf( __( 'Need to edit trip code to set your own? Trip Code extension allows you to add unique trip code to your trips. %1$sGet Trip Code extension now%2$s.', 'wp-travel-engine' ), '<a target="_blank" href="https://wptravelengine.com/plugins/trip-code/?utm_source=setting&utm_medium=customer_site&utm_campaign=setting_addon">', '</a>' ),
									array(
										'a' => array(
											'href'   => array(),
											'target' => array(),
										),
									)
								);
							?>
						</p>
					</div>
				</div>
			<?php

		}
	}

	/**
	 * Display Extension Notes
	 */
	function wpte_display_extension_upsell_notes() {

		/**
		 * wte_after_pricing_options_section hook
		 *
		 * @hooked wte_add_group_discount_pricing - Group Discount Addon
		 * @hooked wpte_partial_payment_add_meta_boxes - Partial Payment Addon
		 */
		do_action( 'wte_after_pricing_options_section' );

		if ( ! class_exists( 'Wte_Partial_Payment_Admin' ) ) {
			?>
				<div class="wpte-form-block">
					<div class="wpte-title-wrap">
						<h2 class="wpte-title"><?php esc_html_e( 'Partial Payment', 'wp-travel-engine' ); ?></h2>
					</div> <!-- .wpte-title-wrap -->
					<div class="wpte-info-block">
						<p>
							<?php
							echo wp_kses(
								sprintf(
									__( 'Want to collect upfront or partial payment? Partial Payment extension allows you to set upfront payment in percentage or fixed amount which travellers can pay when booking a tour. %1$sGet Partial Payment extension now%2$s.', 'wp-travel-engine' ),
									'<a target="_blank" href="https://wptravelengine.com/plugins/partial-payment/?utm_source=setting&utm_medium=customer_site&utm_campaign=setting_addon">',
									'</a>'
								),
								array(
									'a' => array(
										'target' => array(),
										'href'   => array(),
									),
								)
							);
							?>
						</p>
					</div>
				</div>
			<?php
		}

		if ( ! class_exists( 'Wp_Travel_Engine_Group_Discount' ) ) {
			?>
				<div class="wpte-form-block">
					<div class="wpte-title-wrap">
						<h2 class="wpte-title"><?php esc_html_e( 'Group Discount', 'wp-travel-engine' ); ?></h2>
					</div> <!-- .wpte-title-wrap -->
					<div class="wpte-info-block">
						<p>
							<?php
								// Translators: %1$s: Opening anchor tag. %2$s: Closing anchor tag.
								echo wp_kses(
									sprintf(
										__( 'Want to provide group discounts and increase sales? Group Discount extension allows you to provide group discount on the basis of number booking a tour. %1$sGet Group Discount extension now%2$s.', 'wp-travel-engine' ),
										'<a target="_blank" href="https://wptravelengine.com/plugins/group-discount/?utm_source=setting&utm_medium=customer_site&utm_campaign=setting_addon">',
										'</a>'
									),
									array(
										'a' => array(
											'target' => array(),
											'href'   => array(),
										),
									)
								);
							?>
						</p>
					</div>
				</div>
			<?php
		}

	}

	/** Add class to the body for all trip pages */
	function wpte_body_class_before_header_callback( $classes ) {
		$screen = get_current_screen();

		// phpcs:disable
		if ( isset( $_GET['page'] ) ) {
			if(in_array( $screen->id, array( 'booking_page_class-wp-travel-engine-admin', 'wte-coupon', 'edit-wte-coupon', 'trip_page_class-wp-travel-engine-admin' ), true )
			|| in_array( $screen->post_type, array( 'trip', 'booking', 'customer' ), true )
			|| 'class-wp-travel-engine-admin.php' === $_GET['page']) {
				$classes .= 'wpte-activated';
			}
		}
		// phpcs:enable

		return $classes;
	}

	/** Add Custom Info inside the trip tab section */

	function wp_travel_engine_trip_custom_info() {

		$output = apply_filters( 'wp_travel_engine_filtered_trip_custom_info', false );
		if ( ! ! $output ) {
			echo wp_kses_post( $output );
			return;
		}
		?>
		<div style="margin-top:40px;" class="wpte-form-block">
			<div class="wpte-title-wrap">
				<h2 class="wpte-title"><?php esc_html_e( 'Itinerary Downloader', 'wp-travel-engine' ); ?></h2>
			</div> <!-- .wpte-title-wrap -->
			<div class="wpte-info-block">
				<b><?php esc_html_e( 'Note:', 'wp-travel-engine' ); ?></b>
				<p>
					<?php esc_html_e( 'Want travellers to download the tour details in PDF format and read later?', 'wp-travel-engine' ); ?>
					<?php
					if ( ! class_exists( 'Wte_Itinerary_Downloader' ) ) {
						echo wp_kses(
							sprintf(
								// Translators: %1$s: Opening Anchor tag %2$s: Closing anchor tag.
								__( '%1$sGet Itinerary Downloader extension now%2$s.', 'wp-travel-engine' ),
								'<a target="_blank" href="https://wptravelengine.com/plugins/itinerary-downloader/?utm_source=setting&utm_medium=customer_site&utm_campaign=setting_addon">',
								'</a>'
							),
							array(
								'a' => array(
									'target' => array(),
									'href'   => array(),
								),
							)
						);
					} else {
						echo wp_kses( __( 'You can configure Itinerary Downloader via <b>WP Travel Engine > Settings > Extensions > Itinerary Downloader</b>.', 'wp-travel-engine' ), array( 'b' => array() ) );
					}
					?>
				</p>
			</div>
			<?php
			if ( class_exists( 'Wte_Itinerary_Downloader' ) ) {
				$page_shortcode = '[wte_itinerary_downloader]';
				?>
				<div class="wpte-shortcode">
					<span class="wpte-tooltip"><?php esc_html_e( 'To display Itinerary Downloader in posts/pages/tabs/widgets use the following', 'wp-travel-engine' ); ?> <b><?php esc_html_e( 'Shortcode.', 'wp-travel-engine' ); ?></b></span>
					<div class="wpte-field wpte-field-gray wpte-floated">
						<input id="wpte-iten-down-code" readonly type="text" value="<?php esc_attr( $page_shortcode ); ?>">
						<button data-copyid="wpte-iten-down-code" class="wpte-copy-btn"><?php esc_html_e( 'Copy', 'wp-travel-engine' ); ?></button>
					</div>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
}
