<?php
/**
 * Gallery Template
 *
 * @package Wp_Travel_Engine
 */
global $post;
// Get post ID.
if ( ! is_object( $post ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	$post_id  = $args['post_id'];
	$next_tab = $args['next_tab'];
} else {
	$post_id = $post->ID;
}
// Get settings meta.
$image_gallery        = get_post_meta( $post_id, 'wpte_gallery_id', true );
$enable_image_gallery = isset( $image_gallery['enable'] ) && '1' == $image_gallery['enable'];
$image_ids            = array();

if ( ! empty( $image_gallery ) ) {
	if ( isset( $image_gallery['enable'] ) ) {
		unset( $image_gallery['enable'] );
	}
	$image_ids = $image_gallery;
}

$wp_travel_engine_setting = get_post_meta( $post_id, 'wp_travel_engine_setting', true );

// Video Gallery settings
$enable_video_gallery = isset( $wp_travel_engine_setting['enable_video_gallery'] );
?>
<div class="wpte-onoff-block">
	<a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $enable_image_gallery ? 'active' : ''; ?>">
		<label for="wpte-enable-image-gallery" class="wpte-field-label"><?php esc_html_e( 'Enable Image Gallery', 'wp-travel-engine' ); ?><span class="wpte-onoff-btn"></span></label>
	</a>
	<input type="hidden" name="wpte_gallery_id[enable]" value="0" >
	<input id="wpte-enable-image-gallery" type="checkbox" <?php checked( $enable_image_gallery, true ); ?> name="wpte_gallery_id[enable]" value="1">
	<span class="wpte-tooltip"><?php esc_html_e( 'Upload images for the gallery. Recommended image size is 990 x 490 pixels.', 'wp-travel-engine' ); ?> </span>
	<div class="wpte-onoff-popup" <?php echo $enable_image_gallery ? 'style=display:block' : ''; ?>>
		<div class="wpte-gallery">
			<?php
			foreach ( $image_ids as $key => $id ) :
						$image_prev = wp_get_attachment_image_url( $id, 'thumbnail' );
				?>
			<!-- Image repeater -->
			<div class="wpte-gal-img">
				<input type="hidden" value="<?php echo esc_attr( $id ); ?>" readonly name="wpte_gallery_id[<?php echo esc_attr( $key ); ?>]">
				<img src="<?php echo esc_url( $image_prev ); ?>" alt="">
				<div class="wpte-gal-btns">
					<button data-uploader-button-text="<?php esc_attr_e( 'Replace Image', 'wp-travel-engine' ); ?>" data-uploader-title="<?php esc_attr_e( 'Upload new image', 'wp-travel-engine' ); ?>" class="wpte-change wpte-change-gal-img"></button>
					<button class="wpte-delete wpte-delete-gal-img"></button>
				</div>
			</div>
			<?php endforeach; ?>
			<div id="wpte-gal-img-upldr-btn" class="wpte-img-uploader">
				<button data-uploader-button-text="<?php esc_attr_e( 'Add Image(s)', 'wp-travel-engine' ); ?>" data-uploader-title="<?php esc_attr_e( 'Upload gallery images to trip', 'wp-travel-engine' ); ?>" class="wpte-upload-btn wpte-add-gallery-img"><?php esc_html_e( 'Add New Image', 'wp-travel-engine' ); ?></button>
				<span class="wpte-tooltip"><?php printf( esc_html__( 'Max. file size %1$s Supports: JPG,PNG images', 'wp-travel-engine' ), '5MB' ); ?></span>
			</div>

		</div>
	</div>
</div>

<div class="wpte-onoff-block">
	<a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $enable_video_gallery ? 'active' : ''; ?>">
		<label for="wpte-enable-video-gallery" class="wpte-field-label"><?php esc_html_e( 'Enable Video Gallery', 'wp-travel-engine' ); ?><span class="wpte-onoff-btn"></span></label>
	</a>
	<input id="wpte-enable-video-gallery" type="checkbox" <?php checked( $enable_video_gallery, true ); ?> name="wp_travel_engine_setting[enable_video_gallery]" value="true">
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter YouTube or Vimeo URL.', 'wp-travel-engine' ); ?></span>
	<div class="wpte-onoff-popup" <?php echo $enable_video_gallery ? 'style=display:block' : ''; ?>>
		<div class="wpte-field wpte-url">
			<input id="wte-trip-vid-url" type="text" placeholder="<?php esc_html_e( 'Enter youtube/vimeo video URL', 'wp-travel-engine' ); ?>">
			<button class="wp-travel-engine-trip-video-gallery-add-video"><?php esc_html_e( 'Add Video', 'wp-travel-engine' ); ?></button>
		</div>
		<ul class="wp-travel-engine-trip-video-gallery wte-video-list-srtable">
			<?php
					$wpte_vid_gallery = get_post_meta( $post_id, 'wpte_vid_gallery', true );
			if ( ! empty( $wpte_vid_gallery ) ) :
				foreach ( $wpte_vid_gallery as $key => $gal ) :
					$video_type  = isset( $gal['type'] ) ? $gal['type'] : '';
					$video_id    = isset( $gal['id'] ) ? $gal['id'] : '';
					$video_thumb = isset( $gal['thumb'] ) ? $gal['thumb'] : '';
					?>
			<li class="wte-video-gal-<?php echo esc_html( $video_type ); ?>">
				<input type="hidden" name="wpte_vid_gallery[<?php echo esc_attr( $key ); ?>][id]" value="<?php echo esc_attr( $video_id ); ?>">
				<input type="hidden" name="wpte_vid_gallery[<?php echo esc_attr( $key ); ?>][type]" value="<?php echo esc_attr( $video_type ); ?>">
				<input type="hidden" name="wpte_vid_gallery[<?php echo esc_attr( $key ); ?>][thumb]" value="<?php echo esc_attr( $video_thumb ); ?>">
					<?php
					if ( 'youtube' === $video_type ) {
						?>
				<img class="image-preview" src="<?php echo esc_url( $video_thumb ); ?>">
				<small><a class="remove-video" href="#"><?php esc_html_e( 'Remove video', 'wp-travel-engine' ); ?></a></small>
						<?php
					} else {
						?>
				<img class="image-preview" data-vimeo-id="<?php echo esc_attr( $video_id ); ?>" src="<?php echo esc_url( $video_thumb ); ?>">
				<small><a class="remove-video" href="#"><?php esc_html_e( 'Remove video', 'wp-travel-engine' ); ?></a></small>
								<?php
					}
					?>
			</li>
					<?php
				endforeach;
					endif;
			?>
		</ul>
		<script type="text/html" id="tmpl-wpte-trip-videogallery-row">
		<li class="wte-video-gal-{{data.video_data.type}}">
			<input type="hidden" name="wpte_vid_gallery[{{data.index}}][id]" value="{{data.video_data.id}}">
			<input type="hidden" name="wpte_vid_gallery[{{data.index}}][type]" value="{{data.video_data.type}}">
			<input type="hidden" name="wpte_vid_gallery[{{data.index}}][thumb]" value="{{data.thumb}}">
			<#
				if ( 'youtube'===data.video_data.type ) {
				#>
				<img class="image-preview" src="{{data.thumb}}">
				<small><a class="remove-video" href="#"><?php esc_html_e( 'Remove video', 'wp-travel-engine' ); ?></a></small>
				<#
					} else {
					#>
					<img class="image-preview" data-vimeo-id="{{data.video_data.id}}" src="{{data.thumb}}">
					<small><a class="remove-video" href="#"><?php esc_html_e( 'Remove video', 'wp-travel-engine' ); ?></a></small>
					<#
						}
						#>
		</li>
		</script>
	</div>
</div>
<div class="wpte-info-block">
	<b><?php esc_html_e( 'Note:', 'wp-travel-engine' ); ?></b>
	<p>
	<?php
	$page_shortcode     = "[wte_video_gallery trip_id='{$post_id}']";
	$template_shortcode = "<?php echo do_shortcode('[wte_video_gallery trip_id={$post_id}]'); ?>";
	echo wp_kses( sprintf( __( 'You can use this shortcode <b>%1$s</b> to display Video Gallery of this trip in posts/pages/tabs or use this snippet <b>%2$s</b> to display Video Gallery in templates.', 'wp-travel-engine' ), $page_shortcode, $template_shortcode ), array( 'b' => array() ) );
	?>
		</p>
	<p>
		<?php esc_html_e( "Additional attributes are: type='popup/slider' title='' label='', where type displays either a popup or slider layout, defaults popup layout.", 'wp-travel-engine' ); ?>
	</p>
</div>

<?php if ( $next_tab ) : ?>
<div class="wpte-field wpte-submit">
	<input data-tab="gallery" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php esc_attr_e( 'Save &amp; Continue', 'wp-travel-engine' ); ?>">
</div>
	<?php
endif;
