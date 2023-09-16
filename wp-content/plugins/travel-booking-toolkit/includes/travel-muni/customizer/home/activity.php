<?php
/**
 * Activity Section
 *
 * @package Travel_Muni
 */

function travel_muni_customize_register_frontpage_activities( $wp_customize ){
    $obj  = new Travel_Booking_Toolkit_Functions;
    /** Activity Section */
    $wp_customize->add_section(
        'activities_section',
        array(
            'title'    => __( 'Activity Section', 'travel-booking-toolkit' ),
            'priority' => 55,
            'panel'    => 'frontpage_settings',
        )
    );

    $wp_customize->add_setting(
        'ed_activities',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'ed_activities',
            array(
                'type'        => 'checkbox',
                'section'     => 'activities_section',
                'label'       => __( 'Enable Section', 'travel-booking-toolkit' ),
            )
    );

    /** Activity title */
    $wp_customize->add_setting(
        'activities_title',
        array(
            'default'           => __( 'Category', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'activities_title', array(
        'selector'        => '.activity-category .section-content-wrap .section-title',
        'render_callback' => 'travel_muni_get_activities_title',
    ) );
    
    $wp_customize->add_control(
        'activities_title',
        array(
            'section' => 'activities_section',
            'label'   => __( 'Section Title', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );

    /** Activity desc */
    $wp_customize->add_setting(
        'activities_desc',
        array(
            'default'           => __( 'The origin of the word travel is most likely lost to history. The term travel may originate from the Old French word travail.', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'activities_desc', array(
        'selector'        => '.activity-category .section-desc',
        'render_callback' => 'travel_muni_get_activities_desc',
    ) );
    

    $wp_customize->add_control(
        'activities_desc',
        array(
            'section' => 'activities_section',
            'label'   => __( 'Section Description', 'travel-booking-toolkit' ),
            'type'    => 'textarea',
        )
    );

    /** Enable/Disable Activities Section */
    $wp_customize->add_setting(
        'ed_activities_demo',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'ed_activities_demo',
        array(
            'section'         => 'activities_section',
            'label'           => __( 'Enable Activities Demo Content', 'travel-booking-toolkit' ),
            'description'     => __( 'If there is no Activity selected, demo content will be displayed. Uncheck to hide demo content of this section.', 'travel-booking-toolkit' ),
            'type'            => 'checkbox',
        )   
    );

    if( $obj->travel_booking_toolkit_is_wpte_activated() ){
        $wp_customize->add_setting(
            'top_activities',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_travel_muni_sanitize_select',
            )
        );
    
        $wp_customize->add_control(
            new Travel_Booking_Toolkit_Select_Control(
                $wp_customize,
                'top_activities',
                array(
                    'label'           => __( 'Select Activities', 'travel-booking-toolkit' ),
                    'section'         => 'activities_section',
                    'choices'         => travel_muni_get_categories( true,'activities',false ),
                    'multiple'        => 8,
                )
            )
        );
    }else{
        if( class_exists( 'Travel_Booking_Toolkit_Plugin_Travel_Muni_Recommend_Control' ) ){
            $wp_customize->add_setting(
                'activities_i_note', array(
                    'sanitize_callback' => 'sanitize_text_field',
                 )
             );
        
            $wp_customize->add_control(
                new Travel_Booking_Toolkit_Plugin_Travel_Muni_Recommend_Control(
                    $wp_customize, 'activities_i_note', array(
                        'label'       => __( 'Instructions', 'travel-booking-toolkit' ),
                        'section'     => 'activities_section',
                        'capability'  => 'install_plugins',
                        'plugin_slug' => 'wp-travel-engine',
                        'description' => __( 'Please install the recommended plugin "WP Travel Engine" for setting of this section.', 'travel-booking-toolkit' )
                    )
                )
            );
        }
    }

    

}
add_action( 'customize_register', 'travel_muni_customize_register_frontpage_activities' );