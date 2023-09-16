<?php
/**
 * Misc Tab
 */
$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
$feat_img                  = isset( $wp_travel_engine_settings['feat_img'] ) ? esc_attr( $wp_travel_engine_settings['feat_img'] ) : '0';
?>
<div class="wpte-form-block-wrap">
	<div class="wpte-form-block">
		<div class="wpte-form-content">
			<div class="wpte-field wpte-text wpte-floated">
				<label for="wp_travel_engine_settings[book_btn_txt]" class="wpte-field-label"><?php esc_html_e( 'Book Now Button Text', 'wp-travel-engine' ); ?></label>
				<input type="text" id="wp_travel_engine_settings[book_btn_txt]" name="wp_travel_engine_settings[book_btn_txt]" value="<?php echo esc_attr( isset( $wp_travel_engine_settings['book_btn_txt'] ) ? esc_attr( $wp_travel_engine_settings['book_btn_txt'] ) : wte_default_labels( 'checkout.submitButtonText' ) ); ?>" placeholder="<?php esc_attr_e( 'Book now button label', 'wp-travel-engine' ); ?>">
				<span class="wpte-tooltip"> <?php esc_html_e( 'Book Now button text', 'wp-travel-engine' ); ?></span>
			</div>

			<div class="wpte-field wpte-text wpte-floated">
				<label for="wp_travel_engine_settings[query_subject]" class="wpte-field-label"><?php esc_html_e( 'Email Subject For Enquiry', 'wp-travel-engine' ); ?></label>
				<input type="text" id="wp_travel_engine_settings[query_subject]" name="wp_travel_engine_settings[query_subject]" value="<?php echo esc_attr( isset( $wp_travel_engine_settings['query_subject'] ) ? esc_attr( $wp_travel_engine_settings['query_subject'] ) : __( 'Enquiry received', 'wp-travel-engine' ) ); ?>">
				<span class="wpte-tooltip"> <?php esc_html_e( 'Email subject for admin if a query is received. Supported Email tags - {enquirer_name}, {enquirer_email}', 'wp-travel-engine' ); ?></span>
			</div>

			<div class="wpte-field wpte-text wpte-floated">
				<label for="wp_travel_engine_settings[person_format]" class="wpte-field-label"><?php esc_html_e( 'Per Person Format', 'wp-travel-engine' ); ?></label>
				<input type="text" id="wp_travel_engine_settings[person_format]" name="wp_travel_engine_settings[person_format]" value="<?php echo esc_attr( isset( $wp_travel_engine_settings['person_format'] ) ? esc_attr( $wp_travel_engine_settings['person_format'] ) : __( '/person', 'wp-travel-engine' ) ); ?>">
				<span class="wpte-tooltip"><?php esc_html_e( "Per Person format in the trip booking form. Default is '/person'", 'wp-travel-engine' ); ?></span>
			</div>

			<div class="wpte-field wpte-textarea wpte-floated">
				<label for="wp_travel_engine_settings[confirmation_msg]" class="wpte-field-label"><?php esc_html_e( 'Booking Confirmation Message', 'wp-travel-engine' ); ?></label>
				<textarea rows="4" cols="30" id="wp_travel_engine_settings[confirmation_msg]" name="wp_travel_engine_settings[confirmation_msg]"><?php echo esc_html( isset( $wp_travel_engine_settings['confirmation_msg'] ) ? esc_attr( $wp_travel_engine_settings['confirmation_msg'] ) : __( 'Thank you for booking the trip. Please check your email for confirmation. Below is your booking detail:', 'wp-travel-engine' ) ); ?></textarea>
			</div>

			<div class="wpte-field wpte-textarea wpte-floated">
				<label for="wp_travel_engine_settings[gdpr_msg]" class="wpte-field-label"><?php esc_html_e( 'GDPR Message', 'wp-travel-engine' ); ?></label>
				<textarea rows="4" cols="30" id="wp_travel_engine_settings[gdpr_msg]" name="wp_travel_engine_settings[gdpr_msg]"><?php echo isset( $wp_travel_engine_settings['gdpr_msg'] ) ? esc_attr( $wp_travel_engine_settings['gdpr_msg'] ) : 'By contacting us, you agree to our '; ?></textarea>
			</div>
		</div>
	</div>
</div>
