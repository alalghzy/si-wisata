<?php
/**
 * Admin View: Notice - Untested extensions.
 *
 * @package WPTravelEngine\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wte-plugin-upgrade-notice <?php echo esc_attr( $upgrade_type ); ?>">
	<p><?php echo wp_kses_post( $message ); ?></p>

	<table class="plugin-details-table" cellspacing="0">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Plugin', 'wp-travel-engine' ); ?></th>
				<th><?php esc_html_e( 'Tested up to', 'wp-travel-engine' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $untested_plugins as $plugin ) : ?>
				<tr>
					<td><?php echo esc_html( $plugin['Name'] ); ?></td>
					<td><?php echo esc_html( $plugin['WTE tested up to'] ); ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>
