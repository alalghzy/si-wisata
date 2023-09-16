<?php
global $post;
$wp_travel_engine_tabs = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
?>
<ul id="itinerary-list">
	<?php
	if ( isset( $wp_travel_engine_tabs['itinerary'] ) && ! empty( $wp_travel_engine_tabs['itinerary']['itinerary_title'] ) ) {
		$maxlen   = max( array_keys( $wp_travel_engine_tabs['itinerary']['itinerary_title'] ) );
		$arr_keys = array_keys( $wp_travel_engine_tabs['itinerary']['itinerary_title'] );
		$i        = 1;

		foreach ( $arr_keys as $key => $value ) {
			if ( array_key_exists( $value, $wp_travel_engine_tabs['itinerary']['itinerary_title'] ) ) {
				?>
				<li id="itinerary-tabs<?php echo esc_attr( $value ); ?>" data-id="<?php echo esc_attr( $value ); ?>" class="itinerary-row">
					<span class="tabs-handle"><span></span></span>
					<i class="dashicons dashicons-no-alt delete-faq delete-icon" data-id="<?php echo esc_attr( $value ); ?>"></i>
					<div class="itinerary-holder">
						<a class="accordion-tabs-toggle" href="javascript:void(0);"><span class="faq-count">
						<?php
						esc_html_e( 'Day-', 'wp-travel-engine' );
						echo esc_attr( $i );
						?>
								</span></a>
						<div class="itinerary-content">
							<div class="title">
								<input placeholder="<?php esc_html_e( 'Itinerary Title:', 'wp-travel-engine' ); ?>" type="text" class="itinerary-title" name="wp_travel_engine_setting[itinerary][itinerary_title][<?php echo esc_attr( $value ); ?>]" id="wp_travel_engine_setting[itinerary][itinerary_title][<?php echo esc_attr( $value ); ?>]" value="<?php echo ( isset( $wp_travel_engine_tabs['itinerary']['itinerary_title'][ $value ] ) ? esc_attr( $wp_travel_engine_tabs['itinerary']['itinerary_title'][ $value ] ) : '' ); ?>">
							</div>
							<div class="content">
								<textarea placeholder="<?php esc_html_e( 'Itinerary Content:', 'wp-travel-engine' ); ?>" rows="5" cols="32" class="itinerary-content" name="wp_travel_engine_setting[itinerary][itinerary_content][<?php echo esc_attr( $value ); ?>]" id="wp_travel_engine_setting[itinerary][itinerary_content][<?php echo esc_attr( $value ); ?>]"><?php echo ( isset( $wp_travel_engine_tabs['itinerary']['itinerary_content'][ $value ] ) ? esc_attr( $wp_travel_engine_tabs['itinerary']['itinerary_content'][ $value ] ) : '' ); ?></textarea>
								<textarea rows="5" cols="32" class="itinerary-content-inner" name="wp_travel_engine_setting[itinerary][itinerary_content_inner][<?php echo esc_attr( $value ); ?>]" id="wp_travel_engine_setting[itinerary][itinerary_content_inner][<?php echo esc_attr( $value ); ?>]"><?php echo ( isset( $wp_travel_engine_tabs['itinerary']['itinerary_content_inner'][ $value ] ) ? esc_attr( $wp_travel_engine_tabs['itinerary']['itinerary_content_inner'][ $value ] ) : '' ); ?></textarea>
							</div>
						</div>
					</div>
				</li>
				<?php
			}
			$i++;
		}
	}
	?>
</ul>
<span id="itinerary-holder"></span>
<input type="button" class="button button-small add-itinerary" value="<?php esc_html_e( 'Add Itinerary', 'wp-travel-engine' ); ?>">
