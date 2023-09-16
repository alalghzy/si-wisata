<?php
/**
 * Dashboard General Settings.
 */
// Get saved settings for the tab.
$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
$enable_social_login       = isset( $wp_travel_engine_settings['enable_social_login'] ) ? $wp_travel_engine_settings['enable_social_login'] : 'no';
$enable_google_login       = isset( $wp_travel_engine_settings['enable_google_login'] ) ? $wp_travel_engine_settings['enable_google_login'] : 'no';
$enable_facebook_login     = isset( $wp_travel_engine_settings['enable_facebook_login'] ) ? $wp_travel_engine_settings['enable_facebook_login'] : 'no';
$enable_linkedin_login     = isset( $wp_travel_engine_settings['enable_linkedin_login'] ) ? $wp_travel_engine_settings['enable_linkedin_login'] : 'no';
$social_networking_sites   = array(
	'Facebook' => 'facebook',
	'Google'   => 'google',
	'LinkedIn' => 'linkedin',
);
?>
<div class="wpte-field wpte-floated">
	<span class="wpte-tooltip">
		<?php esc_html_e( 'Note: Please go through ', 'wp-travel-engine' ); ?><a href="https://docs.wptravelengine.com/docs/social-login" target="_blank">this</a><?php esc_html_e( ' doc to learn how to get client id and secret for each of the social login integration', 'wp-travel-engine' ); ?>
	</span>
</div>
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" for="wp_travel_engine_settings[enable_social_login]"><?php esc_html_e( 'Enable Social Login', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[enable_social_login]" value="no">
		<input type="checkbox"
		login-integration="sociallogin"
		data-onchange
		data-onchange-toggle-target="[data-social-login-section]"
		data-onchange-toggle-off-value="no"
		id="wp_travel_engine_settings[enable_social_login]" name="wp_travel_engine_settings[enable_social_login]" value="yes" <?php checked( $enable_social_login, 'yes' ); ?>>
		<label for="wp_travel_engine_settings[enable_social_login]"></label>
	</div>
</div>
<?php $display_class = isset( $enable_social_login ) && 'yes' === $enable_social_login ? 'show' : 'hide'; ?>
	<div class="wpte-field-subfields" data-social-login-section class="wpte-login-settings <?php wptravelengine_hidden_class( isset( $enable_social_login ) && 'yes' !== $enable_social_login, true ); ?>" login-integration="sociallogin">
	<?php
	foreach ( $social_networking_sites as $social_logins => $social_network ) {
		?>
			<div class="wpte-field wpte-checkbox advance-checkbox">
			<label class="wpte-field-label" for="wp_travel_engine_settings[enable_<?php echo $social_network; ?>_login]"><?php printf( __( 'Enable %s', 'wp-travel-engine' ), $social_logins ); ?></label>
			<div class="wpte-checkbox-wrap">
				<input type="hidden" name="wp_travel_engine_settings[enable_<?php echo $social_network; ?>_login]" value="no">
				<input login-integration="<?php echo $social_network; ?>"
				class="wpte-<?php echo $social_network; ?>-checkbox" type="checkbox" id="wp_travel_engine_settings[enable_<?php echo $social_network; ?>_login]" name="wp_travel_engine_settings[enable_<?php echo $social_network; ?>_login]" value="yes"
				data-onchange
				data-onchange-toggle-target="[data-social-login-<?php echo esc_attr( $social_network ); ?>]"
				data-onchange-toggle-off-value="no"
				<?php checked( $wp_travel_engine_settings[ 'enable_' . $social_network . '_login' ], 'yes' ); ?>>
				<label for="wp_travel_engine_settings[enable_<?php echo $social_network; ?>_login]"></label>
			</div>
		</div>
		<?php
		$display_class = isset( $wp_travel_engine_settings[ "enable_{$social_network}_login" ] ) && 'yes' === $wp_travel_engine_settings[ "enable_{$social_network}_login" ] ? 'show' : 'hide';
		?>
		<div data-social-login-<?php echo esc_attr( $social_network ); ?>
		class="wpte-field-subfields wpte-field wpte-field-wrapper <?php wptravelengine_hidden_class( isset( $wp_travel_engine_settings[ "enable_{$social_network}_login" ] ) && 'yes' !== $wp_travel_engine_settings[ "enable_{$social_network}_login" ], true ); ?> login-integration="<?php echo $social_network; ?>">
			<div class="wpte-field wpte-floated wpte-social-login-client-credentials">
				<label for="wp_travel_engine_settings[<?php echo $social_network; ?>_client_id]" class="wpte-field-label"><?php esc_html_e( 'Client ID', 'wp-travel-engine' ); ?></label>
				<input type="text" id="wp_travel_engine_settings[<?php echo $social_network; ?>_client_id]" name="wp_travel_engine_settings[<?php echo $social_network; ?>_client_id]" value="<?php echo esc_attr( isset( $wp_travel_engine_settings[ '' . $social_network . '_client_id' ] ) ? esc_attr( $wp_travel_engine_settings[ '' . $social_network . '_client_id' ] ) : '' ); ?>" placeholder="<?php esc_attr_e( '' . $social_logins . ' Client Id', 'wp-travel-engine' ); ?>">
				<?php $show = empty( $wp_travel_engine_settings[ "{$social_network}_client_id" ] ) ? '' : ' hidden'; ?>
				<span class="wpte-tooltip<?php echo esc_attr( $show ); ?>" style="color:#d63638;padding-left:160px;"><?php printf( __( 'Please enter a valid client id for %s.', 'wp-travel-engine' ), esc_html( $social_logins ) ); ?></span>
			</div>
			<div class="wpte-field wpte-floated wpte-social-login-client-credentials">
				<label for="wp_travel_engine_settings[<?php echo $social_network; ?>_client_secret]" class="wpte-field-label"><?php esc_html_e( 'Client Secret', 'wp-travel-engine' ); ?></label>
				<input type="text" id="wp_travel_engine_settings[<?php echo $social_network; ?>_client_secret]" name="wp_travel_engine_settings[<?php echo $social_network; ?>_client_secret]" value="<?php echo esc_attr( isset( $wp_travel_engine_settings[ '' . $social_network . '_client_secret' ] ) ? esc_attr( $wp_travel_engine_settings[ '' . $social_network . '_client_secret' ] ) : '' ); ?>" placeholder="<?php esc_attr_e( '' . $social_logins . ' Client Secret', 'wp-travel-engine' ); ?>">
				<?php $show = empty( $wp_travel_engine_settings[ "{$social_network}_client_secret" ] ) ? '' : ' hidden'; ?>
				<span class="wpte-tooltip<?php echo esc_attr( $show ); ?>" style="color:#d63638;padding-left:160px;"><?php printf( __( 'Please enter a valid client secret for %s.', 'wp-travel-engine' ), esc_html( $social_logins ) ); ?></span>
			</div>
		</div>
		<?php
	}
	?>
	</div>
</div>
<script>
; (function () {
	var socialLoginCredentials = document.querySelectorAll(".wpte-social-login-client-credentials")
	if (socialLoginCredentials) {
		socialLoginCredentials.forEach(function (wrapper) {
			var input = wrapper.querySelector('input')
			var tooltip = wrapper.querySelector('.wpte-tooltip')
			input.addEventListener('keyup', function () {
				tooltip.classList.toggle('hidden', this.value.length > 0)
			})
		})
	}
})();
</script>
