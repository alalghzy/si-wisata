<?php
global $post;
$wp_travel_engine_postmeta_settings = get_post_meta( $post->ID, 'wp_travel_engine_booking_setting', true );

/**
 * Get payment options.
 *
 * @return string
 */
function payment_status_options() {
	 $options = array(
		 'publish'   => 'Publish',
		 'refunded'  => 'Refunded',
		 'failed'    => 'Failed',
		 'abandoned' => 'Abandoned',
		 'revoked'   => 'Revoked',
		 'pending'   => 'Pending',
	 );
	 $options = apply_filters( 'wp_travel_engine_payment_status_options', $options );
	 return $options;
}
?>
<div class="trip-info-meta">
	<label class="wpte-field-label" for="wp_travel_engine_booking_setting[place_order][payment][payment_status]"><?php esc_html_e( 'Payment Status: ', 'wp-travel-engine' ); ?></label>
	<div class="wpte-field wpte-select">
		<select id="wp_travel_engine_booking_setting[place_order][payment][payment_status]" name="wp_travel_engine_booking_setting[place_order][payment][payment_status]" data-placeholder="<?php esc_attr_e( 'Choose a field type&hellip;', 'wp-travel-engine' ); ?>" class="wc-enhanced-select" >
		<?php
		$options = payment_status_options();
		$value   = isset( $wp_travel_engine_postmeta_settings['place_order']['payment']['payment_status'] ) ? $wp_travel_engine_postmeta_settings['place_order']['payment']['payment_status'] : '';
		foreach ( $options as $key => $val ) {
			echo '<option value="' . ( ! empty( $val ) ? esc_attr( $val ) : 'Please select' ) . '"' . selected( $value, $val, false ) . '>' . esc_html( $val ) . '</option>';
		}
		?>
		</select><br />
	</div>
	<label class="wpte-field-label" for="wp_travel_engine_booking_setting[place_order][payment][payer_status]"><?php esc_html_e( 'Payer Status: ', 'wp-travel-engine' ); ?></label>
	<div class="wpte-field wpte-text">
		<input type="text" id="wp_travel_engine_booking_setting[place_order][payment][payer_status]" name="wp_travel_engine_booking_setting[place_order][payment][payer_status]" value="<?php echo isset( $wp_travel_engine_postmeta_settings['place_order']['payment']['payer_status'] ) ? esc_attr( $wp_travel_engine_postmeta_settings['place_order']['payment']['payer_status'] ) : ''; ?>"><br/>
	</div>

	<label class="wpte-field-label" for="wp_travel_engine_booking_setting[place_order][payment][payment_type]"><?php esc_html_e( 'Payment Type: ', 'wp-travel-engine' ); ?></label>
	<div class="wpte-field wpte-text">
		<input type="text" id="wp_travel_engine_booking_setting[place_order][payment][payment_type]" name="wp_travel_engine_booking_setting[place_order][payment][payment_type]" value="<?php echo isset( $wp_travel_engine_postmeta_settings['place_order']['payment']['payment_type'] ) ? esc_attr( $wp_travel_engine_postmeta_settings['place_order']['payment']['payment_type'] ) : ''; ?>"><br/>
	</div>

	<label class="wpte-field-label" for="wp_travel_engine_booking_setting[place_order][payment][txn_id]"><?php esc_html_e( 'Transaction ID: ', 'wp-travel-engine' ); ?></label>
	<div class="wpte-field wpte-text">
		<input type="text" id="wp_travel_engine_booking_setting[place_order][payment][txn_id]" name="wp_travel_engine_booking_setting[place_order][payment][txn_id]" value="<?php echo isset( $wp_travel_engine_postmeta_settings['place_order']['payment']['txn_id'] ) ? esc_attr( $wp_travel_engine_postmeta_settings['place_order']['payment']['txn_id'] ) : ''; ?>"><br/>
	</div>
</div>
