<?php
/**
 * Popular Section
 * 
 * @package Travel_Muni
 */
$ed_popular              = get_theme_mod( 'ed_popular',true );
$popular_title           = get_theme_mod( 'popular_title', __( 'Popular Trips', 'travel-booking-toolkit' ) );
$popular_desc            = get_theme_mod( 'popular_desc', __( 'The origin of the word travel is most likely lost to history. The term travel may originate from the Old French word travail.', 'travel-booking-toolkit' ) );
$popular_trips           = get_theme_mod( 'popular_trips' );
$popular_view_more_label = get_theme_mod( 'popular_view_more_label',__( 'View More Trips','travel-booking-toolkit' ) );
$popular_view_more_link  = get_theme_mod( 'popular_view_more_link','#' );
$ed_popular_demo         = get_theme_mod( 'ed_popular_demo',true );
if( $ed_popular && ( $popular_title || $popular_desc || $popular_trips || $popular_view_more_label || $popular_view_more_link ) ){
?>   
<!-- Popular Trips -->
<section id="popular_section" class="popular-trips">
    <div class="container">
        <?php if( $popular_title || $popular_desc ){ ?>
            <div class="section-content-wrap algnlft">
                <?php
                    if( $popular_title ) echo '<h2 class="section-title">'.travel_muni_get_popular_title().'</h2>';
                    if( $popular_desc ) echo '<div class="section-desc">'.wpautop( travel_muni_get_popular_desc() ).'</div>';
                ?>
            </div>
        <?php } ?>
        <?php if( $popular_trips && travel_muni_is_wpte_activated() ){ ?>
            <div class="popular-trips-wrap">
                <?php 
                    $args = array(
                        'post_type'      => 'trip',
                        'post_status'    => 'publish',
                        'post__in'       => $popular_trips,
                        'orderby'        => 'post__in',
                        'posts_per_page' => -1,
                    );
                    $popular_qry = new WP_Query( $args );  
                    if( $popular_qry->have_posts() ){
                        while( $popular_qry->have_posts() ){
                            $popular_qry->the_post();
                            $wp_travel_engine_setting = get_post_meta( get_the_ID(),'wp_travel_engine_setting',true );
                            travel_muni_trip_block( get_the_ID(), $wp_travel_engine_setting );
                        } 
                        wp_reset_postdata();
                    } 
                ?>
            </div>
        <?php }else{
             if( travel_muni_is_tbt_activated() && $ed_popular_demo ){
                tbt_travel_muni_pro_demo_content( 'popular' );
            }
        } ?>
        <?php if( $popular_view_more_label && $popular_view_more_link ){ ?>
            <div class="loadmore-btn popular-trips-loadmore">
                <a href="<?php echo esc_url( $popular_view_more_link ); ?>" class="btn-primary load-more"><?php echo travel_muni_get_popular_view_more_label(); ?></a>
            </div>
        <?php } ?>
    </div>
</section><!-- Popular Trips -->
<?php 
}
