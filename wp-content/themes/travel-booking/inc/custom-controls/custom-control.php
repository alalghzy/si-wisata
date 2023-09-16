<?php
if( ! function_exists( 'travel_booking_register_custom_controls' ) ) :
/**
 * Register Custom Controls
*/
function travel_booking_register_custom_controls( $wp_customize ){
    
    // Load our custom control.
    require_once get_template_directory() . '/inc/custom-controls/note/class-note-control.php';
    require_once get_template_directory() . '/inc/custom-controls/radioimg/class-radio-image-control.php';
            
    // Register the control type.
    $wp_customize->register_control_type( 'Travel_Booking_Radio_Image_Control' );
}
endif;
add_action( 'customize_register', 'travel_booking_register_custom_controls' );