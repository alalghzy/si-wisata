<?php
$global_settings         = wp_travel_engine_get_settings();
$default_payment_gateway = isset( $global_settings['default_gateway'] ) && ! empty( $global_settings['default_gateway'] ) ? $global_settings['default_gateway'] : 'booking_only';

?>
<div class="wpte-bf-outer wpte-bf-checkout">
	<div class="wpte-bf-booking-steps">
		<?php
		$show_header_steps_checkout = apply_filters( 'wp_travel_engine_show_checkout_header_steps', true );

		if ( $show_header_steps_checkout ) {
			/**
			 * Action hook for header steps.
			 */
			do_action( 'wp_travel_engine_checkout_header_steps' );
		}

		$options                           = get_option( 'wp_travel_engine_settings', true );
		$wp_travel_engine_terms_conditions = isset( $options['pages']['wp_travel_engine_terms_and_conditions'] ) ? esc_attr( $options['pages']['wp_travel_engine_terms_and_conditions'] ) : '';

		if ( function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url() ) {

			$privacy_policy_lbl = sprintf( __( 'Check the box to confirm you\'ve read and agree to our <a href="%1$s" id="terms-and-conditions" target="_blank"> Terms and Conditions</a> and <a href="%2$s" id="privacy-policy" target="_blank">Privacy Policy</a>.', 'wp-travel-engine' ), esc_url( get_permalink( $wp_travel_engine_terms_conditions ) ), esc_url( get_privacy_policy_url() ) );

			$checkout_default_fields['privacy_policy_info'] = array(
				'type'              => 'checkbox',
				'options'           => array( '0' => $privacy_policy_lbl ),
				'name'              => 'wp_travel_engine_booking_setting[terms_conditions]',
				'wrapper_class'     => 'wp-travel-engine-terms',
				'id'                => 'wp_travel_engine_booking_setting[terms_conditions]',
				'default'           => '',
				'validations'       => array(
					'required' => true,
				),
				'option_attributes' => array(
					'required'                      => true,
					'data-msg'                      => __( 'Please make sure to check the privacy policy checkbox', 'wp-travel-engine' ),
					'data-parsley-required-message' => __( 'Please make sure to check the privacy policy checkbox', 'wp-travel-engine' ),
				),
				'priority'          => 70,
			);

		} elseif ( current_user_can( 'edit_theme_options' ) ) {

			$privacy_policy_lbl = sprintf( __( '%1$sPrivacy Policy page not set or not published, please check Admin Dashboard > Settings > Privacy.%2$s', 'wp-travel-engine' ), '<p style="color:red;">', '</p>' );

			$checkout_default_fields['privacy_policy_info'] = array(
				'type'     => 'text_info',
				// 'label'             => __( 'Privacy Policy', 'wp-travel-engine' ),
				'id'       => 'wp-travel-engine-privacy-info',
				'default'  => $privacy_policy_lbl,
				'priority' => 80,
			);

		}

		$checkout_fields = WTE_Default_Form_Fields::booking();
		$checkout_fields = apply_filters( 'wp_travel_engine_booking_fields_display', $checkout_fields );
		?>
		<div class="wpte-bf-step-content-wrap">
			<?php if ( ! empty( $checkout_fields ) && is_array( $checkout_fields ) ) : ?>
			<div class="wpte-bf-checkout-form">
				<?php do_action( 'wp_travel_engine_before_billing_form' ); ?>
				<div class="wpte-bf-title"><?php echo esc_html( apply_filters( 'wpte_billings_details_title', esc_html__( 'Billing Details', 'wp-travel-engine' ) ) ); ?></div>
				<form id="wp-travel-engine-new-checkout-form" method="POST" name="wp_travel_engine_new_checkout_form" action="" enctype="multipart/form-data"
					class="">
					<input type="hidden" name="action" value="wp_travel_engine_new_booking_process_action">
					<?php
							// Create booking process action nonce for security.
							wp_nonce_field( 'wp_travel_engine_new_booking_process_nonce_action', 'wp_travel_engine_new_booking_process_nonce' );
					?>
					<?php
							// Include the form class - framework.
							include_once WP_TRAVEL_ENGINE_ABSPATH . '/includes/lib/wte-form-framework/class-wte-form.php';

							// form fields initialize.
							$form_fields = new WP_Travel_Engine_Form_Field();

							$checkout_fields = array_map(
								function( $field ) {
									$field['wrapper_class'] = 'wpte-bf-field wpte-cf-' . $field['type'];
									return $field;
								},
								$checkout_fields
							);

							// $privacy_fields = array();

							// if ( isset( $checkout_fields['privacy_policy_info'] ) ) :
							// $privacy_fields['privacy_policy_info'] = $checkout_fields['privacy_policy_info'];
							// unset( $checkout_fields['privacy_policy_info'] );
							// endif;

							// Render form fields.
							$form_fields->init( $checkout_fields )->render();

					if ( wp_travel_engine_is_cart_partially_payable() ) :
						global $wte_cart;

						$tripid               = $wte_cart->get_cart_trip_ids();
						$partial_payment_data = wp_travel_engine_get_trip_partial_payment_data( $tripid[0] );

						if ( ! empty( $partial_payment_data ) ) :
							if ( 'amount' === $partial_payment_data['type'] ) :
								$trip_price_partial = wte_get_formated_price( $partial_payment_data['value'], null, true );
								elseif ( 'percentage' === $partial_payment_data['type'] ) :
									$trip_price_partial = sprintf( '%s%%', $partial_payment_data['value'] );
									endif;
								endif;
						?>
					<div class="wpte-bf-field wpte-bf-radio wpte-bf_downpayment-options">
						<label for="" class="wpte-bf-label">
						<?php
						$partial_payment_label = apply_filters( 'wte_checkout_partial_pay_heading', __( 'Down payment options', 'wp-travel-engine' ) );
						$down_payment_label    = apply_filters( 'wte_checkout_down_pay_label', __( 'Down payment(%s)', 'wp-travel-engine' ) );
						$full_payment_label    = apply_filters( 'wte_checkout_full_pay_label', __( 'Full payment(100%)', 'wp-travel-engine' ) );
						echo esc_html( $partial_payment_label );
						?>
						</label>
						<div class="wpte-bf-radio-wrap">
							<input type="radio" name="wp_travel_engine_payment_mode" value="partial" id="wp_travel_engine_payment_mode-partial" checked>
							<label for="wp_travel_engine_payment_mode-partial"><?php echo sprintf( esc_html( $down_payment_label ), $trip_price_partial ); ?></label>
						</div>
						<div class="wpte-bf-radio-wrap">
							<input type="radio" name="wp_travel_engine_payment_mode" value="full_payment" id="wp_travel_engine_payment_mode-full">
							<label for="wp_travel_engine_payment_mode-full"><?php echo esc_html( $full_payment_label ); ?></label>
						</div>
					</div>
						<?php
							endif;
							// Get active payment gateways to display publically.
							$active_payment_methods = wp_travel_engine_get_active_payment_gateways();
					if ( ! empty( $active_payment_methods ) ) :
						?>
					<div class="wpte-bf-field wpte-bf-radio wpte-bf_payment-methods">
						<label for="" class="wpte-bf-label">
						<?php esc_html_e( 'Payment Method', 'wp-travel-engine' ); ?>
						</label>
						<?php
						$first_payment_option = true;
						foreach ( $active_payment_methods as $key => $payment_method ) :
							?>
							<div class="wpte-bf-radio-wrap wpte-bf_payment-method">
								<input data-target-info="wpte__checkout-info--<?php echo esc_attr( $key ); ?>" <?php checked( $first_payment_option, true ); ?> type="radio" name="wpte_checkout_paymnet_method" value="<?php echo esc_attr( $key ); ?>" id="wpte-checkout-paymnet-method-<?php echo esc_attr( $key ); ?>">
								<label for="wpte-checkout-paymnet-method-<?php echo esc_attr( $key ); ?>">
									<?php
									if ( isset( $payment_method['icon_url'] ) && ! empty( $payment_method['icon_url'] ) ) :
										?>
									<img src="<?php echo esc_url( $payment_method['icon_url'] ); ?>" alt="<?php echo esc_attr( $payment_method['label'] ); ?>">
										<?php
											else :
												echo esc_html( $payment_method['label'] );
												endif;
											?>
								</label>
								<?php
								if ( ! empty( $payment_method['info_text'] ) ) :
									?>
									<div id="wpte__checkout-info--<?php echo esc_attr( $key ); ?>" class="wpte-checkout-payment-info<?php echo esc_attr( $first_payment_option ? '' : ' hidden' ); ?>"><?php echo esc_html( $payment_method['info_text'] ); ?></div>
									<?php
								endif;
								?>
							</div>
							<?php
							$first_payment_option = false;
						endforeach;
						?>
					</div>
						<?php
							endif;

					if ( ! empty( $checkout_default_fields ) ) :
						$checkout_default_fields = apply_filters( 'wte_booking_privacy_fields', $checkout_default_fields );

						if ( isset( $checkout_default_fields['privacy_policy_info']['type'] ) && 'text_info' === $checkout_default_fields['privacy_policy_info']['type'] ) {
							unset( $checkout_default_fields['privacy_policy_info'] );
						}
						$form_fields->init( $checkout_default_fields )->render();
						endif;

						do_action( 'wte_booking_before_submit_button' );
					?>
					<div class="wpte-bf-field wpte-bf-submit">
						<?php
						$button_label = wte_default_labels( 'checkout.submitButtonText' );
						?>
						<input type="submit"
						disabled
						data-checkout-label="<?php echo esc_attr__( 'Pay %s', 'wp-travel-engine' ); ?>"
						name="wp_travel_engine_nw_bkg_submit"
						value="<?php echo esc_attr( wte_default_labels( 'checkout.submitButtonText' ) ); ?>">
					</div>
					<?php do_action( 'wte_booking_after_submit_button' ); ?>
				</form>
				<?php do_action( 'wte_booking_after_checkout_form_close' ); ?>
			</div><!-- .wpte-bf-checkout-form -->
			<?php endif; ?>
			<div class="wpte-bf-book-summary">
				<?php
					do_action( 'wte_booking_before_minicart' );
					wte_get_template( 'checkout/mini-cart.php' );
					do_action( 'wte_booking_after_minicart' );
				?>
			</div><!-- .wpte-bf-book-summary -->
		</div><!-- .wpte-bf-step-content-wrap -->
	</div><!-- .wpte-bf-booking-steps -->
</div><!-- .wpte-bf-outer -->
