<?php
/**
 * Testimonial Section
 *
 * @package Travel_Muni
 */

function travel_muni_customize_register_frontpage_clients_testimonial( $wp_customize ){
    $obj  = new Travel_Booking_Toolkit_Functions;
    /** Testimonial Section */
    $wp_customize->add_section(
        'testimonial_section',
        array(
            'title'    => __( 'Testimonial Section', 'travel-booking-toolkit' ),
            'priority' => 45,
            'panel'    => 'frontpage_settings',
        )
    );

    $wp_customize->add_setting(
        'ed_testimonial',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'ed_testimonial',
            array(
                'type'        => 'checkbox',
                'section'     => 'testimonial_section',
                'label'       => __( 'Enable Section', 'travel-booking-toolkit' ),
            )
    );

    /** Testimonial title */
    $wp_customize->add_setting(
        'testimonial_title',
        array(
            'default'           => __( 'Clients Testimonials', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'testimonial_title', array(
        'selector'        => '.clients-testimonial .section-content-wrap .section-title',
        'render_callback' => 'travel_muni_get_testimonial_title',
    ) );
    
    $wp_customize->add_control(
        'testimonial_title',
        array(
            'section' => 'testimonial_section',
            'label'   => __( 'Section Title', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );

    /** Testimonial desc */
    $wp_customize->add_setting(
        'testimonial_desc',
        array(
            'default'           => __( 'The origin of the word travel is most likely lost to history. The term travel may originate from the old french word travail.', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'testimonial_desc',
        array(
            'section' => 'testimonial_section',
            'label'   => __( 'Section Desc', 'travel-booking-toolkit' ),
            'type'    => 'textarea',
        )
    );
    
    /** Enable/Disable Popular Section */
    $wp_customize->add_setting(
        'ed_testimonial_demo',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'ed_testimonial_demo',
        array(
            'section'         => 'testimonial_section',
            'label'           => __( 'Enable Testimonial Demo Content', 'travel-booking-toolkit' ),
            'description'     => __( 'If there is no Testimonials selected, demo content will be displayed. Uncheck to hide demo content of this section.', 'travel-booking-toolkit' ),
            'type'            => 'checkbox',
        )   
    );

    if( $obj->travel_booking_is_wpte_tr_activated() ){

        //Review One
        $wp_customize->add_setting(
            'testimonial_review_one',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_travel_muni_sanitize_select',
            )
        );

        $wp_customize->add_control(
            new Travel_Booking_Toolkit_Select_Control(
                $wp_customize,
                'testimonial_review_one',
                array(
                    'label'           => __( 'Review One', 'travel-booking-toolkit' ),
                    'section'         => 'testimonial_section',
                    'choices'         => travel_muni_get_trip_review_comment(),
                )
            )
        );

        //Review Two
        $wp_customize->add_setting(
            'testimonial_review_two',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_travel_muni_sanitize_select',
            )
        );

        $wp_customize->add_control(
            new Travel_Booking_Toolkit_Select_Control(
                $wp_customize,
                'testimonial_review_two',
                array(
                    'label'           => __( 'Review Two', 'travel-booking-toolkit' ),
                    'section'         => 'testimonial_section',
                    'choices'         => travel_muni_get_trip_review_comment(),
                )
            )
        );

        //Review Three
        $wp_customize->add_setting(
            'testimonial_review_three',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_travel_muni_sanitize_select',
            )
        );

        $wp_customize->add_control(
            new Travel_Booking_Toolkit_Select_Control(
                $wp_customize,
                'testimonial_review_three',
                array(
                    'label'           => __( 'Review Three', 'travel-booking-toolkit' ),
                    'section'         => 'testimonial_section',
                    'choices'         => travel_muni_get_trip_review_comment(),
                )
            )
        );
    }

    /** Testimonial Button Label */
    $wp_customize->add_setting(
        'testimonial_section_btn_label',
        array(
            'default'           => __( 'Read More Reviews', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'testimonial_section_btn_label', array(
        'selector'        => '.clients-testimonial .loadmore-btn .load-more',
        'render_callback' => 'travel_muni_get_testimonial_section_btn_label',
    ) );
    
    $wp_customize->add_control(
        'testimonial_section_btn_label',
        array(
            'section' => 'testimonial_section',
            'label'   => __( 'Read More Label', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );

    /** Testimonial Button URL */
    $wp_customize->add_setting(
        'testimonial_section_btn_url',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'testimonial_section_btn_url',
        array(
            'section' => 'testimonial_section',
            'label'   => __( 'Testimonial Button URL', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );

    
}
add_action( 'customize_register', 'travel_muni_customize_register_frontpage_clients_testimonial' );