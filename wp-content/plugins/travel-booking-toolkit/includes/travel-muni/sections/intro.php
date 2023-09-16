<?php
/**
 * Intro Section
 * 
 * @package Travel_Muni
 */

$ed_intro           = get_theme_mod( 'ed_intro',true );
$intro_title        = get_theme_mod( 'intro_title',__( 'Create Your Travel Booking Website with Travel Muni Theme', 'travel-booking-toolkit' ) );
$intro_desc         = get_theme_mod( 'intro_desc',__( '<p>Tell a story about your company here. You can modify this section from Appearance > Customize > Home Page Settings > About Section.</p>
<p>Travel Muni is a free WordPress theme that you can use create stunning and functional travel and tour booking website. It is lightweight, responsive and SEO friendly. It is compatible with WP Travel Engine, a WordPress plugin for travel booking. </p>', 'travel-booking-toolkit' ) );
$intro_readmore     = get_theme_mod( 'intro_readmore',__( 'Know More About Us', 'travel-booking-toolkit' ) );
$intro_readmore_url = get_theme_mod( 'intro_readmore_url','#' );
$intro_tripadvisor  = get_theme_mod( 'intro_trip_advisor','<img src="' . esc_url( TBT_FILE_URL.'/images/tripadvisor.jpg' ) . '"/>' );
if( $ed_intro ){
if( $intro_title || $intro_desc || ( $intro_readmore && $intro_readmore_url ) || $intro_tripadvisor ){
?>
<section id="intro_section" class="intro-section">
    <div class="container">
        <div class="intro-desc">
            <?php if( $intro_title ){ ?>
                <h2 class="section-title"><?php echo travel_muni_get_intro_title(); ?></h2>
            <?php }if( $intro_desc || ( $intro_readmore && $intro_readmore_url ) ) ?>
            <div class="intro-desc-wrap">
                <?php 
                    if( $intro_desc ){
                        echo '<div class="intro-desc-inn-wrap">';
                        echo wp_kses_post( $intro_desc );
                        echo '</div>';
                    }
                if( $intro_readmore && $intro_readmore_url ){ ?>
                <div class="int-more-tbn">
                    <a class="btn-primary int-us-more" href="<?php echo esc_html( $intro_readmore_url ); ?>">
                        <?php echo travel_muni_get_intro_readmore(); ?> 
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php if( $intro_tripadvisor ){
            ?>
                <div class="trip-advisor-wrap">
                    <?php echo $intro_tripadvisor; ?>
                </div>
            <?php 
        } ?>
    </div>
</section>
<?php }
}