<?php
/**
 * Trip Metas Tabs HTML
 *
 * @package WP_Travel_Engine
 */
$trip_meta_tabs = array(
	'wpte-general'               => array(
		'tab_label'         => esc_html__( 'General', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'General', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/general.php',
		'callback_function' => 'wpte_edit_trip_tab_general',
		'content_key'       => 'wpte-general',
		'current'           => true,
		'content_loaded'    => true,
		'priority'          => 10,
	),
	'wpte-pricing'               => array(
		'tab_label'         => esc_html__( 'Pricing', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'Pricing', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/pricing.php',
		'callback_function' => 'wpte_edit_trip_tab_pricing',
		'content_key'       => 'wpte-pricing',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 20,
	),
	'wpte-extra-services-upsell' => array(
		'tab_label'         => esc_html__( 'Extra Services', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'Extra Services', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/extra-services.php',
		'callback_function' => 'wpte_edit_trip_tab_extra_services_upsell',
		'content_key'       => 'wpte-extra-services-upsell',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 25,
	),
	'wpte-overview'              => array(
		'tab_label'         => esc_html__( 'Overview', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'Overview', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/overview.php',
		'callback_function' => 'wpte_edit_trip_tab_overview',
		'content_key'       => 'wpte-overview',
		'current'           => false,
		'content_loaded'    => true,
		'priority'          => 30,
	),
	'wpte-itinerary'             => array(
		'tab_label'         => esc_html__( 'Itinerary', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'Itinerary', 'wp-travel-engine' ),
		'content_path'      => apply_filters( 'wte_trip_itinerary_setting_path', WP_TRAVEL_ENGINE_BASE_PATH . '/admin/meta-parts/trip-tabs/itinerary.php' ),
		'callback_function' => 'wpte_edit_trip_tab_itinerary',
		'content_key'       => 'wpte-itinerary',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 40,
	),
	'wpte-include-exclude'       => array(
		'tab_label'         => __( 'Includes/Excludes', 'wp-travel-engine' ),
		'tab_heading'       => __( 'Includes/Excludes', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/includes-excludes.php',
		'callback_function' => 'wpte_edit_trip_tab_include_exclude',
		'content_key'       => 'wpte-include-exclude',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 50,
	),
	'wpte-facts'                 => array(
		'tab_label'         => esc_html__( 'Trip Info', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'Trip Info', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/trip-facts.php',
		'callback_function' => 'wpte_edit_trip_tab_facts',
		'content_key'       => 'wpte-facts',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 70,
	),
	'wpte-gallery'               => array(
		'tab_label'         => esc_html__( 'Gallery', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'Gallery', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/gallery.php',
		'callback_function' => 'wpte_edit_trip_tab_gallery',
		'content_key'       => 'wpte-gallery',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 80,
	),
	'wpte-map'                   => array(
		'tab_label'         => esc_html__( 'Map', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'Map', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/map.php',
		'callback_function' => 'wpte_edit_trip_tab_map',
		'content_key'       => 'wpte-map',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 90,
	),
	'wpte-faqs'                  => array(
		'tab_label'         => esc_html__( 'FAQs', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'FAQs', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/faqs.php',
		'callback_function' => 'wpte_edit_trip_tab_faqs',
		'content_key'       => 'wpte-faqs',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 100,
	),
	'wte_file_downloads'         => array(
		'tab_label'         => esc_html__( 'File Downloads', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'File Downloads', 'wp-travel-engine' ),
		'content_path'      => apply_filters( 'wte_trip_file_downloads_setting_path', WP_TRAVEL_ENGINE_BASE_PATH . '/admin/meta-parts/trip-tabs/file-downloads.php' ),
		'callback_function' => 'wpte_edit_trip_tab_wte_file_downloads',
		'content_key'       => 'wpte-tab wte_file_downloads',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 120,
	),
);
// Apply filter hooks.
$trip_meta_tabs = apply_filters( 'wp_travel_engine_admin_trip_meta_tabs', $trip_meta_tabs );
// Sorted array of tabs.
$trip_meta_tabs = wp_travel_engine_sort_array_by_priority( $trip_meta_tabs );

// Initialize tabs class.
require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . '/admin/class-wp-travel-engine-tabs-ui.php';
$admin_tabs_ui = new WP_Travel_Engine_Tabs_UI();

$tab_args = array(
	'id'          => 'wpte-edit-trip',
	'class'       => 'wpte-edit-trip',
	'content_key' => 'wpte_edit_trip_tabs',
);
// Load Tabs.
$admin_tabs_ui->init( $tab_args )->template( $trip_meta_tabs );
