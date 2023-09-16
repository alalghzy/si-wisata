<?php
/**
 * Admin general Tab content - Trip Meta
 *
 *  @package Wp_Travel_Engine/Admin/Meta_parts
 */
// Get global post.
global $post;

// Get settings meta.
$wp_travel_engine_setting = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

// Get DB values.
$trip_duration        = isset( $wp_travel_engine_setting['trip_duration'] ) ? $wp_travel_engine_setting['trip_duration'] : false;
$trip_duration_nights = isset( $wp_travel_engine_setting['trip_duration_nights'] ) ? $wp_travel_engine_setting['trip_duration_nights'] : false;
$trip_duration_unit   = $trip_duration ? 'days' : '';

// cut-off
$enable_cut_off    = isset( $wp_travel_engine_setting['trip_cutoff_enable'] ) ? true : false;
$trip_cut_off_time = isset( $wp_travel_engine_setting['trip_cut_off_time'] ) ? $wp_travel_engine_setting['trip_cut_off_time'] : false;
$trip_cut_off_unit = isset( $wp_travel_engine_setting['trip_cut_off_unit'] ) ? $wp_travel_engine_setting['trip_cut_off_unit'] : 'days';

// Min- Max age
$min_max_age_enable = isset( $wp_travel_engine_setting['min_max_age_enable'] ) ? true : false;
$trip_minimum_age   = get_post_meta( $post->ID, 'wp_travel_engine_trip_min_age', true );
$trip_maximum_age   = get_post_meta( $post->ID, 'wp_travel_engine_trip_max_age', true );

// Min-Max Pax
$minmax_pax_enable = isset( $wp_travel_engine_setting['minmax_pax_enable'] ) ? true : false;
$trip_minimum_pax  = isset( $wp_travel_engine_setting['trip_minimum_pax'] ) ? $wp_travel_engine_setting['trip_minimum_pax'] : '';
$trip_maximum_pax  = isset( $wp_travel_engine_setting['trip_maximum_pax'] ) ? $wp_travel_engine_setting['trip_maximum_pax'] : '';
?>
<?php
/**
 * wp_travel_engine_trip_code_display hook
 *
 * @hooked wpte_display_trip_code_section
 */
do_action( 'wp_travel_engine_trip_code_display' );
?>

<?php
$duration_array = apply_filters(
	'wp_travel_engine_trip_duration_units',
	array(
		'days'  => esc_html__( 'Days', 'wp-travel-engine' ),
		'hours' => esc_html__( 'Hours', 'wp-travel-engine' ),
	// 'minutes' => esc_html__( 'Minutes', 'wp-travel-engine' )
	)
);
?>
<div class="wpte-field wpte-floated">
	<label class="wpte-field-label"><?php esc_html_e( 'Duration', 'wp-travel-engine' ); ?></label>
	<div class="wpte-multi-fields wpte-floated">
		<input type="number" min="1" step="1" name="wp_travel_engine_setting[trip_duration]" value="<?php echo $trip_duration ? esc_attr( $trip_duration ) : ''; ?>" placeholder="<?php esc_attr_e( 'Enter Duration', 'wp-travel-engine' ); ?>">
		<select name="wp_travel_engine_setting[trip_duration_unit]"
			data-onchange
			data-onchange-toggle-off-value="hours"
			data-onchange-toggle-target="#wte-edit__general_duration-night">
			<option><?php esc_html_e( 'Select Duration Type', 'wp-travel-engine' ); ?></option>
			<?php
			$trip_duration_unit = isset( $wp_travel_engine_setting['trip_duration_unit'] ) ? $wp_travel_engine_setting['trip_duration_unit'] : 'days';
			foreach ( $duration_array as $value => $label ) {
				echo '<option ' . selected( $trip_duration_unit, $value, false ) . ' value="' . esc_attr( $value ) . '">' . esc_html( $label ) . '</option>';
			}
			?>
		</select>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter the duration ( number ) for the trip and choose desired unit.', 'wp-travel-engine' ); ?></span>
</div>
<?php // if ( $trip_duration_nights ) : ?>
<div class="wpte-field wpte-floated<?php echo $trip_duration_unit == 'hours' ? ' hidden' : '' ?>" id="wte-edit__general_duration-night">
	<label class="wpte-field-label"><?php esc_html_e( 'Nights', 'wp-travel-engine' ); ?> </label>
	<div class="wpte-multi-fields wpte-floated">
		<input type="number" min="1" step="1" name="wp_travel_engine_setting[trip_duration_nights]" value="<?php echo $trip_duration_nights ? esc_attr( $trip_duration_nights ) : ''; ?>" placeholder="<?php esc_attr_e( 'Enter Duration', 'wp-travel-engine' ); ?>">
		<select>
			<option><?php esc_html_e( 'Night(s)', 'wp-travel-engine' ); ?></option>
		</select>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter the trip duration in nights.', 'wp-travel-engine' ); ?></span>
</div>
<?php // endif; ?>
<div class="wpte-field wpte-onoff-block">
	<a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $enable_cut_off ? 'active' : ''; ?>">
		<label for="wpte-enable-cut-off" class="wpte-field-label"><?php esc_html_e( 'Enable Cut-Off Time', 'wp-travel-engine' ); ?><span class="wpte-onoff-btn"></span></label>
	</a>
	<input id="wpte-enable-cut-off" type="checkbox" <?php checked( $enable_cut_off, true ); ?> name="wp_travel_engine_setting[trip_cutoff_enable]" value="true">
	<span class="wpte-tooltip"><?php esc_html_e( 'Enable the cut-off time for the trip bookings. The cut-off time will be the time before which bookings are allowed for the trip.', 'wp-travel-engine' ); ?></span>
	<div <?php echo $enable_cut_off ? 'style="display:block;"' : ''; ?> class="wpte-onoff-popup">
		<div class="wpte-field wpte-multi-fields wpte-floated">
			<label class="wpte-field-label"><?php esc_html_e( 'Cut-Off Time', 'wp-travel-engine' ); ?></label>
			<div class="wpte-floated">
				<input type="number" step="1" min="1" name="wp_travel_engine_setting[trip_cut_off_time]" value="<?php echo $trip_cut_off_time ? (int) $trip_cut_off_time : ''; ?>" placeholder="<?php esc_attr_e( 'Enter cut-off time', 'wp-travel-engine' ); ?>">
				<select name="wp_travel_engine_setting[trip_cut_off_unit]">
					<option><?php esc_html_e( 'Select Duration Type', 'wp-travel-engine' ); ?></option>
					<?php
							$cut_off = apply_filters(
								'wp_travel_engine_trip_duration_units',
								array(
									'days' => esc_html__( 'Days', 'wp-travel-engine' ),
								// 'hours' => esc_html__( 'Hours', 'wp-travel-engine' ),
								)
							);
							foreach ( $cut_off as $value => $label ) {
								echo '<option ' . selected( $trip_cut_off_unit, $value, false ) . ' value="' . esc_attr( $value ) . '">' . esc_html( $label ) . '</option>';
							}
							?>
				</select>
			</div>
			<span class="wpte-tooltip"><?php esc_html_e( 'Enter trip cut-off value in number of days. If you set your cutoff time to 1 day, the product cannot be booked with a start date today. If 2 days, the product cannot be booked with a start date today and tomorrow etc.', 'wp-travel-engine' ); ?></span>
		</div>
	</div>
</div>

<div class="wpte-field wpte-onoff-block">
	<a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $min_max_age_enable ? 'active' : ''; ?>">
		<label for="wpte-enable-min-max-age" class="wpte-field-label"><?php esc_html_e( 'Set Minimum And Maximum Age', 'wp-travel-engine' ); ?><span class="wpte-onoff-btn"></span></label>
	</a>
	<input id="wpte-enable-min-max-age" type="checkbox" <?php checked( $min_max_age_enable, true ); ?> name="wp_travel_engine_setting[min_max_age_enable]" value="true">
	<span class="wpte-tooltip"><?php esc_html_e( 'Enable minimum and maximum age required restriction for booking this trip.', 'wp-travel-engine' ); ?></span>
	<div <?php echo $min_max_age_enable ? 'style="display:block;"' : ''; ?> class="wpte-onoff-popup">
		<div class="wpte-field wpte-minmax wpte-floated">
			<div class="wpte-min">
				<label class="wpte-field-label"><?php esc_html_e( 'Minimum Age', 'wp-travel-engine' ); ?></label>
				<input type="number" step="1" min="1" name="wp_travel_engine_trip_min_age" value="<?php echo esc_attr( $trip_minimum_age ); ?>" placeholder="<?php esc_attr_e( 'Enter minimum age', 'wp-travel-engine' ); ?>">
			</div>
			<div class="wpte-max">
				<label class="wpte-field-label"><?php esc_html_e( 'Maximum Age', 'wp-travel-engine' ); ?></label>
				<input type="number" step="1" min="1" name="wp_travel_engine_trip_max_age" value="<?php echo esc_attr( $trip_maximum_age ); ?>" placeholder="<?php esc_attr_e( 'Enter maximum age', 'wp-travel-engine' ); ?>">
			</div>
		</div>
	</div>
</div>

<div class="wpte-field wpte-onoff-block">
	<a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $minmax_pax_enable ? 'active' : ''; ?>">
		<label for="wpte-fsd-enable-min-max" class="wpte-field-label"><?php esc_html_e( 'Set Minimum And Maximum Participants', 'wp-travel-engine' ); ?><span class="wpte-sublabel"><?php esc_html_e( '(Optional)', 'wp-travel-engine' ); ?></span><span class="wpte-onoff-btn"></span></label>
	</a>
	<input id="wpte-fsd-enable-min-max" type="checkbox" <?php checked( $minmax_pax_enable, true ); ?> name="wp_travel_engine_setting[minmax_pax_enable]" value="true">
	<span class="wpte-tooltip"><?php esc_html_e( 'Enable minimum and maximum participants for booking this trip.', 'wp-travel-engine' ); ?></span>
	<div <?php echo $minmax_pax_enable ? 'style="display:block;"' : ''; ?> class="wpte-onoff-popup">
		<div class="wpte-field wpte-minmax wpte-floated">
			<div class="wpte-min">
				<label class="wpte-field-label"><?php esc_html_e( 'Minimum Participants', 'wp-travel-engine' ); ?></label>
				<input type="number" step="1" min="1" name="wp_travel_engine_setting[trip_minimum_pax]" value="<?php echo esc_attr( $trip_minimum_pax ); ?>" placeholder="<?php esc_attr_e( 'Enter minimum participants', 'wp-travel-engine' ); ?>">
			</div>
			<div class="wpte-max">
				<label class="wpte-field-label"><?php esc_html_e( 'Maximum Participants', 'wp-travel-engine' ); ?></label>
				<input type="number" step="1" min="1" name="wp_travel_engine_setting[trip_maximum_pax]" value="<?php echo esc_attr( $trip_maximum_pax ); ?>" placeholder="<?php esc_attr_e( 'Enter maximum participants', 'wp-travel-engine' ); ?>">
			</div>
		</div>
	</div>
</div>
<?php if ( $next_tab ) : ?>
<div class="wpte-field wpte-submit">
	<input data-tab="general" data-post-id="<?php echo esc_attr( $post->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php esc_attr_e( 'Save &amp; Continue', 'wp-travel-engine' ); ?>">
</div>
	<?php
endif;
