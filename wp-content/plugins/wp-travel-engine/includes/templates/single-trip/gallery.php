<?php
/**
 * Trip gallery template.
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/gallery.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wp_enqueue_style( 'jquery-fancy-box' );
wp_enqueue_script( 'jquery-fancy-box' );
wp_enqueue_script( 'owl-carousel' );
wp_enqueue_style( 'owl-carousel' );

global $post;
global $wp_query;

$is_main_slider = isset( $is_main_slider ) && $is_main_slider;

$wpte_trip_images = get_post_meta( $post->ID, 'wpte_gallery_id', true );
?>
<div class="wpte-gallery-wrapper">
	<?php
	if ( isset( $wpte_trip_images['enable'] ) && $wpte_trip_images['enable'] == '1' ) {
		if ( isset( $wpte_trip_images ) && $wpte_trip_images != '' ) {
			unset( $wpte_trip_images['enable'] );
			?>
			<?php ob_start(); ?>
			<div class="wpte-trip-feat-img-gallery owl-carousel <?php echo esc_attr( $is_main_slider ? 'single-trip-main-carousel' : '' ); ?>">
				<?php
				$feat_img = isset( $wp_travel_engine_setting_option_setting['feat_img'] ) ? esc_attr( $wp_travel_engine_setting_option_setting['feat_img'] ) : '';
				if ( has_post_thumbnail( $post->ID ) ) {
					$gallery_image_size = apply_filters( 'wp_travel_engine_single_trip_feat_img_size', 'trip-single-size' );
					$link               = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $gallery_image_size );
					$image_alt          = get_post_meta( get_post_thumbnail_id( $post->ID ), '_wp_attachment_image_alt', true );
				}
				if ( isset( $link[0] ) ) :
					?>
						<div class="item" data-thumb="<?php echo esc_url( $link[0] ); ?>">
							<img alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" itemprop="image" src="<?php echo esc_url( $link[0] ); ?>">
						</div>
					<?php
				endif;
				foreach ( $wpte_trip_images as $image ) {
					$gallery_image_size = apply_filters( 'wp_travel_engine_trip_single_gallery_image_size', 'large' );
					$link               = wp_get_attachment_image_src( $image, $gallery_image_size );
					$image_alt          = get_post_meta( $image, '_wp_attachment_image_alt', true );
					if ( ! isset( $image_alt ) || $image_alt == '' ) {
						$image_alt = get_the_title( $image ); }

					if ( isset( $link[0] ) ) :
						?>
							<div class="item" data-thumb="<?php echo esc_url( $link[0] ); ?>">
								<img alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" itemprop="image" src="<?php echo esc_url( $link[0] ); ?>">
							</div>
						<?php
					endif;
				}
				?>
			</div>
			<?php
			$html = ob_get_clean();
			echo wp_kses_post( apply_filters( 'wpte_trip_gallery_images', $html, $wpte_trip_images ) );
		}
	} else {
		$wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', true );
		$feat_img                                = isset( $wp_travel_engine_setting_option_setting['feat_img'] ) ? esc_attr( $wp_travel_engine_setting_option_setting['feat_img'] ) : '';
		if ( isset( $feat_img ) && $feat_img != '1' ) {
			if ( has_post_thumbnail( $post->ID ) ) {
				$trip_feat_img_size = apply_filters( 'wp_travel_engine_single_trip_feat_img_size', 'trip-single-size' );
				$feat_image_url     = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $trip_feat_img_size );
				$image_alt          = get_post_meta( get_post_thumbnail_id( $post->ID ), '_wp_attachment_image_alt', true );
				if ( ! isset( $image_alt ) || $image_alt == '' ) {
					$image_alt = get_the_title( get_post_thumbnail_id( $post->ID ) ); }
				?>
				<div class="wpte-trip-feat-img">
					<img alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" itemprop="image" src="<?php echo esc_url( $feat_image_url[0] ); ?>" alt="">
				</div>
				<?php
			} else {
				?>
				<div class="wpte-trip-feat-img">
					<img alt="<?php the_title(); ?>" itemprop="image" loading="lazy" src="<?php echo esc_url( WP_TRAVEL_ENGINE_IMG_URL . '/public/css/images/single-trip-featured-img.jpg' ); ?>" alt="">
				</div>
				<?php
			}
		}
	}
	?>
	<?php if ( is_singular( 'trip' ) && isset( $args ) && ! isset( $args['related_query'] ) ) : ?>
		<div class="wpte-gallery-container">
			<?php
			global $post;
			$random                   = rand();
			$wp_travel_engine_setting = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
			$wpte_trip_images         = get_post_meta( $post->ID, 'wpte_gallery_id', true );
			if ( isset( $wpte_trip_images['enable'] ) && $wpte_trip_images['enable'] == '1' ) {
				if ( count( $wpte_trip_images ) > 1 ) {
					unset( $wpte_trip_images['enable'] );
					?>
				<span class="wp-travel-engine-image-gal-popup">
					<a
						data-galtarget="#wte-image-gallary-popup-<?php echo esc_attr( $post->ID . $random ); ?>"
						data-variable="<?php echo esc_attr( 'wteimageGallery' . $random ); ?>"
						href="#wte-image-gallary-popup-<?php echo esc_attr( $post->ID . $random ); ?>"
						class="wte-trip-image-gal-popup-trigger"><?php echo esc_html_e( 'Gallery', 'wp-travel-engine' ); ?>
					</a>
				</span>
					<?php
					$gallery_images = array_map(
						function( $image ) {
							return array( 'src' => wp_get_attachment_image_url( $image, 'large' ) );
						},
						$wpte_trip_images
					);
					?>
				<script type="text/javascript">
					jQuery(function($){
						$('.wte-trip-image-gal-popup-trigger').on( 'click', function(){
							jQuery.fn.fancybox && $.fancybox.open(<?php echo wp_json_encode( array_values( $gallery_images ) ); ?>,{
								buttons: [
									"zoom",
									"slideShow",
									"fullScreen",
									"close"
								]
							});
						});
					});
				</script>
					<?php
				}
			}
			if ( isset( $wp_travel_engine_setting['enable_video_gallery'] ) && $wp_travel_engine_setting['enable_video_gallery'] == true ) {
				echo do_shortcode( '[wte_video_gallery label="Video"]' );
			}
			?>
		</div>
	<?php endif; ?>
</div>
<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
