<?php
/**
 * Admin itinerary Tab content - Trip Meta
 *
 * @package Wp_Travel_Engine/Admin/Meta_parts
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

$trip_itinerary_title = isset( $wp_travel_engine_setting['trip_itinerary_title'] ) ? $wp_travel_engine_setting['trip_itinerary_title'] : esc_html__( 'Itinerary', 'wp-travel-engine' );

$trip_itineraries = isset( $wp_travel_engine_setting['itinerary'] ) ? $wp_travel_engine_setting['itinerary'] : array();

$arr_keys = isset( $trip_itineraries['itinerary_title'] ) ? array_keys( $trip_itineraries['itinerary_title'] ) : array();
?>
<div class="wpte-field wpte-text wpte-floated">
	<label class="wpte-field-label"><?php esc_html_e( 'Section Title', 'wp-travel-engine' ); ?></label>
	<input type="text" name="wp_travel_engine_setting[trip_itinerary_title]" value="<?php echo esc_attr( $trip_itinerary_title ); ?>" placeholder="Enter Here">
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter title for the trip itinerary section tab content.', 'wp-travel-engine' ); ?></span>
</div>
<div class="wpte-info-block">
	<b><?php esc_html_e( 'Note:', 'wp-travel-engine' ); ?></b>
	<p><?php printf( esc_html__( 'Need additional itinerary fields or require rich text editing for the itinerary? Advanced Itinerary Builder extension provides a rich text editor, sleeping mode, meals, ability to add photos to each day and more. %1$sGet Advanced Itinerary Builder extension now%2$s.', 'wp-travel-engine' ), '<a target="_blank" href="https://wptravelengine.com/plugins/advanced-itinerary-builder/?utm_source=setting&utm_medium=customer_site&utm_campaign=setting_addon">', '</a>' ); ?></p>
</div>
<input type="hidden" name="wp_travel_engine_setting[itinerary]" value="false">
<div id="wpte-itinerary-repeter-holder" class="wpte-repeater-wrap">
	<?php
	$i         = 1;
	$count_iti = count( $arr_keys );
	foreach ( $arr_keys as $key => $value ) :
		if ( array_key_exists( $value, $wp_travel_engine_setting['itinerary']['itinerary_title'] ) ) :

			$iti_title = isset( $wp_travel_engine_setting['itinerary']['itinerary_title'][ $value ] ) ? $wp_travel_engine_setting['itinerary']['itinerary_title'][ $value ] : '';

			$iti_content = isset( $wp_travel_engine_setting['itinerary']['itinerary_content'][ $value ] ) ? $wp_travel_engine_setting['itinerary']['itinerary_content'][ $value ] : '';
			?>
			<div class="wpte-repeater-block wpte-itinerary-repeter">
				<span class="wpte-itinerary-day"><?php echo esc_html__( 'Day ', 'wp-travel-engine' ) . (int) $i; ?></span>
				<div class="wpte-itinerary-fields">
					<div class="wpte-field wpte-floated">
						<input type="text" name="wp_travel_engine_setting[itinerary][itinerary_title][<?php echo esc_attr( $value ); ?>]" value="<?php echo esc_attr( $iti_title ); ?>" placeholder="<?php esc_attr_e( 'Itinerary Title', 'wp-travel-engine' ); ?>">
					</div>
					<div class="wpte-field wpte-textarea">
						<textarea
							name="wp_travel_engine_setting[itinerary][itinerary_content][<?php echo esc_attr( $value ); ?>]"
							placeholder="<?php esc_attr_e( 'Itinerary Content', 'wp-travel-engine' ); ?>"><?php echo wp_kses_post( $iti_content ); ?></textarea>
					</div>
				</div>
					<?php if ( $count_iti === $i ) : ?>
				<button class="wpte-delete wpte-remove-iti"></button>
				<?php endif; ?>
			</div> <!-- .wpte-repeater-block -->
			<?php
		endif;
		$i++;
	endforeach;
	if ( empty( $arr_keys ) ) :
		?>
		<div class="wpte-repeater-block wpte-itinerary-repeter">
			<span class="wpte-itinerary-day"><?php echo esc_html__( 'Day ', 'wp-travel-engine' ) . '1'; ?></span>
			<div class="wpte-itinerary-fields">
				<div class="wpte-field wpte-floated">
					<input type="text" name="wp_travel_engine_setting[itinerary][itinerary_title][1]" value="" placeholder="<?php esc_attr_e( 'Itinerary Title', 'wp-travel-engine' ); ?>">
				</div>
				<div class="wpte-field wpte-textarea">
					<textarea name="wp_travel_engine_setting[itinerary][itinerary_content][1]" placeholder="<?php esc_attr_e( 'Itinerary Content', 'wp-travel-engine' ); ?>"></textarea>
				</div>
			</div>
			<button class="wpte-delete wpte-remove-iti"></button>
		</div> <!-- .wpte-repeater-block -->
	<?php endif; ?>
</div> <!-- .wpte-repeater-wrap -->
<div class="wpte-add-btn-wrap">
	<button class="wpte-add-btn wpte-add-itinerary"><?php esc_html_e( 'Add Itinerary', 'wp-travel-engine' ); ?></button>
</div>
<script type="text/html" id="tmpl-wpte-add-iti-row">
<div class="wpte-repeater-block wpte-itinerary-repeter">
	<span class="wpte-itinerary-day"><?php echo esc_html__( 'Day ', 'wp-travel-engine' ) . '{{data.key}}'; ?></span>
	<div class="wpte-itinerary-fields">
		<div class="wpte-field wpte-floated">
			<input type="text" name="wp_travel_engine_setting[itinerary][itinerary_title][{{data.key}}]" value="" placeholder="<?php esc_attr_e( 'Itinerary Title', 'wp-travel-engine' ); ?>">
		</div>
		<div class="wpte-field wpte-textarea">
			<textarea name="wp_travel_engine_setting[itinerary][itinerary_content][{{data.key}}]" placeholder="<?php esc_attr_e( 'Itinerary Content', 'wp-travel-engine' ); ?>"></textarea>
		</div>
	</div>
	<button class="wpte-delete wpte-remove-iti"></button>
</div> <!-- .wpte-repeater-block -->
</script>
<?php do_action( 'wp_travel_engine_trip_custom_info' ); ?>
<?php if ( $next_tab ) : ?>
<div class="wpte-field wpte-submit">
	<input data-tab="itinerary" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php esc_attr_e( 'Save &amp; Continue', 'wp-travel-engine' ); ?>">
</div>
	<?php
endif;
