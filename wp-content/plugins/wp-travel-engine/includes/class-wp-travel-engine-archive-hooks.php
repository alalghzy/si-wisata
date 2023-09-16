<?php
/**
 *
 * This class defines all hooks for archive page of the trip.
 *
 * @since      1.0.0
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 * @author     WP Travel Engine <https://wptravelengine.com/>
 */
/**
 *
 */
class Wp_Travel_Engine_Archive_Hooks {

	function __construct() {
		add_action( 'wp_travel_engine_trip_archive_outer_wrapper', array( $this, 'wp_travel_engine_trip_archive_wrapper' ) );
		add_action( 'wp_travel_engine_trip_archive_wrap', array( $this, 'wp_travel_engine_trip_archive_wrap' ) );
		add_action( 'wp_travel_engine_trip_archive_outer_wrapper_close', array( $this, 'wp_travel_engine_trip_archive_outer_wrapper_close' ) );
		add_action( 'wp_travel_engine_header_filters', array( $this, 'wp_travel_engine_header_filters_template' ) );
		add_action( 'wp_travel_engine_archive_header_block', array( $this, 'wp_travel_engine_archive_header_block' ) );
		add_action( 'wp_travel_engine_featured_trips_sticky', array( $this, 'wte_featured_trips_sticky' ), 10, 1 );

		add_action( 'pre_get_posts', array( $this, 'archive_pre_get_posts' ), 11 );
	}

	/**
	 *
	 * @since
	 */
	public static function archive_view_mode() {
		// phpcs:disable
		if ( isset( $_GET['view_mode'] ) && ( 'grid' === $_GET['view_mode'] || 'list' === $_GET['view_mode'] ) ) {
			$view_mode = wte_clean( wp_unslash( $_GET['view_mode'] ) );
		} else {
			$view_mode = apply_filters( 'wp_travel_engine_default_archive_view_mode', get_option( 'wptravelengine_trip_view_mode', 'list' ) );
		}
		return $view_mode;
		// phpcs:enable
	}

	/**
	 *
	 * @since 5.5.7
	 */
	public static function archive_sort_by() {
		if ( ! empty( $_GET['wte_orderby'] ) ) {
			return $_GET['wte_orderby'];
		} else {
			return get_option( 'wptravelengine_trip_sort_by', 'latest' );
		}
	}

	/**
	 *
	 * @since 5.5.7
	 */
	public static function get_query_args_by_sort( $sort_by ) {
		$sort_args = array();
		switch ( $sort_by ) {
			case 'latest':
				$sort_args['order']   = 'DESC';
				$sort_args['orderby'] = 'date';
				break;
			case 'rating':
				$sort_args['order']   = 'DESC';
				$sort_args['orderby'] = 'comment_count';
				break;
			case 'price':
				$sort_args['meta_key'] = '_s_price';
				$sort_args['order']    = 'ASC';
				$sort_args['orderby']  = 'meta_value_num';
				break;
			case 'price-desc':
				$sort_args['meta_key'] = '_s_price';
				$sort_args['order']    = 'DESC';
				$sort_args['orderby']  = 'meta_value_num';
				break;
			case 'days':
				$sort_args['meta_key'] = '_s_duration';
				$sort_args['order']    = 'ASC';
				$sort_args['orderby']  = 'meta_value_num';
				break;
			case 'days-desc':
				$sort_args['meta_key'] = '_s_duration';
				$sort_args['order']    = 'DESC';
				$sort_args['orderby']  = 'meta_value_num';
				break;
			case 'name':
				$sort_args['order']   = 'ASC';
				$sort_args['orderby'] = 'title';
				break;
			case 'name-desc':
				$sort_args['order']   = 'DESC';
				$sort_args['orderby'] = 'title';
				break;
		}

		return $sort_args;
	}

	/**
	 *
	 * @since 5.5.7
	 */
	public function show_featured_trips_on_top() {
		$settings = get_option( 'wp_travel_engine_settings', true );

		return ! isset( $settings['show_featured_trips_on_top'] ) || 'yes' === $settings['show_featured_trips_on_top'];

	}

	/**
	 * Filtes Query on archive page to sort and list trips properly.
	 *
	 * @since 5.5.7
	 */
	public function archive_pre_get_posts( $query ) {
		if ( ! is_admin() && $query->is_main_query() ) {
			if ( $query->is_post_type_archive( WP_TRAVEL_ENGINE_POST_TYPE ) || $query->is_tax ) {
				if ( $query->is_tax ) {
					$queried_object = $query->get_queried_object();
					$taxonomies = wptravelengine_get_trip_taxonomies();
					if ( ! isset( $taxonomies[ $queried_object->taxonomy ] ) ) {
						return;
					}
				}
			} else {
				return;
			}

			/**
			 * Exclude featured trips on Post Archive only because featured trips queried separately.
			 */
			if ( $query->is_post_type_archive() && $this->show_featured_trips_on_top() ) {
				$featured_trips = self::get_featured_trips_ids();

				if ( is_array( $featured_trips ) ) {
					$query->set( 'post__not_in', $featured_trips );
				}
			}

			$sort_by   = self::archive_sort_by();
			$sort_args = self::get_query_args_by_sort( $sort_by );
			if ( isset( $sort_args['order'] ) ) {
				$query->set( 'order', $sort_args['order'] );
			}
			if ( isset( $sort_args['meta_key'] ) ) {
				$query->set( 'meta_key', $sort_args['meta_key'] );
			}
			if ( isset( $sort_args['orderby'] ) ) {
				$query->set( 'orderby', $sort_args['orderby'] );
			}
		}
	}

	/**
	 *
	 * @since 5.5.7
	 */
	public static function get_featured_trips_ids() {
		global $wpdb;

		$featured_trips = wp_cache_get( 'featured_trip_ids', 'wptravelengine' );

		if ( !! $featured_trips ) {
			return $featured_trips;
		}

		$wte_global    = get_option( 'wp_travel_engine_settings', true );
		$feat_trip_num = isset( $wte_global['feat_trip_num'] ) ? (int) $wte_global['feat_trip_num'] : 2;

		$results = $wpdb->get_col( "SELECT `post_id` FROM {$wpdb->postmeta} WHERE `meta_key` = 'wp_travel_engine_featured_trip' AND `meta_value` = 'yes' LIMIT {$feat_trip_num}" );

		wp_cache_add( 'featured_trip_ids', $results, 'wptravelengine' );

		$args = array(
			'post_type'   => 'trip',
			'numberposts' => -1,
		);

		return apply_filters( 'wp_travel_engine_feat_trips_array', $results, $args );
	}

	/**
	 * Featured Trips sticky section for WP Travel Engine Archives.
	 *
	 * @return void
	 */
	public function wte_featured_trips_sticky( $view_mode ) {
		global $wp_query;

		if ( ! $wp_query->is_post_type_archive() || ! $this->show_featured_trips_on_top() ) {
			return;
		}

		$trips_array = wte_get_featured_trips_array();
		if ( empty( $trips_array ) ) {
			return;
		}

		$args = array(
			'post_type' => 'trip',
			'post__in'  => $trips_array,
		);

		$featured_query = new \WP_Query( $args );

		$user_wishlists = wptravelengine_user_wishlists();
		while ( $featured_query->have_posts() ) :
			$featured_query->the_post();
			$details = wte_get_trip_details( get_the_ID() );
			$details['user_wishlists'] = $user_wishlists;
			wte_get_template( 'content-' . $view_mode . '.php', $details );
		endwhile;

		wp_reset_postdata();
	}

	public static function archive_filters_sub_options() {
		$view_mode = wp_travel_engine_get_archive_view_mode();
		$orderby   = Wp_Travel_Engine_Archive_Hooks::archive_sort_by();
		?>
		<div class="wp-travel-toolbar clearfix">
			<div class="wte-filter-foundposts"></div>
			<div class="wp-travel-engine-toolbar wte-view-modes">
				<?php
				$current_url = '';
				if ( isset( $_SERVER['HTTP_HOST'] ) ) {
					$current_url .= esc_url_raw( wp_unslash( $_SERVER['HTTP_HOST'] ) );
				}
				if ( isset( $_SERVER['REQUEST_URI'] ) ) {
					$current_url .= esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
				}
				?>
				<span><?php esc_html_e( 'View by :', 'wp-travel-engine' ); ?></span>
				<ul class="wte-view-mode-selection-lists">
					<li class="wte-view-mode-selection <?php echo ( 'grid' === $view_mode ) ? 'active' : ''; ?>" data-mode="grid" >
						<a href="<?php echo esc_url( add_query_arg( 'view_mode', 'grid', $current_url ) ); ?>"></a>
					</li>
					<li class="wte-view-mode-selection <?php echo ( 'list' === $view_mode ) ? 'active' : ''; ?>" data-mode="list" >
						<a href="<?php echo esc_url( add_query_arg( 'view_mode', 'list', $current_url ) ); ?>"></a>
					</li>
				</ul>
			</div>
			<div class="wp-travel-engine-toolbar wte-filterby-dropdown">
				<?php
				$wte_sorting_options = wptravelengine_get_sorting_options();
				?>
					<form class="wte-ordering" method="get">
						<span><?php esc_html_e( 'Sort : ', 'wp-travel-engine' ); ?></span>
						<div class="wpte-trip__adv-field wpte__select-field">
							<?php

							?>
							<span class="wpte__input"><?php esc_html_e( 'Latest', 'wp-travel-engine' ); ?></span>
							<input type="hidden" class="wpte__input-value" name="wte_orderby" value=""/>
							<div class="wpte__select-options">
								<ul>
									<?php
									foreach ( $wte_sorting_options as $id => $name ) {
										if ( is_array( $name ) ) {
											$options  = '';
											$options .= '<ul>';
											$options .= sprintf( '<li class="wpte__select-options__label">%s</li>', $name['label'] );
											foreach ( $name['options'] as $key => $label ) {
												// $options .= "<option value=\"{$key}\">{$label}</option>";
												$options .= sprintf(
													'<li data-value="%2$s" %4$s data-label="%3$s"><span>%1$s</span></li>',
													esc_html( $label ),
													esc_attr( $key ),
													esc_attr( $label ),
													( $key === $orderby ) ? 'data-selected' : ''
												);

											}
											$options .= '</ul>';
											printf( '<li>%1$s</li>', $options ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										} else {
											printf(
												'<li data-value="%2$s" %4$s data-label="%3$s"><span>%1$s</span></li>',
												esc_html( $name ),
												esc_attr( $id ),
												esc_attr( $name ),
												( $id === $orderby ) ? 'data-selected' : ''
											);
										}
									}
									?>
								</ul>
							</div>
						</div>
						<input type="hidden" name="paged" value="1" />
						<?php wte_query_string_form_fields( null, array( 'wte_orderby', 'submit', 'paged' ) ); ?>
					</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Header filter section for WP Travel Engine Archives.
	 *
	 * @return void
	 */
	function wp_travel_engine_header_filters_template() {
		self::archive_filters_sub_options();
	}

	/**
	 * Hook for the header block ( contains title and description )
	 *
	 * @return void
	 */
	function wp_travel_engine_archive_header_block() {
		$page_header = apply_filters( 'wte_trip_archive_description_page_header', true );

		$queried_object = get_queried_object();

		$page_title = '';
		if ( $queried_object instanceof \WP_Term ) {
			$page_title = $queried_object->name;
		} elseif ( $queried_object instanceof \WP_Post_Type ) {
			$page_title = $queried_object->label;
		}

		if ( $page_header && ! empty( $page_title ) ) {
			?>
			<header class="page-header">
				<?php
				$settings = get_option( 'wp_travel_engine_settings', [] );
				$show_archive_title = apply_filters( 'wte_trip_archive_title', false );
				$show_archive_title = ! isset( $settings['hide_term_title'] ) || 'yes' !== $settings['hide_term_title'];
				if( $show_archive_title ) {
					echo "<h1 class=\"page-title\" itemprop=\"name\">{$page_title}</h1>";
				}
				$taxonomies = array( 'trip_types', 'destination', 'activities' );
				if ( is_tax( $taxonomies ) ) {
					$image_id       = get_term_meta( get_queried_object()->term_id, 'category-image-id', true );
					$wte_global     = get_option( 'wp_travel_engine_settings', true );
					$show_tax_image = isset( $image_id ) && '' != $image_id
					&& isset( $wte_global['tax_images'] ) ? true : false;
					if ( $show_tax_image ) {
						$tax_banner_size = apply_filters( 'wp_travel_engine_template_banner_size', 'full' );
						echo wp_get_attachment_image( $image_id, $tax_banner_size );
					}
				}

				$show_archive_description = apply_filters( 'wte_trip_archive_description_below_title', false );
				$show_archive_description = ! isset( $settings['hide_term_description'] ) || 'yes' !== $settings['hide_term_description'];
				if ( $show_archive_description && ! is_post_type_archive( WP_TRAVEL_ENGINE_POST_TYPE ) ) {
					the_archive_description( '<div class="taxonomy-description" itemprop="description">', '</div>' );
				}
				?>
			</header><!-- .page-header -->
			<?php
		}
	}

	/**
	 * Main wrap of the archive.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_trip_archive_wrapper() {
		?>
		<div id="wte-crumbs">
			<?php
				do_action( 'wp_travel_engine_breadcrumb_holder' );
			?>
		</div>
		<div id="wp-travel-trip-wrapper" class="trip-content-area" itemscope itemtype="http://schema.org/ItemList">
			<?php
			$header_block = apply_filters( 'wp_travel_engine_archive_header_block_display', true );
			if ( $header_block ) {
				do_action( 'wp_travel_engine_archive_header_block' );
			}
			?>
			<div class="wp-travel-inner-wrapper">
		<?php
	}

	/**
	 * Inner wrap of the archive.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_trip_archive_wrap() {
		?>
		<div class="wp-travel-engine-archive-outer-wrap">
			<?php
				/**
				 * wp_travel_engine_archive_sidebar hook
				 *
				 * @hooked wte_advanced_search_archive_sidebar - Trip Search addon
				 */
				do_action( 'wp_travel_engine_archive_sidebar' );
			?>
			<div class="wp-travel-engine-archive-repeater-wrap">
				<?php
					/**
					 * Hook - wp_travel_engine_header_filters
					 * Hook for the new archive filters on trip archive page.
					 *
					 * @hooked - wp_travel_engine_header_filters_template.
					 */
					do_action( 'wp_travel_engine_header_filters' );
				?>
				<div class="wte-category-outer-wrap">
					<?php
					$j         = 1;
					$view_mode = wp_travel_engine_get_archive_view_mode();
					if ( 'grid' === $view_mode ) {
						$view_class = class_exists( 'Wte_Advanced_Search' ) ? 'col-2 category-grid' : 'col-3 category-grid';
					} else {
						$view_class = 'category-list';
					}
					echo '<div class="category-main-wrap ' . esc_attr( $view_class ) . '">';
					/**
					 * wp_travel_engine_featured_trips_sticky hook
					 * Hook for the featured trips sticky section
					 *
					 * @hooked wte_featured_trips_sticky
					 */
					do_action( 'wp_travel_engine_featured_trips_sticky', $view_mode );
					$user_wishlists = wptravelengine_user_wishlists();

					while ( have_posts() ) :
						the_post();
						$details      = wte_get_trip_details( get_the_ID() );
						$details['j'] = $j;
						$details['user_wishlists'] = $user_wishlists;
						wte_get_template( 'content-' . $view_mode . '.php', $details );
						$j++;
					endwhile;
					wp_reset_postdata();
					echo '</div>';
					?>
				</div>
				<div id="loader" style="display: none">
					<div class="table">
						<div class="table-grid">
							<div class="table-cell">
								<?php wptravelengine_svg_by_fa_icon( 'fas fa-spinner', true, [ 'fa-spin' ] ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="trip-pagination">
			<?php
			the_posts_pagination(
				array(
					'prev_text'          => esc_html__( 'Previous', 'wp-travel-engine' ),
					'next_text'          => esc_html__( 'Next', 'wp-travel-engine' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'wp-travel-engine' ) . ' </span>',
				)
			);
			?>
		</div>
		<?php
	}
	/**
	 * Oter wrap of the archive.
	 *
	 * @since    1.0.0
	 */
	function wp_travel_engine_trip_archive_outer_wrapper_close() {
		?>

		</div><!-- wp-travel-inner-wrapper -->
		</div><!-- .wp-travel-trip-wrapper -->
		<?php
	}
}
new Wp_Travel_Engine_Archive_Hooks();
