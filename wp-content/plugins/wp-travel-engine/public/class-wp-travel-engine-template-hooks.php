<?php
/**
 * WP Travel Engine Template Hooks
 *
 * @package WP_Travel_Engine
 */
use WPTravelEngine\Packages;
class WP_Travel_Engine_Template_Hooks {

	private static $_instance = null;

	private function __construct() {
		$this->init_hooks();

	}

	public static function get_instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Initialization hooks.
	 *
	 * @return void
	 */
	private function init_hooks() {

		add_action( 'wte_bf_travellers_input_fields', array( $this, 'booking_form_traveller_inputs' ) );
		add_action( 'wte_after_price_info_list', array( $this, 'display_multi_pricing_info' ) );
		add_action( 'wp_travel_engine_trip_itinerary_template', array( $this, 'display_itinerary_content' ) );

		add_action( 'wp_travel_engine_checkout_header_steps', array( $this, 'checkout_header_steps' ) );

		$this->init_single_trip_hooks();

	}

	private function init_single_trip_hooks() {
		add_action( 'wp_travel_engine_before_trip_content', array( $this, 'trip_content_wrapper_start' ), 5 );
		add_action( 'wte_single_trip_content', array( $this, 'display_single_trip_title' ), 5 );
		add_action( 'wte_single_trip_content', array( $this, 'display_single_trip_gallery' ), 10 );
		add_action( 'wte_single_trip_content', array( $this, 'display_single_trip_content' ), 15 );
		add_action( 'wte_single_trip_content', array( $this, 'display_single_trip_facts' ), 15 );
		add_action( 'wte_single_trip_content', array( $this, 'display_single_trip_tabs_nav' ), 20 );
		add_action( 'wte_single_trip_content', array( $this, 'display_single_trip_tabs_content' ), 25 );
		add_action( 'wte_single_trip_footer', array( $this, 'display_single_trip_footer' ), 5 );
		add_action( 'wp_travel_engine_after_trip_content', array( $this, 'trip_content_wrapper_end' ), 5 );
		add_action( 'wp_travel_engine_trip_sidebar', array( $this, 'trip_content_sidebar' ), 5 );
		add_action( 'wp_travel_engine_primary_wrap_close', array( $this, 'trip_wrappers_end' ), 5 );

		add_action( 'wte_single_trip_tab_content_wp_editor', array( $this, 'display_wp_editor_content' ), 10, 4 );
		add_action( 'wte_single_trip_tab_content_itinerary', array( $this, 'display_itinerary_content' ), 10, 4 );
		add_action( 'wte_single_trip_tab_content_cost', array( $this, 'display_cost_content' ), 10, 4 );
		add_action( 'wte_single_trip_tab_content_faqs', array( $this, 'display_faqs_content' ), 10, 4 );
		add_action( 'wte_single_trip_tab_content_map', array( $this, 'display_map_content' ), 10, 4 );
		add_action( 'wte_single_trip_tab_content_review', array( $this, 'display_review_content' ), 10, 4 );

		add_action( 'wp_travel_engine_trip_secondary_wrap', array( $this, 'trip_secondary_wrap_start' ), 5 );
		add_action( 'wp_travel_engine_trip_price', array( $this, 'display_trip_price' ), 5 );
		add_action( 'wp_travel_engine_trip_facts', array( $this, 'display_trip_facts' ), 5 );
		add_action( 'wp_travel_engine_trip_secondary_wrap_close', array( $this, 'trip_secondary_wrap_close' ), 5 );

		add_action( 'wte_after_overview_content', array( $this, 'display_overview_trip_highlights' ), 999 );

		// Tab Titles.
		add_action( 'wte_overview_tab_title', array( $this, 'show_overview_title' ), 999 );
		add_action( 'wte_cost_tab_title', array( $this, 'show_cost_tab_title' ), 999 );
		add_action( 'wte_custom_t_tab_title', array( $this, 'show_custom_tab_title' ), 999 );
		add_action( 'wte_faqs_tab_title', array( $this, 'show_faqs_tab_title' ), 999 );
		add_action( 'wte_map_tab_title', array( $this, 'show_map_tab_title' ), 999 );
		add_action( 'wte_itinerary_tab_title', array( $this, 'show_itinerary_tab_title' ), 999 );

		add_action( 'wp_travel_engine_related_posts', [ __CLASS__, 'trip_related_trips' ], 15 );

	}

	/**
	 *
	 * @since 5.5.0
	 */
	public static function trip_related_trips() {
		$settings = get_option( "wp_travel_engine_settings", [] );
		if ( isset( $settings['show_related_trips'] ) && 'no' == $settings['show_related_trips'] ) {
			return;
		}
		$section_title = ! empty( $settings['related_trips_section_title'] ) ? $settings['related_trips_section_title'] : __( "Related trips you might interested in", 'wp-travel-engine' );

		$no_of_trips = ! empty( $settings['no_of_related_trips'] ) ? (int) $settings['no_of_related_trips'] : 3;

		$show_trip_by = ! empty( $settings['related_trip_show_by'] ) ? $settings['related_trip_show_by'] : 'activities';

		global $post;
		$terms = get_the_terms( $post->ID, $show_trip_by );
		wp_enqueue_style( 'wte-blocks-index' );
		$related_trips = new \WP_Query( [
			'post_type' => WP_TRAVEL_ENGINE_POST_TYPE,
			'posts_per_page'  => $no_of_trips,
			'post__not_in'    => [ $post->ID ],
			'orderby'        => 'rand',
			'tax_query'       => [
				array(
					'taxonomy' => $show_trip_by,
					'field'    => 'term_id',
					'terms'    => array_map( function( $term ) {
						return $term->term_id;
					}, is_array( $terms ) ? $terms : [] ),
				),
			]
		] );
		if ( $related_trips->have_posts() ) {
			wte_get_template( "content-related-trips.php", compact( "section_title", 'related_trips' ) );
		}
	}

	/**
	 * Displays multiple prices from different categories.
	 *
	 * @return void
	 */
	public static function categorised_trip_prices( $trip_id = null, $echo = true ) {
		if ( $trip_id ) {
			$trip = get_post( $trip );
		} else {
			global $post;
			$trip = $post;
		}
		if ( is_null( $trip ) || WP_TRAVEL_ENGINE_POST_TYPE != $trip->post_type ) {
			return;
		}

		global $wtetrip;

		if ( $wtetrip && $wtetrip->use_legacy_trip ) {
			return;
		}

		if ( ! $echo ) {
			ob_start();
		}

		global $wtetrip;

		$package            = $wtetrip->default_package;
		$package_categories = (object) $package->{'package-categories'};
		$pricing_categories = wte_get_terms_by_id(
			'trip-packages-categories',
			array(
				'hide_empty' => false,
			)
		);

		$primary_pricing_category = get_option( 'primary_pricing_category', 0 );

		$categories_in_package = $package_categories->c_ids;
		if ( in_array( $primary_pricing_category, $categories_in_package ) ) {
			$ids_as_key = array_combine( $categories_in_package, range( 1, count( $categories_in_package ) ) );
			unset( $ids_as_key[ $primary_pricing_category ] );
			$categories_in_package = array_keys( $ids_as_key );
			array_unshift( $categories_in_package, $primary_pricing_category );
		}

		foreach ( $categories_in_package as $c_id ) {
			$price = $package_categories->prices[ $c_id ];

			if ( '' === $price ) {
				continue;
			}
			$has_sale   = isset( $package_categories->enabled_sale[ $c_id ] ) && ( $package_categories->enabled_sale[ $c_id ] == '1' );
			$sale_price = $has_sale ? $package_categories->sale_prices[ $c_id ] : $price;

			$sale_price = apply_filters_deprecated( 'wp_travel_engine_trip_prev_price', array( $sale_price, $trip->ID ), '5.0', 'wte_before_formatting_price_figure', __( 'Replacing multiple filters with single filter', 'wp-travel-engine' ) );
			$price      = apply_filters( 'wp_travel_engine_trip_prev_price', $price, $trip->ID );
			$per_label  = ! empty( $pricing_categories[ $c_id ]->name ) ? $pricing_categories[ $c_id ]->name : $package_categories->labels[ $c_id ];

			$category_term_meta = get_term_meta( $c_id, 'pll_category_name', true );
			$locale             = get_locale();
			if ( ! empty( $category_term_meta[ substr( $locale, 0, 2 ) ] ) ) {
				$per_label = $category_term_meta[ substr( $locale, 0, 2 ) ];
			}
			// NOTE to Dev: Disabled this as per support request. Maybe add filter later ?
			// $per_label  = isset( $package_categories->pricing_types[ $c_id ] ) && 'per-group' === $package_categories->pricing_types[ $c_id ] ? __( 'Group', 'wp-travel-engine' ) : $per_label;

			$price_display_format = apply_filters(
				'categorised_trip_price_display_format',
				null,
				array(
					'trip_ID'     => $trip->ID,
					'category_id' => $c_id,
					'has_sale'    => $has_sale,
					'price'       => $price,
					'sale_price'  => $sale_price,
					'per_label'   => $per_label,
				)
			);

			if ( is_null( $price_display_format ) ) :
				$price_per_label = apply_filters( 'wptravelengine_price_per_label', __( '/ %s', 'wp-travel-engine' ) );
				?>
				<div class="wpte-bf-price">
					<span class="wpte-bf-reg-price">
						<span class="wpte-bf-price-from"><?php esc_html_e( 'From', 'wp-travel-engine' ); ?></span>
						<?php if ( $has_sale ) : ?>
							<del><?php \wte_the_formated_price( $price ); ?></del>
						<?php endif; ?>
					</span>
					<span class="wpte-bf-offer-price">
						<ins class="wpte-bf-offer-amount"><?php \wte_the_formated_price( $sale_price ); ?></ins>
						<div class="wpte-bf-pqty"><?php printf( esc_html( $price_per_label ), esc_html( $per_label ) ); ?></div>
					</span>
				</div>
				<?php
			else :
				echo wp_kses_post( (string) $price_display_format );
			endif;
		}

		if ( ! $echo ) {
			return ob_get_clean();
		}
	}

	/**
	 * Secondary wrap start.
	 */
	public function trip_secondary_wrap_start() {
		do_action( 'wp_travel_engine_before_secondary' );
		?>
			<div id="secondary" class="widget-area">
		<?php
	}

	/**
	 * Checkout page header steps.
	 *
	 * @return void
	 */
	public function checkout_header_steps() {
		// Get template for header crumbs.
		return wte_get_template( 'checkout/header-steps.php' );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 * @since 5.0.0
	 */
	public static function trip_price_sidebar() {
		global $wp_query;

		$queried_object = $wp_query->get_queried_object();

		if ( ! isset( $queried_object->post_type ) || WP_TRAVEL_ENGINE_POST_TYPE !== $queried_object->post_type ) {
			return;
		}
		$post = $queried_object;

		$trip_version = get_post_meta( $post->ID, 'trip_version', ! 0 );

		$price = Packages\get_trip_lowest_price( $post->ID );

		$wte_settings = get_option( 'wp_travel_engine_settings', array() );

		if ( ( isset( $wte_settings['booking'] ) && ! empty( $wte_settings['booking'] ) ) || empty( $price ) ) {
			return;
		}
		do_action( 'wp_travel_engine_before_trip_price' );
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/frontend/trip-meta/trip-meta-parts/trip-prices-sidebar-widget.php';
		do_action( 'wp_travel_engine_after_trip_price' );
	}

	/**
	 * Secondary content such as pricing for single trip.
	 */
	public function display_trip_price() {
		global $post;
		global $wtetrip;

		if ( $wtetrip && ! $wtetrip->use_legacy_trip ) {
			if ( ! empty( $wtetrip->packages ) ) {
				self::trip_price_sidebar();
			}
			return;
		}

		// Functions
		$functions     = \wte_functions();
		$currency_code = 'USD';
		$currency_code = $functions->trip_currency_code( $post );

		// Get global and post settings.
		$post_meta    = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
		$wte_settings = get_option( 'wp_travel_engine_settings', true );

		$price_per_text = isset( $post_meta['trip_price_per'] ) && ! empty( $post_meta['trip_price_per'] ) ? $post_meta['trip_price_per'] : 'per-person';

		// Get trip price.
		$is_sale_price_enabled = wp_travel_engine_is_trip_on_sale( $post->ID );
		$sale_price            = wp_travel_engine_get_sale_price( $post->ID );
		$regular_price         = wp_travel_engine_get_prev_price( $post->ID );
		$price                 = wp_travel_engine_get_actual_trip_price( $post->ID );
		// Don't load the trip price template, if the booking form hidden option is set.
		if ( isset( $wte_settings['booking'] ) ) {
			return;
		}

		// Don't load the template, if the regular price is not set.
		if ( '' === trim( $regular_price ) ) {
			return;
		}

		// Get booking steps.
		/**
		 * Converted into Associative array.
		 *
		 * @change 4.1.7 To keepup tab/step uniqueness.
		 */
		$booking_steps = array(
			'date'       => __( 'Select a Date', 'wp-travel-engine' ),
			'travellers' => __( 'Travellers', 'wp-travel-engine' ),
		);
		$booking_steps = apply_filters( 'wte_trip_booking_steps', $booking_steps );

		// Get placeholder.
		$wte_placeholder = isset( $wte_settings['pages']['wp_travel_engine_place_order'] ) ? $wte_settings['pages']['wp_travel_engine_place_order'] : '';

		do_action( 'wp_travel_engine_before_trip_price' );
		if ( defined( 'WTE_USE_OLD_BOOKING_PROCESS' ) && WTE_USE_OLD_BOOKING_PROCESS ) :
			require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/frontend/trip-meta/trip-meta-parts/trip-price-bak.php';
		else :
			require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/frontend/trip-meta/trip-meta-parts/trip-price.php';
		endif;
		do_action( 'wp_travel_engine_after_trip_price' );
	}

	/**
	 * Secondary content such as trip facts for single trip.
	 */
	public function display_trip_facts() {
		$settings  = get_option( 'wp_travel_engine_settings', true );
		if( isset( $settings['show_trip_facts'] ) && 'yes' === $settings['show_trip_facts'] && isset( $settings['show_trip_facts_sidebar'] ) && 'yes' === $settings['show_trip_facts_sidebar'] ){
			do_action( 'wp_travel_engine_before_trip_facts' );
			include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/frontend/trip-meta/trip-meta-parts/trip-facts.php';
			do_action( 'wp_travel_engine_after_trip_facts' );
		}
	}

	/**
	 * Secondary wrap close.
	 */
	public function trip_secondary_wrap_close() {
		?>
			</div>
			<!-- #secondary -->
		<?php
	}

	/**
	 * Trip Footer
	 */
	public function display_single_trip_footer() {
		wte_get_template( 'single-trip/trip-footer.php' );
	}

	/**
	 * Cost Includes/Excludes tab content
	 */
	public function display_cost_content( $id, $field, $name, $icon ) {
		global $post;

		$post_meta = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

		$data = array(
			'cost' => $post_meta['cost'],
		);

		wte_get_template( 'single-trip/trip-tabs/cost.php', $data );
	}

	/**
	 * Faqs tab content
	 */
	public function display_faqs_content( $id, $field, $name, $icon ) {
		global $post;

		$post_meta = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

		$data = array(
			'faq' => $post_meta['faq'],
		);

		wte_get_template( 'single-trip/trip-tabs/faqs.php', $data );
	}

	/**
	 * Map Tab content.
	 */
	public function display_map_content( $id, $field, $name, $icon ) {
		global $post;
		// $post_meta = get_post_meta($post->ID, 'wp_travel_engine_setting', true);

		$data = array(
			'post_id' => $post->ID,
		);

		wte_get_template( 'single-trip/trip-tabs/map.php', $data );
	}

	/**
	 * Review Tab content
	 */
	public function display_review_content( $id, $field, $name, $icon ) {
		global $post;

		$post_meta = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

		$title = isset( $post_meta['review']['review_title'] ) && '' != $post_meta['review']['review_title']
		? $post_meta['review']['review_title'] : '';

		$data = array(
			'id'    => $post->ID,
			'title' => $title,
		);

		wte_get_template( 'single-trip/trip-tabs/review.php', $data );
	}

	/**
	 * Itinerary Tab Content
	 */
	public function display_itinerary_content( $id, $field, $name, $icon ) {
		wte_get_template( 'single-trip/trip-tabs/itinerary-tab.php' );
	}

	/**
	 * Overview/WPeditor Tab
	 */
	public function display_wp_editor_content( $id, $field, $name, $icon ) {
		global $post;
		$post_meta = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

		$key = "{$id}_wpeditor";

		if ( '1' == $id ) {
			$data = array(
				'overview' => $post_meta['tab_content'][ $key ],
			);
			wte_get_template( 'single-trip/trip-tabs/overview.php', $data );
		} else {
			$data = array(
				'editor' => $post_meta['tab_content'][ $key ],
				'name'   => sanitize_title( $name ),
				'id'     => $id,
			);
			wte_get_template( 'single-trip/trip-tabs/editor.php', $data );
		}
	}

	/**
	 * Trip tabs content
	 */
	public function display_single_trip_tabs_content() {
		$settings = wte_get_active_single_trip_tabs();

		if ( false === $settings ) {
			return;
		}

		$data = array(
			'tabs' => $settings['trip_tabs'],
		);

		wte_get_template( 'single-trip/tabs-content.php', $data );
	}

	/**
	 * Trip tabs nav
	 */
	public function display_single_trip_tabs_nav() {
		$settings = wte_get_active_single_trip_tabs();

		if ( false === $settings ) {
			return;
		}

		$data = array(
			'tabs' => $settings['trip_tabs'],
		);

		wte_get_template( 'single-trip/tabs-nav.php', $data );
	}

	/**
	 * Single Trip title.
	 */
	public function display_single_trip_title() {
		global $post;

		$post_meta = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

		$duration = isset( $post_meta['trip_duration'] ) && '' != $post_meta['trip_duration']
		? $post_meta['trip_duration'] : '';
		$duration_unit = isset( $post_meta['trip_duration_unit'] ) && '' != $post_meta['trip_duration_unit']
		? $post_meta['trip_duration_unit'] : 'days';

		wte_get_template( 'single-trip/title.php', compact( 'duration', 'duration_unit' ) );
	}

	/**
	 * Single Trip Feat Image or Gallery.
	 */
	public function display_single_trip_gallery() {

		do_action( 'wp_travel_engine_feat_img_trip_galleries' );

		if ( ! has_action( 'wp_travel_engine_feat_img_trip_galleries' ) ) {
			wte_get_template( 'single-trip/gallery.php', ['is_main_slider' => true] );
		}
	}

	/**
	 * Single Trip content
	 */
	public function display_single_trip_content() {
		global $post;

		$settings  = get_option( 'wp_travel_engine_settings', true );
		$post_meta = get_post_meta( $post->ID, 'WTE_Fixed_Starting_Dates_setting', true );

		$data = array(
			'settings'  => $settings,
			'post_meta' => $post_meta,
		);

		wte_get_template( 'single-trip/trip-content.php', $data );
	}

	/**
	 * Trip Facts section in single trip.
	 */
	public function display_single_trip_facts(){

		$settings  = get_option( 'wp_travel_engine_settings', array() );
		if( isset( $settings['show_trip_facts'] ) && 'yes' === $settings['show_trip_facts'] && isset( $settings['show_trip_facts_content_area'] ) && 'yes' === $settings['show_trip_facts_content_area'] ){
			require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/frontend/trip-meta/trip-meta-parts/trip-facts.php';
		}
	}

	/**
	 * Main wrap of the single trip.
	 */
	public function trip_content_wrapper_start() {
		wte_get_template( 'single-trip/trip-content-wrapper-start.php' );
	}

	/**
	 * Main wrap end of the single trip.
	 */
	public function trip_content_wrapper_end() {
		wte_get_template( 'single-trip/trip-content-wrapper-end.php' );
	}

	/**
	 * Trip Wrapper close.
	 */
	public function trip_wrappers_end() {
		?>
			</div>
			<!-- #primary -->
		</div>
		<!-- .row -->
		<?php
			do_action( 'wp_travel_engine_before_related_posts' );
			do_action( 'wp_travel_engine_related_posts' );
			do_action( 'wp_travel_engine_after_related_posts' );
		?>
	</div>
	<!-- .trip-content-area  -->
		<?php
	}

	/**
	 * Sidebar of the single trip.
	 */
	public function trip_content_sidebar() {
		wte_get_template( 'single-trip/trip-sidebar.php' );
	}

	/**
	 * Booking form traveller input fields.
	 *
	 * @return void
	 */
	public function booking_form_traveller_inputs() {

		global $post;

		$trip_id = $post->ID;

		$post_meta = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

		// Get trip price.
		$is_sale_price_enabled = wp_travel_engine_is_trip_on_sale( $post->ID );
		$sale_price            = wp_travel_engine_get_sale_price( $post->ID );
		$regular_price         = wp_travel_engine_get_prev_price( $post->ID );
		$price                 = wp_travel_engine_get_actual_trip_price( $post->ID );

		$this->booking_form_multiple_pricing_inputs( $trip_id, $price );

	}

	public function display_multi_pricing_info() {
		$wte_options = get_option( 'wp_travel_engine_settings', true );

		// Bail if disabled.
		if ( ! isset( $wte_options['show_multiple_pricing_list_disp'] ) || '1' != $wte_options['show_multiple_pricing_list_disp'] ) {
			return;
		}

		global $post;
		global $wtetrip;

		// If trip is migrated, call new function to render price.
		if ( $wtetrip && ! $wtetrip->use_legacy_trip ) {
			call_user_func( array( __CLASS__, 'categorised_trip_prices' ) );
			return;
		}

		// Don't show the child price info, if the multi pricing is for child is set.
		$trip_settings            = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
		$multiple_pricing_options = isset( $trip_settings['multiple_pricing'] ) && ! empty( $trip_settings['multiple_pricing'] ) ? $trip_settings['multiple_pricing'] : false;
		if ( $multiple_pricing_options ) :
			foreach ( $multiple_pricing_options as $price_key => $multiple_pricing ) :
				if ( '' === $multiple_pricing['price'] ) {
					continue;
				}

				$is_sale = false;
				if ( isset( $multiple_pricing['enable_sale'] ) && '1' === $multiple_pricing['enable_sale'] ) {
					$is_sale = true;
				}

				if ( isset( $multiple_pricing['sale_price'] ) ) {
					$sale_price = apply_filters( 'wp_travel_engine_trip_prev_price', $multiple_pricing['sale_price'], $post->ID );
				}

				if ( isset( $multiple_pricing['price'] ) ) {
					$regular_price = apply_filters( 'wp_travel_engine_trip_prev_price', $multiple_pricing['price'], $post->ID );
				}

				$price = $regular_price;
				if ( $is_sale ) {
					$price = $sale_price;
				}
				?>
				<?php $a = 1; ?>
			<div class="wpte-bf-price">
				<?php if ( $is_sale ) : ?>
					<del>
					<?php echo wp_kses( wte_get_formated_price_html( $regular_price ), array( 'span' => array( 'class' => array() ) ) ); ?>
					</del>
				<?php endif; ?>
					<ins>
				<?php echo wp_kses( wte_get_formated_price_html( $price ), array( 'span' => array( 'class' => array() ) ) ); ?></b>
			</ins>
			<span class="wpte-bf-pqty"><?php esc_html_e( 'Per', 'wp-travel-engine' ); ?> <?php echo esc_html( $multiple_pricing['label'] ); ?></span>
		</div>

				<?php
			endforeach;
		endif;
	}

	/**
	 * Load booking form input fields
	 *
	 * @return void
	 */
	public function booking_form_default_traveller_inputs( $price ) {
		?>
			<div class="wpte-bf-traveler-block">
				<div class="wpte-bf-traveler">
					<div class="wpte-bf-number-field">
						<input type="text" name="add-member" value="1" min="0" max="99999999999999"
							disabled
							data-cart-field = "travelers"
							data-cost-field = 'travelers-cost'
							data-type = '<?php echo esc_html( apply_filters( 'wte_default_traveller_type', __( 'Person', 'wp-travel-engine' ) ) ); ?>'
							data-cost="<?php echo esc_attr( $price ); ?>" />
						<button class="wpte-bf-plus">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M368 224H224V80c0-8.84-7.16-16-16-16h-32c-8.84 0-16 7.16-16 16v144H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h144v144c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V288h144c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"></path></svg>
						</button>
						<button class="wpte-bf-minus">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M368 224H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h352c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"></path></svg>
						</button>
					</div>
					<span><?php echo esc_html( apply_filters( 'wte_default_traveller_type', __( 'Person', 'wp-travel-engine' ) ) ); ?></span>
				</div>
				<div class="wpte-bf-price">
					<ins>
						<?php echo wp_kses( wte_get_formated_price_html( $price ), array( 'span' => array( 'class' => array() ) ) ); ?></b>
					</ins>
					<span class="wpte-bf-pqty"><?php echo esc_html( apply_filters( 'wte_default_traveller_unit', __( 'Per Person', 'wp-travel-engine' ) ) ); ?></span>
				</div>
			</div>
		<?php
		do_action( 'wpte_after_travellers_input' );

	}

	/**
	 * Multiple pricing input fields.
	 *
	 * @return void
	 */
	public function booking_form_multiple_pricing_inputs( $trip_id, $default_price ) {

		$trip_settings                             = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
		$multiple_pricing_options                  = isset( $trip_settings['multiple_pricing'] ) && ! empty( $trip_settings['multiple_pricing'] ) ? $trip_settings['multiple_pricing'] : false;
		$multiple_pricing_is_adult_price_available = $this->multiple_pricing_is_adult_price_available( $trip_id );
		if ( $multiple_pricing_options && $multiple_pricing_is_adult_price_available ) :
			foreach ( $multiple_pricing_options as $key => $pricing_option ) :
				$min_pax     = isset( $pricing_option['min_pax'] ) && ! empty( $pricing_option['min_pax'] ) ? $pricing_option['min_pax'] : 0;
				$max_pax     = isset( $pricing_option['max_pax'] ) && ! empty( $pricing_option['max_pax'] ) ? $pricing_option['max_pax'] : 999999999;
				$enable_sale = isset( $pricing_option['enable_sale'] ) && '1' == $pricing_option['enable_sale'] ? true : false;

				$price         = $enable_sale && isset( $pricing_option['sale_price'] ) && ! empty( $pricing_option['sale_price'] ) ? $pricing_option['sale_price'] : $pricing_option['price'];
				$pricing_label = isset( $pricing_option['label'] ) ? $pricing_option['label'] : ucfirst( $key );
				$value         = 'adult' === $key ? '1' : 0;
				$min_pax       = 0;

				$pricing_type = isset( $pricing_option['price_type'] ) && ! empty( $pricing_option['price_type'] ) ? $pricing_option['price_type'] : 'per-person';

				if ( '' === $price ) {
					continue;
				}

				// $price = apply_filters( 'wte_multi_pricing', $price, $trip_id );

				?>
					<div class="wpte-bf-traveler-block">
						<div class="wpte-bf-traveler">
							<div class="wpte-bf-number-field">
								<input type="text" name="add-member" value="<?php echo esc_attr( $value ); ?>" min="<?php echo esc_attr( $min_pax ); ?>" max="<?php echo esc_attr( $max_pax ); ?>"
									disabled
									data-cart-field = "pricing_options[<?php echo esc_attr( $key ); ?>][pax]"
									data-cost-field = 'pricing_options[<?php echo esc_attr( $key ); ?>][cost]'
									data-type = '<?php echo esc_attr( $key ); ?>'
									data-cost="<?php echo esc_attr( $price ); ?>"
									data-pricing-type="<?php echo esc_attr( $pricing_type ); ?>"/>
								<button class="wpte-bf-plus">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M368 224H224V80c0-8.84-7.16-16-16-16h-32c-8.84 0-16 7.16-16 16v144H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h144v144c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V288h144c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"></path></svg>
								</button>
								<button class="wpte-bf-minus">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M368 224H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h352c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"></path></svg>
								</button>
							</div>
							<span><?php echo esc_html( $pricing_label ); ?></span>
						</div>
						<div class="wpte-bf-price">
							<ins>
								<?php echo wp_kses( wte_get_formated_price_html( $price ), array( 'span' => array( 'class' => array() ) ) ); ?></b>
							</ins>
							<span class="wpte-bf-pqty"><?php echo esc_html( apply_filters( 'wte_default_pricing_option_unit_' . $key, sprintf( __( 'Per %1$s', 'wp-travel-engine' ), $pricing_label ) ) ); ?></span>
						</div>
					</div>
				<?php
			endforeach;
		else :
			$this->booking_form_default_traveller_inputs( $default_price );
		endif;

	}

	/**
	 * Check if adult price available in multiple pricing
	 *
	 * @return void
	 */
	public function multiple_pricing_is_adult_price_available( $trip_id ) {

		$trip_settings            = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
		$multiple_pricing_options = isset( $trip_settings['multiple_pricing'] ) && ! empty( $trip_settings['multiple_pricing'] ) ? $trip_settings['multiple_pricing'] : false;

		if ( ! $multiple_pricing_options ) {
			return false;
		}

		if ( isset( $multiple_pricing_options['adult'] ) ) {

			$pricing_option = $multiple_pricing_options['adult'];
			$enable_sale    = isset( $pricing_option['enable_sale'] ) && '1' == $pricing_option['enable_sale'] ? true : false;
			$price          = $enable_sale && isset( $pricing_option['sale_price'] ) && ! empty( $pricing_option['sale_price'] ) ? $pricing_option['sale_price'] : $pricing_option['price'];

			return ! empty( $price );

		}
		return false;
	}

	/**
	 * Display Trip highlights section
	 *
	 * @return void
	 */
	public function display_overview_trip_highlights() {
		global $post;

		$trip_id       = $post->ID;
		$post_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );

		$trip_highlights_title   = isset( $post_settings['trip_highlights_title'] ) ? $post_settings['trip_highlights_title'] : '';
		$trip_highlights_content = isset( $post_settings['trip_highlights'] ) ? $post_settings['trip_highlights'] : array();

		if ( ! empty( $trip_highlights_content ) && is_array( $trip_highlights_content ) ) {
			if ( ! empty( $trip_highlights_title ) ) {
				echo "<h3 class='wpte-trip-highlights-title'>" . esc_html( $trip_highlights_title ) . '</h3>';
				echo "<ul class='wpte-trip-highlights' >";
				foreach ( $trip_highlights_content as $key => $highlight ) {
					$highlight = isset( $highlight['highlight_text'] ) && ! empty( $highlight['highlight_text'] ) ? $highlight['highlight_text'] : false;

					if ( $highlight ) {
						echo "<li class='trip-highlight'>" . esc_html( $highlight ) . '</li>';
					}
				}
				echo '</ul>';
			}
		}
	}

	// Tab section title hooks.
	public function show_overview_title() {

		$show_tab_titles = apply_filters( 'wpte_show_tab_titles_inside_tabs', true );

		if ( ! $show_tab_titles ) {
			return;
		}

		global $post;
		$trip_id = $post->ID;

		$trip_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
		$tab_title     = isset( $trip_settings['overview_section_title'] ) && ! empty( $trip_settings['overview_section_title'] ) ? $trip_settings['overview_section_title'] : false;

		if ( $tab_title ) {
			echo "<h2 class='wpte-overview-title'>" . esc_html( $tab_title ) . '</h2>';
		}
	}

	// Tab section title hooks.
	public function show_cost_tab_title() {

		$show_tab_titles = apply_filters( 'wpte_show_tab_titles_inside_tabs', true );

		if ( ! $show_tab_titles ) {
			return;
		}

		global $post;
		$trip_id = $post->ID;

		$trip_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
		$tab_title     = isset( $trip_settings['cost_tab_sec_title'] ) && ! empty( $trip_settings['cost_tab_sec_title'] ) ? $trip_settings['cost_tab_sec_title'] : false;

		if ( $tab_title ) {
			echo "<h2 class='wpte-cost-tab-title'>" . esc_html( $tab_title ) . '</h2>';
		}
	}

	// Tab section title hooks.
	public function show_itinerary_tab_title() {

		$show_tab_titles = apply_filters( 'wpte_show_tab_titles_inside_tabs', true );

		if ( ! $show_tab_titles ) {
			return;
		}

		global $post;
		$trip_id = $post->ID;

		$trip_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
		$tab_title     = isset( $trip_settings['trip_itinerary_title'] ) && ! empty( $trip_settings['trip_itinerary_title'] ) ? $trip_settings['trip_itinerary_title'] : false;

		if ( $tab_title ) {
			if ( defined( "WTEAI_VERSION" ) ) {
				echo "<h2 class='wpte-itinerary-title'>" . esc_html( $tab_title ) . '</h2>';
			} else {
			?>
			<div class="wte-itinerary-header-wrapper">
				<div class="wp-travel-engine-itinerary-header">
					<h2 class='wpte-itinerary-title'><?php echo esc_html( $tab_title ); ?></h2>
					<div class="aib-button-toggle toggle-button expand-all-button">
						<label for="itinerary-toggle-button" class="aib-button-label"><?php echo esc_html__( 'Expand all', 'wp-travel-engine' ); ?></label>
						<input id="itinerary-toggle-button" type="checkbox" class="checkbox" checked>
					</div>
				</div>
			</div>
			<?php
			}
		}
	}

	// Tab section title hooks.
	public function show_faqs_tab_title() {

		$show_tab_titles = apply_filters( 'wpte_show_tab_titles_inside_tabs', true );

		if ( ! $show_tab_titles ) {
			return;
		}

		global $post;
		$trip_id = $post->ID;

		$trip_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
		$tab_title     = isset( $trip_settings['faq_section_title'] ) && ! empty( $trip_settings['faq_section_title'] ) ? $trip_settings['faq_section_title'] : false;

		if ( $tab_title ) {
			echo "<h2 class='wpte-faqs-title'>" . esc_html( $tab_title ) . '</h2>';
		}
	}

	// Tab section title hooks.
	public function show_map_tab_title() {

		$show_tab_titles = apply_filters( 'wpte_show_tab_titles_inside_tabs', true );

		if ( ! $show_tab_titles ) {
			return;
		}

		global $post;
		$trip_id = $post->ID;

		$trip_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
		$tab_title     = isset( $trip_settings['map_section_title'] ) && ! empty( $trip_settings['map_section_title'] ) ? $trip_settings['map_section_title'] : false;

		if ( $tab_title ) {
			echo "<h2 class='wpte-map-title'>" . esc_html( $tab_title ) . '</h2>';
		}
	}

	// Tab section title hooks.
	public function show_custom_tab_title( $tab_key ) {

		$show_tab_titles = apply_filters( 'wpte_show_tab_titles_inside_tabs', true );

		if ( ! $show_tab_titles ) {
			return;
		}

		global $post;
		$trip_id = $post->ID;

		$trip_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
		$tab_title     = isset( $trip_settings[ 'tab_' . $tab_key . '_title' ] ) && ! empty( $trip_settings[ 'tab_' . $tab_key . '_title' ] ) ? $trip_settings[ 'tab_' . $tab_key . '_title' ] : false;

		if ( $tab_title ) {
			echo "<h2 class='wpte-" . esc_html( $tab_key ) . "-title'>" . esc_html( $tab_title ) . '</h2>';
		}
	}

}

WP_Travel_Engine_Template_Hooks::get_instance();
