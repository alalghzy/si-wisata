<!-- Temaplate for Categories. -->
<script type="text/html" id="tmpl-wte-package-categories">
	<!--  -->
	<#
	var package = data.tripPackage
	var idSuffix = '_' + package.id;
	#>
	<div class="wpte-block-content">
		<div class="wpte-block-heading">
			<h4><?php esc_html_e( 'Package Pricing Categories', 'wp-travel-engine' ); ?></h4>
			<?php
				echo wp_kses(
					sprintf( __( 'To manage package pricing categories %1$sClick Here%2$s', 'wp-travel-engine' ), sprintf( '<a href="%1$s" target="_blank" class="">', admin_url( 'term.php?taxonomy=trip-packages-categories' ) ), '</a>' ),
					array(
						'a' => array(
							'href'   => array(),
							'class'  => array(),
							'target' => array(),
						),
					)
				);
				?>
		</div>
		<div class="wte-accordion" id="wte-package-categories{{idSuffix}}"><!-- Categories will be listed here --></div>
	</div>
</script>
