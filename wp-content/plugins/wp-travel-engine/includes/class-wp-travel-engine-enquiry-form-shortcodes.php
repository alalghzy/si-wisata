<?php
/**
 * Class for enquiry form shortcodes.
 */
class WP_Travel_Engine_Enquiry_Form_Shortcodes {

	function init() {
		add_action( 'wp_travel_engine_enquiry_form', array( $this, 'wpte_enquiry_form' ) );
	}

	/**
	 * Gets Package Details Fields for Enquiry Form.
	 *
	 * @since 5.3.1
	 */
	public static function get_package_detail_fields( $post_id ) {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', array() );
		$enquiry_thank_you_url     = home_url();
		if ( ! empty( $wp_travel_engine_settings['pages']['enquiry'] ) ) {
			$enquiry_thank_you_url = $wp_travel_engine_settings['pages']['enquiry'];
			$enquiry_thank_you_url = get_permalink( $enquiry_thank_you_url );
		}

		$package_detail_fields = array(
			'package_name'  => array(
				'label'    => __( 'Trip Name', 'wp-travel-engine' ),
				'type'     => 'hidden',
				'name'     => 'package_name',
				'id'       => 'package_name',
				'default'  => get_the_title( $post_id ),
				'priority' => 7,
			),
			'package_id'    => array(
				'type'          => 'hidden',
				'name'          => 'package_id',
				'wrapper_class' => 'row-repeater package-name-holder',
				'id'            => 'package_id',
				'default'       => esc_attr( $post_id ),
				'priority'      => 8,
			),
			'package_label' => array(
				'type'          => 'text_info',
				'wrapper_class' => 'row-repeater package-name-holder',
				'field_label'   => __( 'Package name:', 'wp-travel-engine' ),
				'name'          => 'package_label',
				'id'            => 'package_label',
				'validations'   => array(
					'required' => true,
				),
				'remove_wrap'   => true,
				'default'       => get_the_title( $post_id ),
				'priority'      => 9,
			),
			'redirect_url'  => array(
				'type'          => 'hidden',
				'name'          => 'redirect-url',
				'wrapper_class' => 'row-repeater package-name-holder',
				'id'            => 'redirect-url',
				'default'       => esc_url( $enquiry_thank_you_url ),
				'priority'      => 8,
			),
		);

		return apply_filters( 'wte_enquiry_package_detail_fields', $package_detail_fields, $post_id );
	}

	/**
	 * Gets Enquiry Form Fields.
	 */
	public static function get_enquiry_form_fields( $post_id ) {
		$enquiry_form_fields = WTE_Default_Form_Fields::enquiry();

		$package_fields = self::get_package_detail_fields( $post_id );

		return apply_filters( 'wp_travel_engine_enquiry_fields_display', array_merge( $package_fields, $enquiry_form_fields ), $post_id );
	}

	// Enquiry form main function
	function wpte_enquiry_form() {
		wp_enqueue_script( "parsley" );

		global $post;
		$post_id = 0;

		if ( isset( $post->ID ) ) :
			$post_id = $post->ID;
		endif;

		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );

		// Include the form class - framework.
		include_once WP_TRAVEL_ENGINE_ABSPATH . '/includes/lib/wte-form-framework/class-wte-form.php';

		// Form fields initialize.
		$form_field          = new \WP_Travel_Engine_Form_Field();
		$enquiry_form_fields = self::get_enquiry_form_fields( $post_id );

		$privacy_policy_fields = array();

		if ( function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url() ) {
			$privacy_policy_fields['enquiry_confirmation'] = array(
				'type'              => 'checkbox',
				'label'             => __( 'Privacy Policy', 'wp-travel-engine' ),
				'options'           => array( 'on' => isset( $wp_travel_engine_settings['gdpr_msg'] ) ? esc_attr( $wp_travel_engine_settings['gdpr_msg'] ) . get_the_privacy_policy_link() . '.' : sprintf( __( 'By contacting us, you agree to our <a href="%1$s">Privacy Policy</a>', 'wp-travel-engine' ), get_privacy_policy_url() ) ),
				'name'              => 'enquiry_confirmation',
				'wrapper_class'     => 'row-form confirm-holder',
				'id'                => 'enquiry_confirmation',
				'validations'       => array(
					'required' => true,
				),
				'option_attributes' => array(
					'required' => true,
				),
				'priority'          => 80,
			);
		} elseif ( current_user_can( 'edit_theme_options' ) ) {
			$privacy_policy_lbl = sprintf( __( '%1$sPrivacy Policy page not set or not published, please check Admin Dashboard > Settings > Privacy.%2$s', 'wp-travel-engine' ), '<p style="color:red;">', '</p>' );

			$privacy_policy_fields['enquiry_confirmation'] = array(
				'type'     => 'text_info',
				'label'    => __( 'Privacy Policy', 'wp-travel-engine' ),
				'id'       => 'enquiry_confirmation',
				'default'  => $privacy_policy_lbl,
				'priority' => 80,
			);
		}
		?>
			<div class="wte_enquiry_contact_form-wrap">
				<form name="wte_enquiry_contact_form" action="#" method="post" id="wte_enquiry_contact_form" class="wte_enquiry_contact_form">
					<h2 class="enquiry-form-title"><?php echo esc_html__( 'You can send your enquiry via the form below.', 'wp-travel-engine' ); ?></h2>
					<?php
					// Print display fields.
					$form_field->init( $enquiry_form_fields )->render();

					$privacy_policy_fields = apply_filters( 'wte_enquiry_privacy_fields', $privacy_policy_fields );

						// Print privacy policy fields.
					if ( ! isset( $privacy_policy_lbl ) ) {
						$form_field->init( $privacy_policy_fields )->render();
					}
					wp_nonce_field( 'wte_enquiry_send_mail', 'nonce' );
					?>
					<input type="hidden" name="action" value="wte_enquiry_send_mail">
					<?php
						do_action( 'wte_enquiry_contact_form_before_submit_button' );
					?>
					<input type="submit" class="enquiry-submit" name="enquiry_submit_button" id="enquiry_submit_button" value="<?php echo esc_attr__( 'Send Email', 'wp-travel-engine' ); ?>">
					<?php
					do_action( 'wte_enquiry_contact_form_after_submit_button' );
					?>
				</form>
			</div>
		<?php
	}


	/**
	 * Sends mail to subscriber and admin.
	 *
	 * @since 3.0.0
	 */
	public static function wte_enquiry_send_mail( $post_data ) {
		// phpcs:disable
		if ( ! isset( $post_data['action'] ) || 'wte_enquiry_send_mail' !== $post_data['action'] ) {
			wp_send_json_error( array( 'message' => __( 'Invalid Action', 'wp-travel-engine' ) ) );
			exit;
		}

		$formdata = wte_clean(wp_unslash( $post_data ));
		// phpcs:enable

		$email           = ! empty( $formdata['enquiry_email'] ) ? ( $formdata['enquiry_email'] ) : false;
		$enquiry_message = ! empty( $formdata['enquiry_message'] ) ? ( $formdata['enquiry_message'] ) : false;
		$name            = ! empty( $formdata['enquiry_name'] ) ? ( $formdata['enquiry_name'] ) : false;

		$cust_enquiry_subject = isset( $formdata['enquiry_subject'] ) && ! empty( $formdata['enquiry_subject'] ) ? $formdata['enquiry_subject'] : false;

		if ( ! $email || ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => __( 'Please provide a valid email', 'wp-travel-engine' ) ) );
		}

		$validation_check = apply_filters( 'wp_travel_engine_enquiry_validation_check', array( 'status' => true ) );

		if ( ! empty( $validation_check ) && false === $validation_check['status'] ) {
			$result['type']    = 'error';
			$result['message'] = $validation_check['message'];
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				$result = wp_json_encode( $result );
				echo $result; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				die();
			}
		}

		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		$postid                    = get_post( $formdata['package_id'] );
		$slug                      = $postid->post_title;

		$url     = '<a href=' . esc_url( get_permalink( $postid ) ) . '>' . esc_attr( $slug ) . '</a>';
		$subject = isset( $wp_travel_engine_settings['query_subject'] ) ? $wp_travel_engine_settings['query_subject'] : __( 'Enquiry received', 'wp-travel-engine' );

		if ( $cust_enquiry_subject ) {
			$subject = $cust_enquiry_subject;
		}

		$enquirer_tags         = array( '{enquirer_name}', '{enquirer_email}' );
		$enquirer_replace_tags = array( $name, $email );
		$subject               = str_replace( $enquirer_tags, $enquirer_replace_tags, $subject );
		$admin_email           = get_option( 'admin_email' );

		$to        = sanitize_email( $admin_email );
		$ipaddress = '';
		if ( getenv( 'HTTP_CLIENT_IP' ) ) {
			$ipaddress = getenv( 'HTTP_CLIENT_IP' );
		} elseif ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_X_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED' );
		} elseif ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED' );
		} elseif ( getenv( 'REMOTE_ADDR' ) ) {
			$ipaddress = getenv( 'REMOTE_ADDR' );
		} else {
			$ipaddress = 'UNKNOWN';
		}

		$from_email = wte_get_from_email();
		$blogname   = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$sender     = $blogname . ' <' . $from_email . '>';

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$headers .= 'From: ' . $sender . "\r\n";
		$headers .= 'Reply-To: ' . $email . "\r\n";
		// Create email headers.
		$headers .= 'X-Mailer: PHP/' . phpversion();

		$remove_keys = array(
			'package_id',
			'redirect-url',
			'enquiry_confirmation[]',
			'enquiry_confirmation',
			'wp_travel_engine_enquiry_submit_name',
			'_wp_http_referer',
			'action',
		);

		// Add Package Name.
		$formdata['package_name'] = $url;

		$valid_form_fields = array_keys( self::get_enquiry_form_fields( $formdata['package_id'] ) );

		// Message.
		foreach ( $formdata as $key => $data ) {
			if ( in_array( $key, $remove_keys ) || ! in_array( $key, $valid_form_fields ) ) {
				unset( $formdata[ $key ] );
				continue;
			}
		}
		// Add customer IP Address.
		$formdata['IP Address:'] = $ipaddress;

		// Mail class.
		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-emails.php';

		// Prepare enquiry emails.
		$mail_obj                     = new WP_Travel_Engine_Emails();
		$admin_email_template_content = $mail_obj->get_email_template( 'enquiry', 'admin', true, $formdata );

		// Prepare admin email template.
		$admin_email_template  = $mail_obj->get_email_header();
		$admin_email_template .= $admin_email_template_content;
		$admin_email_template .= $mail_obj->get_email_footer();

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		$uploadedfile = $_FILES;
		$attachments  = array();
		foreach ( $uploadedfile as $key => $file ) {
			$upload_file = wp_handle_upload( $file, array( 'test_form' => false ) );
			if ( $upload_file && ! isset( $upload_file['error'] ) ) {
				$attachments[ $key ] = $upload_file['file'];
			}
		}

		if ( strpos( $wp_travel_engine_settings['email']['emails'], ',' ) !== false ) {
			$wp_travel_engine_settings['email']['emails'] = str_replace( ' ', '', $wp_travel_engine_settings['email']['emails'] );
			$admin_emails                                 = explode( ',', $wp_travel_engine_settings['email']['emails'] );
			foreach ( $admin_emails as $key => $value ) {
				$to         = sanitize_email( $value );
				$admin_sent = wp_mail( $to, esc_html( $subject ), $admin_email_template, $headers, $attachments );
			}
		} else {
			$wp_travel_engine_settings['email']['emails'] = str_replace( ' ', '', $wp_travel_engine_settings['email']['emails'] );
			$admin_sent                                   = wp_mail( $to, esc_html( $subject ), $admin_email_template, $headers, $attachments );
		}
		if ( isset( $wp_travel_engine_settings['email']['cust_notif'] ) && $wp_travel_engine_settings['email']['cust_notif'] == '1' ) {

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
			$headers .= 'Reply-To: ' . $to . "\r\n";

			// Create email headers.
			$headers .= 'X-Mailer: PHP/' . phpversion();
			$subject  = apply_filters( 'customer_enquiry_subject', 'Enquiry Sent.' );

			wp_mail( $email, $subject, $admin_email_template, $headers );
		}

		if ( $admin_sent == 1 ) {
			$new_post = array(
				'post_title'  => 'enquiry ',
				'post_status' => 'publish',
				'post_type'   => 'enquiry',
			);

			// Insert the post into the database.
			$post_id = wp_insert_post( $new_post );

			if ( ! $post_id ) {
				return false;
			}

			if ( ! is_wp_error( $post_id ) ) :

				/**
				 * @action_hook wte_after_enquiry_created
				 *
				 * @since 2.2.0
				 */
				do_action( 'wte_after_enquiry_created', $post_id );

			endif;

			$arr['enquiry'] = array(
				'name'    => $name,
				'email'   => $email,
				'message' => $enquiry_message,
				'pname'   => $formdata['package_id'],
			);

			add_post_meta( $post_id, 'wp_travel_engine_setting', $arr );

			// Added new meta key for form editor addon support.
			$_formdata = array();
			foreach ( $formdata as $field => $value ) {
				$_formdata[ $field ] = wp_kses( $value, array() );
			}
			add_post_meta( $post_id, 'wp_travel_engine_enquiry_formdata', $_formdata );

			$title = $name;

			$post_data = array(
				'ID'         => $post_id,
				'post_title' => $title,
			);

			// Update the post into the database.
			wp_update_post( $post_data );

			$result['type']    = 'success';
			$result['message'] = __( 'Your query has been successfully sent. Thank You.', 'wp-travel-engine' );

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				$result = wp_json_encode( $result );
				echo $result; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Hook - after_enquiry_sent
		 */
		do_action( 'wp_travel_engine_after_enquiry_sent', $post_id );

		if ( $admin_sent == 0 ) {
			$result['type']    = 'failed';
			$result['message'] = __( 'Sorry, your query could not be sent at the moment. May be try again later. Thank You.', 'wp-travel-engine' );

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				$result = wp_json_encode( $result );
				echo $result; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
		if ( isset( $formdata['query_confirmation'] ) && $formdata['query_confirmation'] != 'on' ) {
			$result['type']    = 'failed';
			$result['message'] = __( 'Confirmation failed, please try again. Thank You.', 'wp-travel-engine' );
		}
		exit;
	}
}
$obj = new WP_Travel_Engine_Enquiry_Form_Shortcodes();
$obj->init();
