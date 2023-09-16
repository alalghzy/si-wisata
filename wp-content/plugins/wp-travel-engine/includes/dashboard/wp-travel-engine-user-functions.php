<?php
/**
 * User Functions.
 *
 * @package Wp_Travel
 */
/**
 * Prevent any user who cannot 'edit_posts' (subscribers, customers etc) from seeing the admin bar.
 *
 * Note: get_option( 'wp_travel_engine_lock_down_admin', true ) is a deprecated option here for backwards compatibility. Defaults to true.
 *
 * @access public
 * @param bool $show_admin_bar
 * @return bool
 */
function wp_travel_engine_disable_admin_bar( $show_admin_bar ) {
	if ( apply_filters( 'wp_travel_engine_disable_admin_bar', ! current_user_can( 'edit_posts' ) ) ) {
		$show_admin_bar = false;
	}

	return $show_admin_bar;
}
add_filter( 'show_admin_bar', 'wp_travel_engine_disable_admin_bar', 10, 1 );

if ( ! function_exists( 'wp_travel_engine_create_new_customer' ) ) {

	/**
	 * Create a new customer.
	 *
	 * @param  string $email Customer email.
	 * @param  string $username Customer username.
	 * @param  string $password Customer password.
	 * @return int|WP_Error Returns WP_Error on failure, Int (user ID) on success.
	 */
	function wp_travel_engine_create_new_customer( $email, $username = '', $password = '' ) {

		$settings = wp_travel_engine_get_settings();

		$generate_username_from_email = isset( $settings['generate_username_from_email'] ) ? $settings['generate_username_from_email'] : 'no';
		$generate_user_password       = isset( $settings['generate_user_password'] ) ? $settings['generate_user_password'] : 'no';

		// Check the email address.
		if ( empty( $email ) || ! is_email( $email ) ) {
			return new WP_Error( 'registration-error-invalid-email', __( 'Please provide a valid email address.', 'wp-travel-engine' ) );
		}

		if ( email_exists( $email ) ) {
			return new WP_Error( 'registration-error-email-exists', apply_filters( 'wp_travel_engine_registration_error_email_exists', __( 'An account is already registered with your email address. Please log in.', 'wp-travel-engine' ), $email ) );
		}

		// Handle username creation.
		if ( 'no' === $generate_username_from_email || ! empty( $username ) ) {
			$username = sanitize_user( $username );

			if ( empty( $username ) || ! validate_username( $username ) ) {
				return new WP_Error( 'registration-error-invalid-username', __( 'Please enter a valid account username.', 'wp-travel-engine' ) );
			}

			if ( username_exists( $username ) ) {
				return new WP_Error( 'registration-error-username-exists', __( 'An account is already registered with that username. Please choose another.', 'wp-travel-engine' ) );
			}
		} else {
			$username = sanitize_user( current( explode( '@', $email ) ), true );

			// Ensure username is unique.
			$append     = 1;
			$o_username = $username;

			while ( username_exists( $username ) ) {
				$username = $o_username . $append;
				$append++;
			}
		}

		// Handle password creation.
		if ( 'yes' === $generate_user_password && empty( $password ) ) {
			$password           = wp_generate_password();
			$password_generated = true;
		} elseif ( empty( $password ) ) {
			return new WP_Error( 'registration-error-missing-password', __( 'Please enter an account password.', 'wp-travel-engine' ) );
		} else {
			$password_generated = false;
		}

		// Use WP_Error to handle registration errors.
		$errors = new WP_Error();

		// do_action( 'wp_travel_engine_register_post', $username, $email, $errors );

		$errors = apply_filters( 'wp_travel_engine_registration_errors', $errors, $username, $email );

		if ( $errors->get_error_code() ) {
			return $errors;
		}

		$new_customer_data = apply_filters(
			'wp_travel_engine_new_customer_data',
			array(
				'user_login' => $username,
				'user_pass'  => $password,
				'user_email' => $email,
				'role'       => 'wp-travel-engine-customer',
			)
		);

		$customer_id = wp_insert_user( $new_customer_data );

		if ( is_wp_error( $customer_id ) ) {
			return new WP_Error( 'registration-error', __( 'Error:', 'wp-travel-engine' ) . __( 'Couldn&#8217;t register you&hellip; please contact us if you continue to have problems.', 'wp-travel-engine' ) );
		}

		do_action( 'wp_travel_engine_created_customer', $customer_id, $new_customer_data, $password_generated, $template = 'emails/customer-new-account.php' );

		return $customer_id;
	}
}

/**
 * Login a member (set auth cookie and set global user object).
 *
 * @param int $customer_id
 */
function wp_travel_engine_set_customer_auth_cookie( $customer_id ) {
	global $current_user;

	$current_user = get_user_by( 'id', $customer_id );

	wp_set_auth_cookie( $customer_id, true );
}

/**
 * Get endpoint URL.
 *
 * Gets the URL for an endpoint, which varies depending on permalink settings.
 *
 * @param  string $endpoint  Endpoint slug.
 * @param  string $value     Query param value.
 * @param  string $permalink Permalink.
 *
 * @return string
 */
function wp_travel_engine_get_endpoint_url( $endpoint, $value = '', $permalink = '' ) {
	if ( ! $permalink ) {
		$permalink = get_permalink();
	}

	// Map endpoint to options.
	$query_class = new WP_Travel_Engine_Query();
	$query_vars  = $query_class->get_query_vars();
	$endpoint    = ! empty( $query_vars[ $endpoint ] ) ? $query_vars[ $endpoint ] : $endpoint;

	if ( get_option( 'permalink_structure' ) ) {
		if ( strstr( $permalink, '?' ) ) {
			$query_string = '?' . wp_parse_url( $permalink, PHP_URL_QUERY );
			$permalink    = current( explode( '?', $permalink ) );
		} else {
			$query_string = '';
		}
		$url = trailingslashit( $permalink ) . trailingslashit( $endpoint );

		if ( $value ) {
			$url .= trailingslashit( $value );
		}

		$url .= $query_string;
	} else {
		$url = add_query_arg( $endpoint, $value, $permalink );
	}

	return apply_filters( 'wp_travel_engine_get_endpoint_url', $url, $endpoint, $value, $permalink );
}

/**
 * Returns the url to the lost password endpoint url.
 *
 * @return string
 */
function wp_travel_engine_lostpassword_url() {
	$default_url = wp_lostpassword_url();
	// Avoid loading too early.
	if ( ! did_action( 'init' ) ) {
		$url = $default_url;
	} else {
		// Don't redirect to the WP Travel endpoint on global network admin lost passwords.
		if ( is_multisite() && isset( $_GET['redirect_to'] ) && false !== strpos( wp_unslash( $_GET['redirect_to'] ), network_admin_url() ) ) { // phpcs:ignore
			$url = $default_url;
		} else {
			$wp_travel_engine_account_page_url    = wp_travel_engine_get_page_permalink_by_id( wp_travel_engine_get_dashboard_page_id() );
			$wp_travel_engine_account_page_exists = wp_travel_engine_get_dashboard_page_id() > 0;

			if ( $wp_travel_engine_account_page_exists ) {
				$url = $wp_travel_engine_account_page_url . '?action=lost-pass';
			} else {
				$url = $default_url;
			}
		}
	}
	return apply_filters( 'wp_travel_engine_lostpassword_url', $url, $default_url );
}
function wte_get_user_by_id_or_email( $id_or_email ) {
	$user    = false;
	$user_id = false;
	if ( is_numeric( $id_or_email ) ) :
		$id   = (int) $id_or_email;
		$user = get_user_by( 'id', $id );
	elseif ( is_object( $id_or_email ) ) :
		if ( ! empty( $id_or_email->user_id ) ) :
			$id   = (int) $id_or_email->user_id;
			$user = get_user_by( 'id', $id );
		endif;
	else :
		$user = get_user_by( 'email', $id_or_email );
	endif;

	if ( $user && is_object( $user ) ) :
		$user_id = $user->data->ID;
	endif;
	return $user_id;
}
function wte_get_custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {

	$user_id    = wte_get_user_by_id_or_email( $id_or_email );
	$users_meta = get_user_meta( $user_id, 'wte_users_meta', true );
	if ( isset( $users_meta['user_profile_image_id'] ) && $users_meta['user_profile_image_id'] ) :
		$src    = wp_get_attachment_image_src( $users_meta['user_profile_image_id'], array( $size, $size ) );
		$src    = ( isset( $src[0] ) && $src[0] ? $src[0] : $src );
		$avatar = "<img alt='{$alt}' src='{$src}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
	endif;
	return $avatar;

}

add_filter( 'get_avatar', 'wte_get_custom_avatar', 1, 5 );

function wte_get_custom_avatar_url( $url, $id_or_email, $args ) {
	$user_id    = wte_get_user_by_id_or_email( $id_or_email );
	$users_meta = get_user_meta( $user_id, 'wte_users_meta', true );
	if ( isset( $users_meta['user_profile_image_id'] ) && $users_meta['user_profile_image_id'] ) :
		$url = wp_get_attachment_image_src( $users_meta['user_profile_image_id'], 'full' );
		$url = ( isset( $url[0] ) && $url[0] ? $url[0] : $url );
	endif;
	return $url;

}

add_filter( 'get_avatar_url', 'wte_get_custom_avatar_url', 1, 3 );
