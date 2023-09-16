<?php
/**
 * gallery popup video template.
 *
 * @package WP_Travel_Engine
 */
wp_enqueue_script( 'jquery-fancy-box' );
wp_enqueue_style( 'jquery-fancy-box' );

if ( $args['title'] ) :
	?>
		<h3><?php echo esc_html( $args['title'] ); ?></h3>
	<?php
endif;
if ( ! empty( $args['gallery'] ) ) :
	$random = rand();
	?>
	<span class="wp-travel-engine-vid-gal-popup">
		<a
			data-galtarget="#wte-video-gallary-popup-<?php echo esc_attr( $args['trip_id'] . $random ); ?>"
			data-variable="<?php echo esc_attr( 'wtevideoGallery' . $random ); ?>"
			href="#wte-video-gallary-popup-<?php echo esc_attr( $args['trip_id'] . $random ); ?>"
			class="wte-trip-vidgal-popup-trigger"><?php echo esc_html( $args['label'] ); ?></a>
	</span>
	<?php
	$slides = array();
	foreach ( $args['gallery'] as $key => $gallery_item ) :
		$video_id  = $gallery_item['id'];
		$video_url = 'youtube' === $gallery_item['type'] ? '//www.youtube.com/watch?v=' . $video_id : '//vimeo.com/' . $video_id;
		$slides[]  = array( 'src' => $video_url );
	endforeach;
	$slides = 'var ' . esc_attr( 'wtevideoGallery' . $random ) . ' = ' . wp_json_encode( $slides );
	wp_add_inline_script( 'wp-travel-engine', $slides, 'before' );
endif;
