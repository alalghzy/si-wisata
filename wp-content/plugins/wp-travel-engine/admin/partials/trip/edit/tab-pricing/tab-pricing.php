<?php
/**
 * Trip Edit Tab - Pricing Template.
 */
$post_ID      = $args['post_id'];
$packages_ids = get_post_meta( $post_ID, 'packages_ids', true );
$next_tab     = $args['next_tab'];

$tab_contents = apply_filters(
	'trip_edit_tab_pricing_and_dates_tab_content',
	array(
		10 => array(
			'content_id'       => 'packages',
			'content_callback' => function() {
				require_once WTE_PRICING_TAB_PARTIALS_DIR . 'tab-pricing__packages.php';
			},
		),
		20 => array(
			'content_id'       => 'partial-payment-upsell',
			'content_callback' => function() {
					require_once WTE_PRICING_TAB_PARTIALS_DIR . 'tab-pricing__partial-payment-upsell.php';
			},
		),
	),
	$post_ID
);

ksort( $tab_contents );

foreach ( $tab_contents as $args ) {
	if ( isset( $args['content_callback'] ) && is_callable( $args['content_callback'] ) ) {
		call_user_func( $args['content_callback'], $post_ID );
	}
}

?>
<script>
jQuery(function($) {
	;(function() {
		// wteEdit && wteEdit.renderPackages()
		var toggleInputs = document.getElementsByClassName('wpte-onoff-block');
		if (toggleInputs) {
			for (var i = 0; i < toggleInputs.length; i++) {
				var ti = toggleInputs[i];
				console.log(ti)
				var toggle = ti.getElementsByClassName('wpte-onoff-toggle')[0]
				var input = ti.querySelector('[type=checkbox]')
				if (input && input.checked) {
					toggle.classList.add('active')
				}
			}
		}
		flatpickr('#wte-flatpickr__date', {
			mode: 'multiple'
		})
	})()
})
</script>

<?php
$trip_edit_script_templates = array(
	'tmpl-wte-package-general'         => 'script-templates/trip-edit/tab-pricing/tmpl-wte-package-general.php',
	'tmpl-wte-packages'                => 'script-templates/tmpl-wte-packages.php',
	'tmpl-wte-package-single'          => 'script-templates/tmpl-wte-package-single.php',
	'tmpl-wte-package-categories'      => 'script-templates/tmpl-wte-package-categories.php',
	'tmpl-wte-package-category-single' => 'script-templates/tmpl-wte-package-category-single.php',
	// 'tmpl-wte-package-date-single'     => 'script-templates/trip-edit/tab-pricing/dates/tmpl-wte-package-date-single.php',
	'tmpl-wte-package-dates'           => 'script-templates/trip-edit/tab-pricing/dates/tmpl-wte-package-dates.php',
	'tmpl-wte-group-discount'          => 'script-templates/trip-edit/tab-pricing/group-discount/tmpl-wte-group-discount-pricing.php',
);

foreach ( $trip_edit_script_templates as $template ) {
	wte_get_template( $template );
}
?>
<?php
if ( $next_tab ) :
	?>
	<div class="wpte-field wpte-submit">
		<input data-tab="pricing" data-post-id="<?php echo esc_attr( $post_ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>" data-next-tab="<?php echo isset( $next_tab['callback_function'] ) ? esc_attr( $next_tab['callback_function'] ) : ''; ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php esc_attr_e( 'Continue', 'wp-travel-engine' ); ?>">
	</div>
	<?php
endif;
