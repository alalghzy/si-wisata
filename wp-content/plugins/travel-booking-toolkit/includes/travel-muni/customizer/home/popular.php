<?php
/**
 * Popular Section
 *
 * @package Travel_Muni
 */

function travel_muni_customize_register_frontpage_popular( $wp_customize ){
    $obj  = new Travel_Booking_Toolkit_Functions;
    /** Popular Section */
    $wp_customize->add_section(
        'popular_section',
        array(
            'title'    => __( 'Popular Section', 'travel-booking-toolkit' ),
            'priority' => 50,
            'panel'    => 'frontpage_settings',
        )
    );

    $wp_customize->add_setting(
        'ed_popular',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'ed_popular',
            array(
                'type'        => 'checkbox',
                'section'     => 'popular_section',
                'label'       => __( 'Enable Section', 'travel-booking-toolkit' ),
            )
    );

    /** Popular title */
    $wp_customize->add_setting(
        'popular_title',
        array(
            'default'           => __( 'Popular Trips', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'popular_title', array(
        'selector'        => '.popular-trips .section-content-wrap .section-title',
        'render_callback' => 'travel_muni_get_popular_title',
    ) );
    
    $wp_customize->add_control(
        'popular_title',
        array(
            'section' => 'popular_section',
            'label'   => __( 'Section Title', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );

    /** popular desc */
    $wp_customize->add_setting(
        'popular_desc',
        array(
            'default'           => __( 'The origin of the word travel is most likely lost to history. The term travel may originate from the Old French word travail.', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
     $wp_customize->selective_refresh->add_partial( 'popular_desc', array(
        'selector'        => '.popular-trips .section-content-wrap.algnlft .section-desc p ',
        'render_callback' => 'travel_muni_get_popular_desc',
    ) );

    $wp_customize->add_control(
        'popular_desc',
        array(
            'section' => 'popular_section',
            'label'   => __( 'Section Description', 'travel-booking-toolkit' ),
            'type'    => 'textarea',
        )
    );
    
     /** Enable/Disable Popular Section */
    $wp_customize->add_setting(
        'ed_popular_demo',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'ed_popular_demo',
        array(
            'section'         => 'popular_section',
            'label'           => __( 'Enable Popular Demo Content', 'travel-booking-toolkit' ),
            'description'     => __( 'If there is no Popular trip selected, demo content will be displayed. Uncheck to hide demo content of this section.', 'travel-booking-toolkit' ),
            'type'            => 'checkbox',
        )   
    );


    if( $obj->travel_booking_toolkit_is_wpte_activated() ){
         $wp_customize->add_setting(
            'popular_trips',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_travel_muni_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            new Travel_Booking_Toolkit_Select_Control(
                $wp_customize,
                'popular_trips',
                array(
                    'label'           => __( 'Select Popular Trips', 'travel-booking-toolkit' ),
                    'section'         => 'popular_section',
                    'choices'         => travel_muni_get_posts( 'trip',false ),
                    'multiple'        => 12,
                )
            )
        );
    }else{
        if( class_exists( 'Travel_Booking_Toolkit_Plugin_Travel_Muni_Recommend_Control' ) ){
            $wp_customize->add_setting(
                'popular_i_note', array(
                    'sanitize_callback' => 'sanitize_text_field',
                 )
             );
        
            $wp_customize->add_control(
                new Travel_Booking_Toolkit_Plugin_Travel_Muni_Recommend_Control(
                    $wp_customize, 'popular_i_note', array(
                        'label'       => __( 'Instructions', 'travel-booking-toolkit' ),
                        'section'     => 'popular_section',
                        'capability'  => 'install_plugins',
                        'plugin_slug' => 'wp-travel-engine',
                        'description' => __( 'Please install the recommended plugin "WP Travel Engine" for setting of this section.', 'travel-booking-toolkit' )
                    )
                )
            );
        }
    }

    $wp_customize->add_setting(
        'popular_view_more_label',
        array(
            'default'           => __( 'View More Trips','travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'popular_view_more_label', array(
        'selector'        => '.popular-trips .loadmore-btn .load-more',
        'render_callback' => 'travel_muni_get_popular_view_more_label',
    ) );

    $wp_customize->add_control(
        'popular_view_more_label',
        array(
            'section' => 'popular_section',
            'label'   => __( 'Popular View More Label', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );
    
    $wp_customize->add_setting(
        'popular_view_more_link',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'popular_view_more_link',
        array(
            'section' => 'popular_section',
            'label'   => __( 'Popular View More Link', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );
    

}
add_action( 'customize_register', 'travel_muni_customize_register_frontpage_popular' );