<?php
/**
 * Widgets
 *
 * @package Travel_Booking
 */

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function travel_booking_widgets_init() {
	
    $sidebars = array(
        'sidebar'   => array(
            'name'        => __( 'Sidebar', 'travel-booking' ),
            'id'          => 'sidebar', 
            'description' => __( 'Default Sidebar', 'travel-booking' ),
        ),
        'about' => array(
            'name'        => __( 'About Section', 'travel-booking' ),
            'id'          => 'about', 
            'description' => __( 'Add "Text" widget for the title and description. Add "WP Travel Engine: Icon Text Widget" for the about section.', 'travel-booking' ),
        ),
        'cta-one' => array(
            'name'        => __( 'Call to Action One Section', 'travel-booking' ),
            'id'          => 'cta-one', 
            'description' => __( 'Add "WP Travel Engine: Call To Action Widget" for the cta one section.', 'travel-booking' ),
        ),
        'cta-two' => array(
            'name'        => __( 'Call to Action Two Section', 'travel-booking' ),
            'id'          => 'cta-two', 
            'description' => __( 'Add "WP Travel Engine: Call To Action Widget" for the cta two section.', 'travel-booking' ),
        ),
        'footer-one'=> array(
            'name'        => __( 'Footer One', 'travel-booking' ),
            'id'          => 'footer-one', 
            'description' => __( 'Add footer one widgets here.', 'travel-booking' ),
        ),
        'footer-two'=> array(
            'name'        => __( 'Footer Two', 'travel-booking' ),
            'id'          => 'footer-two', 
            'description' => __( 'Add footer two widgets here.', 'travel-booking' ),
        ),
        'footer-three'=> array(
            'name'        => __( 'Footer Three', 'travel-booking' ),
            'id'          => 'footer-three', 
            'description' => __( 'Add footer three widgets here.', 'travel-booking' ),
        ),
        'footer-four'=> array(
            'name'        => __( 'Footer Four', 'travel-booking' ),
            'id'          => 'footer-four', 
            'description' => __( 'Add footer four widgets here.', 'travel-booking' ),
        )
    );
    
    foreach( $sidebars as $sidebar ){
        register_sidebar( array(
    		'name'          => esc_html( $sidebar['name'] ),
    		'id'            => esc_attr( $sidebar['id'] ),
    		'description'   => esc_html( $sidebar['description'] ),
    		'before_widget' => '<section id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</section>',
    		'before_title'  => '<h2 class="widget-title" itemprop="name">',
    		'after_title'   => '</h2>',
    	) );
    }
    
}
add_action( 'widgets_init', 'travel_booking_widgets_init' );