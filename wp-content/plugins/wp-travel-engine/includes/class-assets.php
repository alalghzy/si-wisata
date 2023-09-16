<?php
/**
 * Assets Management for WP Travel Engine.
 *
 * @package wp-travel-engine
 */

namespace WPTravelEngine;

use \WPTravelEngine\Core\Functions;

/**
 * Class \WPTravelEngine\Assets
 *
 * @since 5.3.1
 */
class Assets {
	/**
	 * Holds instance of the class.
	 *
	 * @var WPTravelEngine\Assets $instance Instance.
	 */
	protected static $instance = null;

	/**
	 * Provides active instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->plugin_name = 'wp-travel-engine';
		$this->version     = \WP_TRAVEL_ENGINE_VERSION;
	}

	public function is_fontawesome_enabled() {
		$settings = get_option( 'wp_travel_engine_settings', array() );
		return apply_filters( 'wte_enable_fontawesome', ! isset( $settings['disable_fa_icons_js'] ) || ( $settings['disable_fa_icons_js'] !== 'yes' ) );
	}

	/**
	 * Register Libraries.
	 */
	public function get_external_libraries() {
		$url_prefix = apply_filters( 'wte_vendors_directory', plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . 'assets/lib/' );
		$libs       = array(
			'owl-carousel'          => array(
				'js'  => array( $url_prefix . 'owl-carousel-2.3.4/owl.carousel.js', array( 'jquery' ), '2.3.4', true ),
				'css' => array( $url_prefix . 'owl-carousel-2.3.4/owl.carousel.css', array(), '2.3.4' ),
			),
			'slick'                 => array(
				'js'  => array( $url_prefix . 'slick/slick-min-js.js', array( 'jquery' ), '2.3.4', true ),
				'css' => array( $url_prefix . 'slick/slick.min.css', array(), '2.3.4' ),
			),
			'wte-nouislider'        => array(
				'js'  => array( $url_prefix . 'nouislider/nouislider.min.js', array(), '2.3.4', false ),
				'css' => array( $url_prefix . 'nouislider/nouislider.min.css', array( 'wp-travel-engine' ), '2.3.4' ),
			),
			'parsley'               => array( 'js' => array( $url_prefix . 'parsley-min.js', array( 'jquery' ), '2.9.2', true ) ),
			'jquery-fancy-box'      => array(
				'css' => array( $url_prefix . 'fancybox/jquery.fancybox.min.css', array(), '3.5.7' ),
				'js'  => array( $url_prefix . 'fancybox/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true ),
			),
			'jquery-steps'          => array( 'js' => array( $url_prefix . 'jquery-steps.min.js', array( 'jquery', 'jquery-ui-core' ), $this->version, true ) ),
			'jquery-validate'       => array( 'js' => array( $url_prefix . 'jquery.validate.min.js', array( 'jquery' ), '1.19.1', true ) ),
			'wte-fontawesome-all'   => array( 'js' => array( $url_prefix . 'fontawesome/all.min.js', array(), '5.6.3', true ) ),
			'v4-shims'              => array( 'js' => array( $url_prefix . 'fontawesome/v4-shims.min.js', array(), '5.6.3', true ) ),
			'wte-fontawesome'       => array( 'js' => array( $url_prefix . 'fontawesome/fontawesome.bundle.js', array(), '5.6.3', false ) ),
			'jquery-sticky-kit'     => array( 'js' => array( $url_prefix . 'jquery.sticky-kit.js', array( 'jquery' ), null, true ) ),
			'toastr'                => array(
				'js'  => array( $url_prefix . 'toastr/toastr.min.js', array( 'jquery' ), null, true ),
				'css' => array( $url_prefix . 'toastr/toastr.min.css', array(), $this->version ),
			),
			'wte-select2'           => array(
				'js'  => array( $url_prefix . 'select2-4.0.13/select2.js', array( 'jquery' ), '4.0.13', true ),
				'css' => array( $url_prefix . 'select2-4.0.13/select2.css', array(), '4.0.13' ),
			),
			'wte-rrule'             => array( 'js' => array( $url_prefix . 'rrule.min.js', array( 'jquery' ), '3.3.2', true ) ),
			'wte-fpickr'            => array(
				'js'  => array( $url_prefix . 'flatpickr-4.6.9/fpickr.js', array(), '4.6.9', true ),
				'css' => array( $url_prefix . 'flatpickr-4.6.9/fpickr.css', array(), '4.6.9' ),
			),
			'wte-fpickr-l10n'       => array(
				'js' => array( $url_prefix . 'flatpickr-4.6.9/l10n/default.js', array( 'wte-fpickr' ), '4.6.9', true ),
			),
			'wte-highlightjs'       => array(
				'js'  => array( $url_prefix . 'highlightjs-10.5.0/highlight.pack.js', array(), '10.5.0', true ),
				'css' => array( $url_prefix . 'highlightjs-10.5.0/highlight.pack.css', array(), '10.5.0' ),
			),
			'wte-redux'             => array( 'js' => array( $url_prefix . 'redux.min.js', array( 'wp-redux-routine' ), '4.0.5', true ) ),
			'wte-rxjs'              => array( 'js' => array( $url_prefix . 'rxjs.umd.js', array(), '6.6.6', ! 0 ) ),
			'wte-moment-tz'         => array( 'js' => array( $url_prefix . 'moment/moment-tz.js', array( 'moment' ), '0.5.33', true ) ),
			'wte-custom-niceselect' => array(
				'js'  => array( $url_prefix . 'nice-select/jquery.nice-select.min.js', array( 'jquery' ), '1.0', true ),
				'css' => array( $url_prefix . 'nice-select/nice-select.css', array(), '1.0' ),
			),
			'wte-custom-scrollbar'  => array(
				'js'  => array( $url_prefix . 'custom-scrollbar/jquery.mCustomScrollbar.concat.min.js', array( 'jquery' ), '3.1.13', true ),
				'css' => array( $url_prefix . 'custom-scrollbar/jquery.mCustomScrollbar.min.css', array(), '3.1.13' ),
			),
			'datepicker-style'      => array( 'css' => array( $url_prefix . 'datepicker/datepicker-style.css', array(), '1.11.4' ) ),
			'animate'               => array( 'css' => array( $url_prefix . 'animate.css', array(), '3.5.2' ) ),
			'jquery-ui'             => array( 'css' => array( $url_prefix . 'jquery-ui.css', array(), '1.12.1', 'all' ) ),
			'wte-icons'             => array( 'css' => array( $url_prefix . 'wte-icons/style.css', array(), '1.0.0' ) ),
			'wte-dropzone'          => array(
				'js'  => array( $url_prefix . 'dropzone/dropzone.min.js', array(), '5.9.2', true ),
				'css' => array( $url_prefix . 'dropzone/dropzone.min.css', array(), '5.9.2' ),
			),
			'wte-popper'          => array(
				'js'  => array( $url_prefix . 'tippy/popper.js', array(), '1.0.0', true ),
			),
			'wte-tippyjs'          => array(
				'js'  => array( $url_prefix . 'tippy/tippy.js', array(), '5.0.0', true ),
			),
		);

		if ( ! $this->is_fontawesome_enabled() ) {
			unset( $libs['wte-fontawesome-all'] );
			unset( $libs['v4-shims'] );
		}

		return apply_filters( 'wte_external_libraries', $libs );
	}

	/**
	 * Common assets shared between Admin and Client Side.
	 */
	public function get_common_assets() {
		$url_prefix = apply_filters( 'wte_common_assets_directory', plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) );

		$development = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

		$suffix = $development ? '' : '';
		$assets = array(
			'regenerator-runtime' => array( 'js' => array( $url_prefix . "dist/global/regenerator-runtime{$suffix}.js", array(), '0.13.7', true ) ),
			'wte-global'          => array(
				'js'            => array( $url_prefix . "dist/global/wte-global{$suffix}.js", array( 'lodash', 'regenerator-runtime' ), filemtime( plugin_dir_path( \WP_TRAVEL_ENGINE_FILE_PATH ) . "dist/global/wte-global{$suffix}.js" ), true ),
				'localize_data' => array( 'wteL10n', array( $this, 'get_global_localize_data' ) ),
				'css'           => array( $url_prefix . "dist/global/wte-global{$suffix}.css", array(), filemtime( plugin_dir_path( \WP_TRAVEL_ENGINE_FILE_PATH ) . "dist/global/wte-global{$suffix}.css" ) ),
			),
		);

		return apply_filters( 'wte_common_assets', $assets, $suffix, $this->version );
	}

	public static function append_dependency( $handle, $dependency ) {
		global $wp_scripts;

		$script = $wp_scripts->query( $handle, 'registered' );
		if ( ! $script ) return false;

		if ( !in_array( $dependency, $script->deps ) ){
			$script->deps[] = $dependency;
		}

		return true;
	}

	/**
	 * Registers all the required Scripts.
	 *
	 * @param array $assets Assets to be registered.
	 */
	public function register_scripts( $assets = array() ) {
		// Library Scripts Registration.
		$libs = $this->get_external_libraries();
		$common_assets = $this->get_common_assets();

		$registering_assets = array_merge( $libs, $common_assets, $assets );
		foreach ( $registering_assets as $handle => $params_array ) {

			foreach ( $params_array as $type => $_args ) {
				switch ( $type ) {
					case 'js':
							list( $url, $dependencies, $version, $in_footer ) = $_args;
							$in_footer = isset( $in_footer ) && $in_footer;
							wp_register_script( $handle, $url, $dependencies, $version, $in_footer );
						break;
					case 'css':
						list( $url, $dependencies, $version ) = $_args;

						$media = isset( $_args[3] ) ? $_args[3] : 'all';
						wp_register_style( $handle, $url, $dependencies, $version, $media );
						break;
					case 'localize_data':
						list( $object_name, $data ) = $_args;

						$data = is_callable( $data ) ? call_user_func( $data, $this ) : $data;
						wp_localize_script( $handle, $object_name, $data );
						break;
				}
			}
		}

	}

	/**
	 * Localize Scripts.
	 */
	public function get_global_localize_data() {
		$settings = get_option( 'wp_travel_engine_settings', array() );

		$base_currency = wte_array_get( $settings, 'currency_code', 'USD' );
		$currency      = apply_filters( 'wp_travel_engine_currency_code', $base_currency, ! 1 );

		$extensions = array();
		foreach ( array(
			'wte-extra-services'            => 'WTE_EXTRA_SERVICES_VERSION',
			'wte-trip-reviews'              => 'WTE_TRIP_REVIEW_VERSION',
			'wte-trip-fixed-starting-dates' => 'WTE_FIXED_DEPARTURE_VERSION',
			'wte-currency-converter'        => 'WTE_CURRENCY_CONVERTER_VERSION',
		) as $slug => $constant_name ) {
			if ( defined( $constant_name ) ) {
				$extensions[ $slug ] = \constant( $constant_name );
			}
		}

		$l10n = array(
			'version'            => $this->version,
			'baseCurrency'       => $base_currency,
			'baseCurrencySymbol' => Functions::currency_symbol_by_code( $base_currency ),
			'currency'           => $currency,
			'currencySymbol'     => Functions::currency_symbol_by_code( $currency ),
			'_nonces'            => array(
				'addtocart' => wp_create_nonce( 'wte_add_trip_to_cart' ),
			),
			'wpapi'              => array(
				'root'          => esc_url_raw( rest_url() ),
				'nonce'         => wp_create_nonce( 'wp_rest' ),
				'versionString' => 'wp/v2/',
			),
			'wpxhr'              => array(
				'root'  => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
				'nonce' => wp_create_nonce( 'wp_xhr' ),
			),
			'format'             => array(
				'number'   => array(
					'decimal'           => wte_array_get( $settings, 'decimal_digits', 0 ),
					'decimalSeparator'  => wte_array_get( $settings, 'decimal_separator', '.' ),
					'thousandSeparator' => wte_array_get( $settings, 'thousands_separator', ',' ),
				),
				'price'    => wte_array_get( $settings, 'amount_display_format', '%CURRENCY_SYMBOL%%FORMATED_AMOUNT%' ),
				'date'     => get_option( 'date_format', 'Y-m-d' ),
				'time'     => get_option( 'time_format', 'g:i a' ),
				'datetime' => array(
					'date'      => get_option( 'date_format', 'Y-m-d' ),
					'time'      => get_option( 'time_format', 'g:i a' ),
					'GMTOffset' => wte_get_timezone_info(),
					'timezone'  => get_option( 'timezone_string', '' ),
				),
			),
			'extensions'         => apply_filters( 'wte_active_extensions', $extensions ),
			'locale'             => get_locale(),
			'l10n'               => wp_parse_args(
				wte_default_labels(),
				array(
					// Translators: %s: Minimum Number of Traveller.
					'invalidCartTraveler'  => __( 'No. of Travellers\' should be at least %s', 'wp-travel-engine' ),
					// Translators: %s: Maximum Number of Traveller.
					'availableSeatsExceed' => __( 'The number of pax can not exceed more than %s', 'wp-travel-engine' ),
				)
			),
			'layout' => array(
				'showFeaturedTripsOnTop' => ! isset( $settings['show_featured_trips_on_top'] ) || 'yes' === $settings['show_featured_trips_on_top'],
			),
		);

		if ( function_exists( 'pll_current_language' ) && \pll_current_language() ) {
			$l10n['locale'] = \pll_current_language();
		}

		global $post;
		global $wtetrip;
		if ( $post instanceof \WP_Post && ( \WP_TRAVEL_ENGINE_POST_TYPE === $post->post_type ) && $wtetrip ) {
			$trip_version = get_post_meta( $post->ID, 'trip_version', true );
			if ( empty( $trip_version ) ) {
				$trip_version = '0.0.0';
			}
			$l10n['tripID']      = (int) $post->ID;
			$l10n['tripVersion'] = $trip_version;
			$l10n['legacy']      = $wtetrip->use_legacy_trip;
		}

		return apply_filters( 'wtel10n', $l10n );
	}

	/**
	 *
	 * @since __release_version__
	 */
	public function add_script_attributes( $tag, $handle, $source ) {
		$handles = array( 'wte-fontawesome', 'wte-nouislider' );
		if ( in_array( $handle, $handles, true ) ) {
			$tag = str_replace( 'src=', 'defer async src=', $tag );
		}
		return $tag;
	}

	/**
	 *
	 * @since 5.5.0
	 */
	public function add_style_attributes( $tag, $handle, $source ) {

		// onload='this.onload=null;this.rel=\'stylesheet\''
		$handles = array( 'wp-travel-engine' );
		if ( in_array( $handle, $handles, true ) ) {
			$tag = str_replace( "rel='stylesheet'", "rel='preload' as=\"style\" onload=\"this.onload=null;this.rel='stylesheet'\"", $tag );
		}
		return $tag;
	}

	/**
	 *
	 * @since 5.5.0
	 */
	public function backward_compatibility_dependencies( $default = array() ) {
		$deps = array();
		if ( defined( 'WTE_TRIP_REVIEW_VERSION' ) && version_compare( WTE_TRIP_REVIEW_VERSION, '2.1.3', '<' ) ) {
			$deps[] = 'jquery-ui-datepicker';
		}
		return array_merge( $default, $deps );
	}

	/**
	 * Public Assets.
	 */
	public function wp_enqueue_scripts() {
		add_filter( 'script_loader_tag', array( $this, 'add_script_attributes' ), 10, 3 );
		add_filter( 'style_loader_tag', array( $this, 'add_style_attributes' ), 10, 3 );

		$this->register_scripts();

		global $post;

		// phpcs:disable
		$post_id = 0;
		if ( isset( $_GET['action'] ) && 'partial-payment' === wp_unslash( $_GET['action'] ) && ! empty( $_GET['booking_id'] ) ) :
			$post_id = (int) $_GET['booking_id'];
		elseif ( is_object( $post ) && ! is_404() ) :
			$post_id = $post->ID;
		endif;

		$development = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

		$suffix = $development ? '' : '';

		// $public_script_dependencies

		$public_script_dependencies = $this->backward_compatibility_dependencies( array(
			"jquery",
			"lodash",
			"regenerator-runtime",
			// 'wte-global',
			// 'wte-fpickr',
			// 'jquery',
			// 'wte-custom-niceselect',
			// 'jquery-ui-core',
			// 'jquery-ui-datepicker',
			// 'jquery-steps', // used in admin
			// 'jquery-validate', // used in admin
			// 'toastr', // used in old booking form
			// 'jquery-sticky-kit', // not used
			// 'parsley',
			// 'jquery-fancy-box',
			// 'owl-carousel',
			// 'wte-rrule',
			// 'moment',
			// 'wte-moment-tz'
		) );

		if ( $this->is_fontawesome_enabled() ) {
			// $public_script_dependencies[] = 'wte-fontawesome';
			// $public_script_dependencies[] = 'v4-shims';
			// $public_script_dependencies[] = 'wte-fontawesome-all';
		}

		$this->register_scripts( [
			$this->plugin_name => array(
				'js'            => array(
					plugin_dir_url( \WP_TRAVEL_ENGINE_FILE_PATH ) . "dist/public/wte-public{$suffix}.js",
					$public_script_dependencies,
					filemtime( plugin_dir_path( \WP_TRAVEL_ENGINE_FILE_PATH ) . "dist/public/wte-public{$suffix}.js" ),
					true
				),
				'localize_data' => array( 'wteL10n', array( $this, 'get_global_localize_data' ) ),
				'css'           => array(
					plugin_dir_url( \WP_TRAVEL_ENGINE_FILE_PATH ) . "dist/public/wte-public{$suffix}.css",
					// array( 'animate', 'jquery-ui', 'owl-carousel', 'wte-global' ),
					array(),
					filemtime( plugin_dir_path( \WP_TRAVEL_ENGINE_FILE_PATH ) . "dist/public/wte-public{$suffix}.css" ),
				),
			),
		] );
		// Public Scripts.
		// \wp_register_script(
		// 	$this->plugin_name,
		// 	plugin_dir_url( \WP_TRAVEL_ENGINE_FILE_PATH ) . "dist/public/wte-public{$suffix}.js",
		// 	$public_script_dependencies,
		// 	$this->version,
		// 	true
		// );

		wp_add_inline_script( 'wte-dropzone', 'Dropzone.autoDiscover = false;' );

		global $wte_cart;

		$currency_code_js = apply_filters( 'wpte_cc_allow_payment_with_switcher', ! 0 ) ? wp_travel_engine_get_currency_code() : wte_currency_code_in_db();

		$totals = $wte_cart->get_total();

		$settings = get_option( 'wp_travel_engine_settings', array() );

		if ( ! empty( $_GET['booking_id'] ) ) {
			$booking = Core\Booking::get_booking_info_by_id( $_GET['booking_id'] );
		}

		$to_be_localized = array(
			'wte_currency_vars' => array(
				'handle' => $this->plugin_name,
				'l10n'   => array(
					'code_or_symbol' => wte_array_get( $settings, 'currency_option', 'symbol' ),
				),
			),
			'WTEAjaxData'       => array(
				'handle' => $this->plugin_name,
				'l10n'   => array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'wp_rest' ),
				),
			),
			'wte_strings'       => array(
				'handle' => $this->plugin_name,
				'l10n'   => array(
					'bookNow'         => wte_get_book_now_text(),
					// Translators: 1. Selected Pax Number 2. Minimum number of travellers 3. Maximum Number of Traveller.
					'pax_validation'  => __( 'Number of pax (%1$s) is not eligible for booking. Please choose travellers number between %2$s and %3$s for this trip.', 'wp-travel-engine' ),
					'bookingContinue' => _x( 'Continue', 'Booking continue button Label', 'wp-travel-engine' ),
				),
			),
			'wte'               => array(
				'handle' => $this->plugin_name,
				'l10n'   => array(
					'personFormat'    => wte_get_person_format(),
					'bookNow'         => wte_get_book_now_text(),
					'totaltxt'        => wte_get_total_text(),
					'currency'        => array(
						'code'   => apply_filters( 'wpte_cc_allow_payment_with_switcher', true ) ? \wp_travel_engine_get_currency_code() : wte_currency_code_in_db(),
						'symbol' => \wp_travel_engine_get_currency_symbol( $currency_code_js ),
					),
					'trip'            => array(
						'id'                 => $post_id,
						'salePrice'          => wp_travel_engine_get_sale_price( $post_id ),
						'regularPrice'       => wp_travel_engine_get_prev_price( $post_id ),
						'isSalePriceEnabled' => wp_travel_engine_is_trip_on_sale( $post_id ),
						'price'              => wp_travel_engine_get_actual_trip_price( $post_id ),
						'travellersCost'     => wp_travel_engine_get_actual_trip_price( $post_id ),
						'extraServicesCost'  => 0.0,
					),
					'payments'        => isset( $_GET['action'] ) && 'partial-payment' == $_GET['action'] && ! empty( $_GET['booking_id'] ) ?
					array(
						'locale'        => get_locale(),
						'total'         => round( $booking->cart_info['total'], 2 ),
						'total_partial' => round( $booking->cart_info['cart_partial'], 2 ),
					):
					array(
						'locale'        => get_locale(),
						'total'         => isset( $_GET['action'] ) && 'partial-payment' == $_GET['action'] && ! empty( $_GET['booking_id'] ) ? round( $booking->cart_info['total'], 2 ) : $totals['total'],
						'total_partial' => isset( $_GET['action'] ) && 'partial-payment' == $_GET['action'] && ! empty( $_GET['booking_id'] ) ? round( $booking->cart_info['cart_partial'], 2 ) : $totals['total_partial'],
					),
					'single_showtabs' => apply_filters( 'wte_single_trip_show_all_tabs', ! 1 ),
					'pax_labels'      => wte_multi_pricing_labels( $post_id ),
					'booking_cutoff'  => wpte_get_booking_cutoff( $post_id ),
				),
			),
		);

		$to_be_localized['wte_cart'] = array(
			'handle' => $this->plugin_name,
			'l10n'   => $wte_cart->getItems(),
		);

		$to_be_localized['rtl'] = array(
			'handle' => $this->plugin_name,
			'l10n'   => array( 'enable' => is_rtl() ? '1' : '0' ),
		);

		$to_be_localized['Url'] = array(
			'handle' => $this->plugin_name,
			'l10n'   => array(
				'paypalurl' => defined( 'WP_TRAVEL_ENGINE_PAYMENT_DEBUG' ) && \WP_TRAVEL_ENGINE_PAYMENT_DEBUG ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr',
				'normalurl' => esc_url( wte_array_get( $settings, 'pages.wp_travel_engine_confirmation_page', '' ) ),
			),
		);

		$to_be_localized['wp_travel_engine'] = array(
			'handle' => $this->plugin_name,
			'l10n'   => array(
				'ajaxurl'     => admin_url( 'admin-ajax.php' ),
				'cartURL'     => '',
				'CheckoutURL' => add_query_arg( 'wte_id', time(), wp_travel_engine_get_checkout_url() ),
			),
		);

		// Localization.
		foreach (
			$to_be_localized as $object_name => $load
			) {
				wp_localize_script(
					$load['handle'],
					$object_name,
					$load['l10n']
				);
		}

		// wp_register_style(
		// 	$this->plugin_name,
		// 	plugin_dir_url( \WP_TRAVEL_ENGINE_FILE_PATH ) . "dist/public/wte-public{$suffix}.css",
		// 	array( 'animate', 'jquery-ui', 'owl-carousel', 'wte-global' ),
		// 	$this->version
		// );

		global $wte_cart;
		$wptravelengine_cart = '';
		if ( wp_travel_engine_is_checkout_page() && isset( $wte_cart ) ) {
			$cart_data           = array(
				'cart_items'  => $wte_cart->getItems(),
				'cart_totals' => $wte_cart->get_total(),
			);
			$wptravelengine_cart = "window['wptravelengineCart'] = %s;";
			wp_add_inline_script( $this->plugin_name, sprintf( $wptravelengine_cart, wp_json_encode( $cart_data ) ) );
		}
		// wp_enqueue_script( 'wte-fpickr-l10n' );
		wp_enqueue_script( $this->plugin_name );
		wp_add_inline_script( $this->plugin_name, 'var WPTE_Price_Separator = "' . wte_array_get( $settings, 'thousands_separator', '' ) . '"; // Backward compatibility.' );
		wp_enqueue_style( $this->plugin_name );
		$wte_account_arr = array(
			'ajax_url'                => admin_url( 'admin-ajax.php' ),
			'change_user_profile_msg' => __( 'Click here or Drop new image to update your profile picture', 'wp-travel-engine' ),
		);
		wp_localize_script( $this->plugin_name, 'wte_account_page', $wte_account_arr );
	}

	/**
	 * Admin Assets.
	 */
	public function admin_enqueue_scripts() {
		$this->register_scripts();
		// Admin Scripts.
		$screens        = array( 'trip', 'enquiry', 'booking', 'customer', 'wte-coupon', 'downloadfile' );
		$current_screen = get_current_screen();

		if ( ! $current_screen ) {
			return;
		}

		$screen_ids = array( 'trip_page_class-wp-travel-engine-admin' );

		$development = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

		$suffix = $development ? '' : '';

		wp_enqueue_style( 'wte-plugins-php', plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . "dist/admin/plugins-php{$suffix}.css", array(), $this->version );

		$dependencies = array(
			'jquery',
			'jquery-ui-core',
			'jquery-ui-datepicker',
			'jquery-ui-tabs',
			'jquery-ui-sortable',
			'toastr',
			'parsley',
			'wte-select2',
			'jquery-fancy-box',
			'wte-fpickr',
			'moment',
		);

		if ( $this->is_fontawesome_enabled() ) {
			$dependencies[] = 'wte-fontawesome-all';
			$dependencies[] = 'v4-shims';
		}

		if ( $current_screen && WP_TRAVEL_ENGINE_POST_TYPE === $current_screen->id ) {
			$dependencies[] = 'wte-redux';
			$dependencies[] = 'wte-rxjs';
		}

		$to_be_localized = array(
			'WTE_UI' => array(
				'handle' => "{$this->plugin_name}",
				'l10n'   => array(
					'suretodel'        => __( 'Sure to delete? This action cannot be reverted.', 'wp-travel-engine' ),
					'validation_error' => esc_html__( 'Validation Error. Settings could not be saved.', 'wp-travel-engine' ),
					'copied'           => esc_html__( 'Text copied to clipboard.', 'wp-travel-engine' ),
					'novid'            => esc_html__( 'No video URL supplied.', 'wp-travel-engine' ),
					'invalid_url'      => esc_html__( 'Invalid URL supplied. Please make sure to add valid YouTube or Vimeo video URL', 'wp-travel-engine' ),
				),
			),
		);

		wp_register_script( 'wte-edit--coupon', plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . "dist/admin/coupon{$suffix}.js", array( 'jquery', 'wte-fpickr' ), filemtime(\WP_TRAVEL_ENGINE_ABSPATH . "dist/admin/coupon{$suffix}.js"), true );
		wp_register_script( $this->plugin_name, plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . "dist/admin/wte-admin{$suffix}.js", $dependencies, filemtime(\WP_TRAVEL_ENGINE_ABSPATH . "dist/admin/wte-admin{$suffix}.js"), true );

		foreach ( apply_filters( 'wte_admin_localize_data', $to_be_localized ) as $object_name => $load ) {
			wp_localize_script( $load['handle'], $object_name, $load['l10n'] );
		}
		wp_register_style( $this->plugin_name . '_core_ui', plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . "dist/admin/wte-admin{$suffix}.css", array( 'wte-select2', 'wte-global', 'datepicker-style', 'animate', 'toastr' ), filemtime(WP_TRAVEL_ENGINE_ABSPATH . "dist/admin/wte-admin{$suffix}.css") );

		if ( in_array( $current_screen->post_type, $screens, true ) || ( isset( $_GET['page'] ) && 'class-wp-travel-engine-admin.php' === wp_unslash( $_GET['page'] ) ) || in_array( $current_screen->id, $screen_ids, true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			wp_enqueue_editor();
			wp_enqueue_media();
			wp_enqueue_script( 'wte-global' );
			wp_enqueue_style( 'wte-fpickr' );
			// wp_enqueue_script( 'wte-fpickr-l10n' );
			wp_enqueue_script( $this->plugin_name );

			// Styles.
			wp_enqueue_style( $this->plugin_name . '_core_ui' );
		}

		wp_register_style( 'wte-plugins-php', plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . 'dist/admin/plugins-php.css', array(), filemtime(WP_TRAVEL_ENGINE_ABSPATH . 'dist/admin/plugins-php.css') );
	}
}

new Assets();
