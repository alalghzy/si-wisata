<?php
	wp_enqueue_script( "parsley" );
	wp_enqueue_script( "jquery-fancy-box" );
	$wrapper_classes = apply_filters( 'wpte_bf_outer_wrapper_classes', '' );
	$wte_options     = get_option( 'wp_travel_engine_settings', true );

	global $wtetrip;
	$settings = get_option( 'wp_travel_engine_settings', array() );
?>
<div class="widget wpte-bf-outer <?php echo esc_attr( $wrapper_classes ); ?>">
	<!-- Prices List -->
	<?php do_action( 'wte_before_price_info' ); ?>
	<div class="wpte-booking-area">
		<button type="button" id="wpte_price-toggle-btn-mb" class="wpte_price-toggle-btn-mb" data-active-text="Hide Prices"><span class="current-text">
			<?php _e( 'Show Prices', 'wp-travel-engine' ); ?>
		</span></button>
		<div class="wpte-booking-inner-wrapper">
			<?php
			if ( true || wte_array_get( $wte_options, 'show_multiple_pricing_list_disp', '' ) == '1' ) :
				?>
				<!-- Group Discount Badge Section -->
				<?php
				if ( $wtetrip->has_group_discount ) :
					?>
					<span class="wpte-bf-gd-text"><?php echo esc_html( apply_filters( 'wte_group_discount_badge_text', __( 'Group Discount Available', 'wp-travel-engine' ) ) ); ?></span>
				<?php endif; // Group Discount Badge. ?>

				<!-- Discount Percent Badge -->
				<?php
				// Show Discount Percent if Available.
				if ( $wtetrip->has_sale ) :
					?>
					<span class="wpte-bf-discount-tag"><?php echo sprintf( esc_html__( '%d%% Off', 'wp-travel-engine' ), (float) $wtetrip->sale_percentage ); ?></span>
					<?php
				endif;
				?>
					<div class="wpte-bf-price-wrap">
						<?php
						// Displays Package with lowest pricings.
						\WP_Travel_Engine_Template_Hooks::categorised_trip_prices();
						?>
					</div>

				<?php
				// Show highlights if available.
				$highlights = isset( $settings['trip_highlights'] ) && is_array( $settings['trip_highlights'] ) ? $settings['trip_highlights'] : array();
				if ( count( $highlights ) > 0 ) :
					?>
					<div class="wpte-bf-content">
						<ul>
							<?php
							foreach ( $highlights as $highlight ) {
								$highlight = (object) $highlight;
								printf( '<li>%1$s%2$s</li>', esc_html( $highlight->highlight ), ! empty( $highlight->help ) ? '<span class="wpte-custom-tooltip" data-title="' . esc_attr( $highlight->help ) . '"></span>' : '' );
							}
							?>
						</ul>
					</div>
					<?php endif; ?>
			<?php endif; // Show_multiple_pricing_list_disp. ?>

			<div class="wpte-bf-btn-wrap">
				<button disabled type="button" id="open-booking-modal" class="wpte-bf-btn wte-book-now btn-loading"><?php esc_html_e( 'Check Availability', 'wp-travel-engine' ); ?></button>
			</div>
		</div>
		<?php if ( empty( $settings['enquiry'] ) ) : ?>
		<div class="wpte-booking-footer-text">
			<span><?php esc_html_e( 'Need help with booking?', 'wp-travel-engine' ); ?></span> <a href="#wte_enquiry_contact_form" id="wte-send-enquiry-message"><?php esc_html_e( 'Send Us A Message', 'wp-travel-engine' ); ?></a>
		</div>
		<?php endif; ?>
	</div>
	<?php
	do_action( 'wte_after_price_info' );
	?>
	<!-- ./ Prices List -->
</div>
<?php
