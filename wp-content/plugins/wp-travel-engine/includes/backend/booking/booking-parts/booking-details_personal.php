<?php
/**
 * Personal details
 */
wp_enqueue_script( "jquery-ui-datepicker" );

global $post;
$cart_info         = get_post_meta( $post->ID, 'order_trips', ! 0 );
$item              = array_shift( $cart_info );
$pno               = ( isset( $item['pax'] ) ) ? array_sum( $item['pax'] ) : 0;
$booked_travellers = $pno;

?>
<div class="wpte-block-wrap">
	<div class="wpte-block">
		<div class="wpte-title-wrap">
			<h4 class="wpte-title"><?php esc_html_e( 'Traveller Details', 'wp-travel-engine' ); ?></h4>
		</div>
		<div class="wpte-block-content wpte-floated">

			<div class="wpte-toggle-item-wrap wpte-col2">
				<?php
				for ( $i = 1; $i <= $pno; $i++ ) {
					?>
						<div class="wpte-toggle-item">
							<div class="wpte-toggle-title">
								<a href="Javascript:void(0);"><?php printf( esc_html__( 'Traveller %1$s', 'wp-travel-engine' ), (int) $i ); ?></a>
							</div>
							<div class="wpte-toggle-content">
								<div class="wpte-prsnl-dtl-blk wpte-floated">
									<div class="wpte-button-wrap wpte-rightalign wpte-edit-prsnl-details">
										<a href="#" class="wpte-btn-transparent wpte-btn-sm">
											<?php wptravelengine_svg_by_fa_icon( "fas fa-pencil-alt" ); ?>
										<?php esc_html_e( 'Edit', 'wp-travel-engine' ); ?>
										</a>
									</div>
								<?php if ( isset( $personal_options['travelers'] ) ) : ?>
										<h4><?php esc_html_e( 'Traveller information', 'wp-travel-engine' ); ?></h4>
										<div class="wpte-prsnl-dtl-blk-content">
											<ul class="wpte-list">
												<?php
												foreach ( $personal_options['travelers'] as $key => $value ) :
													if ( ! isset( $value[ $i ] ) ) :
														continue;
													endif;

													$ti_key = 'traveller_' . $key;

													if ( 'fname' === $key ) {
														$ti_key = 'traveller_first_name';
													} elseif ( 'lname' === $key ) {
														$ti_key = 'traveller_last_name';
													} elseif ( 'passport' === $key ) {
														$ti_key = 'traveller_passport_number';
													}
													$data_label = wp_travel_engine_get_traveler_info_field_label_by_name( $ti_key );
													$data_value = isset( $value[ $i ] ) && ! empty( $value[ $i ] ) ? $value[ $i ] : false;
													if ( is_array( $data_value ) ) {
														$data_value = implode( ',', $data_value );
													}
													if ( $data_value ) :
														?>
														<li>
															<b><?php echo esc_html( $data_label ); ?></b>
															<?php
															if ( 'dob' === $key ) :
																$data_value = wte_get_formated_date( $data_value );
															endif;
															?>
															<span><?php echo esc_html( $data_value ); ?></span>
														</li>
														<?php
													endif;
												endforeach;
												?>
											</ul>
										</div>
										<div style="display:none;" class="wpte-prsnl-dtl-blk-content-edit edit-personal-info">
											<ul class="wpte-list">
												<?php
												foreach ( $personal_options['travelers'] as $key => $value ) :
													if ( ! isset( $value[ $i ] ) ) :
														continue;
													endif;

													$ti_key = 'traveller_' . $key;

													if ( 'fname' === $key ) {
														$ti_key = 'traveller_first_name';
													} elseif ( 'lname' === $key ) {
														$ti_key = 'traveller_last_name';
													} elseif ( 'passport' === $key ) {
														$ti_key = 'traveller_passport_number';
													}
													$data_label = wp_travel_engine_get_traveler_info_field_label_by_name( $ti_key );
													$data_value = isset( $value[ $i ] ) && ! empty( $value[ $i ] ) ? $value[ $i ] : false;
													if ( is_array( $data_value ) ) {
														$data_value = implode( ',', $data_value );
													}
													if ( $data_value ) :
														?>
														<li>
															<b><?php echo esc_html( $data_label ); ?></b>
															<?php
															switch ( $key ) {
																case 'dob':
																	$data_value = wte_get_formated_date( $data_value );
																	?>
																		<span>
																		<div class="wpte-field wpte-text">
																			<input class="wp-travel-engine-datetime hasDatepicker" type="text" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" value="<?php echo esc_attr( $data_value ); ?>">
																		</div>
																		</span>
																	<?php
																	break;
																case 'country':
																	?>
																		<span>
																			<div class="wpte-field wpte-select">
																			<select class="wpte-enhanced-select" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]">
																		<?php
																			$wte             = new Wp_Travel_Engine_Functions();
																			$country_options = $wte->wp_travel_engine_country_list();
																		foreach ( $country_options as $key => $country ) {
																			$selected = selected( $data_value, $country, false );
																			echo '<option ' . $selected . " value='" . esc_attr( $country ) ."'>" . esc_html( $country ) . "</option>"; // phpcs:ignore
																		}
																		?>
																		</select>
																		</div>
																		</span>
																		<?php
																	break;

																default:
																	?>
																		<span>
																		<div class="wpte-field wpte-text">
																			<input type="text" name="wp_travel_engine_booking_setting[place_order][travelers][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" value="<?php echo esc_attr( $data_value ); ?>">
																			</div>
																		</span>
																	<?php
															}
															?>
														</li>
														<?php
													endif;
												endforeach;
												?>
											</ul>
										</div>
									<?php endif; ?>
								</div>
								<div class="wpte-prsnl-dtl-blk wpte-floated">
									<div class="wpte-button-wrap wpte-rightalign wpte-edit-prsnl-details">
										<a href="#" class="wpte-btn-transparent wpte-btn-sm">
											<?php wptravelengine_svg_by_fa_icon( "fas fa-pencil-alt" ); ?>
											<?php esc_html_e( 'Edit', 'wp-travel-engine' ); ?>
										</a>
									</div>
									<?php if ( isset( $personal_options['relation'] ) ) : ?>
										<h4><?php esc_html_e( 'Emergency Contact', 'wp-travel-engine' ); ?></h4>
										<div class="wpte-prsnl-dtl-blk-content">
											<ul class="wpte-list">
												<?php
												foreach ( $personal_options['relation'] as $key => $value ) :
													if ( ! isset( $value[ $i ] ) ) :
														continue;
													endif;

													$ti_key = 'traveller_emergency_' . $key;

													if ( 'fname' === $key ) {
														$ti_key = 'traveller_emergency_first_name';
													} elseif ( 'lname' === $key ) {
														$ti_key = 'traveller_emergency_last_name';
													} elseif ( 'passport' === $key ) {
														$ti_key = 'traveller_emergency_passport_number';
													}
													$data_label = wp_travel_engine_get_relationship_field_label_by_name( $ti_key );
													$data_value = isset( $value[ $i ] ) && ! empty( $value[ $i ] ) ? $value[ $i ] : false;

													if ( is_array( $data_value ) ) {
														$data_value = implode( ',', $data_value );
													}

													if ( $data_value ) :
														?>
														<li>
															<b><?php echo esc_html( $data_label ); ?></b>
															<?php
															if ( 'dob' === $key ) :
																$data_value = wte_get_formated_date( $data_value );
															endif;
															?>
															<span><?php echo esc_html( $data_value ); ?></span>
														</li>
														<?php
													endif;
												endforeach;
												?>
											</ul>
										</div>
										<div style="display:none;" class="wpte-prsnl-dtl-blk-content-edit edit-relation-info">
											<ul class="wpte-list">
												<?php
												foreach ( $personal_options['relation'] as $key => $value ) :
													if ( ! isset( $value[ $i ] ) ) :
														continue;
													endif;

													$ti_key = 'traveller_emergency_' . $key;

													if ( 'fname' === $key ) {
														$ti_key = 'traveller_emergency_first_name';
													} elseif ( 'lname' === $key ) {
														$ti_key = 'traveller_emergency_last_name';
													} elseif ( 'passport' === $key ) {
														$ti_key = 'traveller_emergency_passport_number';
													}
													$data_label = wp_travel_engine_get_relationship_field_label_by_name( $ti_key );
													$data_value = isset( $value[ $i ] ) && ! empty( $value[ $i ] ) ? $value[ $i ] : false;
													if ( is_array( $data_value ) ) {
														$data_value = implode( ',', $data_value );
													}

													if ( $data_value ) :
														?>
															<li>
																<b><?php echo esc_html( $data_label ); ?></b>
																<?php
																switch ( $key ) {
																	case 'dob':
																		$data_value = wte_get_formated_date( $data_value );
																		?>
																			<span>
																			<div class="wpte-field wpte-text">
																				<input class="wp-travel-engine-datetime hasDatepicker" type="text" name="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" value="<?php echo esc_attr( $data_value ); ?>">
																				</div>
																			</span>
																		<?php
																		break;
																	case 'country':
																		?>
																			<div class="wpte-field wpte-select">
																				<select class="wpte-enhanced-select" name="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]">
																			<?php
																				$wte             = new Wp_Travel_Engine_Functions();
																				$country_options = $wte->wp_travel_engine_country_list();
																			foreach ( $country_options as $key => $country ) {
																				$selected = selected( $data_value, $country, false );
																				echo '<option ' . $selected . " value='" . esc_attr( $country ) . "'>" . esc_html( $country ) . "</option>"; // phpcs:ignore
																			}
																			?>
																			</select>
																			</div>
																			<?php
																		break;

																	default:
																		?>
																			<span>
																			<div class="wpte-field wpte-text">
																				<input type="text" name="wp_travel_engine_booking_setting[place_order][relation][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]" value="<?php echo esc_attr( $data_value ); ?>">
																				</div>
																			</span>
																		<?php
																}
																?>
															</li>
														<?php
													endif;
												endforeach;
												?>
											</ul>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php
					if ( $i % 3 === 0 && $i != $booked_travellers ) {
						echo '</div><div class="wpte-toggle-item-wrap wpte-col2">';
					}
				}
				?>
			</div>

		</div>
	</div> <!-- .wpte-block -->
</div> <!-- .wpte-block-wrap -->
<?php
