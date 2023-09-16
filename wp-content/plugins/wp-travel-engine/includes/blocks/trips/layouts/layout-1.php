<?php
/**
 * Trip Card Layout - 1
 */
list( $settings, $trip, $results, $meta, $is_featured, $wte_global, $details ) = $args;

?>
<div class="category-trips-single" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
	<div class="category-trips-single-inner-wrap">
		<figure class="category-trip-fig">
			<?php if ( wte_array_get( $settings, 'layoutFilters.showFeaturedRibbon', false ) && $is_featured ) : ?>
				<div class="category-feat-ribbon">
					<span class="category-feat-ribbon-txt"><?php esc_html_e( 'Featured', 'wp-travel-engine' ); ?></span>
					<span class="cat-feat-shadow"></span>
				</div>
			<?php endif; ?>
			<?php if ( wte_array_get( $settings, 'layoutFilters.showFeaturedImage', true ) ) : ?>
				<a href="<?php echo esc_url( get_the_permalink( $trip ) ); ?>">
					<?php
					$size = apply_filters( 'wp_travel_engine_archive_trip_feat_img_size', 'trip-single-size' );
					if ( has_post_thumbnail( $trip ) ) :
						echo get_the_post_thumbnail( $trip, $size );
					endif;
					?>
				</a>
			<?php endif; ?>
		</figure>

		<div class="category-trip-content-wrap">
			<div class="category-trip-prc-title-wrap">
				<?php if ( wte_array_get( $settings, 'layoutFilters.showTitle', true ) ) : ?>
					<h2 class="category-trip-title" itemprop="name">
						<a itemprop="url" href="<?php echo esc_url( get_the_permalink( $trip ) ); ?>"><?php echo $trip->post_title; ?></a>
					</h2>
				<?php endif; ?>

				<?php
				if ( wte_array_get( $settings, 'layoutFilters.showReviews', false ) ) :
					echo \wte_get_the_trip_reviews( $trip->ID );
				endif;
				?>

				<?php if ( ! empty( $position ) ) : ?>
					<meta itemprop="position" content="<?php echo esc_attr( $position ); ?>"/>
				<?php endif; ?>
			</div>

			<div class="category-trip-detail-wrap">
				<div class="category-trip-prc-wrap">
					<div class="category-trip-desti">
						<?php
						if ( wte_array_get( $settings, 'layoutFilters.showActivities', false ) ) :
							$terms = wte_get_the_tax_term_list( $trip->ID, 'activities', '', ', ', '' );
							if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
								?>
								<span class="category-trip-types"><i><svg enableBackground="new 0 0 512 512" height="20" viewBox="0 0 512 512" width="20" xmlns="http://www.w3.org/2000/svg"><g><path d="m319.715 89.922c24.803 1.658 46.254-17.104 47.912-41.907s-17.104-46.255-41.907-47.913-46.254 17.104-47.912 41.907c-1.659 24.803 17.104 46.254 41.907 47.913z" /><path d="m180.651 280.102c-2.63-1.1-5.04-2.506-7.207-4.156l-29.212 63.346-21.873-10.087c-8.513-3.926-18.596-.207-22.521 8.306l-57.736 125.2c-3.925 8.513-.207 18.595 8.306 22.521l7.443 3.433c-1.247 1.16-2.297 2.573-3.055 4.216-3.116 6.758-.164 14.762 6.593 17.879 6.758 3.116 14.762.164 17.878-6.594.757-1.642 1.15-3.358 1.223-5.061l7.443 3.433c8.513 3.925 18.596.207 22.521-8.306l57.736-125.201c3.516-7.625.886-16.496-5.816-21.087l30.124-65.324c-3.961-.091-7.971-.898-11.847-2.518z" /><path d="m455.933 193.212-61.003-15.353-27.55-31.735.557-8.329c1.12-16.755-11.554-31.246-28.309-32.366l-54.101-3.618c-16.141-1.079-30.176 10.647-32.185 26.488l12.36-3.551-49.924 26.673c-4.171 2.229-7.446 5.828-9.27 10.192l-32.207 77.031c-4.375 10.465.561 22.494 11.026 26.87 10.398 4.348 22.466-.495 26.87-11.026l29.386-70.285 43.476-23.228-31.897 26.955-4.602 11.007-5.806 86.838.292.019c.027.994 2.936 59.118 4.354 87.639l-48.014 103.551c-5.726 12.348-.357 26.999 11.991 32.724 12.346 5.725 26.998.357 32.724-11.99l50.573-109.07c1.681-3.624 2.455-7.603 2.256-11.593l-4.384-87.951 11.11.743 27.269 81.093 10.35 118.554c1.184 13.572 13.149 23.594 26.694 22.408 13.56-1.183 23.591-13.135 22.408-26.694l-10.605-121.481c-.17-1.944-.57-3.862-1.192-5.712l-21.727-64.61.678.045 5.961-89.15 4.656 5.364c2.755 3.174 6.42 5.427 10.496 6.452l67.266 16.93c10.976 2.766 22.156-3.886 24.928-14.903 2.768-11.002-3.905-22.162-14.905-24.931z" /></g></svg></i>
									<span><?php echo wp_kses_post( $terms ); ?></span>
								</span>
								<?php
							endif;
						endif;

						if ( wte_array_get( $settings, 'layoutFilters.showTripType', false ) ) :
							$terms = wte_get_the_tax_term_list( $trip->ID, 'trip_types', '', ', ', '' );
							if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
								?>
								<span class="category-trip-types"><i><svg enableBackground="new 0 0 512 512" height="20" viewBox="0 0 512 512" width="20" xmlns="http://www.w3.org/2000/svg"><g><path d="m319.715 89.922c24.803 1.658 46.254-17.104 47.912-41.907s-17.104-46.255-41.907-47.913-46.254 17.104-47.912 41.907c-1.659 24.803 17.104 46.254 41.907 47.913z" /><path d="m180.651 280.102c-2.63-1.1-5.04-2.506-7.207-4.156l-29.212 63.346-21.873-10.087c-8.513-3.926-18.596-.207-22.521 8.306l-57.736 125.2c-3.925 8.513-.207 18.595 8.306 22.521l7.443 3.433c-1.247 1.16-2.297 2.573-3.055 4.216-3.116 6.758-.164 14.762 6.593 17.879 6.758 3.116 14.762.164 17.878-6.594.757-1.642 1.15-3.358 1.223-5.061l7.443 3.433c8.513 3.925 18.596.207 22.521-8.306l57.736-125.201c3.516-7.625.886-16.496-5.816-21.087l30.124-65.324c-3.961-.091-7.971-.898-11.847-2.518z" /><path d="m455.933 193.212-61.003-15.353-27.55-31.735.557-8.329c1.12-16.755-11.554-31.246-28.309-32.366l-54.101-3.618c-16.141-1.079-30.176 10.647-32.185 26.488l12.36-3.551-49.924 26.673c-4.171 2.229-7.446 5.828-9.27 10.192l-32.207 77.031c-4.375 10.465.561 22.494 11.026 26.87 10.398 4.348 22.466-.495 26.87-11.026l29.386-70.285 43.476-23.228-31.897 26.955-4.602 11.007-5.806 86.838.292.019c.027.994 2.936 59.118 4.354 87.639l-48.014 103.551c-5.726 12.348-.357 26.999 11.991 32.724 12.346 5.725 26.998.357 32.724-11.99l50.573-109.07c1.681-3.624 2.455-7.603 2.256-11.593l-4.384-87.951 11.11.743 27.269 81.093 10.35 118.554c1.184 13.572 13.149 23.594 26.694 22.408 13.56-1.183 23.591-13.135 22.408-26.694l-10.605-121.481c-.17-1.944-.57-3.862-1.192-5.712l-21.727-64.61.678.045 5.961-89.15 4.656 5.364c2.755 3.174 6.42 5.427 10.496 6.452l67.266 16.93c10.976 2.766 22.156-3.886 24.928-14.903 2.768-11.002-3.905-22.162-14.905-24.931z" /></g></svg></i>
									<span><?php echo wp_kses_post( $terms ); ?></span>
								</span>
								<?php
							endif;
						endif;

						if ( wte_array_get( $settings, 'layoutFilters.showLocation', false ) ) :
							$terms = wte_get_the_tax_term_list( $trip->ID, 'destination', '', ', ', '' );
							if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
								?>
								<span class="category-trip-loc"><i><svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 0C4.4087 0 2.88258 0.632141 1.75736 1.75736C0.632141 2.88258 0 4.4087 0 6C0 10.05 5.2875 14.625 5.5125 14.82C5.64835 14.9362 5.82124 15 6 15C6.17877 15 6.35165 14.9362 6.4875 14.82C6.75 14.625 12 10.05 12 6C12 4.4087 11.3679 2.88258 10.2426 1.75736C9.11742 0.632141 7.5913 0 6 0ZM6 13.2375C4.4025 11.7375 1.5 8.505 1.5 6C1.5 4.80653 1.97411 3.66193 2.81802 2.81802C3.66193 1.97411 4.80653 1.5 6 1.5C7.19347 1.5 8.33807 1.97411 9.18198 2.81802C10.0259 3.66193 10.5 4.80653 10.5 6C10.5 8.505 7.5975 11.745 6 13.2375ZM6 3C5.40666 3 4.82664 3.17595 4.33329 3.50559C3.83994 3.83524 3.45542 4.30377 3.22836 4.85195C3.0013 5.40013 2.94189 6.00333 3.05764 6.58527C3.1734 7.16721 3.45912 7.70176 3.87868 8.12132C4.29824 8.54088 4.83279 8.8266 5.41473 8.94236C5.99667 9.05811 6.59987 8.9987 7.14805 8.77164C7.69623 8.54458 8.16477 8.16006 8.49441 7.66671C8.82405 7.17336 9 6.59334 9 6C9 5.20435 8.68393 4.44129 8.12132 3.87868C7.55871 3.31607 6.79565 3 6 3ZM6 7.5C5.70333 7.5 5.41332 7.41203 5.16665 7.2472C4.91997 7.08238 4.72771 6.84811 4.61418 6.57403C4.50065 6.29994 4.47094 5.99834 4.52882 5.70736C4.5867 5.41639 4.72956 5.14912 4.93934 4.93934C5.14912 4.72956 5.41639 4.5867 5.70737 4.52882C5.99834 4.47094 6.29994 4.50065 6.57403 4.61418C6.84811 4.72771 7.08238 4.91997 7.2472 5.16665C7.41203 5.41332 7.5 5.70333 7.5 6C7.5 6.39782 7.34197 6.77936 7.06066 7.06066C6.77936 7.34196 6.39783 7.5 6 7.5Z" fill="white" /></svg></i>
									<span><?php echo wp_kses_post( $terms ); ?></span>
								</span>
								<?php
							endif;
						endif;
						if ( wte_array_get( $settings, 'layoutFilters.showDuration', false ) && ( ! empty( $meta->duration['days'] ) || ! empty( $meta->duration['days'] ) ) ) :
							wte_get_template(
								'components/content-trip-card-duration.php',
								array(
									'trip_duration_unit'   => $meta->duration['duration_unit'],
									'trip_duration'        => $meta->duration['days'],
									'trip_duration_nights' => $meta->duration['nights'],
									'set_duration_type'    => $details['set_duration_type'],
								)
							);
						endif;
						?>
						<?php if ( wte_array_get( $settings, 'layoutFilters.showGroupSize', false ) && (int) $meta->min_pax ) : ?>
							<span class="wpte-trip-meta category-trip-dur wpte-trip-pax">
								<i>
									<svg
										width="18"
										height="13"
										viewBox="0 0 18 13"
										fill="none"
										xmlns="http://www.w3.org/2000/svg"
									>
										<path
											d="M9.225 6.665C9.62518 6.3186 9.94616 5.89016 10.1662 5.40877C10.3861 4.92737 10.5 4.40428 10.5 3.875C10.5 2.88044 10.1049 1.92661 9.40165 1.22335C8.69839 0.520088 7.74456 0.125 6.75 0.125C5.75544 0.125 4.80161 0.520088 4.09835 1.22335C3.39509 1.92661 3 2.88044 3 3.875C2.99999 4.40428 3.11385 4.92737 3.33384 5.40877C3.55384 5.89016 3.87482 6.3186 4.275 6.665C3.22511 7.14041 2.33435 7.90815 1.70924 8.87641C1.08412 9.84467 0.751104 10.9725 0.75 12.125C0.75 12.3239 0.829018 12.5147 0.96967 12.6553C1.11032 12.796 1.30109 12.875 1.5 12.875C1.69891 12.875 1.88968 12.796 2.03033 12.6553C2.17098 12.5147 2.25 12.3239 2.25 12.125C2.25 10.9315 2.72411 9.78693 3.56802 8.94302C4.41193 8.09911 5.55653 7.625 6.75 7.625C7.94347 7.625 9.08807 8.09911 9.93198 8.94302C10.7759 9.78693 11.25 10.9315 11.25 12.125C11.25 12.3239 11.329 12.5147 11.4697 12.6553C11.6103 12.796 11.8011 12.875 12 12.875C12.1989 12.875 12.3897 12.796 12.5303 12.6553C12.671 12.5147 12.75 12.3239 12.75 12.125C12.7489 10.9725 12.4159 9.84467 11.7908 8.87641C11.1657 7.90815 10.2749 7.14041 9.225 6.665ZM6.75 6.125C6.30499 6.125 5.86998 5.99304 5.49997 5.74581C5.12996 5.49857 4.84157 5.14717 4.67127 4.73604C4.50097 4.3249 4.45642 3.8725 4.54323 3.43605C4.63005 2.99959 4.84434 2.59868 5.15901 2.28401C5.47368 1.96934 5.87459 1.75505 6.31105 1.66823C6.7475 1.58142 7.1999 1.62597 7.61104 1.79627C8.02217 1.96657 8.37357 2.25496 8.62081 2.62497C8.86804 2.99498 9 3.42999 9 3.875C9 4.47174 8.76295 5.04403 8.34099 5.46599C7.91903 5.88795 7.34674 6.125 6.75 6.125ZM14.055 6.365C14.535 5.8245 14.8485 5.15679 14.9579 4.44225C15.0672 3.72772 14.9677 2.99681 14.6713 2.3375C14.375 1.67819 13.8943 1.1186 13.2874 0.726067C12.6804 0.333538 11.9729 0.124807 11.25 0.125C11.0511 0.125 10.8603 0.204018 10.7197 0.34467C10.579 0.485322 10.5 0.676088 10.5 0.875C10.5 1.07391 10.579 1.26468 10.7197 1.40533C10.8603 1.54598 11.0511 1.625 11.25 1.625C11.8467 1.625 12.419 1.86205 12.841 2.28401C13.2629 2.70597 13.5 3.27826 13.5 3.875C13.4989 4.26893 13.3945 4.65568 13.197 4.99657C12.9996 5.33745 12.7162 5.62054 12.375 5.8175C12.2638 5.88164 12.1709 5.97325 12.1053 6.08356C12.0396 6.19386 12.0034 6.31918 12 6.4475C11.9969 6.57482 12.0262 6.70085 12.0852 6.81369C12.1443 6.92654 12.2311 7.02249 12.3375 7.0925L12.63 7.2875L12.7275 7.34C13.6315 7.76879 14.3942 8.44699 14.9257 9.29474C15.4572 10.1425 15.7354 11.1245 15.7275 12.125C15.7275 12.3239 15.8065 12.5147 15.9472 12.6553C16.0878 12.796 16.2786 12.875 16.4775 12.875C16.6764 12.875 16.8672 12.796 17.0078 12.6553C17.1485 12.5147 17.2275 12.3239 17.2275 12.125C17.2336 10.9741 16.9454 9.84069 16.3901 8.83255C15.8348 7.8244 15.031 6.97499 14.055 6.365Z"
											fill="#2183DF"
										/>
									</svg>
								</i>
								<?php printf( '<span>' . __( '%s People', 'wp-travel-engine' ) . '</span>', (int) $meta->max_pax ? esc_html( $meta->min_pax . '-' . $meta->max_pax ) : esc_html( $meta->min_pax ) ); ?>
							</span>
						<?php endif; ?>
					</div>
					<?php if ( wte_array_get( $settings, 'layoutFilters.showPrice', true ) ) : ?>
						<div class="category-trip-budget">
							<?php if ( wte_array_get( $settings, 'layoutFilters.showDiscount', false ) && $meta->discount_percent ) : ?>
								<div class="category-disc-feat-wrap">
									<div class="category-trip-discount">
										<span class="discount-offer">
											<span><?php echo sprintf( __( '%1$s%% ', 'wp-travel-engine' ), (float) $meta->discount_percent ); ?></span>
										<?php esc_html_e( 'Off', 'wp-travel-engine' ); ?></span>
									</div>
								</div>
							<?php endif; ?>
							<span class="price-holder">
								<span class="actual-price"><?php echo wte_esc_price( wte_get_formated_price_html( $meta->has_sale ? $meta->sale_price : $meta->price ) ); ?></span>
								<?php if ( wte_array_get( $settings, 'layoutFilters.showStrikedPrice', true ) && $meta->has_sale ) : ?>
								<span class="striked-price"><?php echo wte_esc_price( wte_get_formated_price_html( $meta->price ) ); ?></span>
								<?php endif; ?>
							</span>
						</div>
					<?php endif; ?>
				</div>
				<?php if ( \wte_array_get( $settings, 'layoutFilters.showDescription', false ) ) : ?>
					<div class="category-trip-desc">
						<p><?php echo esc_html( \wte_get_the_excerpt( $trip->ID, wte_array_get( $settings, 'excerptLength', 10 ) ) ); ?></p>
					</div>
				<?php endif; ?>
				<?php if ( wte_array_get( $settings, 'layoutFilters.showViewMoreButton', true ) && wte_array_get( $settings, 'layout', '' ) == 'grid' ) : ?>
					<a href="<?php echo esc_url( get_the_permalink( $trip->ID ) ); ?>" class="button category-trip-viewmre-btn"><?php echo esc_html( wte_array_get( $settings, 'viewMoreButtonText', __( 'View Details', 'wp-travel-engine' ) ) ); ?></a>
				<?php endif; ?>
			</div>
		</div>

		<?php
		$show_available_months = wte_array_get( $settings, 'layoutFilters.showTripAvailableTime', false );
		if ( $show_available_months || wte_array_get( $settings, 'layoutFilters.showViewMoreButton', true ) ) {

			$layout = wte_array_get( $settings, 'layout', 'list' );
			if ( wte_array_get( $settings, 'layout', '' ) == 'list' ) {
				echo '<div class="category-trip-aval-time">';
			}
			if ( $show_available_months ) {
				$fsds = apply_filters( 'trip_card_fixed_departure_dates', $trip->ID );

				$dates_layout = wte_array_get( $settings, 'datesLayout', 'months_list' );
				if ( $fsds == $trip->ID ) {
					if ( $layout == 'grid' ) {
						echo '<div class="category-trip-aval-time">';
					}
					echo '<div class="category-trip-avl-tip-inner-wrap">';
					echo '<span class="category-available-trip-text"> ' . __( 'Available through out the year:', 'wp-travel-engine' ) . '</span>';
					echo '<ul class="category-available-months">';
					foreach ( range( 1, 12 ) as $month_number ) :
						echo '<li>' . date_i18n( 'M', strtotime( "2021-{$month_number}-01" ) ) . '</li>';
						endforeach;
					echo '</ul></div>';
					if ( $layout == 'grid' ) {
						echo '</div>';
					}
				} elseif ( is_array( $fsds ) && count( $fsds ) > 0 ) {
					if ( $layout == 'grid' ) {
						echo '<div class="category-trip-aval-time">';
					}
					switch ( $dates_layout ) {
						case 'months_list':
							$available_months = array_map(
								function( $fsd ) {
									return date_i18n( 'n', strtotime( $fsd['start_date'] ) );
								},
								$fsds
							);
							$available_months = array_flip( $available_months );

							if ( empty( $available_months ) ) {
								echo '<ul class="category-available-months">';
								foreach ( range( 1, 12 ) as $month_number ) :
									echo '<li>' . date_i18n( 'n-M', strtotime( "2021-{$month_number}-01" ) ) . '</li>';
								endforeach;
								echo '</ul>';
								break;
							}

							$availability_txt     = ! empty( $available_months ) && is_array( $available_months ) ? __( 'Available in the following months:', 'wp-travel-engine' ) : __( 'Available through out the year:', 'wp-travel-engine' );
							$available_throughout = apply_filters( 'wte_available_throughout_txt', $availability_txt );

							echo '<div class="category-trip-avl-tip-inner-wrap">';
							echo '<span class="category-available-trip-text"> ' . esc_html( $available_throughout ) . '</span>';
							$months_list = '';
							echo '<ul class="category-available-months">';
							foreach ( range( 1, 12 ) as $month_number ) {
								isset( $available_months[ $month_number ] ) ? printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( get_the_permalink() ) . '?month=' . esc_html( $available_months[ $month_number ] ) . '#wte-fixed-departure-dates', date_i18n( 'M', strtotime( "2021-{$month_number}-01" ) ) ) : printf( '<li><a href="#" class="disabled">%1$s</a></li>', date_i18n( 'M', strtotime( "2021-{$month_number}-01" ) ) );
							}
							echo '</ul>';
							echo '</div>';
							break;
						case 'dates_list':
							$list_count = wte_array_get( $settings, 'datesCount', 3 );
							$icon       = '<i><svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61"><g id="Group_773" data-name="Group 773" transform="translate(283.072 34.13)"><path id="Path_23383" data-name="Path 23383" d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/><path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/></g></svg></i>';
							echo '<div class="next-trip-info">';
							printf( '<div class="fsd-title">%1$s</div>', esc_html__( 'Next Departure', 'wp-travel-engine' ) );
							echo '<ul class="next-departure-list">';
							foreach ( $fsds as $fsd ) {
								if ( --$list_count < 0 ) {
									break;
								}
								printf(
									'<li><span class="left">%1$s %2$s</span><span class="right">%3$s</span></li>',
									wte_esc_svg( $icon ),
									esc_html( wte_get_formated_date( $fsd['start_date'] ) ),
									sprintf( __( '%s Available', 'wp-travel-engine' ), (float) $fsd['seats_left'] )
								);
							}
							echo '</ul>';
							echo '</div>';
							break;
						default:
							break;
					}
					if ( $layout == 'grid' ) {
						echo '</div>';
					}
				}
			}

			if ( wte_array_get( $settings, 'layoutFilters.showViewMoreButton', true ) && $layout == 'list' ) :
				?>
				<a href="<?php echo esc_url( get_the_permalink( $trip->ID ) ); ?>" class="button category-trip-viewmre-btn"><?php echo esc_html( wte_array_get( $settings, 'viewMoreButtonText', __( 'View Details', 'wp-travel-engine' ) ) ); ?></a>
				<?php
			endif;
			if ( $layout == 'list' ) {
				echo '</div>';
			}
		}
		?>
	</div>
</div>
