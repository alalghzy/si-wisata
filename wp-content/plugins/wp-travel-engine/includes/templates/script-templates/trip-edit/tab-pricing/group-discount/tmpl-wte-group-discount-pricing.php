<?php
/**
 * Template for group discount pricing
 */
?>
<script id="tmpl-wte-group-discount-pricing" type="text/html">
	<div class="wpte-info-block">
		<?php
		echo wp_kses(
			sprintf(
				__( 'Want to provide group discounts and increase sales? Group Discount extension allows you to provide group discount on the basis of number booking a tour. %1$sGet Group Discount extension now%2$s.', 'wp-travel-engine' ),
				'<a target="_blank" href="https://wptravelengine.com/plugins/group-discount/?utm_source=setting&utm_medium=customer_site&utm_campaign=setting_addon">',
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
	</div>
</script>
