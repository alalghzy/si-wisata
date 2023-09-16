<?php
/**
 * Special Section
 * 
 * @package Travel_Muni
 */
$ed_special            = get_theme_mod( 'ed_special',true );
$special_offer_title   = get_theme_mod( 'special_offer_title', __( 'Special Offers', 'travel-booking-toolkit' ) );
$special_offer_desc    = get_theme_mod( 'special_offer_desc', __( 'The origin of the word travel is most likely lost to history.', 'travel-booking-toolkit' ) );
$special_offer_trips   = get_theme_mod( 'special_offer_trips' );
$ed_special_offer_demo = get_theme_mod( 'ed_special_offer_demo',true );
if( $ed_special && ( $special_offer_title || $special_offer_desc || $special_offer_trips ) ){
?>
<!-- Special Offer -->
<section id="special_section" class="special-offer">
    <div class="container-full">
        <div class="special-offer-main-wrap">
            <?php if( $special_offer_title || $special_offer_desc ){ ?>
                <div class="section-content-wrap algnlft">
                    <?php 
                        if( $special_offer_title ) echo '<h2 class="section-title">'.travel_muni_get_special_offer_title().'</h2>'; 
                        if( $special_offer_desc ) echo '<div class="section-desc">'.wpautop( travel_muni_get_special_offer_desc() ).'</div>'; 
                    ?>
                </div>
            <?php }if( $special_offer_trips && tbt_is_tbt_activated() ){ ?>
                <div class="special-offer-slid-wrap">
                    <div id="carousel" class="owl-carousel special-offer-slid-sc">
                        <?php 
                            $args = array(
                                'post_type'   => 'trip',
                                'post_status' => 'publish',
                                'post__in'    => $special_offer_trips,
                                'orderby'     => 'post__in',
                            );
                            $special_qry = new WP_Query( $args );  
                            if( $special_qry->have_posts() ){
                                while( $special_qry->have_posts() ){
                                    $special_qry->the_post();
                                    $wp_travel_engine_setting                = get_post_meta( get_the_ID(),'wp_travel_engine_setting',true );
                                    $wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', true ); 
                                    echo '<div class="item">';
                                    travel_muni_trip_block( get_the_ID(), $wp_travel_engine_setting );
                                    echo '</div>';
                                } wp_reset_postdata();
                            } 
                        ?>
                    </div>
                </div>
          <?php }else{
             if( $ed_special_offer_demo ){
                tbt_travel_muni_pro_demo_content( 'special' );
            }
          } ?>
        </div>
    </div>
</section><!-- Special Offer -->
<?php
}
