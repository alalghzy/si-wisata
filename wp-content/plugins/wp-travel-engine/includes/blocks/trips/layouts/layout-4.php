<?php
/**
 * Trip Card Layout - 4
 */
list( $settings, $trip, $results, $meta, $is_featured, $wte_global, $details ) = $args;

$is_featured = wte_is_trip_featured( $trip->ID );
$meta        = \wte_trip_get_trip_rest_metadata( $trip->ID );
?>
<div class="wpte-trip-single">
	<div class="wpte-trip-image-wrap">
		<?php if ( wte_array_get( $settings, 'layoutFilters.showDiscount', false ) && $meta->discount_percent ) : ?>
		<div class="discount-text-wrap">
			<span class="discount-percent"><?php echo sprintf( __( '%1$s%% Off', 'wp-travel-engine' ), (int) $meta->discount_percent ); ?></span>
		</div>
		<?php endif; ?>
		<?php if ( wte_array_get( $settings, 'layoutFilters.showFeaturedRibbon', false ) && $is_featured ) : ?>
		<div class="featured-text-wrap">
			<span class="featured-icon">
				<svg
					width="14"
					height="14"
					viewBox="0 0 14 14"
					fill="none"
					xmlns="http://www.w3.org/2000/svg"
				>
					<g clip-path="url(#clip0)">
						<path
							d="M13.8081 4.12308C13.6427 3.98191 13.4093 3.95216 13.2137 4.04737L10.2211 5.50424L7.41314 2.26669C7.30929 2.14692 7.15855 2.07812 7.00001 2.07812C6.84147 2.07812 6.69075 2.14692 6.58687 2.26669L3.77888 5.50421L0.786276 4.04734C0.590686 3.95216 0.357334 3.98188 0.191904 4.12305C0.0264748 4.26423 -0.0395877 4.49004 0.0236584 4.69812L2.10178 11.5341C2.17181 11.7644 2.38424 11.9219 2.62501 11.9219H11.375C11.6157 11.9219 11.8282 11.7644 11.8982 11.5341L13.9763 4.69815C14.0396 4.49006 13.9735 4.26426 13.8081 4.12308ZM10.9696 10.8281H3.03032L1.43479 5.57955L3.67758 6.67141C3.90026 6.7798 4.16785 6.72506 4.33008 6.53803L7.00001 3.45967L9.66996 6.53803C9.83216 6.72509 10.0998 6.77977 10.3224 6.67141L12.5652 5.57955L10.9696 10.8281Z"
							fill="white"
						/>
					</g>
					<defs>
						<clipPath id="clip0">
							<rect
								width="14"
								height="14"
								fill="white"
							/>
						</clipPath>
					</defs>
				</svg>
			</span>
			<span class="featured-text"><?php esc_html_e( 'Featured', 'wp-travel-engine' ); ?></span>
		</div>
		<?php endif; ?>
		<?php if ( wte_array_get( $settings, 'layoutFilters.showFeaturedImage', true ) ) : ?>
		<figure class="thumbnail overlay">
			<a href="<?php echo esc_url( get_the_permalink( $trip ) ); ?>">
				<?php
				$size = apply_filters( 'wp_travel_engine_archive_trip_feat_img_size', 'trip-single-size' );
				if ( has_post_thumbnail( $trip ) ) :
					echo get_the_post_thumbnail( $trip, $size );
				endif;
				?>
			</a>
		</figure>
		<?php endif; ?>
	</div>
	<div class="wpte-trip-details-wrap">
		<div class="wpte-trip-header-wrap">
			<?php if ( wte_array_get( $settings, 'layoutFilters.showTitle', true ) ) : ?>
			<div class="wpte-trip-title-wrap">
				<h2 class="wpte-trip-title">
					<a itemprop="url" href="<?php echo esc_url( get_the_permalink( $trip ) ); ?>"><?php echo esc_html($trip->post_title); ?></a>
				</h2>
			</div>
			<?php endif; ?>
			<?php
			if ( wte_array_get( $settings, 'layoutFilters.showReviews', false ) ) :
				echo \wte_get_the_trip_reviews( $trip->ID );
			endif;
			?>
		</div>
		<div class="wpte-trip-meta-list">
			<?php
			if ( wte_array_get( $settings, 'layoutFilters.showLocation', false ) ) :
				$terms = wte_get_the_tax_term_list( $trip->ID, 'destination', '', ', ', '' );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
					?>
					<span class="wpte-trip-meta wpte-trip-destination">
						<i><svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 0C4.4087 0 2.88258 0.632141 1.75736 1.75736C0.632141 2.88258 0 4.4087 0 6C0 10.05 5.2875 14.625 5.5125 14.82C5.64835 14.9362 5.82124 15 6 15C6.17877 15 6.35165 14.9362 6.4875 14.82C6.75 14.625 12 10.05 12 6C12 4.4087 11.3679 2.88258 10.2426 1.75736C9.11742 0.632141 7.5913 0 6 0ZM6 13.2375C4.4025 11.7375 1.5 8.505 1.5 6C1.5 4.80653 1.97411 3.66193 2.81802 2.81802C3.66193 1.97411 4.80653 1.5 6 1.5C7.19347 1.5 8.33807 1.97411 9.18198 2.81802C10.0259 3.66193 10.5 4.80653 10.5 6C10.5 8.505 7.5975 11.745 6 13.2375ZM6 3C5.40666 3 4.82664 3.17595 4.33329 3.50559C3.83994 3.83524 3.45542 4.30377 3.22836 4.85195C3.0013 5.40013 2.94189 6.00333 3.05764 6.58527C3.1734 7.16721 3.45912 7.70176 3.87868 8.12132C4.29824 8.54088 4.83279 8.8266 5.41473 8.94236C5.99667 9.05811 6.59987 8.9987 7.14805 8.77164C7.69623 8.54458 8.16477 8.16006 8.49441 7.66671C8.82405 7.17336 9 6.59334 9 6C9 5.20435 8.68393 4.44129 8.12132 3.87868C7.55871 3.31607 6.79565 3 6 3ZM6 7.5C5.70333 7.5 5.41332 7.41203 5.16665 7.2472C4.91997 7.08238 4.72771 6.84811 4.61418 6.57403C4.50065 6.29994 4.47094 5.99834 4.52882 5.70736C4.5867 5.41639 4.72956 5.14912 4.93934 4.93934C5.14912 4.72956 5.41639 4.5867 5.70737 4.52882C5.99834 4.47094 6.29994 4.50065 6.57403 4.61418C6.84811 4.72771 7.08238 4.91997 7.2472 5.16665C7.41203 5.41332 7.5 5.70333 7.5 6C7.5 6.39782 7.34197 6.77936 7.06066 7.06066C6.77936 7.34196 6.39783 7.5 6 7.5Z" fill="white" /></svg></i>
						<span><?php echo wp_kses_post( $terms ); ?></span>
					</span>
					<?php
				endif;
			endif;
			?>
			<?php if ( wte_array_get( $settings, 'layoutFilters.showDuration', false ) && ( ! empty( $meta->duration['days'] ) || ! empty( $meta->duration['days'] ) ) ) :
				wte_get_template( 'components/content-trip-card-duration.php', [
					'trip_duration_unit' => $meta->duration['duration_unit'],
					'trip_duration' => $meta->duration['days'],
					'trip_duration_nights' => $meta->duration['nights'],
					'set_duration_type' => $details['set_duration_type'],
					'is_block_layout'  => true,
				] );
			endif; ?>

			<?php if ( wte_array_get( $settings, 'layoutFilters.showGroupSize', false ) && (int) $meta->min_pax ) : ?>
			<span class="wpte-trip-meta wpte-trip-pax">
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
				<?php printf( __( '%s People', 'wp-travel-engine' ), (int) $meta->max_pax ? esc_html( $meta->min_pax . '-' . $meta->max_pax ) : $meta->min_pax ); ?>
			</span>
			<?php endif; ?>
		</div>
		<div class="wpte-trip-budget-wrap justify-content-between align-items-center">
			<?php if ( wte_array_get( $settings, 'layoutFilters.showPrice', true ) ) : ?>
			<div class="wpte-trip-price-wrap">
				<?php if ( wte_array_get( $settings, 'layoutFilters.showStrikedPrice', true ) && $meta->has_sale ) : ?>
					<del><?php echo wte_esc_price( wte_get_formated_price_html( $meta->price ) ); ?></del>
				<?php endif; ?>
				<ins><?php echo wte_esc_price( wte_get_formated_price_html( $meta->has_sale ? $meta->sale_price : $meta->price ) ); ?></ins>
			</div>
			<?php endif; ?>
			<?php if ( wte_array_get( $settings, 'layoutFilters.showViewMoreButton', true ) ) : ?>
			<div class="wpte-trip-btn-wrap">
				<a href="<?php echo esc_url( get_the_permalink( $trip->ID ) ); ?>" class="wpte-trip-explore-btn"><?php echo esc_html( wte_array_get( $settings, 'viewMoreButtonText', __( 'View Details', 'wp-travel-engine' ) ) ); ?></a>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
