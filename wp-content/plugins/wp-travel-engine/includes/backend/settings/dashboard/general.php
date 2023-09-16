<?php
/**
 * Dashboard General Settings.
 */
// Get saved settings for the tab.
$wp_travel_engine_settings                = get_option( 'wp_travel_engine_settings', true );
$enable_checkout_customer_registration    = isset( $wp_travel_engine_settings['enable_checkout_customer_registration'] ) ? $wp_travel_engine_settings['enable_checkout_customer_registration'] : 'no';
$disable_my_account_customer_registration = isset( $wp_travel_engine_settings['disable_my_account_customer_registration'] ) ? $wp_travel_engine_settings['disable_my_account_customer_registration'] : 'no';
$generate_username_from_email             = isset( $wp_travel_engine_settings['generate_username_from_email'] ) ? $wp_travel_engine_settings['generate_username_from_email'] : 'no';
$generate_user_password                   = isset( $wp_travel_engine_settings['generate_user_password'] ) ? $wp_travel_engine_settings['generate_user_password'] : 'no';
?>
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" for="wp_travel_engine_settings[enable_checkout_customer_registration]"><?php esc_html_e( 'Require Customer Registration To Book Trips', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[enable_checkout_customer_registration]" value="no">
		<input type="checkbox" id="wp_travel_engine_settings[enable_checkout_customer_registration]" name="wp_travel_engine_settings[enable_checkout_customer_registration]" value="yes" <?php checked( $enable_checkout_customer_registration, 'yes' ); ?>>
		<label for="wp_travel_engine_settings[enable_checkout_customer_registration]"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Require customer registration for booking trips.', 'wp-travel-engine' ); ?></span>
</div>
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" for="wp_travel_engine_settings[disable_my_account_customer_registration]"><?php esc_html_e( 'Disable Customer Registration On "Dashboard" Page', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[disable_my_account_customer_registration]" value="no">
		<input type="checkbox" id="wp_travel_engine_settings[disable_my_account_customer_registration]" name="wp_travel_engine_settings[disable_my_account_customer_registration]" value="yes" <?php checked( $disable_my_account_customer_registration, 'yes' ); ?>>
		<label for="wp_travel_engine_settings[disable_my_account_customer_registration]"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Disable customer registration on the "Dashboard" page.', 'wp-travel-engine' ); ?></span>
</div>

<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" for="wp_travel_engine_settings[generate_username_from_email]"><?php esc_html_e( 'Automatically Generate Username From Customer Email', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[generate_username_from_email]" value="no">
		<input type="checkbox" id="wp_travel_engine_settings[generate_username_from_email]" name="wp_travel_engine_settings[generate_username_from_email]" value="yes" <?php checked( $generate_username_from_email, 'yes' ); ?>>
		<label for="wp_travel_engine_settings[generate_username_from_email]"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Automatically generate username from customer email.', 'wp-travel-engine' ); ?></span>
</div>

<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" for="wp_travel_engine_settings[generate_user_password]"><?php esc_html_e( 'Automatically Generate Customer Password', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[generate_user_password]" value="no">
		<input type="checkbox" id="wp_travel_engine_settings[generate_user_password]" name="wp_travel_engine_settings[generate_user_password]" value="yes" <?php checked( $generate_user_password, 'yes' ); ?>>
		<label for="wp_travel_engine_settings[generate_user_password]"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Automatically generate customer password.', 'wp-travel-engine' ); ?></span>
</div>
