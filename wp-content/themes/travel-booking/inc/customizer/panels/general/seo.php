<?php
/**
 * SEO Section
 *
 * @package Travel_Booking
 */

if ( ! function_exists( 'travel_booking_customize_register_seo_section' ) ) :

    /** 
     * SEO Section 
     */
    function travel_booking_customize_register_seo_section( $wp_customize ) {
    
        /** SEO Settings */
        $wp_customize->add_section(
            'seo_settings',
            array(
                'title'    => __( 'SEO Settings', 'travel-booking' ),
                'priority' => 25,
                'panel'    => 'general_settings',
            )
        );

        /** Enable updated date */
        $wp_customize->add_setting( 
            'ed_post_update_date', 
            array(
                'default'           => true,
                'sanitize_callback' => 'travel_booking_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
            'ed_post_update_date',
            array(
                'section' => 'seo_settings',
                'label'   => __( 'Enable Last Update Post Date', 'travel-booking' ),
                'type'    => 'checkbox'
            )
        );

        /** Enable/Disable BreadCrumb */
        $wp_customize->add_setting(
            'ed_breadcrumb',
            array(
                'default'           => true,
                'sanitize_callback' => 'travel_booking_sanitize_checkbox',
            )
        );
        
        $wp_customize->add_control(
    		'ed_breadcrumb',
    		array(
    			'section'	  => 'seo_settings',
    			'label'		  => __( 'Enable Breadcrumb', 'travel-booking' ),
                'type'        => 'checkbox'
    		)		
    	);
        
        /** Home Text */
        $wp_customize->add_setting(
            'breadcrumb_home_text',
            array(
                'default'           => __( 'Home', 'travel-booking' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );
        
        $wp_customize->add_control(
            'breadcrumb_home_text',
            array(
                'label'   => __( 'Breadcrumb Home Text', 'travel-booking' ),
                'section' => 'seo_settings',
                'type'    => 'text',
            )
        );

    }
endif;
add_action( 'customize_register', 'travel_booking_customize_register_seo_section' );