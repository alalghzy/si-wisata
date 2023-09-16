<?php
/**
 * Multiple Pricing option template.
 *
 * @link       https://wptravelengine.com/
 * @since      3.0.2
 */
?>
<div class="multiple-pricing-repeater" data-id="<?php echo esc_attr( $index ); ?>">
	<!-- Delete icon -->
	<span class="dashicons dashicons-no"></span>
	<!-- ./ Delete icon -->

	<!-- Multiple Pricing name -->
	<div class="repeater">
		<label for="<?php echo esc_attr( $name ); ?>[extra_service][<?php echo esc_attr( $index ); ?>]">
			<?php esc_html_e( 'Service Name', 'wp-travel-engine' ); ?>
		</label>
		<input type="text" id="<?php echo esc_attr( $name ); ?>[extra_service][<?php echo esc_attr( $index ); ?>]"
			name="<?php echo esc_attr( $name ); ?>[extra_service][<?php echo esc_attr( $index ); ?>]"
			value="<?php echo esc_attr( $extra_service ); ?>"
			placeholder="<?php echo esc_html_e( 'Service name', 'wp-travel-engine' ); ?>" />
	</div>
	<!-- ./ Multiple Pricing title -->

	<!-- Multiple Pricing cost -->
	<div class="repeater">
		<label for="<?php echo esc_attr( $name ); ?>[extra_service_cost][<?php echo esc_attr( $index ); ?>]">
			<?php esc_html_e( 'Service Cost', 'wp-travel-engine' ); ?>
		</label>
		<div class="number-holder">
			<span class="currency-code">
				<?php echo esc_html( $wte_option_settings['currency_code'] ); ?>
			</span>
			<input type="number" name="<?php echo esc_attr( $name ); ?>[extra_service_cost][<?php echo esc_attr( $index ); ?>]"
				id="<?php echo esc_attr( $name ); ?>[extra_service_cost][<?php echo esc_attr( $index ); ?>]"
				value="<?php echo esc_attr( $extra_service_cost ); ?>"
				placeholder="<?php esc_html_e( 'Price per person', 'wp-travel-engine' ); ?>" />
		</div>
	</div>
	<!-- ./ Multiple Pricing cost -->

	<!-- Multiple Pricing description -->
	<div class="repeater">
		<label for="<?php echo esc_attr( $name ); ?>[extra_service_desc][<?php echo esc_attr( $index ); ?>]">
			<?php esc_html_e( 'Service Description', 'wp-travel-engine' ); ?>
			<span class="tooltip"
				title="<?php esc_html_e( 'Select Service Unit if the service cost is Per Unit or Per Traveller. This will be displayed in the booking form in the front-end.', 'wp-travel-engine' ); ?>">
				<i class="fas fa-question-circle"></i>
			</span>
		</label>
		<textarea name="<?php echo esc_attr( $name ); ?>[extra_service_desc][<?php echo esc_attr( $index ); ?>]"
			id="<?php echo esc_attr( $name ); ?>[extra_service_desc][<?php echo esc_attr( $index ); ?>]"
			placeholder="<?php esc_html_e( 'Service Description', 'wp-travel-engine' ); ?>"><?php echo esc_attr( $extra_service_desc ); ?></textarea>
	</div>
	<!-- ./ Multiple Pricing description -->

	<!-- Multiple Pricing unit -->
	<div class="repeater">
		<label for="">
			<?php esc_html_e( 'Service Unit', 'wp-travel-engine' ); ?>
			<span class="tooltip"
				title="<?php esc_html_e( 'Write short service description about the service. This will be displayed in the booking form in the front-end.', 'wp-travel-engine' ); ?>">
				<i class="fas fa-question-circle"></i>
			</span>
		</label>
		<div class="select-holder">
			<select name=<?php echo esc_attr( $name ); ?>[extra_service_unit][<?php echo esc_attr( $index ); ?>]>
				<option value="unit" <?php selected( 'unit', $extra_service_unit ); ?>>
					<?php esc_html_e( 'Per Unit', 'wp-travel-engine' ); ?>
				</option>
				<option value="traveler" <?php selected( 'traveler', $extra_service_unit ); ?>>
					<?php esc_html_e( 'Per Traveller', 'wp-travel-engine' ); ?>
				</option>
			</select>
		</div>
	</div>
	<!-- ./ Multiple Pricing unit -->
</div>
<?php
