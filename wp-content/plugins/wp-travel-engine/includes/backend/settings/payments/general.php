<?php
/**
 * Payment General Tab
 */
$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
$payment_debug             = isset( $wp_travel_engine_settings['payment_debug'] ) ? $wp_travel_engine_settings['payment_debug'] : 'no';
?>
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" for="wp_travel_engine_settings[payment_debug]"><?php esc_html_e( 'Debug Mode', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" value="no" name="wp_travel_engine_settings[payment_debug]">
		<input type="checkbox" id="wp_travel_engine_settings[payment_debug]" name="wp_travel_engine_settings[payment_debug]" value="yes" <?php checked( $payment_debug, 'yes' ); ?>>
		<label for="wp_travel_engine_settings[payment_debug]"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Check this option to enable debug mode for all active payment gateways. Enabling this option will use sandbox accounts( if available ) on the checkout page.', 'wp-travel-engine' ); ?></span>
</div>

<?php
$payment_gateways_sorted = wp_travel_engine_get_sorted_payment_gateways();
if ( ! empty( $payment_gateways_sorted ) ) :
	$default_gateway = isset( $wp_travel_engine_settings['default_gateway'] ) ? $wp_travel_engine_settings['default_gateway'] : '';
	?>
	<div class="wpte-field wpte-select wpte-floated">
		<label class="wpte-field-label"><?php esc_html_e( 'Default Gateway', 'wp-travel-engine' ); ?></label>
		<select name="wp_travel_engine_settings[default_gateway]">
			<?php
			foreach ( $payment_gateways_sorted as $key => $payment_gateway ) :
				?>
			<option <?php selected( $default_gateway, $key ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $payment_gateway['label'] ); ?></option>
				<?php
					endforeach;
			?>
		</select>
		<span class="wpte-tooltip"><?php esc_html_e( 'Choose the default payment gateway. The chosen gateway will be selected by default on the checkout page.', 'wp-travel-engine' ); ?></span>
	</div>

	<div class="wpte-field wpte-multi-checkbox">
		<div class="wpte-title-wrap">
			<h3 class="wpte-title"><?php esc_html_e( 'Payment Gateways', 'wp-travel-engine' ); ?></h3>
		</div>

		<?php
		foreach ( $payment_gateways_sorted as $key => $payment_gateway ) :
			?>
		<div class="wpte-checkbox">
			<div class="wpte-checkbox-wrap">
				<input type="checkbox"
					id="wp_travel_engine_settings[<?php echo esc_attr( $key ); ?>]"
					class="<?php echo esc_attr( $payment_gateway['input_class'] ); ?>"
					name="wp_travel_engine_settings[<?php echo esc_attr( $key ); ?>]"
					value="1"
					<?php
					if ( isset( $wp_travel_engine_settings[ esc_attr( $key ) ] ) && $wp_travel_engine_settings[ esc_attr( $key ) ] != '' ) {
						echo 'checked';
					}
					?>
						>
				<label for="wp_travel_engine_settings[<?php echo esc_attr( $key ); ?>]"></label>
			</div>
			<label class="wpte-field-label" for="wp_travel_engine_settings[<?php echo esc_attr( $key ); ?>]"><?php echo esc_html( $payment_gateway['label'] ); ?></label>
		</div>
			<?php
				endforeach;
		?>
		<div class="wpte-tooltip"><?php esc_html_e( 'Check the payment gateways to enable on the checkout page. You can configure each payment gateway settings by switching to the Payment gateway settings tab.', 'wp-travel-engine' ); ?></div>
		<div class="wpte-info-block">
			<b><?php _e( 'Note:', 'wp-travel-engine' ); ?></b>
			<p>
			<?php
			echo wp_kses(
				sprintf( __( 'Need more payment gateways to receive payment from customers? We support several payment gateways to empower travel agencies to sell travel packages. %s', 'wp-travel-engine' ), sprintf( '<a target="_blank" href="https://wptravelengine.com/plugins/category/payment-gateways/?utm_source=customer_site&utm_id=customer_site" rel="nofollow">%s</a>', __( 'See all the supported payment gateways here.', 'wp-travel-engine' ) ) ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
						'rel'    => array(),
					),
				)
			);
			?>
				</p>
		</div>
	</div>
	<?php
endif;
