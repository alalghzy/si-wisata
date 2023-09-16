<?php
/**
 * Mini cart template.
 *
 * @package WP Travel Engine.
 */
global $wte_cart;

$cart_items  = $wte_cart->getItems();
$date_format = get_option( 'date_format', 'F j, Y' );
$cart_totals = $wte_cart->get_total( false );

$wte_settings        = get_option( 'wp_travel_engine_settings' );
$extra_service_title = isset( $wte_settings['extra_service_title'] ) ? $wte_settings['extra_service_title'] : __( 'Extra Services', 'wp-travel-engine' );
$cart_discounts      = $wte_cart->get_discounts();
$partial_final_total = 0;
if ( ! empty( $cart_items ) ) :
	$currency = wp_travel_engine_get_currency_code_or_symbol();
	?>
	<div class="wpte-bf-summary-wrap">
		<div class="wpte-bf-title"><?php esc_html_e( 'Booking Summary', 'wp-travel-engine' ); ?></div>

		<?php
		foreach ( $cart_items as $key => $cart_item ) :
			?>
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

				<span class="wpte-bf-trip-date">
				<?php
				$trip_date = $cart_item['trip_date'];
				if ( ! empty( $cart_item['trip_time'] ) ) {
					$trip_date    = $cart_item['trip_time'];
					$date_format .= ' \a\t ' . get_option( 'time_format', 'g:i a' );
				}

				echo esc_html(
					sprintf(
						__( 'Starting Date: %1$s ', 'wp-travel-engine' ),
						wp_date( $date_format, strtotime( $trip_date ), new \DateTimeZone( 'utc' ) )
					)
				);
				?>
				</span>
			</div>
			<table class="wpte-bf-summary-table">
				<tbody>
					<tr class="wte-booked-package-name">
						<td colspan="2">
							<?php
							$package = get_post( $cart_item['price_key'] );
							if ( $package ) {
								printf(
									'<span class="label">%1$s</span><span class="value">%2$s</span>',
									esc_html__( 'Package:', 'wp-travel-engine' ),
									esc_html( $package->post_title )
								);
							}
							?>
						</td>
					</tr>
					<?php
					foreach ( $cart_item['pax'] as $pax_label => $pax ) :
						if ( $pax == '0' ) {
							continue;
						}

						$pax_label_disp = $pax_label;

						if ( isset( $cart_item['multi_pricing_used'] ) && $cart_item['multi_pricing_used'] ) :
							$pax_label_disp = wte_get_pricing_label_by_key( $cart_item['trip_id'], $pax_label );
						endif;

						if ( isset( $cart_item['category_info'][ $pax_label ] ) ) {
							$pricing_category = $cart_item['category_info'][ $pax_label ];
							$pax_label_disp   = $pricing_category['label'];
						}

						?>
						<tr>
							<td><span><?php printf( esc_html( __( '%1$s %2$s', 'wp-travel-engine' ) ), number_format_i18n( $pax ), ucfirst( $pax_label_disp ) ); ?></span></td>
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
							<span class="wpte-bf-total-txt"><?php esc_html_e( 'Subtotal :', 'wp-travel-engine' ); ?></span>
							<?php
							// echo $cart_totals['sub_total'];
							echo wte_esc_price( wte_get_formated_price_html( $cart_totals['cart_total'], null, true ) );
							?>
						</td>
					</tr>
				</tfoot>
			</table>
			<!-- Price Adjustments -->
			<?php
			$has_tax                = isset( $wte_settings['tax_enable'] ) && 'yes' === $wte_settings['tax_enable'];
			$has_tax                = $has_tax && isset( $wte_settings['tax_type_option'] ) && $wte_settings['tax_type_option'] == 'exclusive';
			$show_price_adjustments = ! empty( $cart_discounts );

			if ( ! empty( $cart_discounts ) || $has_tax ) :
				?>
				<table class="wpte-bf_price-adjustments">
					<tbody>
						<?php
						if ( ! empty( $cart_discounts ) ) {
							$obj              = \wte_functions();
							$trip_total       = $wte_cart->get_total();
							$code             = isset( $wte_settings['currency_code'] ) ? $wte_settings['currency_code'] : 'USD';
							$currency         = wp_travel_engine_get_currency_code_or_symbol();
							$calculated_total = isset( $trip_total['cart_total'] ) && ! empty( $trip_total['cart_total'] ) ? intval( $trip_total['cart_total'] ) : 0;

							foreach ( $cart_discounts as $discount_key => $discount_item ) {
								?>
									<tr class="wte-coupons-discount-calculation-tr">
										<td class="wte-coupons-discount-calculation-td">
											<span>
											<?php esc_html_e( 'Coupon Discount :', 'wp-travel-engine' ); ?> <?php echo esc_attr( $discount_item['name'] ) . ' - '; ?><?php echo isset( $discount_item ['type'] ) && 'percentage' === $discount_item ['type'] ? '(' . esc_attr( $discount_item ['value'] ) . '%)' : '(' . esc_attr( $currency ) . ' ' . esc_attr( $discount_item['value'] ) . ')'; ?></span>
										</td>
										<td class="wte-coupons-discount-calculation-td">
											<b>
										<?php
										if ( 'fixed' === $discount_item['type'] ) {
											$new_tcost     = $calculated_total - $discount_item ['value'];
											$tax_actual    = number_format( ( ( $new_tcost * $cart_totals['tax_amount'] ) / 100 ), '2', '.', '' );
											$new_totalcost = number_format( ( $new_tcost + $tax_actual ), '2', '.', '' );
											echo '-' . wte_get_formated_price_html( $discount_item ['value'], null, true );

										} elseif ( 'percentage' === $discount_item ['type'] ) {

											$discount_amount_actual = number_format( ( ( $calculated_total * $discount_item ['value'] ) / 100 ), '2', '.', '' );
											$new_tcost              = number_format( ( $calculated_total - $discount_amount_actual ), '2', '.', '' );
											$tax_actual             = number_format( ( ( $new_tcost * $cart_totals['tax_amount'] ) / 100 ), '2', '.', '' );
											$new_totalcost          = number_format( ( $new_tcost + $tax_actual ), '2', '.', '' );
											echo '-' . wte_get_formated_price_html( $discount_amount_actual, null, true );
										} else {
											$new_tcost = $calculated_total;
										}
										?>
											</b>
										</td>
									</tr>
									<?php
							}
						} else {
							$tax_actual    = number_format( ( ( $cart_totals['cart_total'] * $cart_totals['tax_amount'] ) / 100 ), '2', '.', '' );
							$new_totalcost = number_format( ( $cart_totals['cart_total'] + $tax_actual ), '2', '.', '' );
						}
						if ( $has_tax ) {
						?>
							<tr class="wte-tax-calculation-tr">
								<td>
									<span><?php echo sprintf( esc_html__( 'Tax (%s%%)', 'wp-travel-engine' ), (int) $cart_totals['tax_amount'] ); ?></span>
								</td>
								<td>
									<b><?php echo '+' . wte_get_formated_price_html( $tax_actual, null, true ); ?></b>
								</td>
							</tr>
						<?php
						}
					endif;
					?>
				</tbody>
			</table>
			<!-- Partial payment Section -->
			<?php if ( wp_travel_engine_is_trip_partially_payable( $cart_item['trip_id'] ) ) : ?>
				<table class="wpte-bf-extra-info-table">
					<tbody>
						<tr>
							<td><span><?php echo esc_html__( 'Down Payment', 'wp-travel-engine' ); ?></span></td>
							<td class="wpte-dwnpay-amt"><b>
							<?php
							echo wte_esc_price( wte_get_formated_price_html( $cart_totals['total_partial'], null, true ) );
							?>
							</b></td>
						</tr>

						<tr>
						<td><span><?php esc_html_e( 'Remaining Payment', 'wp-travel-engine' ); ?></span></td>
							<td class="wpte-remain-amt">
							<b>
							<?php
							if ( ( ! empty( $cart_discounts ) || sizeof( $cart_discounts ) !== 0 ) ) {
								// echo $new_tcost;
								// echo $new_totalcost;
								if ( $cart_totals['tax_amount'] != '' ) {
									$partial_final_total = ( $new_totalcost - (float) $cart_totals['total_partial'] );
								} else {
									$partial_final_total = ( intval( $new_tcost ) - (float) $cart_totals['total_partial'] );
								}
								echo wte_esc_price( wte_get_formated_price_html( $partial_final_total, null, true ) );
							} else {
								$partial_final_total = ( (float) $cart_totals['sub_total'] - (float) $cart_totals['total_partial'] );
								echo wte_esc_price( wte_get_formated_price_html( $partial_final_total, null, true ) );
							}
							?>
							</b>
							</td>
						</tr>
					</tbody>
				</table>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<div class="wpte-bf-summary-total">
	<?php
	if ( ( ! empty( $cart_discounts ) || sizeof( $cart_discounts ) !== 0 ) && wp_travel_engine_is_trip_partially_payable( $cart_item['trip_id'] ) ) {
		$payable_now = $cart_totals['total_partial'];
	} elseif ( ( ! empty( $cart_discounts ) || sizeof( $cart_discounts ) !== 0 ) && ! wp_travel_engine_is_trip_partially_payable( $cart_item['trip_id'] ) ) {
		$payable_now = $new_tcost;
	} else {
		$payable_now = wp_travel_engine_is_trip_partially_payable( $cart_item['trip_id'] ) ? $cart_totals['total_partial'] : $cart_totals['cart_total'];
	}
	$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
	$tax_enable                = isset( $wp_travel_engine_settings['tax_enable'] ) ? $wp_travel_engine_settings['tax_enable'] : 'no';
	if ( $tax_enable == 'yes' ) {
		if ( isset( $wte_settings['tax_type_option'] ) && $wte_settings['tax_type_option'] == 'exclusive' ) {
			if ( wp_travel_engine_is_trip_partially_payable( $cart_item['trip_id'] ) ) {
				$payable_now = $cart_totals['total_partial'];
			} else {
				$payable_now = $new_totalcost;
			}
		}
	}
	?>
		<div class="wpte-bf-total-price">
			<span class="wpte-bf-total-txt"><?php esc_html_e( 'Total : ', 'wp-travel-engine' ); ?></span>
			<span class="wpte-price-wrap">
				<?php echo wte_esc_price( wte_get_formated_price_html( $payable_now, null, true ) ); ?>
			</span>
			<?php
			if ( $tax_enable == 'yes' && isset( $wte_settings['tax_type_option'] ) && 'inclusive' === $wte_settings['tax_type_option'] ) {
				printf( '<span class="wpte-inclusive-tax-label">%s</span>', __( '(Incl. tax)', 'wp-travel-engine' ) );
			}
			?>
		</div>
	</div><!-- .wpte-bf-summary-total -->
	<?php
endif;
