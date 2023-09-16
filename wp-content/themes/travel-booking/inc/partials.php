<?php
/**
 * Partials for Selective Refresh
 *
 * @package Travel_Booking
 */

if ( ! function_exists( 'travel_booking_customize_partial_blogname' ) ) :
    /**
     * Render the site title for the selective refresh partial.
     *
     */
    function travel_booking_customize_partial_blogname() {
        $blog_name = get_bloginfo( 'name' );

        if ( $blog_name ){
            return esc_html( $blog_name );
        } else {
            return false;
        }

    }
endif;

if ( ! function_exists( 'travel_booking_customize_partial_blogdescription' ) ) :
    /**
     * Render the site description for the selective refresh partial.
     *
     */
    function travel_booking_customize_partial_blogdescription() {
        $blog_description = get_bloginfo( 'description' );

        if ( $blog_description ){
            return esc_html( $blog_description );
        } else {
            return false;
        }
    }
endif;

if( ! function_exists( 'travel_booking_get_banner_title' ) ) :
    /**
     * Display Banner title
    */
    function travel_booking_get_banner_title(){
        $banner_title = get_theme_mod( 'banner_title', __( 'Find Your Best Holiday', 'travel-booking' ) );

        if( ! empty( $banner_title ) ){
            return esc_html( $banner_title );
        }
                                                   
        return false;           
    }
endif;

if( ! function_exists( 'travel_booking_get_banner_btn_label' ) ) :
/**
 * Display Banner Button Label
*/
function travel_booking_get_banner_btn_label(){
    $button_label = get_theme_mod( 'banner_btn_label', __( 'GET STARTED', 'travel-booking' ) );

    if( ! empty( $button_label ) ){
        return esc_html( $button_label );
    }

    return false;    
}
endif;

if( ! function_exists( 'travel_booking_get_about_section_readmore_btn_label' ) ) :
/**
 * Display About Section Button Label
*/
function travel_booking_get_about_section_readmore_btn_label(){
    $button_label = get_theme_mod( 'about_widget_readmore_text', __( 'Read More', 'travel-booking' ) );

    if( ! empty( $button_label ) ){
        return esc_html( $button_label );
    }

    return false;    
}
endif;

if( ! function_exists( 'travel_booking_get_blog_section_title' ) ) :
/**
 * Display blog section title
*/
function travel_booking_get_blog_section_title(){
    $section_title = get_theme_mod( 'blog_section_title', __( 'Travel Stories', 'travel-booking' ) );

    if( ! empty( $section_title ) ){
        return esc_html( $section_title );
    }

    return false;
}
endif;

if( ! function_exists( 'travel_booking_get_blog_section_sub_title' ) ) :
/**
 * Display blog section sub-title
*/
function travel_booking_get_blog_section_sub_title(){
    $section_subtitle = get_theme_mod( 'blog_section_subtitle', __( 'This is the best place to show your most sold and popular travel packages. You can modify this section from Appearance > Customize > Front Page Settings > Blog section.', 'travel-booking' ) );

    if( ! empty( $section_subtitle ) ){
        return wpautop( wp_kses_post( $section_subtitle ) );
    } 

    return false;
    
}
endif;

if( ! function_exists( 'travel_booking_get_blog_section_readmore' ) ) :
/**
 * Display blog section readmore
*/
function travel_booking_get_blog_section_readmore(){
    $blog_readmore = get_theme_mod( 'blog_section_readmore', __( 'Read More', 'travel-booking' ) );

    if( ! empty( $blog_readmore ) ){
        return esc_html( $blog_readmore );
    } 

    return false;
    
}
endif;


if( ! function_exists( 'travel_booking_get_blog_view_all_btn' ) ) :
/**
 * Display blog view all button
*/
function travel_booking_get_blog_view_all_btn(){
    $blog_view_all = get_theme_mod( 'blog_view_all', __( 'View All Posts', 'travel-booking' ) );

    if( ! empty( $blog_view_all ) ){
        return esc_html( $blog_view_all );
    } 
    return false;
}
endif;

if( ! function_exists( 'travel_booking_get_related_title' ) ) :
/**
 * Display blog view all button
*/
function travel_booking_get_related_title(){
    $related_posts_title = get_theme_mod( 'related_title', __( 'You may also like.', 'travel-booking' ) );

    if( ! empty( $related_posts_title ) ){
        return esc_html( $related_posts_title ); 
    }

    return false;
}
endif;

if( ! function_exists( 'travel_booking_get_readmore_btn' ) ) :
/**
 * Display blog view all button
*/
function travel_booking_get_readmore_btn(){
    $readmore_label = get_theme_mod( 'readmore', __( 'Read More', 'travel-booking' ) );

    if( ! empty( $readmore_label ) ){
        return esc_html( $readmore_label );
    }

    return false;
}
endif;

if( ! function_exists( 'travel_booking_get_footer_copyright' ) ) :
/**
 * Prints footer copyright
*/
function travel_booking_get_footer_copyright(){
    $copyright = get_theme_mod( 'footer_copyright' );
    echo '<span class="copyright">';
    if( $copyright ){
        echo wp_kses_post( $copyright );
    }else{
        esc_html_e( '&copy; Copyright ', 'travel-booking' ); 
        echo date_i18n( esc_html__( 'Y', 'travel-booking' ) );
        echo ' <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>. ';    
    }    
    echo '</span>';
}
endif;