<script type="text/html" id="tmpl-wte-package">
	<#
	var tripPackage = data.tripPackage
	var idSuffix = '_' + tripPackage.id
	var index = +tripPackage.id
	#>
	<div class="wpte-repeator-block wpte-sortable" id="wte-package-{{tripPackage.id}}">
		<div class="wpte-repeator-block-title">
			<div class="wpte-field">
				<input type="text" name="packages_titles[{{tripPackage.id}}]" placeholder="Package Name" value="{{{tripPackage.title.rendered}}}" />
			</div>
			<input type="hidden" name="packages_ids[{{index}}]" placeholder="id" value="{{tripPackage.id}}"/>
			<div class="wpte-action-wrap">
				<a href="#" class="wpte-btn wte-package-edit" data-package-id="{{tripPackage.id}}"><?php esc_html_e( 'Edit Pricing and Dates', 'wp-travel-engine' ); ?></a>
				<button class="wpte-delete wte-package-delete" data-target="#wte-package-{{tripPackage.id}}" data-package-id="{{tripPackage.id}}"></button>
			</div>
			<div id="wte-package-editor{{idSuffix}}" class="wte-package-editor" style="display:none;">
				<div class="wpte-tab-sub wpte-horizontal-tab">
					<div class="wpte-tab-wrap wte-tabs">
						<a href="#wpte-tab-pricing-categories-{{tripPackage.id}}" data-toggle="tab" class="wte-tab current" ><?php echo esc_html__( 'Pricing Categories', 'wp-travel-engine' ); ?></a>
						<a href="#wpte-tab-pricing-dates-{{tripPackage.id}}" data-toggle="tab" class="wte-tab"><?php echo esc_html__( 'Dates', 'wp-travel-engine' ); ?></a>
						<a href="#wpte-tab-pricing-general-{{tripPackage.id}}" data-toggle="tab" class="wte-tab" ><?php echo esc_html__( 'General', 'wp-travel-engine' ); ?></a>
					</div>
					<div class="wpte-tab-content-wrap wte-tabs-content">
						<div class="wpte-tab-content wte-tabpricing-general" id="wpte-tab-pricing-general-{{tripPackage.id}}"><!-- General Tab Content will be rendered here --></div>
						<div class="wpte-tab-content current wte-tabpricing-categories" id="wpte-tab-pricing-categories-{{tripPackage.id}}"><!-- Categories Tab Content will be rendered here --></div>
						<div class="wpte-tab-content wte-tabpricing-dates" id="wpte-tab-pricing-dates-{{tripPackage.id}}"><!-- Dates tab content will be rendered here --></div>
					</div>
				</div>
				<button data-fancybox-close title="<?php esc_html_e( 'Save and Close', 'wp-travel-engine' ); ?>" class="wte-package-editor__close"><?php esc_html_e( 'Save and Close', 'wp-travel-engine' ); ?></button>
			</div>
		</div>
	</div>
</script>
