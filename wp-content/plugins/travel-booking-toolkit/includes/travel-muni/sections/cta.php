<?php
/**
 * CTA Section
 * 
 * @package Travel_Muni
 */
$ed_cta       = get_theme_mod( 'ed_cta',true );
$cta_title    = get_theme_mod( 'cta_title',__( 'Why Book With Us', 'travel-booking-toolkit' ) ) ;
$cta_desc     = get_theme_mod( 'cta_desc',__( 'Let your visitors know why they should trust you. You can modify this section from Appearance > Customize > Home Page Settings > Why Book with Us.', 'travel-booking-toolkit' ) );
$cta_bg_image = get_theme_mod( 'cta_bg_image', TBT_FILE_URL . '/images/cta_bg.jpg' );
if( $cta_bg_image ){
    $bg = ' style="background-image: url(' . esc_url( $cta_bg_image ) . '); background-repeat:no-repeat"';
}else{
    $bg = '';
}

if( $ed_cta && ( $cta_title || $cta_desc ) ){ ?>
<!-- CTA Offer -->
<section id="cta_section" class="cta-section">
    <div class="cta-section-main-wrap"<?php echo $bg; ?>>
        <div class="container">
            <?php 
                if( $cta_title ) echo '<h2 class="section-title">'.esc_html($cta_title).'</h2>'; 
                if( $cta_desc ) echo '<div class="section-desc">'.wpautop( wp_kses_post( $cta_desc ) ).'</div>'; 
            ?>
        </div>
    </div>
</section><!-- CTA Offer -->
<?php 
}