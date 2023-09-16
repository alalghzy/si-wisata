<?php
/**
 * Trip Info
 */
wp_enqueue_script( "jquery-ui-datepicker" );
?>
<div class="wpte-block wpte-col3">
	<div class="wpte-title-wrap">
		<h4 class="wpte-title"><?php esc_html_e( 'Trip Info', 'wp-travel-engine' ); ?></h4>
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
				<b><?php esc_html_e( 'Trip Name', 'wp-travel-engine' ); ?></b>
				<span><a target="_blank" href="<?php echo esc_url( get_the_permalink( $trip_id ) ); ?>"><?php echo esc_html( $trip_name ); ?></a></span>
			</li>
			<?php
				/**
				 * wte_booking_after_trip_name hook
				 *
				 * @hooked wte_display_trip_code_booking - Trip Code Addon
				 */
				do_action( 'wte_booking_after_trip_name', $trip_id );
			?>
			<li>
				<b><?php esc_html_e( 'Trip Start Date', 'wp-travel-engine' ); ?></b>
				<span><?php echo esc_html( wte_get_formated_date( $trip_start_date ) ); ?></span>
			</li>
			<li>
				<b><?php esc_html_e( 'Travellers', 'wp-travel-engine' ); ?></b>
				<span><?php echo esc_html( $booked_travellers ); ?></span>
			</li>
			<li>
				<b><?php esc_html_e( 'Total Paid', 'wp-travel-engine' ); ?></b>
				<span><?php echo wte_esc_price( wte_get_formated_price_html( $total_paid ) ); ?></span>
			</li>
			<li>
				<b><?php esc_html_e( 'Remaining Payment', 'wp-travel-engine' ); ?></b>
				<span><?php echo wte_esc_price( wte_get_formated_price_html( $remaining_payment ) ); ?></span>
			</li>
			<li>
				<b><?php esc_html_e( 'Total Cost', 'wp-travel-engine' ); ?></b>
				<span><?php echo wte_esc_price( wte_get_formated_price_html( $total_cost ) ); ?></span>
			</li>
			<!-- <li>
				<b><?php esc_html_e( 'Trip ID', 'wp-travel-engine' ); ?></b>
				<span><?php echo esc_html( $trip_id ); ?></span>
			</li> -->
		</ul>
	</div>
	<div style="display:none;" class="wpte-block-content-edit edit-trip-info">
		<ul class="wpte-list">
			<li>
				<b><?php esc_html_e( 'Trip Name', 'wp-travel-engine' ); ?></b>
				<span>
					<div class="wpte-field wpte-select">
						<select class="wpte-enhanced-select" name="wp_travel_engine_booking_setting[place_order][tid]"
							id="wpte-booking-trip-id">
							<?php
							$trips_options = wp_travel_engine_get_trips_array();
							foreach ( $trips_options as $key => $trip ) {
								$selected = selected( $trip_id, $key, false );
								echo '<option ' . $selected . " value='" . esc_attr( $key ) . "'>" . esc_html( $trip ) . "</option>";
							}
							?>
						</select>
					</div>
				</span>
			</li>
			<li>
				<b><?php esc_html_e( 'Trip Start Date', 'wp-travel-engine' ); ?></b>
				<span>
					<div class="wpte-field wpte-text">
						<input type="text" class="wp-travel-engine-datetime hasDatepicker"
							id="wp_travel_engine_booking_setting[place_order][datetime]"
							name="wp_travel_engine_booking_setting[place_order][datetime]"
							placeholder="YYYY-MM-DD"
							value="<?php echo esc_attr( $trip_start_date ); ?>">
					</div>
				</span>
			</li>
			<li>
				<b><?php esc_html_e( 'Travellers', 'wp-travel-engine' ); ?></b>
				<span>
					<div class="wpte-field wpte-number">
						<input type="number" min="1" step="1"
							id="wp_travel_engine_booking_setting[place_order][traveler]"
							name="wp_travel_engine_booking_setting[place_order][traveler]"
							value="<?php echo esc_attr( $booked_travellers ); ?>" class="">
					</div>
				</span>
			</li>
			<li>
				<b><?php esc_html_e( 'Total Paid', 'wp-travel-engine' ); ?></b>
				<span>
					<div class="wpte-field wpte-number">
						<input type="number" min="0" step="0.01"
							name="wp_travel_engine_booking_setting[place_order][cost]"
							value="<?php echo esc_attr( $total_paid ); ?>">
					</div>
				</span>
			</li>
			<li>
				<b><?php esc_html_e( 'Remaining Payment', 'wp-travel-engine' ); ?></b>
				<span>
					<div class="wpte-field wpte-number">
						<input type="number" min="0" step="0.01" id="wp_travel_engine_booking_setting[place_order][due]"
							name="wp_travel_engine_booking_setting[place_order][due]"
							value="<?php echo esc_attr( $remaining_payment ); ?>" class="">
					</div>
				</span>
			</li>
			<li>
				<b><?php esc_html_e( 'Total Cost', 'wp-travel-engine' ); ?></b>
				<span>
					<div class="wpte-field wpte-number">
						<input type="number" min="0" step="0.01"
							id="wp_travel_engine_booking_setting[place_order][total]"
							name="wp_travel_engine_booking_setting[place_order][total]"
							value="<?php echo esc_attr( $total_cost ); ?>" class="">
					</div>
				</span>
			</li>
		</ul>
	</div>
	<?php
	if ( ! empty( $booking_discounts ) && is_array( $booking_discounts ) ) {
		?>
	<div class="wpte-title-wrap">
		<h4 class="wpte-title"><?php esc_html_e( 'Coupon Discounts', 'wp-travel-engine' ); ?></h4>
	</div>
	<div class="wpte-block-content">
		<ul>
			<?php
			foreach ( $booking_discounts as $key => $discount ) {
				$amount_str = 'percentage' === $discount['type'] ? $discount['value'] . '%' : wte_get_formated_price_html( $discount['value'] );
				?>
			<li>
				<b><?php esc_html_e( 'Acutal Cost:', 'wp-travel-engine' ); ?></b>
				<span>
					<?php echo wte_esc_price( wte_get_formated_price_html( $discount['actual_total'] ) ); ?>
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
					<?php echo (int) $discount['amount']; ?>
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
</div> <!-- .wpte-block -->
<?php
