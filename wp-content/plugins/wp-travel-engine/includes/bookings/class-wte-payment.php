<?php
/**
 * Payment Object.
 */


class WTE_Payment {

	public static function get_instance( $payment_id ) {
		$_payment = WP_Post::get_instance( $payment_id );

		if ( ! $_payment ) {
			return $_payment;
		}

		if ( $_payment && 'wte-payments' === $_payment->post_type ) {
			return new WTE_Payment( $_payment );
		}
	}

	public function __construct( $payment_obj ) {
		foreach ( get_object_vars( $payment_obj ) as $key => $value ) {
			$this->$key = $value;
		}
	}
}

function wte_get_payment( $payment_id ) {
	return WTE_Payment::get_instance( $payment_id );
};
