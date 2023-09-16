<?php
/**
 * CTA Section
 *
 * @package Travel_Muni
 */

function travel_muni_customize_register_frontpage_cta( $wp_customize ){

    /** CTA Section */
    $wp_customize->add_section(
        'cta_section',
        array(
            'title'    => __( 'CTA Section', 'travel-booking-toolkit' ),
            'priority' => 65,
            'panel'    => 'frontpage_settings',
        )
    );

    $wp_customize->add_setting(
        'ed_cta',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox',
        )
    );

      $wp_customize->add_control(
        'ed_cta',
            array(
                'type'    => 'checkbox',
                'section' => 'cta_section',
                'label'   => __( 'Enable Section', 'travel-booking-toolkit' ),
            )
    );

    /** CTA title */
    $wp_customize->add_setting(
        'cta_title',
        array(
            'default'           => __( 'Why Book With Us', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'cta_title', array(
        'selector'        => '.cta-section .section-title',
        'render_callback' => 'travel_muni_get_cta_title',
    ) );

    $wp_customize->add_control(
        'cta_title',
        array(
            'section' => 'cta_section',
            'label'   => __( 'Section Title', 'travel-booking-toolkit' ),
            'type'    => 'text',
        )
    );

    /** Destination desc */
    $wp_customize->add_setting(
        'cta_desc',
        array(
            'default'           => __( 'Let your visitors know why they should trust you. You can modify this section from Appearance > Customize > Home Page Settings > Why Book with Us.', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->selective_refresh->add_partial( 'cta_desc', array(
        'selector'        => '.cta-section .section-desc',
        'render_callback' => 'travel_muni_get_cta_desc',
    ) );

    $wp_customize->add_control(
        'cta_desc',
        array(
            'section' => 'cta_section',
            'label'   => __( 'Section Description', 'travel-booking-toolkit' ),
            'type'    => 'textarea',
        )
    );

    /** Background Image */
    $wp_customize->add_setting(
        'cta_bg_image',
        array(
            'default'           => TBT_FILE_URL . '/images/cta_bg.jpg',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
       new WP_Customize_Image_Control(
           $wp_customize,
           'cta_bg_image',
           array(
               'label'   => __( 'Background Image', 'travel-booking-toolkit' ),
               'section' => 'cta_section'
           )
       )
    );

}
add_action( 'customize_register', 'travel_muni_customize_register_frontpage_cta' );