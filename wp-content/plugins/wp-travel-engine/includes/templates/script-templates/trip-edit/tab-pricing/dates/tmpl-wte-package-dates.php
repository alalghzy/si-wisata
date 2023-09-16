<script type="text/html" id="tmpl-wte-package-dates">
<#
var tripPackage = data.tripPackage;
var enabledHourSlots = tripPackage.has_weekly_time_slots
var tripDurationType = data.tripDurationType == "hours"
#>
<div class="wpte-block-content">
	<div class="wpte-block-heading">
		<h4><?php esc_html_e( 'Fixed Departure Dates', 'wp-travel-engine' ); ?></h4>
	</div>
	<div class="wpte-info-block">
		<p>
			<?php
			echo wp_kses(
				sprintf(
					__( 'By default, this trip can be booked throughout the year. Do you have trips with fixed departure dates and want them booked only on these days? Trip Fixed Starting Dates extension allows you to set specific dates when the trips can be booked. %1$sGet Trip Fixed Starting Dates extension now%2$s.', 'wp-travel-engine' ),
					'<a target="_blank" href="https://wptravelengine.com/plugins/trip-fixed-starting-dates/?utm_source=setting&amp;utm_medium=customer_site&amp;utm_campaign=setting_addon">',
					'</a>'
				),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			);
			?>
		</p>
	</div>
	<div class="wpte-field wpte-onoff-block wpte-weekly-houly-feature-wrap" style="{{tripDurationType && 'display:block;' || 'display:none;'}}">
		<input type="checkbox" checked="checked" name="package_weekly_time_slots_enable[{{tripPackage.id}}]" value="no">
		<a href="Javascript:void(0);" class="wpte-onoff-toggle{{enabledHourSlots && ' active' || ''}}">
			<label for="wpte-enable-hour-slots_{{tripPackage.id}}" class="wpte-field-label"><?php esc_html_e( 'Enable Hours', 'wp-travel-engine' ); ?><span class="wpte-onoff-btn"></span></label>
		</a>
		<input id="wpte-enable-hour-slots_{{tripPackage.id}}" type="checkbox" {{enabledHourSlots && 'checked' || ''}} name="package_weekly_time_slots_enable[{{tripPackage.id}}]" value="yes">
		<!-- <span class="wpte-tooltip">Enable the cut-off time for the trip bookings. The cut-off time will be the time before which bookings are allowed for the trip.</span> -->
		<div style="{{enabledHourSlots && 'display:block;' || 'display:none;'}}" class="wpte-onoff-popup">
			<div class="wte-week-days_list" id="wte-weekly-time-slots_{{tripPackage.id}}">
				<?php
				foreach ( range( 1, 7 ) as $_date ) {
					$date = new \DateTime( "2022-08-{$_date}" );
					?>
					<#
					var _index = <?php echo (int) $_date; ?>;
					var config = JSON.stringify({
						enableTime: true,
						noCalendar: true,
						dateFormat: "H:i",
					})
					#>
					<div class="wte-week-days_day-item">
						<div class="wte-week-days_day-title-wrap">
							<span class="wte-week-days_day-name"><?php echo esc_html( wp_date( 'l', $date->getTimestamp() ) ); ?></span>
							<div class="wte-week-days_day-date-input-control">
								<div class="wte-week-days_time-picker-container">
									<input type="text" id="wte-time-picker_{{tripPackage.id}}_{{_index}}" data-fpconfig="{{config}}" class="wte-week-days_time-picker wte-flatpickr" placeholder="-- : --" />
								</div>
								<button class="wpte-add-btn wpte-add-weekly-time-slot" data-package-id="{{tripPackage.id}}" data-target-td="<?php echo (int) $_date; ?>" />
							</div>
						</div>
						<div class="wte-week-days_selected-times" data-td-{{_index}}>
						<#
						var _weeklyTimeSlots = tripPackage['weekly_time_slots'];
						var _timeSlots = _weeklyTimeSlots[_index];

						for( var __index in _timeSlots ) {
							var _ts = _timeSlots[__index];
							#>
							<div class="wte-week-days_selected-time-item">
								<div class="wte-week-days_time-picker-container">
									<input
										type="text"
										class="wte-week-days_time-picker wte-flatpickr"
										data-fpconfig="{{config}}"
										placeholder="-- : --"
										name="<?php echo esc_attr( 'package_weekly_time_slots[{{tripPackage.id}}][{{_index}}][{{__index}}]' ); ?>"
										value="{{_ts}}"/>
									<button class="wte-week-days_clear-btn"></button>
								</div>
							</div>
						<# } #>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
</script>
<script type="text/html" id="tmpl-wte-package-dates-weekly-hour">
	<#
	var config = JSON.stringify({
		enableTime: true,
		noCalendar: true,
		dateFormat: "H:i",
	})
	var packageId
	#>
	<div class="wte-week-days_selected-time-item">
		<div class="wte-week-days_time-picker-container">
			<input
				type="text"
				data-fpconfig="{{config}}"
				class="wte-week-days_time-picker wte-flatpickr"
				value="{{data.hour}}"
				name="<?php echo esc_attr( 'package_weekly_time_slots[{{data.packageId}}][{{data.targetId}}][]' ); ?>"
				placeholder="-- : --" />
			<button class="wte-week-days_clear-btn"></button>
		</div>
	</div>
</div>
</script>
