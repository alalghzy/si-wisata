<?php
/**
 * Coupon Form Template.
 *
 * @since __addonmigration__
 */
global $wte_cart;
$cart_discounts            = $wte_cart->get_discounts();
$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
$code                      = isset( $wp_travel_engine_settings['currency_code'] ) ? $wp_travel_engine_settings['currency_code'] : 'USD';
$currency                  = wp_travel_engine_get_currency_code_or_symbol();
wp_enqueue_script( 'wp-util' );
?>
<div class="wte-coupon-whole-wrap" id="wte-checkout-coupon">
	<div id="wp-travel-engine-coupon-block" <?php echo ( empty( $cart_discounts ) || sizeof( $cart_discounts ) === 0 ) ? 'style="display:block;"' : 'style="display:none;"'; ?>>
		<button class="coupon-close-button wte-coupon-remove-trigger">Close</button>
		<h5 class="wte-apply-coupon-title">
			<p><?php echo esc_html__( 'Have a Coupon code?', 'wp-travel-engine' ); ?></p>
		</h5>
		<p><?php echo esc_html__( 'Add your coupon code below to get your discount.', 'wp-travel-engine' ); ?> <!--<a href="#" class="wte-coupon-show-trigger"> < ?php echo esc_html__( 'here', 'wp-travel-engine-coupons' ); ?></a>--></p>
		<div class="coupon" id="wte-coupons-holder-wrap">
			<input type="text" name="wp_travel_engine_coupon_code_input" class="input-text" id="coupon_code" value="" placeholder="<?php echo esc_attr__( 'Coupon Code', 'wp-travel-engine' ); ?>">
			<input type="submit" class="button wp-travel-engine-coupons-apply-btn" name="apply_coupon" value="<?php echo esc_attr__( 'Apply', 'wp-travel-engine' ); ?>">
			<?php
			$trip_array = array();
			if ( is_object( $wte_cart ) && ! empty( $wte_cart ) ) {
				$cart_items = $wte_cart->getItems();
				foreach ( $cart_items as $key => $value ) {
					$trip_array[] = +$value['trip_id'];
				}
			}
			?>
			<input type="hidden" name="wte_couponse_trip_id" value="<?php echo isset( $trip_array ) && ! empty( $trip_array ) ? wp_json_encode( $trip_array ) : ''; ?>">
			<div id="price-loader-coupon" style="display: none">
				<div class="table">
					<div class="table-row">
						<div class="table-cell">
							<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="coupon-response-holder-wrap">
		<div id="coupon-response-holder">
		<?php
		if ( ! empty( $cart_discounts ) || sizeof( $cart_discounts ) !== 0 ) {
			foreach ( $cart_discounts as $discount_key => $discount_item ) {
				?>
				<span class="coupon-response-success"><?php echo sprintf( __( 'Coupon "%1$s" applied successfully.', 'wp-travel-engine' ), $discount_item['name'] ); ?></span>
				<?php
			}
		}
		?>
		</div>
		<a id="wte-coupon-response-reset-coupon" class="wte-coupon-response-reset-coupon" <?php echo ( isset( $cart_discounts ) && ! empty( $cart_discounts ) || sizeof( $cart_discounts ) !== 0 ) ? 'style="display:block;"' : 'style="display:none;"'; ?>>
			<?php echo esc_attr__( 'Reset Coupon', 'wp-travel-engine' ); ?>
		</a>
	</div>
	<?php wp_nonce_field( 'wte_session_cart_apply_coupon', 'wte_apply_coupon_nonce' ); ?>
	<?php wp_nonce_field( 'wte_session_cart_reset_coupon', 'wte_reset_coupon_nonce' ); ?>
</div>

<!-- JS Templating coupon response -->
<script type="text/html" id="tmpl-wte-coupon-response">
	<span class="coupon-response-{{data.type}}">{{data.message}}</span>
</script>

<script type="text/html" id="tmpl-wte-coupon-response-updated-price">
	<tr class="wte-coupons-discount-calculation-tr">
		<td class="wte-coupons-discount-calculation-td">
			<span class="wpte-bf-total-txt">
				<?php _e( 'Coupon Discount :', 'wp-travel-engine' ); ?> {{data.coupon_code}}
				<#
					if ( data.dis_type == 'percentage' ) {
				#>
						- ({{data.discount_percent}}{{{data.unit}}})
				<#
					} else {
				#>
						- (<?php echo esc_attr( $currency ); ?> {{data.discount_amt}})
				<#
					}
				#>
			</span>
		</td>
		<td class="wte-coupons-discount-calculation-td">
			<span class="wpte-currency-code"><?php echo esc_attr( $currency ); ?></span>
			<span class="wpte-price">{{data.discount_amt}}</span>
		</td>
	</tr>
	<tr class="wte-coupons-discount-calculation-tr">
			<td class="wte-coupons-discount-calculation-td">
			<span class="wpte-bf-total-txt">
				<?php _e( 'Total after Discount :', 'wp-travel-engine' ); ?>
			</span>
		</td>
		<td>
			<span class="wpte-currency-code"><?php echo esc_attr( $currency ); ?></span>
			<span class="wpte-price">{{data.new_cost}}</span>
		</td>
	</tr>
</script>
