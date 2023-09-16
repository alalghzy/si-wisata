<?php
/**
 * WP Travel Engine Social Login.
 *
 * @since 5.4.0
 * @package WP_Travel_Engine
 * @subpackage WP_Travel_Engine/includes/social-login
 */
namespace WPTravelEngine\Dashboard;

use Hybridauth\Hybridauth;
/**
 * WPTravelEngine\Dashboard\Login
 */
class Login extends Account {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	private function init_hooks() {
		add_action( 'login_init', array( $this, 'handle_login_request' ) );
		add_action( 'wp_travel_engine_social_login', array( $this, 'include_social_login_file' ) );

		$providers = apply_filters(
			'wptravelengine_login_api_providers',
			array(
				'facebook' => array( $this, 'login_with_facebook' ),
				'google'   => array( $this, 'login_with_google' ),
				'linkedin' => array( $this, 'login_with_linkedin' ),
			)
		);

		foreach ( $providers as $provider => $callback ) {
			add_action( "login_with_{$provider}", $callback );
		}
	}

	/**
	 * Handles wte login request.
	 */
	public function handle_login_request() {
		if ( isset( $_GET['wte_login'] ) ) {
			$provider = sanitize_text_field( $_GET['wte_login'] );
			do_action( "login_with_{$provider}" );
		}
	}

	/**
	 * Sets Client ID.
	 */
	public function set_client_id( $client_id ) {
		$this->client_id = $client_id;
	}

	/**
	 * Sets Client Secret.
	 */
	public function set_client_secret( $client_secret ) {
		$this->client_secret = $client_secret;
	}

	/**
	 * Login with Google.
	 */
	public function login_with_google() {
		$result = $this->wteGoogleUserData();
		$this->process_login( $result );
	}

	/**
	 * Login with Facebook.
	 */
	public function login_with_facebook() {
		$result = $this->wteFacebookUserData();
		$this->process_login( $result );
	}

	/**
	 * Login with Facebook.
	 */
	public function login_with_linkedin() {
		$result = $this->wteLinkedinUserData();
		$this->process_login( $result );
	}

	/**
	 * Process Login when result accepted.
	 */
	public function process_login( $result ) {
		if ( isset( $result->status ) && 'SUCCESS' === $result->status ) {
			$user_ids = $this->get_user_id( $result );
			if ( isset( $user_ids ) ) {
				$this->redirect_after_login( $user_ids );
			}
		} else {
			if ( isset( $_REQUEST['error'] ) ) {
				$options = get_option( 'wp_travel_engine_settings', array() );
				if ( isset( $options['pages']['wp_travel_engine_dashboard_page'] ) ) {
					$page_id = $options['pages']['wp_travel_engine_dashboard_page'];
				}
				if ( isset( $page_id ) ) {
					$redirect_to = get_permalink( $page_id );
				} else {
					$redirect_to = site_url();
				}
				$this->redirecturl( $redirect_to );
			}
			die();
		}
	}

	function base_url() {
		return dirname( $_SERVER['SCRIPT_FILENAME'] );
	}
	/**
	 * wteGoogleUserData
	 *
	 * @return object
	 */
	function wteGoogleUserData() {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		$clientID                  = $wp_travel_engine_settings['google_client_id'];
		$clientSecret              = $wp_travel_engine_settings['google_client_secret'];
		$redirectUri               = wp_login_url() . '?wte_login=google';
		$result                    = new \stdClass();

		$config = array(
			'callback'                 => $redirectUri,
			'keys'                     => array(
				'id'     => $clientID,
				'secret' => $clientSecret,
			),
			'scope'                    => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
			'authorize_url_parameters' => array(
				'approval_prompt' => 'force',
				'access_type'     => 'offline',

			),
		);
		try {
			$adapter = new \Hybridauth\Provider\Google( $config );
			try {
				$adapter->authenticate( 'Google' );
				if ( $adapter->isConnected() ) {
					$userProfile = $adapter->getUserProfile();
					$adapter->disconnect();
				}
			} catch ( \Exception $e ) {
				if ( $adapter->isConnected() ) {
					$adapter->disconnect();
				}
				echo $e->getMessage();
			}
		} catch ( \Exception $e ) {
			echo 'Oops, we ran into an issue! ' . $e->getMessage();
		}

		if ( ! empty( $userProfile ) ) {
			if ( ! empty( $userProfile->email ) ) {
				$result->email      = $userProfile->email;
				$result->status     = 'SUCCESS';
				$result->name       = $userProfile->displayName;
				$result->first_name = $userProfile->firstName;
				$result->last_name  = $userProfile->lastName;
			} else {
				$result->status = 'FAIL';
			}
		} else {
			$result->status = 'FAIL';
		}
			return $result;
	}


	/**
	 * wteFacebookUserData
	 *
	 * @return object
	 */
	function wteFacebookUserData() {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		$clientID                  = $wp_travel_engine_settings['facebook_client_id'];
		$clientSecret              = $wp_travel_engine_settings['facebook_client_secret'];
		$redirectUri               = wp_login_url() . '?wte_login=facebook';
		$result                    = new \stdClass();

		$config = array(
			'callback'                 => $redirectUri,
			'keys'                     => array(
				'id'     => $clientID,
				'secret' => $clientSecret,
			),
			'authorize_url_parameters' => array(
				'approval_prompt' => 'force',
				// 'access_type'     => 'offline',

			),
		);
		try {
			$adapter = new \Hybridauth\Provider\Facebook( $config );
			try {
				$adapter->authenticate( 'Facebook' );
				if ( $adapter->isConnected() ) {
					$userProfile = $adapter->getUserProfile();
					$adapter->disconnect();
				}
			} catch ( \Exception $e ) {
				if ( $adapter->isConnected() ) {
					$adapter->disconnect();
				}
				echo $e->getMessage();
			}
		} catch ( \Exception $e ) {
			echo 'Oops, we ran into an issue! ' . $e->getMessage();
		}

		if ( ! empty( $userProfile ) ) {
			if ( ! empty( $userProfile->email ) ) {
				$result->email      = $userProfile->email;
				$result->status     = 'SUCCESS';
				$result->name       = $userProfile->displayName;
				$result->first_name = $userProfile->firstName;
				$result->last_name  = $userProfile->lastName;
			} else {
				$result->status = 'FAIL';
			}
		} else {
			$result->status = 'FAIL';
		}
		return $result;

	}



	/**
	 * wteLinkedinUserData
	 *
	 * @return object
	 */
	function wteLinkedinUserData() {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		$clientID                  = $wp_travel_engine_settings['linkedin_client_id'];
		$clientSecret              = $wp_travel_engine_settings['linkedin_client_secret'];
		$redirectUri               = wp_login_url() . '?wte_login=linkedin';
		$result                    = new \stdClass();

		$config = array(
			'callback'                 => $redirectUri,
			'keys'                     => array(
				'id'     => $clientID,
				'secret' => $clientSecret,
			),
			'authorize_url_parameters' => array(
				'approval_prompt' => 'force',
				'access_type'     => 'offline',

			),
		);
		try {
			$adapter = new \Hybridauth\Provider\LinkedIn( $config );
			try {
				$adapter->authenticate( 'LinkedIn' );
				if ( $adapter->isConnected() ) {
					$userProfile = $adapter->getUserProfile();
					$adapter->disconnect();
				}
			} catch ( \Exception $e ) {
				if ( $adapter->isConnected() ) {
					$adapter->disconnect();
				}
				echo $e->getMessage();
			}
		} catch ( \Exception $e ) {
			echo 'Oops, we ran into an issue! ' . $e->getMessage();
		}

		if ( ! empty( $userProfile ) ) {
			if ( ! empty( $userProfile->email ) ) {
				$result->email      = $userProfile->email;
				$result->status     = 'SUCCESS';
				$result->name       = $userProfile->displayName;
				$result->first_name = $userProfile->firstName;
				$result->last_name  = $userProfile->lastName;
			} else {
				$result->status = 'FAIL';
			}
		} else {
			$result->status = 'FAIL';
		}
		return $result;
	}

	/**
	 * get_user_id
	 *
	 * @param  mixed $result
	 * @return void
	 */
	function get_user_id( $result ) {
		$user_id = email_exists( $result->email );
		if ( ! $user_id ) {
			$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
			$get_username    = explode( '@', $result->email );
			$wte_username    = $get_username[0];
			$userdata        = array(
				'first_name' => $result->first_name,
				'user_email' => $result->email,
				'user_login' => $wte_username,
				'user_pass'  => $random_password,
				'last_name'  => $result->last_name,
			);
			$user_id         = wp_insert_user( $userdata );
			do_action( 'wp_travel_engine_created_customer', $user_id, $userdata, $random_password, 'emails/customer-new-account.php' );
		}

		return $user_id;
	}

	/**
	 * getUserIDByMail
	 *
	 * @param  mixed $email
	 * @return void
	 */
	function user_id_by_email( $email ) {
		if ( $email ) {
			$user = get_user_by( 'email', $email );
			if ( $user ) {
				return $user->ID;
			}
		}
		return 0;
	}

	/**
	 * redirect_after_login
	 *
	 * @param  mixed $users_id
	 * @return void
	 */
	function redirect_after_login( $users_id ) {
		$options = get_option( 'wp_travel_engine_settings', array() );
		if ( isset( $options['pages']['wp_travel_engine_dashboard_page'] ) ) {
			$page_id = $options['pages']['wp_travel_engine_dashboard_page'];
		}
		if ( isset( $page_id ) ) {
			$redirect_to = get_permalink( $page_id );
		} else {
			$redirect_to = site_url();
		}
		/* Set login User by Id*/
		if ( ! $this->set_login_user( $users_id ) ) {
			return false;
		}
		wp_safe_redirect( $redirect_to );
		exit();
	}



	/**
	 * set_login_user
	 *
	 * @param  mixed $users_id
	 * @return void
	 */
	function set_login_user( $users_id ) {
		$user = get_user_by( 'id', $users_id );
		if ( $user ) {
			wp_set_current_user( $users_id, $user->user_login );
			wp_set_auth_cookie( $users_id );
			do_action( 'wp_login', $user->user_login, $user );
		}
		return true;
	}

	/**
	 * redirecturl
	 *
	 * @param  mixed $redirect_url
	 * @return void
	 */
	function redirecturl( $redirect_url ) {
		if ( headers_sent() ) { // Javascript redirect
			$redirecturl  = '<script type="text/javascript">';
			$redirecturl .= 'window.location = "' . $redirect_url . '"';
			$redirecturl .= '</script>';
			echo $redirecturl;
		} else { // Default Header Redirect
			header( 'Location: ' . $redirect_url );
		}
		exit;
	}

	/**
	 * include_social_login_file
	 *
	 * @return void
	 */
	function include_social_login_file() {
		include plugin_dir_path( __FILE__ ) . 'social-login.php';
	}
}
$wte_login = new Login();
