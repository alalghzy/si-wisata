<?php
/**
 * Includes / Excludes Tabs content.
 *
 * @package WP_Travel_Engine/Admin/Meta_Parts
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
$wp_travel_engine_setting = get_post_meta( $post_id, 'wp_travel_engine_setting', true );
$cost_tab_sec_title       = isset( $wp_travel_engine_setting['cost_tab_sec_title'] ) ? $wp_travel_engine_setting['cost_tab_sec_title'] : '';
$cost_includes_title      = isset( $wp_travel_engine_setting['cost']['includes_title'] ) ? $wp_travel_engine_setting['cost']['includes_title'] : '';
$cost_excludes_title      = isset( $wp_travel_engine_setting['cost']['excludes_title'] ) ? $wp_travel_engine_setting['cost']['excludes_title'] : '';

$cost_includes_content = isset( $wp_travel_engine_setting['cost']['cost_includes'] ) ? $wp_travel_engine_setting['cost']['cost_includes'] : '';

$cost_excludes_content = isset( $wp_travel_engine_setting['cost']['cost_excludes'] ) ? $wp_travel_engine_setting['cost']['cost_excludes'] : '';
?>
<div class="wpte-form-block-wrap">
	<div class="wpte-form-block">
		<div class="wpte-form-content">
			<div class="wpte-field wpte-text wpte-floated">
				<label class="wpte-field-label"><?php esc_html_e( 'Section Title', 'wp-travel-engine' ); ?></label>
				<input type="text" name="wp_travel_engine_setting[cost_tab_sec_title]" value="<?php echo esc_attr( $cost_tab_sec_title ); ?>" placeholder="Enter Here">
				<span class="wpte-tooltip"><?php esc_html_e( 'Enter the cost tab section title', 'wp-travel-engine' ); ?></span>
			</div>
			<div class="wpte-title-wrap">
				<h2 class="wpte-title"><?php esc_html_e( 'Cost Includes', 'wp-travel-engine' ); ?></h2>
			</div>
			<div class="wpte-field wpte-text wpte-floated">
				<label class="wpte-field-label"><?php esc_html_e( 'Cost Includes Title', 'wp-travel-engine' ); ?></label>
				<input type="text" name="wp_travel_engine_setting[cost][includes_title]" value="<?php esc_attr_e( $cost_includes_title, 'wp-travel-engine' ); ?>" placeholder="<?php esc_attr_e( 'Cost includes title', 'wp-travel-engine' ); ?>">
				<span class="wpte-tooltip"></span>
			</div>
			<div class="wpte-field wpte-textarea wpte-floated">
				<label class="wpte-field-label"><?php esc_html_e( 'List Of Services', 'wp-travel-engine' ); ?> </label>
				<textarea name="wp_travel_engine_setting[cost][cost_includes]" placeholder="<?php esc_attr_e( 'List of services that are included...', 'wp-travel-engine' ); ?>"><?php echo esc_html( $cost_includes_content ); ?></textarea>
				<span class="wpte-tooltip"><?php esc_html_e( 'Enter the content for trip includes section', 'wp-travel-engine' ); ?></span>
			</div>
		</div>
	</div> <!-- .wpte-form-block -->

	<div class="wpte-form-block">
		<div class="wpte-title-wrap">
			<h2 class="wpte-title"><?php esc_html_e( 'Cost Excludes', 'wp-travel-engine' ); ?></h2>
		</div>
		<div class="wpte-form-content">
			<div class="wpte-field wpte-text wpte-floated">
				<label class="wpte-field-label"><?php esc_html_e( 'Cost Excludes Title', 'wp-travel-engine' ); ?></label>
				<input type="text" name="wp_travel_engine_setting[cost][excludes_title]" value="<?php esc_attr_e( $cost_excludes_title, 'wp-travel-engine' ); ?>" placeholder="<?php esc_attr_e( 'Cost excludes title', 'wp-travel-engine' ); ?>">
				<span class="wpte-tooltip"></span>
			</div>
			<div class="wpte-field wpte-textarea wpte-floated">
				<label class="wpte-field-label"><?php esc_html_e( 'List Of Services', 'wp-travel-engine' ); ?> </label>
				<textarea name="wp_travel_engine_setting[cost][cost_excludes]" placeholder="<?php esc_attr_e( 'List of services that are included...', 'wp-travel-engine' ); ?>"><?php echo esc_html( $cost_excludes_content ); ?></textarea>
				<span class="wpte-tooltip"><?php esc_html_e( 'Enter the content for trip excludes section', 'wp-travel-engine' ); ?></span>
			</div>
		</div>
	</div> <!-- .wpte-form-block -->
</div> <!-- .wpte-form-block-wrap -->
<?php if ( $next_tab ) : ?>
<div class="wpte-field wpte-submit">
	<input data-tab="includes-excludes" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php esc_attr_e( 'Save &amp; Continue', 'wp-travel-engine' ); ?>">
</div>
	<?php
endif;
