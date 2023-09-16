<?php
/**
 * Admin overview Tab content - Trip Meta
 *
 * @package Wp_Travel_Engine/Admin/Meta_parts
 */
// Get post ID.
$post_id  = $args['post_id'];
$next_tab = $args['next_tab'];

// Get settings meta.
$wp_travel_engine_setting = get_post_meta( $post_id, 'wp_travel_engine_setting', true );

$trip_highlights_title  = isset( $wp_travel_engine_setting['trip_highlights_title'] ) ? $wp_travel_engine_setting['trip_highlights_title'] : '';
$trip_highlights        = isset( $wp_travel_engine_setting['trip_highlights'] ) ? $wp_travel_engine_setting['trip_highlights'] : array();
$overview_content       = isset( $wp_travel_engine_setting['tab_content']['1_wpeditor'] ) ? $wp_travel_engine_setting['tab_content']['1_wpeditor'] : '';
$overview_section_title = isset( $wp_travel_engine_setting['overview_section_title'] ) ? $wp_travel_engine_setting['overview_section_title'] : esc_html__( 'Overview', 'wp-travel-engine' );
?>
<div class="wpte-form-block-wrap">
	<div class="wpte-form-block">
		<div class="wpte-title-wrap">
			<h2 class="wpte-title"><?php esc_html_e( 'Trip Description', 'wp-travel-engine' ); ?></h2>
		</div>
		<div class="wpte-form-content">
			<div class="wpte-field wpte-text wpte-floated">
				<label class="wpte-field-label"><?php esc_html_e( 'Section Title', 'wp-travel-engine' ); ?></label>
				<input type="text" name="wp_travel_engine_setting[overview_section_title]"
					value="<?php echo esc_attr( $overview_section_title ); ?>" placeholder="Enter Here">
				<span class="wpte-tooltip"><?php esc_html_e( 'Enter the overview section title', 'wp-travel-engine' ); ?></span>
			</div>

			<div class="wpte-field wpte-textarea wpte-floated wpte-rich-textarea delay">
				<label class="wpte-field-label"><?php esc_html_e( 'Trip Description', 'wp-travel-engine' ); ?></label>
				<!-- <div class="wte-editor-notice">
						<?php esc_html_e( 'Click to initialize RichEditor', 'wp-travel-engine' ); ?>
					</div> -->
				<textarea placeholder="<?php esc_attr_e( 'Trip Overview:', 'wp-travel-engine' ); ?>"
					name="wp_travel_engine_setting[tab_content][1_wpeditor]" class="wte-editor-area wp-editor-area"
					id="WTE_Trip_Overview"><?php echo wp_kses_post( $overview_content ); ?></textarea>
				<span class="wpte-tooltip"><?php esc_html_e( 'The overview section content', 'wp-travel-engine' ); ?></span>
			</div>
		</div>
	</div> <!-- .wpte-form-block -->
	<div class="wpte-form-block">
		<div class="wpte-title-wrap">
			<h2 class="wpte-title"><?php esc_html_e( 'Trip Highlights', 'wp-travel-engine' ); ?></h2>
		</div>
		<div class="wpte-form-content">
			<div class="wpte-field wpte-text wpte-floated">
				<label class="wpte-field-label"><?php esc_html_e( 'Section Title', 'wp-travel-engine' ); ?></label>
				<input type="text" name="wp_travel_engine_setting[trip_highlights_title]"
					value="<?php echo esc_attr( $trip_highlights_title ); ?>" placeholder="Enter Here">
				<span
					class="wpte-tooltip"><?php esc_html_e( 'Enter title for the Trip Highlights section.', 'wp-travel-engine' ); ?></span>
			</div>
			<input type="hidden" name="wp_travel_engine_setting[trip_highlights]" value="false">
			<div class="wpte-repeater-wrap wpte-trip-highlights-hldr">
				<?php
				if ( is_array( $trip_highlights ) ) :
					foreach ( $trip_highlights as $key => $highlight ) :
						$highlight_text = isset( $highlight['highlight_text'] ) ? $highlight['highlight_text'] : '';
						?>
				<div class="wpte-repeater-block wpte-sortable wpte-trp-highlight">
					<div class="wpte-field wpte-field-gray wpte-floated">
						<input required type="text"
							name="wp_travel_engine_setting[trip_highlights][<?php echo esc_attr( $key ); ?>][highlight_text]"
							value="<?php echo esc_attr( $highlight['highlight_text'] ); ?>"
							placeholder="<?php esc_attr_e( 'Enter trip highlight', 'wp-travel-engine' ); ?>">
						<button class="wpte-delete wte-delete-highlight"></button>
					</div>
				</div> <!-- .wpte-repeater-block -->
						<?php
				endforeach;
					endif;
				?>
				<script type="text/html" id="tmpl-tour-highlight-row">
				<div class="wpte-repeater-block wpte-sortable wpte-trp-highlight">
					<div class="wpte-field wpte-field-gray wpte-floated">
						<input required type="text"
							name="wp_travel_engine_setting[trip_highlights][{{data.key}}][highlight_text]" value=""
							placeholder="<?php esc_attr_e( 'Enter trip highlight', 'wp-travel-engine' ); ?>">
						<button class="wpte-delete wte-delete-highlight"></button>
					</div>
				</div> <!-- .wpte-repeater-block -->
				</script>
			</div> <!-- .wpte-repeater-wrap -->
			<div class="wpte-add-btn-wrap">
				<button
					class="wpte-add-btn wte-add-trip-highlight"><?php esc_html_e( 'Add trip highlight', 'wp-travel-engine' ); ?></button>
			</div>
		</div>
	</div> <!-- .wpte-form-block -->
</div> <!-- .wpte-form-block-wrap -->

<?php

if ( $next_tab ) :
	?>
<div class="wpte-field wpte-submit">
	<input data-tab="overview" data-post-id="<?php echo esc_attr( $post_id ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>"
		data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link"
		type="submit" name="wpte_trip_tabs_save_continue"
		value="<?php esc_attr_e( 'Save &amp; Continue', 'wp-travel-engine' ); ?>">
</div>
	<?php
endif;
