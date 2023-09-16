<?php
/**
 * Appearance Options 
 *
 * @package Travel_Booking
 */

if( ! function_exists( 'travel_booking_customize_register_appearance_panel' ) ) :

	/**
	 * Appearance panel
	 */
	function travel_booking_customize_register_appearance_panel( $wp_customize ) {
	    
	    $wp_customize->add_panel( 'appearance_settings', array(
	        'title'          => __( 'Appearance Settings', 'travel-booking' ),
	        'priority'       => 25,
	        'capability'     => 'edit_theme_options',
	    ) );
	    
	}
endif;
add_action( 'customize_register', 'travel_booking_customize_register_appearance_panel' );                                                         