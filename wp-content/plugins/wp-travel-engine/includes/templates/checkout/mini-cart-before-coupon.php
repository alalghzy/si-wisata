<?php
/**
 * Mini cart template.
 *
 * @package WP Travel Engine.
 */
global $wte_cart;

$cart_items          = $wte_cart->getItems();
$date_format         = get_option( 'date_format' );
$cart_totals         = $wte_cart->get_total( false );
$wte_settings        = get_option( 'wp_travel_engine_settings' );
$extra_service_title = isset( $wte_settings['extra_service_title'] ) ? $wte_settings['extra_service_title'] : __( 'Extra Services', 'wp-travel-engine' );
$cart_discounts      = $wte_cart->get_discounts();
if ( ! empty( $cart_items ) ) :
	?>
<div class="wpte-bf-book-summary">
	<div class="wpte-bf-summary-wrap">
		<div class="wpte-bf-title"><?php esc_html_e( 'Booking Summary', 'wp-travel-engine' ); ?></div>

		<?php foreach ( $cart_items as $key => $cart_item ) : ?>
		<div class="wpte-bf-trip-name-wrap">
			<div class="wpte-bf-trip-name"><?php echo esc_html( get_the_title( $cart_item['trip_id'] ) ); ?></div>
			<?php
			/**
			 * wte_bf_after_trip_name hook
			 *
			 * @hooked wte_display_trip_code_minicart - Trip Code Addon
			 */
			do_action( 'wte_bf_after_trip_name', $cart_item['trip_id'] );
			?>
			<span class="wpte-bf-trip-date"><?php echo esc_html( sprintf( __( 'Starting Date: %1$s', 'wp-travel-engine' ), date_i18n( $date_format, strtotime( $cart_item['trip_date'] ) ) ) ); ?></span>
		</div>
		<table class="wpte-bf-summary-table">
			<tbody>
				<?php
				foreach ( $cart_item['pax'] as $pax_label => $pax ) :
					if ( $pax == '0' ) {
						continue;
					}

						$pax_label_disp = $pax_label;

					if ( isset( $cart_item['multi_pricing_used'] ) && $cart_item['multi_pricing_used'] ) :
						$pax_label_disp = wte_get_pricing_label_by_key( $cart_item['trip_id'], $pax_label );
						endif;
					?>
					<tr>
						<td><span><?php printf( esc_html__( '%1$s %2$s', 'wp-travel-engine' ), number_format_i18n( $pax ), ucfirst( $pax_label_disp ) ); ?></span></td>
						<td><b><?php echo wp_kses_post( wte_get_formated_price_html( $cart_item['pax_cost'][ $pax_label ], null, true ) ); ?></b></td>
					</tr>
				<?php endforeach; ?>

				<!-- Extra Services -->
				<?php if ( isset( $cart_item['trip_extras'] ) && ! empty( $cart_item['trip_extras'] ) ) : ?>
				<tr>
					<td colspan="2"><?php echo esc_html( $extra_service_title ); ?></td>
				</tr>
					<?php foreach ( $cart_item['trip_extras'] as $trip_extra ) : ?>
				<tr>
					<td><span><?php echo esc_html( $trip_extra['qty'] ); ?> x <?php echo esc_html( $trip_extra['extra_service'] ); ?></span></td>
					<td><b><?php echo wp_kses_post( wte_get_formated_price_html( $trip_extra['qty'] * $trip_extra['price'], null, true ) ); ?></b></td>
				</tr>
				<?php endforeach; ?>
				<?php endif; ?>
				<!-- ./ Extra Services -->
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2">
						<span class="wpte-bf-total-txt"><?php esc_html_e( 'Total :', 'wp-travel-engine' ); ?></span>
						<?php echo wte_esc_price( wte_get_formated_price_html( $cart_totals['sub_total'], null, true ) ); ?>
					</td>
				</tr>
				<!-- Coupon Code Discount -->
				<?php
				if ( ! empty( $cart_discounts ) || sizeof( $cart_discounts ) !== 0 ) {
					$obj              = \wte_functions();
					$trip_total       = $wte_cart->get_total();
					$code             = isset( $wte_settings['currency_code'] ) ? $wte_settings['currency_code'] : 'USD';
					$currency         = $obj->wp_travel_engine_currencies_symbol( $code );
					$calculated_total = isset( $trip_total['cart_total'] ) && ! empty( $trip_total['cart_total'] ) ? intval( $trip_total['cart_total'] ) : 0;

					foreach ( $cart_discounts as $discount_key => $discount_item ) {
						?>
						<tr class="wte-coupons-discount-calculation-tr">
							<td class="wte-coupons-discount-calculation-td">
								<span class="wpte-bf-total-txt"><?php esc_html_e( 'Coupon Discount :', 'wp-travel-engine' ); ?> <?php echo esc_attr( $discount_item['name'] ) . ' - '; ?><?php echo isset( $discount_item ['type'] ) && 'percentage' === $discount_item ['type'] ? '(' . esc_attr( $discount_item ['value'] ) . '%)' : '(' . esc_attr( $currency ) . ' ' . esc_attr( $discount_item['value'] ) . ')'; ?></span>
							</td>
							<td class="wte-coupons-discount-calculation-td">
								<span class="wpte-currency-code"><?php echo esc_attr( $currency ); ?></span>
								<?php
								if ( 'fixed' === $discount_item ['type'] ) {
									$new_tcost = $obj->wp_travel_engine_price_format( $calculated_total - $discount_item ['value'] );
									?>
									<span class="wpte-price"><?php echo esc_html( $new_tcost ); ?></span>
									<?php
								} elseif ( 'percentage' === $discount_item ['type'] ) {

										$discount_amount_actual = number_format( ( ( $calculated_total * $discount_item ['value'] ) / 100 ), '2', '.', '' );
										$new_tcost              = number_format( ( $calculated_total - $discount_amount_actual ), '2', '.', '' );
									?>
									<span class="wpte-price"><?php echo esc_html( $discount_amount_actual ); ?></span>
									<?php
								} else {
									$new_tcost = $calculated_total;
								}
								?>
							</td>
						</tr>
						<tr class="wte-coupons-discount-calculation-tr">
							<td class="wte-coupons-discount-calculation-td">
								<span class="wpte-bf-total-txt">
									<?php esc_html_e( 'Total after Discount :', 'wp-travel-engine' ); ?>
								</span>
							</td>
							<td class="wte-coupons-discount-calculation-td">
								<span class="wpte-currency-code"><?php echo esc_attr( $currency ); ?></span>
								<span class="wpte-price"><?php echo esc_html( $new_tcost ); ?></span>
							</td>
						</tr>
						<?php
					}
				}
				?>
				<!-- ./ Coupon Code Discount -->
			</tfoot>
		</table>
			<?php if ( wp_travel_engine_is_trip_partially_payable( $cart_item['trip_id'] ) ) : ?>
		<table class="wpte-bf-extra-info-table">
			<tbody>
				<tr>
					<td><span><?php echo esc_html__( 'Down Payment', 'wp-travel-engine' ); ?></span></td>
					<td class="wpte-dwnpay-amt"><b><?php echo wte_esc_price( wte_get_formated_price_html( $cart_totals['total_partial'], null, true ) ); ?></b></td>
				</tr>
				<tr>
					<td><span><?php esc_html_e( 'Remaining Payment', 'wp-travel-engine' ); ?></span></td>
					<td class="wpte-remain-amt"><b><?php echo wte_esc_price( wte_get_formated_price_html( ( $cart_totals['sub_total'] - $cart_totals['total_partial'] ), null, true ) ); ?></b></td>
				</tr>
			</tbody>
		</table>
		<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<div class="wpte-bf-summary-total">
		<?php
		if ( ! empty( $cart_discounts ) || sizeof( $cart_discounts ) !== 0 ) {
			$payable_now = $new_tcost;
		} else {
			$payable_now = wp_travel_engine_is_trip_partially_payable( $cart_item['trip_id'] ) ? $cart_totals['total_partial'] : $cart_totals['cart_total'];
		}
		?>
		<div class="wpte-bf-total-price">
			<span class="wpte-bf-total-txt"><?php esc_html_e( 'Total Payable Now :', 'wp-travel-engine' ); ?></span>
			<span class="wpte-price"><?php echo wte_esc_price( wte_get_formated_price_html( $payable_now, null, true ) ); ?>
			</span>
		</div>
	</div><!-- .wpte-bf-summary-total -->
</div><!-- .wpte-bf-book-summary -->
	<?php
endif;
