<?php
/**
 * User onboarding process.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Onboarding Process to assist user on intial setup on first activation of the plugin.
 */
class WP_TRAVEL_ENGINE_ONBOARDING_PROCESS {


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
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current page name
	 */
	private $page_name = 'wp-travel-engine-onboard';

	/**
	 * Initialize onboarding process class.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'wp_travel_engine_onboarding_menu_callback' ), 30 );
		add_action( 'admin_menu', array( $this, 'add_onboarding_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'wte_onboard_dynamic_flag_set' ) );
		add_action( 'admin_menu', array( $this, 'remove_menus' ) );
	}

	function remove_menus() {
		remove_menu_page( $this->page_name );
	}

	/**
	 * Add menu for Onboard Process
	 */
	function add_onboarding_admin_menu() {
		add_menu_page(
			esc_html__( 'WP Travel Engine - User Onboarding', 'wp-travel-engine' ),
			esc_html__( 'WP Travel Engine - User Onboarding', 'wp-travel-engine' ),
			'manage_options',
			$this->page_name,
			array( $this, 'wp_travel_engine_onboarding_menu_callback' )
		);
	}

	function wp_travel_engine_onboarding_menu_callback() {

		// Do not proceed if we're not on the right page.
		if ( ! isset( $_GET['page'] ) || $_GET['page'] !== $this->page_name ) { // phpcs:ignore
			return;
		}

		// Dump Loaded content buffer.
		if ( ob_get_length() ) {
			ob_end_clean();
		}
		$asset_script_path = '/dist/';
		$version_prefix    = '-' . WP_TRAVEL_ENGINE_VERSION;

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$asset_script_path = '/';
			$version_prefix    = '';
		}

		wp_enqueue_style( $this->plugin_name . '_core_ui', plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . 'dist/admin/wte-admin.css', array(), $this->version );

		wp_enqueue_script( 'wpte-user-onboarding', plugin_dir_url( __FILE__ ) . 'onboard-process/js/onboard-process-admin.js', array( 'jquery' ), WP_TRAVEL_ENGINE_VERSION, true );

		wp_enqueue_script( 'all', plugin_dir_url( __FILE__ ) . 'onboard-process/js/all.js', array( 'jquery', 'wpte-user-onboarding' ), WP_TRAVEL_ENGINE_VERSION, true );

		$ajax_nonce  = wp_create_nonce( 'wpte_onboard_save_function' );
		$ajax_object = array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => $ajax_nonce,
		);

		wp_localize_script( 'wpte-user-onboarding', 'WPTEOB_OBJ', $ajax_object );

		wp_enqueue_style( 'wpte-user-onboarding', plugin_dir_url( __FILE__ ) . 'onboard-process/css/onboard-process-admin.css', WP_TRAVEL_ENGINE_VERSION );

		wp_enqueue_script( 'wte-select2', plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/vendors/select2-4.0.13/select2.js', array( 'jquery' ), '4.0.13', true );
		wp_enqueue_style( 'wte-select2', plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/vendors/select2-4.0.13/select2.css', array(), '4.0.13' );

		add_filter( 'admin_body_class', array( $this, 'wpte_onboard_body_class_before_header_callback' ) );

		// Load fresh buffer.
		ob_start();
		/**
		 * Start the actual page content.
		 */
		include plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/onboard-process/views/header.php';
		include plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/onboard-process/views/onboard-process.php';
		include plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/onboard-process/views/footer.php';
		exit;
	}

	/**
	 * Get view file to display.
	 *
	 * @param string $view View to display.
	 * @return string
	 */
	public function get_view( $view ) {
		$view_path = plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . "includes/onboarding-process/views/{$view}.php";
		return $view_path;
	}

	/**
	 * Save & continue button callback
	 *
	 * @return void
	 */
	public static function wpte_onboard_save_function_callback() {

		// phpcs:disable
		if ( isset( $_POST['action'] ) && 'wpte_onboard_save_function' === $_POST['action'] ) {

			if ( empty( $wp_travel_engine_setting_saved ) ) {
				$wp_travel_engine_setting_saved = array();
			}

			$setting_to_save = isset( $_POST['wp_travel_engine_settings'] ) ? wte_clean( wp_unslash( $_POST['wp_travel_engine_settings'] ) ) : array();

			$next_tab = wte_clean( wp_unslash( $_POST['next_tab'] ) );

			$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', array() );

			$sanitized_settings_to_save = $setting_to_save;

			if ( isset( $sanitized_settings_to_save ) && is_array( $sanitized_settings_to_save ) ) {
				foreach ( $sanitized_settings_to_save as $key => $value ) {
					$data_key = array_key_exists( $key, $wp_travel_engine_settings );
					if ( $data_key !== false ) {
						if ( is_array( $value ) ) {
							foreach ( $value as $k => $v ) {
								$d_key = array_key_exists( $k, $value );
								if ( $d_key !== false ) {
									$wp_travel_engine_settings[ $key ][ $k ] = $v;
								}
							}
						} else {
							$wp_travel_engine_settings[ $key ] = $value;
						}
					} else {
						if ( is_array( $value ) ) {
							foreach ( $value as $k => $v ) {
								$wp_travel_engine_settings[ $key ][ $k ] = $v;
							}
						} else {
							$wp_travel_engine_settings[ $key ] = $value;
						}
					}
				}
			}

			update_option( 'wp_travel_engine_settings', $wp_travel_engine_settings );

			$message_array = array( 'message' => __( 'Settings Saved Sucessfully', 'wp-travel-engine' ) );

			$currency_code = ! empty( $_POST['wp_travel_engine_settings']['currency_code'] ) ? wte_clean( wp_unslash( $_POST['wp_travel_engine_settings']['currency_code'] ) ) : '';
			if ( ! empty( $currency_code ) ) {
				$additional_message = array(
					'additional_message' => 'yes',
					'currency_code'      => $currency_code,
				);
				$message_array      = array_merge( $message_array, $additional_message );
			}

			wp_send_json_success( $message_array );
		} else {
			wp_send_json_error( array( 'message' => __( 'Unauthorized Access. Aborting.', 'wp-travel-engine' ) ) );
		}
		wp_die();
		// phpcs:enable
	}

	/**
	 * Output for recommentation payment gateways
	 */
	public static function wte_onboard_dynamic_recommendation_callback() {
		// phpcs:ignore
		$currency_code = isset( $_POST['currency_code'] ) ? wte_clean( wp_unslash( $_POST['currency_code'] ) ) : 'USD';
		$addons_data   = get_transient( 'wp_travel_engine_onboard_addons_list' );
		if ( ! $addons_data ) {
			$addons_data = wp_safe_remote_get( WP_TRAVEL_ENGINE_STORE_URL . '/edd-api/v2/products/?category=payment-gateways&number=-1' );
			if ( is_wp_error( $addons_data ) ) {
				return;
			}
			$addons_data = wp_remote_retrieve_body( $addons_data );
			set_transient( 'wp_travel_engine_onboard_addons_list', $addons_data, 128 * HOUR_IN_SECONDS );
		}

		if ( ! empty( $addons_data ) ) {
			$addons_data = json_decode( $addons_data );
			$addons_data = $addons_data->products;
			// $addons_additonal_data = $addons_data->wte;
		}
		$output = '';
		if ( $addons_data ) {
			?>
			<div class="wpte-field wpte-block-link wpte-floated">
				<?php
				foreach ( $addons_data as $key => $product ) {
					$prod_info           = isset( $product ) && ! empty( $product ) ? $product->info : '';
					$wte_object          = ! empty( $product->wte ) ? $product->wte : '';
					$suported_currencies = is_object( $wte_object ) && ! empty( $wte_object ) ? $wte_object->supported_currencies : array();
					if ( in_array( $currency_code, $suported_currencies ) ) {
						$link = \WP_TRAVEL_ENGINE_STORE_URL . "plugins/{$product->info->slug}";
						?>
						<a href="<?php echo esc_url( $link ); ?>" title="<?php echo esc_html( $prod_info->title ); ?>" target="_blank">
							<img src="<?php echo esc_url( $prod_info->thumbnail ); ?>" class="attachment-showcase wp-post-image" alt="<?php echo esc_html( $prod_info->title ); ?>">
						</a>
						<?php
					}
				}
				?>
			</div>
			<?php
		}
		die();
	}


	/**
	 * Dynamic flag set to set value, that the first time onboarding page, for WP Travel Engine has been called.
	 */
	function wte_onboard_dynamic_flag_set() {
		// Do not proceed if we're not on the right page.
		if ( ! isset( $_GET['page'] ) || $_GET['page'] !== $this->page_name ) { // phpcs:ignore
			return;
		}
		update_option( 'wp_travel_engine_first_time_activation_flag', 'true' );
	}

	/** Add class in body for travel engine pages/posts */
	function wpte_onboard_body_class_before_header_callback() {
		$screen = get_current_screen();
		if ( ( isset( $_GET['page'] ) && $_GET['page'] == 'wp-travel-engine-onboard' ) || $screen->id == 'wp-travel-engine-onboard' ) { // phpcs:ignore
			$classes .= 'wpte-activated';
		} else {
			$classes .= '';
		}
		return $classes;
	}

	public static function add_query_arguements( $args ) {
		return esc_url( add_query_arguements( $args ) );
	}


}
$obj = new WP_TRAVEL_ENGINE_ONBOARDING_PROCESS( $this->get_plugin_name(), $this->get_version() );
$obj->init();
