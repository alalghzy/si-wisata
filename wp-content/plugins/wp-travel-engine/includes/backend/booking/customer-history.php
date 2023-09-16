<div class="customer-history-wrap">
	<?php
	global $post;
	$wp_travel_engine_postmeta_settings = get_post_meta( $post->ID, 'wp_travel_engine_booking_setting', true );

	$my_bookings                      = get_post_meta( $post->ID, 'wp_travel_engine_bookings', true );
	$wp_travel_engine_booked_settings = get_post_meta( $post->ID, 'wp_travel_engine_booked_trip_setting', true );

	$size = sizeof( $wp_travel_engine_booked_settings['traveler'] );
	?>

	<table id="book-list-table">
		<thead>
			<tr>
				<th>S.N</th>
				<th><?php esc_html_e( 'Bookings: ', 'wp-travel-engine' ); ?></th>
				<th><?php esc_html_e( 'Booked On: ', 'wp-travel-engine' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			foreach ( $my_bookings as $key => $value ) {
				?>
				<tr><td><?php echo esc_html( $i++ . '. ' ); ?></td>
				<td><a target="_blank" href="<?php echo esc_url( get_edit_post_link( $value, 'display' ) ); ?>"><?php echo esc_attr( get_the_title( $value ) ); ?></a></td>
				<td><?php echo esc_attr( get_the_time( 'Y-m-d', $value ) ); ?></td></tr>
			<?php } ?>
		</tbody>
	</table>
</div>
