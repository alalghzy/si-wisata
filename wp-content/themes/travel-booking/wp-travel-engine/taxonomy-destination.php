<?php
/**
* The template for displaying trips archive page
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
            <div class="details">
                <?php
                    $wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', true );  
                    $obj = new Wp_Travel_Engine_Functions();              
                    $termID = get_queried_object()->term_id; // Parent A ID
                    $term_tax = get_term( $termID );
                    $taxonomyName = $term_tax->taxonomy;
                    $terms = get_terms('activities');
                    $act_terms = array();
                    $count = '';
                    $j = 1;
                    if ( !empty( $terms ) && !is_wp_error( $terms ) ){
                        foreach ( $terms as $t ) {
                            $act_terms[] = $t->term_id;
                        }
                    } 

                    $orders = apply_filters('wpte_activities_terms_order','ASC');
                    $ordersby = apply_filters('wpte_activities_terms_order_by','date');
                    $terms = get_terms('activities', array('orderby' => $ordersby, 'order' => $orders));
                    $wte_trip_cat_slug = get_queried_object()->slug;
                    $wte_trip_cat_name = get_queried_object()->name; ?>
                    <div class="page-header">
                        <div id="wte-crumbs">
                            <?php do_action('wp_travel_engine_beadcrumb_holder'); ?>
                        </div>
                        <h1 class="page-title" itemprop="name">
                            <?php echo esc_html( $wte_trip_cat_name ); ?>
                        </h1>
                        <?php 
                            $image_id = get_term_meta ( $termID, 'category-image-id', true );
                            if(isset($image_id) && $image_id !='' && isset($wp_travel_engine_setting_option_setting['tax_images']) && $wp_travel_engine_setting_option_setting['tax_images']!='' ){
                                $destination_banner_size = apply_filters('wp_travel_engine_template_banner_size', 'full');
                                echo wp_get_attachment_image ( $image_id, $destination_banner_size );
                            } 
                        ?>
                    </div>
                    <?php $term_description = term_description( $termID, 'destination' ); ?>
                    <div class="parent-desc" itemprop="description">
                        <p>
                            <?php echo isset( $term_description ) ?  wp_kses_post( $term_description ) : ''; ?>
                        </p>
                    </div>
                    <?php
                        $default_posts_per_page = get_option( 'posts_per_page' );
                        $wte_trip_cat_slug = get_queried_object()->slug;
                        if( isset($terms) && $terms!='' && is_array($terms) ) {
                            foreach( $terms as $t ) {
                                $args = array(
                                    'post_type'      => 'trip',
                                    'order'          => apply_filters('wpte_destination_trips_order','ASC'),
                                    'orderby'        => apply_filters('wpte_destination_trips_order_by','date'),
                                    'post_status'    => 'publish',
                                    'posts_per_page' => $default_posts_per_page,
                                    'tax_query'      => array(
                                        'relation' => 'AND',
                                        array(
                                            'taxonomy' => $taxonomyName,
                                            'field'    => 'slug',
                                            'terms'    => $wte_trip_cat_slug
                                        ),
                                        array(
                                            'taxonomy' => 'activities',
                                            'field'    => 'slug',
                                            'terms'    => array( $t->slug )
                                        )
                                    )
                                );
                                $my_query = new WP_Query($args);
                                $count = $my_query->found_posts;
                                if ($my_query->have_posts()) { ?>
                                    <h2 class="activity-title"><span><?php echo esc_html($t->name);?></span></h2>
                                    <div class="wrap">
                                        <div class="child-desc">
                                            <p>
                                                <?php echo html_entity_decode(term_description( $t->term_id, 'activities' ));?>
                                            </p>
                                        </div>
                                        <div class="category-main-wrap category-grid col-3 grid <?php echo esc_attr($t->term_id);?>" data-id="<?php echo esc_attr( $my_query->max_num_pages ); ?>">
                                            <?php
                                                while ($my_query->have_posts()) : $my_query->the_post(); 
                                                    $details = wte_get_trip_details( get_the_ID() ); 
                                                    $details['j'] = $j;   
                                                    wte_get_template( 'content-grid.php', $details );
                                                    $j++;
                                                endwhile;
                                                if( $count > $default_posts_per_page ){
                                                    echo "<div class='load-destination' data-query-vars='".json_encode( $my_query->query_vars )."' data-current-page='".esc_attr( $paged )."' data-max-page='".esc_attr( $my_query->max_num_pages )."'><span>".__("Load More Trips","travel-booking")."</span></div>";
                                                }
                                                wp_reset_postdata();
                                                wp_reset_query();
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                } // END if have_posts loop
                            //end
                            }
                        }
                
                        $args = array(
                            'post_type'      => 'trip',
                            'order'          => apply_filters('wpte_destination_trips_order','ASC'),
                            'orderby'        => apply_filters('wpte_destination_trips_order_by','date'),
                            'post_status'    => 'publish',
                            'posts_per_page' => $default_posts_per_page,
                            'tax_query'      => array(
                                'relation' => 'AND',
                                array(
                                    'taxonomy' => $taxonomyName,
                                    'field'    => 'slug',
                                    'terms'    => $wte_trip_cat_slug
                                ),
                                array(
                                    'taxonomy' => 'activities',
                                    'field'    => 'term_id',
                                    'terms'    => $act_terms,
                                    'operator' => 'NOT IN'
                                )
                            )
                        );
                        $others_query = new WP_Query($args);
                        if ($others_query->have_posts()) { ?>
                            <h2 class="activity-title"><span><?php 
                            $other_trips = apply_filters('wp_travel_engine_other_trips_title', __('Other Trips','travel-booking') ); 
                            echo esc_html($other_trips);
                            ?></span></h2>
                            <div class="wrap">
                                <div class="child-desc">
                                    <p>
                                        <?php $other_trips_desc = apply_filters('wp_travel_engine_other_trips_desc',__('These are other trips.','travel-booking') ); 
                                        echo esc_html($other_trips_desc);
                                        ?>
                                    </p>
                                </div>
                                <div class="category-main-wrap category-grid col-3 grid other" data-id="<?php echo esc_attr( $others_query->max_num_pages ); ?>">
                                    <?php
                                        while ($others_query->have_posts()) : $others_query->the_post(); 
                                            $details = wte_get_trip_details( get_the_ID() );
                                            $details['j'] = $j;
                                            wte_get_template( 'content-grid.php', $details );
                                            $j++;                                    
                                        endwhile;
                                        wp_reset_postdata(); 
                                        wp_reset_query();
                                        if( $others_query->found_posts > $default_posts_per_page ){
                                            echo "<div class='load-destination' data-query-vars='".json_encode( $others_query->query_vars )."' data-current-page='".esc_attr( $paged )."' data-max-page='".esc_attr( $others_query->max_num_pages )."'><span>".__("Load More Trips","travel-booking")."</span></div>";
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                        } // END if have_posts loop
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
</div>
<?php 
get_footer();