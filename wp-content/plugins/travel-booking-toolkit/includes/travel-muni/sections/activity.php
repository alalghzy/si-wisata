<?php
/**
 * Activity Section
 * 
 * @package Travel_Muni
 */
$ed_activities      = get_theme_mod( 'ed_activities',true );
$activities_title   = get_theme_mod( 'activities_title', __( 'Category', 'travel-booking-toolkit' ) );
$activities_desc    = get_theme_mod( 'activities_desc', __( 'The origin of the word travel is most likely lost to history. The term travel may originate from the Old French word travail.', 'travel-booking-toolkit' ) );
$top_activities     = get_theme_mod( 'top_activities' );
$ed_activities_demo = get_theme_mod( 'ed_activities_demo',true );
if( $ed_activities && ( $activities_title || $activities_desc || $top_activities ) ){
?>
<!-- Activity Categories -->
<section id="activity_section" class="activity-category">
    <div class="container">
        <?php if( $activities_title || $activities_desc ){ ?>
        <div class="section-content-wrap algnlft">
            <?php 
                if( $activities_title ) echo '<h2 class="section-title">'.travel_muni_get_activities_title().'</h2>';
                if( $activities_desc ) echo '<div class="section-desc">'.wpautop( travel_muni_get_activities_desc() ).'</div>'; 
            ?>
        </div>
        <?php } ?>
    </div>
    <?php if( $top_activities && travel_muni_is_wpte_activated() ){ ?>
        <div class="container-stretch">
            <div class="activity-category-wrap">
                <?php 
                    $activity_lists = get_terms(  array(
                            'taxonomy'   => 'activities',
                            'hide_empty' => false,
                            'include'    => $top_activities
                    ) );
                    if( !empty( $activity_lists ) && ! is_wp_error( $activity_lists ) ){
                        foreach( $activity_lists as $activity ){
                        $term_name  = !empty( $activity->name ) ? $activity->name : '';
                        $term_count = !empty( $activity->count ) ? $activity->count : '';
                        $image_id   = get_term_meta ( $activity->term_id, 'category-image-id', true ); ?>
                            <div class="activity-category-single">
                                <figure>
                                    <?php 
                                        echo '<a class="activity-category-image-wrap" href="'.esc_url( get_term_link( $activity->term_id ) ).'">';
                                            if( isset( $image_id ) && $image_id !='' ){
                                                echo wp_get_attachment_image ( $image_id, 'travel-muni-activity-thumb-size', false, array( 'itemprop' => 'image' ) );
                                            }else{
                                                 travel_muni_get_fallback_svg( 'travel-muni-activity-thumb-size' );
                                            } 
                                        echo "</a>";
                                    ?>
                                    <div class="activity-category-title">
                                        <h3>
                                            <a href="<?php echo esc_url( get_term_link( $activity->term_id ) ); ?>"><?php echo esc_html( $term_name ) ?> 
                                            <?php if( $term_count ){ ?>
                                                <span>
                                                    <?php printf( _nx( '( %s Tour )', '( %s Tours )', $term_count, 'Tours', 'travel-booking-toolkit' ), $term_count ); ?>
                                                </span>
                                            <?php } ?>
                                            </a>
                                        </h3>
                                    </div>
                                </figure>
                            </div>
                        <?php
                        }
                    }
                ?>
            </div>
        </div>
    <?php }else{
        if( $ed_activities_demo ){
            tbt_travel_muni_pro_demo_content( 'activity' );
        }
    } ?>
</section><!-- Activity Categories -->
<?php 
}