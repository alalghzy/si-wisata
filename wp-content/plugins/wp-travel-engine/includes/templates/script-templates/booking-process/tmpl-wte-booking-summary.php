<script id="tmpl-wte-booking-summary" type="text/html">
<#
var trip = data.trip;
var tripPackages = data.tripPackages;
var selectedDate = data.selectedDate;
var selectedTime = data.selectedTime;
var selectedPackage = data.selectedPackage;
var travelerRecord = data.travelerRecord;
var packageCategories = data.packageCategories;
var formatPrice = data.formatPrice;
var groupPricings = data.groupPricings;
var cartTotal = data.cartTotal || {}
cartTotal = Object.values(cartTotal).length < 1 ? 0 : Object.values(cartTotal).reduce((acc, current) => +acc + +current )
#>
	<h5 class="wte-booking-block-title"><?php esc_html_e( 'Booking Summary', 'wp-travel-engine' ); ?></h5>
	<h2 class="wte-booking-trip-title">{{{trip.title.rendered}}}</h2>
	<div class="wte-booking-dates">
	<# if(selectedDate.get()) { /*  ifsd  */#>
		<p class="wte-booking-starting-date"><strong><?php esc_html_e( 'Starting Date:', 'wp-travel-engine' ); ?></strong> {{{selectedTime.get() ? selectedTime.format(null, true) : selectedDate.format()}}}</p>
	<# } /*  endifsd  */#>
	<# if(selectedTime.get() && false ) { /*  ifsd  */#>
		<p class="wte-booking-starting-time"><strong><?php esc_html_e( 'Starting Time:', 'wp-travel-engine' ); ?></strong> {{selectedTime.format(null, true)}}</p>
	<# } /*  endifsd  */#>
	</div>
	<div class="wte-booking-summary-info">
		<#
		if(selectedPackage) { /*  iftpsp */
		#>
		<h5 class="wte-booking-summary-info-title">
		<?php
		// translators: 1: Package Name.
		printf( esc_html__( 'Package: %s', 'wp-travel-engine' ), '{{{tripPackages[ selectedPackage ].title.rendered}}}' );
		?>
		</h5>
		<#
		} /*  endiftpsp */;
		#>
		<div class="wte-booking-trip-info">
			<# if(Object.values(travelerRecord).length > 0 ) { /* ifovtr */#>
			<div class="wte-booking-details">
				<h6 class="wte-booking-details-title"><?php esc_html_e( 'Travellers', 'wp-travel-engine' ); ?></h6>
				<ul>
					<# for( var cid in travelerRecord ) {
						var count = !! travelerRecord[cid] ? travelerRecord[cid] : 0
						if(count < 1 ){
							continue;
						}
						var pc = packageCategories[cid]
						var price = pc.enabledSale ? pc.salePrice : pc.price
						if ( pc.enabledGroupDiscount && Object.keys(groupPricings).length > 0 && groupPricings[cid] ) {
							var gp = groupPricings[cid]
							for( let r of gp ) { /* Group Pricing check discount */
								if(r.to.length < 1 ) {
									price = r.price || price
									break;
								}
								if(+count >= +r.from && +count <= +r.to ) {
									price = r.price
									break;
								}
							}
						}
					#>
					<li>
						<label><strong>{{count}} {{{packageCategories[cid].label}}}</strong> <span class="qty">({{{formatPrice(price)}}}/{{pc.pricingType === 'per-group' ? "<?php esc_html_e( 'group', 'wp-travel-engine' ); ?>" : "<?php esc_html_e( 'person', 'wp-travel-engine' ); ?>"}})</span></label>
						<div class="amount-figure"><strong>{{{formatPrice( ( pc.pricingType === 'per-group' ? +price : +count * +price ) )}}}</strong></div>
					</li>
					<# } #>
				</ul>
			</div>
			<# } /* endifovtr */ #>
			<?php do_action( 'wte_after_booking_details' ); ?>
		</div>
		<div class="total-amount">
			<p class="price"><span class="total-text"><?php echo esc_html__( 'Total :', 'wp-travel-engine' ); ?></span> {{{formatPrice(cartTotal)}}}</p>
		</div>
	</div>
</script>
