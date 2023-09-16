<?php
/**
 * Intro Section
 *
 * @package Travel_Muni
 */
function travel_muni_customize_register_frontpage_intro_section( $wp_customize ){
    
    /** Intro Section */
    $wp_customize->add_section(
        'intro_section',
        array(
            'title'    => __( 'Intro Section', 'travel-booking-toolkit' ),
            'priority' => 30,
            'panel'    => 'frontpage_settings',
        )
    );

     $wp_customize->add_setting(
        'ed_intro',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'ed_intro',
            array(
                'type'        => 'checkbox',
                'section'     => 'intro_section',
                'label'       => __( 'Enable Section', 'travel-booking-toolkit' ),
            )
    );

    /** Intro title */
    $wp_customize->add_setting(
        'intro_title',
        array(
            'default'           => __( 'Create Your Travel Booking Website with Travel Muni Theme', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'intro_title', array(
        'selector'        => '.intro-section .section-title',
        'render_callback' => 'travel_muni_get_intro_title',
    ) );

    $wp_customize->add_control(
        'intro_title',
        array(
            'section' => 'intro_section',
            'label'   => __( 'Intro Title', 'travel-booking-toolkit' ),
            'type'    => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'intro_desc',
        array(
            'default'           => __( '<p>Tell a story about your company here. You can modify this section from Appearance > Customize > Home Page Settings > About Section.</p>
            <p>Travel Muni is a free WordPress theme that you can use create stunning and functional travel and tour booking website. It is lightweight, responsive and SEO friendly. It is compatible with WP Travel Engine, a WordPress plugin for travel booking. </p>', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'intro_desc', array(
        'selector'        => '.intro-section .intro-desc .intro-desc-inn-wrap',
        'render_callback' => 'travel_muni_get_intro_desc',
    ) );
  
    $wp_customize->add_control(
        new Travel_Booking_Toolkit_Customize_Editor_Control(
            $wp_customize, 
            'intro_desc', 
            array(
                'label'    => __( 'Section Description', 'travel-booking-toolkit' ),
                'section'  => 'intro_section',
            )
        )
    );

    /** Intro Readmore title */
    $wp_customize->add_setting(
        'intro_readmore',
        array(
            'default'           => __( 'Know More About Us', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'intro_readmore', array(
        'selector'        => '.intro-section .intro-desc .int-us-more',
        'render_callback' => 'travel_muni_get_intro_readmore',
    ) );

    $wp_customize->add_control(
        'intro_readmore',
        array(
            'section' => 'intro_section',
            'label'   => __( 'Readmore Label', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );

    /** Intro Readmore url */
    $wp_customize->add_setting(
        'intro_readmore_url',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'intro_readmore_url',
        array(
            'section' => 'intro_section',
            'label'   => __( 'Readmore Label URL', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'intro_trip_advisor',
        array(
            'default'           => '<img src="' . esc_url( TBT_FILE_URL.'/images/tripadvisor.jpg' ) . '"/>',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
  
    $wp_customize->add_control(
        new Travel_Booking_Toolkit_Customize_Editor_Control(
            $wp_customize, 
            'intro_trip_advisor', 
            array(
                'label'    => __( 'Trip Advisor Code', 'travel-booking-toolkit' ),
                'section'  => 'intro_section',
            )
        )
    );

}
add_action( 'customize_register', 'travel_muni_customize_register_frontpage_intro_section' );