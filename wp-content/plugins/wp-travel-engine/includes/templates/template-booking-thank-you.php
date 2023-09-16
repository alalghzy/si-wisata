<?php
/**
 * Thank you page template after booking success.
 *
 * @package WP_Travel_Engine
 */
global $wte_cart;

$cart_items          = $wte_cart->getItems();
$cart_totals         = $wte_cart->get_total();
$date_format         = get_option( 'date_format' );
$wte_settings        = get_option( 'wp_travel_engine_settings' );
$extra_service_title = isset( $wte_settings['extra_service_title'] ) && ! empty( $wte_settings['extra_service_title'] ) ? $wte_settings['extra_service_title'] : __( 'Extra Services:', 'wp-travel-engine' );

if ( ! empty( $cart_items ) ) :

		$thankyou  = __( 'Thank you for booking the trip. Please check your email for confirmation.', 'wp-travel-engine' );
		$thankyou .= __( ' Below is your booking detail:', 'wp-travel-engine' );
		$thankyou .= '<br>';

	if ( isset( $wte_settings['confirmation_msg'] ) && $wte_settings['confirmation_msg'] != '' ) {
		$thankyou = $wte_settings['confirmation_msg'];
	}

		// Display thany-you message.
		echo wp_kses_post( $thankyou );
	?>
	<div class="thank-you-container">
		<h3 class="trip-details"><?php echo esc_html__( 'Trip Details:', 'wp-travel-engine' ); ?></h3>
		<div class="detail-container">

			<?php if ( isset( $_GET['booking_id'] ) && ! empty( $_GET['booking_id'] ) ) : ?>

			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Booking ID:', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( wp_unslash( $_GET['booking_id'] ) ); ?></span>
			</div>

				<?php
					endif;

			foreach ( $cart_items as $key => $cart_item ) :

				?>
			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Trip ID:', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( $cart_item['trip_id'] ); ?></span>
			</div>

			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Trip Name:', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( get_the_title( $cart_item['trip_id'] ) ); ?></span>
			</div>

				<?php
					/**
					 * wte_thankyou_after_trip_name hook
					 *
					 * @hooked wte_display_trip_code_thankyou - Trip Code Addon
					 */
					do_action( 'wte_thankyou_after_trip_name', $cart_item['trip_id'] );
				?>

			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Trip Cost:', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( wte_get_formated_price( wp_travel_engine_get_actual_trip_price( $cart_item['trip_id'] ), '', '', false, false, true ) ); ?></span>
			</div>

			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Trip start date:', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( date_i18n( $date_format, strtotime( $cart_item['trip_date'] ) ) ); ?></span>
			</div>

				<?php
				if ( isset( $cart_item['multi_pricing_used'] ) && $cart_item['multi_pricing_used'] ) :

					foreach ( $cart_item['pax'] as $pax_key => $pax ) :

						if ( '0' == $pax || empty( $pax ) ) {
							continue;
						}

						$pax_label         = wte_get_pricing_label_by_key_invoices( $cart_item['trip_id'], $pax_key, $pax );
						$per_pricing_price = ( $cart_item['pax_cost'][ $pax_key ] / $pax );
						?>
			<div class="detail-item">
				<strong class="item-label"><?php echo esc_html( $pax_label ); ?></strong>
				<span class="value"><?php echo esc_html( $pax ); ?> X <?php echo wp_kses_post( wte_get_formated_price( $per_pricing_price, '', '', false, false, true ) ); ?></span>
			</div>

						<?php
						endforeach;
					else :

						$travelr_per_pricing_price = ( $cart_item['pax_cost']['adult'] / $cart_item['pax']['adult'] );
						?>
			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Number of Traveller(s):', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( $cart_item['pax']['adult'] ); ?> X <?php echo wp_kses_post( wte_get_formated_price( $travelr_per_pricing_price, '', '', false, false, true ) ); ?></span>
			</div>

						<?php
						if ( isset( $cart_item['pax']['child'] ) && 0 != $cart_item['pax']['child'] ) :
							$child_per_pricing_price = ( $cart_item['pax_cost']['child'] / $cart_item['pax']['child'] );
							?>

			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Number of Child Traveller(s):', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( $cart_item['pax']['child'] ); ?> X <?php echo wp_kses_post( wte_get_formated_price( $child_per_pricing_price, '', '', false, false, true ) ); ?></span>
			</div>

							<?php
						endif;

					endif;

					if ( isset( $cart_item['trip_extras'] ) && ! empty( $cart_item['trip_extras'] ) ) :
						?>

			<div class="detail-item">
				<strong class="item-label"><?php echo esc_html( $extra_service_title ); ?></strong>
				<span class="value">
						<?php foreach ( $cart_item['trip_extras'] as $trip_extra ) : ?>
					<div>
							<?php
									$qty           = $trip_extra['qty'];
									$extra_service = $trip_extra['extra_service'];
									$price         = $trip_extra['price'];
									$cost          = $qty * $price;
							if ( 0 === $cost ) {
								continue;
							}
									$formattedCost = wte_get_formated_price( $cost, '', '', false, false, true );
									$output        = "{$qty} X {$extra_service} = {$formattedCost}";
									echo esc_html( $output );
							?>
					</div>
					<?php endforeach; ?>
				</span>
			</div>

						<?php

						endif;

					$cart_discounts = $wte_cart->get_discounts();
					if ( ! empty( $cart_discounts ) || sizeof( $cart_discounts ) !== 0 ) :
						$trip_total       = $wte_cart->get_total();
						$code             = isset( $wte_settings['currency_code'] ) ? $wte_settings['currency_code'] : 'USD';
						$calculated_total = isset( $trip_total['cart_total'] ) && ! empty( $trip_total['cart_total'] ) ? intval( $trip_total['cart_total'] ) : 0;
						foreach ( $cart_discounts as $discount_key => $discount_item ) {
							?>
			<div class="detail-item">
				<strong class="item-label">
							<?php esc_html_e( 'Coupon Discount :', 'wp-travel-engine' ); ?>
					<div><?php echo esc_attr( $discount_item['name'] ) . ' - '; ?><?php echo isset( $discount_item ['type'] ) && 'percentage' === $discount_item ['type'] ? '(' . esc_attr( $discount_item ['value'] ) . '%)' : '(' . wte_get_formated_price( esc_attr( $discount_item['value'] ) ) . ')'; ?>
					</div>
				</strong>
				<span class="value">
							<?php
							if ( 'fixed' === $discount_item ['type'] ) {
								$new_tcost = $calculated_total - $discount_item ['value'];
								echo wte_esc_price( wte_get_formated_price( $discount_item['value'], '', '', false, false, true ) );
							} elseif ( 'percentage' === $discount_item ['type'] ) {
								$discount_amount_actual = number_format( ( ( $calculated_total * $discount_item ['value'] ) / 100 ), '2', '.', '' );
								$new_tcost              = $calculated_total - $discount_amount_actual;
								echo wte_esc_price( wte_get_formated_price( $discount_amount_actual, '', '', false, false, true ) );
							} else {
								$new_tcost = $calculated_total;
							}
						}
						?>
				</span>
			</div>
						<?php
						endif;

					if ( wp_travel_engine_is_trip_partially_payable( $cart_item['trip_id'] ) ) :

						$booking = get_post_meta( wte_clean( wp_unslash( $_GET['booking_id'] ) ), 'wp_travel_engine_booking_setting', true );
						$due     = isset( $booking['place_order']['due'] ) ? $booking['place_order']['due'] : 0;
						$paid    = isset( $booking['place_order']['cost'] ) ? $booking['place_order']['cost'] : 0;

						if ( 0 < floatval( $due ) && $paid != floatval( $due + $paid ) ) :

							?>
			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Total Paid:', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( wte_get_formated_price( $paid, '', '', false, false, true ) ); ?></span>
			</div>

			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Due:', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( wte_get_formated_price( $due, '', '', false, false, true ) ); ?></span>
			</div>

							<?php

						endif;

						endif;

						endforeach;
			?>
			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Total Cost:', 'wp-travel-engine' ); ?></strong>
				<span class="value">
					<?php
					if ( ! empty( $cart_discounts ) || sizeof( $cart_discounts ) !== 0 ) {
						echo esc_html( wte_get_formated_price( $new_tcost, '', '', false, false, true ) );
					} else {
						echo esc_html( wte_get_formated_price( $cart_totals['cart_total'], '', '', false, false, true ) );
					}
					?>
				</span>
			</div>

		</div>
		<?php
		if ( ! empty( $_GET['booking_id'] ) ) :
			$booking_id     = wte_clean( wp_unslash( $_GET['booking_id'] ) );
			$payment_method = get_post_meta( $booking_id, 'wp_travel_engine_booking_payment_method', true );

			$payment_method_actions = array(
				'direct_bank_transfer' => function() {
					$settings = get_option( 'wp_travel_engine_settings', array() );
					$instructions = isset( $settings['bank_transfer']['instruction'] ) ? $settings['bank_transfer']['instruction'] : '';
					?>
		<div class="wte-bank-transfer-instructions">
					<?php echo wp_kses_post( $instructions ); ?>
		</div>
		<h3 class="bank-details"><?php echo esc_html__( 'Bank Details:', 'wp-travel-engine' ); ?></h3>
					<?php
						$bank_details = isset( $settings['bank_transfer']['accounts'] ) && is_array( $settings['bank_transfer']['accounts'] ) ? $settings['bank_transfer']['accounts'] : array();
					foreach ( $bank_details as $bank_detail ) :
							$details = array(
								'bank_name'      => array(
									'label' => __( 'Bank:', 'wp-travel-engine' ),
									'value' => $bank_detail['bank_name'],
								),
								'account_name'   => array(
									'label' => __( 'Account Name:', 'wp-travel-engine' ),
									'value' => $bank_detail['account_name'],
								),
								'account_number' => array(
									'label' => __( 'Account Number:', 'wp-travel-engine' ),
									'value' => $bank_detail['account_number'],
								),
								'sort_code'      => array(
									'label' => __( 'Sort Code:', 'wp-travel-engine' ),
									'value' => $bank_detail['sort_code'],
								),
								'iban'           => array(
									'label' => __( 'IBAN:', 'wp-travel-engine' ),
									'value' => $bank_detail['iban'],
								),
								'swift'          => array(
									'label' => __( 'BIC/SWIFT:', 'wp-travel-engine' ),
									'value' => $bank_detail['swift'],
								),

							);

						?>
		<div class="detail-container">
						<?php
						foreach ( $details as $detail ) :
							?>
			<div class="detail-item">
				<strong class="item-label"><?php echo esc_html( $detail['label'] ); ?></strong>
				<span class="value"><?php echo esc_html( $detail['value'] ); ?></span>
			</div>
			<?php endforeach; ?>
		</div>
						<?php
						endforeach;
				},
				'check_payments'       => function() {
								$settings = get_option( 'wp_travel_engine_settings', array() );
								$instructions = isset( $settings['check_payment']['instruction'] ) ? $settings['check_payment']['instruction'] : '';
					?>
		<div class="wte-bank-transfer-instructions">
					<?php echo wp_kses_post( $instructions ); ?>
		</div>
					<?php
				},
			);

			if ( isset( $payment_method_actions[ $payment_method ] ) ) {
				$payment_method_actions[ $payment_method ]();
			}
				endif;
		?>
	</div>
	<?php
	else :

		$thank_page_msg = __( 'Sorry, you may not have confirmed your booking. Please fill up the form and confirm your booking. Thank you.', 'wp-travel-engine' );

		$thank_page_error = apply_filters( 'wp_travel_engine_thankyou_page_error_msg', $thank_page_msg );

		echo wp_kses_post( $thank_page_error );

endif;

	// Clear cart data.
	$wte_cart->clear();
