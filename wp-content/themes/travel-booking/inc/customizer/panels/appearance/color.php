<?php
/**
 * Color Settings
 *
 * @package Travel_Booking_Pro
 */

if( ! function_exists( 'travel_booking_customize_register_color_scheme' ) ) : 

    /**
     * Color Scheme
     */
    function travel_booking_customize_register_color_scheme( $wp_customize ) {
                                                                                  
        // Move default color section to appearance panel
        $wp_customize->get_section( 'colors' )->panel           = 'appearance_settings';
        $wp_customize->get_section( 'background_image' )->panel = 'appearance_settings';
    }
endif;
add_action( 'customize_register', 'travel_booking_customize_register_color_scheme' );