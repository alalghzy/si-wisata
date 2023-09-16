<?php
/**
 * Payment Details
 */
// Booking
$booking_meta = get_post_meta( $post->ID, 'wp_travel_engine_booking_setting', true );
// Payment Details.
$payment_status                            = get_post_meta( $post->ID, 'wp_travel_engine_booking_payment_status', true );
$payment_gateway                           = get_post_meta( $post->ID, 'wp_travel_engine_booking_payment_gateway', true );
$wp_travel_engine_remaining_payment_detail = get_post_meta( $post->ID, 'wp_travel_engine_remaining_payment_detail', true );
$wte_remaining_paid_transaction_detail     = get_post_meta( $post->ID, 'wp_travel_engine_remaining_payment_transaction_detail', true );
$booking_meta_array                        = booking_meta_details( $post->ID );

$payment_status_arr = array(
	'completed'        => __( 'Completed', 'wp-travel-engine' ),
	'cancelled'        => __( 'Cancelled', 'wp-travel-engine' ),
	'pending'          => __( 'Pending', 'wp-travel-engine' ),
	'partially-paid'   => __( 'Partially Paid', 'wp-travel-engine' ),
	'refunded'         => __( 'Refunded', 'wp-travel-engine' ),
	'abandoned'        => __( 'Abandoned', 'wp-travel-engine' ),
	'voucher-waiting'  => __( 'Waiting for Voucher', 'wp-travel-engine' ),
	'voucher-received' => __( 'Voucher Received', 'wp-travel-engine' ),
	'check-waiting'    => __( 'Waiting for Check', 'wp-travel-engine' ),
	'check-received'   => __( 'Check Received', 'wp-travel-engine' ),
);
?>
<div class="wpte-block wpte-col3">
	<div class="wpte-title-wrap">
		<h4 class="wpte-title"><?php esc_html_e( 'Payment Details', 'wp-travel-engine' ); ?></h4>
		<div class="wpte-button-wrap wpte-edit-bkng">
			<a href="#" class="wpte-btn-transparent wpte-btn-sm">
				<?php wptravelengine_svg_by_fa_icon( "fas fa-pencil-alt" ); ?>
				<?php esc_html_e( 'Edit', 'wp-travel-engine' ); ?>
			</a>
		</div>
	</div>
	<div class="wpte-block-content">
		<ul class="wpte-list">
			<li>
				<b><?php esc_html_e( 'Payment Status', 'wp-travel-engine' ); ?></b>
				<span class="wpte-payment-status"><?php echo isset( $payment_status_arr[ $payment_status ] ) ? esc_html( $payment_status_arr[ $payment_status ] ) : esc_html__( 'N/A', 'wp-travel-engine' ); ?></span>
			</li>
			<li>
				<b><?php esc_html_e( 'Payment Gateway', 'wp-travel-engine' ); ?></b>
				<span><?php echo ! empty( $payment_gateway ) ? esc_html( $payment_gateway ) : 'N/A'; ?></span>
			</li>
			<?php
			if ( ! empty( $payment_details ) && is_array( $payment_details ) ) :
				foreach ( $payment_details as $key => $value ) :
					?>
						<li>
							<b><?php echo esc_html( $value['label'] ); ?></b>
							<span><?php echo esc_html( $value['value'] ); ?></span>
						</li>
					<?php
				endforeach;
			endif;
			?>
		</ul>
	</div>
	<div style="display:none;" class="wpte-block-content-edit edit-payment-info">
		<ul class="wpte-list">
			<li>
				<b><?php esc_html_e( 'Payment Status', 'wp-travel-engine' ); ?></b>
				<span class="wpte-payment-status">
				<div class="wpte-field wpte-select">
					<select name="wp_travel_engine_booking_payment_status" id="wp_travel_engine_booking_payment-status">
						<?php
						foreach ( $payment_status_arr as $key => $status ) {
							$selected = selected( $payment_status, $key, false );
							echo wp_kses(
								'<option ' . $selected . " value='{$key}'>{$status}</option>",
								array(
									'option' => array(
										'selected' => array(),
										'value'    => array(),
									),
								)
							);
						}
						?>
					</select>
					</div>
				</span>
			</li>
			<li>
				<b><?php esc_html_e( 'Payment Gateway', 'wp-travel-engine' ); ?></b>
				<span>
				<div class="wpte-field wpte-text">
					<input type="text" name="wp_travel_engine_booking_payment_gateway" value="<?php echo esc_attr( $payment_gateway ); ?>" >
					</div>
					</span>
			</li>
			<?php
			if ( ! empty( $payment_details ) && is_array( $payment_details ) ) :
				foreach ( $payment_details as $key => $value ) :
					?>
						<li>
							<b>
							<div class="wpte-field wpte-text">
								<input type="text" name="wp_travel_engine_booking_payment_details[<?php echo esc_attr( $key ); ?>][label]" value="<?php echo esc_html( $value['label'] ); ?>" >
							</div>
							</b>
							<span>
							<div class="wpte-field wpte-text">
								<input type="text" name="wp_travel_engine_booking_payment_details[<?php echo esc_attr( $key ); ?>][value]" value="<?php echo esc_html( $value['value'] ); ?>" >
								</div>
								</span>
						</li>
					<?php
				endforeach;
			endif;
			?>
		</ul>
	</div>
	<div class="wpte-block-content">
		<ul class="wpte-list">
		<?php
		if ( ( isset( $booking_meta['place_order']['due'] ) && intval( $booking_meta['place_order']['due'] ) !== 0 ) && ( $booking_meta_array['total_cost'] !== $booking_meta_array['total_paid'] ) ) {
			$partial_label = __( 'Partial', 'wp-travel-engine' );
		} else {
			$partial_label = '';
		}
		if ( ! empty( $additional_fields ) ) {

			?>
			<h4 class="wpte-title"><?php echo esc_html( $partial_label ) . ' ' . __( 'Payment Details', 'wp-travel-engine' ); ?></h4>
			<ul class="wpte-list">
				<?php
				foreach ( $additional_fields as $key => $value ) {
					if ( in_array( $key, $exclude_add_info_key_array, true ) ) {
						$data_label = wp_travel_engine_get_booking_field_label_by_name( $key );
						?>
						<?php
						if ( is_object( json_decode( $value ) ) ) {
							$payment_details = json_decode( $value );
							if ( isset( $payment_details->payer ) && ! empty( $payment_details->payer ) ) {
								?>
								<?php
								foreach ( $payment_details->payer as $k => $v ) {
									?>
									<li>
										<?php
										switch ( $k ) {
											case 'email_address':
												?>
												<b><?php echo esc_html( $k ); ?></b>
												<span><?php echo isset( $v ) && ! is_object( $v ) ? esc_html( $v ) : ''; ?></span>
												<?php
												break;
											case 'payer_id':
												?>
												<b><?php echo esc_html( $k ); ?></b>
												<span><?php echo isset( $v ) && ! is_object( $v ) ? esc_html( $v ) : ''; ?></span>
												<?php
											default:
										}
										?>
									</li>
									<?php
								}
							}
						} else {
							?>
							<li>
								<b><?php echo esc_html( $data_label ); ?></b>
								<span><?php echo isset( $value ) ? esc_attr( $value ) : ''; ?></span>
							</li>
							<?php
						}
					}
				}

				if ( isset( $booking_meta['place_order']['cost'] ) && ! empty( $booking_meta['place_order']['cost'] ) ) {
					?>
					<li>
						<b><?php echo $partial_label . ' ' . __( 'Amount Paid', 'wp-travel-engine' ); ?></b>
						<span>
						<?php
						if ( isset( $booking_meta['place_order']['partial_cost'] ) && ! empty( $booking_meta['place_order']['partial_cost'] ) ) {
							echo wte_esc_price( wte_get_formated_price( $booking_meta['place_order']['partial_cost'] ) );
						} elseif ( isset( $booking_meta['place_order']['cost'] ) && ! empty( $booking_meta['place_order']['cost'] ) ) {
							echo wte_esc_price( wte_get_formated_price( $booking_meta['place_order']['cost'] ) );
						}
						?>
						</span>
					</li>
				<?php } ?>
			</ul>
			<?php
		}

		if ( ! empty( $remaining_payment_detail ) ) {
			?>
			<h4 class="wpte-title"><?php esc_html_e( 'Remaining Payment Details', 'wp-travel-engine' ); ?></h4>
			<ul class="wpte-list">
			<?php
			foreach ( $remaining_payment_detail as $key => $value ) {
				if ( in_array( $key, $exclude_add_info_key_array ) ) {
					$data_label = wp_travel_engine_get_booking_field_label_by_name( $key );
					?>
					<?php
					if ( is_object( json_decode( $value ) ) ) {
						$payment_details = json_decode( $value );
						if ( isset( $payment_details->payer ) && ! empty( $payment_details->payer ) ) {
							?>
							<?php
							foreach ( $payment_details->payer as $k => $v ) {
								?>
								<li>
									<?php
									switch ( $k ) {
										case 'email_address':
											?>
											<b><?php echo esc_html( $k ); ?></b>
											<span><?php echo isset( $v ) && ! is_object( $v ) ? esc_attr( $v ) : ''; ?></span>
											<?php
											break;
										case 'payer_id':
											?>
											<b><?php echo esc_html( $k ); ?></b>
											<span><?php echo isset( $v ) && ! is_object( $v ) ? esc_attr( $v ) : ''; ?></span>
											<?php
										default:
									}
									?>
								</li>
								<?php
							}
						}
					} else {
						?>
						<li>
							<b><?php echo esc_html( $data_label ); ?></b>
							<span><?php echo isset( $value ) ? esc_attr( $value ) : ''; ?></span>
						</li>
						<?php
					}
				}
			}

			if ( isset( $booking_meta['place_order']['partial_due'] ) && ! empty( $booking_meta['place_order']['partial_due'] ) ) {
				?>
					<li>
						<b><?php esc_html_e( 'Remaining Amount Paid', 'wp-travel-engine' ); ?></b>
						<span>
						<?php
						if ( isset( $booking_meta['place_order']['partial_due'] ) && ! empty( $booking_meta['place_order']['partial_due'] ) ) {
							echo wte_esc_price( wte_get_formated_price_html( $booking_meta['place_order']['partial_due'] ) );
						}
						?>
						</span>
					</li>
				<?php } ?>
			<?php
			if ( isset( $wte_remaining_paid_transaction_detail['paypal'] ) && ! empty( $wte_remaining_paid_transaction_detail['paypal'] ) ) {
				foreach ( $wte_remaining_paid_transaction_detail['paypal'] as $key => $val ) {
					?>
					<li>
						<b><?php echo esc_html( $key ); ?></b>
						<span><?php echo esc_html( $val ); ?></span>
					</li>
					<?php
				}
			}
			?>
			</ul>
			<?php
		}
		?>
	</div>
</div> <!-- .wpte-block -->
