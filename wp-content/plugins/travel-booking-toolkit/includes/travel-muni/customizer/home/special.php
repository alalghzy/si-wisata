<?php
/**
 * Special Offer Section
 *
 * @package Travel_Muni
 */

function travel_muni_customize_register_frontpage_special_offer( $wp_customize ){

    $obj  = new Travel_Booking_Toolkit_Functions;

    /** Special Offer Section */
    $wp_customize->add_section(
        'special_offer_section',
        array(
            'title'    => __( 'Special Offer Section', 'travel-booking-toolkit' ),
            'priority' => 60,
            'panel'    => 'frontpage_settings',
        )
    );

    $wp_customize->add_setting(
        'ed_special',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox',
        )
    );
    
      $wp_customize->add_control(
        'ed_special',
            array(
                'type'        => 'checkbox',
                'section'     => 'special_offer_section',
                'label'       => __( 'Enable Section', 'travel-booking-toolkit' ),
            )
    );

    /** Special Offer title */
    $wp_customize->add_setting(
        'special_offer_title',
        array(
            'default'           => __( 'Special Offers', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'special_offer_title', array(
        'selector'        => '.special-offer .section-content-wrap .section-title',
        'render_callback' => 'travel_muni_get_special_offer_title',
    ) );

    $wp_customize->add_control(
        'special_offer_title',
        array(
            'section' => 'special_offer_section',
            'label'   => __( 'Section Title', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );

    /** Destination desc */
    $wp_customize->add_setting(
        'special_offer_desc',
        array(
            'default'           => __( 'The origin of the word travel is most likely lost to history.', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'special_offer_desc', array(
        'selector'        => '.special-offer .section-desc p',
        'render_callback' => 'travel_muni_get_special_offer_desc',
    ) );
    
    $wp_customize->add_control(
        'special_offer_desc',
        array(
            'section' => 'special_offer_section',
            'label'   => __( 'Section Description', 'travel-booking-toolkit' ),
            'type'    => 'textarea',
        )
    );
    
    /** Enable/Disable Popular Section */
     $wp_customize->add_setting(
        'ed_special_offer_demo',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'ed_special_offer_demo',
        array(
            'section'         => 'special_offer_section',
            'label'           => __( 'Enable Special Offer Demo Content', 'travel-booking-toolkit' ),
            'description'     => __( 'If there is no Special Offer selected, demo content will be displayed. Uncheck to hide demo content of this section.', 'travel-booking-toolkit' ),
            'type'            => 'checkbox',
        )   
    );

    if( $obj->travel_booking_toolkit_is_wpte_activated() ){
          $wp_customize->add_setting(
            'special_offer_trips',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_travel_muni_sanitize_select',
            )
        );

        $wp_customize->add_control(
            new Travel_Booking_Toolkit_Select_Control(
                $wp_customize,
                'special_offer_trips',
                array(
                    'label'           => __( 'Select Special Offers', 'travel-booking-toolkit' ),
                    'section'         => 'special_offer_section',
                    'choices'         => travel_muni_get_posts( 'trip',false ),
                    'multiple'        => 12,
                )
            )
        );
    }else{
        if( class_exists( 'Travel_Booking_Toolkit_Plugin_Travel_Muni_Recommend_Control' ) ){
            $wp_customize->add_setting(
                'special_i_note', array(
                    'sanitize_callback' => 'sanitize_text_field',
                 )
             );
        
            $wp_customize->add_control(
                new Travel_Booking_Toolkit_Plugin_Travel_Muni_Recommend_Control(
                    $wp_customize, 'special_i_note', array(
                        'label'       => __( 'Instructions', 'travel-booking-toolkit' ),
                        'section'     => 'special_offer_section',
                        'capability'  => 'install_plugins',
                        'plugin_slug' => 'wp-travel-engine',
                        'description' => __( 'Please install the recommended plugin "WP Travel Engine" for setting of this section.', 'travel-booking-toolkit' )
                    )
                )
            );
        }
    }


}
add_action( 'customize_register', 'travel_muni_customize_register_frontpage_special_offer' );