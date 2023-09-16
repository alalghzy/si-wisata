<?php
$options      = get_option( 'wp_travel_engine_settings', true );
$booking_meta = booking_meta_details( $booking );
// phpcs:disable
if ( ! empty( $_GET['booking_id'] ) && ! empty( $_GET['redirect_type'] && $_GET['redirect_type'] == 'partial-payment' ) ) :
	if ( isset( $_GET['wte_gateway'] ) && ! empty( $_GET['wte_gateway'] ) && ( isset( $_GET['status'] ) && $_GET['status'] == 'success' ) ) :
		do_action( 'wp_travel_engine_after_partial_payment_gateway_redirect', $booking, wte_clean( wp_unslash( $_GET['wte_gateway'] ) ) );
endif;
// phpcs:enable
	?>
	<div class="thank-you-container">
		<h3 class="trip-details"><?php echo esc_html__( 'Trip Details:', 'wp-travel-engine' ); ?></h3>

		<div class="detail-container">
			<?php if ( isset( $booking ) && ! empty( $booking ) ) : ?>

				<div class="detail-item">
					<strong class="item-label"><?php esc_html_e( 'Booking ID :', 'wp-travel-engine' ); ?></strong>
					<span class="value"><?php echo esc_html( $booking ); ?></span>
				</div>

			<?php endif; ?>
			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Trip start date :', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( date_i18n( $booking_meta['date_format'], strtotime( $booking_meta['trip_start_date'] ) ) ); ?></span>
			</div>

			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Trip Name :', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( $booking_meta['trip_name'] ); ?></span>
			</div>

			<?php
			/**
			 * wte_thankyou_after_trip_name hook
			 *
			 * @hooked wte_display_trip_code_thankyou - Trip Code Addon
			 */
			do_action( 'wte_thankyou_after_trip_name', $booking );
			?>
			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Total Trip Cost :', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( wte_get_formated_price( $booking_meta['total_cost'], '', '', false, false, true ) ); ?></span>
			</div>

			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Partial Payment #1 :', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( wte_get_formated_price( $booking_meta['partial_cost'], '', '', false, false, true ) ); ?></span>
			</div>

			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Partial Payment #2 :', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( wte_get_formated_price( $booking_meta['partial_due'], '', '', false, false, true ) ); ?></span>
			</div>
			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Payed Amount Subtotal :', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( wte_get_formated_price( $booking_meta['total_cost'], '', '', false, false, true ) ); ?></span>
			</div>
		</div>
		<div class="thank-you-container-2">
			<div class="wpte-lrf-btn-wrap">
					<a target="_blank" class="wpte-lrf-btn" href="<?php echo esc_url( get_post_type_archive_link( 'trip' ) ); ?>"><?php esc_html_e( 'Book More Trips', 'wp-travel-engine' ); ?></a>
			</div>
			<?php
				$user_account_page_id = wp_travel_engine_get_dashboard_page_id();
			if ( ! empty( $user_account_page_id ) ) {
				?>
					<div class="wpte-lrf-btn-wrap">
						<a class="wpte-lrf-btn" href="<?php echo esc_url( get_permalink( $user_account_page_id ) ); ?>"><?php esc_html_e( 'Back to User Dashboard', 'wp-travel-engine' ); ?></a>
					</div>
					<?php
			}
			?>
		</div>
	</div>
	<?php
else :

	$thank_page_msg = __( 'Sorry, you may not have confirmed your booking. Please fill up the form and confirm your booking. Thank you.', 'wp-travel-engine' );

	$thank_page_error = apply_filters( 'wp_travel_engine_thankyou_page_error_msg', $thank_page_msg );

	echo wp_kses_post( $thank_page_error );

endif; ?>
