<?php
global $wte_cart;

$booking_metas           = get_post_meta( $booking, 'wp_travel_engine_booking_setting', true );
$booking_meta            = booking_meta_details( $booking );
$global_settings         = wp_travel_engine_get_settings();
$default_payment_gateway = isset( $global_settings['default_gateway'] ) && ! empty( $global_settings['default_gateway'] ) ? $global_settings['default_gateway'] : 'booking_only';
$user_account_page_id    = wp_travel_engine_get_dashboard_page_id();

$booking_object = get_post( $booking );
if ( is_null( $booking_object ) || 'booking' !== $booking_object->post_type ) {
	wp_safe_redirect( get_permalink( $user_account_page_id ) );
}
// Legacy Support.
if ( empty( $booking_object->payments ) ) {
	if ( wte_array_get( $booking_metas, 'place_order.due', 0 ) <= 0 ) {
		wp_safe_redirect( get_permalink( $user_account_page_id ) );
	}
} elseif ( $booking_object->due_amount <= 0 ) {
	wp_safe_redirect( get_permalink( $user_account_page_id ) );
}

$ordered_trips = $booking_object->order_trips;
$order_trip    = (object) array_shift( $ordered_trips );

// Legacy Booking Support.
if ( empty( $booking_object->payments ) ) {
	// @TODO: Remove Later.
	$total_paid = wte_array_get( $booking_metas, 'place_order.cost', 0 );
	$due        = wte_array_get( $booking_metas, 'place_order.due', 0 );
} else {
	$total_paid = $booking_object->paid_amount;
	$due        = $booking_object->due_amount;
}
?>
<div class = "wpte-bf-checkout">
	<table class="wpte-lrf-tables">
		<tr>
			<th><span class="wpte-bf-trip-name"><?php echo esc_html( $order_trip->title ); ?></span></th>
			<td>
				<span class="lrf-td-title"><?php esc_html_e( 'Departure', 'wp-travel-engine' ); ?></span>
				<span class="lrf-td-desc"><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $order_trip->datetime ) ) ); ?></span>
			</td>
			<td>
				<span class="lrf-td-title"><?php esc_html_e( 'Total', 'wp-travel-engine' ); ?></span>
				<span class="lrf-td-desc"><?php echo wte_esc_price( wte_get_formated_price_html( $booking_object->cart_info['total'] ) ); ?></span>
			</td>
			<td>
				<span class="lrf-td-title"><?php esc_html_e( 'Paid', 'wp-travel-engine' ); ?></span>
				<span class="lrf-td-desc"><?php echo wte_esc_price( wte_get_formated_price_html( $total_paid ) ); ?></span>
			</td>
			<td>
				<span class="lrf-td-title"><?php esc_html_e( 'Due', 'wp-travel-engine' ); ?></span>
				<span class="lrf-td-desc"><?php echo wte_esc_price( wte_get_formated_price_html( $due ) ); ?></span>
			</td>
		</tr>
	</table>

	<?php
		$active_payment_methods = wp_travel_engine_get_active_payment_gateways();
	if ( ! empty( $active_payment_methods ) ) :
		?>
		<form id="wp-travel-engine-new-checkout-form" method="POST" name="wp_travel_engine_new_checkout_form" action="" enctype="multipart/form-data" novalidate=""
		class="">
			<div class="wpte-bf-field wpte-bf-radio">
				<label for="" class="wpte-bf-label">
				<?php esc_html_e( 'Payment Method', 'wp-travel-engine' ); ?>
				</label>
			<?php
			foreach ( $active_payment_methods as $key => $payment_method ) :
				if ( $key == 'booking_only' ) :
					continue;
					endif;
				?>
						<div class="wpte-bf-radio-wrap">
							<input <?php checked( $default_payment_gateway, $key ); ?> type="radio" name="wpte_checkout_paymnet_method" value="<?php echo esc_attr( $key ); ?>" id="wpte-checkout-paymnet-method-<?php echo esc_attr( $key ); ?>">
							<label for="wpte-checkout-paymnet-method-<?php echo esc_attr( $key ); ?>">
							<?php
							if ( isset( $payment_method['icon_url'] ) && ! empty( $payment_method['icon_url'] ) ) :
								?>
									<img src="<?php echo esc_url( $payment_method['icon_url'] ); ?>" alt="<?php echo esc_attr( $payment_method['label'] ); ?>">
								<?php
								else :
									echo esc_html( $payment_method['label'] );
								endif;
								?>
							</label>
						</div>
				<?php endforeach; ?>
			</div>
		<div class="wpte-bf-field wpte-bf-submit">
			<input type="submit" name="wp_travel_engine_nw_bkg_submit" value="<?php esc_attr_e( 'Pay Now', 'wp-travel-engine' ); ?>">
		</div>
		<?php wp_nonce_field( 'nonce_checkout_partial_payment_remaining_action', 'nonce_checkout_partial_payment_remaining_field' ); ?>
		<input type="hidden" name="currency" value="<?php echo wp_travel_engine_get_currency_code(); ?> ">
		<input type="hidden" name="booking_id" value="<?php echo esc_attr( $booking ); ?>">
		<input type="hidden" name="action" value="partial-payment">
		<?php do_action( 'wte_before_remaining_payment_form_close' ); ?>
	</form>
		<?php do_action( 'wte_booking_after_checkout_form_close' ); ?>
	<?php else : ?>
		<span class="wte-none-available-message wte-error-message">
			<?php echo esc_html__( 'No payment method has been added. Please contact the site owner for assistance.', 'wp-travel-engine' ); ?>
		</span>
	<?php endif; ?>
</div>
<div class="wpte-lrf-btn-wrap">
	<a target="_blank" class="wpte-lrf-btn"
		href="<?php echo esc_url( get_post_type_archive_link( 'trip' ) ); ?>"><?php esc_html_e( 'Book More Trips', 'wp-travel-engine' ); ?></a>
	<?php
	$user_account_page_id = wp_travel_engine_get_dashboard_page_id();
	if ( ! empty( $booking_metas ) ) : // phpcs:ignore
		if ( ! empty( $user_account_page_id ) ) :
			?>
	<a class="wpte-lrf-btn cancel-btn"
		href="<?php echo esc_url( get_permalink( $user_account_page_id ) ); ?>"><?php esc_html_e( 'Cancel', 'wp-travel-engine' ); ?></a>
			<?php
		endif;
	endif;
	?>
</div>
