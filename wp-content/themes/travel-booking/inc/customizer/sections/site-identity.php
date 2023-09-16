<?php
/**
 * Site Identitiy
 *
 * @package Travel_Booking
 */

if ( ! function_exists( 'travel_booking_customize_register_site_identity_section' ) ) :
    /**
     * Add custom site identity controls
     */
    function travel_booking_customize_register_site_identity_section( $wp_customize ) {

        /** Add postMessage support for site title and description */
        $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
        $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    	
        // Selective refresh for blogname 
        $wp_customize->selective_refresh->add_partial( 'blogname', array(
            'selector'        => '.site-title a',
            'render_callback' => 'travel_booking_customize_partial_blogname',
        ) );

        // Selective refresh for blogdescription 
        $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
            'selector'        => '.site-description',
            'render_callback' => 'travel_booking_customize_partial_blogdescription',
        ) );
    }
endif;
add_action( 'customize_register', 'travel_booking_customize_register_site_identity_section' );