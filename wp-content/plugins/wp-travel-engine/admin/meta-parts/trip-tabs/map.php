<?php
/**
 * Map Template.
 */
// Get post ID.
$post_id  = $args['post_id'];
$next_tab = $args['next_tab'];

// Get settings meta.
$wp_travel_engine_setting = get_post_meta( $post_id, 'wp_travel_engine_setting', true );
$src[0]                   = '';
if ( isset( $wp_travel_engine_setting['map']['image_url'] ) && $wp_travel_engine_setting['map']['image_url'] != '' ) {
	$src = wp_get_attachment_image_src( $wp_travel_engine_setting['map']['image_url'], 'medium' );
}
$map_section_title = isset( $wp_travel_engine_setting['map_section_title'] ) ? $wp_travel_engine_setting['map_section_title'] : '';
?>
<div class="wpte-field wpte-text wpte-floated">
	<label class="wpte-field-label"><?php esc_html_e( 'Section Title', 'wp-travel-engine' ); ?> </label>
	<input type="text" name="wp_travel_engine_setting[map_section_title]" value="<?php esc_attr_e( $map_section_title, 'wp-travel-engine' ); ?>" placeholder="<?php esc_attr_e( 'Enter Map Title', 'wp-travel-engine' ); ?>">
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter the section title for the the Map section. The section title will be displayed in the Map Tab.', 'wp-travel-engine' ); ?></span>
</div>

<div class="wpte-field wpte-file wpte-floated">
	<label class="wpte-field-label"><?php esc_html_e( 'Map Image', 'wp-travel-engine' ); ?></label>
	<div class="wpte-file-wrap">
		<input type="hidden" name="wp_travel_engine_setting[map][image_url]" id="image_url" class="regular-text" value="<?php echo isset( $wp_travel_engine_setting['map']['image_url'] ) ? esc_attr( $wp_travel_engine_setting['map']['image_url'] ) : ''; ?>">
		<label class="wpte-file-label" id="wpte-upload-map-img"><?php esc_html_e( 'Upload Image', 'wp-travel-engine' ); ?></label>
		<div class="wpte-file-preview">
			<img id="map-image-prev-hldr" src="<?php echo esc_url( ( isset( $wp_travel_engine_setting['map']['image_url'] ) && $wp_travel_engine_setting['map']['image_url'] != '' ) ? ( $src[0] ) : ( plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . 'admin/css/images/img-fallback.png' ) ); ?>" alt="">
			<?php
			$rem_disp = ! empty( $wp_travel_engine_setting['map']['image_url'] ) ? 'display:block;' : 'display:none;';
			?>
			<button style="<?php echo esc_attr( $rem_disp ); ?>" data-fallback="<?php echo esc_url( plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . 'admin/css/images/img-fallback.png' ); ?>" class="wpte-delete wpte-delete-map-img"></button>
		</div>
	</div>
</div>

<div class="wpte-field wpte-textarea wpte-floated">
	<label class="wpte-field-label"><?php esc_html_e( 'Map Iframe Code', 'wp-travel-engine' ); ?></label>
	<textarea name="wp_travel_engine_setting[map][iframe]" id="wp_travel_engine_setting[map][iframe]"><?php echo isset( $wp_travel_engine_setting['map']['iframe'] ) ? wp_kses( $wp_travel_engine_setting['map']['iframe'], 'wte_iframe' ) : ''; ?></textarea>
</div>

<?php
		$page_shortcode     = '[wte_trip_map id=' . "'" . $post_id . "'" . ']';
		$template_shortcode = "&lt;?php echo do_shortcode('[wte_trip_map id=" . $post_id . "]'); ?&gt;";
?>
<div class="wpte-shortcode">
	<span class="wpte-tooltip"><?php esc_html_e( 'To display Tour Map of this tour in posts/pages/tabs/widgets use the following', 'wp-travel-engine' ); ?> <b><?php esc_html_e( 'Shortcode.', 'wp-travel-engine' ); ?></b></span>
	<div class="wpte-field wpte-field-gray wpte-floated">
		<input id="wpte-map-code" readonly type="text" value="<?php esc_attr_e( $page_shortcode, 'wp-travel-engine' ); ?>">
		<button data-copyid="wpte-map-code" class="wpte-copy-btn"><?php esc_html_e( 'Copy', 'wp-travel-engine' ); ?></button>
	</div>
</div>

<div class="wpte-shortcode">
	<span class="wpte-tooltip"><?php esc_html_e( 'To display Tour Map of this tour in posts/pages/tabs/widgets, please use below', 'wp-travel-engine' ); ?> <b><?php esc_html_e( 'PHP Funtion.', 'wp-travel-engine' ); ?></b></span>
	<div class="wpte-field wpte-field-gray wpte-floated">
		<input id="wpte-map-temp-code" readonly type="text" value="<?php esc_attr_e( $template_shortcode, 'wp-travel-engine' ); ?>">
		<button data-copyid="wpte-map-temp-code" class="wpte-copy-btn"><?php esc_html_e( 'Copy', 'wp-travel-engine' ); ?></button>
	</div>
</div>

<?php if ( $next_tab ) : ?>
<div class="wpte-field wpte-submit">
	<input data-tab="map" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php esc_attr_e( 'Save &amp; Continue', 'wp-travel-engine' ); ?>">
</div>
	<?php
endif;
