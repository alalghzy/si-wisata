<?php
/**
 * Check Payment Settings.
 */
$settings      = get_option( 'wp_travel_engine_settings', array() );
$check_payment = isset( $settings['check_payment'] ) ? $settings['check_payment'] : array();
$label         = ! empty( $check_payment['title'] ) ? $check_payment['title'] : __( 'Check payments', 'wp-travel-engine' );
$description   = ! empty( $check_payment['description'] ) ? $check_payment['description'] : __( 'Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.', 'wp-travel-engine' );
$instruction   = ! empty( $check_payment['instruction'] ) ? $check_payment['instruction'] : __( 'Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.', 'wp-travel-engine' );

?>
<div class="wpte-field wpte-floated">
	<label
		class="wpte-field-label"
		for="wp_travel_engine_settings[check_payment][title]"><?php esc_html_e( 'Title', 'wp-travel-engine' ); ?></label>
	<input
		type="text"
		id="wp_travel_engine_settings[check_payment]"
		name="wp_travel_engine_settings[check_payment][title]"
		value="<?php echo esc_attr( $label ); ?>" />
	<span class="wpte-tooltip"><?php esc_html_e( 'The title which the user see during checkout.', 'wp-travel-engine' ); ?></span>
</div>
<div class="wpte-field wpte-floated">
	<label
		class="wpte-field-label"
		for="wp_travel_engine_settings[check_payment][description]"><?php esc_html_e( 'Description', 'wp-travel-engine' ); ?></label>
	<textarea
		type="text"
		id="wp_travel_engine_settings[check_payment][description]"
		name="wp_travel_engine_settings[check_payment][description]"><?php echo wp_kses_post( $description ); ?></textarea>
	<span class="wpte-tooltip"><?php esc_html_e( 'Payment method description.', 'wp-travel-engine' ); ?></span>
</div>
<div class="wpte-field wpte-floated">
	<label
		class="wpte-field-label"
		for="wp_travel_engine_settings[check_payment][instruction]"><?php esc_html_e( 'Instructions', 'wp-travel-engine' ); ?></label>
	<textarea
		type="text"
		id="wp_travel_engine_settings[check_payment][instruction]"
		name="wp_travel_engine_settings[check_payment][instruction]"><?php echo wp_kses( $instruction, 'wte_formats' ); ?></textarea>
	<span class="wpte-tooltip"><?php esc_html_e( 'Instructions to the user, displays on the thankyou page and email.', 'wp-travel-engine' ); ?></span>
</div>
