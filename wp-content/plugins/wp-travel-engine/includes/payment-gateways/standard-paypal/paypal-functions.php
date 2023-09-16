<?php
/**
 * Retrieve the correct PayPal Redirect based on http/s
 * and "live" or "test" mode, i.e., sandbox.
 *
 * @return PayPal URI
 */
function wte_get_paypal_redirect_url( $ssl_check = false ) {
	$protocol = is_ssl() || ! $ssl_check ? 'https://' : 'http://';
	if ( defined( 'WP_TRAVEL_ENGINE_PAYMENT_DEBUG' ) && WP_TRAVEL_ENGINE_PAYMENT_DEBUG ) {
		$paypal_uri = $protocol . 'www.sandbox.paypal.com/cgi-bin/webscr';
	} else {
		$paypal_uri = $protocol . 'www.paypal.com/cgi-bin/webscr';
	}
	return $paypal_uri;
}


/**
 * When a payment is made PayPal will send us a response and this function is
 * called. From here we will confirm arguments that we sent to PayPal which
 * the ones PayPal is sending back to us.
 * This is the Pink Lilly of the whole operation.
 */
function wp_travel_engine_process_paypal_ipn( $tokenized_data ) {

	/**
	 * Instantiate the IPNListener class
	 */
	include dirname( __FILE__ ) . '/php-paypal-ipn/IPNListener.php';
	$listener = new IPNListener();

	/**
	 * Set to PayPal sandbox or live mode
	 */
	$settings              = get_option( 'wp_travel_engine_settings', true );
	$listener->use_sandbox = ( defined( 'WP_TRAVEL_ENGINE_PAYMENT_DEBUG' ) ) ? WP_TRAVEL_ENGINE_PAYMENT_DEBUG : false;

	/**
	 * Check if IPN was successfully processed
	 */
	if ( $verified = $listener->processIpn() ) {
		if ( ! isset( $tokenized_data['pid'] ) ) {
			return;
		}
		$payment_id = $tokenized_data['pid'];
		$booking_id = $tokenized_data['bid'];
		$payment    = get_post( $payment_id );

		/**
		 * Log successful purchases
		 */
		$transactionData = $listener->getPostData(); // POST data array
		file_put_contents( 'ipn_success.log', wp_json_encode( array( 'data' => $transactionData ), JSON_PRETTY_PRINT ) . PHP_EOL, LOCK_EX | FILE_APPEND );

		$message = null;

		/**
		 * Verify currency
		 *
		 * Check if the currency that was processed by the IPN matches what is saved as
		 * the currency setting
		 */
		if ( sanitize_text_field( wp_unslash( $_POST['mc_currency'] ) ) != wp_travel_engine_get_currency_code() ) {
			$message .= "\nCurrency does not match those assigned in settings\n";
		}

		$payable = $payment->payable;

		$booking = get_post( $booking_id );

		// @TODO: Check here if transaction already took place.

		$payment_meta_input = array();
		if ( isset( $_REQUEST['payment_status'] ) ) {
			$payment_meta_input['payment_status'] = strtolower( sanitize_text_field( wp_unslash( $_REQUEST['payment_status'] ) ) );
			$payment_meta_input['_txn_id']        = sanitize_text_field( wp_unslash( $_REQUEST['txn_id'] ) );
			if ( 'Completed' == $_REQUEST['payment_status'] ) {
				$amount = (float) ( wp_unslash( $_REQUEST['mc_gross'] ) );
				WTE_Booking::update_booking(
					$booking_id,
					array(
						'meta_input' => array(
							'paid_amount' => +$booking->paid_amount + +$amount,
							'due_amount'  => +$booking->due_amount - +$amount,
							'wp_travel_engine_booking_status' => 'booked',
						),
					)
				);
				$payment_meta_input['payment_amount'] = array(
					'value'    => $amount,
					'currency' => sanitize_text_field( wp_unslash( $_REQUEST['mc_currency'] ) ),
				);
			}
		}

		$payment_meta_input['gateway_response'] = wte_clean( wp_unslash( $_REQUEST ) );

		WTE_Booking::update_booking(
			$payment_id,
			array(
				'meta_input' => $payment_meta_input,
			)
		);

		do_action( 'wte_booking_cleanup', $payment_id, 'notification' ); // Delete the saved key for generated JWT, sent in notification url.

		// @TODO: Send Emails from here of payment confirmation.

		if ( isset( $_SERVER['SERVER_PROTOCOL'] ) ) {
			header( wp_unslash( $_SERVER['SERVER_PROTOCOL'] ) . ' ' . 200 . ' OK' );
		}
		exit;

	} else {

		/**
		 * Log errors
		 */
		$errors = $listener->getErrors();
		file_put_contents( 'ipn_errors.log', print_r( $errors, true ) . PHP_EOL, LOCK_EX | FILE_APPEND );

		/**
		 * An Invalid IPN *may* be caused by a fraudulent transaction attempt. It's
		 * a good idea to have a developer or sys admin manually investigate any
		 * invalid IPN.
		 */
		$from_email = isset( $settings['email']['from'] ) ? $settings['email']['from'] : '';
		if ( ! empty( $from_email ) ) {
			wp_mail( $settings['email']['from'], __( 'Invalid IPN', 'wp-travel-engine' ), $listener->getTextReport() );
		}
	}
}

add_action( 'wte_callback_for_paypal_payment_notification', 'wp_travel_engine_process_paypal_ipn' );
