<?php
/**
 * Payment Tax Tab
 */
$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
$tax_enable                = isset( $wp_travel_engine_settings['tax_enable'] ) ? $wp_travel_engine_settings['tax_enable'] : 'no';
$tax_option                = isset( $wp_travel_engine_settings['tax_type_option'] ) ? $wp_travel_engine_settings['tax_type_option'] : 'exclusive';
$tax_percentage 		   = isset($wp_travel_engine_settings['tax_percentage'] ) && !empty($wp_travel_engine_settings['tax_percentage'])?  $wp_travel_engine_settings['tax_percentage']  : 13;
?>
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" for="wp_travel_engine_settings[tax_enable]"><?php esc_html_e( 'Enable Tax', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" value="no" name="wp_travel_engine_settings[tax_enable]">
		<input type="checkbox"
			data-onchange
			data-onchange-toggle-target="[data-tax-enable-section]"
			data-onchange-toggle-off-value="no"
			id="wp_travel_engine_settings[tax_enable]"
			name="wp_travel_engine_settings[tax_enable]"
			value="yes" <?php checked( $tax_enable, 'yes' ); ?>>
		<label for="wp_travel_engine_settings[tax_enable]"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Check this option to enable tax option for trips.', 'wp-travel-engine' ); ?></span>
</div>
<div class="wpte-field-subfields" data-tax-enable-section>
	<div class="wpte-field wpte-floated <?php wptravelengine_hidden_class( 'no', $tax_enable ); ?>" data-tax-enable-section>
		<label class="wpte-field-label" for="wp_travel_engine_settings[tax_enable]"><?php esc_html_e( 'Trip Prices', 'wp-travel-engine' ); ?></label>
		<div class="wpte-field wpte-radio">
			<div class="wpte-radio-wrap">
				<input type="radio" id="inclusive" value="inclusive" name="wp_travel_engine_settings[tax_type_option]" <?php echo checked( $tax_option, 'inclusive' ); ?>>
				<label for="inclusive"></label>
			</div>
			<label class="wpte-field-label" for="inclusive"><?php _e( 'Inclusive of tax', 'wp-travel-engine' ); ?></label>
			<div class="wpte-radio-wrap">
				<input type="radio" id="exclusive" value="exclusive" name="wp_travel_engine_settings[tax_type_option]" <?php echo checked( $tax_option, 'exclusive' ); ?>>
				<label for="exclusive"></label>
			</div>
			<label class="wpte-field-label" for="exclusive"><?php _e( 'Exclusive of tax', 'wp-travel-engine' ); ?></label>
		</div>
		<div class="wpte-tooltip"><?php esc_html_e( 'This option will affect how you enter trip prices.', 'wp-travel-engine' ); ?></div>
	</div>
	<div class="wpte-field wpte-floated <?php wptravelengine_hidden_class( 'no', $tax_enable ); ?>" data-tax-enable-section>
		<label for="wp_travel_engine_settings[tax_percentage]" class="wpte-field-label"><?php esc_html_e( 'Tax Percentage', 'wp-travel-engine' ); ?></label>
		<input type="number" min="0" id="wp_travel_engine_settings[tax_percentage]" name="wp_travel_engine_settings[tax_percentage]" value="<?php  echo $tax_percentage;?>" placeholder="<?php esc_attr_e( 'for example: 13%', 'wp-travel-engine' ); ?>">
		<div class="wpte-tooltip"><?php esc_html_e( 'Trip Tax percentage added to trip price.', 'wp-travel-engine' ); ?></div>
	</div>
</div>
