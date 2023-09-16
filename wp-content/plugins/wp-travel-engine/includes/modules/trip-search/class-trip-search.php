<?php
namespace WPTravelEngine\Modules;

/**
 * Trip Search
 *
 * @since __addonmigration__
 */
class TripSearch {

	public function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	private function includes() {}

	private function init_hooks() {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 15 );

		add_action( 'init', array( $this, 'init' ) );
		/**
		 * Admin Hooks.
		 */
		// add_action( 'wte_advanced_search_page', array( __CLASS__, 'search_page' ) );
		// add_action( 'wp_travel_engine_search_fields', array( __CLASS__, 'search_fields' ) );
		add_action( 'wpte_get_global_extensions_tab', array( __CLASS__, 'settings' ) );
		/**p
		 * Add settings to choose Search Page.
		 * Settings>General>Page Settings
		 */
		add_filter( 'wpte_global_page_options', array( __CLASS__, 'choose_page' ) );

		/**
		 * Public hooks
		 */
		add_filter(
			'body_class',
			function( $classes ) {
				if ( self::is_search_page() ) {
					$classes[] = 'trip-search-result';
				}
				return $classes;
			}
		);

		add_action( 'template_redirect', array( __CLASS__, 'template_redirect' ) );

		add_action( 'wp_travel_engine_archive_sidebar', array( __CLASS__, 'archive_filter_sidebar' ) );

		// Search Filter forms callback.
		add_filter(
			'wptravelengine_search_filter_price',
			function() {
				return array( __CLASS__, 'search_filter_price' );
			}
		);
		add_filter(
			'wptravelengine_search_filter_duration',
			function() {
				return array( __CLASS__, 'search_filter_duration' );
			}
		);
	}

	public static function search_filter_duration( $args ) {
		wp_enqueue_script( 'jquery-ui-slider' );
		$range = (array) self::get_duration_range();
		$id    = wte_uniqid();
		?>
		<div data-value-format="duration" class="wpte-trip__adv-field wpte__select-field" data-range-slider="#<?php echo esc_attr( $id ); ?>" data-range="<?php echo esc_attr( implode( ',', $range ) ); ?>" data-min="0" data-max="50" data-suffix="<?php esc_attr_e( 'Days', 'wp-travel-engine' ); ?>">
			<span class="icon">
				<svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M7.4375 0.125C5.99123 0.125 4.57743 0.553871 3.3749 1.35738C2.17236 2.16089 1.2351 3.30294 0.681634 4.63913C0.128168 5.97531 -0.0166435 7.44561 0.265511 8.8641C0.547665 10.2826 1.24411 11.5855 2.26678 12.6082C3.28946 13.6309 4.59242 14.3273 6.0109 14.6095C7.42939 14.8916 8.89969 14.7468 10.2359 14.1934C11.5721 13.6399 12.7141 12.7026 13.5176 11.5001C14.3211 10.2976 14.75 8.88377 14.75 7.4375C14.75 6.47721 14.5609 5.52632 14.1934 4.63913C13.8259 3.75193 13.2872 2.94581 12.6082 2.26678C11.9292 1.58775 11.1231 1.04912 10.2359 0.681631C9.34868 0.314143 8.39779 0.125 7.4375 0.125ZM7.4375 13.2875C6.28048 13.2875 5.14944 12.9444 4.18742 12.3016C3.22539 11.6588 2.47558 10.7451 2.03281 9.6762C1.59004 8.60725 1.47419 7.43101 1.69991 6.29622C1.92563 5.16143 2.48279 4.11906 3.30093 3.30092C4.11907 2.48279 5.16144 1.92563 6.29622 1.69991C7.43101 1.47418 8.60725 1.59003 9.6762 2.0328C10.7451 2.47558 11.6588 3.22539 12.3016 4.18741C12.9444 5.14944 13.2875 6.28048 13.2875 7.4375C13.2875 8.98901 12.6712 10.477 11.5741 11.5741C10.477 12.6712 8.98902 13.2875 7.4375 13.2875ZM9.70438 7.89819L8.16875 7.01337V3.78125C8.16875 3.58731 8.09171 3.40131 7.95457 3.26418C7.81744 3.12704 7.63144 3.05 7.4375 3.05C7.24356 3.05 7.05757 3.12704 6.92043 3.26418C6.78329 3.40131 6.70625 3.58731 6.70625 3.78125V7.4375C6.70625 7.4375 6.70625 7.496 6.70625 7.52525C6.71058 7.57563 6.72293 7.625 6.74282 7.6715C6.75787 7.71488 6.77748 7.75655 6.80131 7.79581C6.82132 7.83737 6.84585 7.87661 6.87444 7.91281L6.99144 8.00787L7.05725 8.07369L8.9585 9.17056C9.06995 9.23373 9.19603 9.26651 9.32413 9.26562C9.48604 9.26676 9.64375 9.21412 9.77252 9.11596C9.90129 9.01781 9.99385 8.8797 10.0357 8.72328C10.0775 8.56686 10.0662 8.40098 10.0036 8.25166C9.94101 8.10233 9.83062 7.97801 9.68975 7.89819H9.70438Z" fill="#2183DF" />
				</svg>
			</span>
			<input type="text" data-value class="wpte__input" placeholder="<?php echo esc_attr( $args['label'] ); ?>" />
			<input type="hidden" data-value-min class="wpte__input-min" name="min-duration">
			<input type="hidden" data-value-max class="wpte__input-max" name="max-duration">
			<div class="wpte__select-options">
				<div id="<?php echo esc_attr( $id ); ?>"></div>
			</div>
		</div>
		<?php
	}

	public static function search_filter_price( $args ) {
		// wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'wte-nouislider' );
		wp_enqueue_style( 'wte-nouislider' );
		$range = (array) self::get_price_range();
		$id    = wte_uniqid();
		?>
		<div class="wpte-trip__adv-field wpte__select-field" data-range-slider="#<?php echo esc_attr( $id ); ?>" data-range="<?php echo esc_attr( implode( ',', $range ) ); ?>" data-min="0" data-max="50" data-value-format="price">
			<span class="icon">
				<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M8.25 6.75H11.25C11.4489 6.75 11.6397 6.67098 11.7803 6.53033C11.921 6.38968 12 6.19891 12 6C12 5.80109 11.921 5.61032 11.7803 5.46967C11.6397 5.32902 11.4489 5.25 11.25 5.25H9.75V4.5C9.75 4.30109 9.67099 4.11032 9.53033 3.96967C9.38968 3.82902 9.19892 3.75 9 3.75C8.80109 3.75 8.61033 3.82902 8.46967 3.96967C8.32902 4.11032 8.25 4.30109 8.25 4.5V5.25C7.65327 5.25 7.08097 5.48705 6.65901 5.90901C6.23706 6.33097 6 6.90326 6 7.5C6 8.09674 6.23706 8.66903 6.65901 9.09099C7.08097 9.51295 7.65327 9.75 8.25 9.75H9.75C9.94892 9.75 10.1397 9.82902 10.2803 9.96967C10.421 10.1103 10.5 10.3011 10.5 10.5C10.5 10.6989 10.421 10.8897 10.2803 11.0303C10.1397 11.171 9.94892 11.25 9.75 11.25H6.75C6.55109 11.25 6.36033 11.329 6.21967 11.4697C6.07902 11.6103 6 11.8011 6 12C6 12.1989 6.07902 12.3897 6.21967 12.5303C6.36033 12.671 6.55109 12.75 6.75 12.75H8.25V13.5C8.25 13.6989 8.32902 13.8897 8.46967 14.0303C8.61033 14.171 8.80109 14.25 9 14.25C9.19892 14.25 9.38968 14.171 9.53033 14.0303C9.67099 13.8897 9.75 13.6989 9.75 13.5V12.75C10.3467 12.75 10.919 12.5129 11.341 12.091C11.763 11.669 12 11.0967 12 10.5C12 9.90326 11.763 9.33097 11.341 8.90901C10.919 8.48705 10.3467 8.25 9.75 8.25H8.25C8.05109 8.25 7.86033 8.17098 7.71967 8.03033C7.57902 7.88968 7.5 7.69891 7.5 7.5C7.5 7.30109 7.57902 7.11032 7.71967 6.96967C7.86033 6.82902 8.05109 6.75 8.25 6.75ZM9 0.75C7.36831 0.75 5.77325 1.23385 4.41655 2.14038C3.05984 3.0469 2.00242 4.33537 1.378 5.84286C0.753575 7.35035 0.590197 9.00915 0.908525 10.6095C1.22685 12.2098 2.01259 13.6798 3.16637 14.8336C4.32016 15.9874 5.79017 16.7732 7.39051 17.0915C8.99085 17.4098 10.6497 17.2464 12.1571 16.622C13.6646 15.9976 14.9531 14.9402 15.8596 13.5835C16.7661 12.2268 17.25 10.6317 17.25 9C17.25 6.81196 16.3808 4.71354 14.8336 3.16637C13.2865 1.61919 11.188 0.75 9 0.75ZM9 15.75C7.66498 15.75 6.35994 15.3541 5.2499 14.6124C4.13987 13.8707 3.27471 12.8165 2.76382 11.5831C2.25293 10.3497 2.11925 8.99251 2.3797 7.68314C2.64015 6.37377 3.28303 5.17103 4.22703 4.22703C5.17104 3.28302 6.37377 2.64015 7.68314 2.3797C8.99252 2.11925 10.3497 2.25292 11.5831 2.76381C12.8165 3.2747 13.8707 4.13987 14.6124 5.2499C15.3541 6.35993 15.75 7.66498 15.75 9C15.75 10.7902 15.0388 12.5071 13.773 13.773C12.5071 15.0388 10.7902 15.75 9 15.75Z" fill="#2183DF" />
				</svg>
			</span>
			<input type="text" class="wpte__input" data-value placeholder="<?php echo esc_attr( $args['label'] ); ?>" />
			<input type="hidden" data-value-min class="wpte__input-min" name="min-cost" />
			<input type="hidden" data-value-max class="wpte__input-max" name="max-cost"/>
			<div class="wpte__select-options">
				<div id="<?php echo esc_attr( $id ); ?>"></div>
			</div>
		</div>
		<?php
	}

	public function enqueue_scripts() {
		wp_register_script( 'wte-trip-search', plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . 'dist/public/trip-search/index.js', array( 'jquery', 'wte-custom-scrollbar', 'jquery-ui-slider' ), WP_TRAVEL_ENGINE_VERSION, true );
		// wp_register_style( 'wte-trip-search', plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . 'dist/public/trip-search/index.css', array(), WP_TRAVEL_ENGINE_VERSION );

		$price_range = self::get_price_range();
		$min_cost    = 0;
		$max_cost    = $price_range->{'max_price'};
		// phpcs:disable
		$duration_range  = self::get_duration_range();
		$to_be_localized = array(
			'ajax_url'              => admin_url( 'admin-ajax.php' ),
			'min_cost'              => (int) $price_range->{'min_price'},
			'max_cost'              => (int) $price_range->{'max_price'},
			'min_duration'          => (int) $duration_range->{'min_duration'},
			'max_duration'          => (int) $duration_range->{'max_duration'},
			'selected_min_cost'     => ! empty( $_GET['min-cost'] ) ? (int) $_GET['min-cost'] : (int) $price_range->{'min_price'},
			'selected_max_cost'     => (int) $price_range->{'max_price'},
			'selected_min_duration' => ! empty( $_GET['min-duration'] ) ? (int) $_GET['min-duration'] : (int) $duration_range->{'min_duration'},
			'selected_max_duration' => ! empty( $_GET['max-duration'] ) ? (int) $_GET['max-duration'] : (int) $duration_range->{'max_duration'},
			'cur_symbol'            => wp_travel_engine_get_currency_code(),
			'days_text'             => __( 'Days', 'wp-travel-engine' ),
		);
		wp_localize_script( 'wte-trip-search', 'wte_advanced_search', $to_be_localized );
		// phpcs:enable
	}

	public static function load_trips_html() {
		// phpcs:disable
		$args                = json_decode( wp_unslash( $_POST['query'] ), true );
		$args['paged']       = wte_clean( wp_unslash( $_POST['page'] ) ) + 1; // we need next page to be loaded
		$args['post_status'] = 'publish';

		$query = new \WP_Query( $args );
		ob_start();

		$view_mode  = wte_clean( wp_unslash( $_POST['mode'] ) );
		$view_class = 'grid' === $view_mode ? 'col-2 category-grid' : 'category-list';

		$user_wishlists = wptravelengine_user_wishlists();

		// phpcs:enable
		while ( $query->have_posts() ) :
			$query->the_post();
			$details = \wte_get_trip_details( get_the_ID() );
			$details['user_wishlists'] = $user_wishlists;
			\wte_get_template( 'content-' . $view_mode . '.php', $details );
		endwhile;
		\wp_reset_postdata();

		$html = ob_get_clean();
		wp_send_json_success(
			array(
				'data' => $html,
			)
		);
		exit();
	}

	public static function filter_trips_html( $post_data ) {

		$query_args = self::get_query_args( true );

		$query = new \WP_query( $query_args );

		if ( ! $query->have_posts() ) {
			return wp_send_json_success(
				array(
					'foundposts' => apply_filters( 'no_result_found_message', __( 'No results found!', 'wp-travel-engine' ) ),
					'data'       => '',
				)
			);
		}

		ob_start();

		$view_mode  = ! empty( $post_data['mode'] ) ? wte_clean( wp_unslash( $post_data['mode'] ) ) : wp_travel_engine_get_archive_view_mode(); // phpcs:ignore
		$view_class = 'grid' === $view_mode ? 'col-2 category-grid' : 'category-list';

		echo '<div class="category-main-wrap ' . esc_attr( $view_class ) . '">';

		$user_wishlists = wptravelengine_user_wishlists();

		while ( $query->have_posts() ) :
			$query->the_post();
			$details = wte_get_trip_details( get_the_ID() );
			$details['user_wishlists'] = $user_wishlists;

			wte_get_template( 'content-' . $view_mode . '.php', $details );
		endwhile;
		wp_reset_postdata();
		echo '</div>';

		$default_posts_per_page = get_option( 'posts_per_page', 10 );
		$paged                  = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
		if ( $query->found_posts > $default_posts_per_page ) {
			echo "<span data-id='" . $query->found_posts . "' class='wte-search-load-more'><a data-query-vars='" . wp_json_encode( $query->query_vars ) . "' data-current-page='" . $paged . "' data-max-page='" . $query->max_num_pages . "' href='#' class='load-more-search' data-nonce='" . wp_create_nonce( 'wte_show_ajax_result_load' ) . "'>" . __( 'Load More', 'wp-travel-engine' ) . '</a></span>';
		}

		$foundposts = sprintf( _nx( '%s Trip Found', '%s Trips found', $query->found_posts, 'number of trips', 'wp-travel-engine' ), "<strong>" . number_format_i18n( $query->found_posts ) . "</strong>" );
		return wp_send_json_success(
			array(
				'foundposts' => $foundposts,
				'data'       => ob_get_clean(),
			)
		);
		exit;
	}

	/**
	 * Prepares query to get results.
	 */
	public static function get_query_args( $ajax_request = false ) {
		$paged          = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
		$posts_per_page = get_option( 'posts_per_page', 10 );

		$query_args = array(
			'post_type'                => WP_TRAVEL_ENGINE_POST_TYPE,
			'post_status'              => 'publish',
			'posts_per_page'           => $posts_per_page,
			'wpse_search_or_tax_query' => true,
			'paged'                    => $paged,
		);

		$categories = apply_filters(
			'wte_filter_categories',
			array(
				'trip_types'  => array(
					'taxonomy'         => 'trip_types',
					'field'            => 'slug',
					'include_children' => true,
				),
				'cat'         => array(
					'taxonomy'         => 'trip_types',
					'field'            => 'slug',
					'include_children' => true,
				),
				'budget'      => array(
					'taxonomy'         => 'budget',
					'field'            => 'slug',
					'include_children' => false,
				),
				'activities'  => array(
					'taxonomy'         => 'activities',
					'field'            => 'slug',
					'include_children' => true,
				),
				'destination' => array(
					'taxonomy'         => 'destination',
					'field'            => 'slug',
					'include_children' => true,
				),
				'trip_tag' => array(
					'taxonomy'         => 'trip_tag',
					'field'            => 'slug',
				),
				'difficulty' => array(
					'taxonomy'         => 'difficulty',
					'field'            => 'slug',
				),
			)
		);
		// phpcs:disable
		$tax_query = array();
		foreach ( $categories as $cat => $term_args ) {
			if ( $ajax_request ) {
				$category = ! empty( $_REQUEST['result'][ $cat ] ) && $_REQUEST['result'][ $cat ] != '-1' ? $_REQUEST['result'][ $cat ] : ''; // phpcs:ignore
			} else {
				$category = ! empty( $_REQUEST[ $cat ] ) && $_REQUEST[ $cat ] != -1 ? $_REQUEST[ $cat ] : ''; // phpcs:ignore
			}

			if ( ! empty( $category ) ) {
				$term_args['terms'] = $category;
				$tax_query[]        = $term_args;
			}
		}

		if ( ! empty( $tax_query ) ) {
			$query_args['tax_query'] = $tax_query; // phpcs:ignore
			$query_args['tax_query']['relation'] = 'AND';
		}

		$meta_query = array();
		// Check Price.
		if ( $ajax_request && isset( $_REQUEST['mincost'], $_REQUEST['maxcost'] ) ) {
			$min_cost = intval( $_REQUEST['mincost'] );
			$max_cost = intval( $_REQUEST['maxcost'] );
		} else {
			$cost_range = (array) self::get_price_range();
			$min_cost   = isset( $_REQUEST['min-cost'] ) ? intval( $_REQUEST['min-cost'] ) : (int) $cost_range['min_price'];
			$max_cost   = isset( $_REQUEST['max-cost'] ) ? intval( $_REQUEST['max-cost'] ) : (int) $cost_range['max_price'];
		}

		if ( isset( $max_cost ) && $max_cost > 0 ) {
			$meta_query[] = array(
				'key'     => apply_filters( 'wpte_advance_search_price_filter', '_s_price' ),
				'value'   => array( $min_cost - 1, $max_cost + 1 ),
				'compare' => 'BETWEEN',
				'type'    => 'numeric',
			);
		}

		// Check Duration.
		if ( $ajax_request && isset( $_REQUEST['mindur'], $_REQUEST['maxdur'] ) ) {
			$min_duration = intval( $_REQUEST['mindur'] );
			$max_duration = intval( $_REQUEST['maxdur'] );
		} else {
			$range        = (array) self::get_duration_range();
			$min_duration = isset( $_REQUEST['min-duration'] ) ? intval( $_REQUEST['min-duration'] ) : (int) $range['min_duration'];
			$max_duration = isset( $_REQUEST['max-duration'] ) ? intval( $_REQUEST['max-duration'] ) : (int) $range['max_duration'];
		}
		if ( isset( $max_duration ) && 0 != $max_duration ) {
			array_push(
				$meta_query,
				array(
					'key'     => '_s_duration',
					'value'   => array( ($min_duration * 24) - 1, ($max_duration * 24) + 1 ),
					'compare' => 'BETWEEN',
					'type'    => 'numeric',
				)
			);
		}

		if ( ! empty( $_REQUEST['trip-date-select'] ) || ! empty( $_REQUEST['date'] ) ) {
			$date = $ajax_request ? wte_clean( wp_unslash( $_REQUEST['date'] ) ) : wte_clean( wp_unslash( $_REQUEST['trip-date-select'] ) );

			try {
				$min_date = new \DateTime( $date . '-01' );
				$date     = $min_date->format( 'ym' );
			} catch ( \Exception $e ) {
				$date = str_replace( '-', '', $date );
			}
			$meta_query[] = array(
				'key'     => 'trip_available_months',
				'value'   => $date,
				'compare' => 'LIKE',
			);
		}

		if ( ! empty( $meta_query ) ) {
			$query_args['meta_query'] = $meta_query; // phpcs:ignore
			$query_args['meta_query']['relation'] = 'AND'; // phpcs:ignore
		}

		if ( isset( $_REQUEST['sort'] ) && ! empty( $_REQUEST['sort'] ) ) {
			$sortby_val = isset( $_REQUEST['sort'] ) && ! empty( $_REQUEST['sort'] ) ? wte_clean( wp_unslash( $_REQUEST['sort'] ) ) : 'menu_order';
			$sort_args  = wte_advanced_search_get_order_args( $sortby_val );
			$args       = array_merge( $query_args, $sort_args );
		}

		if ( ! empty( $_REQUEST['wte_orderby'] ) || ! empty( $_REQUEST['sort'] ) ) {
			$order_by   = $ajax_request ? $_REQUEST['sort'] : wte_clean( wp_unslash( $_REQUEST['wte_orderby'] ) );
			$sort_args = wte_advanced_search_get_order_args( $order_by ); // phpcs:ignore
			$query_args = array_merge( $query_args, $sort_args );
		}
		// phpcs:enable

		return apply_filters( 'query_args_for_trip_filters', $query_args );
	}

	public static function template_redirect( $template ) {
		global $post;
		if ( is_null( $post ) ) {
			return $template;
		}
		if ( self::is_search_page() || \has_shortcode( $post->post_content, 'WTE_Trip_Search' ) ) {
			\wte_get_template( 'template-trip-search-results.php' );
			exit;
		}
	}

	public static function get_duration_range() {
		global $wpdb;

		$range = wp_cache_get( 'wte_duration_range', 'options' );

		if ( ! $range ) {
			$where   = $wpdb->prepare( 'meta_key = %s', '_s_duration' );
			$query   = "SELECT MIN(meta_value * 1) as `min_duration`, MAX(meta_value * 1) as `max_duration` FROM {$wpdb->postmeta} WHERE {$where}";
			$results = $wpdb->get_row( $query ); // phpcs:ignore
			$range   = array(
				'min_duration' => 0,
				'max_duration' => 0,
			);
			if ( ! empty( $results ) ) {
				$range = $results;
				$range->min_duration = 0;
				$range->max_duration = $range->max_duration / 24;
			}

			wp_cache_add( 'wte_duration_range', $range, 'options' );
		}

		return (object) $range;
	}

	public static function get_filters_sections() {
		$settings = get_option( 'wp_travel_engine_settings', array() );

		return apply_filters(
			'trip_filters_sections',
			array(
				'destinations' => array(
					'label'  => __( 'Destination', 'wp-travel-engine' ),
					'show'   => empty( $settings['trip_search']['destination'] ),
					'render' => array( __CLASS__, 'filter_destinations_render' ),
				),
				'price'        => array(
					'label'  => __( 'Price', 'wp-travel-engine' ),
					'show'   => empty( $settings['trip_search']['budget'] ),
					'render' => array( __CLASS__, 'filter_price_render' ),
				),
				'duration'     => array(
					'label'  => __( 'Duration', 'wp-travel-engine' ),
					'show'   => empty( $settings['trip_search']['duration'] ),
					'render' => array( __CLASS__, 'filter_duration_render' ),
				),
				'activities'   => array(
					'label'  => __( 'Activities', 'wp-travel-engine' ),
					'show'   => empty( $settings['trip_search']['activities'] ),
					'render' => array( __CLASS__, 'filter_activities_render' ),
				),
				'trip_types'   => array(
					'label'  => __( 'Trip Types', 'wp-travel-engine' ),
					'show'   => empty( $settings['trip_search']['trip_types'] ),
					'render' => array( __CLASS__, 'filter_trip_types_render' ),
				),
				'trip_tag'   => array(
					'label'  => __( 'Tags', 'wp-travel-engine' ),
					'show'   => empty( $settings['trip_search']['trip_tag'] ),
					'render' => array( __CLASS__, 'filter_trip_tag_render' ),
				),
				'difficulty'   => array(
					'label'  => __( 'Difficulties', 'wp-travel-engine' ),
					'show'   => empty( $settings['trip_search']['difficulty'] ),
					'render' => array( __CLASS__, 'filter_difficulty_render' ),
				),
			)
		);
	}

	public static function taxonomy_filter_html( $terms, $children = false ) {

		$parent_count = 0;
		$term_count   = 0;
		if ( is_array( $terms ) && count( $terms ) > 0 ) {
			printf( '<ul class="%1$s">', $children ? 'children' : 'wte-search-terms-list' );
			$invisible_terms = '';
			$queried_term = get_queried_object();
			$possible_queries = [];
			if ( $queried_term instanceof \WP_Term ) {
				$possible_queries[] =  $queried_term->slug;
			}
			foreach ( $terms as $term ) {
				if ( $term->parent && ! $children ) {
					continue;
				}
				$possible_queries[] = wte_array_get( $_GET, $term->taxonomy, false );

				ob_start();
				printf( '<li class="%1$s">', $children ? 'has-children' : '' );
				printf(
					'<label>'
					. '<input type="checkbox" %1$s value="%2$s" name="%3$s" class="%3$s wte-filter-item"/>'
					. '<span>%4$s</span>'
					. '</label>',
					checked( true, in_array( $term->slug, $possible_queries ), false ), // phpcs:ignore
					$term->slug,
					$term->taxonomy,
					$term->name
				);

				if ( apply_filters( 'wte_advanced_search_filters_show_tax_count', true ) ) {
					printf( '<span class="count">%1$s</span>', $term->count );
				}
				if ( is_array( $term->children ) && count( $term->children ) > 0 ) {
					$_children = array();
					foreach ( $term->children as $term_child ) {
						if ( ! isset( $terms[ $term_child ] ) ) {
							continue;
						}
						$_children[ $term_child ] = $terms[ $term_child ];
					}
					call_user_func( array( __CLASS__, __FUNCTION__ ), $_children, true );
				}
				print( '</li>' );

				$list = ob_get_clean();
				if ( ( ++$parent_count > 4 ) && ! $children ) {
					$invisible_terms .= $list;
				} else {
					$term_count += count( $term->children ) + 1;
					echo $list;
				}
			}
			if ( $invisible_terms != '' && ! $children ) {
				printf(
					'<li class="wte-terms-more"><button class="show-more">%2$s</button><ul class="wte-terms-more-list">%1$s</ul><button class="show-less">%3$s</button></li>',
					$invisible_terms,
					sprintf( __( 'Show all %s', 'wp-travel-engine' ), count( $terms ) - $term_count ),
					__( 'Show less', 'wp-travel-engine' )
				);
			}
			print( '</ul>' );
		}

	}

	public static function filter_taxonomies_render( $taxonomy, $filter ) {
		if ( empty( $filter['label'] ) ) {
			return;
		}

		$categories = get_categories( "taxonomy={$taxonomy}" );
		if ( empty( $categories ) ) {
			return;
		}
		$terms = \wte_get_terms_by_id( $taxonomy );

		?>
		<div class='advanced-search-field search-trip-type'>
			<h3 class='filter-section-title trip-type'><?php echo esc_html( $filter['label'] ); ?></h3>
			<div class="filter-section-content">
			<?php self::taxonomy_filter_html( $terms ); ?>
			</div>
		</div>
		<?php
	}

	public static function filter_destinations_render( $filter ) {
		self::filter_taxonomies_render( 'destination', $filter );
	}

	public static function filter_activities_render( $filter ) {
		self::filter_taxonomies_render( 'activities', $filter );
	}

	/**
	 *
	 * @since 5.5.7
	 */
	public static function filter_trip_tag_render( $filter ) {
		self::filter_taxonomies_render( 'trip_tag', $filter );
	}

	/**
	 *
	 * @since 5.5.7
	 */
	public static function filter_difficulty_render( $filter ) {
		self::filter_taxonomies_render( 'difficulty', $filter );
	}

	public static function filter_duration_render( $filter ) {
		// phpcs:disable
		$min_duration = isset( $_GET['min-duration'] ) ? (int) $_GET['min-duration'] : '';
		$max_duration = isset( $_GET['max-duration'] ) ? (int) $_GET['max-duration'] : '';
		// phpcs:enable
		$duration_range = self::get_duration_range();
		if ( '' === $min_duration ) {
			$min_duration   = (int) $duration_range->{'min_duration'};
			$max_duration   = (int) $duration_range->{'max_duration'};
		}
		?>
		<div class="advanced-search-field search-duration search-trip-type"
			data-value-format="duration"
			data-suffix="<?php echo __( 'Days', 'wp-travel-engine' ); ?>"
			data-min="<?php echo esc_attr( $duration_range->min_duration ); ?>"
			data-max="<?php echo esc_attr( $duration_range->max_duration ); ?>"
			data-range="<?php echo esc_attr( $min_duration . ',' . $max_duration ); ?>"
			data-range-slider="#duration-slider-range">
			<h3 class="filter-section-title"><?php echo esc_html( $filter['label'] ); ?></h3>
			<div class="filter-section-content">
				<div id="duration-slider-range" data-min-key="mindur" data-max-key="maxdur"></div>
				<input type="hidden" data-value-min class="wpte__input-min" name="mindur" />
				<input type="hidden" data-value-max class="wpte__input-max" name="maxdur"/>

				<div class="duration-slider-value">
					<span id="min-duration" class="min-duration" name="min-duration">
						<?php  echo sprintf( __( '%1$s Days', 'wp-travel-engine' ), round( $duration_range->min_duration ) ); ?>
					</span>
					<span class="max-duration" id="max-duration" name="max-duration">'
						<?php echo sprintf( __( '%1$s Days', 'wp-travel-engine' ), round( $duration_range->max_duration ) ); ?>
					</span>
				</div>
			</div>
		</div>
		<?php
	}

	public static function filter_price_render( $filter ) {
		// phpcs:disable
		$min_cost = ! empty( $_GET['min-cost'] ) ? (int) $_GET['min-cost'] : '';
		$max_cost = ! empty( $_GET['max-cost'] ) ? (int) $_GET['max-cost'] : '';
		// phpcs:enable
		$price_range = self::get_price_range();
		if ( $min_cost === '' ) {
			$min_cost    = (int) $price_range->{'min_price'};
			$max_cost    = (int) $price_range->{'max_price'};
		}

		print( '<div class="advanced-search-field search-cost search-trip-type" data-max="'. $price_range->{'max_price'} .'" data-min="'. $price_range->{'min_price'} .'" data-range="' . $min_cost . ',' . $max_cost . '" data-value-format="price" data-range-slider="#cost-slider-range">'
			. '<h3 class="filter-section-title">' . $filter['label'] . '</h3>'
			. '<div class="filter-section-content">'
				. '<input type="hidden" data-value-min class="wpte__input-min" name="mincost" />'
				. '<input type="hidden" data-value-max class="wpte__input-max" name="maxcost"/>'

				. '<div id="cost-slider-range" data-min-key="mincost" data-max-key="maxcost"></div>'
				. '<div class="cost-slider-value"><span class="min-cost">'
				. \wte_get_formated_price( (int) $price_range->{'min_price'} )
				. '</span><span class="max-cost">'
				. \wte_get_formated_price( (int) $price_range->{'max_price'} )
				. '</span></div>'
			. '</div>'
			. '</div>'
		);
	}

	public static function filter_trip_types_render( $filter ) {
		self::filter_taxonomies_render( 'trip_types', $filter );
	}

	public static function get_price_range() {
		global $wpdb;

		$range = wp_cache_get( 'wte_price_range', 'options' );

		if ( ! $range ) {
			$where   = $wpdb->prepare( 'meta_key = %s', '_s_price' );
			$query   = "SELECT MIN(meta_value * 1) as min_price, MAX(meta_value * 1) as max_price FROM {$wpdb->postmeta} WHERE {$where}";
			$results = $wpdb->get_row( $query ); // phpcs:ignore
			$range   = array(
				'min_price' => 0,
				'max_price' => 0,
			);
			if ( ! empty( $results ) ) {
				$range = $results;
			}
			wp_cache_add( 'wte_price_range', $range, 'options' );
		}

		return (object) $range;
	}

	/**
	 * Shows filters sidebar on Archive Page.
	 */
	public static function archive_filter_sidebar() {
		\wp_enqueue_style( 'wte-trip-search' );
		\wp_enqueue_script( 'wte-trip-search' );
		\wte_get_template( 'template-trip-filters-sidebar.php' );
	}

	public function init() {
		add_shortcode( 'Wte_Advanced_Search_Form', array( __CLASS__, 'search_form' ) );
		add_shortcode( 'WTE_Trip_Search', function() {} );
	}

	/**
	 * Search Form Output.
	 */
	public static function search_form() {
		$is_rest_route = defined( 'REST_REQUEST' ) && REST_REQUEST;
		if ( ( is_admin() && ! $is_rest_route ) || ( ! is_admin() && $is_rest_route ) ) {
			return;
		}

		$cost_range     = self::get_price_range();
		$duration_range = self::get_duration_range();

		wp_enqueue_script( 'wte-trip-search' );
		wp_enqueue_style( 'wte-trip-search' );

		ob_start();
			\wte_get_template( 'template-trip-search-form.php', compact( 'duration_range', 'cost_range' ) );
		return ob_get_clean();
	}

	public static function is_search_page() {
		global $post;

		if ( ! is_object( $post ) ) {
			return false;
		}

		$options = get_option( 'wp_travel_engine_settings', array() );

		return isset( $options['pages']['search'] ) && ( (int) $post->ID === (int) $options['pages']['search'] );
	}

	public static function search_page() {
		// 404 do_action not found
	}

	public static function search_fields() {

	}

	public static function settings( $settings ) {
		$settings['wte_trip_search'] = array(
			'label'        => __( 'Trip Search', 'wp-travel-engine' ),
			'content_path' => plugin_dir_path( __FILE__ ) . 'views/admin-settings.php',
			'current'      => true,
			'has_updates' => 'wte_note_5.5.7',
		);
		return $settings;
	}

	public static function choose_page( $pages ) {
		$options = get_option( 'wp_travel_engine_settings', array() );
		$search  = isset( $options['pages']['search'] ) ? esc_attr( $options['pages']['search'] ) : get_page_by_title( 'Trip Search Result' )->ID;

		$_pages = get_pages();
		$_pages = array_column( $_pages, 'post_title', 'ID' );

		$pages['wte-search-page'] = array(
			'label'         => __( 'Trip Search Results Page', 'wp-travel-engine' ),
			'label_class'   => 'wpte-field-label',
			'wrapper_class' => 'wpte-field wpte-select wpte-floated',
			'field_label'   => __( 'Trip Search Results Page', 'wp-travel-engine' ),
			'type'          => 'select',
			'options'       => $_pages,
			'class'         => 'wpte-enhanced-select',
			'name'          => 'wp_travel_engine_settings[pages][search]',
			'default'       => $search,
			'selected'      => $search,
			'tooltip'       => __( 'This is the trip search results page with search filters.', 'wp-travel-engine' ),
		);

		return $pages;
	}

	public static function get_page_id() {
		$page_id = get_option( 'wp_travel_engine_search_page_id', false );

		if ( ! $page_id ) {
			$settings = get_option( 'wp_travel_engine_settings', [] ); // Not used wp_travel_engine_get_settings due to infinite loop.
			$page_id = isset( $settings['pages']['search'] ) ? $settings['pages']['search'] : -1;
			update_option( 'wp_travel_engine_search_page_id', $page_id );
		}

		$page_id = apply_filters( 'wp_travel_engine_get_search_page_id', $page_id );

		return $page_id ? absint( $page_id ) : -1;
	}

}

new TripSearch();
