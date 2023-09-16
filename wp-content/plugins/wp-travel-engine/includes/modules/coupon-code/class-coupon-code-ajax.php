<?php
/**
 *
 */
namespace WPTravelEngine\Modules\CouponCode;

use WPTravelEngine\Modules\CouponCode;

class Ajax {
	public function __construct() {}

	public static function check_coupon_code() {
		// phpcs:disable
		if ( empty( $_POST['coupon_code'] ) ) {
			\wp_send_json_error(
				new \WP_Error( 'WTE_INVALID_REQUEST', __( 'Coupon Code is required.', 'wp-travel-engine' ) )
			);
			die;
		}

		$post_id   = intval( sanitize_text_field( wte_clean( wp_unslash( $_POST['coupon_id'] ) ) ) );
		$coupon_id = CouponCode::coupon_id_by_code( wte_clean( wp_unslash( $_POST['coupon_code'] ) ) );

		if ( ! $coupon_id || $post_id === $coupon_id ) {
			wp_send_json_success(
				array(
					'status'      => 'valid',
					'coupon_code' => wte_clean( wp_unslash( $_POST['coupon_code'] ) ),
				)
			);
			 die;
		}
		// phpcs:enable

		\wp_send_json_error(
			new \WP_Error( 'INVALID_COUPON_CODE', __( 'Invalid Coupon code.', 'wp-travel-engine' ) )
		);
		die;
	}
	public static function apply_coupon() {
		global $wte_cart;
		$cart_totals = $wte_cart->get_total( false );
		if ( empty( $_POST['CouponCode'] ) ) { // phpcs:ignore
			\wp_send_json_error(
				new \WP_Error( 'WTE_INVALID_REQUEST', __( 'Coupon Code is required.', 'wp-travel-engine' ) )
			);
			die;
		}

		if ( empty( $_POST['trip_ids'] ) ) { // phpcs:ignore
			\wp_send_json_error(
				new \WP_Error( 'WTE_INVALID_REQUEST', __( 'Coupon cannot be applied. No Trips to apply Coupon', 'wp-travel-engine' ) )
			);
			die;
		}

		$coupon_id = CouponCode::coupon_id_by_code( wte_clean( wp_unslash( $_POST['CouponCode'] ) ) ); // phpcs:ignore

		if ( ! $coupon_id ) {
			\wp_send_json_error(
				new \WP_Error( 'WTE_COUPON_NOT_EXIST', __( 'Coupon does not exist or has been removed.', 'wp-travel-engine' ) )
			);
			die;
		}

		if ( ! CouponCode::is_coupon_date_valid( $coupon_id ) ) {
			\wp_send_json_error(
				new \WP_Error( 'WTE_COUPON_INVALID', __( 'Coupon is either inactive or has expired. Coupon Code could not be applied.', 'wp-travel-engine' ) )
			);
			die;
		}

		$trip_ids = wte_clean( wp_unslash( json_decode( wte_clean( wp_unslash( $_POST['trip_ids'] ) ) ) ) ); // phpcs:ignore

		$trip_id = is_array( $trip_ids ) ? array_shift( $trip_ids ) : 0;

		if ( ! $trip_id || ! CouponCode::coupon_can_be_applied( $coupon_id, $trip_id ) ) {
			\wp_send_json_error(
				new \WP_Error( 'WTE_COUPON_INVALID', __( 'Coupon Code could not be applied to the selected Trip.', 'wp-travel-engine' ) )
			);
			die;
		}

		$coupon_limit_number = CouponCode::get_coupon_meta( $coupon_id, 'restriction', 'coupon_limit_number' );

		if ( ! ! $coupon_limit_number && ( +$coupon_limit_number <= CouponCode::get_usage_count( $coupon_id ) ) ) {
			\wp_send_json_error(
				new \WP_Error( 'WTE_COUPON_INVALID', sprintf( __( 'Coupon "%1$s" has expired. Maximum no. of coupon usage exceeded.', 'wp-travel-engine' ), sanitize_text_field( wp_unslash( $_POST['CouponCode'] ) ) ) ) // phpcs:ignore
			);
			die;
		}

		$discount_type  = CouponCode::get_discount_type( $coupon_id );
		$discount_value = CouponCode::get_discount_value( $coupon_id );

		$cart_calculated_totals = $wte_cart->get_total();

		$cart_total = $cart_calculated_totals['cart_total'];

		$success          = false;
		$discounted_total = 0;
		if ( 'fixed' == $discount_type ) {
			if ( $discount_value >= $cart_total ) {
				\wp_send_json_error(
					new \WP_Error( 'WTE_COUPON_AMOUNT_EXCEED', sprintf( __( 'Coupon "%1$s" cannot be applied for this trip.', 'wp-travel-engine' ), sanitize_text_field( wp_unslash( $_POST['CouponCode'] ) ) ) ) // phpcs:ignore
				);
				die;
			}
			$discounted_total = $cart_total - $discount_value;
		}

		if ( 'percentage' == $discount_type ) {
			$discounted_total = round( $cart_total * ( 100 - $discount_value ) / 100, 2 );
		}

		$wte_cart->add_discount_values( wte_clean( wp_unslash( $_POST['CouponCode'] ) ), $discount_type, $discount_value ); // phpcs:ignore

		if ( wp_travel_engine_is_trip_partially_payable( $trip_id ) ) {
			$new_dicounted_cost = $discounted_total - $cart_calculated_totals['total_partial'];
		} else {
			$new_dicounted_cost = $discounted_total;
		}

		wp_send_json_success(
			array(
				'dis_type'            => $discount_type,
				'new_discounted_cost' => round( $new_dicounted_cost, 2 ),
				'new_cost'            => $discounted_total,
				'coupon_code'         => wte_clean( wp_unslash( $_POST['CouponCode'] ) ), // phpcs:ignore
				'discount_percent'    => ( 'percentage' == $discount_type ) ? $discount_value : 0,
				'discount_amt'        => ( 'percentage' == $discount_type ) ? $cart_total * +$discount_value / 100 : $discount_value,
				'unit'                => ( 'percentage' == $discount_type ) ? '%' : \wte_currency_code(),
				'type'                => 'success',
				'message'             => sprintf( __( 'Coupon "%1$s" applied successfully.', 'wp-travel-engine' ), wte_clean( wp_unslash( $_POST['CouponCode'] ) ) ), // phpcs:ignore
			)
		);
		die;
	}

	public static function reset_coupon() {
		global $wte_cart;
		$cart_totals    = $wte_cart->get_total( false );
		$cart_discounts = $wte_cart->get_discounts();
		$wte_cart->discount_clear();
		// if ( ! empty( $cart_discounts ) ) {
		// foreach ( $cart_discounts as $discount_key => $discount_item ) {
		// $coupon_id = CouponCode::coupon_id_by_code( $discount_item['name'] );
		// CouponCode::update_usage_count( $coupon_id, -1 );
		// }
		// }

		\wp_send_json_success(
			array(
				'default_cost' => \wte_get_formated_price( $cart_totals['sub_total'], '', '', false, false, true ),
				'message'      => __(
					'Applied Coupons reset successfully.',
					'wp-travel-engine'
				),
			)
		);
		die;
	}
}

new Ajax();
