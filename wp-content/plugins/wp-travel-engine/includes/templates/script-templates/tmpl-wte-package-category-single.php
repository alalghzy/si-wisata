<script type="text/html" id="tmpl-wte-package-category">
	<#
	var {packageID, categoryID, enabledSale, label, maxPax, minPax, price, salePrice, pricingType} = data.category
	categoryidSuffix = '_' + packageID + '_' + categoryID;

	#>
	<div class="wte-accordion-item">
		<div class="wte-accordion-header">
			<label class="wte-accordion-button" style="display:block;" data-target="wte-collapse-{{packageID}}-{{categoryID}}">{{label || 'Label'}}</label>
		</div>
		<div id="wte-collapse-{{packageID}}-{{categoryID}}" class="wte-accordion-collapse wte-collapse">
			<div class="wte-accordion-body">
				<input type="hidden" name="categories[{{packageID}}][c_ids][{{categoryID}}]" value="{{categoryID}}">
				<?php
				$field_builder = new WTE_Field_Builder_Admin(
					array(
						array(
							'label'       => __( 'Label', 'wp-travel-engine' ),
							'name'        => 'categories[{{packageID}}][labels][{{categoryID}}]',
							'value'       => '{{{label}}}',
							'type'        => 'text',
							'readonly'    => ! 0,
							'placeholder' => __( 'Label', 'wp-travel-engine' ),
						),
						array(
							'type'      => 'multifields',
							'tooltip'   => __( 'Change pricing type for this pricing option. Selecting "Per Group" will treat the price on total regardless of number of travellers.', 'wp-travel-engine' ),

							'subfields' => array(
								array(
									'label'       => __( 'Price', 'wp-travel-engine' ),
									'name'        => 'categories[{{packageID}}][prices][{{categoryID}}]',
									'value'       => '{{price}}',
									'type'        => 'text',
									'placeholder' => __( 'e.g 500', 'wp-travel-engine' ),
									'id'          => 'category_price{{categoryidSuffix}}',
									'attributes'  => array(
										'data-wte-field-type' => 'price',
									),
								),
								array(
									'label'   => __( 'Pricing Type', 'wp-travel-engine' ),
									'name'    => 'categories[{{packageID}}][pricing_types][{{categoryID}}]',
									'value'   => '{{pricingType}}',
									'type'    => 'select',
									'options' => array(
										'per-person' => array(
											'label'      => __( 'Per Person', 'wp-travel-engine' ),
											'attributes' => array(
												'selected' => "{{pricingType == 'per-person' ? ' selected ' : ''}}",
											),
										),
										'per-group'  => array(
											'label'      => __( 'Per Group', 'wp-travel-engine' ),
											'attributes' => array(
												'selected' => "{{pricingType == 'per-group' ? ' selected ' : ''}}",
											),
										),
									),
								),
							),
						),
						array(
							'label'             => __( 'Enable Sale', 'wp-travel-engine' ),
							'name'              => 'categories[{{packageID}}][enabled_sale][{{categoryID}}]',
							'value'             => '1',
							'default_value'     => '1',
							'type'              => 'checkbox',
							'id'                => 'enable_sale{{categoryidSuffix}}',
							'checked_classname' => "{{(enabledSale) ? ' active' : ''}}",
							'attributes'        => array(
								'checked'            => "{{(enabledSale) ? 'checked' : ''}}",
								'data-toggle-target' => '#sale_price{{categoryidSuffix}}',
							),
							'wrapper_classes'   => 'flex-direction-row',
							'after_field'       => "<input id=\"sale_price{{categoryidSuffix}}\" style=\"{{(enabledSale) ? '' : 'display:none;'}}\" type=\"text\" name=\"categories[{{packageID}}][sale_prices][{{categoryID}}]\" data-wte-field-type=\"price\" value=\"{{salePrice}}\" />",
						),
						array(
							'type'      => 'multifields',
							'subfields' => array(
								array(
									'label'      => __( 'Min Pax.', 'wp-travel-engine' ),
									'name'       => 'categories[{{packageID}}][min_paxes][{{categoryID}}]',
									'value'      => '{{minPax || ""}}',
									'attributes' => array(
										'min' => '0',
									),
									'type'       => 'number',
								),
								array(
									'label'      => __( 'Max Pax.', 'wp-travel-engine' ),
									'name'       => 'categories[{{packageID}}][max_paxes][{{categoryID}}]',
									'value'      => '{{maxPax || ""}}',
									'attributes' => array(
										'min' => 0,
									),
									'type'       => 'number',
								),
							),
						),
					)
				);
				$field_builder->render();
				?>
				<div id="wte-group-discount-pricing{{categoryidSuffix}}"><!-- Group Discount goes here --></div>
			</div>
		</div>
	</div>
</script>
