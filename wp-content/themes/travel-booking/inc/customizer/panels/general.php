<?php
/**
 * General Setting Panel
 *
 * @package Travel Booking
 */
if ( ! function_exists( 'travel_booking_customize_register_general_settings_panel' ) ) :
	/**
	 * Add general settings panel
	 */
	function travel_booking_customize_register_general_settings_panel( $wp_customize ) {

		$wp_customize->add_panel( 'general_settings', array(
		    'title'      => __( 'General Settings', 'travel-booking' ),
		    'priority'   => 40,
		    'capability' => 'edit_theme_options',
		) );
	}
endif;
add_action( 'customize_register', 'travel_booking_customize_register_general_settings_panel' );