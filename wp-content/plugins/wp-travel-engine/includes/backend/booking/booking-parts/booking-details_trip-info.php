<?php
/**
 * Trip Info
 */
global $post;

$booking_details = new \stdClass();
extract( $_args );

$order_trips = $booking_details->order_trips;


$cart_info = wp_parse_args( $booking_details->cart_info,  [
	"currency" => wp_travel_engine_get_settings( 'currency_code' ),
	"subtotal" => 0,
	"total" => 0,
	"cart_partial" => 0,
	"discounts" => [],
	"tax_amount" => 0
] );

$currency = $cart_info['currency'];
?>
<div class="wpte-block wpte-col3">
	<div class="wpte-title-wrap">
		<h4 class="wpte-title"><?php echo esc_html( _n( 'Trip Info', 'Trips Info', count( $order_trips ), 'wp-travel-engine' ) ); ?></h4>
		<div class="wpte-button-wrap wpte-edit-booking-detail">
			<a href="#" class="wpte-btn-transparent wpte-btn-sm">
				<?php wptravelengine_svg_by_fa_icon( "fas fa-pencil-alt" ); ?>
				<?php esc_html_e( 'Edit', 'wp-travel-engine' ); ?>
			</a>
		</div>
	</div>
	<div class="wpte-block-content">
		<?php
		$index              = 0;
		$pricing_categories = get_terms(
			array(
				'taxonomy'   => 'trip-packages-categories',
				'hide_empty' => false,
				'orderby'    => 'term_id',
				'fields'     => 'id=>name',
			)
		);
		if ( is_wp_error( $pricing_categories ) ) {
			$pricing_categories = array();
		}
		foreach ( $order_trips as $cart_id => $order_trip ) :
			$order_trip = (object) current( $order_trips );
			?>
			<h4><?php echo sprintf( esc_html__( 'Trip #%d', 'wp-travel-engine' ), (int) $index + 1 ); ?></h4>
			<ul class="wpte-list">
				<li>
					<b><?php esc_html_e( 'Trip Name', 'wp-travel-engine' ); ?></b>
					<span>
						<div class="wpte-field wpte-select">
							<select disabled data-attrib-name="order_trips[<?php echo esc_attr( $cart_id ); ?>][ID]" class="wpte-enhanced-select"
								id="wpte-booking-trip-id">
								<?php
								$trips_options = wp_travel_engine_get_trips_array();
								foreach ( $trips_options as $key => $trip ) {
									$selected = selected( $order_trip->ID, $key, false );
									echo '<option ' . $selected . " value='" . esc_attr( $key ) . "'>" . esc_html( $trip ) . "</option>"; // phpcs:ignore
								}
								?>
							</select>
						</div>
					</span>
				</li>
				<?php
				/**
				 * wte_booking_after_trip_name hook
				 *
				 * @hooked wte_display_trip_code_booking - Trip Code Addon
				 */
				do_action( 'wte_booking_after_trip_name', $order_trip->ID );

				$date_format = get_option( 'date_format', 'Y m d' );
				if ( isset( $order_trip->has_time ) && $order_trip->has_time ) {
					$time_format  = get_option( 'time_format', 'H:i' );
					$date_format .= " @{$time_format}";
				}
				?>
				<li>
					<b><?php esc_html_e( 'Trip Start Date', 'wp-travel-engine' ); ?></b>
					<span>
						<div class="wpte-field wpte-text">
							<input type="text" placeholder="YYYY-MM-DD" readonly data-attrib-name="order_trips[<?php echo esc_attr( $cart_id ); ?>][datetime]" data-attrib-value="<?php echo esc_attr( $order_trip->datetime ); ?>" value="<?php echo esc_attr( wp_date( $date_format, strtotime( $order_trip->datetime ) ) ); ?>"/>
						</div>
					</span>
				</li>
				<li>
					<b><?php esc_html_e( 'Travellers', 'wp-travel-engine' ); ?></b>
				</li>
				<?php
				$pricing_categories = get_terms(
					array(
						'taxonomy'   => 'trip-packages-categories',
						'hide_empty' => false,
						'orderby'    => 'term_id',
						'fields'     => 'id=>name',
					)
				);
				if ( is_wp_error( $pricing_categories ) ) {
					$pricing_categories = array();
				}
				foreach ( $order_trip->pax as $category => $number ) {
					if ( $number < 1 ) {
						continue;
					}
					$label    = isset( $pricing_categories[ $category ] ) ? $pricing_categories[ $category ] : $category;
					$pax_cost = isset( $order_trip->pax_cost ) ? +$order_trip->pax_cost[ $category ] / +$number : 0;
					$cost     = wte_get_formated_price( $pax_cost, $currency, '', ! 0 );
					$total    = $number * $pax_cost;
					$ptotal   = wte_get_formated_price( $total, $currency, '', ! 0 );
					?>
					<li>
						<p style="flex:0 1 75%;display:flex;align-items:center;font-size:14px;margin-bottom:0;justify-content:space-between;">
							<strong><?php echo esc_html( $label ); ?></strong>
							<input style="flex: 0 1 25%;" min="0" type="text"
								data-attrib-type="number"
								readonly
								data-attrib-name="order_trips[<?php echo esc_attr( $cart_id ); ?>][pax][<?php echo esc_attr( $category ); ?>]"
								data-attrib-value="<?php echo esc_attr( $number ); ?>"
								value="<?php echo esc_attr( $number ); ?>"/>
								 x
							<input style="flex: 0 1 40%;" min="0" type="text"
								data-attrib-type="number"
								data-attrib-value="<?php echo esc_attr( $pax_cost ); ?>"
								readonly
								data-attrib-name="order_trips[<?php echo esc_attr( $cart_id ); ?>][pax_cost][<?php echo esc_attr( $category ); ?>]"
								value="<?php echo esc_attr( $cost ); ?>"/>
						</p>
						<span style="flex: 0 1 25%;align-items:center;justify-content:flex-end;">
							<strong><?php echo esc_attr( $ptotal ); ?></strong>
						</span>
					</li>
					<?php
				}
				?>
			</ul>
			<?php
			if ( ! empty( $order_trip->trip_extras ) ) { // ifotte
				echo '<h5>' . esc_html__( 'Extra Services', 'wp-travel-engine' ) . '</h5>';
				echo '<ul class="wpte-list">';
				foreach ( $order_trip->trip_extras as $index => $tx ) { // forotteitx
					$cost =  $tx['price'];
					$formated_cost   = wte_get_formated_price( $tx['price'], $currency, '', ! 0 );
					$total = (int) $tx['qty'] * (float) $tx['price'];
					$label = $tx['extra_service'];
					?>
					<li>
						<p style="flex:0 1 75%;display:flex;align-items:center;font-size:14px;margin-bottom:0;justify-content:space-between">
							<strong><?php echo esc_html( $label ); ?></strong>
							<input type="hidden" name="order_trips[<?php echo esc_attr( $cart_id ); ?>][trip_extras][<?php echo esc_attr( $index ); ?>][extra_service]" value="<?php echo esc_attr( $label ); ?>" />
							<input style="flex: 0 1 25%;" min="0" type="text"
								data-attrib-type="number"
								readonly
								data-attrib-name="order_trips[<?php echo esc_attr( $cart_id ); ?>][trip_extras][<?php echo esc_attr( $index ); ?>][qty]"
								data-attrib-value="<?php echo esc_attr( $tx['qty'] ); ?>"
								value="<?php echo esc_attr( $tx['qty'] ); ?>"/>
								 x
							<input style="flex: 0 1 40%;" min="0" type="text"
								data-attrib-type="number"
								data-attrib-value="<?php echo esc_attr( $cost ); ?>"
								readonly
								data-attrib-name="order_trips[<?php echo esc_attr( $cart_id ); ?>][trip_extras][<?php echo esc_attr( $index ); ?>][price]"
								value="<?php echo esc_attr( $formated_cost ); ?>"/>
						</p>
						<span style="flex: 0 1 25%;align-items:center;justify-content:flex-end;">
							<strong><?php echo esc_attr( wte_get_formated_price( $total, $currency, '', ! 0 ) ); ?></strong>
						</span>
					</li>
					<?php
				} // endforotteitx
				echo '</ul>';
			} // endifotte
			$index++;
		endforeach;
		?>
	</div>
	<?php
	$discounts = isset( $cart_info['discounts'] ) ? $cart_info['discounts'] : array();
	if ( ! empty( $discounts ) && is_array( $discounts ) ) {
		?>
	<div class="wpte-title-wrap">
		<h4 class="wpte-title"><?php esc_html_e( 'Coupon Discounts', 'wp-travel-engine' ); ?></h4>
	</div>
	<div class="wpte-block-content">
		<ul>
			<?php
			foreach ( $discounts as $key => $discount ) {
				$amount_str = 'percentage' === $discount['type'] ? $discount['value'] . '%' : wte_get_formated_price( +$discount['value'], $currency, '', ! 0 );
				?>
			<li>
				<b><?php esc_html_e( 'Actual Cost:', 'wp-travel-engine' ); ?></b>
				<span>
					<?php echo wte_esc_price( wte_get_formated_price( $cart_info['subtotal'], $currency, '', ! 0 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			</li>
			<li>
				<b><?php esc_html_e( 'Discount Name:', 'wp-travel-engine' ); ?></b>
				<span>
					<?php echo esc_html( $discount['name'] . '( ' . $amount_str . ' )' ); ?>
				</span>
			</li>
			<li>
				<b><?php esc_html_e( 'Discount Amount:', 'wp-travel-engine' ); ?></b>
				<span>
					<?php echo wte_esc_price( wte_get_formated_price( 'percentage' === $discount['type'] ? +$cart_info['subtotal'] * ( +$discount['value'] / 100 ) : +$discount['value'], $currency, '', ! 0 ) ); // // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			</li>
				<?php
			}
			?>
		</ul>
	</div>
		<?php
	}
	?>
	<?php
	$tax = isset( $cart_info['tax_amount'] ) ? $cart_info['tax_amount'] : array();
	if ( ! empty( $tax ) ) {
		?>
	<div class="wpte-title-wrap">
		<h4 class="wpte-title"><?php esc_html_e( 'Tax Details', 'wp-travel-engine' ); ?></h4>
	</div>
	<div class="wpte-block-content">
		<ul>
			<?php
			$tax_amount = wp_travel_engine_get_tax_detail($cart_info);
				?>
			<li>
				<b><?php esc_html_e( 'Actual Cost:', 'wp-travel-engine' ); ?></b>
				<span>
					<?php echo wte_esc_price( wte_get_formated_price( $cart_info['subtotal'], $currency, '', ! 0 ) ); ?>
				</span>
			</li>
			<li>
				<b><?php echo sprintf( esc_html__( 'Tax (%s%%):', 'wp-travel-engine' ), (int) $cart_info['tax_amount'] ); ?></b>
				<span>
				<?php echo wte_get_formated_price_html( $tax_amount['tax_actual'], null, true ); ?>
				</span>
			</li>
				<b><?php esc_html_e( 'Total:', 'wp-travel-engine' ); ?></b>
				<span>
					<?php echo wte_get_formated_price_html( $tax_amount['new_totalcost'], null, true ); ?>
				</span>
			</li>
				<?php
			// }
			?>
		</ul>
	</div>
		<?php
	}
	?>
</div> <!-- .wpte-block -->
<?php
