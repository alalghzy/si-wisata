<?php
/**
 * Trip Facts Template.
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

// Global settings.
$wp_travel_engine_option_settings = get_option( 'wp_travel_engine_settings', true );

$trip_facts_title = isset( $wp_travel_engine_setting['trip_facts_title'] ) ? $wp_travel_engine_setting['trip_facts_title'] : '';

$page_shortcode     = '[Trip_Info_Shortcode id=' . "'" . $post_id . "'" . ']';
$template_shortcode = "&lt;?php echo do_shortcode('[Trip_Info_Shortcode id=" . $post_id . "]'); ?&gt;";
?>

	<div class="wpte-field wpte-text wpte-floated">
		<label class="wpte-field-label"><?php esc_html_e( 'Section Title', 'wp-travel-engine' ); ?></label>
		<input type="text" name="wp_travel_engine_setting[trip_facts_title]" value="<?php echo esc_attr( $trip_facts_title ); ?>" placeholder="<?php esc_attr_e( 'Enter Trip Info Title', 'wp-travel-engine' ); ?>">
		<span class="wpte-tooltip"><?php esc_html_e( 'Enter the title to display inside the tab section of Trip Facts section.', 'wp-travel-engine' ); ?></span>
	</div>
	<div class="wpte-info-block">
		<b><?php esc_html_e( 'Note:', 'wp-travel-engine' ); ?></b>
		<p><?php echo wp_kses( __( sprintf( 'You can use this shortcode <b>%1$s</b> to display Trip Info of this trip in posts/pages/tabs or use this snippet <b>%2$s</b> to display Trip Info in templates.', $page_shortcode, $template_shortcode ), 'wp-travel-engine' ), array( 'b' => array() ) ); ?></p>
	</div>

	<div class="wpte-field wpte-multi-fields wpte-floated">
	<?php
	$global_trip_facts = wptravelengine_get_trip_facts_options();
	if ( $global_trip_facts ) {
		?>
			<label class="wpte-field-label"><?php esc_html_e( 'Trip info selection', 'wp-travel-engine' ); ?></label>
			<div class="wpte-floated">
				<select id="wte_global_trip_facts" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_add_trip_info' ) ); ?>" name="wte_global_trip_facts" data-placeholder="<?php esc_attr_e( 'Info Type&hellip;', 'wp-travel-engine' ); ?>">
					<option value=""><?php esc_html_e( 'Select Trip Fact', 'wp-travel-engine' ); ?></option>
				<?php
				foreach ( $global_trip_facts['field_type'] as $key => $value ) {
					$id = $global_trip_facts['field_id'][ $key ];
					echo '<option value="' . esc_attr( $id ) . '">' . esc_html( $id ) . '</option>';
				}
				?>
				</select>
				<input type="button" class="button button-small add-info" value="<?php esc_attr_e( 'Add Fact', 'wp-travel-engine' ); ?>">
			</div>
			<span class="wpte-tooltip"><?php esc_html_e( 'Select the trip fact title and click on add fact button to enter trip fact data.', 'wp-travel-engine' ); ?></span>
		<?php
	} else {
		esc_html_e( 'Global Trip Info not found. Please add trip info in the global settings page first.', 'wp-travel-engine' );
	}
	?>
	</div>
	<input type="hidden" name="wp_travel_engine_setting[trip_facts]" value="false">
	<div class="wpte-repeater-wrap wpte-trip-facts-hldr">
		<?php
		if ( isset( $wp_travel_engine_setting['trip_facts'] ) && is_array( $wp_travel_engine_setting['trip_facts'] ) ) {

			foreach ( $wp_travel_engine_setting['trip_facts']['field_type'] as $key => $value ) {
				if ( isset( $global_trip_facts['fid'][ $key ] ) ) {
					$id = $global_trip_facts['field_id'][ $key ];
					?>
						<div class="wpte-repeater-block wpte-sortable wpte-trip-fact-row">
							<div class="wpte-field wpte-floated">
								<label class="wpte-field-label" for="wp_travel_engine_setting[trip_facts][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $key ); ?>]"><?php echo esc_html( $id . ': ' ); ?></label>
								<input type="hidden" name="wp_travel_engine_setting[trip_facts][field_id][<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $id ); ?>">
								<input type="hidden" name="wp_travel_engine_setting[trip_facts][field_type][<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $global_trip_facts['field_type'][ $key ] ); ?>">
							<?php
							switch ( $value ) {
								case 'select':
									$options        = $global_trip_facts['select_options'][ $key ];
									$options        = explode( ',', $options );
									$selected_field = isset( $wp_travel_engine_setting['trip_facts'][ $key ][ $key ] ) ? esc_attr( $wp_travel_engine_setting['trip_facts'][ $key ][ $key ] ) : '';
									?>
											<select id="wp_travel_engine_setting[trip_facts][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $key ); ?>]" name="wp_travel_engine_setting[trip_facts][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $key ); ?>]" data-placeholder="<?php esc_attr_e( 'Choose a field type&hellip;', 'wp-travel-engine' ); ?>" class="wc-enhanced-select" >
											<option value=" "><?php esc_html_e( 'Choose input type&hellip;', 'wp-travel-engine' ); ?></option>
										<?php
										foreach ( $options as $key => $val ) {
											if ( isset( $val ) && $val != '' ) {
												$val = trim( $val );
												echo '<option value="' . ( ! empty( $val ) ? esc_attr( $val ) : 'Please select' ) . '" ' . selected( $selected_field, $val, false ) . '>' . esc_html( $val ) . '</option>';
											}
										}
										?>
											</select>
										<?php
									break;

								case 'duration':
									?>
										<input type="number" min="1" placeholder = "<?php esc_attr_e( 'Number of days', 'wp-travel-engine' ); ?>" class="duration" id="wp_travel_engine_setting[trip_facts][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $key ); ?>]" name="wp_travel_engine_setting[trip_facts][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( isset( $wp_travel_engine_setting['trip_facts'][ $key ][ $key ] ) ? esc_attr( $wp_travel_engine_setting['trip_facts'][ $key ][ $key ] ) : '' ); ?>"/>
										<?php
									break;

								case 'number':
									?>
												<input  type="number" min="1" id="wp_travel_engine_setting[trip_facts][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $key ); ?>]" name="wp_travel_engine_setting[trip_facts][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $key ); ?>]" value="<?php echo isset( $wp_travel_engine_setting['trip_facts'][ $key ][ $key ] ) ? esc_attr( $wp_travel_engine_setting['trip_facts'][ $key ][ $key ] ) : ''; ?>" placeholder="<?php echo isset( $global_trip_facts['input_placeholder'][ $key ] ) ? esc_attr( $global_trip_facts['input_placeholder'][ $key ] ) : ''; ?>" >
										<?php
									break;

								case 'text':
									?>
												<input type="text" id="wp_travel_engine_setting[trip_facts][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $key ); ?>]" name="wp_travel_engine_setting[trip_facts][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $key ); ?>]" value="<?php echo isset( $wp_travel_engine_setting['trip_facts'][ $key ][ $key ] ) ? esc_attr( $wp_travel_engine_setting['trip_facts'][ $key ][ $key ] ) : ''; ?>" placeholder="<?php echo isset( $global_trip_facts['input_placeholder'][ $key ] ) ? esc_attr( $global_trip_facts['input_placeholder'][ $key ] ) : ''; ?>">
										<?php
									break;

								case 'textarea';
									?>
												<textarea id="wp_travel_engine_setting[trip_facts][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $key ); ?>]" name="wp_travel_engine_setting[trip_facts][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $key ); ?>]" placeholder="<?php echo esc_attr( isset( $global_trip_facts['input_placeholder'][ $key ] ) ? $global_trip_facts['input_placeholder'][ $key ] : '' ); ?>" ><?php echo wp_kses_post( isset( $wp_travel_engine_setting['trip_facts'][ $key ][ $key ] ) ? $wp_travel_engine_setting['trip_facts'][ $key ][ $key ] : '' ); ?></textarea>
										<?php
										break;

								default:
									?>
									<input type="text" id="wp_travel_engine_setting[trip_facts][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $key ); ?>]" name="wp_travel_engine_setting[trip_facts][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $key ); ?>]" value="<?php echo isset( $wp_travel_engine_setting['trip_facts'][ $key ][ $key ] ) ? esc_attr( $wp_travel_engine_setting['trip_facts'][ $key ][ $key ] ) : ''; ?>" placeholder="<?php echo esc_attr( isset( $global_trip_facts['input_placeholder'][ $key ] ) ? esc_attr( $global_trip_facts['input_placeholder'][ $key ] ) : '' ); ?>">
									<?php
									break;
							}
							?>
								<button class="wpte-delete wpte-remove-trp-fact"></button>
							</div>
						</div>
					<?php
				}
			}
		}
		?>
	</div> <!-- .wpte-repeater-wrap -->

	<?php if ( $next_tab ) : ?>
		<div class="wpte-field wpte-submit">
			<input data-tab="facts" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php esc_attr_e( 'Save &amp; Continue', 'wp-travel-engine' ); ?>">
		</div>
		<?php
	endif;
