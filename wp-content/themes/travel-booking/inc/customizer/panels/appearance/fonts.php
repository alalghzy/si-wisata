<?php
/**
 * Fonts Settings
 *
 * @package Travel_Booking
 */

if( ! function_exists( 'travel_booking_customize_register_fonts' ) ) : 
    function travel_booking_customize_register_fonts( $wp_customize ) {

        $wp_customize->add_section(
            'fonts_settings',
            array(
                'title'    => __( 'Fonts Settings', 'travel-booking' ),
                'panel'    => 'appearance_settings',
                'priority' => 90,
            )
        );

        $wp_customize->add_setting(
            'ed_localgoogle_fonts',
            array(
                'default'           => false,
                'sanitize_callback' => 'travel_booking_sanitize_checkbox',
            )
        );
        
        $wp_customize->add_control(
            'ed_localgoogle_fonts',
            array(
                'section'     => 'fonts_settings',
                'label'       => __( 'Load Google Fonts Locally', 'travel-booking' ),
                'description' => __( 'Enable to load google fonts from your own server instead from google\'s CDN. This solves privacy concerns with Google\'s CDN and their sometimes less-than-transparent policies.', 'travel-booking' ),
                'type'        => 'checkbox',
            )
        ); 

        $wp_customize->add_setting(
            'ed_preload_local_fonts',
            array(
                'default'           => false,
                'sanitize_callback' => 'travel_booking_sanitize_checkbox',
            )
        );
        
        $wp_customize->add_control(
    		'ed_preload_local_fonts',
    		array(
    			'section'         => 'fonts_settings',
    			'label'           => __( 'Preload Local Fonts', 'travel-booking' ),
    			'description'     => __( 'Preloading Google fonts will speed up your website speed.', 'travel-booking' ),
    			'type'            => 'checkbox',
    			'active_callback' => 'travel_booking_ed_localgoogle_fonts'
    		)		
    	);

        $wp_customize->add_setting(
            'flush_google_fonts',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses',
            )
        );
    
        $wp_customize->add_control(
            'flush_google_fonts',
            array(
                'label'       => __( 'Flush Local Fonts Cache', 'travel-booking' ),
                'description' => __( 'Click the button to reset the local fonts cache.', 'travel-booking' ),
                'type'        => 'button',
                'settings'    => array(),
                'section'     => 'fonts_settings',
                'input_attrs' => array(
                    'value' => __( 'Flush Local Fonts Cache', 'travel-booking' ),
                    'class' => 'button button-primary flush-it',
                ),
                'active_callback' => 'travel_booking_ed_localgoogle_fonts'
            )
        );
    }
endif;
add_action( 'customize_register', 'travel_booking_customize_register_fonts' );

/**
 * Active Callback for local fonts
*/
function travel_booking_ed_localgoogle_fonts( $control ){
    $ed_localgoogle_fonts = $control->manager->get_setting( 'ed_localgoogle_fonts' )->value();
    $control_id           = $control->id;
    
    if ( $control_id == 'flush_google_fonts' && $ed_localgoogle_fonts ) return true;
    if ( $control_id == 'ed_preload_local_fonts' && $ed_localgoogle_fonts ) return true;
    return false;
}