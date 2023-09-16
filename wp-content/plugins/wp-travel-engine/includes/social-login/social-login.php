<?php
if ( isset( $_POST['_wpnonce'] ) ) {
	$nonce_value = sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) );
}

if ( isset( $_POST['wp-travel-engine-register-nonce'] ) ) {
	$nonce_value = sanitize_text_field( wp_unslash( $_POST['wp-travel-engine-register-nonce'] ) );
}

if ( ! empty( $_POST['register'] ) && wp_verify_nonce( $nonce_value, 'wp-travel-engine-register' ) ) {
	$login_text = 'style=display:block';
	$reg_text   = 'style=display:none';
} else {
	$login_text = '';
	$reg_text   = 'style=display:none';
}

$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
$enable_social_login       = isset( $wp_travel_engine_settings['enable_social_login'] ) && 'yes' === $wp_travel_engine_settings['enable_social_login'];
$enable_google_login       = isset( $wp_travel_engine_settings['enable_google_login'] ) && 'yes' === $wp_travel_engine_settings['enable_google_login'];
$enable_facebook_login     = isset( $wp_travel_engine_settings['enable_facebook_login'] ) && 'yes' === $wp_travel_engine_settings['enable_facebook_login'];
$enable_linkedin_login     = isset( $wp_travel_engine_settings['enable_linkedin_login'] ) && 'yes' === $wp_travel_engine_settings['enable_linkedin_login'];
$settings                  = wp_travel_engine_get_settings();


if ( $enable_social_login ) {
	?>
	<div class="wte-social-login-wrapper">
	<?php
	if ( $enable_facebook_login && ( $wp_travel_engine_settings['facebook_client_id'] != '' && $wp_travel_engine_settings['facebook_client_secret'] != '' ) ) {
		?>
		<a href="<?php echo site_url(); ?>/wp-login.php?wte_login=facebook" class="login-with-facebook">
			<span class="social-icon">
				<svg>
					<use xlink:href="#facebook-logo"></use>
				</svg>
			</span>
			<span class="social-label wpte-login" <?php echo esc_html( $login_text ); ?>><?php esc_html_e( 'Continue with Facebook', 'wp-travel-engine' ); ?></span>
			<span class="social-label wpte-register" <?php echo esc_html( $reg_text ); ?>><?php esc_html_e( 'Signup with Facebook', 'wp-travel-engine' ); ?></span>
		</a>
			<?php
	}
	if ( $enable_google_login && ( $wp_travel_engine_settings['google_client_id'] != '' && $wp_travel_engine_settings['google_client_secret'] != '' ) ) {
		?>
		<a href="<?php echo site_url(); ?>/wp-login.php?wte_login=google" class="login-with-google">
			<span class="social-icon">
				<svg>
					<use xlink:href="#google-logo"></use>
				</svg>
			</span>
			<span class="social-label wpte-login" <?php echo esc_html( $login_text ); ?>><?php esc_html_e( 'Continue with Google', 'wp-travel-engine' ); ?></span>
			<span class="social-label wpte-register" <?php echo esc_html( $reg_text ); ?>><?php esc_html_e( 'Signup with Google', 'wp-travel-engine' ); ?></span>
		</a>
			<?php
	}
	if ( $enable_linkedin_login && ( $wp_travel_engine_settings['linkedin_client_id'] != '' && $wp_travel_engine_settings['linkedin_client_secret'] != '' ) ) {
		?>
		<a href="<?php echo site_url(); ?>/wp-login.php?wte_login=linkedin" class="login-with-linkedin">
			<span class="social-icon">
				<svg>
					<use xlink:href="#linkedin"></use>
				</svg>
			</span>
			<span class="social-label wpte-login" <?php echo esc_html( $login_text ); ?>><?php esc_html_e( 'Continue with LinkedIn', 'wp-travel-engine' ); ?></span>
			<span class="social-label wpte-register" <?php echo esc_html( $reg_text ); ?>><?php esc_html_e( 'Signup with LinkedIn', 'wp-travel-engine' ); ?></span>
		</a>
		<?php } ?>
	</div>
	<svg width="0" height="0" class="hidden">
	<symbol fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="google-logo">
		<rect width="24" height="24" fill="white"></rect>
		<path fill-rule="evenodd" clip-rule="evenodd" d="M23.04 12.2614C23.04 11.4459 22.9668 10.6618 22.8309 9.90912H12V14.3575H18.1891C17.9225 15.795 17.1123 17.013 15.8943 17.8284V20.7139H19.6109C21.7855 18.7118 23.04 15.7637 23.04 12.2614Z" fill="#4285F4"></path>
		<path fill-rule="evenodd" clip-rule="evenodd" d="M11.9995 23.4998C15.1045 23.4998 17.7077 22.47 19.6104 20.7137L15.8938 17.8282C14.864 18.5182 13.5467 18.9259 11.9995 18.9259C9.00425 18.9259 6.46902 16.903 5.5647 14.1848H1.72266V17.1644C3.61493 20.9228 7.50402 23.4998 11.9995 23.4998Z" fill="#34A853"></path>
		<path fill-rule="evenodd" clip-rule="evenodd" d="M5.56523 14.1851C5.33523 13.4951 5.20455 12.758 5.20455 12.0001C5.20455 11.2421 5.33523 10.5051 5.56523 9.81506V6.83551H1.72318C0.944318 8.38801 0.5 10.1444 0.5 12.0001C0.5 13.8557 0.944318 15.6121 1.72318 17.1646L5.56523 14.1851Z" fill="#FBBC05"></path>
		<path fill-rule="evenodd" clip-rule="evenodd" d="M11.9995 5.07386C13.6879 5.07386 15.2038 5.65409 16.3956 6.79364L19.694 3.49523C17.7024 1.63955 15.0992 0.5 11.9995 0.5C7.50402 0.5 3.61493 3.07705 1.72266 6.83545L5.5647 9.815C6.46902 7.09682 9.00425 5.07386 11.9995 5.07386Z" fill="#EA4335"></path>
	</symbol>
	<symbol fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="facebook-logo">
		<rect width="24" height="24" fill="none"></rect>
		<path d="M23.5 12.0699C23.5 5.7186 18.3513 0.569879 12 0.569879C5.64872 0.569879 0.5 5.7186 0.5 12.0699C0.5 17.8099 4.70538 22.5674 10.2031 23.4302V15.3941H7.2832V12.0699H10.2031V9.53629C10.2031 6.6541 11.92 5.06207 14.5468 5.06207C15.805 5.06207 17.1211 5.28668 17.1211 5.28668V8.11675H15.671C14.2424 8.11675 13.7969 9.00322 13.7969 9.91266V12.0699H16.9863L16.4765 15.3941H13.7969V23.4302C19.2946 22.5674 23.5 17.8099 23.5 12.0699Z" fill="currentColor"></path>
	</symbol>
	<symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" id="linkedin">
		<path d="M29.63.001H2.362C1.06.001 0 1.034 0 2.306V29.69C0 30.965 1.06 32 2.362 32h27.27C30.937 32 32 30.965 32 29.69V2.306C32 1.034 30.937.001 29.63.001z" fill="currentColor"></path>
		<path d="M4.745 11.997H9.5v15.27H4.745zm2.374-7.6c1.517 0 2.75 1.233 2.75 2.75S8.636 9.9 7.12 9.9a2.76 2.76 0 0 1-2.754-2.753 2.75 2.75 0 0 1 2.753-2.75m5.35 7.6h4.552v2.087h.063c.634-1.2 2.182-2.466 4.5-2.466 4.806 0 5.693 3.163 5.693 7.274v8.376h-4.743V19.84c0-1.77-.032-4.05-2.466-4.05-2.47 0-2.85 1.93-2.85 3.92v7.554h-4.742v-15.27z" fill="#fff"></path>
	</symbol>
	</svg>
		<?php
}
