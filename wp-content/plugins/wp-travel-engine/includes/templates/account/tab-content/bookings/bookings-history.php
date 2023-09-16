<?php
/**
 * Booking History Tab.
 *
 * @package wp-travel-engine/includes/templates/account/tab-content/bookings
 */
$bookings = $args['bookings'];

global $wp, $wte_cart;
?>

<div class="wpte-bookings-contents">
<?php
foreach ( $bookings as $key => $booking ) :
	$booking_object = get_post( $booking );
	if ( is_null( $booking_object ) || 'booking' !== $booking_object->post_type || 'publish' !== $booking_object->post_status ) {
		continue;
	}
	$booking_metas    = get_post_meta( $booking, 'wp_travel_engine_booking_setting', true );
	$booking_meta     = booking_meta_details( $booking );
	$show_pay_now_btn = ! 1;
	$booking_status   = get_post_meta( $booking, 'wp_travel_engine_booking_status', true );
	if ( empty( $booking_object->payments ) ) {
		$show_pay_now_btn = ( $payment_status == 'partially-paid' || $booking_meta['remaining_payment'] > 0 ) && ! empty( $active_payment_methods );
		$total_paid       = wte_array_get( $booking_metas, 'place_order.cost', 0 );
		$due              = wte_array_get( $booking_metas, 'place_order.due', 0 );
		$payment_status   = get_post_meta( $booking, 'wp_travel_engine_booking_payment_status', true );
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

	$booked_trip = is_array( $order_trips ) ? array_shift( $order_trips ) : array();

	if ( empty( $booked_trip ) ) {
		continue;
	}

	$booked_trip            = (object) $booked_trip;
	$active_payment_methods = wp_travel_engine_get_active_payment_gateways();

	if ( ! empty( $booking_metas ) ) {
		if ( ! $payment_status ) {
			$payment_status = __( 'pending', 'wp-travel-engine' );
		}

		$billing_info = $booking_object->billing_info;
		?>
		<div class="wpte-booked-trip-wrap">
			<div class="wpte-booked-trip-image">
				<?php
				if ( has_post_thumbnail( $booked_trip->ID ) ) {
					echo get_the_post_thumbnail( $booked_trip->ID );
				} else {
					?>
					<img alt="<?php the_title(); ?>"  itemprop="image" src="<?php echo esc_url( WP_TRAVEL_ENGINE_IMG_URL . '/public/css/images/single-trip-featured-img.jpg' ); ?>" alt="">
					<?php
				}
				?>
			</div>
			<div class="wpte-booked-trip-content">
				<div class="wpte-booked-trip-description-left">
					<div class="wpte-booked-trip-title">
						<?php echo esc_html( $booked_trip->title ); ?>
					</div>
					<div class="wpte-booked-trip-descriptions">
						<div class="wpte-booked-trip-inner-descriptions-left">
							<ul class="booking-status-info">
								<li>
									<span class="lrf-td-title"><?php esc_html_e( 'Departure:', 'wp-travel-engine' ); ?></span>
									<span class="lrf-td-desc"><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $booked_trip->datetime ) ) ); ?></span>
								</li>
								<li>
									<span class="lrf-td-title"><?php esc_html_e( 'Booking Status:', 'wp-travel-engine' ); ?></span>
									<span class="lrf-td-desc"><?php echo wp_kses( $booking_status, array( 'code' => array() ) ); ?></span>
								</li>
								<li>
									<span class="lrf-td-title"><?php esc_html_e( 'Payment Status:', 'wp-travel-engine' ); ?></span>
									<span class="lrf-td-desc"><?php echo wp_kses( $payment_status, array( 'code' => array() ) ); ?></span>
								</li>
							</ul>
						</div>
						<div class="wpte-booked-trip-inner-descriptions-right">
							<ul class="booking-payment-info">
								<li>
									<span class="lrf-td-title"><?php esc_html_e( 'Total:', 'wp-travel-engine' ); ?></span>
									<span class="lrf-td-desc"><?php echo wte_esc_price( wte_get_formated_price_html( $booking_object->cart_info['total'] ) ); ?></span>
								</li>
								<li>
									<span class="lrf-td-title"><?php esc_html_e( 'Paid:', 'wp-travel-engine' ); ?></span>
									<span class="lrf-td-desc"><?php echo wte_esc_price( wte_get_formated_price_html( $total_paid ) ); ?></span>
								</li>
								<li>
									<span class="lrf-td-title"><?php esc_html_e( 'Due:', 'wp-travel-engine' ); ?></span>
									<span class="lrf-td-desc"><?php echo wte_esc_price( wte_get_formated_price_html( $due ) ); ?></span>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="wpte-booked-trip-buttons-right">
					<a class="wpte-lrf-btn-transparent wpte-detail-btn" href="<?php echo esc_url( get_the_permalink() . '?action=booking-details&booking_id=' . $booking . '"' ); ?>"><?php esc_html_e( 'View Details', 'wp-travel-engine' ); ?></a>
				</div>
			</div>
		</div>
		<?php
	}
	endforeach;
?>
</div>
