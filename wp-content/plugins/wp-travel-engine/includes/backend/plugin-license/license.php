<?php
/**
 * Plugin License page.
 */
$wp_travel_engine = get_option( 'wp_travel_engine_license' );
$addon_name       = apply_filters( 'wp_travel_engine_addons', array() );

?>
<div class="wpte-main-wrap wte-license-key">
	<div class="wpte-tab-sub wpte-horizontal-tab">
		<form method="post" action="options.php">
			<?php wp_nonce_field( 'wp_travel_engine_license_nonce', 'wp_travel_engine_license_nonce' ); ?>

			<?php settings_fields( 'wp_travel_engine_license' ); ?>
			<div class="wpte-tab-wrap">
				<a href="javascript:void(0);" class="wpte-tab wte-addons current"><?php esc_html_e( 'WP Travel Engine Addons', 'wp-travel-engine' ); ?></a>
			</div>

			<div class="wpte-tab-content-wrap">
				<div class="wpte-tab-content wte-addons-content current">
					<div class="wpte-title-wrap">
						<h2 class="wpte-title"><?php esc_html_e( 'License Keys', 'wp-travel-engine' ); ?></h2>
						<div class="settings-note">
							<?php esc_html_e( 'All of the premium addon installed and activated on your website has been listed below. You can add/edit and manage your License keys for each addon individually.', 'wp-travel-engine' ); ?>
						</div>
					</div> <!-- .wpte-title-wrap -->

					<div class="wpte-block-content">
						<input type="hidden" name="addon_name" class="addon_name" type="text" value="" />
					<?php
					$licensed_addons = wptravelengine_get_licensed_addons();
					if ( count( $licensed_addons ) == 0 ) {
						echo '<h3 class="active-msg" style="color:#CA4A1F;">' . esc_html__( 'Premium Extensions not Found!', 'wp-travel-engine' ) . '</h3>';
					}

					foreach ( $licensed_addons as $key => $addon ) {
						/**
						 * Check addons status here.
						 *
						 * @since 4.3.8
						 */
						$addon          = (object) $addon;
						$license_status = wptravelengine_addon_check_license( $addon );
						$active_class   = isset( $license_status->license ) && 'valid' === $license_status->license ? 'wte-license-activate' : '';

						$activation_message = '';
						$msg_color          = '';
						if ( $license_status->license === 'valid' ) {
							$activation_message = sprintf( __( 'Your license key for %1$s addon is activated on this site.', 'wp-travel-engine' ), $addon->title );
							$msg_color          = 'style="color:#11b411"';
						} elseif ( ! empty( $addon->{'license_key'} ) ) {
							$activation_message = isset( $_GET['wte_license_error_msg'] ) && $_GET['wte_addon_name'] === $addon->slug ? sanitize_text_field( wp_unslash( $_GET['wte_license_error_msg'] ) ) : sprintf( __( 'Your license key for %1$s addon is not activated on this site yet. Please activate.', 'wp-travel-engine' ), $addon->title ); // phpcs:ignore
							$msg_color          = 'style="color:#f66757"';
						}
						?>
							<div class="wpte-floated <?php echo esc_attr( $active_class ); ?>">
								<label for="wp_travel_engine_license[<?php echo esc_attr( $addon->slug ); ?>_license_key]" class="wpte-field-label"><?php echo esc_html( $addon->title ); ?></label>
								<div class="wpte-field wpte-password">
									<input id="<?php echo esc_attr( $addon->slug ); ?>" class="wp_travel_engine_addon_license_key" name="wp_travel_engine_license[<?php echo esc_attr( $addon->slug ); ?>_license_key]" type="text" class="regular-text" value="<?php echo esc_attr( $addon->license_key ); ?>" />
									<span <?php echo esc_html( $msg_color ); ?> class="wpte-tooltip"><?php echo esc_html( $activation_message ); ?></span>
								<?php if ( 'valid' == $license_status->license ) : ?>
										<span class="wte-license-active">
											<?php wptravelengine_svg_by_fa_icon( "fas fa-check" ); ?>
										<?php esc_html_e( 'Activated', 'wp-travel-engine' ); ?>
										</span>
									<?php endif; ?>
								</div>
								<div class="wpte-btn-wrap">
								<?php if ( $license_status->license == 'valid' ) { ?>
									<input type="submit" class="wpte-btn wpte-btn-deactive deactivate-license" data-id="<?php echo esc_attr( $addon->slug ); ?>" name="edd_license_deactivate" value="<?php echo 'Deactivate License'; ?>"/>
								<?php } elseif ( ! empty( $addon->{'license_key'} ) ) { ?>
									<input type="submit" class="wpte-btn wpte-btn-active activate-license" data-id="<?php echo esc_attr( $addon->slug ); ?>" name="edd_license_activate" value="<?php echo 'Activate License'; ?>"/>
								<?php } ?>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="wpte-field wpte-submit" style="text-align:<?php echo count( $addon_name ) > 0 ? "right" : "left"; ?>;">
			<?php
			if ( sizeof( $addon_name ) != 0 ) {
				?>
				<input id="submit" type="submit" name="submit" value="<?php echo esc_attr__( 'Save Changes', 'wp-travel-engine' ); ?>">
				<?php
			} else {
				echo '<a target="_blank" href="https://wptravelengine.com/plugins/?utm_source=setting&amp;utm_medium=customer_site&amp;utm_campaign=setting_addon" class="wpte-link-btn">' . esc_html__( 'Get Now', 'wp-travel-engine' ) . '</a>';
			}
			?>
			</div>
		</form>
	</div>
</div><!-- .wpte-main-wrap -->
<script>
(function ($) {
	$('body').on('click', '.activate-license, .deactivate-license', function (e) {
		var val = $(this).attr('data-id');
		$('.addon_name').attr('value', val);
	});
}(jQuery));
</script>
