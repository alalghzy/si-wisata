<?php
/**
 * Trip Search Form Template.
 * Content for Wte_Advanced_Search_Form Shortcode.
 */
wp_enqueue_script( "wte-custom-niceselect" );

$options = get_option( 'wp_travel_engine_settings', array() );
if ( ! empty( $options['pages']['search'] ) ) {
	$pid   = $options['pages']['search'];
	$nonce = wp_create_nonce( 'search-nonce' );

	// $max_duration = wte_advanced_search_cost_and_duration( 'duration', 'max' );
	// $max_cost     = wte_advanced_search_cost_and_duration( 'cost', 'max' );

	$duration_range = \WPTravelEngine\Modules\TripSearch::get_duration_range();
	$max_duration   = (int) $duration_range->max_duration;
	$min_duration   = (int) $duration_range->min_duration;

	$price_range  = \WPTravelEngine\Modules\TripSearch::get_price_range();
	$max_cost     = $price_range->max_price;
	$min_cost_val     = $price_range->min_price;
	$max_cost_val = apply_filters( 'wte_price_cost_reverse', $max_cost );


	// Search label filters.
	$destination_lbl = apply_filters( 'wte_search_destination_label', __( 'Destination', 'wp-travel-engine' ) );
	$activities_lbl  = apply_filters( 'wte_search_activities_label', __( 'Activities', 'wp-travel-engine' ) );
	$duration_lbl    = apply_filters( 'wte_search_duration_label', __( 'Duration', 'wp-travel-engine' ) );
	$budget_lbl      = apply_filters( 'wte_search_budget_label', __( 'Budget', 'wp-travel-engine' ) );

	?>
	<h3>
	<?php
	$search_title = __( 'Search for a trip', 'wp-travel-engine' );
	echo esc_html( apply_filters( 'wte_advanced_search_title', $search_title ) );
	?>
	</h3>
	<form method="get" action='<?php echo esc_url( get_permalink( $pid ) ); ?>' id="wte-advanced-search-form-shortcode">
		<div class="class-wte-advanced-search-wrapper wte-advanced-search-wrapper-nice-select">
			<input type="hidden" name="search-nonce" value="<?php echo esc_attr( $nonce ); ?>">
			<?php
			$categories = get_categories( 'taxonomy=destination' );
			$taxonomy   = 'destination';
			/** Get only parent taxonomy terms */
			$terms = get_terms(
				$taxonomy,
				array(
					'hide_empty' => 1,
					'parent'     => 0,
				)
			);

			$select  = "<div class='advanced-search-field trip-destination'><h3>" . $destination_lbl . "</h3><div class='custom-select'><select name='destination' id='destination' class='postform'>";
			$select .= "<option value='-1'>" . $destination_lbl . '</option>';
			if ( sizeof( $categories ) > 0 ) {
				foreach ( $terms as $term ) {
					$select .= '<option value="' . $term->slug . '">' . $term->name . '</option>';

					/** Check if term has children */
					$childterms = get_terms(
						$taxonomy,
						array(
							'hide_empty' => 1,
							'orderby'    => 'slug',
							'parent'     => $term->term_id,
						)
					);
					if ( $childterms ) :
						foreach ( $childterms as $childterm ) {
							$select .= "<option value='" . $childterm->slug . "'>&nbsp;&nbsp;&nbsp;" . $childterm->name . '</option>';

							/** Check if childterm has grand children */
							$grandterms = get_terms(
								$taxonomy,
								array(
									'hide_empty' => 1,
									'orderby'    => 'slug',
									'parent'     => $childterm->term_id,
								)
							);
							if ( $grandterms ) :
								foreach ( $grandterms as $grandterm ) {
									$select .= "<option value='" . $grandterm->slug . "'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $grandterm->name . '</option>';

									/** Check if grand child term has great grand children */
									$greatterms = get_terms(
										$taxonomy,
										array(
											'hide_empty' => 1,
											'orderby'    => 'slug',
											'parent'     => $grandterm->term_id,
										)
									);
									if ( $greatterms ) :
										foreach ( $greatterms as $greatterm ) {
															$select .= "<option value='" . $greatterm->slug . "'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $greatterm->name . '</option>';
										}
											endif;
								}
					endif;
						}
			endif;
				}
			}
			$select .= '</select></div></div>';

			if ( ! isset( $options['trip_search']['destination'] ) || '0' === $options['trip_search']['destination'] ) {
				echo $select; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			$categories = get_categories( 'taxonomy=activities' );
			$taxonomy   = 'activities';
			/** Get only parent taxonomy terms */
			$terms   = get_terms(
				$taxonomy,
				array(
					'hide_empty' => 1,
					'parent'     => 0,
				)
			);
			$select  = "<div class='advanced-search-field trip-activities'><h3>" . $activities_lbl . "</h3><div class='custom-select'><select name='activities' id='activities' class='postform'>";
			$select .= "<option value='-1'>" . $activities_lbl . '</option>';

			if ( sizeof( $categories ) > 0 ) {
				foreach ( $terms as $term ) {
					$select .= '<option value="' . $term->slug . '">' . $term->name . '</option>';

					/** Check if term has children */
					$childterms = get_terms(
						$taxonomy,
						array(
							'hide_empty' => 1,
							'orderby'    => 'slug',
							'parent'     => $term->term_id,
						)
					);
					if ( $childterms ) :
						foreach ( $childterms as $childterm ) {
							$select .= "<option value='" . $childterm->slug . "'>&nbsp;&nbsp;&nbsp;" . $childterm->name . '</option>';

							/** Check if childterm has grand children */
							$grandterms = get_terms(
								$taxonomy,
								array(
									'hide_empty' => 1,
									'orderby'    => 'slug',
									'parent'     => $childterm->term_id,
								)
							);
							if ( $grandterms ) :
								foreach ( $grandterms as $grandterm ) {
											$select .= "<option value='" . $grandterm->slug . "'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $grandterm->name . '</option>';

											/** Check if grand child term has great grand children */
											$greatterms = get_terms(
												$taxonomy,
												array(
													'hide_empty' => 1,
													'orderby'    => 'slug',
													'parent'     => $grandterm->term_id,
												)
											);
									if ( $greatterms ) :
										foreach ( $greatterms as $greatterm ) {
											$select .= "<option value='" . $greatterm->slug . "'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $greatterm->name . '</option>';
										}
									endif;
								}
							endif;
						}
					endif;
				}
			}

			$select .= '</select></div></div>';
			if ( ! isset( $options['trip_search']['activities'] ) || '0' === $options['trip_search']['activities'] ) {
				echo $select; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			if ( ! isset( $options['trip_search']['duration'] ) || '0' === $options['trip_search']['duration'] ) :
			?>
			<div class="advanced-search-field trip-duration">
				<h3><?php echo esc_html( $duration_lbl ); ?></h3>
				<strong data-search-duration-value><?php echo esc_html( $duration_lbl ); ?></strong>
				<div class="advanced-search-field search-dur">
					<div data-suffix="<?php echo __( 'Days', 'wp-travel-engine' ); ?>" data-range='<?php echo wp_json_encode( $duration_range );?>' data-target-show="[data-search-duration-value]" id="sform-duration-slider-range"></div>
					<div class="duration-slider-value">
						<span class="min-duration" id="min-duration" name="min-duration"><?php echo sprintf( __( '%d Days', 'wp-travel-engine' ), round( $min_duration ) ); ?></span>
						<span class="max-duration" id="max-duration" name="max-duration"><?php echo sprintf( __( '%d Days', 'wp-travel-engine' ), round( $max_duration ) ); ?></span>
					</div>
				</div>
				<input type="hidden" id="min-duration-value" value="0" name="min-duration">
				<input type="hidden" id="max-duration-value" name="max-duration" value="<?php echo (int) $max_duration; ?>">
			</div>
			<?php
			endif;

			if ( ! isset( $options['trip_search']['budget'] ) || '0' === $options['trip_search']['budget'] ) :
			?>
			<div class="advanced-search-field trip-cost">
				<h3><?php echo esc_html( $budget_lbl ); ?></h3>
				<strong data-search-cost-value><?php echo esc_html( $budget_lbl ); ?></strong>
				<div class="advanced-search-field search-price">
					<div data-range='<?php echo wp_json_encode( $cost_range );?>' data-target-show="[data-search-cost-value]" id="sform-cost-slider-range"></div>
					<div class="cost-slider-value">
						<span class="min-cost" id="min-cost" name="min-cost">0</span>
						<span class="max-cost" id="max-cost" name="max-cost"><?php echo $max_cost; ?></span>
					</div>
				</div>
				<input type="hidden" id="min-cost-value" name="min-cost" value="<?php echo (float) $min_cost_val; ?>">
				<input type="hidden" id="max-cost-value" name="max-cost" value="<?php echo (float) $max_cost_val; ?>">
			</div>
			<?php
			endif;
			printf(
				'<div class="advanced-search-field-submit"><input type="submit" name="search" value="%1$s"/></div>',
				apply_filters( 'wte_search_label', __( 'Search', 'wp-travel-engine' ) )
			);
			?>
		</div>
	</form>
	<?php
} // endif;
