<?php
/**
 * Admin FAQs tab template.
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
$faqs_title               = isset( $wp_travel_engine_setting['faq']['title'] ) ? $wp_travel_engine_setting['faq']['title'] : '';
$faq_section_title        = isset( $wp_travel_engine_setting['faq_section_title'] ) ? $wp_travel_engine_setting['faq_section_title'] : $faqs_title;
?>
<div class="wpte-field wpte-text wpte-floated">
	<label class="wpte-field-label"><?php esc_html_e( 'Section Title', 'wp-travel-engine' ); ?> </label>
	<input type="text" name="wp_travel_engine_setting[faq_section_title]" value="<?php esc_attr_e( $faq_section_title, 'wp-travel-engine' ); ?>" placeholder="<?php esc_attr_e( 'Enter FAQs Title', 'wp-travel-engine' ); ?>">
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter the section title for the the FAQs section. The section title will be displayed in the FAQs Tab.', 'wp-travel-engine' ); ?></span>
</div>
<input type="hidden" name="wp_travel_engine_setting[faq]" value="false">
<div class="wpte-repeater-wrap wpte-faq-block-hldr">
	<?php
	if ( isset( $wp_travel_engine_setting['faq']['faq_title'] ) && ! empty( $wp_travel_engine_setting['faq']['faq_title'] ) ) {

		$arr_keys = array_keys( $wp_travel_engine_setting['faq']['faq_title'] );
		foreach ( $arr_keys as $key => $value ) {
			if ( array_key_exists( $value, $wp_travel_engine_setting['faq']['faq_title'] ) ) {
				$faq_title_txt   = isset( $wp_travel_engine_setting['faq']['faq_title'][ $value ] ) ? esc_attr( $wp_travel_engine_setting['faq']['faq_title'][ $value ] ) : '';
				$faq_content_txt = isset( $wp_travel_engine_setting['faq']['faq_content'][ $value ] ) ? ( $wp_travel_engine_setting['faq']['faq_content'][ $value ] ) : '';
				?>
				<div class="wpte-repeater-block wpte-sortable wpte-faq-block-row">
					<div class="wpte-faq-block">
						<div class="wpte-faq-title-wrap wpte-floated">
							<a href="Javascript:void(0);" class="wpte-faq-title"><?php echo esc_html( $faq_title_txt ); ?></a>
							<button class="wpte-delete wpte-del-faq"></button>
						</div>
						<div class="wpte-faq-content">
							<div class="wpte-field wpte-text wpte-floated">
								<label for="wp_travel_engine_setting[faq][faq_title][<?php echo esc_attr( $value ); ?>]" class="wpte-field-label"><?php esc_html_e( 'Question', 'wp-travel-engine' ); ?></label>
								<input type="text" id="wp_travel_engine_setting[faq][faq_title][<?php echo esc_attr( $value ); ?>]" name="wp_travel_engine_setting[faq][faq_title][<?php echo esc_attr( $value ); ?>]" value="<?php echo esc_attr( $faq_title_txt ); ?>">
							</div>
							<div class="wpte-field wpte-textarea wpte-floated">
								<label for="wp_travel_engine_setting[faq][faq_content][<?php echo esc_attr( $value ); ?>]" class="wpte-field-label"><?php esc_html_e( 'Answer', 'wp-travel-engine' ); ?></label>
								<textarea id="wp_travel_engine_setting[faq][faq_content][<?php echo esc_attr( $value ); ?>]" name="wp_travel_engine_setting[faq][faq_content][<?php echo esc_attr( $value ); ?>]"><?php echo wp_kses_post( $faq_content_txt ); ?></textarea>
							</div>
						</div>
					</div>
				</div> <!-- .wpte-repeater-block -->
				<?php
			}
		}
	}
	?>
</div> <!-- .wpte-repeater-wrap -->
<script type="text/html" id="tmpl-wpte-faq-block-tmp">
<div class="wpte-repeater-block wpte-sortable wpte-faq-block-row">
	<div class="wpte-faq-block wpte-active">
		<div class="wpte-faq-title-wrap wpte-floated">
			<a href="Javascript:void(0);" class="wpte-faq-title"><?php echo esc_html__( 'FAQ Question', 'wp-travel-engine' ); ?></a>
			<button class="wpte-delete wpte-del-faq"></button>
		</div>
		<div class="wpte-faq-content" style="display:block;">
			<div class="wpte-field wpte-text wpte-floated">
				<label for="wp_travel_engine_setting[faq][faq_title][{{data.key}}]" class="wpte-field-label"><?php esc_html_e( 'Question', 'wp-travel-engine' ); ?></label>
				<input type="text" id="wp_travel_engine_setting[faq][faq_title][{{data.key}}]" placeholder="<?php esc_attr_e( 'FAQ Question', 'wp-travel-engine' ); ?>" name="wp_travel_engine_setting[faq][faq_title][{{data.key}}]" value="">
			</div>
			<div class="wpte-field wpte-textarea wpte-floated">
				<label for="wp_travel_engine_setting[faq][faq_content][{{data.key}}]" class="wpte-field-label"><?php esc_html_e( 'Answer', 'wp-travel-engine' ); ?></label>
				<textarea id="wp_travel_engine_setting[faq][faq_content][{{data.key}}]" placeholder="<?php esc_attr_e( 'FAQ Answer', 'wp-travel-engine' ); ?>" name="wp_travel_engine_setting[faq][faq_content][{{data.key}}]"></textarea>
			</div>
		</div>
	</div>
</div> <!-- .wpte-repeater-block -->
</script>
<div class="wpte-add-btn-wrap">
	<button class="wpte-add-btn wpte-add-faq-blck"><?php esc_html_e( 'Add FAQs', 'wp-travel-engine' ); ?></button>
</div>

<?php // if ( $next_tab && 'false' != $next_tab ) : ?>
<div class="wpte-field wpte-submit">
	<input data-tab="faqs" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php esc_attr_e( 'Save &amp; Continue', 'wp-travel-engine' ); ?>">
</div>
<?php
// endif;
