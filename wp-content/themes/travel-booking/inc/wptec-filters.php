<?php
/**
 * Filter to modify functionality of WPTEC plugin.
 *
 * @package Travel_Booking
 */

if( ! function_exists( 'travel_booking_popular_package_image_size' ) ){
	/**
	 * Filter to define image size of popular packages section
	 */    
	function travel_booking_popular_package_image_size(){
		return 'travel-booking-popular-package';
	}
}
add_filter( 'tbt_popular_package_image_size', 'travel_booking_popular_package_image_size' );

if( ! function_exists( 'travel_booking_featured_trip_image_size' ) ){
	/**
	 * Filter to define image size of featured trip section
	 */    
	function travel_booking_featured_trip_image_size(){
		return 'travel-booking-popular-package';
	}
}
add_filter( 'tbt_featured_trip_image_size', 'travel_booking_featured_trip_image_size' );

if( ! function_exists( 'travel_booking_deals_discount_image_size' ) ){
	/**
	 * Filter to define image size of deals and discount section
	 */    
	function travel_booking_deals_discount_image_size(){
		return 'travel-booking-deals-discount';
	}
}
add_filter( 'tbt_deals_discount_image_size', 'travel_booking_deals_discount_image_size' );

if( ! function_exists( 'travel_booking_activities_image_size' ) ){
	/**
	 * Filter to define image size of activities section
	 */    
	function travel_booking_activities_image_size(){
		return 'travel-booking-destination';
	}
}
add_filter( 'tbt_activities_image_size', 'travel_booking_activities_image_size' );

if( ! function_exists( 'travel_booking_destinations_image_size' ) ){
	/**
	 * Filter to define image size of destination section
	 */    
	function travel_booking_destinations_image_size(){
		return 'travel-booking-destination';
	}
}
add_filter( 'tbt_destination_image_size', 'travel_booking_destinations_image_size' );

if( ! function_exists( 'travel_booking_cta_section_bgcolor_filter' ) ){
	/**
	 * Filter to add bg color of cta section widget
	 */    
	function travel_booking_cta_section_bgcolor_filter(){
		return '#0aa3f3';
	}
}
add_filter( 'tbt_cta_bg_color', 'travel_booking_cta_section_bgcolor_filter' );

if( ! function_exists( 'travel_booking_cta_btn_alignment_filter' ) ){
	/**
	 * Filter to add btn alignment of cta section widget
	 */    
	function travel_booking_cta_btn_alignment_filter(){
		return 'centered';
	}
}
add_filter( 'tbt_cta_btn_alignment', 'travel_booking_cta_btn_alignment_filter' );
