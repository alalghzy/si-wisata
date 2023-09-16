<?php
/**
 * Travel Booking Theme Customizer
 *
 * @package Travel_Booking
 */

$travel_booking_panels   = array( 'appearance', 'frontpage', 'general' );
$travel_booking_sections = array( 'info', 'site-identity', 'sidebar-layout', 'footer' );

$travel_booking_sub_sections = array(
    'appearance' => array( 'color', 'fonts' ),
    'frontpage'  => array( 'banner','search', 'about', 'blog' ),
    'general'   => array( 'post-page', '404-page', 'seo' ),
);

foreach( $travel_booking_panels as $p ){
   require get_template_directory() . '/inc/customizer/panels/' . $p . '.php';
}

foreach( $travel_booking_sections as $section ){
    require get_template_directory() . '/inc/customizer/sections/' . $section . '.php';
}

foreach( $travel_booking_sub_sections as $k => $v ){
    foreach( $v as $w ){        
        require get_template_directory() . '/inc/customizer/panels/' . $k . '/' . $w . '.php';
    }
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function travel_booking_customize_preview_js() {
	wp_enqueue_style( 'travel-booking-customizer', get_template_directory_uri() . '/inc/css/customizer.css', array(), TRAVEL_BOOKING_THEME_VERSION );
    wp_enqueue_script( 'travel-booking-customizer', get_template_directory_uri() . '/inc/js/customizer.js', array( 'customize-preview', 'customize-selective-refresh' ), TRAVEL_BOOKING_THEME_VERSION, true );
}
add_action( 'customize_preview_init', 'travel_booking_customize_preview_js' );

function travel_booking_customizer_scripts() {
    wp_enqueue_style( 'travel-booking-customize',get_template_directory_uri().'/inc/css/customize.css', array(), TRAVEL_BOOKING_THEME_VERSION, 'screen' );
    wp_enqueue_script( 'travel-booking-customize', get_template_directory_uri() . '/inc/js/customize.js', array( 'jquery', 'customize-controls' ), TRAVEL_BOOKING_THEME_VERSION, true );

    $array = array(
        'ajax_url'   => admin_url( 'admin-ajax.php' ),
    	'flushit'    => __( 'Successfully Flushed!', 'travel-booking' ),
    	'nonce'      => wp_create_nonce('ajax-nonce')
    );

    wp_localize_script( 'travel-booking-customize', 'travel_booking_cdata', $array );
}
add_action( 'customize_controls_enqueue_scripts', 'travel_booking_customizer_scripts' );

/*
 * Notifications in customizer
 */
require get_template_directory() . '/inc/customizer-plugin-recommend/customizer-notice/class-customizer-notice.php';

require get_template_directory() . '/inc/customizer-plugin-recommend/plugin-install/class-plugin-install-helper.php';

require get_template_directory() . '/inc/customizer-plugin-recommend/section-notice/class-section-notice.php';

$config_customizer = array(
    'recommended_plugins' => array( 
       'travel-booking-toolkit' => array(
            'recommended' => true,
            'description' => sprintf( 
                /* translators: %s: plugin name */
                esc_html__( 'If you want to take full advantage of the features this theme has to offer, please install and activate %s plugin.', 'travel-booking' ), '<strong>Travel Booking Toolkit</strong>' ),
        ),
    ),
    'recommended_plugins_title' => esc_html__( 'Recommended Plugin', 'travel-booking' ),
    'install_button_label'      => esc_html__( 'Install and Activate', 'travel-booking' ),
    'activate_button_label'     => esc_html__( 'Activate', 'travel-booking' ),
    'deactivate_button_label'   => esc_html__( 'Deactivate', 'travel-booking' ),
);
Travel_Booking_Customizer_Notice::init( apply_filters( 'travel_booking_customizer_notice_array', $config_customizer ) );

Travel_Booking_Customizer_Section::get_instance();