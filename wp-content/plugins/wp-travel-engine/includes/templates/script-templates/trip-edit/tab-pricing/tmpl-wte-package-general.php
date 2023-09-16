<?php
use WPTravelEngine\Packages;

$pricing_categories = Packages\get_packages_pricing_categories();

$options = array();

foreach ( $pricing_categories as $pricing_category ) {
	$options[ $pricing_category->term_id ] = array(
		'label'      => $pricing_category->name,
		'attributes' => array(
			'selected' => '{{primaryCategory == ' . $pricing_category->term_id . " ? ' selected ' : ''}}",
		),
	);
}
?>

<script type="text/html" id="tmpl-wte-package-general">
	<#
	var tripPackage = data.tripPackage
	var idSuffix = '_' + tripPackage.id
	var index = +tripPackage.id
	var primaryCategory = tripPackage.primary_pricing_category

	var categories = {}
	#>
	<div class="wpte-block-content">
		<div class="wpte-block-heading">
			<h4><?php esc_html_e( 'General Package Settings', 'wp-travel-engine' ); ?></h4>
		</div>
		<?php
		$field_builder = new WTE_Field_Builder_Admin(
			array(
				array(
					'label'       => __( 'Short Description', 'wp-travel-engine' ),
					'name'        => 'packages_descriptions[{{tripPackage.id}}]',
					'type'        => 'textarea',
					'value'       => '{{tripPackage.content.raw}}',
					'placeholder' => 'Add short description for pricing package..',
				),
			)
		);

		$field_builder->render();
		?>
	</div>
</script>
