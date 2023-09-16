<?php
/**
 * Booking Details Metabox Content.
 */

$booking_details       = null;
$traveller_information = get_post_meta( $post->ID, 'wp_travel_engine_placeorder_setting', true );
$personal_options      = isset( $traveller_information['place_order'] ) ? $traveller_information['place_order'] : array();

extract( $_args );

if ( is_null( $booking_details ) ) {
	return;
}
?>
<div class="wpte-main-wrap wpte-edit-booking">
	<div class="wpte-block-wrap wpte-floated">
		<?php
		foreach ( array( 'trip-info', 'payments', 'customer' ) as $file ) {
			$_args = array( 'booking_details' => $booking_details );
			require plugin_dir_path( __FILE__ ) . "booking-details_{$file}.php";
		}
		?>
	</div> <!-- .wpte-block-wrap -->

	<?php
	if ( isset( $personal_options ) && ! empty( $personal_options ) ) {
		include plugin_dir_path( __FILE__ ) . 'booking-details_personal.php';
	}

	/**
	 * Hooks for Addons.
	 *
	 * @param int $post->ID Post ID.
	 */
	do_action( 'wp_travel_engine_booking_screen_after_personal_details', $post->ID );
	?>
</div><!-- .wpte-main-wrap -->
