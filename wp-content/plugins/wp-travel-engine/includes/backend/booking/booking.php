<?php
wp_enqueue_script( "jquery-ui-datepicker" );

global $post;
$wp_travel_engine_option_settings  = get_option( 'wp_travel_engine_settings', true );
$wp_travel_engine_settings         = get_post_meta( $post->ID, 'wp_travel_engine_placeorder_setting', true );
$wp_travel_engine_booking_settings = get_post_meta( $post->ID, 'wp_travel_engine_booking_setting', true );

if ( ! isset( $wp_travel_engine_settings['place_order'] ) || $wp_travel_engine_settings['place_order'] == '' ) {
	esc_html_e( 'Travellers info not available.', 'wp-travel-engine' );
	return;
}
	$pno              = $wp_travel_engine_booking_settings['place_order']['traveler'];
	$billing_options  = $wp_travel_engine_settings['place_order'];
	$personal_options = $wp_travel_engine_settings['place_order'];
	$relation_options = $wp_travel_engine_settings['place_order'];
?>
	<div class="place-order-form-primary-wrapper">
		<?php
		for ( $i = 1; $i <= $pno; $i++ ) {
			?>
			<div class='wp-travel-engine-personal-details-wrapper'>
				<div class='personal-options-title'>
					<?php
					esc_html_e( 'Personal details for Traveller: ', 'wp-travel-engine' );
					echo (int) $i;
					?>
				</div>
				<div class='wp-travel-engine-personal-details-inner-wrapper'>
					<?php
					foreach ( $personal_options['travelers'] as $key => $value ) {

						if ( ! isset( $value[ $i ] ) ) :
							continue;
						endif;

						?>
						<div class='wp-travel-engine-personal-details'>
							<?php
							switch ( $key ) {
								case 'title':
									?>
								<label for="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>]"><?php esc_html_e( 'Title', 'wp-travel-engine' ); ?></label>
								<select id="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>]" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>]">
								<option value=" "><?php esc_html_e( 'Please choose&hellip;', 'wp-travel-engine' ); ?></option>
									<?php
									$obj     = \wte_functions();
									$options = $obj->order_form_title_options();
									$value   = $value[ $i ];
									foreach ( $options as $key => $val ) {
										echo '<option value="' . ( ! empty( $val ) ? esc_attr( $val ) : 'Please select' ) . '"' . selected( $value, $val, false ) . '>' . esc_html( $val ) . '</option>';
									}
									?>
								</select>
									<?php
									break;

								case 'fname':
									?>
								<label for="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'First Name', 'wp-travel-engine' ); ?></label>
								<input type="text" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
									<?php
									break;

								case 'lname':
									?>
								<label for="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'Last Name', 'wp-travel-engine' ); ?></label>
								<input type="text" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
									<?php
									break;

								case 'email':
									?>
								<label for="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'Email', 'wp-travel-engine' ); ?></label>
								<input type="email" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
									<?php
									break;

								case 'address':
									?>
								<label for="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'Address', 'wp-travel-engine' ); ?></label>
								<input type="text" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
									<?php
									break;

								case 'city':
									?>
								<label for="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'City', 'wp-travel-engine' ); ?></label>
								<input type="text" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
									<?php
									break;

								case 'country':
									?>
								<label for="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'Country', 'wp-travel-engine' ); ?></label>
								<select  id="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" data-placeholder="<?php esc_attr_e( 'Choose a field type&hellip;', 'wp-travel-engine' ); ?>" class="wc-enhanced-select" >
										<option value=" "><?php esc_html_e( 'Choose country&hellip;', 'wp-travel-engine' ); ?></option>
										<?php
										$obj     = \wte_functions();
										$options = $obj->wp_travel_engine_country_list();
										$value   = $value[ $i ];

										foreach ( $options as $key => $val ) {
											echo '<option value="' . ( ! empty( $val ) ? esc_attr( $val ) : 'Please select' ) . '"' . selected( $value, $key, false ) . '>' . esc_html( $val ) . '</option>';
										}
										?>
								</select>
									<?php
									break;

								case 'postcode':
									?>
								<label for="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'Post-code', 'wp-travel-engine' ); ?></label>
								<input type="number" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
									<?php
									break;

								case 'phone':
									?>
								<label for="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'Phone', 'wp-travel-engine' ); ?></label>
								<input type="tel" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
									<?php
									break;

								case 'passport':
									?>
								<label for="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'Passport Number', 'wp-travel-engine' ); ?></label>
								<input type="text" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
									<?php
									break;

								case 'dob':
									?>
								<label for="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'Date of Birth', 'wp-travel-engine' ); ?></label>
								<input class="wp-travel-engine-datetime" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" type="text" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
									<?php
									break;

								case 'gender':
									?>
								<label for="wp_travel_engine_booking_setting[place_order][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'Gender', 'wp-travel-engine' ); ?></label>
								<select id="wp_travel_engine_booking_setting[place_order][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" name="wp_travel_engine_booking_setting[place_order][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]">
								<option value=" "><?php esc_html_e( 'Please choose&hellip;', 'wp-travel-engine' ); ?></option>
									<?php
									$gender  = $value[ $i ];
									$obj     = \wte_functions();
									$options = $obj->gender_options();
									foreach ( $options as $key => $val ) {
										echo '<option value="' . ( ! empty( $gender ) ? esc_attr( $gender ) : 'Please select' ) . '"' . selected( $gender, $val, false ) . '>' . esc_html( $gender ) . '</option>';
									}
									?>
								</select>
									<?php
									break;
								default:
									$label_data = wp_travel_engine_get_traveler_info_field_label_by_name( $key )
									?>
									<label for="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php echo esc_html( $label_data ); ?></label>
									<input class="wp-travel-engine" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" type="text" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
									<?php
									break;
							}
							?>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php
			if ( isset( $relation_options['relation'] ) && $relation_options['relation'] != '' ) {
				?>
				<div class='wp-travel-engine-relation-details-wrapper'>
					<div class='relation-options-title'>
						<?php
						esc_html_e( 'Emergency contact details for Traveller: ', 'wp-travel-engine' );
						echo (int) $i;
						?>
					</div>
					<div class='wp-travel-engine-relation-details-inner-wrapper'>
					<?php
					foreach ( $relation_options['relation'] as $key => $value ) {
						?>
							<div class='wp-travel-engine-relation-details'>
								<?php
								switch ( $key ) {
									case 'title':
										?>
									<label for="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>]"><?php esc_html_e( 'Title', 'wp-travel-engine' ); ?></label>
									<select id="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>]" name="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>]">
									<option value=" "><?php esc_html_e( 'Please choose&hellip;', 'wp-travel-engine' ); ?></option>
										<?php
										$obj     = \wte_functions();
										$options = $obj->order_form_title_options();
										$value   = $value[ $i ];
										foreach ( $options as $key => $val ) {
											echo '<option value="' . ( ! empty( $val ) ? esc_attr( $val ) : 'Please select' ) . '"' . selected( $value, $val, false ) . '>' . esc_html( $val ) . '</option>';
										}
										?>
									</select>
										<?php
										break;

									case 'fname':
										?>
									<label for="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'First Name', 'wp-travel-engine' ); ?></label>
									<input type="text" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" name="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
										<?php
										break;

									case 'lname':
										?>
									<label for="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'Last Name', 'wp-travel-engine' ); ?></label>
									<input type="text" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" name="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
										<?php
										break;

									case 'address':
										?>
									<label for="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'Address', 'wp-travel-engine' ); ?></label>
									<input type="text" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" name="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
										<?php
										break;

									case 'postcode':
										?>
									<label for="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'Post-code', 'wp-travel-engine' ); ?></label>
									<input type="text" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" name="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
										<?php
										break;

									case 'phone':
										?>
									<label for="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'Phone', 'wp-travel-engine' ); ?></label>
									<input type="tel" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" name="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
										<?php
										break;

									case 'relation':
										?>
									<label for="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( 'Relation', 'wp-travel-engine' ); ?></label>
									<input type="text" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" name="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
										<?php
										break;
									default:
										$label_data = wp_travel_engine_get_relationship_field_label_by_name( $key )
										?>
										<label for="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"><?php echo esc_html( $label_data ); ?></label>
										<input class="wp-travel-engine" value="<?php echo isset( $value[ $i ] ) ? esc_attr( $value[ $i ] ) : ''; ?>" type="text" name="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" id="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" >
										<?php
										break;
								}
								?>
							</div>
						<?php
					}
					?>
					</div>
				</div>
				<?php
			}
			$wp_travel_engine_tabs = get_option( 'wp_travel_engine_settings' );
			if ( isset( $wp_travel_engine_tabs['placeorder']['medication'] ) && ! empty( $wp_travel_engine_tabs['placeorder']['medication'] ) ) {
				$maxlen   = max( array_keys( $wp_travel_engine_tabs['placeorder']['medication'] ) );
				$arr_keys = array_keys( $wp_travel_engine_tabs['placeorder']['medication'] );
				?>
			<div class="wp-travel-engine-medical-details-wrapper">
				<div class='relation-options-title'>
					<?php
					esc_html_e( 'Medical details for Traveller: ', 'wp-travel-engine' );
					echo (int) $i;
					?>
				</div>
				<?php
				foreach ( $arr_keys as $key => $value ) {
					?>
					<div class="wte-medication-form-list-li">
						<input type="checkbox" class="medication_check" name="wp_travel_engine_placeorder_setting[place_order][medication_check][<?php echo esc_attr( $i ); ?>][<?php echo esc_attr( $value ); ?>]"
							id="wp_travel_engine_placeorder_setting[place_order][medication_check][<?php echo esc_attr( $i ); ?>][<?php echo esc_attr( $value ); ?>]"
							<?php $j = isset( $wp_travel_engine_settings['place_order']['medication_check'][ $i ][ $value ] ) ? esc_attr( $wp_travel_engine_settings['place_order']['medication_check'][ $i ][ $value ] ) : '0'; ?>
							value="1" <?php checked( $j, true ); ?>/>
						<label for="wp_travel_engine_placeorder_setting[place_order][medication_check][<?php echo esc_attr( $i ); ?>][<?php echo esc_attr( $value ); ?>]">
						<?php echo isset( $wp_travel_engine_tabs['placeorder']['medication'][ $value ] ) ? esc_attr( $wp_travel_engine_tabs['placeorder']['medication'][ $value ] ) : ''; ?>
						</label>
					</div>
				<?php } ?>
			</div>
				<?php
			}
			?>

			<?php
		}
		?>
	</div>
