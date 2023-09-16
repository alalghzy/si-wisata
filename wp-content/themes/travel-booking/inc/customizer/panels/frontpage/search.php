<?php
/**
 * Search Section
 *
 * @package Travel_Booking
 */

if ( ! function_exists( 'travel_booking_customize_register_search_section' ) ) :
 /**
 * Add search section controls
 */
function travel_booking_customize_register_search_section( $wp_customize ) {
    $wp_customize->add_section( 'search_section', 
    array(
        'title'    => __( 'Search Section', 'travel-booking' ),
        'priority' => 11,
        'panel'    => 'home_page_setting',
    ) ); 

    /** Enable Search Bar */
    $wp_customize->add_setting(
        'ed_search_bar',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_booking_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'ed_search_bar',
        array(
            'section'         => 'search_section',
            'label'           => __( 'Enable Search Bar', 'travel-booking' ),
            'type'            => 'checkbox',
            'active_callback' => 'travel_booking_is_wte_advanced_search_active'
        )
    );
}
endif;    
add_action( 'customize_register', 'travel_booking_customize_register_search_section' );