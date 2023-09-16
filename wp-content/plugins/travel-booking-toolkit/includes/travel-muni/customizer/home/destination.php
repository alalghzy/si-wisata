<?php
/**
 * Destination Section
 *
 * @package Travel_Muni
 */

function travel_muni_customize_register_frontpage_top_destination( $wp_customize ){
    $obj  = new Travel_Booking_Toolkit_Functions;
    /** Destination Section */
    $wp_customize->add_section(
        'destination_section',
        array(
            'title'    => __( 'Destination Section', 'travel-booking-toolkit' ),
            'priority' => 40,
            'panel'    => 'frontpage_settings',
        )
    );

    $wp_customize->add_setting(
        'ed_destination',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'ed_destination',
            array(
                'type'        => 'checkbox',
                'section'     => 'destination_section',
                'label'       => __( 'Enable Section', 'travel-booking-toolkit' ),
            )
    );

    /** Destination title */
    $wp_customize->add_setting(
        'destination_title',
        array(
            'default'           => __( 'Top Destinations', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'destination_title', array(
        'selector'        => '.top-destination .section-content-wrap .section-title',
        'render_callback' => 'travel_muni_get_destination_title',
    ) );

    $wp_customize->add_control(
        'destination_title',
        array(
            'section' => 'destination_section',
            'label'   => __( 'Section Title', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );

    /** Destination desc */
    $wp_customize->add_setting(
        'destination_desc',
        array(
            'default'           => __( 'For the Tours in Nepal, Trekking in Nepal, Holidays and Air Ticketing. We offer and we are committed to making your time in Nepal.', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'destination_desc', array(
        'selector'        => '.top-destination .section-content-wrap.algnlft .section-desc p',
        'render_callback' => 'travel_muni_get_destination_desc',
    ) );
    
    $wp_customize->add_control(
        'destination_desc',
        array(
            'section' => 'destination_section',
            'label'   => __( 'Section Description', 'travel-booking-toolkit' ),
            'type'    => 'textarea',
        )
    );

   
    
    /** Enable/Disable Popular Section */
    $wp_customize->add_setting(
        'ed_destination_demo',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'ed_destination_demo',
        array(
            'section'         => 'destination_section',
            'label'           => __( 'Enable Destination Demo Content', 'travel-booking-toolkit' ),
            'description'     => __( 'If there is no Destinations selected, demo content will be displayed. Uncheck to hide demo content of this section.', 'travel-booking-toolkit' ),
            'type'            => 'checkbox',
        )   
    );

    if( $obj->travel_booking_toolkit_is_wpte_activated() ){
          $wp_customize->add_setting(
            'top_destinations',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_travel_muni_sanitize_select',
            )
        );

        $wp_customize->add_control(
            new Travel_Booking_Toolkit_Select_Control(
                $wp_customize,
                'top_destinations',
                array(
                    'label'           => __( 'Select Top Destinations', 'travel-booking-toolkit' ),
                    'section'         => 'destination_section',
                    'choices'         => travel_muni_get_categories( true,'destination',false ),
                    'multiple'        => 6,
                )
            )
        );
    }else{
        if( class_exists( 'Travel_Booking_Toolkit_Plugin_Travel_Muni_Recommend_Control' ) ){
            $wp_customize->add_setting(
                'destination_i_note', array(
                    'sanitize_callback' => 'sanitize_text_field',
                 )
             );
        
            $wp_customize->add_control(
                new Travel_Booking_Toolkit_Plugin_Travel_Muni_Recommend_Control(
                    $wp_customize, 'destination_i_note', array(
                        'label'       => __( 'Instructions', 'travel-booking-toolkit' ),
                        'section'     => 'destination_section',
                        'capability'  => 'install_plugins',
                        'plugin_slug' => 'wp-travel-engine',
                        'description' => __( 'Please install the recommended plugin "WP Travel Engine" for setting of this section.', 'travel-booking-toolkit' )
                    )
                )
            );
        }
    }
  
    /** Destination title */
    $wp_customize->add_setting(
        'destination_more_label',
        array(
            'default'           => __( '28+ Top Destinations','travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'destination_more_label', array(
        'selector'        => '.top-destination .destination-wrap .desti-small-wrap .desti-single-wrap .last-desti-single-item h4',
        'render_callback' => 'travel_muni_get_destination_more_label',
    ) );

    $wp_customize->add_control(
        'destination_more_label',
        array(
            'section' => 'destination_section',
            'label'   => __( 'Top Destination Label', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'destination_view_more_label',
        array(
            'default'           => __( 'View All','travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'destination_view_more_label', array(
        'selector'        => '.top-destination .destination-wrap .desti-small-wrap .desti-single-wrap .last-desti-single-item .btn-book .btn-primary',
        'render_callback' => 'travel_muni_get_destination_view_more_label',
    ) );

    $wp_customize->add_control(
        'destination_view_more_label',
        array(
            'section' => 'destination_section',
            'label'   => __( 'Destination View More Label', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );
    
    $wp_customize->add_setting(
        'destination_view_more_link',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'destination_view_more_link',
        array(
            'section' => 'destination_section',
            'label'   => __( 'Destination View More Link', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );
    

}
add_action( 'customize_register', 'travel_muni_customize_register_frontpage_top_destination' );