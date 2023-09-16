<?php
/**
 * File Downloads upsell
 */
global $post;
// Get post ID.
if ( ! is_object( $post ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	$post_id  = $args['post_id'];
	$next_tab = $args['next_tab'];
} else {
	$post_id = $post->ID;
}
?>
<div class="wpte-info-block">
	<p><?php esc_html_e( 'Want to provide downloadable files such as brochures, guidebooks, offline maps, etc? File Downloads extension allows you to upload files in various formats that can be downloaded by travellers.', 'wp-travel-engine' ); ?> <a target="_blanks" href="//wptravelengine.com/plugins/file-downloads/?utm_source=setting&utm_medium=customer_site&utm_campaign=setting_addon"><?php esc_html_e( 'Get File Downloads extension now.', 'wp-travel-engine' ); ?></a>
	</p>
</div>

<?php
if ( $next_tab ) :
	?>
<div class="wpte-field wpte-submit">
	<input data-tab="overview" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>" data-next-tab="<?php echo isset( $next_tab['callback_function'] ) ? esc_attr( $next_tab['callback_function'] ) : ''; ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php esc_attr_e( 'Continue', 'wp-travel-engine' ); ?>">
</div>
	<?php
endif;
