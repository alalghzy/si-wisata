<?php
/**
 * Banner Section
 *
 * @package Travel_Booking
 */

if ( ! function_exists( 'travel_booking_customize_register_banner_section' ) ) :
    
    /**
     * Add Banner section controls
     */
    function travel_booking_customize_register_banner_section( $wp_customize ) {
                                                                                    
        $wp_customize->get_section( 'header_image' )->panel    = 'home_page_setting';
        $wp_customize->get_section( 'header_image' )->title    = __( 'Banner Section', 'travel-booking' );
        $wp_customize->get_section( 'header_image' )->priority = 10;
        $wp_customize->remove_control( 'header_textcolor' );

        /** Enable/Disable Banner Section */
        $wp_customize->add_setting(
            'ed_banner_section',
            array(
                'default'           => true,
                'sanitize_callback' => 'travel_booking_sanitize_checkbox',
            )
        );
        
        $wp_customize->add_control(
    		'ed_banner_section',
    		array(
    			'section'  => 'header_image',
    			'label'	   => __( 'Enable Banner Section', 'travel-booking' ),
                'type'     => 'checkbox',
                'priority' => 5,
    		)		
    	);
        
        /** Title */
        $wp_customize->add_setting(
            'banner_title',
            array(
                'default'           => __( 'Book unique homes and experiences all over the world.', 'travel-booking' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'banner_title',
            array(
                'label'    => __( 'Title', 'travel-booking' ),
                'section'  => 'header_image',
                'type'     => 'text',
            )
        );
        
        $wp_customize->selective_refresh->add_partial( 'banner_title', array(
            'selector' => '.banner .banner-text h1.title',
            'render_callback' => 'travel_booking_get_banner_title',
        ) );
        
        /** Button Label */
        $wp_customize->add_setting(
            'banner_btn_label',
            array(
                'default'           => __( 'GET STARTED', 'travel-booking' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'banner_btn_label',
            array(
                'label'    => __( 'Button Label', 'travel-booking' ),
                'section'  => 'header_image',
                'type'     => 'text',
            )
        );
        
        $wp_customize->selective_refresh->add_partial( 'banner_btn_label', array(
            'selector' => '.banner .banner-text a.primary-btn',
            'render_callback' => 'travel_booking_get_banner_btn_label',
        ) );
        
        /** Button Url */
        $wp_customize->add_setting(
            'banner_btn_url',
            array(
                'default'           => __( '#', 'travel-booking' ),
                'sanitize_callback' => 'esc_url_raw',
            )
        );
        
        $wp_customize->add_control(
            'banner_btn_url',
            array(
                'label'    => __( 'Button Url', 'travel-booking' ),
                'section'  => 'header_image',
                'type'     => 'url',
            )
        );
            
    }
endif;
add_action( 'customize_register', 'travel_booking_customize_register_banner_section' );