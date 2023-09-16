<?php
/**
 * Home Page Panel
 *
 * @package Travel_Booking
 */

if ( ! function_exists( 'travel_booking_customize_register_frontpage_panel' ) ) :
	/**
	 * Add frontpage panel
	 */
	function travel_booking_customize_register_frontpage_panel( $wp_customize ) {

	$wp_customize->add_panel( 'home_page_setting', array(
        'title'      => __( 'Front Page Settings', 'travel-booking' ),
        'priority'   => 40,
        'capability' => 'edit_theme_options',
    ) );
}
endif;
add_action( 'customize_register', 'travel_booking_customize_register_frontpage_panel' );