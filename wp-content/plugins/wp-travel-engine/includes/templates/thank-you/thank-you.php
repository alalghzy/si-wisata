<?php
/**
 * Thank you page template.
 *
 * @since 4.3.6
 */

if ( is_null( $booking_id ) ) {
	$booking_id = get_post_meta( $payment_id, 'booking_id', true );
}

$booking = get_post( $booking_id );
if ( is_null( $booking ) || 'booking' !== $booking->post_type ) {
	return __( 'Invalid booking or booking has been removed.', 'wp-travel-engine' );
}

$date_format         = get_option( 'date_format' );
$wte_settings        = get_option( 'wp_travel_engine_settings' );
$extra_service_title = isset( $wte_settings['extra_service_title'] ) && ! empty( $wte_settings['extra_service_title'] ) ? $wte_settings['extra_service_title'] : __( 'Extra Services:', 'wp-travel-engine' );
$thankyou            = __( 'Thank you for booking the trip. Please check your email for confirmation.', 'wp-travel-engine' );
$thankyou           .= __( ' Below is your booking detail:', 'wp-travel-engine' );
$thankyou           .= '<br>';

if ( ! empty( $wte_settings['confirmation_msg'] ) ) {
	$thankyou = $wte_settings['confirmation_msg'];
}

$currency  = $booking->cart_info['currency'];
$cart_info = $booking->cart_info;
// Display thany-you message.
echo wp_kses_post( $thankyou );
?>
<div class="thank-you-container">
	<h3 class="trip-details"><?php echo esc_html__( 'Booking Details:', 'wp-travel-engine' ); ?></h3>
	<div class="detail-container">
		<div class="detail-item">
			<strong class="item-label"><?php esc_html_e( 'Booking ID :', 'wp-travel-engine' ); ?></strong>
			<span class="value"><?php echo esc_html( $booking->ID ); ?></span>
		</div>
		<div class="detail-item" style="text-align:center;justify-content:center;">
			<strong style="font-size:18px;font-weight:normal"><?php echo _n( 'Trip Details', 'Trips Details', count( $booking->order_trips ), 'wp-travel-engine' ); ?></strong>
		</div>
		<?php
		$order_trips = $booking->order_trips;
		foreach ( $order_trips as $key => $item ) :
			$item = (object) $item;
			$trip = get_post( $item->ID );
			?>
			<div class="detail-item">
				<a href="<?php echo esc_url( get_permalink( $trip->ID ) ); ?>"><?php echo esc_html( $trip->post_title ); ?></a> <code><?php echo esc_html( "[#{$trip->ID}]" ); ?></code>
			</div>
			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Trip ID:', 'wp-travel-engine' ); ?></strong>
				<span class="value"><?php echo esc_html( $trip->ID ); ?></span>
			</div>
			<?php
			/**
			 * wte_thankyou_after_trip_name hook
			 *
			 * @hooked wte_display_trip_code_thankyou - Trip Code Addon
			 */
			do_action( 'wte_thankyou_after_trip_name', $trip->ID );
			?>
			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Trip Cost:', 'wp-travel-engine' ); ?></strong>
				<span class="value">
				<?php
				$category_terms = get_terms(
					array(
						'taxonomy'   => 'trip-packages-categories',
						'include'    => array_keys( $item->pax ),
						'hide_empty' => false,
					)
				);
				$labels         = array();
				foreach ( $category_terms as $cterm ) {
					$labels[ $cterm->term_id ] = $cterm->name;
				}
				foreach ( $item->pax as $category_id => $tcount ) {
					if ( +$tcount < 1 ) {
						continue;
					}

					$label    = $labels[ $category_id ];
					$pax_cost = +$item->pax_cost[ $category_id ] / +$tcount;
					echo esc_html( "{$tcount} X {$label} (" . wte_get_formated_price( $pax_cost, $currency, '', ! 0 ) . ') = ' . wte_get_formated_price( $item->pax_cost[ $category_id ], $currency, '', ! 0 ) );
					echo '<br/>';
				}
				?>
				</span>
			</div>

			<div class="detail-item">
				<strong class="item-label"><?php esc_html_e( 'Trip start date:', 'wp-travel-engine' ); ?></strong>
				<span
					class="value"><?php echo esc_html( date_i18n( $date_format, strtotime( $item->datetime ) ) ); ?></span>
			</div>
			<?php
			if ( isset( $item->trip_extras ) && ! empty( $item->trip_extras ) ) :
				?>
				<div class="detail-item">
					<strong class="item-label"><?php echo esc_html( $extra_service_title ); ?></strong>
					<span class="value">
						<?php foreach ( $item->trip_extras as $trip_extra ) : ?>
							<div>
								<?php
								$qty           = $trip_extra['qty'];
								$extra_service = $trip_extra['extra_service'];
								$price         = $trip_extra['price'];
								$cost          = $qty * $price;
								if ( 0 === $cost ) {
									continue;
								}
								$formattedCost = wte_get_formated_price( $cost, $currency, '', ! 0 );
								$output        = "{$qty} X {$extra_service} (" . wte_get_formated_price( $price, $currency, '', ! 0 ) . ") = {$formattedCost}";
								echo esc_html( $output );
								?>
							</div>
						<?php endforeach; ?>
					</span>
				</div>
				<?php
			endif;
		endforeach;
		$payment_method = get_post_meta( $payment_id, 'payment_gateway', true );
		?>
		<div class="detail-item" style="text-align:center;justify-content:center;">
			<strong style="font-size:18px;font-weight:normal;"><?php echo esc_html__( 'Payment Details', 'wp-travel-engine' ); ?></strong>
		</div>
		<?php
		$paid_amount = 0;
		if ( is_array( $booking->payments ) && count( $booking->payments ) ) {
			foreach ( $booking->payments as $index => $pid ) {
				$payment_obj    = get_post( $pid );
				$payment_amount = isset( $payment_obj->payment_amount['value'] ) ? $payment_obj->payment_amount['value'] : (float) $payment_obj->payable['amount'];
				$paid_amount   += (float) $payment_obj->payable['amount'];
				?>
				<div class="detail-item">
					<strong class="item-label"><?php echo esc_html( sprintf( __( 'Partial Payment #%d', 'wp-travel-engine' ), $index + 1 ) ); ?></strong>
					<span class="value"><?php echo wte_esc_price( wte_get_formated_price( $payment_amount, '', '', false, false, true ) ) . " <code>[" . esc_html( $payment_obj->payment_status ) . "]</code>"; ?></span>
				</div>
				<?php
			}
		}

		$payment = get_post( $payment_id );
		?>
		<div class="detail-item">
			<strong class="item-label"><?php esc_html_e( 'Payment amount:', 'wp-travel-engine' ); ?></strong>
			<span class="value">
			<?php echo esc_html( wte_get_formated_price( $payment->payable['amount'], $currency, '', ! 0 ) ); ?>
			<?php
			echo wp_kses(
				"<code>[{$payment->payment_status}]</code>",
				array( 'code' => array( 'style' => array() ) )
			);
			?>
			</span>
		</div>
		<div class="detail-item">
			<strong class="item-label"><?php esc_html_e( 'Due amount:', 'wp-travel-engine' ); ?></strong>
			<span class="value">
				<?php echo esc_html( wte_get_formated_price( +$cart_info['total'] - $paid_amount, $currency, '', ! 0 ) ); ?>
			</span>
		</div>
		<div class="detail-item">
			<strong class="item-label"><?php esc_html_e( 'Remarks: ', 'wp-travel-engine' ); ?></strong>
			<div class="value"><?php echo esc_html( WTE_Booking_Response::responses( $payment->payment_status ) ); ?></div>
		</div>
		<div class="detail-item">
			<strong class="item-label"><?php esc_html_e( 'Subtotal:', 'wp-travel-engine' ); ?></strong>
			<span class="value">
				<?php
					echo esc_html( wte_get_formated_price( +$booking->cart_info['subtotal'], $cart_info['currency'], '', ! 0 ) );
				?>
			</span>
		</div>
		<?php
		$discount_figure = 0;
		$cart_info       = $booking->cart_info;
		if ( ! empty( $cart_info['discounts'] ) ) :
			$discounts     = $cart_info['discounts'];
			$discount      = array_shift( $discounts );
			$discount_type = $discount['type'];

			switch ( $discount_type ) {
				case 'percentage':
					$discount_figure = +$cart_info['subtotal'] * ( +$discount['value'] / 100 );
					break;
				case 'fixed':
					$discount_figure = +$discount['value'];
					break;
				default:
					$discount_figure = 0;
					break;
			}
			?>
			<div class="detail-item">
				<strong class="item-label"><?php echo sprintf( __( 'Discount%s:', 'wp-travel-engine' ), 'percentage' === $discount_type ? ' (' . $discount['value'] . '%)' : '' ); ?></strong>
				<span class="value">
					<?php echo esc_html( '-' . wte_get_formated_price( +$discount_figure, $cart_info['currency'], '', ! 0 )  ); ?>
				</span>
			</div>
			<?php
		endif;

		$tax_figure = 0;
		$cart_info       = $booking->cart_info;
		$tax_amount = wp_travel_engine_get_tax_detail( $cart_info );
		$tax_amount_total = wte_get_formated_price( +$tax_amount['tax_actual'], $cart_info['currency'], '', ! 0 );
		if( ( isset( $wte_settings['tax_enable'] ) && $wte_settings['tax_enable'] == 'yes' ) && ( isset( $wte_settings['tax_type_option'] ) && $wte_settings['tax_type_option'] == 'exclusive' ) ):
		?>
		<div class="detail-item">
			<strong class="item-label"><?php echo sprintf( __( 'Tax (%s):', 'wp-travel-engine' ), $cart_info['tax_amount'] . '%' ); ?></strong>
			<span class="value">
				<?php echo esc_html( '+' . $tax_amount_total  ); ?>
			</span>
		</div>
		<?php endif;?>
		<div class="detail-item">
			<strong class="item-label"><?php esc_html_e( 'Total:', 'wp-travel-engine' ); ?></strong>
			<span class="value">
				<?php
					echo esc_html( wte_get_formated_price( +$cart_info['total'], $cart_info['currency'], '', ! 0 ) );
				?>
			</span>
		</div>
	</div>
</div>
<?php
do_action( "wte_after_thankyou_booking_details_{$payment_method}", $payment_id );
if ( count( $booking->payments ) > 1 ) :
	?>
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
	<?php
endif;
