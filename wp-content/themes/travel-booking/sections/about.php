<?php
/**
 * About Section
 * 
 * @package Travel_Booking
*/

$readmore_label = get_theme_mod( 'about_widget_readmore_text', __( 'Read More', 'travel-booking' ) );
$readmore_url   = get_theme_mod( 'about_widget_readmore_link', '#' );

?>
<!-- welcom section -->
<section id="about-section" class="intro-section">
	<div class="container">
		<div class="grid">
			<?php dynamic_sidebar( 'about' ); ?>
		</div>

		<?php if( ! empty( $readmore_label ) && ! empty( $readmore_url ) ){ ?>
			<div class="btn-holder">
				<a href="<?php echo esc_url( $readmore_url ); ?>" class="primary-btn btn-readmore"><?php echo esc_html( $readmore_label ); ?></a>
			</div>
		<?php } ?>
	</div>
</section>