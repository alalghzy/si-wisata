<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Travel_Booking
 */

if( ! function_exists( 'travel_booking_doctype' ) ) :
/**
 * Doctype Declaration
*/
function travel_booking_doctype(){
    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <?php
}
endif;
add_action( 'travel_booking_doctype', 'travel_booking_doctype' );

if( ! function_exists( 'travel_booking_head' ) ) :
/**
 * Before wp_head 
*/
function travel_booking_head(){
    ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php
}
endif;
add_action( 'travel_booking_before_wp_head', 'travel_booking_head' );

if( ! function_exists( 'travel_booking_page_start' ) ) :
/**
 * Page Start
*/
function travel_booking_page_start(){
    ?>
    <div id="page" class="site">
    <?php
}
endif;
add_action( 'travel_booking_before_header', 'travel_booking_page_start', 20 );

if( ! function_exists( 'travel_booking_header' ) ) :
/**
 * Header Start
*/
function travel_booking_header(){     
    $header_text = get_theme_mod( 'header_text', true );
    ?>
    <header class="site-header" itemscope itemtype="https://schema.org/WPHeader">
        <div class="site-branding" itemscope itemtype="https://schema.org/Organization">
            <?php 
                if( function_exists( 'has_custom_logo' ) && has_custom_logo() ){
                    the_custom_logo();
                } 
                
                if( $header_text ){
                    echo '<div class="text-logo">';

                    if( is_front_page() ){ ?>
                        <h1 class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ) ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>
                    <?php }else{ ?>
                        <p class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ) ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a></p>
                    <?php
                    }

                    $description = get_bloginfo( 'description', 'display' );
                    if ( $description || is_customize_preview() ){ ?>
                        <p class="site-description" itemprop="description"><?php echo $description; ?></p>
                    <?php
                    }

                    echo '</div>';
                } 
            ?>
        </div>
        <div class="mobile-header">
            <div class="overlay"></div>
            <button id="toggle-button" data-toggle-target=".main-menu-modal" class="mobile-menu-opener" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".close-main-nav-toggle">
                <span></span>
            </button>
            <div class="mobile-menu-wrapper">
                <nav id="mobile-site-navigation" class="main-navigation mobile-navigation">
                    <div class="primary-menu-list main-menu-modal cover-modal" data-modal-target-string=".main-menu-modal">
                        <button class="close close-main-nav-toggle" data-toggle-target=".main-menu-modal" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".main-menu-modal"><span></span></button>
                        <div class="mobile-menu" aria-label="<?php esc_attr_e( 'Mobile', 'travel-booking' ); ?>">   
                            <?php get_search_form(); ?>
                            <?php
                                wp_nav_menu( array(
                                    'theme_location' => 'primary',
                                    'menu_id'        => 'mobile-primary-menu',
                                    'menu_class'     => 'nav-menu main-menu-modal',
                                    'fallback_cb'    => 'travel_booking_primary_menu_fallback',
                                ) );
                            ?>
                        </div>
                    </div>
                </nav><!-- #mobile-site-navigation -->
            </div>
        </div>

        <div class="right">
            <nav id="site-navigation" class="main-navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
               <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'fallback_cb'    => 'travel_booking_primary_menu_fallback',
                    ) );
                ?>
            </nav>
            <div class="tools">
                <?php travel_booking_get_header_search(); ?>
            </div>
        </div>

	</header> <!-- header ends -->
    <?php
}
endif;
add_action( 'travel_booking_header', 'travel_booking_header', 20 );

if( ! function_exists( 'travel_booking_container_start' ) ) :
/**
 * Add main container before content 
*/
function travel_booking_container_start(){
    $home_sections = travel_booking_get_homepage_section();

    if( is_front_page() && ! is_home() ){
        if( empty( $home_sections ) ){
            echo '<div class="container-top"></div><div class="container">';
            return;
        }else{
            return;
        }
    }

    if( is_404() ) echo '<div class="error-page" style="background: url('. esc_url( get_template_directory_uri() .'/images/bg-error.jpg' ) .') no-repeat;">';
    ?>
    <div class="container">
    <?php
}
endif;
add_action( 'travel_booking_before_content', 'travel_booking_container_start' );

if( ! function_exists( 'travel_booking_container_end' ) ) :
/**
 * Add main container before content 
*/
function travel_booking_container_end(){
     $home_sections = travel_booking_get_homepage_section();

    if( is_front_page() && ! is_home() ){
        if( empty( $home_sections ) ){
            echo '</div><!-- .container -->';
            return;
        }else{
            return;
        }
    }

    ?>
    </div><!-- .container -->
    <?php
    if( is_404() ){

        echo '</div><!-- .error-page -->';

        /**
         * Popular Packages
         * 
         * @hooked travel_booking_get_popular_package - 15
         */
        do_action( 'travel_booking_popular_package' ); 
    }
}
endif;
add_action( 'travel_booking_before_footer', 'travel_booking_container_end', 30 );

if( ! function_exists( 'travel_booking_breadcrumb' ) ) :
/**
 * Page Header for inner pages
*/
function travel_booking_breadcrumb(){    
    
    global $post;
    $post_page  = get_option( 'page_for_posts' ); //The ID of the page that displays posts.
    $show_front = get_option( 'show_on_front' ); //What to show on the front page    
    $home       = get_theme_mod( 'breadcrumb_home_text', __( 'Home', 'travel-booking' ) ); // text for the 'Home' link
    $before     = '<li class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">'; // tag before the current crumb
    $after      = '</li>'; // tag after the current crumb
    
    if( get_theme_mod( 'ed_breadcrumb', true ) && ! is_front_page() ){
        $depth = 1;
        echo '<div class="breadcrumb-wrapper">
                <ul id="crumbs" itemscope itemtype="https://schema.org/BreadcrumbList">
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( home_url() ) . '" itemprop="item"><span itemprop="name">' . esc_html( $home ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
        
        if( is_home() ){
            $depth = 2;
            echo $before . '<a itemprop="item" href="'. esc_url( get_the_permalink() ) .'"><span itemprop="name">' . esc_html( single_post_title( '', false ) ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
            
        }elseif( is_category() ){
            $depth = 2;
            $thisCat = get_category( get_query_var( 'cat' ), false );

            if( $show_front === 'page' && $post_page ){ //If static blog post page is set
                $p = get_post( $post_page );
                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( $post_page ) ) . '"><span itemprop="name">' . esc_html( $p->post_title ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
                $depth ++;  
            }

            if ( $thisCat->parent != 0 ) {
                $parent_categories = get_category_parents( $thisCat->parent, false, ',' );
                $parent_categories = explode( ',', $parent_categories );

                foreach ( $parent_categories as $parent_term ) {
                    $parent_obj = get_term_by( 'name', $parent_term, 'category' );
                    if( is_object( $parent_obj ) ){
                        $term_url    = get_term_link( $parent_obj->term_id );
                        $term_name   = $parent_obj->name;
                        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
                        $depth ++;
                    }
                }
            }
            echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $thisCat->term_id) ) . '"><span itemprop="name">' .  esc_html( single_cat_title( '', false ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;
        
        }elseif( travel_booking_is_wpte_activated() && is_tax( array( 'activities', 'destination', 'trip_types' ) ) ){  //Trip Taxonomy pages
            $current_term = $GLOBALS['wp_query']->get_queried_object();
            $tax = array(
                'activities'  => 'templates/template-activities.php',
                'destination' => 'templates/template-destination.php',
                'trip_types'  => 'templates/template-trip_types.php'
            );
            $depth = 2;
            
            foreach( $tax as $k => $v ){
                if( is_tax( $k ) ){
                    $p_id = travel_booking_get_page_id_by_template( $v );
                    if( $p_id ){
                        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $p_id[0] ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title( $p_id[0] ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
                    }else{
                        $post_type = get_post_type_object( 'trip' );
                        if( $post_type->has_archive == true ){// For CPT Archive Link
                           
                           // Add support for a non-standard label of 'archive_title' (special use case).
                           $label = !empty( $post_type->labels->archive_title ) ? $post_type->labels->archive_title : $post_type->labels->name;
                           printf( '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>', esc_url( get_post_type_archive_link( get_post_type() ) ), $label );            
                        }
                    }

                    $depth = 3;
                    //For trip taxonomy hierarchy
                    $ancestors = get_ancestors( $current_term->term_id, $k );
                    $ancestors = array_reverse( $ancestors );
            		foreach ( $ancestors as $ancestor ) {
            			$ancestor = get_term( $ancestor, $k );    
            			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
            				echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_term_link( $ancestor ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
                            $depth++;
            			}
            		}
                }
            }

            echo $before .'<a itemprop="item" href="' . esc_url( get_term_link( $current_term->term_id ) ) . '"><span itemprop="name">' . esc_html( $current_term->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;
        }elseif(travel_booking_is_woocommerce_activated() && ( is_product_category() || is_product_tag() ) ){ //For Woocommerce archive page
            
            $depth = 2;
            $current_term = $GLOBALS['wp_query']->get_queried_object();
            if( is_product_category() ){
                $ancestors = get_ancestors( $current_term->term_id, 'product_cat' );
                $ancestors = array_reverse( $ancestors );
                foreach ( $ancestors as $ancestor ) {
                    $ancestor = get_term( $ancestor, 'product_cat' );    
                    if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_term_link( $ancestor ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
                        $depth ++;
                    }
                }
            }           
            echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $current_term->term_id ) ) . '"><span itemprop="name">' . esc_html( $current_term->name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;
            
        }elseif(travel_booking_is_woocommerce_activated() && is_shop() ){ //Shop Archive page
            $depth = 2;
            if ( get_option( 'page_on_front' ) == wc_get_page_id( 'shop' ) ) {
                return;
            }
            $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
            $shop_url = wc_get_page_id( 'shop' ) && wc_get_page_id( 'shop' ) > 0  ? get_the_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' );
    
            if ( ! $_name ) {
                $product_post_type = get_post_type_object( 'product' );
                $_name = $product_post_type->labels->singular_name;
            }
            echo $before . '<a itemprop="item" href="' . esc_url( $shop_url ) . '"><span itemprop="name">' . esc_html( $_name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
            
        }elseif( is_tag() ){
            $queried_object = get_queried_object();
            $depth = 2;

            echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $queried_object->term_id ) ) . '"><span itemprop="name">' . esc_html( single_tag_title( '', false ) ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
     
        }elseif( is_author() ){
            $depth = 2;
            global $author;

            $userdata = get_userdata( $author );
            echo $before . '<a itemprop="item" href="' . esc_url( get_author_posts_url( $author ) ) . '"><span itemprop="name">' . esc_html( $userdata->display_name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
     
        }elseif( is_search() ){
            $depth = 2;
            $request_uri = $_SERVER['REQUEST_URI'];
            echo $before .'<a itemprop="item" href="'. esc_url( $request_uri ) .'"><span itemprop="name">'. esc_html__( 'Search Results for "', 'travel-booking' ) . esc_html( get_search_query() ) . esc_html__( '"', 'travel-booking' ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        
        }elseif( is_day() ){
            
            $depth = 2;
            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'travel-booking' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'travel-booking' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
            $depth ++;
            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_month_link( get_the_time( __( 'Y', 'travel-booking' ) ), get_the_time( __( 'm', 'travel-booking' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'F', 'travel-booking' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
            $depth ++;
            echo $before .'<a itemprop="item" href="' . esc_url( get_day_link( get_the_time( __( 'Y', 'travel-booking' ) ), get_the_time( __( 'm', 'travel-booking' ) ), get_the_time( __( 'd', 'travel-booking' ) ) ) ) . '"><span itemprop="name">'. esc_html( get_the_time( __( 'd', 'travel-booking' ) ) ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        
        }elseif( is_month() ){
            
            $depth = 2;
            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'travel-booking' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'travel-booking' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
            $depth++;
            echo $before .'<a itemprop="item" href="' . esc_url( get_month_link( get_the_time( __( 'Y', 'travel-booking' ) ), get_the_time( __( 'm', 'travel-booking' ) ) ) ) . '"><span itemprop="name">'. esc_html( get_the_time( __( 'F', 'travel-booking' ) ) ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        
        }elseif( is_year() ){
            
            $depth = 2;
            echo $before .'<a itemprop="item" href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'travel-booking' ) ) ) ) . '"><span itemprop="name">'. esc_html( get_the_time( __( 'Y', 'travel-booking' ) ) ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
    
        }elseif( is_single() && !is_attachment() ){
            $depth = 2;
            if( travel_booking_is_wpte_activated() && get_post_type() === 'trip' ){ //For Single Trip 
                // Check for Destination page templage
                $destination = travel_booking_get_page_id_by_template( 'templates/template-destination.php' );
                if( $destination ){
                    echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $destination[0] ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title( $destination[0] ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';                                        
                }else{
                    $post_type = get_post_type_object( 'trip' );
                    if( $post_type->has_archive == true ){// For CPT Archive Link
                       
                       // Add support for a non-standard label of 'archive_title' (special use case).
                       $label = !empty( $post_type->labels->archive_title ) ? $post_type->labels->archive_title : $post_type->labels->name;
                       printf( '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>', esc_url( get_post_type_archive_link( get_post_type() ) ), $label );        
                    }                    
                }

                $depth = 3;
                // Check for destination taxonomy hierarchy
                $terms = wp_get_post_terms( $post->ID, 'destination', array( 'orderby' => 'parent', 'order' => 'DESC' ) );                
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) { //Parents terms
                    $ancestors = get_ancestors( $terms[0]->term_id, 'destination' );
                    $ancestors = array_reverse( $ancestors );
                    foreach ( $ancestors as $ancestor ) {
                        $ancestor = get_term( $ancestor, 'destination' );    
                        if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_term_link( $ancestor ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
                        }
                        $depth ++;
                    }                    
                    // Last child term
                    echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_term_link( $terms[0] ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $terms[0]->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
                    $depth ++;
                }
                                
                echo $before .'<a href="' . esc_url( get_the_permalink() ) . '" itemprop="item"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                
            }elseif( travel_booking_is_woocommerce_activated() && 'product' === get_post_type() ){ //For Woocommerce single product
                /** NEED TO CHECK THIS PORTION WHILE INTEGRATION WITH WOOCOMMERCE */
                if ( $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
                    $main_term = apply_filters( 'woocommerce_breadcrumb_main_term', $terms[0], $terms );
                    $ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
                    $ancestors = array_reverse( $ancestors );
                    foreach ( $ancestors as $ancestor ) {
                        $ancestor = get_term( $ancestor, 'product_cat' );    
                        if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_term_link( $ancestor ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
                            $depth++;
                        }
                    }
                    echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_term_link( $main_term ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $main_term->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
                    $depth ++;
                }
                
                echo $before .'<a href="' . esc_url( get_the_permalink() ) . '" itemprop="item"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                
            }elseif ( get_post_type() != 'post' ){
                $post_type = get_post_type_object( get_post_type() );
                
                if( $post_type->has_archive == true ){// For CPT Archive Link
                   
                   // Add support for a non-standard label of 'archive_title' (special use case).
                   $label = !empty( $post_type->labels->archive_title ) ? $post_type->labels->archive_title : $post_type->labels->name;
                   printf( '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a><meta itemprop="position" content="%3$s" />', esc_url( get_post_type_archive_link( get_post_type() ) ), $label, $depth );
                   echo '<meta itemprop="position" content="'. absint( $depth ).'" /></li>';
                   $depth ++;
    
                }
                echo $before .'<a href="' . esc_url( get_the_permalink() ) . '" itemprop="item"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                
            }else{ //For Post
                $cat_object       = get_the_category();
                $potential_parent = 0;
                $depth            = 2;
                
                if( $show_front === 'page' && $post_page ){ //If static blog post page is set
                    $p = get_post( $post_page );
                    echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $post_page ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $p->post_title ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';  
                    $depth++;
                }
                
                if( is_array( $cat_object ) ){ //Getting category hierarchy if any
        
                    //Now try to find the deepest term of those that we know of
                    $use_term = key( $cat_object );
                    foreach( $cat_object as $key => $object )
                    {
                        //Can't use the next($cat_object) trick since order is unknown
                        if( $object->parent > 0  && ( $potential_parent === 0 || $object->parent === $potential_parent ) ){
                            $use_term = $key;
                            $potential_parent = $object->term_id;
                        }
                    }
                    
                    $cat = $cat_object[$use_term];
              
                    $cats = get_category_parents( $cat, false, ',' );
                    $cats = explode( ',', $cats );

                    foreach ( $cats as $cat ) {
                        $cat_obj = get_term_by( 'name', $cat, 'category' );
                        if( is_object( $cat_obj ) ){
                            $term_url    = get_term_link( $cat_obj->term_id );
                            $term_name   = $cat_obj->name;
                            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
                            $depth ++;
                        }
                    }
                }
    
                echo $before .'<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                
            }
        
        }elseif( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ){
            $depth = 2;
            $post_type = get_post_type_object(get_post_type());
            if( get_query_var('paged') ){
                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $post_type->label ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
                echo $before . sprintf( __('Page %s', 'travel-booking'), get_query_var('paged') ) . $after;
            }elseif( is_archive() ){
                echo $before;
                    echo '<a itemprop="item" href="'. esc_url( get_post_type_archive_link( $post_type->name ) ). '"><span itemprop="name">';
                    echo esc_html( post_type_archive_title() ); 
                    echo '</span></a><meta itemprop="position" content="'. absint( $depth ).'">';    
                echo $after;
            }else{
                echo $before .'<a itemprop="item" href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '"><span itemprop="name">'. esc_html( $post_type->label ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
            }
    
        }elseif( is_attachment() ){
            global $post;
            $depth = 2;
            $parent = get_post( $post->post_parent );
            $cat = get_the_category( $parent->ID ); 
            if( $cat ){
                $cat = $cat[0];
                echo get_category_parents( $cat, TRUE, ' ' );
                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $parent ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $parent->post_title ) . '<span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
            }
            echo  $before .'<a itemprop="item" href="' . esc_url( wp_get_attachment_url( $post->ID ) ) . '"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        
        }elseif( is_page() && !$post->post_parent ){
            $depth = 2;
            echo $before .'<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
    
        }elseif( is_page() && $post->post_parent ){
            $depth = 2;
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            
            while( $parent_id ){
                $page = get_post( $parent_id );
                $breadcrumbs[] = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $page->ID ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title( $page->ID ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></li>';
                $parent_id  = $page->post_parent;
                $depth++;
            }
            $breadcrumbs = array_reverse( $breadcrumbs );
            for ( $i = 0; $i < count( $breadcrumbs) ; $i++ ){
                echo $breadcrumbs[$i];
            }
            echo $before .'<a href="' . get_permalink() . '" itemprop="item"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></span>'. $after;
        
        }elseif( is_404() ){
            echo $before . esc_html__( '404 Error - Page Not Found', 'travel-booking' ) . $after;
        }
                
        echo '</ul></div><!-- .breadcrumb-wrapper -->';
        
    }
}
endif;
add_action( 'travel_booking_before_content', 'travel_booking_breadcrumb', 20 );

if( ! function_exists( 'travel_booking_get_search_page_header' ) ) :
/**
 * Content Start
*/
function travel_booking_get_search_page_header(){ 
    global $wp_query;
    ?>
    
    <div class="page-header">          
        <h1 class="page-title">
            <?php
                /* translators: %s: search query. */
                printf( esc_html__( 'Search Results for: %s', 'travel-booking' ), '<span>' . get_search_query() . '</span>' );
            ?>
        </h1>

        <div class="header-content">
            <?php
                echo '<p>';
                /* translators: 1: number of posts found, 2: search query   */
                printf( esc_html__( 'We found %1$s results for %2$s. You can search again if you are unsatisfied.', 'travel-booking' ), number_format_i18n( $wp_query->found_posts ), get_search_query() ); 
                echo '</p>';
            ?>
        </div>

        <?php get_search_form(); ?>

    </div><!-- .page-header --> 
    <?php        
}
endif;
add_action( 'travel_booking_search_page_header', 'travel_booking_get_search_page_header', 10 );

if( ! function_exists( 'travel_booking_content_start' ) ) :
/**
 * Content Start
*/
function travel_booking_content_start(){
    
    $home_sections = travel_booking_get_homepage_section();
    
    if( is_404() ) return;
    
    if( !( is_front_page() && ! is_home() && $home_sections ) ){
    ?>
    <div id="content" class="site-content">
       <div class="row">
    <?php
    }
}
endif;
add_action( 'travel_booking_content', 'travel_booking_content_start', 10 );

if( ! function_exists( 'travel_booking_render_banner_section' ) ) :
/**
 * Banner
*/
function travel_booking_render_banner_section(){
    
    $ed_banner_section      = get_theme_mod( 'ed_banner_section', true );

    if( is_front_page() && ! is_home() && $ed_banner_section ) {
        get_template_part( 'sections/banner' );
    }
}
endif;
add_action( 'travel_booking_banner_section', 'travel_booking_render_banner_section', 10 );

if( ! function_exists( 'travel_booking_page_header' ) ) :
/**
 * Page Header
*/
function travel_booking_page_header(){
    global $wp_query;

    if( is_404() ) return;
   
    if( is_archive() ){
        echo '<div class="page-header">';
		if( ! is_tax( array( 'destination', 'activities', 'trip_types' ) ) ){
            the_archive_title( '<h1 class="page-title">', '</h1>' );
            if( ! is_post_type_archive( 'trip' ) ) the_archive_description( '<div class="archive-description">', '</div>' );
        }
        echo '</div><!-- .page-header -->';
    }
}
endif;
add_action( 'travel_booking_before_content', 'travel_booking_page_header', 40 );

if( ! function_exists( 'travel_booking_get_post_page_header' ) ) :
    /**
     * Display post/page title.
     *
     */
    function travel_booking_get_post_page_header(){
        if( is_singular() ){
        ?>
        <header class="page-header">
            <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
        </header>
        <?php
        }
    }
endif;
add_action( 'travel_booking_post_page_header', 'travel_booking_get_post_page_header' );
add_action( 'travel_booking_before_text_holder', 'travel_booking_get_post_page_header', 10 );

if( ! function_exists( 'travel_booking_entry_header' ) ) :
/**
 * Post Entry Header
*/
function travel_booking_entry_header(){ 
    if( ! is_page() ){ ?>    
    <header class="entry-header">		

        <?php 
            if( is_single() ){
                the_title( '<h1 class="entry-title">', '</h1>' );    
            }else{
                the_title( '<h2 class="entry-title"><a href="' . esc_url( get_the_permalink() ) . '">', '</a></h2>' ); 
            }
        ?>        
        <div class="entry-meta">
            <?php  
                travel_booking_posted_on();
                travel_booking_posted_by();
                travel_booking_comment_count();   
            ?>
        </div>

	</header>
    <?php  
    }
}
endif;
add_action( 'travel_booking_post_content', 'travel_booking_entry_header', 20 );

if( ! function_exists( 'travel_booking_post_thumbnail' ) ) :
/**
 * Post Thumbnail
*/
function travel_booking_post_thumbnail(){
    if( is_singular() ){
        $sidebar_layout = travel_booking_sidebar_layout();
        $image_size     = ( 'fullwidth' != $sidebar_layout ) ? 'travel-booking-blog-single' : 'travel-booking-blog-full';

        if( has_post_thumbnail() ){
            echo '<div class="post-thumbnail">';
            the_post_thumbnail( $image_size, array( 'itemprop'=>'image' ) );
            echo '</div>';
        }
    }else{
        $image_size = 'travel-booking-blog-full';

        echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';
        if( has_post_thumbnail() ){
            the_post_thumbnail( $image_size, array( 'itemprop'=>'image' ) );
        } else {
            travel_booking_fallback_image( $image_size ); 
        }
        echo '</a>';
    }
}
endif;
add_action( 'travel_booking_before_entry_content', 'travel_booking_post_thumbnail', 20 );
add_action( 'travel_booking_before_text_holder', 'travel_booking_post_thumbnail', 20 );

if( ! function_exists( 'travel_booking_before_entry_header' ) ) :
/**
 * Display Categories
*/
function travel_booking_before_entry_header(){ ?>
    <div class="category">
        <?php travel_booking_categories(); ?>
    </div>
    <?php
}
endif;
add_action( 'travel_booking_post_content', 'travel_booking_before_entry_header', 10 );


if( ! function_exists( 'travel_booking_entry_content' ) ) :
/**
 * Entry Content
*/
function travel_booking_entry_content(){ ?>
    <div class="entry-content">
		<?php
			
            if( ! is_singular() && false === get_post_format() ){
                the_excerpt();
            }else{
                the_content( sprintf(
    				wp_kses(
    					/* translators: %s: Name of current post. Only visible to screen readers */
    					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'travel-booking' ),
    					array(
    						'span' => array(
    							'class' => array(),
    						),
    					)
    				),
    				get_the_title()
    			) );
    
    			wp_link_pages( array(
    				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'travel-booking' ),
    				'after'  => '</div>',
    			) );
            }
            
		?>
	</div><!-- .entry-content -->
    <?php
}
endif;
add_action( 'travel_booking_post_content', 'travel_booking_entry_content', 30 );
add_action( 'travel_booking_page_content', 'travel_booking_entry_content', 10 );

if( ! function_exists( 'travel_booking_entry_footer' ) ) :
/**
 * Entry Footer
*/
function travel_booking_entry_footer(){ ?>
	<footer class="entry-footer">
		<?php
        $readmore = get_theme_mod( 'readmore', __( 'Read More', 'travel-booking' ) );
        if( ! is_page() ){
            if( is_single() ){
                travel_booking_tags();
            }else{
                if( $readmore ) echo '<div class="btn-holder"><a href="' . esc_url( get_the_permalink() ) . '" class="primary-btn">' . esc_html( $readmore ) . '</a></div>';
            } 
        }
        
        if ( get_edit_post_link() ){
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'travel-booking' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
        }
		?>
	</footer><!-- .entry-footer -->
	<?php            
}
endif;
add_action( 'travel_booking_post_content', 'travel_booking_entry_footer', 40 );
add_action( 'travel_booking_page_content', 'travel_booking_entry_footer', 20 );

if( ! function_exists( 'travel_booking_author' ) ) :
/**
 * Author Bio
*/
function travel_booking_author(){ 
    if(  get_the_author_meta( 'description' ) ){ ?>
    <div class="author-section">
		<div class="img-holder"><?php echo get_avatar( get_the_author_meta( 'ID' ), 160 ); ?></div>
		<div class="text-holder">
			<h3><?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?></h3>				
			<?php echo wpautop( wp_kses_post( get_the_author_meta( 'description' ) ) ); ?>            
		</div>
	</div>
    <?php
    }
}
endif;
add_action( 'travel_booking_after_post_content', 'travel_booking_author', 15 );

if( ! function_exists( 'travel_booking_pagination' ) ) :
/**
 * Pagination
*/
function travel_booking_pagination(){    
    if( is_single() ){
        $previous = get_previous_post_link(
    		'<div class="nav-previous nav-holder">%link</div>',
    		'<span class="meta-nav">' . esc_html__( 'Prev Post', 'travel-booking' ) . '</span><span class="post-title">%title</span>',
    		false,
    		'',
    		'category'
    	);
    
    	$next = get_next_post_link(
    		'<div class="nav-next nav-holder">%link</div>',
    		'<span class="meta-nav">' . esc_html__( 'Next Post', 'travel-booking' ) . '</span><span class="post-title">%title</span>',
    		false,
    		'',
    		'category'
    	);
        
        if( $previous || $next ){?>            
            <nav class="navigation post-navigation" role="navigation">
    			<h2 class="screen-reader-text"><?php esc_html_e( 'Post Navigation', 'travel-booking' ); ?></h2>
    			<div class="nav-links">
    				<?php
                        if( $previous ) echo $previous;
                        if( $next ) echo $next;
                    ?>
    			</div>
    		</nav>        
            <?php
        }        
    }else{
        the_posts_pagination( array(
            'prev_text'          => __( 'Previous', 'travel-booking' ),
            'next_text'          => __( 'Next', 'travel-booking' ),
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'travel-booking' ) . ' </span>',
         ) );
    }    
}
endif;
add_action( 'travel_booking_after_post_content', 'travel_booking_pagination', 20 );
add_action( 'travel_booking_after_content', 'travel_booking_pagination' );

if( ! function_exists( 'travel_booking_related_and_recent_posts' ) ) :
/**
 * Related Posts
*/
function travel_booking_related_and_recent_posts(){
    global $post;

    $ed_related    = get_theme_mod( 'ed_related', true );
    $ed_recent     = get_theme_mod( 'ed_recent', true );
    $related_title = get_theme_mod( 'related_title', __( 'You may also like.', 'travel-booking' ) );
    $recent_title  = get_theme_mod( 'recent_title', __( 'Recent Posts', 'travel-booking' ) );
    
    if( $ed_related ){
        $args = array(
            'post_type'             => 'post',
            'post_status'           => 'publish',
            'posts_per_page'        => 4,
            'ignore_sticky_posts'   => true,
            'post__not_in'          => array( $post->ID ),
            'orderby'               => 'rand'
        );
        $cats = get_the_category( $post->ID );
        if( $cats ){
            $c = array();
            foreach( $cats as $cat ){
                $c[] = $cat->term_id; 
            }
            $args['category__in'] = $c;
        }
        
        $qry = new WP_Query( $args );
        
        if( $qry->have_posts() ){ ?>
            <section class="recent-posts-area related">
        		<?php if( $related_title ) echo '<h2 class="section-title">' . esc_html( $related_title ) . '</h2>'; ?>
        		<div class="row">
        			<?php while( $qry->have_posts() ){ $qry->the_post(); ?>

                         <article class="col">
                            <?php 
                                $image_size = 'travel-booking-related'; 

                                if( has_post_thumbnail() ){
                                    the_post_thumbnail( $image_size, array( 'itemprop'=>'image' ) );    
                                }else{ ?>
                                    <a class="post-thumbnail" href="<?php the_permalink(); ?>">
                                       <?php travel_booking_fallback_image( $image_size ); ?>
                                    </a>
                                <?php  
                                }
                            ?>            
                            <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        </article>

        			<?php }
                    wp_reset_postdata(); ?>
        		</div><!-- .row -->
        	</section>
        <?php
        }
    }

    if( $ed_recent ){
        $args = array(
            'post_type'             => 'post',
            'post_status'           => 'publish',
            'posts_per_page'        => 4,
            'ignore_sticky_posts'   => true,
            'post__not_in'          => array( $post->ID ),
            'orderby'               => 'rand'
        );
        
        $qry = new WP_Query( $args );
        
        if( $qry->have_posts() ){
        ?>
        <section class="recent-posts-area">
            <?php if( $recent_title ) echo '<h2 class="section-title">' . esc_html( $recent_title ) . '</h2>'; ?>
            <div class="row">
                <?php while( $qry->have_posts() ){ $qry->the_post(); ?>

                     <article class="col">
                        <?php 
                            $image_size = 'travel-booking-related'; 

                            if( has_post_thumbnail() ){
                                echo '<a class="post-thumbnail" href="'. esc_url( get_the_permalink() ).'">';
                                the_post_thumbnail( $image_size, array( 'itemprop'=>'image' ) );    
                                echo '</a>';
                            }else{ ?>
                                <a class="post-thumbnail" href="<?php the_permalink(); ?>">
                                    <?php travel_booking_fallback_image( $image_size ); ?>
                                </a>
                            <?php  
                            }
                        ?>            
                        <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    </article>

                <?php }
                wp_reset_postdata(); ?>
            </div><!-- .row -->
        </section>
        <?php
        }
    }
}
endif;
add_action( 'travel_booking_after_post_content', 'travel_booking_related_and_recent_posts', 25 );

if( ! function_exists( 'travel_booking_comment' ) ) :
/**
 * Page Header
*/
function travel_booking_comment(){
    // If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
}
endif;
add_action( 'travel_booking_after_post_content', 'travel_booking_comment', 30 );
add_action( 'travel_booking_after_page_content', 'travel_booking_comment' );

if( ! function_exists( 'travel_booking_content_end' ) ) :
/**
 * Content End
*/
function travel_booking_content_end(){
    $home_sections = travel_booking_get_homepage_section();
    
    if( is_404() ) return;
    
    if( ! ( is_front_page() && ! is_home() && $home_sections ) ){
    ?>
            </div><!-- .row -->
    </div><!-- #content -->
    <?php
    }
}
endif;
add_action( 'travel_booking_before_footer', 'travel_booking_content_end', 20 );

if( ! function_exists( 'travel_booking_footer_start' ) ) :
/**
 * Footer Start
*/
function travel_booking_footer_start(){
    $background_image = get_theme_mod( 'footer_bg_image', get_template_directory_uri() .'/images/bg-footer.jpg' );
    $footer_style     = '';

    if( ! empty( $background_image ) ){
        $footer_style = 'style="background: url('. esc_url( $background_image ).') no-repeat;"';
    }
    ?>
    <footer id="colophon" class="site-footer" itemscope itemtype="https://schema.org/WPFooter" <?php echo $footer_style; ?>>
    <?php
}
endif;
add_action( 'travel_booking_footer', 'travel_booking_footer_start', 20 );

if( ! function_exists( 'travel_booking_footer_top' ) ) :
/**
 * Footer Top
*/
function travel_booking_footer_top(){ 
    $footer_sidebars = array( 'footer-one', 'footer-two', 'footer-three', 'footer-four' );
    $active_sidebars = array();
    $sidebar_count   = 0;

    foreach ( $footer_sidebars as $footer_sidebar ) {
        if( is_active_sidebar( $footer_sidebar ) ){
            array_push( $active_sidebars, $footer_sidebar );
            $sidebar_count++ ;
        }
    }

    if( ! empty( $active_sidebars ) ){ ?>

    <div class="footer-t">
        <div class="container">
    		<div class="col-<?php echo esc_attr( $sidebar_count ); ?> footer-col-holder">
    			<?php 
                    foreach( $active_sidebars as $active_footer_sidebar ){
                        if( is_active_sidebar( $active_footer_sidebar ) ){
                            echo '<div class="column">';
                            dynamic_sidebar( $active_footer_sidebar );
                            echo '</div>';
                        }
                    } 
                ?>
    		</div>
        </div><!-- .container -->
	</div><!-- .footer-t -->

    <?php 
    }   
}
endif;
add_action( 'travel_booking_footer', 'travel_booking_footer_top', 30 );

if( ! function_exists( 'travel_booking_footer_bottom' ) ) :
/**
 * Footer Bottom
*/
function travel_booking_footer_bottom(){ ?>
    <div class="footer-b">
        <div class="container">
    		<span class="site-info">
    			<?php
                    travel_booking_get_footer_copyright();
                    
                    printf( esc_html__( 'Travel Booking by %1$sWP Travel Engine%2$s.', 'travel-booking' ), '<a href="' . esc_url( 'https://wptravelengine.com/' ) .'" rel="nofollow" target="_blank">','</a>' );
                    
                    /* translators: %s: poweredby */
                    printf( esc_html__( ' Powered by %s', 'travel-booking' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'travel-booking' ) ) .'" target="_blank">WordPress</a> .' );

                    if ( function_exists( 'the_privacy_policy_link' ) ) {
                        the_privacy_policy_link( '<span class="policy_link">', '</span>');
                    }                        
                     
                ?>                              
    		</span>
        </div>
	</div>
    <?php
}
endif;
add_action( 'travel_booking_footer', 'travel_booking_footer_bottom', 40 );

if( ! function_exists( 'travel_booking_footer_end' ) ) :
/**
 * Footer End 
*/
function travel_booking_footer_end(){
    ?>
    </footer><!-- #colophon -->
    <?php
}
endif;
add_action( 'travel_booking_footer', 'travel_booking_footer_end', 50 );

if( ! function_exists( 'travel_booking_page_end' ) ) :
/**
 * Page End
*/
function travel_booking_page_end(){
    ?>
    </div><!-- #page -->
    <?php
}
endif;
add_action( 'travel_booking_after_footer', 'travel_booking_page_end', 20 );

if( ! function_exists( 'travel_booking_get_popular_package' ) ) :
/**
 * Popular Packages
*/
function travel_booking_get_popular_package(){
    $ed_popular    = get_theme_mod( 'ed_404_popular', true ); 
    $ed_demo       = get_theme_mod( '404_popular_ed_demo', false ); 
    $section_title = get_theme_mod( '404_popular_text', __( 'Popular Trips', 'travel-booking' ) );
    $trip_one      = get_theme_mod( '404_popular_trip_one' ); 
    $trip_two      = get_theme_mod( '404_popular_trip_two' ); 
    $trip_three    = get_theme_mod( '404_popular_trip_three' ); 
    $trip_four     = get_theme_mod( '404_popular_trip_four' ); 
    $trip_five     = get_theme_mod( '404_popular_trip_five' ); 
    $trip_six      = get_theme_mod( '404_popular_trip_six' ); 
    $trip_array    = array( $trip_one, $trip_two, $trip_three, $trip_four, $trip_five, $trip_six );
    $trip_array    = array_diff( array_unique( $trip_array ), array('') );

    if( $ed_popular && travel_booking_is_wpte_activated() && tb_is_tbt_activated() ){
        $obj        = new Travel_Booking_Toolkit_Functions;

        $args = array( 
            'post_type'      => 'trip',
            'post_status'    => 'publish',
            'posts_per_page' => 6,
            'post__in'       => $trip_array,
            'orderby'        => 'post__in'  
        );
        $popular_qry = new WP_Query( $args );
    ?>
    <section class="popular-package">
        <div class="container">

            <?php if( ! empty( $section_title ) ){ ?>
                <header class="section-header">
                    <h1 class="section-title"><?php echo esc_html( $section_title ); ?></h1>
                </header>
            <?php } 

            if( $popular_qry->have_posts() && ! empty( $trip_array ) ){ 
                $currency = $obj->travel_booking_toolkit_get_trip_currency();
                $new_obj  = new Wp_Travel_Engine_Functions();
                ?>
                <div class="grid">
                    <?php  
                        while( $popular_qry->have_posts() ){ 
                            $popular_qry->the_post(); 
                            $code = $new_obj->trip_currency_code( get_post() );
                            $meta = get_post_meta( get_the_ID(), 'wp_travel_engine_setting', true ); ?>
                           <div class="col">
                                <div class="img-holder">
                                    <a href="<?php the_permalink(); ?>">
                                    <?php 
                                        $popular_image_size =  'travel-booking-popular-package';
                                        if( has_post_thumbnail() ){
                                            the_post_thumbnail( $popular_image_size, array( 'itemprop'=>'image' ) );    
                                        }else{ ?>
                                            <img src="<?php echo esc_url( TBT_FILE_URL . '/includes/images/popular-package-image-size.jpg' ); ?>" alt="<?php the_title_attribute(); ?>" />
                                            <?php
                                        }
                                    ?>
                                    </a>
                                    <?php 
                                        if( ( isset( $meta['trip_prev_price'] ) && $meta['trip_prev_price'] ) && ( isset( $meta['sale'] ) && $meta['sale'] ) && ( isset( $meta['trip_price'] ) && $meta['trip_price'] ) ){ 
                                            $diff = (int)( $meta['trip_prev_price'] - $meta['trip_price'] );
                                            $perc = (float)( ( $diff / $meta['trip_prev_price'] ) * 100 );  

                                            /* translators: 1: discount  */
                                            echo '<div class="discount-amount">' . sprintf( __( '%1$s&percnt; Off', 'travel-booking' ), round( $perc ) ) . '</div>';  
                                        }
                                    ?>
                                </div>
                                <div class="text-holder">
                                    <div class="price-info">
                                    <?php 
                                        $obj->travel_booking_toolkit_trip_symbol_options( get_the_ID(), $code, $currency ); 

                                        if( $obj->travel_booking_toolkit_is_wpte_gd_activated() && isset( $meta['group']['discount'] ) && isset( $meta['group']['traveler'] ) && ! empty( $meta['group']['traveler'] ) ){ ?>
                                            <span class="group-discount"><span class="tooltip"><?php esc_html_e( 'You have group discount in this trip.', 'travel-booking' ) ?></span><?php esc_html_e( 'Group Discount', 'travel-booking' ) ?></span>
                                            <?php
                                        } 
                                    ?>
                                        
                                    </div>

                                    <div class="trip-info">
                                        <?php  if( $obj->travel_booking_toolkit_is_wpte_tr_activated() ){ ?>
                                            <div class="star-holder">
                                                <?php
                                                    global $post;
                                                    $comments = get_comments( array(
                                                        'post_id' => $post->ID,
                                                        'status' => 'approve',
                                                    ) );
                                                    if ( !empty( $comments ) ){
                                                        echo '<div class="review-wrap"><div class="average-rating">';
                                                        $sum = 0;
                                                        $i = 0;
                                                        foreach($comments as $comment) {
                                                            $rating = get_comment_meta( $comment->comment_ID, 'stars', true );
                                                            $sum = $sum+$rating;
                                                            $i++;
                                                        }
                                                        $aggregate = $sum/$i;
                                                        $aggregate = round($aggregate,2);

                                                        echo '<div id="agg-rating-' . esc_attr( get_the_ID() ) . '" class="agg-rating"></div><div class="aggregate-rating">
                                                        <span class="rating-star">'.floatval( $aggregate ).'</span><span>'.absint($i).'</span> '. esc_html( _nx( 'review', 'reviews', $i, 'reviews count', 'travel-booking' ) ) .'</div>';
                                                        echo '</div></div><!-- .review-wrap -->';
                                                    }
                                                ?>  
                                            </div>
                                        <?php } ?>
                                        <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                        <div class="meta-info">
                                            <?php 
                                                $destinations = wp_get_post_terms( get_the_ID(), 'destination' );

                                                if( ! empty( $destinations ) ){
                                                    foreach ($destinations as $destination ){
                                                        echo '<span class="place"><i class="fa fa-map-marker"></i>'. esc_html( $destination->name ) .'</span>';
                                                    }
                                                }
                                            ?>
                                            <?php if( isset( $meta['trip_duration'] ) && '' != $meta['trip_duration'] ) echo '<span class="time"><i class="fa fa-clock-o"></i>' . absint( $meta['trip_duration'] ) . esc_html__( ' Days', 'travel-booking' ) . '</span>'; ?>
                                        </div>
                                    </div>
                                    <?php 
                                    if( $obj->travel_booking_toolkit_is_wpte_fsd_activated() ){ 
                                        $starting_dates = get_post_meta( get_the_ID(), 'WTE_Fixed_Starting_Dates_setting', true );

                                        if( isset( $starting_dates['departure_dates'] ) && ! empty( $starting_dates['departure_dates'] ) && isset($starting_dates['departure_dates']['sdate']) ){ ?>
                                            <div class="next-trip-info">
                                                <h3><?php esc_html_e( 'Next Departure', 'travel-booking' ); ?></h3>
                                                <ul class="next-departure-list">
                                                    <?php
                                                        $wpte_option_settings = get_option('wp_travel_engine_settings', true);
                                                        $sortable_settings    = get_post_meta( get_the_ID(), 'list_serialized', true);

                                                        if(!is_array($sortable_settings))
                                                        {
                                                          $sortable_settings = json_decode($sortable_settings);
                                                        }
                                                        $today = strtotime(date("Y-m-d"))*1000;
                                                        $i = 0;
                                                        foreach( $sortable_settings as $content )
                                                        {
                                                            $new_date = substr( $starting_dates['departure_dates']['sdate'][$content->id], 0, 7 );
                                                            if( $today <= strtotime($starting_dates['departure_dates']['sdate'][$content->id])*1000 )
                                                            {
                                                                
                                                                $num = isset($wpte_option_settings['trip_dates']['number']) ? $wpte_option_settings['trip_dates']['number']:5;
                                                                if($i < $num)
                                                                {
                                                                    if( isset( $starting_dates['departure_dates']['seats_available'][$content->id] ) )
                                                                    {
                                                                        $remaining = isset( $starting_dates['departure_dates']['seats_available'][$content->id] ) && ! empty( $starting_dates['departure_dates']['seats_available'][$content->id] ) ?  $starting_dates['departure_dates']['seats_available'][$content->id] . ' ' . __( 'spaces left', 'travel-booking' ) : __( '0 space left', 'travel-booking' );
                                                                        echo '<li><span class="left"><i class="fa fa-clock-o"></i>'. date_i18n( get_option( 'date_format' ), strtotime( $starting_dates['departure_dates']['sdate'][$content->id] ) ).'</span><span class="right">'. esc_html( $remaining) .'</span></li>';
                                                                    }
                                                                
                                                                }
                                                            $i++;
                                                            }
                                                        }
                                                    ?>
                                                </ul>
                                            </div>
                                        <?php } 
                                    }  if( ! empty( $readmore ) ){ ?>
                                        <div class="btn-holder">
                                            <a href="<?php the_permalink(); ?>" class="primary-btn"><?php echo esc_html( $readmore ); ?></a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                    <?php } ?>
                </div>
            <?php } elseif( $ed_demo ) {
                //Default 
                $defaults   = new Travel_Booking_Toolkit_Dummy_Array;
                $populars = $defaults->travel_booking_toolkit_default_trip_featured_posts(); ?>
                    <div class="grid">
                        <?php foreach( $populars as $v ){ ?>
                        <div class="col">
                            <div class="img-holder">
                                <a href="#"><img src="<?php echo esc_url( $v['img'] ); ?>" alt="<?php echo esc_attr( $v['title'] ) ?>"></a>
                                <div class="discount-amount"><?php echo esc_html( $v['discount'] ) ?></div>
                            </div>
                            <div class="text-holder">
                                <div class="price-info">
                                    <span class="price-holder">
                                        <span class="old-price"><?php echo esc_html( $v['old_price'] ) ?></span>
                                        <span class="new-price"><?php echo esc_html( $v['new_price'] ) ?></span>
                                    </span>
                                    <span class="group-discount"><span class="tooltip"><?php esc_html_e( 'You have group discount in this trip.', 'travel-booking' ) ?></span><?php esc_html_e( 'Group Discount', 'travel-booking' ) ?></span>
                                </div>
                                <div class="trip-info">
                                    <div class="star-holder"><img src="<?php echo esc_url( $v['rating'] ) ?>" alt="<?php esc_attr_e( '5 rating', 'travel-booking' ) ?>"></div>
                                    <h2 class="title"><a href="#"><?php echo esc_html( $v['title'] ) ?></a></h2>
                                    <div class="meta-info">
                                        <span class="place"><i class="fa fa-map-marker"></i><?php echo esc_html( $v['destination'] ) ?></span>
                                        <span class="time"><i class="fa fa-clock-o"></i><?php echo esc_html( $v['days'] ) ?></span>
                                    </div>
                                </div>
                                <div class="next-trip-info">
                                    <h3><?php esc_html_e( 'Next Departure', 'travel-booking' ) ?></h3>
                                    <ul class="next-departure-list">
                                        <?php 
                                            foreach ( $v['next-trip-info'] as $value ) {
                                            echo '<li>
                                                    <span class="left"><i class="fa fa-clock-o"></i>'. esc_html( $value['date'] ) .'</span>
                                                    <span class="right">'. esc_html( $value['space_left']).'</span>
                                                </li>';
                                            }
                                        ?>
                                    </ul>
                                </div>
                                <div class="btn-holder">
                                    <a href="#" class="primary-btn"><?php esc_html_e( 'View Details', 'travel-booking' ) ?></a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div><!-- .grid -->
            <?php } ?>
        </div>
    </section>
    <?php
    }
}
endif;
add_action( 'travel_booking_popular_package', 'travel_booking_get_popular_package', 10 );
