<?php
/**
 * Customizer settings specific to Travel Muni theme.
 * 
 * @package Travel_Booking_Toolkit
 */
$travel_muni_sub_sections = array(
    'home'       => array( 'intro','destination' ),
    // 'home'       => array( 'intro','destination','testimonials','popular','activity','special','cta','recommendation' ),
);

require TBT_BASE_PATH. '/includes/travel-muni/customizer/home/intro.php';
require TBT_BASE_PATH. '/includes/travel-muni/customizer/home/destination.php';
require TBT_BASE_PATH. '/includes/travel-muni/customizer/home/activity.php';
require TBT_BASE_PATH. '/includes/travel-muni/customizer/home/cta.php';
require TBT_BASE_PATH. '/includes/travel-muni/customizer/home/popular.php';
require TBT_BASE_PATH. '/includes/travel-muni/customizer/home/recommendation.php';
require TBT_BASE_PATH. '/includes/travel-muni/customizer/home/special.php';
require TBT_BASE_PATH. '/includes/travel-muni/customizer/home/testimonials.php';

function travel_booking_toolkit_travel_muni_header_options( $wp_customize ){

    /** Phone */
    $wp_customize->add_setting(
        'phone',
        array(
            'default'           => __( '(000) 999-656-888', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field', 
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'phone', array(
        'selector'        => 'body .header-layout-5 .header-m .header-m-mid-wrap .contact-wrap-head .contact-phone-wrap .head-5-dtls',
        'render_callback' => 'travel_muni_get_phone',
    ) );
    
    $wp_customize->add_control(
        'phone',
        array(
            'type'    => 'text',
            'section' => 'header_settings',
            'label'   => __( 'Phone', 'travel-booking-toolkit' ),
        )
    );

    /** Phone Label*/
    $wp_customize->add_setting(
        'phone_label',
        array(
            'default'           => __( 'Talk to an Expert (David)', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field', 
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'phone_label', array(
        'selector'        => '.header-layout-5 .header-m .header-m-mid-wrap .contact-wrap-head .head5-titl',
        'render_callback' => 'travel_muni_phone_label',
    ) );
    
    $wp_customize->add_control(
        'phone_label',
        array(
            'type'            => 'text',
            'section'         => 'header_settings',
            'label'           => __( 'Phone Label', 'travel-booking-toolkit' ),
            'description'     => __( 'This works only with the Header 5 Layout', 'travel-booking-toolkit' ),
            'active_callback' => 'travel_booking_toolkit_is_header_five_activated'
        )
    );
    
    /** Email */
    $wp_customize->add_setting(
        'email',
        array(
            'default'           => __( 'contact@travelmuni.com', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_email',
            // 'transport'         => 'postMessage' 
        )
    );

    $wp_customize->selective_refresh->add_partial( 'email', array(
        'selector'        => '.header-t .header-t-rght-wrap .contact-email-wrap',
        'render_callback' => 'travel_muni_get_email',
    ) );
    
    $wp_customize->add_control(
        'email',
        array(
            'type'    => 'text',
            'section' => 'header_settings',
            'label'   => __( 'Email', 'travel-booking-toolkit' ),
        )
    );

    /** Email Label */
    $wp_customize->add_setting(
        'header_email_label',
        array(
            'default'           => __( 'Quick Questions? Email Us', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );

    $wp_customize->selective_refresh->add_partial( 'header_email_label', array(
        'selector'        => 'body .header-layout-5 .header-m .header-m-mid-wrap .contact-wrap-head .eml-lbl',
        'render_callback' => 'travel_muni_get_header_email_label',
    ) );
    
    $wp_customize->add_control(
        'header_email_label',
        array(
            'type'            => 'text',
            'section'         => 'header_settings',
            'label'           => __( 'Email Label', 'travel-booking-toolkit' ),
            'description'     => __( 'This works only with the Header 5 Layout', 'travel-booking-toolkit' ),
            'active_callback' => 'travel_booking_toolkit_is_header_five_activated'
        )
    );

    /** Vip Image */
    $wp_customize->add_setting(
        'header_vip_image',
        array(
            'default'           => TBT_FILE_URL . '/images/header-5-vibpp.jpg',
            'sanitize_callback' => 'travel_muni_sanitize_image',
        )
    );
    $wp_customize->add_control(
       new WP_Customize_Image_Control(
           $wp_customize,
           'header_vip_image',
           array(
               'label'           => __( 'Upload Vip Image', 'travel-booking-toolkit' ),
               'section'         => 'header_settings',
               'active_callback' => 'travel_booking_toolkit_is_header_five_activated'
           )
       )
   );
    
    /** Customizer Trip Label */
    $wp_customize->add_setting(
        'customize_button',
        array(
            'default'           => __( 'Customize The Trip', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );

    $wp_customize->selective_refresh->add_partial( 'customize_button', array(
        'selector'        => '.header-m .btn-book .btn-primary',
        'render_callback' => 'travel_muni_get_customize_button',
    ) );
    
    $wp_customize->add_control(
        'customize_button',
        array(
            'type'    => 'text',
            'section' => 'header_settings',
            'label'   => __( 'Customizer The Trip Label', 'travel-booking-toolkit' ),
        )
    );

    /** Customizer Trip Button URL */
    $wp_customize->add_setting(
        'customize_button_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw' 
        )
    );
    
    $wp_customize->add_control(
        'customize_button_url',
        array(
            'type'    => 'text',
            'section' => 'header_settings',
            'label'   => __( 'Customizer The Trip URL', 'travel-booking-toolkit' ),
        )
    );
}
add_action( 'customize_register', 'travel_booking_toolkit_travel_muni_header_options' );


function travel_booking_toolkit_footer_options( $wp_customize  ){
    $wp_customize->add_section(
        'footer_bottom_settings',
        array(
            'title'      => __( 'Footer Bottom Settings', 'travel-booking-toolkit' ),
            'panel'      => 'footer_settings',
        )
    );

    /** Footer Copyright */
    $wp_customize->add_setting(
        'footer_bottom_textarea_left',
        array(
            'default'           => __( 'Travel Muni is a user-friendly, easy-to-use, and powerful travel booking WordPress theme. You can create a professional and SEO-friendly travel website using it.', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'footer_bottom_textarea_left',
        array(
            'label'       => __( 'Enter some description here', 'travel-booking-toolkit' ),
            'section'     => 'footer_bottom_settings',
            'type'        => 'textarea',
        )
    );

    /** Footer Phone */
    $wp_customize->add_setting(
        'footer_phone_label',
        array(
            'default'           => __( 'Call us on ...','travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->selective_refresh->add_partial( 'footer_phone_label', array(
        'selector'        => '.site-footer .footer-m .col:nth-child(2) .widget-title',
        'render_callback' => 'travel_muni_get_footer_phone_label',
    ) );
    
    $wp_customize->add_control(
        'footer_phone_label',
        array(
            'label'   => __( 'Enter Label For the Phones', 'travel-booking-toolkit' ),
            'section' => 'footer_bottom_settings',
            'type'    => 'text',
        )
    );

    /** Footer Phone */
    $wp_customize->add_setting(
        'footer_phone',
        array(
            'default'           => __( '+1 014701573', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'footer_phone',
        array(
            'label'   => __( 'Enter Phone', 'travel-booking-toolkit' ),
            'section' => 'footer_bottom_settings',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'footer_whatsapp',
        array(
            'default'           => __( '+1 9990605892', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'footer_whatsapp',
        array(
            'label'   => __( 'Enter Whatsapp Phone Number', 'travel-booking-toolkit' ),
            'section' => 'footer_bottom_settings',
            'type'    => 'text',
        )
    );
     
    $wp_customize->add_setting(
        'footer_viber',
        array(
            'default'           => __( '+1 999001573', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'footer_viber',
        array(
            'label'   => __( 'Enter Viber Phone Number', 'travel-booking-toolkit' ),
            'section' => 'footer_bottom_settings',
            'type'    => 'text',
        )
    );

    /* Email Label */

    $wp_customize->add_setting(
        'footer_email_label',
        array(
            'default'           => __( 'Write us at...','travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'footer_email_label', array(
        'selector'        => '.site-footer .footer-m .col:nth-child(3) .widget-title',
        'render_callback' => 'travel_muni_get_footer_email_label',
    ) );

    $wp_customize->add_control(
        'footer_email_label',
        array(
            'label'   => __( 'Enter Label For the Email', 'travel-booking-toolkit' ),
            'section' => 'footer_bottom_settings',
            'type'    => 'text',
        )
    );

     $wp_customize->add_setting(
        'footer_email',
        array(
            'default'           => __( 'contact@yourdomain.com,sales@example.com', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'footer_email',
        array(
            'label'       => __( 'Enter Email', 'travel-booking-toolkit' ),
            'description' => __( 'You can enter multiple email seperating each by comma', 'travel-booking-toolkit' ),
            'section'     => 'footer_bottom_settings',
            'type'        => 'textarea',
        )
    );

    /*Copyright Section*/

    /** Payments Label */
    $wp_customize->add_setting(
        'payments_label',
        array(
            'default'           => __( 'Payments:','travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'payments_label',
        array(
            'label'       => __( 'Payments Label', 'travel-booking-toolkit' ),
            'section'     => 'copyright_settings',
            'type'        => 'text',
        )
    );

    /** Payments Image */
    $wp_customize->add_setting(
        'payments_image',
        array(
            'default'           => TBT_FILE_URL .'/images/payment-gateway.png',
            'sanitize_callback' => 'travel_muni_sanitize_image',
        )
    );
    
    $wp_customize->add_control(
       new WP_Customize_Image_Control(
           $wp_customize,
           'payments_image',
           array(
               'label'   => __( 'Payments Image', 'travel-booking-toolkit' ),
               'section' => 'copyright_settings',
           )
       )
    );
}
add_action( 'customize_register', 'travel_booking_toolkit_footer_options' );

/**
 * Social Settings
 */

function travel_booking_toolkit_customize_register_general_social( $wp_customize ) {
    
    /** Social Media Settings */
    $wp_customize->add_section(
        'social_media_settings',
        array(
            'title'    => __( 'Social Media Settings', 'travel-booking-toolkit' ),
            'priority' => 40,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_social_links', 
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_muni_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        'ed_social_links',
            array(
                'type'        => 'checkbox',
                'section'     => 'social_media_settings',
                'label'       => __( 'Enable Social Links', 'travel-booking-toolkit' ),
                'description' => __( 'Enable to show social links at header.', 'travel-booking-toolkit' ),
            )
    );
    
    $wp_customize->add_setting( 
        new Travel_Booking_Toolkit_Repeater_Setting( 
            $wp_customize, 
            'social_links', 
            array(
                'default' => array(),
                'sanitize_callback' => array( 'Travel_Booking_Toolkit_Repeater_Setting', 'sanitize_repeater_setting' ),
            ) 
        ) 
    );
    
    $wp_customize->add_control(
        new Travel_Booking_Toolkit_Control_Repeater(
            $wp_customize,
            'social_links',
            array(
                'section' => 'social_media_settings',               
                'label'   => __( 'Social Links', 'travel-booking-toolkit' ),
                'fields'  => array(
                    'font' => array(
                        'type'        => 'font',
                        'label'       => __( 'Font Awesome Icon', 'travel-booking-toolkit' ),
                        'description' => __( 'Example: fab fa-facebook-f', 'travel-booking-toolkit' ),
                    ),
                    'link' => array(
                        'type'        => 'url',
                        'label'       => __( 'Link', 'travel-booking-toolkit' ),
                        'description' => __( 'Example: https://facebook.com', 'travel-booking-toolkit' ),
                    )
                ),
                'row_label' => array(
                    'type' => 'field',
                    'value' => __( 'links', 'travel-booking-toolkit' ),
                    'field' => 'link'
                )                        
            )
        )
    );
    /** Social Media Settings Ends */
    
}
add_action( 'customize_register', 'travel_booking_toolkit_customize_register_general_social' );

function travel_booking_toolkit_travel_muni_sanitize_select( $value ){    
    if ( is_array( $value ) ) {
        foreach ( $value as $key => $subvalue ) {
            $value[ $key ] = sanitize_text_field( $subvalue );
        }
        return $value;
    }
    return sanitize_text_field( $value );    
}