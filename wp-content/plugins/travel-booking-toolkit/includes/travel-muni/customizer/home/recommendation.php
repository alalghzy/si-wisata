<?php
/**
 * Recommendation Section
 *
 * @package Travel_Muni
 */

function travel_muni_customize_register_frontpage_recommendation( $wp_customize ){

    /** Recommendation Section */
    $wp_customize->add_section(
        'recommendation_section',
        array(
            'title'    => __( 'Recommendation & Associated Section', 'travel-booking-toolkit' ),
            'priority' => 90,
            'panel'    => 'frontpage_settings',
        )
    );

    $wp_customize->add_setting(
        'ed_recommendation',
        array(
            'default'           => false,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox',
        )
    );
    
      $wp_customize->add_control(
        'ed_recommendation',
            array(
                 'type'    => 'checkbox',
                 'section' => 'recommendation_section',
                 'label'   => __( 'Enable Recommended Section', 'travel-booking-toolkit' ),
            )
    );

    
    /** Recommendation title */
    $wp_customize->add_setting(
        'recommendation_section_title',
        array(
            'default'           => __( 'Were recommended by', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'recommendation_section_title', array(
        'selector'        => '.recommended-by .all-clients-main-wrap .section-content-wrap .section-title',
        'render_callback' => 'travel_muni_get_recommendation_section_title',
    ) );
    
    $wp_customize->add_control(
        'recommendation_section_title',
        array(
            'section' => 'recommendation_section',
            'label'   => __( 'Recommendation Title', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );

    /** Recommendation description */
    $wp_customize->add_setting(
        'recommendation_desc',
        array(
            'default'           => __( 'Travel by water often provided more comfort and speed than land-travel.', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'recommendation_desc', array(
        'selector'        => '.recommended-by .all-clients-main-wrap .section-content-wrap .section-desc',
        'render_callback' => 'travel_muni_get_recommendation_desc',
    ) );
    
    $wp_customize->add_control(
        'recommendation_desc',
        array(
            'section' => 'recommendation_section',
            'label'   => __( 'Recommendation Description', 'travel-booking-toolkit' ),
            'type'    => 'textarea',
        )
    ); 

    /** Recommendation Repeater **/
    $wp_customize->add_setting( 
        new Travel_Booking_Toolkit_Repeater_Setting( 
            $wp_customize, 
            'recommendation_repeater', 
            array(
                'default'           => '',
                'sanitize_callback' => array( 'Travel_Booking_Toolkit_Repeater_Setting', 'sanitize_repeater_setting' ),
            ) 
        ) 
    );
    
    $wp_customize->add_control(
        new Travel_Booking_Toolkit_Control_Repeater(
            $wp_customize,
            'recommendation_repeater',
            array(
                'section' => 'recommendation_section',                
                'label'   => __( 'Recommendation Repeater', 'travel-booking-toolkit' ),
                'fields'  => array(
                    'image' => array(
                        'type'    => 'image',
                        'label'   => __( 'Choose Image', 'travel-booking-toolkit' ),
                    ),
                    'link' => array(
                        'type'    => 'text',
                        'label'   => __( 'Link for Image', 'travel-booking-toolkit' ),
                    )
                ),
                'row_label' => array(
                    'type'  => 'field',
                    'value' => __( 'Recommendation', 'travel-booking-toolkit' ),
                    'field' => 'recommendation'
                ),
            )
        )
    );
    /** Recommendation Section Ends */  
    $wp_customize->add_setting(
        'ed_associated',
        array(
            'default'           => false,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox',
        )
    );
        
    $wp_customize->add_control(
        'ed_associated',
            array(
                 'type'    => 'checkbox',
                 'section' => 'recommendation_section',
                 'label'   => __( 'Enable Associated Section', 'travel-booking-toolkit' ),
            )
    );

    /** Associated title */
    $wp_customize->add_setting(
        'associated_section_title',
        array(
            'default'           => __( 'Were associated with', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'associated_section_title', array(
        'selector'        => '.associated-with .all-clients-main-wrap .section-content-wrap .section-title',
        'render_callback' => 'travel_muni_get_associated_section_title',
    ) );
    
    $wp_customize->add_control(
        'associated_section_title',
        array(
            'section' => 'recommendation_section',
            'label'   => __( 'Associated Title', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );

    /** Associated description */
    $wp_customize->add_setting(
        'associated_desc',
        array(
            'default'           => __( 'The origin of the word travel is most likely lost to history.', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'associated_desc', array(
        'selector'        => '.associated-with .all-clients-main-wrap .section-content-wrap .section-desc',
        'render_callback' => 'travel_muni_get_associated_desc',
    ) );
    

    $wp_customize->add_control(
        'associated_desc',
        array(
            'section' => 'recommendation_section',
            'label'   => __( 'Associated Description', 'travel-booking-toolkit' ),
            'type'    => 'textarea',
        )
    ); 

    /** Associated Repeater **/
    $wp_customize->add_setting( 
        new Travel_Booking_Toolkit_Repeater_Setting( 
            $wp_customize, 
            'associated_repeater', 
            array(
                'default'           => '',
                'sanitize_callback' => array( 'Travel_Booking_Toolkit_Repeater_Setting', 'sanitize_repeater_setting' ),
            ) 
        ) 
    );
    
    $wp_customize->add_control(
        new Travel_Booking_Toolkit_Control_Repeater(
            $wp_customize,
            'associated_repeater',
            array(
                'section' => 'recommendation_section',                
                'label'   => __( 'Associated Repeater', 'travel-booking-toolkit' ),
                'fields'  => array(
                    'image' => array(
                        'type'    => 'image',
                        'label'   => __( 'Choose Image', 'travel-booking-toolkit' ),
                    ),
                    'link' => array(
                        'type'    => 'text',
                        'label'   => __( 'Link for Image', 'travel-booking-toolkit' ),
                    )
                ),
                'row_label' => array(
                    'type'  => 'field',
                    'value' => __( 'Associated', 'travel-booking-toolkit' ),
                    'field' => 'associated'
                ),
            )
        )
    );

    /** Associated Section Ends */  

}
add_action( 'customize_register', 'travel_muni_customize_register_frontpage_recommendation' );