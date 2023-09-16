<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://wptravelengine.com
 * @since      1.0.0
 *
 * @package    Travel_Booking_Toolkit
 * @subpackage Travel_Booking_Toolkit/public/partials
 */

/**
 * Return popular section title
*/
function travel_booking_toolkit_get_popular_section_title(){
	$popular_section_title = get_theme_mod( 'popular_title', __( 'Popular Packages', 'travel-booking-toolkit' ) );

	if( ! empty( $popular_section_title ) ){
    	return $popular_section_title;
	}

    return false;
}

/**
 * Return popular section content
*/
function travel_booking_toolkit_get_popular_section_content(){
	$popular_section_desc = get_theme_mod( 'popular_desc', __( 'This is the best place to show your most sold and popular travel packages. You can modify this section from Appearance > Customize > Home Page Settings > Best Sellers Packages.', 'travel-booking-toolkit' ) );

	if( ! empty( $popular_section_desc ) ){
		return wpautop( $popular_section_desc );
	}

    return false;
}

/**
 * Return popular section readmore
*/
function travel_booking_toolkit_get_popular_readmore(){
	$popular_section_readmore = get_theme_mod( 'popular_readmore_label', __( 'View Details', 'travel-booking-toolkit' ) );

	if( ! empty( $popular_section_readmore ) ){
		return $popular_section_readmore;
	}

    return false;
}

/**
 * Return popular section view all
*/
function travel_booking_toolkit_get_popular_view_all(){
	$popular_section_readmore = get_theme_mod( 'popular_view_all_label', __( 'View All Packages', 'travel-booking-toolkit' ) );

	if( ! empty( $popular_section_readmore ) ){
		return $popular_section_readmore;
	}

    return false;
}

/**
 * Return destination section view all
*/
function travel_booking_toolkit_get_destination_view_all(){
	$popular_section_readmore = get_theme_mod( 'destination_view_all_label', __( 'View All Destinations', 'travel-booking-toolkit' ) );

	if( ! empty( $popular_section_readmore ) ){
		return $popular_section_readmore;
	}

    return false;
}


/**
 * Return featured section title
*/
function travel_booking_toolkit_get_featured_title(){
	$featured_title = get_theme_mod( 'feature_title', __( 'Featured Trip', 'travel-booking-toolkit' ) );

	if( ! empty( $featured_title ) ){
		return $featured_title;
	}
    return false;
}

/**
 * Return featured section content
*/
function travel_booking_toolkit_get_featured_content(){
	$featured_content = get_theme_mod( 'feature_desc', __( 'This is the best place to show your other travel packages. You can modify this section from Appearance > Customize > Home Page Settings > Featured Section.', 'travel-booking-toolkit' ) );

	if( ! empty( $featured_content ) ){
		return wpautop( $featured_content );
	} 
    return false;
}

/**
 * Return featured section btn label
*/
function travel_booking_toolkit_get_featured_label(){
	$readmore_label = get_theme_mod( 'featured_readmore', __( 'View Detail', 'travel-booking-toolkit' ) );

	if( ! empty( $readmore_label ) ){
		return $readmore_label;
	}
    return false;
}

/**
 * Return featured section view all btn label
*/
function travel_booking_toolkit_get_featured_view_all_label(){
	$view_all_label = get_theme_mod( 'featured_view_all', __( 'View All Packages', 'travel-booking-toolkit' ) );

	if( ! empty( $view_all_label ) ){
		return $view_all_label;
	}
    return false;
}

/**
 * Return deal section title
*/
function travel_booking_toolkit_get_deal_title(){
	$deal_title = get_theme_mod( 'deal_title', __( 'Deals and Discounts', 'travel-booking-toolkit' ) );

	if( ! empty( $deal_title ) ){
    	return $deal_title; 
	}
	return false;
}

/**
 * Return deal section content
*/
function travel_booking_toolkit_get_deal_content(){
	$deal_content = get_theme_mod( 'deal_desc', __( 'how the special deals and discounts to your customers here. You can customize this section from Appearance > Customize > Home Page Settings > Deals Section.', 'travel-booking-toolkit' ) );

	if( ! empty( $deal_content ) ){
	    return wpautop( $deal_content );
	}
	return false;
}

/**
 * Return deal section btn label
*/
function travel_booking_toolkit_get_dealbtn_label(){
	$readmore_label = get_theme_mod( 'deal_readmore', __( 'View Details', 'travel-booking-toolkit' ) );

	if( ! empty( $readmore_label ) ){
	    return $readmore_label; 
	}
	return false;
}

/**
 * Return deal section view all btn label
*/
function travel_booking_toolkit_get_deal_view_all_label(){
	$view_all_label = get_theme_mod( 'deal_view_all_label', __( 'View All Deals', 'travel-booking-toolkit' ) );

	if( ! empty( $view_all_label ) ){
	    return $view_all_label;
	}
	return false;
}

/**
 * Return destination section title
*/
function travel_booking_toolkit_get_destination_title(){
	$destination_title = get_theme_mod( 'destination_title', __( 'Popular Destinations', 'travel-booking-toolkit' ) );

	if( ! empty( $destination_title ) ){
    	return $destination_title; 
	}
	return false;
}

/**
 * Return destination section content
*/
function travel_booking_toolkit_get_destination_content(){
	$destination_desc = get_theme_mod( 'destination_desc', __( 'How the special deals and discounts to your customers here. You can customize this section from Appearance > Customize > Home Page Settings > Destination Section.', 'travel-booking-toolkit' ) );

	if( ! empty( $destination_desc ) ){
    	return $destination_desc; 
	}
	return false;
}

/**
 * Return activities section title
*/
function travel_booking_toolkit_get_activities_title(){
	$activities_title = get_theme_mod( 'activities_title', __( 'Browse Activities', 'travel-booking-toolkit' ) );

	if( ! empty( $activities_title ) ){
    	return $activities_title; 
	}
	return false;
}

/**
 * Return activities section content
*/
function travel_booking_toolkit_get_activities_content(){
	$activities_desc = get_theme_mod( 'activities_desc', __( 'How the special deals and discounts to your customers here. You can customize this section from Appearance > Customize > Home Page Settings > Activities Section.', 'travel-booking-toolkit' ) );

	if( ! empty( $activities_desc ) ){
    	return $activities_desc; 
	}
	return false;
}