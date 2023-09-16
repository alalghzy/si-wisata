<div id="wte-booking-datetime-content"></div>
<script type="text/html" id="tmpl-wte-booking-datetime">
	<div class="wte-process-tab-content">
		<div class="wte-booking-date-wrap">
			<div id="wte-booking-date-calendar"></div>
			<div id="wte-booking-times-content"></div>
		</div>
	</div>
</script>

<script type="text/html" id="tmpl-wte-booking-times">
	<#
	var times = data.availableTimes
	var selectedTime = data.selectedTime
	if(Object.keys(times).length > 0) { /* iftil */
	#>
	<div class="wte-booking-times">
		<div class="wte-button-group">
		<#
		var _timeIndex = 0;
		for( var _ti in times ) { /* forvartiat */
			var _ptime = times[_ti]
			var activeClass = selectedTime.get() && selectedTime.get().getTime() === _ptime.from.getTime() ? ' checked' : ''
			var isAvailable = !_ptime.isAvailable ? 'disabled' : ''
		#>
			<button class="wte-check-button wte-time-select-btn{{activeClass}}" {{isAvailable}} data-package-id="{{_ptime.packages.join(',')}}" data-time="{{_ptime.from.getTime()}}">
				{{_ptime.formatter(_ptime.from)}} - {{{_ptime.formatter(_ptime.to)}}}
			</button>
			<# _timeIndex++; #>
		<# } /* endforvartiat */ #>
		</div>
	</div>
	<# } /* endiftil */ #>
</script>
