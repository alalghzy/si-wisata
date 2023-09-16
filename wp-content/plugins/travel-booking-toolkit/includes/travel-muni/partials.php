<?php

/*************
----Header Settings 
************/
if ( ! function_exists( 'travel_muni_get_phone' ) ) :
    /**
     * Phone
     */
    function travel_muni_get_phone() {
        return esc_html( get_theme_mod( 'phone', __( '(000) 999-656-888', 'travel-booking-toolkit' ) ) );
    }
    
endif;

if ( ! function_exists( 'travel_muni_phone_label' ) ) :
    /**
     * Phone Label
     */
    function travel_muni_phone_label() {
        return esc_html( get_theme_mod( 'phone_label', __( 'Talk to an Expert (David)', 'travel-booking-toolkit' ) ) );
    }
endif;

if ( ! function_exists( 'travel_muni_get_email' ) ) :
    /**
     * Email
     */
    function travel_muni_get_email() {
        return sanitize_email( get_theme_mod( 'email', __( 'contact@travelmuni.com', 'travel-booking-toolkit' ) ) );
    }
    
endif;

if ( ! function_exists( 'travel_muni_get_header_email_label' ) ) :
    /**
     * Email Label
     */
    function travel_muni_get_header_email_label() {
        return esc_html( get_theme_mod( 'header_email_label', __( 'Quick Questions? Email Us', 'travel-booking-toolkit' ) ) );
    }
endif;


if ( ! function_exists( 'travel_muni_get_customize_button' ) ) :
    /**
     * Customize Button
     */
    function travel_muni_get_customize_button() {
        return esc_html( get_theme_mod( 'customize_button', __( 'Customize The Trip', 'travel-booking-toolkit' ) ) );
    }
    
endif;


/*************
* Intro Section 
* @section-- Homepage
************/
if ( ! function_exists( 'travel_muni_get_intro_title' ) ) :
    /**
     * Intro Title
     */
    function travel_muni_get_intro_title() {
        return esc_html( get_theme_mod( 'intro_title', __( 'Create Your Travel Booking Website with Travel Muni Theme', 'travel-booking-toolkit' ) ) );
    }
    
endif;

if ( ! function_exists( 'travel_muni_get_intro_desc' ) ) :
    /**
     * Intro Desc
     */
    function travel_muni_get_intro_desc() {
        return wp_kses_post( get_theme_mod( 'intro_desc', __( '<p>Tell a story about your company here. You can modify this section from Appearance > Customize > Home Page Settings > About Section.</p>
            <p>Travel Agency is a free WordPress theme that you can use create stunning and functional travel and tour booking website. It is lightweight, responsive and SEO friendly. It is compatible with WP Travel Engine, a WordPress plugin for travel booking. </p>', 'travel-booking-toolkit' ) ) );
    }
    
endif;



if ( ! function_exists( 'travel_muni_get_intro_readmore' ) ) :
    /**
     * Intro Readmore label
     */
    function travel_muni_get_intro_readmore() {
        return esc_html( get_theme_mod( 'intro_readmore', __( 'Know More About Us', 'travel-booking-toolkit' ) ) );
    }
    
endif;


/*************
* Top Destination Section 
* @section-- Homepage
************/
if ( ! function_exists( 'travel_muni_get_destination_title' ) ) :
    /**
     * Destination Title
     */
    function travel_muni_get_destination_title() {
        return esc_html( get_theme_mod( 'destination_title', __( 'Top Destinations', 'travel-booking-toolkit' ) ) );
    }
    
endif;

if ( ! function_exists( 'travel_muni_get_destination_desc' ) ) :
    /**
     * Destination Desc
     */
    function travel_muni_get_destination_desc() {
        return wp_kses_post( get_theme_mod( 'destination_desc', __( 'For the Tours in Nepal, Trekking in Nepal, Holidays and Air Ticketing. We offer and we are committed to making your time in Nepal.', 'travel-booking-toolkit' ) ) );
    }
endif;

if ( ! function_exists( 'travel_muni_get_destination_more_label' ) ) :
    /**
     * Destination Label
     */
    function travel_muni_get_destination_more_label() {
        return esc_html( get_theme_mod( 'destination_more_label', __( '28+ Top Destinations', 'travel-booking-toolkit' ) ) );
    }
    
endif;

if ( ! function_exists( 'travel_muni_get_destination_view_more_label' ) ) :
    /**
     * Destination More Label
     */
    function travel_muni_get_destination_view_more_label() {
        return esc_html( get_theme_mod( 'destination_view_more_label', __( 'View All', 'travel-booking-toolkit' ) ) );
    }
    
endif;

/*************
* Clients Testimonial Section 
* @section-- Homepage
************/

if ( ! function_exists( 'travel_muni_get_testimonial_title' ) ) :
    /**
     * Testimonial Title
     */
    function travel_muni_get_testimonial_title() {
        return esc_html( get_theme_mod( 'testimonial_title', __( 'Clients Testimonials', 'travel-booking-toolkit' ) ) );
    }
endif;

if ( ! function_exists( 'travel_muni_get_testimonial_section_btn_label' ) ) :
    /**
     * Testimonial Label
     */
    function travel_muni_get_testimonial_section_btn_label() {
        return esc_html( get_theme_mod( 'testimonial_section_btn_label', __( 'Read More Reviews', 'travel-booking-toolkit' ) ) );
    }
endif;

/*************
* Popular Section 
* @section-- Homepage
************/

if ( ! function_exists( 'travel_muni_get_popular_title' ) ) :
    /**
     * Popular Title
     */
    function travel_muni_get_popular_title() {
        return esc_html( get_theme_mod( 'popular_title', __( 'Popular Trips', 'travel-booking-toolkit' ) ) );
    }
endif;

if ( ! function_exists( 'travel_muni_get_popular_desc' ) ) :
    /**
     * Popular Desc 
     */
    function travel_muni_get_popular_desc() {
        return wp_kses_post( get_theme_mod( 'popular_desc', __( 'The origin of the word travel is most likely lost to history. The term travel may originate from the Old French word travail.', 'travel-booking-toolkit' ) ) );
    }
endif;

if ( ! function_exists( 'travel_muni_get_popular_view_more_label' ) ) :
    /**
     * Popular More Label
     */
    function travel_muni_get_popular_view_more_label() {
        return esc_html( get_theme_mod( 'popular_view_more_label', __( 'View More Trips', 'travel-booking-toolkit' ) ) );
    }
endif;

/*************
* Activities Section 
* @section-- Homepage
************/

if ( ! function_exists( 'travel_muni_get_activities_title' ) ) :
    /**
     * Activity Title
     */
    function travel_muni_get_activities_title() {
        return esc_html( get_theme_mod( 'activities_title', __( 'Category', 'travel-booking-toolkit' ) ) );
    }
endif;

if ( ! function_exists( 'travel_muni_get_activities_desc' ) ) :
    /**
     * Activity Desc 
     */
    function travel_muni_get_activities_desc() {
        return wp_kses_post( get_theme_mod( 'activities_desc', __( 'The origin of the word travel is most likely lost to history. The term travel may originate from the Old French word travail.', 'travel-booking-toolkit' ) ) );
    }
endif;

/*************
* Special Section 
* @section-- Homepage
************/

if ( ! function_exists( 'travel_muni_get_special_offer_title' ) ) :
    /**
     * Special Title
     */
    function travel_muni_get_special_offer_title() {
        return esc_html( get_theme_mod( 'special_offer_title', __( 'Special Offers', 'travel-booking-toolkit' ) ) );
    }
endif;

if ( ! function_exists( 'travel_muni_get_special_offer_desc' ) ) :
    /**
     * Special Desc 
     */
    function travel_muni_get_special_offer_desc() {
        return wp_kses_post( get_theme_mod( 'special_offer_desc', __( 'The origin of the word travel is most likely lost to history.', 'travel-booking-toolkit' ) ) );
    }
endif;

/*************
* CTA Section 
* @section-- Homepage
************/

if ( ! function_exists( 'travel_muni_get_cta_title' ) ) :
    /**
     * CTA Title
     */
    function travel_muni_get_cta_title() {
        return esc_html( get_theme_mod( 'cta_title', __( 'Why Book With Us', 'travel-booking-toolkit' ) ) );
    }
endif;

if ( ! function_exists( 'travel_muni_get_cta_desc' ) ) :
    /**
     * CTA Desc 
     */
    function travel_muni_get_cta_desc() {
        return wp_kses_post( get_theme_mod( 'cta_desc', __( 'Let your visitors know why they should trust you. You can modify this section from Appearance > Customize > Home Page Settings > Why Book with Us.', 'travel-booking-toolkit' ) ) );
    }
endif;

/*************
* Association Section 
* @section-- Homepage
************/

if ( ! function_exists( 'travel_muni_get_recommendation_section_title' ) ) :
    /**
     * Association Section Title 
     */
    function travel_muni_get_recommendation_section_title() {
        return esc_html( get_theme_mod( 'recommendation_section_title', __( 'Were recommended by', 'travel-booking-toolkit' ) ) );
    }
endif;

if ( ! function_exists( 'travel_muni_get_recommendation_desc' ) ) :
    /**
     * Recommendation Section Subtitle 
     */
    function travel_muni_get_recommendation_desc() {
        return wp_kses_post( get_theme_mod( 'recommendation_desc', __( 'Travel by water often provided more comfort and speed than land-travel.', 'travel-booking-toolkit' ) ));
    }
endif;

if ( ! function_exists( 'travel_muni_get_associated_section_title' ) ) :
    /**
     * Association Section Title 
     */
    function travel_muni_get_associated_section_title() {
        return esc_html( get_theme_mod( 'associated_section_title', __( 'Were associated withs', 'travel-booking-toolkit' ) ) );
    }
endif;

if ( ! function_exists( 'travel_muni_get_associated_desc' ) ) :
    /**
     * Association Section Subtitle 
     */
    function travel_muni_get_associated_desc() {
        return wp_kses_post( get_theme_mod( 'associated_desc', __( 'The origin of the word travel is most likely lost to history.', 'travel-booking-toolkit' ) ) );
    }
endif;

if ( ! function_exists( 'travel_muni_get_footer_email_label' ) ) :
    function travel_muni_get_footer_email_label() {
        return esc_html( get_theme_mod( 'footer_email_label', __( 'Write us at...', 'travel-booking-toolkit' ) ) );
    }
endif;

if ( ! function_exists( 'travel_muni_get_footer_phone_label' ) ) :
    function travel_muni_get_footer_phone_label() {
        return esc_html( get_theme_mod( 'footer_email_label', __( 'Call us on ...', 'travel-booking-toolkit' ) ) );
    }
endif;

if ( ! function_exists( 'travel_muni_get_related_trip_title' ) ) :
    function travel_muni_get_related_trip_title() {
        return esc_html( get_theme_mod( 'related_trip_title', __( 'Related Trips you might intrested in', 'travel-booking-toolkit' ) ) );
    }
endif;
