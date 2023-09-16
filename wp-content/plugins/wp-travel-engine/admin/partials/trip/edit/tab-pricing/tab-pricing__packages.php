<?php
/**
 * Tab Pricings and Dates sub-content - Packages
 *
 * @since 5.0.0
 */
?>
<div id="wte-packages">
	<input type="hidden" name="trip-edit-tab__dates-pricings" />
	<div class="wpte-title-wrap">
		<h2 class="wpte-title"><?php esc_html_e( 'Packages', 'wp-travel-engine' ); ?></h2>
	</div>
	<div class="wpte-repeator-wrap" id="wte-packages-wrapper">
		<div class="wpte-repeater-hading" style="display:none;">
			<div class="wpte-repeator-title"><?php esc_html_e( 'Package Name', 'wp-travel-engine' ); ?></div>
		</div>
		<div id="wte-packages-list">
			<div class="wpte-loading-anim"></div><!-- Packages will be listed here. -->
		</div>
	</div>
</div>
<div>
	<button
		class="wpte-add-btn wte-package-create"
		id="wte-package-create"
		data-api-action="createPackage"><?php echo esc_html__( 'Add A New Package', 'wp-travel-engine' ); ?></button>
</div>
