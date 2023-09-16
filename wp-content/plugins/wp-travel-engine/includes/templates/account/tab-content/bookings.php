<?php
/**
 * Booking Tab.
 *
 * @package wp-travel-engine/includes/templates/account/tab-content/
 */
wp_enqueue_script( "jquery-fancy-box" );
$bookings = $args['bookings'];

global $wp, $wte_cart;
$settings                      = wp_travel_engine_get_settings();
$wp_travel_engine_dashboard_id = isset( $settings['pages']['wp_travel_engine_dashboard_page'] ) ? esc_attr( $settings['pages']['wp_travel_engine_dashboard_page'] ) : wp_travel_engine_get_page_id( 'my-account' );
?>
<header class="wpte-lrf-header">
	<?php
	if ( ! empty( $bookings ) && isset( $_GET['action'] ) && wte_clean( wp_unslash( $_GET['action'] ) ) == 'partial-payment' ) : // phpcs:ignore
		?>
	<a href="<?php echo esc_url( get_permalink( $wp_travel_engine_dashboard_id ) ); ?>" class="wpte-back-btn">
		<?php wptravelengine_svg_by_fa_icon( "fas fa-arrow-left" ); ?><?php esc_html_e( 'View all bookings', 'wp-travel-engine' ); ?>
	</a>
	<h2 class="wpte-lrf-title"><?php esc_html_e( 'Remaining Booking Payment', 'wp-travel-engine' ); ?></h2>
	<div class="wpte-lrf-description">
		<p><?php esc_html_e( 'Please pay the remaining amount from below. If there is any issue, please contact us.', 'wp-travel-engine' ); ?>
		</p>
	</div>
	<?php else : ?>
	<h2 class="wpte-lrf-title"><?php esc_html_e( 'Booking', 'wp-travel-engine' ); ?></h2>
	<div class="wpte-lrf-description">
		<p><?php esc_html_e( 'Here is the list of bookings successfully made.', 'wp-travel-engine' ); ?>
		</p>
	</div>
	<?php endif; ?>
</header>
<div class="wpte-lrf-block-wrap">
	<div class="wpte-lrf-block">
	<?php
		if ( ! empty( $bookings ) && isset( $_GET['action'] ) && wte_clean( wp_unslash( $_GET['action'] ) ) == 'partial-payment' ) : // phpcs:ignore
		$booking = isset( $_GET['booking_id'] ) && ! empty( $_GET['booking_id'] ) ? sanitize_text_field( intval( $_GET['booking_id'] ) ) : ''; // phpcs:ignore
		wte_get_template(
		'account/remaining-payment.php',
		array(
            'booking' => $booking,
		)
        );
		elseif ( ! empty( $bookings ) && isset( $_GET['action'] ) && wte_clean( wp_unslash( $_GET['action'] ) ) == 'booking-details' ) : // phpcs:ignore
			$booking = isset( $_GET['booking_id'] ) && ! empty( $_GET['booking_id'] ) ? sanitize_text_field( intval( $_GET['booking_id'] ) ) : ''; // phpcs:ignore
			wte_get_template(
				'account/booking-details.php',
				array(
					'booking' => $booking,
				)
			);
		elseif ( ! empty( $bookings ) && ! isset( $_GET['action'] ) ) :
			?>
			<div class="wpte-bookings-tabmenu">
					<?php

					foreach ( $bookings_dashboard_menus as $key => $menu ) :
						?>
						<?php
						if ( $menu['menu_class'] == 'wpte-active-bookings' ) {
							$booking_menu_active_class = 'active';
						} else {
							$booking_menu_active_class = '';
						}
						?>
						<a class="wpte-booking-menu-tab <?php echo esc_attr( $menu['menu_class'] ); ?> <?php echo esc_attr( $booking_menu_active_class ); ?>" href="Javascript:void(0);"><?php echo esc_html( $menu['menu_title'] ); ?></a>
					<?php endforeach; ?>
				</div>
				<div class="wpte-booking-tab-main">
					<?php foreach ( $bookings_dashboard_menus as $key => $menu ) : ?>
						<?php
						if ( $menu['menu_class'] == 'wpte-active-bookings' ) {
							$booking_menu_active_class = 'active';
						} else {
							$booking_menu_active_class = '';
						}
						?>
						<div class="wpte-booking-tab-content wpte-<?php echo esc_attr( $key ); ?>-bookings-content <?php echo esc_attr( $menu['menu_class'] ); ?> <?php echo esc_attr( $booking_menu_active_class ); ?>">
							<?php
							if ( ! empty( $menu['menu_content_cb'] ) ) {
								$args['bookings_glance']    = $bookings_glance;
								$args['biling_glance_data'] = $biling_glance_data;
								$args['bookings']           = $bookings;
								call_user_func( $menu['menu_content_cb'], $args );
							}
							?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php
		else :
			esc_html_e( 'You have not made any bookings yet.', 'wp-travel-engine' );
		endif;
		?>
	</div>
</div>
<?php
