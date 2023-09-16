<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Travel_Booking
 */

    /**
     * After Content
     * 
     * @hooked travel_booking_content_end - 20
     * @hooked travel_booking_container_end - 30
    */
    do_action( 'travel_booking_before_footer' );
    
    /**
     * Footer
     * 
     * @hooked travel_booking_footer_start  - 20
     * @hooked travel_booking_footer_top    - 30
     * @hooked travel_booking_footer_bottom - 40
     * @hooked travel_booking_footer_end    - 50
    */
    do_action( 'travel_booking_footer' );
    
    /**
     * After Footer
     * 
     * @hooked travel_booking_page_end - 20
    */
    do_action( 'travel_booking_after_footer' );   
 
    wp_footer(); 

?>
</body>
</html>