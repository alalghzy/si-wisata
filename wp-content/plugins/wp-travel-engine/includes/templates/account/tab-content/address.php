<?php
/**
 * Address Tab content.
 */
	// Get current user.
	$current_user = $args['current_user'];
?>
<header class="wpte-lrf-header">
	<h2 class="wpte-lrf-title"><?php esc_html_e( 'Billing Address', 'wp-travel-engine' ); ?></h2>
	<div class="wpte-lrf-description">
		<p><?php esc_html_e( 'Edit your billing address details.', 'wp-travel-engine' ); ?></p>
	</div>
</header>
<div class="wpte-lrf-block-wrap">
	<div class="wpte-lrf-block">
		<?php
			wte_get_template(
				'account/form-edit-billing.php',
				array(
					'user' => $current_user,
				)
			);
			?>
	</div>
</div>
<?php
