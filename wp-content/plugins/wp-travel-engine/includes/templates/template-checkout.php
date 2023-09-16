<?php
/**
 * Checkout page template.
 *
 * @package WP Travel Engine
 */
// Mini cart
global $wte_cart;
?>
<div class="place-order-form-secondary-wrapper">
	<span id="wte_upsell_holder"></span>

	<?php
		$trip_totals     = $wte_cart->get_total();
		$currency_code   = wp_travel_engine_get_currency_code();
		$currency_symbol = wp_travel_engine_get_currency_symbol( $currency_code );

	foreach ( $wte_cart->getItems() as $key => $cart_item ) :

		$trip_id            = $cart_item['trip_id'];
		$pax                = isset( $cart_item['pax'] ) ? $cart_item['pax'] : array();
		$trip_settings      = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
		$trip_price_display = wp_travel_engine_get_actual_trip_price( $trip_id );
		$trip_duration      = wp_travel_engine_get_trip_duration( $trip_id );
		$pax_adults         = isset( $cart_item['pax']['adult'] ) ? $cart_item['pax']['adult'] : 0;
		$pax_child          = isset( $cart_item['pax']['child'] ) ? $cart_item['pax']['child'] : 0;
		$total_pax          = array_sum( $pax );

		$trip_extras    = isset( $cart_item['trip_extras'] ) && ! empty( $cart_item['trip_extras'] ) ? $cart_item['trip_extras'] : array();
		$trip_discounts = ! empty( $wte_cart->get_discounts() ) ? $wte_cart->get_discounts() : array();

		?>

		<div class="wp-travel-engine-order-form-wrapper">
			<div class="wp-travel-engine-order-left-column">
				<?php echo get_the_post_thumbnail( $trip_id, 'medium', '' ); ?>
			</div>
			<div class="wp-travel-engine-order-right-column">
				<h3 class="trip-title"><?php echo esc_html( get_the_title( $trip_id ) ); ?><input type="hidden" name="trips[]" value="<?php echo esc_attr( $trip_id ); ?>"></h3>
				<ul class="trip-property">
					<?php if ( isset( $cart_item['trip_date'] ) && ! empty( $cart_item['trip_date'] ) ) : ?>
						<li>
							<span><?php esc_html_e( 'Start Date: ', 'wp-travel-engine' ); ?></span>
								<?php echo esc_html( $cart_item['trip_date'] ); ?>
							<input type="hidden" name="trip-date[]" value="<?php echo esc_attr( $cart_item['trip_date'] ); ?>">
						</li>
					<?php endif; ?>
					<li><span><?php esc_html_e( 'Trip Price:', 'wp-travel-engine' ); ?> </span>
						<?php echo esc_html( wte_get_formated_price( $trip_price_display ) ); ?>
					</li>
					<!-- Group Discounts -->
					<li>
						<span><?php esc_html_e( 'Duration:', 'wp-travel-engine' ); ?> </span><?php echo esc_html( $trip_duration ); ?>
					</li>
					<li>
						<span><?php esc_html_e( 'Number of Travellers:', 'wp-travel-engine' ); ?> </span><span class="travelers-number"><?php echo esc_html( $pax_adults ); ?></span><input type="hidden" name="travelers[]" value="<?php echo esc_attr( $pax_adults ); ?>">
					</li>
					<?php
					if ( '0' !== $pax_child && 0 !== $pax_child ) :
						?>
							<li>
								<span><?php esc_html_e( 'Number of Child Travellers:', 'wp-travel-engine' ); ?> </span><span class="travelers-number"><?php echo esc_html( $pax_child ); ?></span><input type="hidden" name="travelers[]" value="<?php echo esc_attr( $pax_child ); ?>">
							</li>
						<?php
						endif;

					if ( ! empty( $trip_extras ) ) :
						?>
						<!-- Extra Services -->
							<li class="cart-trip-total-price wte-extra-services-row"><span style="width: auto;"><?php esc_html_e( 'Extra Service(s)', 'wp-travel-engine' ); ?></span>
								<div class="extra-service">

									<?php foreach ( $trip_extras as $key => $extra ) : ?>

										<span class="extra-service-name wte-es-item-name"><?php echo esc_html( $extra['extra_service'] ); ?>: </span>
										<span class="extra-service-cost wte-es-item-cost"><?php echo esc_html( $extra['qty'] ); ?> X <?php echo esc_html( wte_get_formated_price( $extra['price'] ) ); ?></span>

									<?php endforeach; ?>

									<div class="extra-service-total-cost">
										<span class="extra-service-name"><?php esc_html_e( 'Total Extra Service(s) Cost:', 'wp-travel-engine' ); ?> </span>
										<span class="extra-service-cost"><?php echo esc_html( wte_get_formated_price( $trip_totals['trip_extras_total'] ) ); ?></span>
									</div>
								</div>
							</li>
						<!-- End Extra Services -->
						<?php
					endif;
					if ( ! empty( $trip_discounts ) ) :
						?>
						<!-- Extra Services -->
						<li class="cart-trip-total-price wte-extra-services-row"><span style="width: auto;"><?php esc_html_e( 'Discount(s)', 'wp-travel-engine' ); ?></span>
								<div class="extra-service">

								<?php foreach ( $trip_discounts as $key => $discount ) : ?>

										<span class="extra-service-name wte-es-item-name"><?php echo esc_html( $discount['name'] ); ?>: </span>
										<span class="extra-service-cost wte-es-item-cost"><?php echo 'percentage' === $discount['type'] ? esc_html( $discount['value'] ) . '%' : esc_html( wte_get_formated_price( $discount['value'] ) ); ?></span>

									<?php endforeach; ?>

									<div class="extra-service-total-cost">
										<span class="extra-service-name"><?php esc_html_e( 'Total discount(s) Cost:', 'wp-travel-engine' ); ?> </span>
										<span class="extra-service-cost"><?php echo esc_html( wte_get_formated_price( $trip_totals['discount'] ) ); ?></span>
									</div>
								</div>
							</li>
						<!-- End Extra Services -->
					<?php endif; ?>
				</ul>
			</div>
		</div>

	<?php endforeach; ?>

	<div class="secondary-inner-wrapper">
		<div class="person-price-table">
			<table id="wte-cart-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Total Traveller(s)', 'wp-travel-engine' ); ?></th>
						<th><?php esc_html_e( 'Total Price', 'wp-travel-engine' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><span class="total-trip-travelers"><?php echo esc_html( $total_pax ); ?></span></td>
						<td><?php echo esc_html( $currency_symbol ); ?><span class="total-trip-price"><?php echo esc_html( wte_price_value_format( $trip_totals['total'], false ) ); ?></span> <?php echo esc_html( $currency_code ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

</div>
<?php

require_once WP_TRAVEL_ENGINE_ABSPATH . '/includes/lib/wte-form-framework/class-wte-form.php';

$form = new WP_Travel_Engine_Form();

$options                  = get_option( 'wp_travel_engine_settings', true );
$wp_travel_engine_confirm = isset( $options['pages']['wp_travel_engine_confirmation_page'] ) ? esc_attr( $options['pages']['wp_travel_engine_confirmation_page'] ) : '';
$wp_travel_engine_confirm = get_permalink( $wp_travel_engine_confirm );

$form_options = array(
	'form_title'       => __( 'Billing Details:', 'wp-travel-engine' ),
	'action'           => esc_url( $wp_travel_engine_confirm ),
	'form_title_class' => 'relation-options-title',
	'id'               => 'wp-travel-engine-order-form',
	'name'             => 'wp-travel-engine-order-form',
	'wrapper_class'    => 'wp-travel-engine-booking-form-form-wrapper',
	'fields_wrapper'   => 'wp-travel-engine-billing-details-wrapper place-order-form-primary-wrapper',
	'submit_button'    => array(
		'name'  => 'wp-travel-engine-submit',
		'class' => 'wp-travel-engine-submit',
		'id'    => 'wp-travel-engine-booking',
		'value' => __( 'Confirm Booking', 'wp-travel-engine' ),
	),
	'nonce'            => array(
		'action' => 'wp_travel_engine_booking_nonce_action',
		'field'  => 'wp_travel_engine_booking_nonce',
	),
);

// $checkout_fields = wp_travel_engine_get_checkout_form_fields();

// Render checkout form.
// $form->init( $form_options )->form_fields( $checkout_fields )->template();
