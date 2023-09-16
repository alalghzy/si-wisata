<?php
/**
 * Testimonials Section
 * 
 * @package Travel_Muni
 */
$ed_testimonial           = get_theme_mod( 'ed_testimonial',true );
$testimonial_title        = get_theme_mod( 'testimonial_title',__( 'Clients Testimonials', 'travel-booking-toolkit' ) );
$testimonial_desc         = get_theme_mod( 'testimonial_desc',__( 'The origin of the word travel is most likely lost to history. The term travel may originate from the old french word travail.', 'travel-booking-toolkit' ) );
$button_label             = get_theme_mod( 'testimonial_section_btn_label', __( 'Read More Reviews', 'travel-booking-toolkit' ) );
$button_url               = get_theme_mod( 'testimonial_section_btn_url', '#' );

$testimonial_review_one   = get_theme_mod( 'testimonial_review_one' );
$testimonial_review_two   = get_theme_mod( 'testimonial_review_two' );
$testimonial_review_three = get_theme_mod( 'testimonial_review_three' );

$testimonial_review_ids   = array( $testimonial_review_one, $testimonial_review_two, $testimonial_review_three );
$testimonial_review_ids   = array_diff( array_unique( $testimonial_review_ids ), array('') );
$ed_testimonial_demo      = get_theme_mod( 'ed_testimonial_demo',true );

if( $ed_testimonial && ( $testimonial_title || $testimonial_review_ids ) ){
?>
<!-- Clients Testimonials -->
<section id="testimonials_section" class="clients-testimonial">
    <div class="container">
        <div class="section-content-wrap algnlft">
            <?php 
                if( $testimonial_title ) echo '<h2 class="section-title">'.travel_muni_get_testimonial_title().'</h2>';
                if( $testimonial_desc ) echo '<div class="section-desc">'. wpautop( wp_kses_post( $testimonial_desc ) ) .'</div>';
            ?>
            <?php if(  $testimonial_review_ids ){ ?>
                <div class="section-desc">
                    <div class="ratingndrev">
                        <?php do_action( 'wte_company_average_rating_star' );  ?>
                        <?php do_action( 'wte_company_average_rating_based_on_text' ); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="container-full">
    	<?php 
            if( ! empty( $testimonial_review_ids ) && travel_booking_toolkit_is_wte_trip_review_activated() ){ 
                $comment_args = array( 
                    'post_type'   => 'trip',
                    'status'      => 'approve',
                    'comment__in' => $testimonial_review_ids,
                    'orderby'     => 'comment__in',
                );
                $comments_qry = new WP_Comment_Query( $comment_args );
                if( ! empty( $comments_qry ) ){ ?>
                    <div class="clients-testimonial-wrap">
                        <?php 
                            $comments = $comments_qry->comments;
                                if( $comments ){
                                    $comment_object = new Wte_Trip_Review_Init();
                                    foreach ( $comments as $comment ){
                                        $commentphotoid = get_comment_meta( $comment->comment_ID,'photo',true );
                                        $commenttitle   = get_comment_meta( $comment->comment_ID,'title',true );
                                        $commentstars   = get_comment_meta( $comment->comment_ID,'stars',true );
                                        $gallery_images = get_comment_meta( $comment->comment_ID,'gallery_images',true );
                                        $commentcontent = !empty( $comment->comment_content ) ? $comment->comment_content : '';
                                    ?>
                                        <div class="clients-testimon-singl">
                                            <div class="clients-testi-inner-wrap">
                                                <?php //comment title
                                                    if( $commenttitle ){
                                                        echo '<h3 class="clients-testi-title">'.esc_html( $commenttitle ).'</h3>';
                                                    } 
                                                ?>
                                                <div class="clients-testi-trip">
                                                    <?php do_action( 'comment_reviewed_tour', $comment ); ?>
                                                </div>
                                                <?php if( $commentcontent ){ ?>
                                                    <div class="clients-testi-desc"><?php echo wp_kses_post( $commentcontent ); ?></div>
                                                <?php } ?>
                                                <div class="client-intro-sc">
                                                    <?php 
                                                    if( ! empty( $commentphotoid ) ){ ?>
                                                        <div class="client-dp">
                                                            <?php echo wp_get_attachment_image( $commentphotoid, 'thumbnail' ); ?>
                                                        </div>
                                                    <?php }else{ 
                                                        ?>
                                                        <div class="client-dp">
                                                            <?php echo get_avatar( $comment->comment_author_email, 83 ); ?>
                                                        </div>
                                                    <?php }
                                                    ?>
                                                    <div class="client-intro-rght">
                                                    <?php
                                                        //comment rating
                                                        do_action('comment_rating', $comment); 
                                                        
                                                        //Comment Meta Author
                                                        do_action('comment_meta_author', $comment);
                                                        //Client location
                                                        do_action('comment_client_location', $comment);
                                                        ?>
                                                    </div>
                                                </div>
                                                <?php if( $gallery_images ){ ?>
                                                    <div class="clients-testi-single-gall">
                                                        <?php do_action( 'comment_meta_gallery', $comment ); ?>
                                                    </div>
                                                <?php } ?>
                                                <div class="clients-testi-single-dateexp">
                                                    <?php do_action( 'comment_experience_date', $comment ); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php   
                                }
                            }
                        ?>
                    </div>
                <?php } if( $button_label && $button_url ){ ?>
                    <div class="loadmore-btn clients-testi-loadmore">
                        <a class="btn-primary load-more" href="<?php echo esc_url( $button_url ); ?>">
                            <?php echo esc_html( $button_label ); ?>   
                        </a>
                    </div>
                <?php } ?>
        <?php }else{
             if( $ed_testimonial_demo ){
                tbt_travel_muni_pro_demo_content( 'testimonials' );
            }
        } ?>
    </div>
</section><!-- Clients Testimonials -->
<?php
}
