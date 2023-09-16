<?php
/**
 * Traveller's Information Template.
 *
 * @package WP_Travel_Engine.
 */
wp_enqueue_script( "parsley" );

if ( wte_array_get( $_REQUEST, '_action', '' ) === 'thankyou' ) {
	$data = WTE_Booking::get_callback_token_payload( 'thankyou' );

	if ( ! $data ) {
		return __( 'Thank you for booking the trip. Please check your email for confirmation.', 'wp-travel-engine' );
	}

	if ( is_array( $data ) && isset( $data['bid'] ) ) {
		$booking_id = $data['bid'];
		$payment_id = $data['pid'];
		$gateway    = $data['_gateway'];
	}
	WTE()->session->set( 'temp_tf_direction', "{$booking_id}|{$payment_id}|{$gateway}" );
} else {
	$temp_tf_redirection = WTE()->session->get( 'temp_tf_direction' );
	if ( ! empty( $temp_tf_redirection ) ) {
		list($booking_id, $payment_id) = explode( '|', $temp_tf_redirection );
	}
}

if ( isset( $booking_id ) ) {
	$booking     = get_post( $booking_id );
	$order_trips = $booking->{'order_trips'};
	$order_trip  = is_array( $order_trips ) ? array_shift( $order_trips ) : array();
	if ( empty( $order_trip ) ) {
		return;
	}
	$total_pax = ( isset( $order_trip['pax'] ) ) ? array_sum( $order_trip['pax'] ) : 0;

	global $wte_cart;

	/**
	 * Input data sanitized.
	 */
	$posted_data = wte_clean( wp_unslash( $_POST  )  ); // phpcs:ignore

	if ( isset( $posted_data['wp_travel_engine_booking_setting']['place_order']['booking']['subscribe'] ) && $posted_data['wp_travel_engine_booking_setting']['place_order']['booking']['subscribe'] == '1' ) {
		$myvar = $posted_data;
		$obj   = new Wte_Mailchimp_Main();
		$new   = $obj->wte_mailchimp_action( $myvar );
	}
	if ( isset( $posted_data['wp_travel_engine_booking_setting']['place_order']['booking']['mailerlite'] ) && $posted_data['wp_travel_engine_booking_setting']['place_order']['booking']['mailerlite'] == '1' ) {
		$myvar = $posted_data;
		$obj   = new Wte_Mailerlite_Main();
		$new   = $obj->wte_mailerlite_action( $myvar );
	}
	if ( isset( $posted_data['wp_travel_engine_booking_setting']['place_order']['booking']['convertkit'] ) && $posted_data['wp_travel_engine_booking_setting']['place_order']['booking']['convertkit'] == '1' ) {
		$myvar = $posted_data;
		$obj   = new Wte_Convertkit_Main();
		$new   = $obj->wte_convertkit_action( $myvar );
	}

	$options                   = get_option( 'wp_travel_engine_settings', true );
	$wp_travel_engine_thankyou = isset( $options['pages']['wp_travel_engine_thank_you'] ) ? esc_attr( $options['pages']['wp_travel_engine_thank_you'] ) : '';

	$wp_travel_engine_thankyou = ! empty( $wp_travel_engine_thankyou ) ? get_permalink( $wp_travel_engine_thankyou ) : home_url( '/' );

	$_booking_id = ! empty( $_GET['booking_id'] ) ? wte_clean( wp_unslash( $_GET['booking_id'] ) ) : 0;

	if ( isset( $_booking_id ) && ! empty( $_booking_id ) ) :
		$wp_travel_engine_thankyou = add_query_arg( 'booking_id', $_booking_id, $wp_travel_engine_thankyou );
	endif;

	$_redirect_type = ! empty( $_GET['redirect_type'] ) ? wte_clean( wp_unslash( $_GET['redirect_type'] ) ) : 0;
	if ( isset( $_redirect_type ) && ! empty( $_redirect_type ) ) :
		$wp_travel_engine_thankyou = add_query_arg( 'redirect_type', $_redirect_type, $wp_travel_engine_thankyou );
	endif;

	$_wte_gateway = ! empty( $_GET['wte_gateway'] ) ? wte_clean( wp_unslash( $_GET['wte_gateway'] ) ) : 0;

	if ( isset( $_wte_gateway ) && ! empty( $_wte_gateway ) ) :
		$wp_travel_engine_thankyou = add_query_arg( 'wte_gateway', $_wte_gateway, $wp_travel_engine_thankyou );
	endif;
	$_status = ! empty( $_GET['status'] ) ? wte_clean( wp_unslash( $_GET['status'] ) ) : 0;

	if ( isset( $_status ) && ! empty( $_status ) ) :
		$wp_travel_engine_thankyou = add_query_arg( 'status', $_status, $wp_travel_engine_thankyou );
	endif;
	?>
	<form method="post" id="wp-travel-engine-order-form" action="">
		<?php
		if ( isset( $_wte_gateway ) && 'paypal' === $_wte_gateway ) {
			do_action( 'wp_travel_engine_verify_paypal_ipn' );
		}

		$hide_traveller_info = isset( $options['travelers_information'] ) ? $options['travelers_information'] : 'yes';

		if ( 'yes' === $hide_traveller_info || '1' === $hide_traveller_info ) {
			if ( isset( $posted_data ) ) {
				$error_found = false;

				// Some input field checking
				if ( $error_found == false ) {
					// Use the wp redirect function
					// self::send_emails( $payment_id );
					wp_redirect( $wp_travel_engine_thankyou );
				} else {
					// Some errors were found, so let's output the header since we are staying on this page
					if ( isset( $_GET['noheader'] ) ) {
						require_once ABSPATH . 'wp-admin/admin-header.php';
					}
				}
			}
		}

			require_once WP_TRAVEL_ENGINE_ABSPATH . '/includes/lib/wte-form-framework/class-wte-form.php';

			$form_fields = new WP_Travel_Engine_Form_Field();

			$traveller_fields = WTE_Default_Form_Fields::traveller_information();
			$traveller_fields = apply_filters( 'wp_travel_engine_traveller_info_fields_display', $traveller_fields );

			$emergency_contact_fields = WTE_Default_Form_Fields::emergency_contact();
			$emergency_contact_fields = apply_filters( 'wp_travel_engine_emergency_contact_fields_display', $emergency_contact_fields );

			$wp_travel_engine_settings_options = get_option( 'wp_travel_engine_settings', true );

		for ( $i = 1; $i <= $total_pax; $i++ ) {
			echo '<div class="relation-options-title">' . sprintf( __( 'Personal details for Traveller: #%1$s', 'wp-travel-engine' ), (int) $i ) . '</div>';

			$modified_traveller_fields = array_map(
				function( $field ) use ( $i ) {
					if ( strpos( $field['name'], 'wp_travel_engine_placeorder_setting[place_order][travelers]' ) !== false ) {
							$field['name'] = sprintf( '%s[%d]', $field['name'], $i );
					} else {
						$field['name'] = sprintf( 'wp_travel_engine_placeorder_setting[place_order][travelers][%s][%d]', $field['name'], $i );
					}
					$field['id']            = sprintf( '%s-%d', $field['id'], $i );
					$field['wrapper_class'] = 'wp-travel-engine-personal-details';
					return $field;
				},
				$traveller_fields
			);

			$form_fields->init( $modified_traveller_fields )->render();

			if ( ! isset( $wp_travel_engine_settings_options['emergency'] ) ) {
				echo '<div class="relation-options-title">' . sprintf( esc_html__( 'Emergency contact details for Traveller: #%1$s', 'wp-travel-engine' ), $i ) . '</div>';

				$modified_emergency_contact_fields = array_map(
					function( $field ) use ( $i ) {
						if ( strpos( $field['name'], 'wp_travel_engine_placeorder_setting[place_order][relation]' ) !== false ) {
								$field['name'] = sprintf( '%s[%d]', $field['name'], $i );
						} else {
							$field['name'] = sprintf( 'wp_travel_engine_placeorder_setting[place_order][relation][%s][%d]', $field['name'], $i );
						}
						$field['id']            = sprintf( '%s-%d', $field['id'], $i );
						$field['wrapper_class'] = 'wp-travel-engine-personal-details';
						return $field;
					},
					$emergency_contact_fields
				);

				$form_fields->init( $modified_emergency_contact_fields )->render();
			}
		}
			$nonce = wp_create_nonce( 'wp_travel_engine_final_confirmation_nonce' );
		?>
		<input type="hidden" name="nonce" value="<?php echo esc_attr( $nonce ); ?>">
		<input type="submit" name="wp-travel-engine-confirmation-submit" value="<?php esc_html_e( 'Confirm Booking', 'wp-travel-engine' ); ?>">
	</form>

	<script>
		;jQuery(function($) {
			var orderForm = document.getElementById('wp-travel-engine-order-form')
			// initialize parsley
			orderForm && jQuery( orderForm ).parsley()
		});
	</script>
	<?php
} else {
	printf( esc_html__( 'No Traveller Information!!', 'wp-travel-engine' ) );
}
