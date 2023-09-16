<?php
/**
 *
 * New Booking order template.
 *
 * @since 4.3.0
 */
$trip_details    = array(
	'{tdate}'    => __( 'Trip Start Date', 'wp-travel-engine' ),
	'{traveler}' => __( 'Total Traveller(s)', 'wp-travel-engine' ),
);
$billing_details = array(
	'{fullname}'        => __( 'Booking Name', 'wp-travel-engine' ),
	'{user_email}'      => __( 'Booking Email', 'wp-travel-engine' ),
	'{billing_address}' => __( 'Billing Address', 'wp-travel-engine' ),
	'{city}'            => __( 'City', 'wp-travel-engine' ),
	'{country}'         => __( 'Country', 'wp-travel-engine' ),
);
$payment_details = array(
	'{subtotal}'        => __( 'Subtotal', 'wp-travel-engine' ),
	'{total}'           => __( 'Total', 'wp-travel-engine' ),
	'{discount_amount}' => __( 'Discount', 'wp-travel-engine' ),
	'{paid_amount}'     => __( 'Paid amount', 'wp-travel-engine' ),
	'{due}'             => __( 'Due', 'wp-travel-engine' ),
);
?>
<table class="main" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="content-wrap aligncenter">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="content-block">
						<h1 class="aligncenter"><?php echo esc_html( WTE_Booking_Emails::get_string( 'order_confirmation', $args['sent_to'], 'heading' ) ); ?></h1>
					</td>
				</tr>
				<tr>
					<td class="content-block aligncenter">
						<table class="invoice">
							<tr>
								<td style="margin: 0; padding: 5px 0;" valign="top"><?php echo esc_html( WTE_Booking_Emails::get_string( 'order', $args['sent_to'], 'greeting' ) ); ?><br><br>
								<?php echo esc_html( WTE_Booking_Emails::get_string( 'order_confirmation', $args['sent_to'], 'greeting_byline' ) ); ?>
								</td>
							</tr>
							<br>
							<tr>
								<td style="margin: 0; padding: 5px 0;" valign="top">
									<table class="invoice-items" cellpadding="0" cellspacing="0">
										<tr>
											<td class="title-holder" style="margin: 0;" valign="top" colspan="2">
												<h3 class="alignleft"><?php echo esc_html__( 'Booked Trips ({booking_trips_count})', 'wp-travel-engine' ); ?></h3>
											</td>
										</tr>
										<tr><td colspan="2">{booking_details}</td></tr>
										<tr>
											<td class="title-holder" style="margin: 0;" valign="top">
												<h3 class="alignleft"><?php echo esc_html__( 'Billing Details', 'wp-travel-engine' ); ?></h3>
											</td>
										</tr>
										<?php foreach ( $billing_details as $tag => $label ) : ?>
											<tr>
												<td><?php echo esc_html( $label ); ?></td>
												<td class="alignright"><?php echo esc_html( $tag ); ?></td>
											</tr>
										<?php endforeach; ?>
										<!-- Bank Details -->
										<tr>
											<td colspan="2">{bank_details}</td>
										</tr>
										<!-- Check Payment Instructions -->
										<tr>
											<td colspan="2">{check_payment_instruction}</td>
										</tr>
										<tr>
											<td class="title-holder" style="margin: 0;" valign="top">
												<h3 class="alignleft"><?php echo esc_html__( 'Payment Details', 'wp-travel-engine' ); ?></h3>
											</td>
										</tr>
										<?php foreach ( $payment_details as $tag => $label ) : ?>
											<tr>
												<td><?php echo esc_html( $label ); ?></td>
												<td class="alignright"><?php echo esc_html( $tag ); ?></td>
											</tr>
										<?php endforeach; ?>
										<tr class="total">
											<td class="alignright"><?php esc_html_e( 'Total', 'wp-travel-engine' ); ?></td>
											<td class="alignright">{price}</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php if ( 'admin' === $args['sent_to'] ) : ?>
				<tr>
					<td class="content-block aligncenter">
						<a href="{booking_url}"><?php esc_html_e( 'View booking on your website site', 'wp-travel-engine' ); ?></a>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td class="content-block aligncenter">
						{sitename}
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php
