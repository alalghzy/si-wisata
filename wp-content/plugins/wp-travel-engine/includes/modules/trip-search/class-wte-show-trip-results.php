<?php
/**
 * Class WTE_Show_Trip_Results
 */

if ( ! class_exists( 'WTE_Show_Trip_Results' ) ) {
	class WTE_Show_Trip_Results {

		// pagination function for search page
		public function search_navigation( $paged, $pages, $range, $showitems ) {
			echo '<nav class="navigation pagination" style="clear:both;" role="navigation"><h2 class="screen-reader-text">Posts navigation</h2><div class="nav-links">';
			if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
				echo "<a href='" . get_pagenum_link( 1 ) . "'>" . __( 'First', 'wp-travel-engine' ) . '</a>';
			}
			if ( $paged > 1 && $showitems < $pages ) {
				echo "<a class='prev page-numbers' href='" . get_pagenum_link( $paged - 1 ) . "'>" . __( 'Previous', 'wp-travel-engine' ) . '</a>';
			}

			for ( $i = 1; $i <= $pages; $i++ ) {
				if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
					echo ( $paged == $i ) ? '<span aria-current="page" class="page-numbers current"><span class="meta-nav screen-reader-text"></span>' . $i . '</span>' : "<a class='page-numbers' href='" . get_pagenum_link( $i ) . "' class=\"inactive\">" . $i . '</a>';
				}
			}

			if ( $paged < $pages && $showitems < $pages ) {
				echo "<a class='next page-numbers' href=\"" . get_pagenum_link( $paged + 1 ) . '">' . __( 'Next', 'wp-travel-engine' ) . '</a>';
			}
			if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
				echo "<a href='" . get_pagenum_link( $pages ) . "'>" . __( 'Last', 'wp-travel-engine' ) . '</a>';
			}
			echo "</div></nav>\n";
		}

		// function to show search result as well as filters
		public function wte_show_result() {
			get_header(); ?>
			<div id="primary" class="content-area">
				<div class="wp-travel-engine-archive-outer-wrap">
					<?php
						/**
						 * wp_travel_engine_archive_sidebar hook
						 *
						 * @hooked wte_advanced_search_archive_sidebar
						 */
						do_action( 'wp_travel_engine_archive_sidebar' );
					?>
					<div class="wp-travel-engine-archive-repeater-wrap">
						<?php
						$nonce = wp_create_nonce( 'search-nonce' );

						$min_cost     = wte_advanced_search_cost_and_duration( 'cost', 'min' );
						$min_duration = wte_advanced_search_cost_and_duration( 'duration', 'min' );
						$max_cost     = wte_advanced_search_cost_and_duration( 'cost', 'max' );
						$max_duration = wte_advanced_search_cost_and_duration( 'duration', 'max' );

						// phpcs:disable
						$min_cost     = isset( $_GET['min-cost'] ) && '' !== $_GET['min-cost'] ? intval( $_GET['min-cost'] ) : $min_cost;
						$min_duration = isset( $_GET['min-duration'] ) && '' !== $_GET['min-duration'] ? intval( $_GET['min-duration'] ) : $min_duration;
						$max_cost     = isset( $_GET['max-cost'] ) && '' !== $_GET['max-cost'] ? intval( $_GET['max-cost'] ) : $max_cost;
						$max_duration = isset( $_GET['max-duration'] ) && '' !== $_GET['max-duration'] ? intval( $_GET['max-duration'] ) : $max_duration;

						$msg = __( 'No results found!', 'wp-travel-engine' );

						if ( isset( $_GET['cat'] ) && ! empty( $_GET['cat'] ) ) {
							$cat = wte_clean( wp_unslash( $_GET['cat'] ) );
						}

						$budget = '';

						if ( isset( $_GET['budget'] ) && ! empty( $_GET['budget'] ) ) {
							$budget = wte_clean( wp_unslash( $_GET['budget'] ) );
						}

						if ( ! empty( $_GET['activities'] ) ) {
							$activities = wte_clean( wp_unslash( $_GET['activities'] ) );
						}

						if ( ! empty( $_GET['destination'] ) ) {
							$destination = wte_clean( wp_unslash( $_GET['destination'] ) );
						}
						// phpcs:enable

						$paged                  = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
						$default_posts_per_page = get_option( 'posts_per_page' );

						// Query arguments.
						$args = array(
							'post_type'                => 'trip',
							'post_status'              => 'publish',
							'posts_per_page'           => $default_posts_per_page,
							'wpse_search_or_tax_query' => true,
							'paged'                    => $paged,
						);

						$taxquery   = array();
						$meta_query = array();

						if ( ! empty( $cat ) && $cat != -1 ) {
							array_push(
								$taxquery,
								array(
									'taxonomy'         => 'trip_types',
									'field'            => 'slug',
									'terms'            => $cat,
									'include_children' => true,
								)
							);
						}

						if ( ! empty( $budget ) && $budget != -1 ) {
							array_push(
								$taxquery,
								array(
									'taxonomy'         => 'budget',
									'field'            => 'slug',
									'terms'            => $budget,
									'include_children' => false,
								)
							);
						}

						if ( ! empty( $activities ) && $activities != -1 ) {
							array_push(
								$taxquery,
								array(
									'taxonomy'         => 'activities',
									'field'            => 'slug',
									'terms'            => $activities,
									'include_children' => true,
								)
							);
						}

						if ( ! empty( $destination ) && $destination != -1 ) {
							array_push(
								$taxquery,
								array(
									'taxonomy'         => 'destination',
									'field'            => 'slug',
									'terms'            => $destination,
									'include_children' => true,
								)
							);
						}
						if ( ! empty( $taxquery ) ) {
							$args['tax_query'] = $taxquery;
						}

						if ( isset( $max_cost ) && 0 != $max_cost ) {
							array_push(
								$meta_query,
								array(
									'key'     => apply_filters( 'wpte_advance_search_price_filter', 'wp_travel_engine_setting_trip_actual_price' ),
									'value'   => array( $min_cost, $max_cost ),
									'compare' => 'BETWEEN',
									'type'    => 'NUMERIC',
								)
							);
						}

						if ( isset( $max_duration ) && 0 != $max_duration ) {
							array_push(
								$meta_query,
								array(
									'key'     => 'wp_travel_engine_setting_trip_duration',
									'value'   => array( $min_duration, $max_duration ),
									'compare' => 'BETWEEN',
									'type'    => 'NUMERIC',
								)
							);
						}

						// phpcs:disable
						if ( isset( $_GET['trip-date-select'] ) && $_GET['trip-date-select'] != '' ) {
							$date                            = wte_clean( wp_unslash( $_GET['trip-date-select'] ) );
							$arr                             = array();
							$arr['departure_dates']['sdate'] = $date;
							array_push(
								$meta_query,
								array(
									'key'     => 'wte_fsd_dates_starts',
									'value'   => $arr['departure_dates']['sdate'],
									'compare' => 'LIKE',
								)
							);
						}

						if ( ! empty( $meta_query ) ) {
								$args['meta_query'] = $meta_query;
						}

						if ( isset( $_REQUEST['wte_orderby'] ) && ! empty( $_REQUEST['wte_orderby'] ) ) {
							$sortby_val = isset( $_REQUEST['wte_orderby'] ) && ! empty( $_REQUEST['wte_orderby'] ) ? wte_clean( wp_unslash( $_REQUEST['wte_orderby'] ) ) : 'menu_order';
							$sort_args  = wte_advanced_search_get_order_args( $sortby_val );
							$args       = array_merge( $args, $sort_args );
						}
						$query = new WP_Query( $args );
						?>
						<?php
						$view_mode = wp_travel_engine_get_archive_view_mode();
						$orderby   = isset( $_GET['wte_orderby'] ) && ! empty( $_GET['wte_orderby'] ) ? wte_clean( wp_unslash( $_GET['wte_orderby'] ) ) : '';
						?>
							<div class="wp-travel-toolbar clearfix">
								<div class="wte-filter-foundposts"></div>
								<div class="wp-travel-engine-toolbar wte-view-modes">
									<?php
										$current_url = '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
										// phpcs:enable
									?>
									<span><?php esc_html_e( 'View by :', 'wp-travel-engine' ); ?></span>
									<ul class="wte-view-mode-selection-lists">
										<li class="wte-view-mode-selection <?php echo ( 'grid' === $view_mode ) ? 'active' : ''; ?>" data-mode="grid" >
											<a href="<?php echo esc_url( add_query_arg( 'view_mode', 'grid', $current_url ) ); ?>">
												<?php wptravelengine_svg_by_fa_icon( "fas fa-th" ); ?>
											</a>
										</li>
										<li class="wte-view-mode-selection <?php echo ( 'list' === $view_mode ) ? 'active' : ''; ?>" data-mode="list" >
											<a href="<?php echo esc_url( add_query_arg( 'view_mode', 'list', $current_url ) ); ?>">
												<?php wptravelengine_svg_by_fa_icon( "fas fa-list" ); ?>
											</a>
										</li>
									</ul>
								</div>
								<div class="wp-travel-engine-toolbar wte-filterby-dropdown">
									<?php
										$wte_sorting_options = apply_filters(
											'wp_travel_engine_archive_header_sorting_options',
											array(
												''       => __( 'Default Sorting', 'wp-travel-engine' ),
												'latest' => __( 'Latest', 'wp-travel-engine' ),
												'rating' => __( 'Most Reviewed', 'wp-travel-engine' ),
												'price'  => __( 'Price: low to high', 'wp-travel-engine' ),
												'price-desc' => __( 'Price: high to low', 'wp-travel-engine' ),
												'days'   => __( 'Days: low to high', 'wp-travel-engine' ),
												'days-desc' => __( 'Days: high to low', 'wp-travel-engine' ),
												'name'   => __( 'Name in Ascending', 'wp-travel-engine' ),
												'name-desc' => __( 'Name in Descending', 'wp-travel-engine' ),
											)
										);
									?>
									<form class="wte-ordering" method="get">
										<span><?php esc_html_e( 'List by :', 'wp-travel-engine' ); ?></span>
										<select name="wte_orderby" class="orderby" aria-label="<?php esc_attr_e( 'Trip order', 'wp-travel-engine' ); ?>">
											<?php foreach ( $wte_sorting_options as $id => $name ) : ?>
											<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
											<?php endforeach; ?>
										</select>
										<input type="hidden" name="paged" value="1" />
											<?php wte_query_string_form_fields( null, array( 'wte_orderby', 'submit', 'paged' ) ); ?>
									</form>
								</div>
							</div>
						<div class="wte-category-outer-wrap">
							<?php
							$j          = 1;
							$view_mode  = wp_travel_engine_get_archive_view_mode();
							$classes    = apply_filters( 'wte_advanced_search_trip_results_grid_classes', 'col-2 category-grid' );
							$view_class = 'grid' === $view_mode ? $classes : 'category-list';

							echo '<div class="category-main-wrap ' . esc_attr( $view_class ) . '">';
							$user_wishlists = wptravelengine_user_wishlists();

							while ( $query->have_posts() ) {
								$query->the_post();
								$details      = wte_get_trip_details( get_the_ID() );
								$details['j'] = $j;
								$details['user_wishlists'] = $user_wishlists;

								wte_get_template( 'content-' . $view_mode . '.php', $details );
								$j++;
							}
								wp_reset_postdata();
							echo '</div>';

							$total_pages = $query->max_num_pages;
							$big         = 999999999; // need an unlikely integer

							if ( $total_pages > 1 ) {
								$current_page = max( 1, get_query_var( 'paged' ) );
								echo '<div class="trip-pagination pagination">';
								echo '<div class="nav-links">';
								echo paginate_links(
									array(
										'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
										'format'    => '?paged=%#%',
										'current'   => $current_page,
										'total'     => $total_pages,
										'prev_text' => __( 'Previous', 'wp-travel-engine' ),
										'next_text' => __( 'Next', 'wp-travel-engine' ),
										'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'wp-travel-engine' ) . ' </span>',
									)
								);
								echo '</div>';
								echo '</div>';
							}
							?>
						</div>
						<div id="loader" style="display: none">
							<div class="table">
								<div class="table-grid">
									<div class="table-cell">
										<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="search-nonce" id="search-nonce" value="<?php echo $nonce; ?>">
					</div>
				</div>
			</div>
			<?php
			get_footer();
		}
	}
}
