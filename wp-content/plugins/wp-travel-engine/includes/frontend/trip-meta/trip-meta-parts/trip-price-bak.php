<?php
	global $post;
	$wp_travel_engine_setting                = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
	$wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', true );

if ( isset( $wp_travel_engine_setting_option_setting['booking'] ) ) {
	return;
}

	$obj  = \wte_functions();
	$cost = isset( $wp_travel_engine_setting['trip_price'] ) ? $wp_travel_engine_setting['trip_price'] : '';
if ( isset( $wp_travel_engine_setting['trip_prev_price'] ) && $wp_travel_engine_setting['trip_prev_price'] != '' || isset( $wp_travel_engine_setting['trip_price'] ) && $wp_travel_engine_setting['trip_price'] != '' ) { ?>
	<div class="secondary-trip-info trip-price">
		<div class="price-holder">
		<?php do_action( 'wp_travel_engine_before_price_info' ); ?>
			<div class="top-price-holder">
				<span class="price-from">
				<?php
				$price_info = __( 'Price From', 'wp-travel-engine' );
				echo esc_html( apply_filters( 'wp_travel_engine_price_info', $price_info ) );
				?>
				</span>
				<strong class="prev-price">
					<?php
					$code          = 'USD';
					$code          = $obj->trip_currency_code( $post );
					$currency      = $obj->wp_travel_engine_currencies_symbol( $code );
					$prev_cost     = isset( $wp_travel_engine_setting['trip_prev_price'] ) ? $wp_travel_engine_setting['trip_prev_price'] : '';
					$person_format = isset( $wp_travel_engine_setting_option_setting['person_format'] ) ? $wp_travel_engine_setting_option_setting['person_format'] : '/person';
					if ( $cost != '' && isset( $wp_travel_engine_setting['sale'] ) ) {
						if ( $prev_cost != '' ) {
							if ( class_exists( 'Wte_Trip_Currency_Converter_Init' ) ) {
								$prev_cost = $obj->convert_trip_price( $post, $prev_cost );
							}
							echo "<strike style='color:red'>";
							echo "<span class='currency-code'>" . esc_attr( $code ) . '</span>&nbsp;<span class="currency">' . esc_attr( $currency ) . '&nbsp;</span>';
							echo '<span class="trip-cost">' . wte_esc_price( $obj->wp_travel_engine_price_format( $prev_cost )) . '</span>';
							echo '</strike>';
						}
						?>
						<strong class="price">
							<?php
								$actual_price = $cost;
							if ( class_exists( 'Wte_Trip_Currency_Converter_Init' ) ) {
								$cost = $obj->convert_trip_price( $post, $cost );
							}
								echo '<span class="currency-code">' . esc_attr( $code ) . '</span>&nbsp;';
								echo '<span class="currency">' . esc_attr( $currency ) . '</span>&nbsp;';
								echo '<strong class="trip-cost-holder">' . esc_attr( $obj->wp_travel_engine_price_format( $cost ) ) . '</strong>';
								echo '<span class="per-person">' . $person_format . '</span>';
							?>
						</strong>
						<?php
					} else {
						?>
						<strong class="price">
							<?php
								$actual_price = $wp_travel_engine_setting['trip_prev_price'];
							if ( class_exists( 'Wte_Trip_Currency_Converter_Init' ) ) {
								$prev_cost = $obj->convert_trip_price( $post, $actual_price );
							}
								echo '<span class="currency-code">' . esc_attr( $code ) . '</span>&nbsp;';
								echo '<span class="currency">' . esc_attr( $currency ) . '</span>&nbsp;';
								echo '<strong class="trip-cost-holder">' . ( wte_esc_price( $obj->wp_travel_engine_price_format( $prev_cost )) ) . '</strong>';
								echo '<span class="per-person">' . $person_format . '</span>';
							?>
						</strong>
						<?php
					}
					?>
				</strong>
				<?php do_action( 'wp_travel_engine_group_discount_info' ); ?>
			</div>
			<?php do_action( 'wp_travel_engine_after_price_info' ); ?>
			<?php
			$options                     = get_option( 'wp_travel_engine_settings', true );
			$wp_travel_engine_placeorder = isset( $options['pages']['wp_travel_engine_place_order'] ) ? esc_attr( $options['pages']['wp_travel_engine_place_order'] ) : '';

			do_action( 'wp_travel_engine_before_price_form' );
			?>
			<form id="booking-frm-<?php echo esc_attr( $post->ID ); ?>" autocomplete='off' method="POST" action="<?php echo esc_url( get_permalink( $wp_travel_engine_placeorder ) ); ?>">
				<?php do_action( 'wp_travel_engine_group_discount_guide' ); ?>
				<div class="date-time-wrapper">
					<input type="text" min="1" class="wp-travel-engine-price-datetime" id="wp-travel-engine-trip-datetime" name="trip-date" placeholder="<?php esc_html_e( 'Pick a date', 'wp-travel-engine' ); ?>">
				</div>
				<div class="travelers-number-input">
					<label for="travelers-no">
					<?php
					$no_of_travelers = __( 'Number of Adult Travellers: ', 'wp-travel-engine' );
					echo esc_html( apply_filters( 'wp_travel_engine_no_of_travelers_text', $no_of_travelers ) );
					?>
					</label>
					<input type = "number" min = "1" name = "travelers" id = "travelers-no" class = "travelers-no" value = "1" required>
					<?php
					if ( class_exists( 'Wp_Travel_Engine_Group_Discount' ) && isset( $wp_travel_engine_setting['child-group']['discount'] ) && isset( $wp_travel_engine_setting_option_setting['group']['discount'] ) ) {
						do_action( 'wpte_child_field', $post );
					}
					if ( class_exists( 'Extra_Services_Wp_Travel_Engine' ) ) {
						do_action( 'calculate_extra_services_cost', $post );
					}
					?>
				</div>
				<?php
				if ( class_exists( 'Wp_Travel_Engine_Group_Discount' ) && isset( $wp_travel_engine_setting['group']['discount'] ) && isset( $wp_travel_engine_setting_option_setting['group']['discount'] ) ) {
					if ( class_exists( 'Wte_Trip_Currency_Converter_Init' ) ) {
						$actual_price = $obj->convert_trip_price( $post, $actual_price );
					}
					echo '<div class="discount-price-per-traveler"><strong>' . __( 'Cost Per Adult Traveller: ', 'wp-travel-engine' ) . '</strong><div class="per-adult-amount"><span class="currency">' . $currency . ' </span><span class="discount-price-traveler">' . $actual_price . '</span>&nbsp;<span class="currency-code">' . $code . '</span></div></div>';
					if ( isset( $wp_travel_engine_setting['child-group']['discount'] ) && isset( $wp_travel_engine_setting['group']['child'] ) && $wp_travel_engine_setting['group']['child'] != '' ) {
						echo '<div class="discount-price-per-child-traveler"><strong>' . __( 'Cost Per Child Traveller: ', 'wp-travel-engine' ) . '</strong><div class="per-adult-amount"><span class="currency">' . $currency . ' </span><span class="discount-price-child-traveler">' . $actual_price . '</span>&nbsp;<span class="currency-code">' . $code . '</span></div></div>';
					}
				}
				?>
				<input type="hidden" min="1" id="travelers" name="trip-id" value="<?php echo $post->ID; ?>">
				<?php
				$nonce = wp_create_nonce( 'wp_travel_engine_booking_nonce' );
				?>
				<input type="hidden" id="nonce" name="nonce" value="<?php echo $nonce; ?>">
				<?php
				if ( $cost != '' && isset( $wp_travel_engine_setting['sale'] ) ) {
					?>
					<span class="hidden-price"><?php echo esc_attr( $cost ); ?></span>
					<div class="total-amt"><b><?php esc_html_e( 'Total', 'wp-travel-engine' ); ?></b>
						<?php echo '<span class="currency">' . esc_attr( $currency ) . '</span>' . ' '; ?><span class="total"><?php echo wte_esc_price( $obj->wp_travel_engine_price_format( $cost )); ?></span><?php echo ' ' . '<span class="currency-code">' . esc_attr( $code ) . '</span>'; ?>
					</div>
					<?php
				} else {
					?>
					<span class="hidden-price"><?php echo esc_attr( $prev_cost ); ?></span>
					<div class="total-amt"><b><?php esc_html_e( 'Total', 'wp-travel-engine' ); ?></b>
						<?php echo '<span class="currency">' . esc_attr( $currency ) . '</span>' . ' '; ?><span class="total"><?php echo wte_esc_price( $obj->wp_travel_engine_price_format( $prev_cost )); ?></span><?php echo ' ' . '<span class="currency-code">' . esc_attr( $code ) . '</span>'; ?>
					</div>
				<?php } ?>
				<?php
				if ( $cost != '' && isset( $wp_travel_engine_setting['sale'] ) ) {
					?>
					<input type="hidden" id="trip-cost" name="trip-cost" value="<?php echo esc_attr( $cost ); ?>">
					<?php
				} else {
					?>
					<input type="hidden" id="trip-cost" name="trip-cost" value="<?php echo esc_attr( $prev_cost ); ?>">
				<?php } ?>
				<input type="hidden" name="fdd-id" class="fdd-id" value="">
				<div class="check-availability-holder">
					<?php

					/**
					 * Hook - wp_travel_engine_proceed_booking_btn
					 * Hooked - wp_travel_engine_default_booking_proceed - 10;
					 */
					do_action( 'wp_travel_engine_proceed_booking_btn' );

					?>
				</div>
			</form>
			<?php
			do_action( 'wp_travel_engine_after_price_form' );
			?>
		<div id="price-loading"><div id="price-loading-wrap"><div id="price-loading-outer"><div id="price-loading-inner"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></div></div></div></div>
		</div>
	</div>
	<?php
}
	do_action( 'wte_quick_enquiry' );
	do_action( 'wte_up_sell' );
