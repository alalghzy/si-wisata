<?php
/**
 * Paypal payment gateway.
 *
 * @package WP_Travel_Engine/includes/payment-gateways
 * @author WP Travel Engine
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * class paypal payment gateway
 *
 * @since 2.2.8
 */
class WTE_Payment_Gateway_Paypal {

	/**
	 * Undocumented function
	 *
	 * @since 4.3.0
	 * @param [type] $booking
	 * @return void
	 */
	public static function booking_process( $payment_id, $type, $gateway = 'paypal_payment' ) {
		if ( ! $payment_id ) {
			return;
		}

		$booking_id = get_post_meta( $payment_id, 'booking_id', ! 0 );

		$args = self::get_args( $booking_id, $payment_id );

		$redirect_uri = esc_url( home_url( '/' ) );

		if ( $args ) {
			$paypal_request_args = http_build_query( $args, '', '&' );
			$redirect_uri        = esc_url( wte_get_paypal_redirect_url() ) . '?' . $paypal_request_args;
		}

		wp_redirect( $redirect_uri );
		exit;
	}

	/**
	 * Get Paypal Arguments.
	 *
	 * @param number $booking_id Booking ID.
	 * @return Array
	 */
	private static function get_args( $booking_id, $payment_id ) {

		$paypal_id = wte_array_get( get_option( 'wp_travel_engine_settings', array() ), 'paypal_id', 0 );

		if ( ! $paypal_id ) {
			wp_die(
				new WP_Error( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'WTE_BOOKING_ERROR',
					esc_html__( 'Couldn\'t proceed - invalid paypal ID.', 'wp-travel-engine' )
				)
			);
		}
		$booking = get_post( $booking_id );

		// TODO: Do some stuff like update booking.
		if ( is_null( $booking ) || 'booking' !== $booking->post_type ) {
			wp_die(
				new WP_Error(
					'WTE_BOOKING_ERROR',
					__( 'Invalid booking id.', 'wp-travel-engine' )
				)
			);
		}

		$payment = get_post( $payment_id );

		if ( is_null( $payment ) ) {
			wp_die(
				new WP_Error(
					'WTE_BOOKING_ERROR',
					__( 'Invalid booking or payment id.', 'wp-travel-engine' )
				)
			);
		}

		$currency_code = $payment->payable['currency'];
		$amount        = $payment->payable['amount'];

		if ( count( $booking->order_trips ) > 0 ) {

			$args['amount'] = $amount;

			$args['cmd']           = '_cart';
			$args['upload']        = '1';
			$args['currency_code'] = $currency_code;
			$args['business']      = $paypal_id;
			$args['bn']            = '';
			$args['rm']            = '2';
			// $args['discount_amount_cart'] = $discount;
			$args['tax_cart']      = 0;
			$args['charset']       = get_bloginfo( 'charset' );
			$args['cbt']           = get_bloginfo( 'name' );
			$args['return']        = WTE_Booking::get_return_url( $booking_id, $payment->ID, 'paypal_payment' );
			$args['cancel']        = WTE_Booking::get_cancel_url( $booking_id, $payment->ID, 'paypal_payment' );
			$args['handling']      = 0;
			$args['handling_cart'] = 0;
			$args['no_shipping']   = 0;
			$args['notify_url']    = WTE_Booking::get_notification_url( $booking_id, $payment->ID, 'paypal_payment' );

			$agrs_index = 1;

			// Add cart items to paypal args.
			foreach ( $booking->order_trips as $cart_id => $item ) {

				$item = (object) $item;

				$args[ 'item_name_' . $agrs_index ]   = $item->title;
				$args[ 'quantity_' . $agrs_index ]    = 1;
				$args[ 'amount_' . $agrs_index ]      = $amount;
				$args[ 'item_number_' . $agrs_index ] = $item->ID;
				$args[ 'on2_' . $agrs_index ]         = __( 'Total Price', 'wp-travel-engine' );
				$args[ 'os2_' . $agrs_index ]         = $amount;

				// @TODO paypal args add for trip extras.

				$agrs_index++;
				break; // @TODO Split payments for each item.
			}
		} else {
			wp_die(
				new WP_Error(
					'WTE_BOOKING_ERROR',
					__( 'Cart is empty.', 'wp-travel-engine' )
				)
			);
		}

		$args['option_index_0'] = $agrs_index;
		$args['custom']         = $booking->ID;

		return apply_filters( 'wp_travel_engine_paypal_request_args', $args );
	}

}

add_action( 'wte_payment_gateway_paypal_payment', array( 'WTE_Payment_Gateway_Paypal', 'booking_process' ), 12, 3 );
