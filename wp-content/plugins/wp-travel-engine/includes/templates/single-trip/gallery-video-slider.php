<?php
/**
 * gallery popup video template.
 *
 * @package WP_Travel_Engine
 */
wp_enqueue_script( 'slick' );
wp_enqueue_style( 'slick' );
wp_enqueue_script( 'wte-video-slider-trigger' );

if ( $args['title'] ) :
	?>
	<h3><?php echo esc_html( $args['title'] ); ?></h3>
	<?php
	endif;
if ( ! empty( $args['gallery'] ) ) :
	$random = rand();
	?>
	<section class="slider-wrapper">
		<section class="main-slider">
			<?php
			foreach ( $args['gallery'] as $key => $gallery_item ) :
					$video_id    = $gallery_item['id'];
					$video_url   = 'youtube' === $gallery_item['type'] ? 'https://www.youtube.com/embed/' . $video_id . '?enablejsapi=1&controls=0&fs=0&iv_load_policy=3&rel=0&showinfo=0&loop=1' : 'https://player.vimeo.com/video/' . $video_id . '?api=1&byline=0&portrait=0&title=0&background=1&mute=1&loop=1&autoplay=0&id=' . $video_id;
					$video_thumb = $gallery_item['thumb'];
				?>
			<div class="item <?php echo esc_attr( $gallery_item['type'] ); ?>">
				<iframe class="embed-player slide-media" src="<?php echo esc_url( $video_url ); ?>" width="980" height="520" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
			</div>
			<?php endforeach; ?>
		</section>
		<section class="slider-nav">
			<?php
			foreach ( $args['gallery'] as $key => $gallery_item ) :
					$video_thumb = $gallery_item['thumb'];
				?>
				<div class="item">
					<img src="<?php echo esc_url( $video_thumb ); ?>" alt="">
				</div>
			<?php endforeach; ?>
		</section>
	</section>
	<?php
endif;
