<?php
/**
 * Booking Details Page
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/account/booking-details.php.
 *
 * HOWEVER, on occasion WP Travel will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://wptravelengine.com
 * @author  WP Travel Engine
 * @package WP Travel Engine/includes/templates
 * @version 1.3.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$booking_metas           = get_post_meta( $booking, 'wp_travel_engine_booking_setting', true );
$booking_meta            = booking_meta_details( $booking );
$global_settings         = wp_travel_engine_get_settings();
$default_payment_gateway = isset( $global_settings['default_gateway'] ) && ! empty( $global_settings['default_gateway'] ) ? $global_settings['default_gateway'] : 'booking_only';
$user_account_page_id    = wp_travel_engine_get_dashboard_page_id();

$booking_object = get_post( $booking );
if ( is_null( $booking_object ) || 'booking' !== $booking_object->post_type ) {
	wp_safe_redirect( get_permalink( $user_account_page_id ) );
}
$ordered_trips = $booking_object->order_trips;
$order_trip    = (object) array_shift( $ordered_trips );
$trip_metas    = get_post_meta( $booking_meta['trip_id'], 'wp_travel_engine_setting', true );

if ( empty( $booking_object->payments ) ) {
	$show_pay_now_btn = ( $payment_status == 'partially-paid' || $booking_meta['remaining_payment'] > 0 ) && ! empty( $active_payment_methods );
	$total_paid       = wte_array_get( $booking_metas, 'place_order.cost', 0 );
	$due              = wte_array_get( $booking_metas, 'place_order.due', 0 );
	$payment_status   = get_post_meta( $booking, 'wp_travel_engine_booking_payment_status', true );
	if ( wte_array_get( $booking_metas, 'place_order.due', 0 ) <= 0 ) {
		wp_safe_redirect( get_permalink( $user_account_page_id ) );
	}
} else {
	if ( +$booking_object->due_amount > 0 ) {
		$show_pay_now_btn = +$booking_object->due_amount > 0;
	}
	$total_paid       = $booking_object->paid_amount;
	$due              = $booking_object->due_amount;
	$booking_payments = $booking_object->payments;
	$payment_status   = array();
	if ( is_array( $booking_payments ) ) {
		foreach ( $booking_payments as $payment_id ) {
			$payment_status[] = get_post_meta( $payment_id, 'payment_status', true );
		}
	}
	$payment_status = implode( '/', $payment_status );
}
$order_trips = $booking_object->order_trips;

$booked_trip                   = is_array( $order_trips ) ? array_shift( $order_trips ) : array();
$billing_info                  = $booking_object->billing_info;
$settings                      = wp_travel_engine_get_settings();
$wp_travel_engine_dashboard_id = isset( $settings['pages']['wp_travel_engine_dashboard_page'] ) ? esc_attr( $settings['pages']['wp_travel_engine_dashboard_page'] ) : wp_travel_engine_get_page_id( 'my-account' );
?>
<a href="<?php echo esc_url( get_permalink( $wp_travel_engine_dashboard_id ) ); ?>" class="wpte-back-btn">
	<?php wptravelengine_svg_by_fa_icon( "fas fa-arrow-left" ); ?><?php esc_html_e( 'Go back', 'wp-travel-engine' ); ?>
</a>
<div class="wpte-booking-details-wrapper">
	<div class="wpte-booking-detail-left-section">
		<div class="wpte-trip-info">
			<div class="wpte-trip-image">
				<?php echo get_the_post_thumbnail( $booking_meta['trip_id'] ); ?>
			</div>
			<div class="wpte-trip-description">
				<h5 class="wpte-trip-heading">
					<?php echo esc_html( $booking_meta['trip_name'] ); ?>
				</h5>
				<?php
				if ( class_exists( 'Wte_Trip_Review_Init' ) ) {
					$review_obj              = new Wte_Trip_Review_Init();
					$comment_datas           = $review_obj->pull_comment_data( $booking_meta['trip_id'] );
					$icon_type               = '';
					$icon_fill_color         = '#F39C12';
					$review_icon_type        = apply_filters( 'trip_rating_icon_type', $icon_type );
					$review_icon_fill_colors = apply_filters( 'trip_rating_icon_fill_color', $icon_fill_color );
					if ( ! empty( $comment_datas ) ) {
						?>
					<span class="review">
						<div
							class="agg-rating trip-review-stars <?php echo ! empty( $review_icon_type ) ? 'svg-trip-adv' : 'trip-review-default'; ?>"
							data-icon-type='<?php echo esc_attr( $review_icon_type ); ?>' data-rating-value="<?php echo esc_attr( $comment_datas['aggregate'] ); ?>"
							data-rateyo-rated-fill="<?php echo esc_attr( $review_icon_fill_colors ); ?>"
							data-rateyo-read-only="true"
						>
						</div>
						<div class="aggregate-rating reviw-txt-wrap">
							<span><?php printf( esc_html( _nx( '%s review', '%s reviews', absint( $comment_datas['i'] ), 'review count', 'wp-travel-engine' ) ), esc_html( number_format_i18n( $comment_datas['i'] ) ) ); ?></span>
						</div>
					</span>

						<?php
					}
				}
				?>
				<a class="wpte-trip-link" href="<?php echo get_permalink( $booking_meta['trip_id'] ); ?>"><?php echo esc_html_e( 'View Trip', 'wp-travel-engine' ); ?></a>
			</div>
		</div>
		<div class="wpte-billing-info">
			<h6 class="wpte-billing-heading"><?php esc_html_e( 'Billing Information', 'wp-travel-engine' ); ?></h6>
			<div class="wpte-billing-content">
				<ul>
					<li>
						<span>
						<?php esc_html_e( 'First Name:', 'wp-travel-engine' ); ?>
						</span>
						<span>
						<?php echo esc_html( wte_array_get( $billing_info, 'fname', '' ) ); ?>
						</span>
					</li>
					<li>
					<span>
					<?php esc_html_e( 'Email:', 'wp-travel-engine' ); ?>
					</span>
					<span>
					<?php echo esc_html( wte_array_get( $billing_info, 'email', '' ) ); ?>
					</span>
					</li>
					<li>
					<span>
					<?php esc_html_e( 'Last Name:', 'wp-travel-engine' ); ?>
					</span>
					<span>
					<?php echo esc_html( wte_array_get( $billing_info, 'lname', '' ) ); ?>
					</span>
					</li>
					<li>
					<span>
					<?php esc_html_e( 'Address:', 'wp-travel-engine' ); ?>
					</span>
					<span>
					<?php echo esc_html( wte_array_get( $billing_info, 'address', '' ) ); ?>
					</span>
					</li>
					<li>
					<span>
					<?php esc_html_e( 'Country:', 'wp-travel-engine' ); ?>
					</span>
					<span>
					<?php echo esc_html( wte_array_get( $billing_info, 'country', '' ) ); ?>
					</span>
					</li>
					<li>
					<span>
					<?php esc_html_e( 'City:', 'wp-travel-engine' ); ?>
					</span>
					<span>
					<?php echo esc_html( wte_array_get( $billing_info, 'city', '' ) ); ?>
					</span>
					</li>
				</ul>

			</div>
		</div>
	</div>
	<div class="wte-booking-detail-right-section">
		<div class="wpte-booking-details">
			<h5 class="wpte-booking-heading"><?php echo esc_html( sprintf( __( 'Booking Details #%1$s', 'wp-travel-engine' ), $booking ) ); ?></h5>
			<div class="wpte-trip-booking-info">
				<h6><?php esc_html_e( 'Trip Information', 'wp-travel-engine' ); ?></h6>
				<ul>
					<li>
						<span>
							<?php esc_html_e( 'Trip Code:', 'wp-travel-engine' ); ?>
						</span>
						<span>
							<?php echo esc_html( $trip_metas['trip_code'] ); ?>
						</span>
					</li>
				</ul>
				<div class="wpte-trip-booking-date">
					<div class="wpte-trip-start-date">
						<span class="wpte-info-title">
							<?php esc_html_e( 'Trip Start Date:', 'wp-travel-engine' ); ?>
						</span>
						<span class="wpte-info-value">
							<?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $booking_meta['trip_start_date'] ) ) ); ?>
						</span>
						<?php if ( isset( $booking_meta['trip_start_date_with_time'] ) && $booking_meta['trip_start_date_with_time'] != '' ) { ?>
						<span class="wpte-info-time">
							<?php
							$newDate = date( 'H:i A', strtotime( $booking_meta['trip_start_date_with_time'] ) );
							echo esc_html( 'From ' . $newDate . '', 'wp-travel-engine' )
							?>
						</span>
						<?php } ?>
					</div>
					<div class="wpte-trip-end-date">
						<span class="wpte-info-title">
							<?php esc_html_e( 'Trip End Date:', 'wp-travel-engine' ); ?>
						</span>
						<span class="wpte-info-value">
							<?php
							$date      = date_create( $booking_meta['trip_start_date'] );
							$trip_days = $trip_metas['trip_duration'] . 'days';
							date_add( $date, date_interval_create_from_date_string( $trip_days ) );
							echo $new_date = date_format( $date, 'F-d-Y' );
							?>
						</span>
					</div>
				</div>
				<ul>
					<li>
						<span>
							<?php esc_html_e( 'Total length of travel:', 'wp-travel-engine' ); ?>
						</span>
						<span>
							<?php echo esc_html( $trip_metas['trip_duration'] ); ?>
							<?php echo esc_html( $trip_metas['trip_duration_unit'] ); ?>
						</span>
					</li>
				</ul>
			</div>
			<div class="wpte-travellers-info">
				<h6><?php esc_html_e( 'Travellers', 'wp-travel-engine' ); ?></h6>
				<ul>
					<?php
					$pricing_categories = get_terms(
						array(
							'taxonomy'   => 'trip-packages-categories',
							'hide_empty' => false,
							'orderby'    => 'term_id',
							'fields'     => 'id=>name',
						)
					);
					if ( is_wp_error( $pricing_categories ) ) {
						$pricing_categories = array();
					}
					foreach ( $order_trip->pax as $category => $number ) {
						$label = isset( $pricing_categories[ $category ] ) ? $pricing_categories[ $category ] : $category;
						?>
						<li>
							<span>
								<?php echo esc_html( $label ); ?>:
							</span>
							<span>
								<?php echo esc_attr( $number ); ?>
							</span>
						</li>
						<?php
					}
					?>
				</ul>

			</div>
			<?php
			if ( ! empty( $order_trip->trip_extras ) ) {
				?>
				<div class="wpte-extra-services-info">
					<h6><?php esc_html_e( 'Extra Services', 'wp-travel-engine' ); ?></h6>
					<ul>
						<?php
						foreach ( $order_trip->trip_extras as $index => $tx ) {
							?>
								<li>
									<span>
										<?php echo esc_html( $tx['extra_service'] ); ?>:
									</span>
									<span>
										<?php echo esc_html( $tx['qty'] ); ?>
									</span>
								</li>
							<?php
						}
						?>
					</ul>
				</div>
				<?php } ?>
		</div>
		<div class="wpte-payment-details">
			<h5 class="wpte-payment-heading"><?php esc_html_e( 'Payment Details', 'wp-travel-engine' ); ?></h5>
			<div class="wpte-payment-data">
				<h6><?php esc_html_e( 'Payment #1', 'wp-travel-engine' ); ?></h6>
				<ul>
					<li>
					<span>
						<?php esc_html_e( 'Payment ID:', 'wp-travel-engine' ); ?>
					</span>
					<span>
						<?php echo esc_html( $payment_id ); ?>
					</span>
					</li>
					<li>
					<span>
						<?php esc_html_e( 'Payment Status:', 'wp-travel-engine' ); ?>
					</span>
					<span class="wpte-status <?php echo $payment_status == 'completed' ? 'completed' : 'pending'; ?>">
						<?php echo wp_kses( $payment_status, array( 'code' => array() ) ); ?>
					</span>
					</li>
					<li>
					<span>
						<?php esc_html_e( 'Amount:', 'wp-travel-engine' ); ?>
					</span>
					<span>
						<?php echo wte_esc_price( wte_get_formated_price_html( $booking_object->cart_info['total'] ) ); ?>
					</span>
					</li>
					<?php
					$wc_order_id = get_post_meta( $booking, '_wte_wc_order_id', true );
					if ( ! empty( $wc_order_id ) ) :
					?>
						<li>
						<?php
						printf(
							__( 'This booking was made using WooCommerce payments, view detail payment information %shere%s', 'wp-travel-engine' ),
							'<a href="' . admin_url( "/post.php?post={$wc_order_id}&action=edit" ) . '">',
							'</a>'
						);
						?>
						</li>
					<?php endif; ?>
				</ul>
			</div>
			<div class="wpte-payment-info">
				<h6><?php esc_html_e( 'Payment Info', 'wp-travel-engine' ); ?></h6>
				<ul>
					<li>
					<span>
						<?php esc_html_e( 'Total Cost:', 'wp-travel-engine' ); ?>
					</span>
					<span>
						<?php echo wte_esc_price( wte_get_formated_price_html( $booking_object->cart_info['total'] ) ); ?>
					</span>
					</li>
					<li>
					<span>
						<?php esc_html_e( 'Total Paid Amount:', 'wp-travel-engine' ); ?>
					</span>
					<span>
						<?php echo wte_esc_price( wte_get_formated_price_html( $total_paid ) ); ?>
					</span>
					</li>
					<li>
					<span>
						<?php esc_html_e( 'Total Due Amount:', 'wp-travel-engine' ); ?>
					</span>
					<span>
						<?php echo wte_esc_price( wte_get_formated_price_html( $due ) ); ?>
					</span>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
