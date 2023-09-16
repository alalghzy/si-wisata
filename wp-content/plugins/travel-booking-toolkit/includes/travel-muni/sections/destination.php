<?php
/**
 * Destination Section
 * 
 * @package Travel_Muni
 */
$ed_destination              = get_theme_mod( 'ed_destination',true );
$destination_title           = get_theme_mod( 'destination_title', __( 'Top Destinations', 'travel-booking-toolkit' ) );
$destination_desc            = get_theme_mod( 'destination_desc', __( 'For the Tours in Nepal, Trekking in Nepal, Holidays and Air Ticketing. We offer and we are committed to making your time in Nepal.', 'travel-booking-toolkit' ) );
$top_destinations            = get_theme_mod( 'top_destinations' );
$destination_more_label      = get_theme_mod( 'destination_more_label',__( '28+ Top Destinations','travel-booking-toolkit' ) );
$destination_view_more_label = get_theme_mod( 'destination_view_more_label',__( 'View All','travel-booking-toolkit' ) );
$destination_view_more_link  = get_theme_mod( 'destination_view_more_link','#' );
$ed_destination_demo         = get_theme_mod( 'ed_destination_demo',true );
if( $ed_destination && ( $destination_title || $destination_desc || $top_destinations ) ){ ?>
<!-- Top Destinations -->
<section id="destination_section" class="top-destination">
    <?php if( $destination_title || $destination_desc ){ ?>
        <div class="container">
            <div class="section-content-wrap algnlft">
                <?php 
                    if( $destination_title ) echo '<h2 class="section-title">'. esc_html( travel_muni_get_destination_title() ) .'</h2>'; 
                    if( $destination_desc ) echo '<div class="section-desc">'.wpautop( travel_muni_get_destination_desc() ).'</div>';
                ?>
            </div>
        </div>
    <?php }if( $top_destinations && travel_muni_is_wpte_activated() ){ 
        $first_destination  = array_slice( $top_destinations, 0, 1 );
        $others_destination = array_slice( $top_destinations, 1 ); ?>
        <div class="container-full">
            <div class="destination-wrap">
                <?php if( $first_destination ){
                    $terms = get_terms(  array(
                        'taxonomy'   => 'destination',
                        'hide_empty' => false,
                        'include'    => $first_destination
                    ) );
                    if( !empty( $terms ) && ! is_wp_error( $terms ) ){
                        $term_name  = !empty( $terms[0]->name ) ? $terms[0]->name : '';
                        $term_count = !empty( $terms[0]->count ) ? $terms[0]->count : '';
                        $term_link  = get_term_link( $terms[0] );
                        $image_id   = get_term_meta ( $terms[0]->term_id, 'category-image-id', true ); ?>
                        <div class="large-desti-item">
                            <div class="desti-single-wrap">
                                <?php 
                                if( isset( $image_id ) && $image_id !='' ){
                                    echo '<a href="' . esc_url( $term_link ) . '">';
                                    echo '<div class="desti-single-img-wrap">';
                                    echo wp_get_attachment_image ( $image_id, 'travel-muni-middle-square-thumb', false, array( 'itemprop' => 'image' ) );
                                    echo "</div>";
                                    echo "</a>";

                                } ?>
                                <?php if( $term_name || $term_count ){ ?>
                                    <div class="desti-single-desc">
                                        <h3 class="desti-single-title">
                                             <a href="<?php echo esc_url( $term_link ); ?>">
                                                <?php if( $term_name ) echo '<strong>'.esc_html( $term_name ).'</strong>'; ?>
                                                <?php if( $term_count ){ ?>
                                                    <span>
                                                        <?php printf( _nx( '(%s tour)', '(%s tours)', $term_count, 'tours', 'travel-booking-toolkit' ), absint( $term_count ) ); ?>
                                                    </span>
                                                <?php } ?>
                                            </a>
                                        </h3>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php }
                    }
                ?>
                <div class="desti-small-wrap">
                    <?php if( $others_destination ){ 
                            $other_terms = get_terms(  array(
                            'taxonomy'   => 'destination',
                            'hide_empty' => false,
                            'include'    => $others_destination
                        ) );
                        if( !empty( $other_terms ) && ! is_wp_error( $other_terms ) ){
                            foreach( $other_terms as $other_term ){
                                $term_name  = !empty( $other_term->name ) ? $other_term->name : '';
                                $term_count = !empty( $other_term->count ) ? $other_term->count : '';
                                $term_link  = get_term_link( $other_term );
                                $image_id   = get_term_meta ( $other_term->term_id, 'category-image-id', true );
                                ?>
                                    <div class="desti-single-wrap">
                                       <?php 
                                        echo '<a href="' . esc_url( $term_link ) . '">';
                                        echo '<div class="desti-single-img-wrap">';
                                            if( isset( $image_id ) && $image_id !='' ){
                                                echo wp_get_attachment_image ( $image_id, 'travel-muni-middle-square-thumb', false, array( 'itemprop' => 'image' ) );
                                            }else{
                                                travel_muni_get_fallback_svg( 'travel-muni-middle-square-thumb' );
                                            } 
                                        echo "</div>";
                                        echo "</a>";
                                        if( $term_name || $term_count ){ ?>
                                            <div class="desti-single-desc">
                                                <h3 class="desti-single-title">
                                                     <a href="<?php echo esc_url( $term_link ); ?>">
                                                        <?php if( $term_name ) echo '<strong>'.esc_html( $term_name ).'</strong>'; ?>
                                                        <?php if( $term_count ){ ?>
                                                            <span>
                                                                <?php printf( _nx( '(%s tour)', '(%s tours)', $term_count, 'tours', 'travel-booking-toolkit' ), absint( $term_count ) ); ?>
                                                            </span>
                                                        <?php } ?>
                                                    </a>
                                                </h3>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php 
                            }
                        }
                    }
                    if( $destination_more_label || ( $destination_view_more_label && $destination_view_more_link ) ){ ?>
                        <div class="desti-single-wrap">
                            <div class="last-desti-single-item">
                                <?php 
                                    if( $destination_more_label || ( $destination_view_more_label && $destination_view_more_link ) ){
                                        echo '<div class="btn-book">';
                                        if( $destination_more_label ) echo '<h4>'.travel_muni_get_destination_more_label().'</h4>';
                                        echo '<a class="btn-primary" href="'.esc_url( $destination_view_more_link ).'">'.travel_muni_get_destination_view_more_label().'</a>';
                                        echo '</div>';
                                    }     
                               ?>
                            </div>
                        </div>
                    <?php } ?>
                    
                </div>
            </div>
        </div>
    <?php }else{
        if( travel_muni_is_tbt_activated() && $ed_destination_demo ){
            tbt_travel_muni_pro_demo_content( 'destination' );
        }
    } ?>
</section> <!-- Top Destinations -->
<?php
}