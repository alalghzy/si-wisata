<?php
/**
 * Dates upsell
 */
// Get post ID.
$post_id  = $args['post_id'];
$next_tab = $args['next_tab'];
?>
<div class="wpte-form-block">
	<div class="wpte-title-wrap">
		<h2 class="wpte-title"><?php esc_html_e( 'Fixed Departure Dates', 'wp-travel-engine' ); ?></h2>
	</div> <!-- .wpte-title-wrap -->
	<div class="wpte-info-block">
		<p>
			<?php
				echo wp_kses(
					sprintf( __( 'By default, this trip can be booked throughout the year. Do you have trips with fixed departure dates and want them booked only on these days? Trip Fixed Starting Dates extension allows you to set specific dates when the trips can be booked. %1$sGet Trip Fixed Starting Dates extension now%2$s.', 'wp-travel-engine' ), '<a target="_blank" href="https://wptravelengine.com/plugins/trip-fixed-starting-dates/?utm_source=setting&utm_medium=customer_site&utm_campaign=setting_addon">', '</a>' ),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					)
				);
				?>
		</p>
	</div>
</div>
<?php
if ( $next_tab ) :
	?>
<div class="wpte-field wpte-submit">
	<input data-tab="overview" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php esc_attr_e( 'Continue', 'wp-travel-engine' ); ?>">
</div>
	<?php
endif;
