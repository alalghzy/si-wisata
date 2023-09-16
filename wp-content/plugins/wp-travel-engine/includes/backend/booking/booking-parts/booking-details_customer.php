<?php
/**
 * Customer Details parts.
 */
global $post;

$booking_details = new \stdClass();

extract( $_args );

$billing_info = $booking_details->billing_info;

$additional_fields = get_post_meta( $post->ID, 'additional_fields', ! 0 );
$additional_fields = is_array( $additional_fields ) ? $additional_fields : array();
?>
<div class="wpte-block wpte-col3">
	<div class="wpte-title-wrap">
		<h4 class="wpte-title"><?php esc_html_e( 'Billing Details', 'wp-travel-engine' ); ?></h4>
		<div class="wpte-button-wrap wpte-edit-booking-detail">
			<a href="#" class="wpte-btn-transparent wpte-btn-sm">
				<?php wptravelengine_svg_by_fa_icon( "fas fa-pencil-alt" ); ?>
				<?php esc_html_e( 'Edit', 'wp-travel-engine' ); ?>
			</a>
		</div>
	</div>
	<div class="wpte-block-content">
		<ul class="wpte-list">
		<?php
		if ( is_array( $billing_info ) ) {
			foreach ( $billing_info as $key => $value ) : // foreachbokv
				$booking_key = 'booking_' . $key;
				if ( isset( $additional_fields[ $key ] ) ) {
					$booking_key = $key;
				}
				if ( 'fname' === $key ) {
					$booking_key = 'booking_first_name';
				} elseif ( 'lname' === $key ) {
					$booking_key = 'booking_last_name';
				}
				if ( 'survey' === $key ) {
					continue;
				}
				if ( is_array( $value ) ) {
					$value = implode( ',', $value );
				}
				$data_label = wp_travel_engine_get_booking_field_label_by_name( preg_replace( '/(^booking_booking_)/', 'booking_', $booking_key ) );
				?>
				<li>
					<b><?php echo esc_html( $data_label ); ?></b>
					<span>
						<div class="wpte-field">
							<input readonly data-attrib-name="billing_info[<?php echo esc_attr( $key ); ?>]" type="text" value="<?php echo esc_attr( $value ); ?>"/>
						</div>
					</span>
				</li>
				<?php
			endforeach; // endforeachbokv
		}
		?>
		</ul>
	</div>
</div>
<?php
return;
?>
<div class="wpte-block wpte-col3">
	<div class="wpte-title-wrap">
		<h4 class="wpte-title"><?php esc_html_e( 'Customer Details', 'wp-travel-engine' ); ?></h4>
		<div class="wpte-button-wrap wpte-edit-bkng">
			<a href="#" class="wpte-btn-transparent wpte-btn-sm">
				<?php wptravelengine_svg_by_fa_icon( "fas fa-pencil-alt" ); ?>
				<?php esc_html_e( 'Edit', 'wp-travel-engine' ); ?>
			</a>
		</div>
	</div>
	<div class="wpte-block-content">
		<ul class="wpte-list">
			<?php if ( isset( $booking_metas['place_order']['booking'] ) ) : ?>
				<li>
					<b><?php esc_html_e( 'Customer ID', 'wp-travel-engine' ); ?></b>
					<?php
						$cid = get_page_by_title( $booking_metas['place_order']['booking']['email'], OBJECT, 'customer' );
					?>
					<span><a target="_blank" href="<?php echo esc_url( get_edit_post_link( $cid->ID, 'display' ) ); ?>"><?php echo esc_attr( $cid->ID ); ?></a></span>
				</li>
				<?php
			endif;
			if ( isset( $billing_options['booking'] ) && ! empty( $billing_options['booking'] ) ) :
				foreach ( $billing_options['booking'] as $key => $value ) :
					$booking_key = 'booking_' . $key;
					if ( 'fname' === $key ) {
						$booking_key = 'booking_first_name';
					} elseif ( 'lname' === $key ) {
						$booking_key = 'booking_last_name';
					}
					if ( 'survey' === $key ) {
						continue;
					}
					$data_label = wp_travel_engine_get_booking_field_label_by_name( $booking_key );
					?>
					<li>
						<b><?php echo esc_html( $data_label ); ?></b>
						<span><?php echo isset( $value ) ? esc_attr( $value ) : ''; ?></span>
					</li>
					<?php
				endforeach;
				else :
					esc_html_e( 'Customer details not found. Click "Edit" to fill details.', 'wp-travel-engine' );
				endif;
				?>
		<?php
		if ( ! empty( $additional_fields ) ) {
			foreach ( $additional_fields as $key => $value ) {
				$data_label = wp_travel_engine_get_booking_field_label_by_name( $key );
				if ( ! in_array( $key, $exclude_add_info_key_array ) ) {
					?>
				<li>
					<b><?php echo esc_html( $data_label ); ?></b>
					<span><?php echo isset( $value ) ? esc_attr( $value ) : ''; ?></span>
				</li>
					<?php
				}
			}
		}
		?>
		</ul>
	</div>
	<div style="display:none;" class="wpte-block-content-edit edit-customer-info">
		<ul class="wpte-list">
			<?php
			if ( isset( $billing_options['booking'] ) && ! empty( $billing_options['booking'] ) ) :
				foreach ( $billing_options['booking'] as $key => $value ) :
					$booking_key = 'booking_' . $key;
					if ( 'fname' === $key ) {
						$booking_key = 'booking_first_name';
					} elseif ( 'lname' === $key ) {
						$booking_key = 'booking_last_name';
					}
					if ( 'survey' === $key ) {
						continue;
					}
					$data_label = wp_travel_engine_get_booking_field_label_by_name( $booking_key );
					?>
					<li>
						<b><?php echo esc_attr( $data_label ); ?></b>
						<span>
						<?php
							// Switch type.
						switch ( $key ) {
							case 'email':
								?>
									<div class="wpte-field wpte-email">
									<input type="email" name="wp_travel_engine_booking_setting[place_order][booking][<?php echo esc_attr( $key ); ?>]" value="<?php echo isset( $value ) ? esc_attr( $value ) : ''; ?>" >
									</div>
								<?php
								break;
							case 'country':
								?>
									<div class="wpte-field wpte-select">
										<select class="wpte-enhanced-select" name="wp_travel_engine_booking_setting[place_order][booking][<?php echo esc_attr( $key ); ?>]">
											<?php
											$wte             = \wte_functions();
											$country_options = $wte->wp_travel_engine_country_list();
											foreach ( $country_options as $key => $country ) {
												$selected = selected( $value, $country, false );
												echo '<option ' . $selected . " value='" . esc_attr( $country ) ."'>" . esc_html( $country ) . "</option>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											}
											?>
										</select>
									</div>
									<?php
								break;

							default:
								?>
								<div class="wpte-field wpte-text">
									<input type="text" name="wp_travel_engine_booking_setting[place_order][booking][<?php echo esc_attr( $key ); ?>]" value="<?php echo isset( $value ) ? esc_attr( $value ) : ''; ?>" >
								</div>
								<?php
								break;
						}
						?>
						</span>
					</li>
					<?php
			endforeach;
			else :
				$checkout_fields = WTE_Default_Form_Fields::booking();
				$checkout_fields = apply_filters( 'wp_travel_engine_booking_fields_display', $checkout_fields );

				// Include the form class - framework.
				include_once WP_TRAVEL_ENGINE_ABSPATH . '/includes/lib/wte-form-framework/class-wte-form.php';

				// form fields initialize.
				$form_fields = new WP_Travel_Engine_Form_Field();

				$checkout_fields = array_map(
					function( $field ) {
						$field['wrapper_class'] = 'wpte-field wpte-floated';
						return $field;
					},
					$checkout_fields
				);

				// Render form fields.
				$form_fields->init( $checkout_fields )->render();
			endif;
			if ( ! empty( $additional_fields ) ) {
				foreach ( $additional_fields as $key => $value ) {
					$data_label = wp_travel_engine_get_booking_field_label_by_name( $key );
					if ( ! in_array( $key, $exclude_add_info_key_array ) ) {
						?>
						<li>
							<b><?php echo esc_html( $data_label ); ?></b>
							<span>
							<div class="wpte-field wpte-text">
								<input type="text" name="wp_travel_engine_booking_setting[additional_fields][<?php echo esc_attr( $key ); ?>]" value="<?php echo isset( $value ) ? esc_attr( $value ) : ''; ?>" >
							</div>
								</span>
						</li>
						<?php
					}
				}
			}
			?>
		</ul>
	</div>
</div> <!-- .wpte-block -->
<?php
