<?php
// Form handling for Dashboard.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handle frontend forms.
 *
 * @class       Wp_Travel_Engine_Form_Handler
 * @version     1.3.3
 * @category    Class
 */
class Wp_Travel_Engine_Form_Handler {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'redirect_reset_password_link' ) );
		add_action( 'template_redirect', array( __CLASS__, 'save_account_details' ) );
		add_action( 'template_redirect', array( __CLASS__, 'update_user_billing_data' ) );
		add_action( 'wp_loaded', array( __CLASS__, 'process_login' ), 20 );
		add_action( 'wp_loaded', array( __CLASS__, 'process_registration' ), 20 );
		add_action( 'wp_loaded', array( __CLASS__, 'process_lost_password' ), 20 );
		add_action( 'wp_loaded', array( __CLASS__, 'process_reset_password' ), 20 );
	}

	/**
	 * Process the login form.
	 */
	public static function process_login() {

		if ( isset( $_POST['_wpnonce'] ) ) {
			$nonce_value = sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) );
		}

		if ( isset( $_POST['wp-travel-engine-login-nonce'] ) ) {
			$nonce_value = sanitize_text_field( wp_unslash( $_POST['wp-travel-engine-login-nonce'] ) );
		}

		if ( ! isset( $nonce_value ) ) {
			return;
		}

		if ( ! empty( $_POST['login'] ) && wp_verify_nonce( $nonce_value, 'wp-travel-engine-login' ) ) {

			try {
				$creds = array();
				if ( isset( $_POST['username'] ) ) {
					$creds['user_login'] = trim( sanitize_text_field( wp_unslash( $_POST['username'] ) ) );
				}
				if ( isset( $_POST['password'] ) ) {
					$creds['user_password'] = trim( sanitize_text_field( wp_unslash( $_POST['password'] ) ) );
				}
				if ( isset( $_POST['rememberme'] ) ) {
					$creds['remember'] = trim( sanitize_text_field( wp_unslash( $_POST['rememberme'] ) ) );
				}

				$validation_error = new WP_Error();
				$validation_error = apply_filters( 'wp_travel_engine_process_login_errors', $validation_error, $creds['user_login'], $creds['user_password'] );

				if ( $validation_error->get_error_code() ) {
					throw new Exception( __( 'Error:', 'wp-travel-engine' ) . $validation_error->get_error_message() );
				}

				if ( empty( $creds['user_login'] ) ) {
					throw new Exception( __( 'Error:', 'wp-travel-engine' ) . __( 'Username is required.', 'wp-travel-engine' ) );
				}

				// On multisite, ensure user exists on current site, if not add them before allowing login.
				if ( is_multisite() ) {
					$user_data = get_user_by( is_email( $creds['user_login'] ) ? 'email' : 'login', $creds['user_login'] );

					if ( $user_data && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
						add_user_to_blog( get_current_blog_id(), $user_data->ID, 'wp-travel-engine-customer' );
					}
				}

				// Perform the login.
				$user = wp_signon( apply_filters( 'wp_travel_login_credentials', $creds ), is_ssl() );

				if ( is_wp_error( $user ) ) {
					$message = $user->get_error_message();
					$message = str_replace( '<strong>' . esc_html( $creds['user_login'] ) . '</strong>', '<strong>' . esc_html( $creds['user_login'] ) . '</strong>', $message );
					throw new Exception( $message );
				} else {

					if ( ! empty( $_POST['redirect'] ) ) {
						$redirect = wp_sanitize_redirect( wp_unslash( $_POST['redirect'] ) );
					} elseif ( wp_travel_engine_get_raw_referer() ) {
						$redirect = wp_travel_engine_get_raw_referer();
					} else {
						$redirect = wp_travel_engine_get_page_permalink_by_id( wp_travel_engine_get_dashboard_page_id() );
					}

					wp_redirect( wp_validate_redirect( apply_filters( 'wp_travel_engine_login_redirect', remove_query_arg( 'wp_travel_engine_error', $redirect ), $user ), wp_travel_engine_get_page_permalink_by_id( wp_travel_engine_get_dashboard_page_id() ) ) );

					exit;
				}
			} catch ( Exception $e ) {

				WTE()->notices->add( apply_filters( 'wp_travel_engine_login_errors', __( 'Error :  Invalid Username or Password', 'wp-travel-engine' ) ), 'error' );

			}
		} elseif ( isset( $_POST['username'] ) && empty( $_POST['username'] ) && wp_verify_nonce( $nonce_value, 'wp-travel-engine-login' ) ) {

			WTE()->notices->add( apply_filters( 'wp_travel_engine_login_errors', __( 'Error :  Username can not be empty', 'wp-travel-engine' ) ), 'error' );

		}
	}

	/**
	 * Process the registration form.
	 */
	public static function process_registration() {

		if ( isset( $_POST['_wpnonce'] ) ) {
			$nonce_value = sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) );
		}

		if ( isset( $_POST['wp-travel-engine-register-nonce'] ) ) {
			$nonce_value = sanitize_text_field( wp_unslash( $_POST['wp-travel-engine-register-nonce'] ) );
		}

		if ( ! empty( $_POST['register'] ) && wp_verify_nonce( $nonce_value, 'wp-travel-engine-register' ) ) {
			$settings = wp_travel_engine_get_settings();

			$generate_username_from_email = isset( $settings['generate_username_from_email'] ) ? $settings['generate_username_from_email'] : 'no';
			$generate_user_password       = isset( $settings['generate_user_password'] ) ? $settings['generate_user_password'] : 'no';

			if ( 'no' === $generate_username_from_email && isset( $_POST['username'] ) ) {
				$username = sanitize_text_field( wp_unslash( $_POST['username'] ) );
			}
			if ( 'no' === $generate_user_password && isset( $_POST['password'] ) ) {
				$password = sanitize_text_field( wp_unslash( $_POST['password'] ) );
			}
			if ( isset( $_POST['email'] ) ) {
				$email = sanitize_email( wp_unslash( $_POST['email'] ) );
			}

			try {
				$validation_error = new WP_Error();
				$validation_error = apply_filters( 'wp_travel_process_registration_errors', $validation_error, $username, $password, $email );

				if ( $validation_error->get_error_code() ) {
					throw new Exception( $validation_error->get_error_message() );
				}

				$new_customer = wp_travel_engine_create_new_customer( sanitize_email( $email ), $username, $password );

				if ( is_wp_error( $new_customer ) ) {
					throw new Exception( $new_customer->get_error_message() );
				}

				if ( apply_filters( 'wp_travel_registration_auth_new_customer', true, $new_customer ) ) {
					wp_travel_engine_set_customer_auth_cookie( $new_customer );
				}

				if ( ! empty( $_POST['redirect'] ) ) {
					$redirect = wp_sanitize_redirect( wp_unslash( $_POST['redirect'] ) );
				} elseif ( wp_travel_engine_get_raw_referer() ) {
					$redirect = wp_travel_engine_get_raw_referer();
				} else {
					$redirect = wp_travel_engine_get_page_permalink_by_id( wp_travel_engine_get_dashboard_page_id() );
				}

				wp_redirect( wp_validate_redirect( apply_filters( 'wp_travel_register_redirect', remove_query_arg( 'wp_travel_error', $redirect ), $username ), wp_travel_engine_get_page_permalink_by_id( wp_travel_engine_get_dashboard_page_id() ) ) );
				exit;

			} catch ( Exception $e ) {
				WTE()->notices->add( __( 'Error:', 'wp-travel-engine' ) . $e->getMessage(), 'error' );
			}
		}
	}

	/**
	 * Handle lost password form.
	 */
	public static function process_lost_password() {
		if ( isset( $_POST['_wpnonce'] ) ) {
			if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'wp_travel_engine_lost_password' ) && isset( $_POST['wp_travel_engine_reset_password'] ) && isset( $_POST['user_login'] ) ) {
				$success = Wp_Travel_Engine_User_Account::retrieve_password();

				// If successful, redirect to my account with query arg set.
				if ( $success ) {
					wp_redirect( add_query_arg( 'reset-link-sent', 'true', wp_travel_engine_lostpassword_url() ) );
					exit;
				}
			}
		}
	}

	/**
	 * Handle reset password form.
	 */
	public static function process_reset_password() {
		$posted_fields = array( 'wp_travel_engine_reset_password', 'password_1', 'password_2', 'reset_key', 'reset_login', '_wpnonce' );

		foreach ( $posted_fields as $field ) {
			if ( ! isset( $_POST[ $field ] ) ) {
				return;
			}
			$posted_fields[ $field ] = sanitize_text_field( wp_unslash( $_POST[ $field ] ) );
		}

		if ( ! wp_verify_nonce( $posted_fields['_wpnonce'], 'wp_travel_engine_reset_password_nonce' ) ) {
			return;
		}

		$user = Wp_Travel_Engine_User_Account::check_password_reset_key( $posted_fields['reset_key'], $posted_fields['reset_login'] );

		if ( $user instanceof WP_User ) {
			if ( empty( $posted_fields['password_1'] ) ) {
				WTE()->notices->add( __( 'Error :  Please enter your password.', 'wp-travel-engine' ), 'error' );
			}

			if ( $posted_fields['password_1'] !== $posted_fields['password_2'] ) {
				WTE()->notices->add( __( 'Error :  Passwords do not match', 'wp-travel-engine' ), 'error' );
			}

			$errors = new WP_Error();

			do_action( 'validate_password_reset', $errors, $user );

			wp_travel_engine_add_wp_error_notices( $errors );

			if ( 0 === wp_travel_engine_get_notice_count( 'error' ) ) {
				Wp_Travel_Engine_User_Account::reset_password( $user, $posted_fields['password_1'] );

				do_action( 'wp_travel_customer_reset_password', $user );

				wp_redirect( add_query_arg( 'password-reset', 'true', wp_travel_engine_get_page_permalink_by_id( wp_travel_engine_get_dashboard_page_id() ) ) );
				exit;
			}
		}
	}

	/**
	 * Remove key and login from query string, set cookie, and redirect to account page to show the form.
	 */
	public static function redirect_reset_password_link() {

		if ( wp_travel_engine_is_account_page() && ! empty( $_GET['key'] ) && ! empty( $_GET['login'] ) ) {

			$value = sprintf( '%s:%s', sanitize_text_field( wp_unslash( $_GET['login'] ) ), sanitize_text_field( wp_unslash( $_GET['key'] ) ) );

			Wp_Travel_Engine_User_Account::set_reset_password_cookie( $value );

			wp_safe_redirect( add_query_arg( 'show-reset-form', 'true', wp_travel_engine_lostpassword_url() ) );
			exit;
		}
	}
	/**
	 * Update User Billing Data.
	 */
	public static function update_user_billing_data() {

		if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) ) {
			return;
		}

		if ( empty( $_POST['action'] ) || 'wp_travel_engine_save_user_meta_billing_address' !== $_POST['action'] || empty( $_POST['wp_billing_address_security'] ) || ! wp_verify_nonce( $_POST['wp_billing_address_security'], 'wp_travel_engine_save_user_meta_billing_address' ) ) {
			return;
		}

		nocache_headers();

		$user_id = get_current_user_id();

		if ( $user_id <= 0 ) {
			return;
		}

		$current_user = get_user_by( 'id', $user_id );

		// Get Billing Data.
		$billing_address  = ! empty( $_POST['customer_billing_address'] ) ? wp_travel_engine_clean_vars( $_POST['customer_billing_address'] ) : '';
		$billing_city     = ! empty( $_POST['customer_billing_city'] ) ? wp_travel_engine_clean_vars( $_POST['customer_billing_city'] ) : '';
		$billing_company  = ! empty( $_POST['customer_billing_company'] ) ? wp_travel_engine_clean_vars( $_POST['customer_billing_company'] ) : '';
		$billing_zip_code = ! empty( $_POST['customer_zip_code'] ) ? wp_travel_engine_clean_vars( $_POST['customer_zip_code'] ) : '';
		$billing_country  = ! empty( $_POST['customer_country'] ) ? wp_travel_engine_clean_vars( $_POST['customer_country'] ) : '';
		$billing_phone    = ! empty( $_POST['customer_phone'] ) ? wp_travel_engine_clean_vars( $_POST['customer_phone'] ) : '';

		// Handle required fields.
		$required_fields = apply_filters(
			'wp_travel_engine_save_customer_billing_details_required_fields',
			array(
				'customer_billing_address' => __( 'Billing Address', 'wp-travel-engine' ),
				'customer_zip_code'        => __( 'ZIP Code', 'wp-travel-engine' ),
				'customer_country'         => __( 'Country', 'wp-travel-engine' ),
				'customer_phone'           => __( 'Phone', 'wp-travel-engine' ),
			)
		);

		foreach ( $required_fields as $field_key => $field_name ) {
			if ( empty( $_POST[ $field_key ] ) ) {
				WTE()->notices->add( sprintf( __( 'Error :   %s is a required field.', 'wp-travel-engine' ), esc_html( $field_name ) ), 'error' );
			}
		}

		if ( wp_travel_engine_get_notice_count( 'error' ) === 0 ) {

			$data_array = array(
				'billing_address'  => $billing_address,
				'billing_city'     => $billing_city,
				'billing_zip_code' => $billing_zip_code,
				'billing_country'  => $billing_country,
				'billing_phone'    => $billing_phone,
			);

			update_user_meta( $user_id, 'wp_travel_engine_customer_billing_details', $data_array );

			WTE()->notices->add( __( 'Billing Details Updated Successfully', 'wp-travel-engine' ), 'success' );

			do_action( 'wp_travel_engine_save_billing_details', $user_id );

			wp_safe_redirect( wp_travel_engine_get_page_permalink_by_id( wp_travel_engine_get_dashboard_page_id() ) );
			exit;
		}

	}

	/**
	 * Save the password/account details and redirect back to the my account page.
	 */
	public static function save_account_details() {
		if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) ) {
			return;
		}

		if ( empty( $_POST['action'] ) || 'wp_travel_engine_save_account_details' !== $_POST['action'] || empty( $_POST['wp_account_details_security'] ) || ! wp_verify_nonce( $_POST['wp_account_details_security'], 'wp_travel_engine_save_account_details' ) ) {
			return;
		}

		nocache_headers();

		$user_id = get_current_user_id();

		if ( $user_id <= 0 ) {
			return;
		}

		$current_user       = get_user_by( 'id', $user_id );
		$current_first_name = $current_user->first_name;
		$current_last_name  = $current_user->last_name;
		$current_email      = $current_user->user_email;

		$account_first_name = ! empty( $_POST['account_first_name'] ) ? wp_travel_engine_clean_vars( $_POST['account_first_name'] ) : '';
		$account_last_name  = ! empty( $_POST['account_last_name'] ) ? wp_travel_engine_clean_vars( $_POST['account_last_name'] ) : '';
		$account_email      = ! empty( $_POST['account_email'] ) ? wp_travel_engine_clean_vars( $_POST['account_email'] ) : '';
		$pass_cur           = ! empty( $_POST['password_current'] ) ? wte_clean( wp_unslash( $_POST['password_current'] ) ) : '';
		$pass1              = ! empty( $_POST['password_1'] ) ? wte_clean( wp_unslash( $_POST['password_1'] ) ) : '';
		$pass2              = ! empty( $_POST['password_2'] ) ? wte_clean( wp_unslash( $_POST['password_2'] ) ) : '';
		$save_pass          = true;

		$user             = new stdClass();
		$user->ID         = $user_id;
		$user->first_name = $account_first_name;
		$user->last_name  = $account_last_name;

		// Prevent emails being displayed, or leave alone.
		$user->display_name = is_email( $current_user->display_name ) ? $user->first_name : $current_user->display_name;

		// Handle required fields.
		$required_fields = apply_filters(
			'wp_travel_engine_save_account_details_required_fields',
			array(
				'account_first_name' => __( 'First name', 'wp-travel-engine' ),
				'account_last_name'  => __( 'Last name', 'wp-travel-engine' ),
				'account_email'      => __( 'Email address', 'wp-travel-engine' ),
			)
		);

		foreach ( $required_fields as $field_key => $field_name ) {
			if ( empty( $_POST[ $field_key ] ) ) {
				WTE()->notices->add( sprintf( __( 'Error :   %s is a required field.', 'wp-travel-engine' ), esc_html( $field_name ) ), 'error' );
			}
		}

		if ( $account_email ) {
			$account_email = sanitize_email( $account_email );
			if ( ! is_email( $account_email ) ) {
				WTE()->notices->add( __( 'Please Provide a valid email address', 'wp-travel-engine' ), 'error' );
			} elseif ( email_exists( $account_email ) && $account_email !== $current_user->user_email ) {
				WTE()->notices->add( __( 'The email address is already registered', 'wp-travel-engine' ), 'error' );
			}
			$user->user_email = $account_email;
		}

		if ( ! empty( $pass_cur ) && empty( $pass1 ) && empty( $pass2 ) ) {
			WTE()->notices->add( __( 'Please Fill Out All Password Fields.', 'wp-travel-engine' ), 'error' );
			$save_pass = false;
		} elseif ( ! empty( $pass1 ) && empty( $pass_cur ) ) {
			WTE()->notices->add( __( 'Please Enter Your Current Password', 'wp-travel-engine' ), 'error' );
			$save_pass = false;
		} elseif ( ! empty( $pass1 ) && empty( $pass2 ) ) {
			WTE()->notices->add( __( 'Please re-enter your password', 'wp-travel-engine' ), 'error' );
			$save_pass = false;
		} elseif ( ( ! empty( $pass1 ) || ! empty( $pass2 ) ) && $pass1 !== $pass2 ) {
			WTE()->notices->add( __( 'New Passwords do not match', 'wp-travel-engine' ), 'error' );
			$save_pass = false;
		} elseif ( ! empty( $pass1 ) && ! wp_check_password( $pass_cur, $current_user->user_pass, $current_user->ID ) ) {
			WTE()->notices->add( __( 'Your current password is incorrect', 'wp-travel-engine' ), 'error' );
			$save_pass = false;
		}

		if ( $pass1 && $save_pass ) {
			$user->user_pass = $pass1;
		}

		$users_meta = get_user_meta( $user_id, 'wte_users_meta', true );
		if ( ! is_array( $users_meta ) || empty( $users_meta ) ) :
			$users_meta = array();
		endif;

		$user_profile_image_file = isset( $_POST['user_profile_image'] ) && ! empty( $_POST['user_profile_image'] ) ? sanitize_text_field( wp_normalize_path( $_POST['user_profile_image'] ) ) : false;
		$user_profile_image_url  = isset( $_POST['user_profile_image_url'] ) && ! empty( $_POST['user_profile_image_url'] ) ? esc_url_raw( $_POST['user_profile_image_url'] ) : false;
		if ( $user_profile_image_file && $user_profile_image_url && $user_profile_image_file != 'custom-image' ) :

			if ( isset( $users_meta['user_profile_image_id'] ) && is_numeric( $users_meta['user_profile_image_id'] ) ) :
				wp_delete_attachment( $users_meta['user_profile_image_id'] );
			endif;

			$attached_img_id = self::set_user_profile_image( $user_profile_image_file, $user_id );

			if ( ! $attached_img_id ) :
				WTE()->notices->add( __( 'There was an issue updating your profile photo.', 'wp-travel-engine' ), 'error' );
			else :
				$users_meta['user_profile_image_id'] = absint( $attached_img_id );
				update_user_meta( $user_id, 'wte_users_meta', $users_meta );
			endif;

		elseif ( ! $user_profile_image_file ) :
			if ( isset( $users_meta['user_profile_image_id'] ) && is_numeric( $users_meta['user_profile_image_id'] ) ) :
				wp_delete_attachment( $users_meta['user_profile_image_id'] );
			endif;

			$users_meta['user_profile_image_id'] = false;
			update_user_meta( $user_id, 'wte_users_meta', $users_meta );
		endif;
		// Allow plugins to return their own errors.
		$errors = new WP_Error();
		do_action_ref_array( 'wp_travel_engine_save_account_details_errors', array( &$errors, &$user ) );

		if ( $errors->get_error_messages() ) {
			foreach ( $errors->get_error_messages() as $error ) {
				WTE()->notices->add( $error, 'error' );
			}
		}

		if ( wp_travel_engine_get_notice_count( 'error' ) === 0 ) {
			wp_update_user( $user );

			WTE()->notices->add( __( 'Account Details Updated Successfully', 'wp-travel-engine' ), 'success' );

			do_action( 'wp_travel_engine_save_account_details', $user->ID );

			wp_safe_redirect( wp_travel_engine_get_page_permalink_by_id( wp_travel_engine_get_dashboard_page_id() ) );
			exit;
		}
	}
	/**
	 * Set user dashboard profile image
	 *
	 * @param boolean $image_url
	 * @param boolean $user_id
	 * @return void
	 */
	public static function set_user_profile_image( $image_url = false, $user_id = false ) {

		if ( $image_url && $user_id ) :

			$users_meta = get_user_meta( $user_id, 'wte_users_meta' );

			if ( ! is_array( $users_meta ) ) :
				$users_meta = array();
			endif;

			$img_upload_dir = wp_upload_dir();
			$wte_image_dir  = trailingslashit( $img_upload_dir['basedir'] ) . 'wp-travel-engine/images/users';
			$img_filetype   = wp_check_filetype( $image_url, null );
			$img_file_ext   = isset( $img_filetype['ext'] ) ? $img_filetype['ext'] : '.jpg';

			$img_file_name = $wte_image_dir . '/wte_users_' . $user_id . '.' . $img_file_ext;
			if ( wp_mkdir_p( $wte_image_dir ) ) :

				if ( file_exists( $image_url ) || file_exists( $wte_image_dir . '/wte_users_' . $user_id . '.' . $img_file_ext ) ) :

					if ( file_exists( $wte_image_dir . '/wte_users_' . $user_id . '.' . $img_file_ext ) ) :
						unlink( $wte_image_dir . '/wte_users_' . $user_id . '.' . $img_file_ext );
					endif;

					if ( file_exists( $image_url ) ) :
						rename( $image_url, $wte_image_dir . '/wte_users_' . $user_id . '.' . $img_file_ext );
					endif;

				endif;

			endif;

			$attachment = array(
				'post_mime_type' => $img_filetype['type'],
				'post_title'     => sanitize_file_name( 'wte_users_' . $user_id . '.' . $img_file_ext ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);

			$attached_img_id = wp_insert_attachment( $attachment, $img_file_name );

			if ( defined( 'ABSPATH' ) && file_exists( ABSPATH . 'wp-admin/includes/image.php' ) ) :

				require_once ABSPATH . 'wp-admin/includes/image.php';

				$attach_data                         = wp_generate_attachment_metadata( $attached_img_id, $img_file_name );
				$update_attachment                   = wp_update_attachment_metadata( $attached_img_id, $attach_data );
				$users_meta['user_profile_image_id'] = $attached_img_id;

				return $attached_img_id;

			endif;

		endif;

		return false;

	}

}
// Run the show.
Wp_Travel_Engine_Form_Handler::init();
