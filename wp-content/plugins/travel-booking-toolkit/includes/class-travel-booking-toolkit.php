<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wptravelengine.com
 * @since      1.0.0
 *
 * @package    Travel_Booking_Toolkit
 * @subpackage Travel_Booking_Toolkit/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Travel_Booking_Toolkit
 * @subpackage Travel_Booking_Toolkit/includes
 * @author     wptravelengine <info@wptravelengine.com>
 */
class Travel_Booking_Toolkit {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Travel_Booking_Toolkit_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'TBT_VERSION' ) ) {
			$this->version = TBT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'travel-booking-toolkit';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Travel_Booking_Toolkit_Loader. Orchestrates the hooks of the plugin.
	 * - Travel_Booking_Toolkit_i18n. Defines internationalization functionality.
	 * - Travel_Booking_Toolkit_Admin. Defines all hooks for the admin area.
	 * - Travel_Booking_Toolkit_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-travel-booking-toolkit-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-travel-booking-toolkit-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-travel-booking-toolkit-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-travel-booking-toolkit-public.php';

		/**
		 * The class responsible for defining all the dummy posts
		 */
		require_once TBT_BASE_PATH . '/includes/class-travel-booking-toolkit-dummy-array.php';
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-travel-booking-toolkit-functions.php';

		/**
		 * The class responsible for defining customizer sections
		 */
		require_once TBT_BASE_PATH . '/public/partials/travel-booking-toolkit-partials.php';

		/**
		 * The class responsible for defining customizer sections
		 */
		require_once TBT_BASE_PATH . '/includes/fontawesome.php';

		$current_theme = wp_get_theme();

		// Added common controls.
		require_once  plugin_dir_path( dirname( __FILE__ ) ) . 'includes/customize-controls/customize-controls.php';
		
		if ( 'travel-muni' === $current_theme->get( 'TextDomain' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/travel-muni/partials.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/travel-muni/demo-content.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/travel-muni/helpers.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/travel-muni/customizer-settings.php';
		} else {
			/**
			 * Icon Text Widget
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/widget-icon-text.php';

			/**
			 * Call to Action Widget
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/widget-cta.php';

			/**
			 * Client logo Widget
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/widget-client-logo.php';

			/**
			 * Recent Posts Widget
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/widget-recent-posts.php';

			/**
			 * Contact Widget
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/widget-contact.php';

			/**
			 * Image Text Widget
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/widget-image-text.php';

			/**
			 * Taxonomy List Widget
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/widget-taxonomy-list.php';
			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-travel-booking-toolkit-customizer.php';
		}
		
		$this->loader = new Travel_Booking_Toolkit_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Travel_Booking_Toolkit_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Travel_Booking_Toolkit_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Travel_Booking_Toolkit_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_print_footer_scripts', $plugin_admin, 'travel_booking_toolkit_client_logo_template' );
		$this->loader->add_action( 'admin_print_footer_scripts', $plugin_admin, 'travel_booking_toolkit_icon_list_enqueue' );
    	$this->loader->add_action( 'admin_notices', $plugin_admin, 'travel_booking_toolkit_premium_addons_activate_notice' );
    	$this->loader->add_action( 'admin_init', $plugin_admin,'travel_booking_toolkit_premium_addons_activate_notice_ignore' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Travel_Booking_Toolkit_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'script_loader_tag', $plugin_public, 'travel_booking_toolkit_js_defer_files', 10 );
		// $this->loader->add_filter( 'tbt_home_sections', $plugin_public, 'front_page_sections' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Travel_Booking_Toolkit_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
