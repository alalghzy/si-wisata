<!-- Container to render packages content later -->
<div id="wte-booking-packages-content"></div>
<script type="text/html" id="tmpl-wte-booking-packages">
	<#
	var trip = data.trip;
	var tripPackages = data.tripPackages;
	var availablePackages = data.availablePackages;
	var selectedPackageID = data.selectedPackage;
	var primaryCategory = data.primaryCategory;
	var packageCategories = data.packageCategories;
	var	travelerRecord = data.travelerRecord;
	var formatPrice = data.formatPrice;
	var groupPricings = data.groupPricings;
	var selectedPackage = tripPackages[selectedPackageID] || null
	#>
	<div class="wte-process-tab-content">
		<# if(availablePackages.length > 1) { /*  ifapl  */#>
			<div class="wte-button-group wte-package-type">
				<#
				for(var apindex of trip.packages_ids ) { /*  forapiap */
					var ap = availablePackages.find(function(_p) {return apindex == _p.id});
					if(!ap) continue;
					var activeClass = +ap.id === +selectedPackageID ? ' checked' : '';
					#>
					<button class="wte-check-button wte-package-select-btn{{activeClass}}" data-package-id="{{ap.id}}">{{{ap.title.rendered}}}</button>
					<#
					} /*  endforapiap */
				#>
			</div>
		<# } /*  endifapl  */#>
		<div class="wte-selected-package-description">{{{selectedPackage && selectedPackage.content.rendered}}}</div>
		<hr>
		<div class="wte-option-heading">
			<span class="text-left"><?php esc_html_e( 'Travellers', 'wp-travel-engine' ); ?></span>
			<span class="text-right"><?php esc_html_e( 'Quantity', 'wp-travel-engine' ); ?></span>
		</div>
		<div class="wte-trip-options">
			<#
			var _pcids = []
			if(Object.keys(packageCategories).length > 0 && primaryCategory && primaryCategory.id) {
				_pcids = Object.keys(packageCategories).filter(function(_id) {return _id != primaryCategory.id})
				_pcids.unshift(primaryCategory.id)
			}
			for( var pcid of _pcids ) { /*  forpcidipc */
				var pc = packageCategories[pcid]
				if( pc.price.length < 1 ) {
					continue;
				}

				var minPax = pc.minPax
				var maxPax = pc.maxPax
				var infoData = JSON.stringify({
							packageID: selectedPackageID,
							catID: pcid,
						})
				var travelerCount = travelerRecord[pcid] || 0

				var categoryPrice = pc.enabledSale ? +(pc.salePrice) : +(pc.price);
				if ( pc.enabledGroupDiscount && Object.keys(groupPricings).length > 0 && groupPricings[pcid] ) {
					var gp = groupPricings[pcid]
					for( let r of gp ) {
						if(r.to.length < 1 && +travelerCount >= +r.from) {
							categoryPrice = r.price || categoryPrice
							break;
						}
						if(+travelerCount >= +r.from && +travelerCount <= +r.to ) {
							categoryPrice = r.price
							break;
						}
					}
				}
				#>
				<div class="wte-trip-guest-wrapper{{pcid == primaryCategory.id ? ' primary-pricing-category' : ''}}">
					<div class="check-in-wrapper">
						<label>{{{pc.label}}}</label>
						<#
						if ( pc.enabledGroupDiscount && Object.keys(groupPricings).length > 0 && groupPricings[pcid] ) {
							var gp = groupPricings[pcid]
						#>
						<div class="wpte-select-options wpte-group-discount-options wte-popper">
							<button class="option-toggle"><?php esc_html_e( 'Group Discounts', 'wp-travel-engine' ); ?></button>
							<div class="wpte-select-options-wrapper">
								<ul class="options-list">
									<li class="list-heading">
										<span class="no-travelers"><?php esc_html_e( 'Number Of Travellers', 'wp-travel-engine' ); ?></span>
										<# if ( pc.pricingType === 'per-person' ) { #>
										<span class="price-per-person"><?php esc_html_e( 'Price/Person', 'wp-travel-engine' ); ?></span>
										<# } #>
										<# if ( pc.pricingType === 'per-group' ) { #>
										<span class="price-per-person"><?php esc_html_e( 'Price/Group', 'wp-travel-engine' ); ?></span>
										<# } #>
									</li>
									<#
									for( let r of gp ) {
										var travelersCount = ''
										travelersCount += r.from
										if(r.to) {
											travelersCount += ' - ' + r.to
										}else{
											travelersCount += '+'
										}
									#>
									<li>
										<span class="traveler-title">{{travelersCount}}</span>
										<span class="traveler-price">{{{formatPrice(r.price)}}}</span>
									</li>
									<# } #>
								</ul>
							</div>
						</div>
							<#
						}
						#>
					</div>
					<div class="select-wrapper">
						<div class="amount-per-person">
							<# if(pc.enabledSale) { /*  ifpcespcid  */#>
								<span class="regular-price"><del>{{{formatPrice(pc.price)}}}</del></span>
								<span class="offer-price">{{{formatPrice(categoryPrice)}}}</span>
							<# } else { #>
								<span class="offer-price">{{{formatPrice(categoryPrice)}}}</span>
							<# } /*  endifpcespcid  */#>
							<# if ( pc.pricingType === 'per-person' ) { #>
							<span class="per-text">/ <?php esc_html_e( 'person', 'wp-travel-engine' ); ?></span>
							<# } #>
							<# if ( pc.pricingType === 'per-group' ) { #>
							<span class="per-text">/ <?php esc_html_e( 'group', 'wp-travel-engine' ); ?></span>
							<# } #>
						</div>
						<div class="wte-qty-number wte-booking-pc-counter" data-info='{{infoData}}'>
							<button class="prev wte-down" {{travelerCount > 0 ? '' : 'disabled' }}>
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="2" viewBox="0 0 14 2">
									<path id="Path_23951" data-name="Path 23951" d="M0,0H12" transform="translate(1 1)" fill="none" stroke="#170d44" stroke-linecap="round" stroke-width="2" opacity="0.5"/>
								</svg>
							</button>
							<input type="text" value="{{travelerCount}}" readonly />
							<button class="next wte-up" {{travelerCount < +maxPax || maxPax == '' ? '' : 'disabled' }}>
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14">
									<g id="Group_2263" data-name="Group 2263" transform="translate(-78 -14)" opacity="0.5">
										<line id="Line_2" data-name="Line 2" x2="12" transform="translate(79 21)" fill="none" stroke="#170d44" stroke-linecap="round" stroke-width="2"/>
										<line id="Line_3" data-name="Line 3" x2="12" transform="translate(84.999 15) rotate(90)" fill="none" stroke="#170d44" stroke-linecap="round" stroke-width="2"/>
									</g>
								</svg>
							</button>
						</div>
					</div>
				</div>
				<#
			} /* endforpcidipc */
			#>
		</div>
	</div>
</script>
