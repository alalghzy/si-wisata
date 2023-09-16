<?php
/**
 * PayPal Standard Settings.
 */
$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
?>
<div class="wpte-field wpte-text wpte-floated">
	<label for="wp_travel_engine_settings[paypal_id]" class="wpte-field-label"><?php esc_html_e( 'PayPal ID', 'wp-travel-engine' ); ?></label>
	<input type="text" id="wp_travel_engine_settings[paypal_id]" name="wp_travel_engine_settings[paypal_id]" value="<?php echo isset( $wp_travel_engine_settings['paypal_id'] ) ? esc_attr( $wp_travel_engine_settings['paypal_id'] ) : ''; ?>">
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter a valid Merchant account ID (strongly recommend) or PayPal account email address. All payments will go to this account.', 'wp-travel-engine' ); ?></span>
</div>
