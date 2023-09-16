<?php
/**
 * Extra Services upsell
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
	<p>
		<?php
		echo wp_kses(
			sprintf( __( 'Do you want to provide additional services such as supplementary room, hotel upgrade, airport pick and drop, etc? Extra Services extension allows you to create add-on services and sell more to your customer. %1$sGet Extra Services extension now%2$s.', 'wp-travel-engine' ), '<a target="_blank" href="https://wptravelengine.com/plugins/extra-services/?utm_source=setting&utm_medium=customer_site&utm_campaign=setting_addon">', '</a>' ),
			array(
				'a' => array(
					'href'   => array(),
					'target' => array(),
				),
			)
		);
		?>
	</p>
</div>

<?php if ( $next_tab ) : ?>
<div class="wpte-field wpte-submit">
	<input data-tab="overview" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php esc_html_e( 'Continue', 'wp-travel-engine' ); ?>">
</div>
	<?php
endif;
