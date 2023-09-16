<?php
/**
* The template for displaying trips according to type.
*
* @package Wp_Travel_Engine
* @subpackage Wp_Travel_Engine/includes/templates
* @since 1.0.0
*/
get_header(); ?>
<div id="wte-crumbs">
   <?php
        do_action('wp_travel_engine_breadcrumb_holder');
    ?>
</div>
<div id="wp-travel-trip-wrapper" class="trip-content-area" itemscope itemtype="https://schema.org/ItemList">
    <div class="wp-travel-inner-wrapper">
        <div class="wp-travel-engine-archive-outer-wrap">
            <?php
                $termID = get_queried_object()->term_id; // Parent A ID
                $term_tax = get_term( $termID );
                $taxonomyName = $term_tax->taxonomy;
                $termchildren = get_term_children( $termID, $taxonomyName );
                $wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', true );

                $j = 1;

                if($termchildren) {
                    $default_posts_per_page = get_option( 'posts_per_page' );
                    $wte_trip_cat_slug = get_queried_object()->slug;
                    $wte_trip_cat_name = get_queried_object()->name; ?>
                    <div class="page-header">
                        <h1 class="page-title" itemprop="name" data-id="<?php echo esc_attr( $taxonomyName );?>"><?php echo esc_html( $wte_trip_cat_name ); ?></h1>
                        <?php 
                            $image_id = get_term_meta ( $termID, 'category-image-id', true );
                            if(isset($image_id) && $image_id !='' && isset($wp_travel_engine_setting_option_setting['tax_images']) && $wp_travel_engine_setting_option_setting['tax_images']!=''){
                                $activities_banner_size = apply_filters('wp_travel_engine_template_banner_size', 'full');
                                echo wp_get_attachment_image ( $image_id, $activities_banner_size );
                            } 
                        ?>
                    </div>
                    <?php $term_description = term_description( $termID, 'activities' ); ?>
                    <div class="parent-desc" itemprop="description"><?php echo isset( $term_description ) ?  wp_kses_post( $term_description ) : ''; ?></div>
                    <?php
                    $wte_trip_tax_post_args = array(
                        'post_type' => 'trip', // Your Post type Name that You Registered
                        'posts_per_page' => $default_posts_per_page,
                        'order' => apply_filters('wpte_activities_trips_order','ASC'),
                        'orderby' => apply_filters('wpte_activities_trips_order_by','date'),
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'activities',
                                'terms' => $termID,
                                'include_children' => false
                            )
                        )
                    );
                    $wte_trip_tax_post_qry = new WP_Query($wte_trip_tax_post_args);
                    $category = get_term_by('name', $wte_trip_cat_name, 'activities');
                    $term_act = get_term( $category->term_id, 'activities' );
                    if($wte_trip_tax_post_qry->have_posts()) : ?>
                        <div class="category-main-wrap category-grid col-3 grid <?php echo esc_attr( $termID ); ?>" data-id="<?php echo esc_attr( $wte_trip_tax_post_qry->max_num_pages );?>">
                            <?php 
                                while($wte_trip_tax_post_qry->have_posts()) :
                                    $wte_trip_tax_post_qry->the_post(); 
                                    $details = wte_get_trip_details( get_the_ID() );
                                    $details['j'] = $j;
                                    wte_get_template( 'content-grid.php', $details );
                                    $j++;                            
                                endwhile; 
                                wp_reset_postdata();
                                if( $term_act->count > $default_posts_per_page ){
                                    echo "<div class='btn-loadmore' data-query-vars='".json_encode( $wte_trip_tax_post_qry->query_vars )."' data-current-page='".esc_attr( $paged )."' data-max-page='".esc_attr( $wte_trip_tax_post_qry->max_num_pages )."'><span>".__("Load More Trips","travel-booking")."</span></div>";
                                }
                            ?>
                        </div>
                        <?php
                    endif;
                    wp_reset_query();
                        
                    foreach ($termchildren as $child) {
                        $child_term = get_term_by( 'id', $child, 'activities' ); 
                        $term_link = get_term_link( $child_term );
                        $child_term_description = term_description( $child_term, 'activities' ); ?>        
                        <h2 class="child-title" itemprop="name"><a href="<?php echo esc_url( $term_link );?>"><?php echo esc_html( $child_term->name );?></a></h2>
                        <div class="child-desc"><?php echo isset( $child_term_description ) ? wp_kses_post( $child_term_description ) : ''; ?></div>
                        <?php
                            $pageed = (get_query_var('paged')) ? get_query_var('paged') : 1;
                            $default_posts_per_page = get_option( 'posts_per_page' );
                            $my_query = new WP_Query( array(
                                'post_type' => 'trip', 
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => $taxonomyName,
                                        'terms' => $child_term->term_id,
                                        'include_children' => false
                                    )
                                ),
                                'posts_per_page' => $default_posts_per_page,
                                'order' => apply_filters('wpte_activities_trips_order','ASC'),
                                'orderby' => apply_filters('wpte_activities_trips_order_by','date'),
                                'paged'=> $pageed
                            ));
                        ?>
                        <div class="category-main-wrap category-grid col-3 grid <?php echo esc_attr( $child_term->term_id ); ?>" data-id="<?php echo esc_attr( $my_query->max_num_pages ); ?>">
                            <?php 
                                while ($my_query->have_posts()) : $my_query->the_post(); 
                                    $details = wte_get_trip_details( get_the_ID() );
                                    $details['j'] = $j;
                                    wte_get_template( 'content-grid.php', $details );
                                    $j++;
                                endwhile;
                                wp_reset_postdata();
                                wp_reset_query();
                                if( $child_term->count > $default_posts_per_page ){
                                    echo "<div class='btn-loadmore' data-query-vars='".json_encode( $my_query->query_vars )."' data-current-page='".esc_attr( $pageed )."' data-max-page='".esc_attr( $my_query->max_num_pages )."'><span>".__("Load More Trips","travel-booking")."</span></div>";
                                }
                            ?>
                        </div>
                        <?php
                    } 
                }else{
                    if(!isset(get_queried_object()->slug))
                        return;
                    $wte_trip_cat_slug = get_queried_object()->slug;
                    $wte_trip_cat_name = get_queried_object()->name;
                    $default_posts_per_page = get_option( 'posts_per_page' );
                    $category = get_term_by('slug', $wte_trip_cat_slug, 'activities');
                    $term_act = get_term( $category->term_id, 'activities' );
                    ?>
                    <div class="page-header">
                        <div id="wte-crumbs">
                            <?php
                            do_action('wp_travel_engine_beadcrumb_holder');
                            ?>
                        </div>
                        <h1 class="page-title" itemprop="name" data-id="<?php echo esc_attr( $taxonomyName );?>"><?php echo esc_html( $wte_trip_cat_name ); ?></h1>
                        <?php $image_id = get_term_meta ( $termID, 'category-image-id', true );
                        if(isset($image_id) && $image_id !='' && isset($wp_travel_engine_setting_option_setting['tax_images']) && $wp_travel_engine_setting_option_setting['tax_images']!='')
                        {
                            $activities_banner_size = apply_filters('wp_travel_engine_template_banner_size', 'full');
                            echo wp_get_attachment_image ( $image_id, $activities_banner_size );
                        } ?>
                    </div>
                    <?php $term_description = term_description( $termID, 'activities' ); ?>
                    <div class="parent-desc" itemprop="description"><?php echo isset( $term_description ) ?  wp_kses_post( $term_description ) : ''; ?></div>
                    <?php
                    $default_posts_per_page = get_option( 'posts_per_page' );
                    $wte_trip_tax_post_args = array(
                        'post_type' => 'trip', // Your Post type Name that You Registered
                        'posts_per_page' => $default_posts_per_page,
                        'order' => apply_filters('wpte_activities_trips_order','ASC'),
                        'orderby' => apply_filters('wpte_activities_trips_order_by','date'),
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'activities',
                                'terms' => $termID
                            )
                        )
                    );
                    $wte_trip_tax_post_qry = new WP_Query($wte_trip_tax_post_args);
                    global $post;
                    if($wte_trip_tax_post_qry->have_posts()) : ?>
                        <div class="category-main-wrap category-grid col-3 grid <?php echo esc_attr( $termID );?>" data-id="<?php echo esc_attr( $wte_trip_tax_post_qry->max_num_pages ); ?>">
                            <?php
                                while($wte_trip_tax_post_qry->have_posts()) :
                                    $wte_trip_tax_post_qry->the_post(); 
                                    $details = wte_get_trip_details( get_the_ID() );
                                    $details['j'] = $j;
                                    wte_get_template( 'content-grid.php', $details );
                                    $j++;                                
                                endwhile;
                                wp_reset_postdata();
                                if( $term_act->count > $default_posts_per_page ){
                                    echo "<div class='btn-loadmore' data-query-vars='".json_encode( $wte_trip_tax_post_qry->query_vars )."' data-current-page='".esc_attr( $paged )."' data-max-page='".esc_attr( $wte_trip_tax_post_qry->max_num_pages )."'><span>".__("Load More Trips","travel-booking")."</span></div>";
                                }
                            ?>
                        </div>
                        <?php
                    endif;
                    wp_reset_query();
                } 
            ?>
            <div id="loader" style="display: none">
                <div class="table">
                    <div class="table-row">
                        <div class="table-cell">
                            <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
get_footer();