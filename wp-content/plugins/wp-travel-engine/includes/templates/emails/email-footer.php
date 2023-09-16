<?php
/**
 * Email footer template.
 */
$settings        = get_option( 'wp_travel_engine_settings' );
$hide_powered_by = ! empty( $settings['hide_powered_by'] ) && 'yes' === $settings['hide_powered_by'];
?>
						<div class="footer">
							<?php
							if ( ! $hide_powered_by ) :
								?>
							<table width="100%">
								<tr>
									<td class="aligncenter content-block"><?php esc_html_e( 'Powered By:', 'wp-travel-engine' ); ?> <a href="https://wptravelengine.com"><?php esc_html_e( 'WP Travel Engine', 'wp-travel-engine' ); ?></td>
								</tr>
							</table>
							<?php endif; ?>
						</div>
					</div>
				</td>
				<td></td>
			</tr>
		</table>
	</body>
</html>
<?php
