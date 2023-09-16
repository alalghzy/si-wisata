<?php
/**
 * WP Travel Engine AJAX
 *
 * @package WP_Travel_Engine
 *
 * @since 2.2.6
 */
class WTE_Ajax {

	public function __construct() {

		// Cart Ajax handlers.
		add_action( 'wp_ajax_wte_add_trip_to_cart', array( $this, 'wte_add_trip_to_cart' ) );
		add_action( 'wp_ajax_nopriv_wte_add_trip_to_cart', array( $this, 'wte_add_trip_to_cart' ) );


		/**
		 * Clone Existing Trips
		 *
		 * @since 2.2.6
		 */
		add_action( 'wp_ajax_wte_fxn_clone_trip_data', array( $this, 'wte_fxn_clone_trip_data' ) );
		add_action( 'wp_ajax_nopriv_wte_fxn_clone_trip_data', array( $this, 'wte_fxn_clone_trip_data' ) );

		// Save global Settings.
		add_action(
			'wp_ajax_wpte_global_tabs_save_data',
			function() {
				if ( ! class_exists( '\Wp_Travel_Engine_Settings' ) ) {
					require_once plugin_dir_path( \WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-settings.php';
				}

				call_user_func( array( '\Wp_Travel_Engine_Settings', 'save_settings' ) );
			}
		);

		// Onboard Dynamic Recommendation
		add_action( 'wp_ajax_wte_onboard_dynamic_recommendation', array( '\WP_TRAVEL_ENGINE_ONBOARDING_PROCESS', 'wte_onboard_dynamic_recommendation_callback' ) );
		add_action( 'wp_ajax_nopriv_wte_onboard_dynamic_recommendation', array( '\WP_TRAVEL_ENGINE_ONBOARDING_PROCESS', 'wte_onboard_dynamic_recommendation_callback' ) );

		$actions = array(
			'wte_enquiry_send_mail'                 => array( 'callback' => array( '\WP_Travel_Engine_Enquiry_Form_Shortcodes', 'wte_enquiry_send_mail' ) ), // [x]
			'wpte_onboard_save_function'            => array( 'callback' => array( '\WP_TRAVEL_ENGINE_ONBOARDING_PROCESS', 'wpte_onboard_save_function_callback' ) ), // [x]
			'wp_add_trip_info'                      => array( 'callback' => array( 'Wp_Travel_Engine_Admin', 'wp_add_trip_info' ) ), // [x]
			'wte_show_ajax_result'                  => array( 'callback' => array( '\WPTravelEngine\Modules\TripSearch', 'filter_trips_html' ) ), // [x]
			'wte_show_ajax_result_load'             => array( 'callback' => array( '\WPTravelEngine\Modules\TripSearch', 'load_trips_html' ) ), // [x]
			'wp_travel_engine_check_coupon_code'    => array( 'callback' => array( '\WPTravelEngine\Modules\CouponCode\Ajax', 'check_coupon_code' ) ), // [x]
			'wte_session_cart_apply_coupon'         => array( 'callback' => array( '\WPTravelEngine\Modules\CouponCode\Ajax', 'apply_coupon' ) ), // [x]
			'wte_session_cart_reset_coupon'         => array( 'callback' => array( '\WPTravelEngine\Modules\CouponCode\Ajax', 'reset_coupon' ) ), // [x]
			'wte_get_enquiry_preview'               => array( 'callback' => array( '\Wp_Travel_Engine_Admin', 'wte_get_enquiry_preview_action' ) ), // [x]
			'wp_travel_engine_featured_trip'        => array( 'callback' => array( '\Wp_Travel_Engine_Admin', 'wp_travel_engine_featured_trip_admin_ajax' ) ), // [x]
			'wp_travel_engine_featured_term'        => array( 'callback' => array( '\Wp_Travel_Engine_Admin', 'wp_travel_engine_featured_term_admin_ajax' ) ), // [x]
			'wpte_admin_load_tab_content'           => array( 'callback' => array( '\Wp_Travel_Engine_Admin', 'wpte_admin_load_tab_content_callback' ) ), // [x]
			'wpte_tab_trip_save_and_continue'       => array(
				'sanitization_callback' => array( '\Wp_Travel_Engine_Admin', 'sanitize_post_data' ),
				'callback'              => array( '\Wp_Travel_Engine_Admin', 'wpte_tab_trip_save_and_continue_callback' ),
			), // [x]
			'wpte_global_settings_load_tab_content' => array( 'callback' => array( '\Wp_Travel_Engine_Admin', 'wpte_global_settings_load_tab_content_callback' ) ), // [x]
			// 'wp_add_trip_cart'                      => array( 'callback' => array( '\Wp_Travel_Engine_Public', 'wp_add_trip_cart' ) ),
			'wte_remove_order'                      => array( 'callback' => array( '\Wp_Travel_Engine_Public', 'wte_remove_from_cart' ) ),
			'wte_update_cart'                       => array( 'callback' => array( '\Wp_Travel_Engine_Public', 'wte_ajax_update_cart' ) ),
			'wpte_ajax_load_more'                   => array(
				'callback'     => array( '\Wp_Travel_Engine_Public', 'wpte_ajax_load_more' ),
				'nonce_action' => 'wpte-be-load-more-nonce',
			),
			'wpte_ajax_load_more_destination'       => array(
				'callback'     => array( '\Wp_Travel_Engine_Public', 'wpte_ajax_load_more_destination' ),
				'nonce_action' => 'wpte-be-load-more-nonce',
			),
			'wte_payment_gateway'                   => array(
				'callback'     => array( '\Wp_Travel_Engine_Public', 'wte_payment_gateway' ),
				'nonce_action' => 'wp_rest',
			),
			'wte_set_difficulty_term_level' => array(
				'callback'     => array( __CLASS__, 'set_difficulty_term_level' ),
				'nonce_action' => 'wp_xhr',
			),
			'wte_user_wishlist' => array(
				'callback'     => array( __CLASS__, 'wte_user_wishlist' ),
				'nonce_action' => 'wp_xhr',
			)
		);

		foreach ( $actions as $action => $args ) {
			add_action(
				"wp_ajax_{$action}",
				function() use ( $args, $action ) {
					if ( isset( $_REQUEST['_nonce'] ) || isset( $_REQUEST['nonce'] ) ) {
						$nonce = isset( $_REQUEST['_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_nonce'] ) ) : sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) );

						$nonce_action = isset( $args['nonce_action'] ) ? $args['nonce_action'] : $action;
						if ( ! wp_verify_nonce( $nonce, $nonce_action ) ) {
							wp_send_json_error( new \WP_Error( 'WTE_INVALID_NONCE', __( 'Invalid nonce or nonce expired.', 'wp-travel-engine' ) ) );
						}
						if ( isset( $args['sanitization_callback'] ) ) {
							$post_data = call_user_func( $args['sanitization_callback'], $_REQUEST );
						} else {
							$post_data = wte_clean( wp_unslash( $_REQUEST ) );
						}
						call_user_func( $args['callback'], $post_data );
					} else {
						wp_send_json_error( new \WP_Error( 'WTE_NONCE_MISSING', __( 'Nonce Missing.', 'wp-travel-engine' ) ) );
					}
					exit;
				}
			);
			if ( ! in_array(
				$action,
				array(
					'wp_ajax_wp_travel_engine_check_coupon_code',
					'wte_get_enquiry_preview',
					'wpte_admin_load_tab_content',
					'wpte_tab_trip_save_and_continue',
					'wpte_global_settings_load_tab_content',
					'wpte_global_tabs_save_data',
				),
				true
			) ) {
				add_action(
					"wp_ajax_nopriv_{$action}",
					function() use ( $args, $action ) {
						if ( isset( $_REQUEST['_nonce'] ) || isset( $_REQUEST['nonce'] ) ) {
							$nonce        = isset( $_REQUEST['_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_nonce'] ) ) : sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) );
							$nonce_action = isset( $args['nonce_action'] ) ? $args['nonce_action'] : $action;
							if ( ! wp_verify_nonce( $nonce, $nonce_action ) ) {
								wp_send_json_error( new \WP_Error( 'WTE_INVALID_NONCE', __( 'Invalid nonce or nonce expired.', 'wp-travel-engine' ) ) );
							}
							if ( isset( $args['sanitization_callback'] ) ) {
								$post_data = call_user_func( $args['sanitization_callback'], $_REQUEST );
							} else {
								$post_data = wte_clean( wp_unslash( $_REQUEST ) );
							}
							call_user_func( $args['callback'], $post_data );
						} else {
							wp_send_json_error( new \WP_Error( 'WTE_NONCE_MISSING', __( 'Nonce Missing.', 'wp-travel-engine' ) ) );
						}
						exit;
					}
				);
			}
		}

	}

	/**
	 *
	 * @since 5.5.7
	 */
	public static function set_difficulty_term_level( $post_data ) {
		// save in options.
		$difficulty_level = get_option('difficulty_level_by_terms', array() );
		$term = get_term( $post_data['term_id'] );
		$difficulty_level[ $post_data['level'] ] = array(
			'level'   => $post_data['level'],
			'term_id' => $post_data['term_id'],
			'label'   => $term->name
		);
		foreach( $difficulty_level as $key => $val ){
			if( ( $post_data['level'] != $key && $post_data['term_id'] == $val['term_id'] ) || ( $post_data['level'] == '' && $post_data['term_id'] == $val['term_id'] ) ){
				unset( $difficulty_level[$key] );
			}
		}
		if ( array_key_exists( "Select Level", $difficulty_level ) )
		{
			unset( $difficulty_level['Select Level'] );
		}
		update_option('difficulty_level_by_terms', $difficulty_level);
		wp_send_json_success( $difficulty_level );

		wp_die();
	}

	public function package_for_cloned_trip( $post_id, $meta_value ) {
		$meta_value = is_array( $meta_value ) ? $meta_value : array();
		foreach ( $meta_value as $package_id ) {
			$package = get_post( $package_id );
			if ( ! is_null( $package ) ) {
				$package        = (object) $package;
				$new_package_id = wp_insert_post(
					array(
						'post_title'  => $package->post_title,
						'post_status' => 'publish',
						'post_type'   => 'trip-packages',
					)
				);

				if ( ! is_wp_error( $new_package_id ) && $new_package_id ) {
					$all_old_meta = get_post_meta( $post_id );

				}

				return $package_id;
			}
		}

	}

	/**
	 * Ajax callback function to clone trip data.
	 *
	 * @since 2.2.6
	 */
	public function wte_fxn_clone_trip_data() {

		// Nonce checks.
		check_ajax_referer( 'wte_clone_post_nonce', 'security' );

		if ( ! isset( $_POST['post_id'] ) ) {
			return;
		}

		$post_id   = (int) $_POST['post_id']; // phpcs:ignore
		$post_type = get_post_type( $post_id );

		if ( WP_TRAVEL_ENGINE_POST_TYPE !== $post_type ) {
			wp_send_json_error(
				new \WP_Error(
					'INVALID_POST_TYPE',
					__( 'Cloning post must be of trip posttype.', 'wp-travel-engine' )
				)
			);
			die;
		}

		$post = get_post( $post_id );

		$new_post_id = wptravelengine_duplicate_post( $post );

		$packages_ids = get_post_meta( $post_id, 'packages_ids', true );

		$_new_package_ids = array();
		if ( is_array( $packages_ids ) ) {
			foreach ( $packages_ids as $package_id ) {
				$_package_id = wptravelengine_duplicate_post( $package_id );
				if ( ! is_null( $_package_id ) ) {
					$_new_package_ids[] = $_package_id;
					wp_update_post(
						array(
							'ID'          => $_package_id,
							'post_status' => 'publish',
							'meta_input'  => array(
								'trip_ID' => $new_post_id,
							),
						)
					);
				}
			}
		}

		update_post_meta( $new_post_id, 'packages_ids', $_new_package_ids );
		update_post_meta( $new_post_id, 'wte_fsd_booked_seats', array() );

		if ( ! is_null( $new_post_id ) ) {
			wp_send_json_success(
				array(
					'post_id'   => $new_post_id,
					'edit_link' => add_query_arg(
						array(
							'post'   => $new_post_id,
							'action' => 'edit',
						),
						admin_url( 'post.php' )
					),
				)
			);
			die;
		}

	}

	/**
	 * Add to cart.
	 *
	 * @since 5.0.0
	 * @return void
	 */
	public static function add_to_cart() {
		// Cart Data sent as JSON body.
		$raw_data = wte_get_request_raw_data();
		// Decoded JSON data.
		$cart_data = json_decode( $raw_data, ! 0 );

		if ( ! $cart_data ) {
			wp_send_json_error( new WP_Error( 'ADD_TO_CART_ERROR', __( 'Invalid data structure.', 'wp-travel-engine' ) ) );
			exit;
		}

		$cart_data = wte_clean( wp_unslash( $cart_data ) );

		$cart_data = (object) $cart_data;

		if ( empty( $cart_data->{'tripID'} ) ) {
			wp_send_json_error( new WP_Error( 'ADD_TO_CART_ERROR', __( 'Invalid Trip ID.', 'wp-travel-engine' ) ) );
			exit;
		}

		global $wte_cart;

		if ( ! apply_filters( 'wp_travel_engine_allow_multiple_cart_items', false ) ) {
			$wte_cart->clear();
		}

		// Mapped Data.
		$trip_id   = $cart_data->{'tripID'};
		$trip_date = $cart_data->{'tripDate'};
		$trip_time = $cart_data->{'tripTime'};
		$travelers = $cart_data->{'travelers'};

		$cart_total = $cart_data->{'cartTotal'};

		$pax           = array();
		$pax_cost      = array();
		$category_info = array(); // This contains pricing category information.

		$only_trip_price = 0;

		if ( isset( $cart_data->{'pricingOptions'} ) && is_array( $cart_data->{'pricingOptions'} ) ) {
			foreach ( $cart_data->{'pricingOptions'} as $cid => $info ) {
				if ( (int) $info['pax'] < 1 ) {
					continue;
				}
				$category_total_cost   = isset( $info['categoryInfo']['pricingType'] ) && 'per-person' === $info['categoryInfo']['pricingType'] ? (float) $info['pax'] * $info['cost'] : (float) $info['cost'];
				$pax[ $cid ]           = $info['pax'];
				$pax_cost[ $cid ]      = $category_total_cost;
				$category_info[ $cid ] = $info['categoryInfo'];
				$only_trip_price      += $category_total_cost;
			}
		}

		$pax_labels = array();
		$attrs      = apply_filters(
			'wp_travel_engine_cart_attributes',
			array(
				'trip_date'          => $cart_data->{'tripDate'},
				'trip_time'          => $cart_data->{'tripTime'},
				'price_key'          => $cart_data->{'packageID'},
				'pricing_options'    => $cart_data->{'pricingOptions'},
				'pax'                => $pax,
				'pax_labels'         => $pax_labels,
				'category_info'      => $category_info,
				'pax_cost'           => $pax_cost,
				'multi_pricing_used' => ! 0,
				'price_key'          => $cart_data->{'packageID'},
				'trip_extras'        => ! empty( $cart_data->{'extraServices'} ) ? (array) $cart_data->{'extraServices'} : array(),
			)
		);

		$trip_price         = $only_trip_price;
		$trip_price_partial = 0;
		$tax_amount         = wp_travel_engine_get_tax_percentage();

		$partial_payment_data = wp_travel_engine_get_trip_partial_payment_data( $trip_id );

		if ( ! empty( $partial_payment_data ) ) :

			if ( 'amount' === $partial_payment_data['type'] ) :

				$trip_price_partial = $partial_payment_data['value'];

			elseif ( 'percentage' === $partial_payment_data['type'] ) :

				$partial            = 100 - $partial_payment_data['value'];
				$trip_price_partial = ( $trip_price ) - ( $partial / 100 ) * $trip_price;


			endif;

		endif;

		// combine additional parameters to attributes insted more params.
		$attrs['trip_price']         = $trip_price;
		$attrs['trip_price_partial'] = $trip_price_partial;
		$attrs['tax_amount']         = $tax_amount['value'];

		$price_key = $cart_data->{'packageID'};
		/**
		 * Action with data.
		 */
		do_action_deprecated( 'wp_travel_engine_before_trip_add_to_cart', array( $trip_id, $trip_price, $trip_price_partial, $pax, $price_key, $attrs ), '4.3.0', 'wte_before_add_to_cart', __( 'deprecated because of more params.', 'wp-travel-engine' ) );
		do_action( 'wte_before_add_to_cart', $trip_id, $attrs );

		// Get any errors/ notices added.
		$wte_errors = WTE()->notices->get( 'error' );

		// If any errors found bail.Ftrip-cost
		if ( $wte_errors ) :
			wp_send_json_error( $wte_errors );
			exit;
		endif;

		// Add to cart.
		$wte_cart->add( $trip_id, $attrs );

		do_action( 'wte_after_add_to_cart', $trip_id, $attrs );

		wp_send_json_success(
			array(
				'code'    => 'ADD_TO_CART_SUCCESS',
				'message' => __( 'Trip added to cart successfully.', 'wp-travel-engine' ),
				'items'   => $wte_cart->getItems(),
			)
		);
		exit;
	}

	/*
	** @since 5.5.7
	** Get request method
	 */
	public static function get_current_request_method() {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) );
		}
		return 'GET';
	}

	/*
	** @since 5.5.7
	** Update user wishlist
	 */
	public static function wte_user_wishlist( $data ) {
		$request_method = self::get_current_request_method();

		$user_wishlists = wptravelengine_user_wishlists();

		if ( ! is_array( $user_wishlists ) ) {
			$user_wishlists = array();
		}
		$message = __( 'Wishlist fetched successfully.', 'wp-travel-engine' );

		if ( 'GET' === $request_method ) {
			wp_send_json_success( compact( 'message', 'user_wishlists' ) );
			wp_die();
		}

		$flipped = array_flip( $user_wishlists );
		if ( 'POST' === $request_method ) {
			$flipped[ $data['wishlist'] ] = trim( $data['wishlist'] );
			$message = __( 'Trip is added to wishlists.', 'wp-travel-engine' );
		} else if( 'DELETE' === $request_method ) {
			if ( $data['wishlist'] == 'all' ){
				$flipped = array();
			} else {
				$explode = explode( ",", $data['wishlist'] );
				foreach ( $explode as $ex ){
					unset( $flipped[ $ex ] );
				}
			}
			$message = __( 'Trip is removed from wishlists.', 'wp-travel-engine' );
		}

		$value = array_keys( $flipped );

		if ( is_user_logged_in(  ) ) {
			$user_id = get_current_user_id();
			update_user_meta( $user_id, 'wptravelengine_wishlists', $value );
		} else {
			WTE()->session->set( 'user_wishlists', $value );
		}
		$message = __( '<strong>0</strong> item in wishlist', 'wp-travel-engine' );
		$user_wishlists = wptravelengine_user_wishlists();
		wp_send_json_success( array(
			'message' => $message,
			'user_wishlists' => $user_wishlists,
			'refresh' => $data['wishlist'] == 'all',
			'partials' => [
				'[data-wptravelengine-wishlist-count]' => ! empty( $user_wishlists ) ? sprintf( _n( '<strong>%d</strong> item in the wishlist', '<strong>%d</strong> items in the wishlist', count( $user_wishlists ), 'wp-travel-engine' ), count( $user_wishlists ) ) : '',
			] ) );
		wp_die();
	}

	/**
	 * Add trip to cart.
	 *
	 * @return void
	 */
	function wte_add_trip_to_cart() {

		if ( ! wte_nonce_verify( '_nonce', 'wte_add_trip_to_cart' ) ) {
			wp_send_json_error( new WP_Error( 'ADD_TO_CART_ERROR', __( 'Nonce verification failed.', 'wp-travel-engine' ) ) );
			die;
		}
		/**
		 * May be using new cart.
		 */
		if ( ! empty( $_REQUEST['cart_version'] ) ) { // phpcs:ignore
			self::add_to_cart();
		}

		// phpcs:disable
		if ( ! isset( $_POST['trip-id'] ) || is_null( get_post( $_POST['trip-id'] ) ) ) {
			wp_send_json_error( new WP_Error( 'ADD_TO_CART_ERROR', __( 'Invalid trip ID.', 'wp-travel-engine' ) ) );
			die;
		}

		global $wte_cart;

		$allow_multiple_cart_items = apply_filters( 'wp_travel_engine_allow_multiple_cart_items', false );

		if ( ! $allow_multiple_cart_items ) {
			$wte_cart->clear();
		}

		$posted_data = wte_clean( wp_unslash( $_POST ) );
		// phpcs:enable
		$trip_id            = $posted_data['trip-id'];
		$trip_date          = isset( $posted_data['trip-date'] ) ? $posted_data['trip-date'] : '';
		$trip_time          = isset( $posted_data['trip-time'] ) ? $posted_data['trip-time'] : '';
		$travelers          = isset( $posted_data['travelers'] ) ? $posted_data['travelers'] : 1;
		$travelers_cost     = isset( $posted_data['travelers-cost'] ) ? $posted_data['travelers-cost'] : 0;
		$child_travelers    = isset( $posted_data['child-travelers'] ) ? $posted_data['child-travelers'] : 0;
		$child_cost         = isset( $posted_data['child-travelers-cost'] ) ? $posted_data['child-travelers-cost'] : 0;
		$trip_extras        = isset( $posted_data['extra_service'] ) ? $posted_data['extra_service'] : array();
		$trip_price         = isset( $posted_data['trip-cost'] ) ? $posted_data['trip-cost'] : 0;
		$price_key          = '';
		$trip_price_partial = 0;

		// Additional cart params.
		$attrs['trip_date']   = $trip_date;
		$attrs['trip_time']   = $trip_time;
		$attrs['trip_extras'] = $trip_extras;

		$pax      = array();
		$pax_cost = array();

		if ( ! empty( $posted_data['pricing_options'] ) ) :

			foreach ( $posted_data['pricing_options'] as $key => $option ) :

				$pax[ $key ]      = $option['pax'];
				$pax_cost[ $key ] = $option['cost'];

			endforeach;

			// Multi-pricing flag
			$attrs['multi_pricing_used'] = true;

		else :

			$pax = array(
				'adult' => $travelers,
				'child' => $child_travelers,
			);

			$pax_cost = array(
				'adult' => $travelers_cost,
				'child' => $child_cost,
			);

		endif;

		$attrs['pax']      = $pax;
		$attrs['pax_cost'] = $pax_cost;

		$attrs = apply_filters( 'wp_travel_engine_cart_attributes', $attrs );

		$partial_payment_data = wp_travel_engine_get_trip_partial_payment_data( $trip_id );
		if ( ! empty( $partial_payment_data ) ) :

			if ( 'amount' === $partial_payment_data['type'] ) :

				$trip_price_partial = $partial_payment_data['value'];

			elseif ( 'percentage' === $partial_payment_data['type'] ) :

				$partial            = 100 - (float) $partial_payment_data['value'];
				$trip_price_partial = ( $trip_price ) - ( $partial / 100 ) * $trip_price;

			endif;

		endif;

		// combine additional parameters to attributes insted more params.
		$attrs['trip_price']         = $trip_price;
		$attrs['trip_price_partial'] = $trip_price_partial;
		$attrs['pax']                = $pax;
		$attrs['price_key']          = $price_key;

		/**
		 * Action with data.
		 */
		do_action_deprecated( 'wp_travel_engine_before_trip_add_to_cart', array( $trip_id, $trip_price, $trip_price_partial, $pax, $price_key, $attrs ), '4.3.0', 'wte_before_add_to_cart', __( 'deprecated because of more params.', 'wp-travel-engine' ) );
		do_action( 'wte_before_add_to_cart', $trip_id, $attrs );

		// Get any errors/ notices added.
		$wte_errors = WTE()->notices->get( 'error' );

			// If any errors found bail.Ftrip-cost
		if ( $wte_errors ) :
			wp_send_json_error( $wte_errors );
		endif;

		// Add to cart.
		$wte_cart->add( $trip_id, $attrs );

		/**
		 * @since 3.0.7
		 */
		do_action_deprecated( 'wp_travel_engine_after_trip_add_to_cart', array( $trip_id, $trip_price, $trip_price_partial, $pax, $price_key, $attrs ), '4.3.0', 'wte_after_add_to_cart', __( 'deprecated because of more params.', 'wp-travel-engine' ) );

		do_action( 'wte_after_add_to_cart', $trip_id, $attrs );

		// send success notification.
		wp_send_json_success( array( 'message' => __( 'Trip added to cart successfully', 'wp-travel-engine' ) ) );

		die;

	}

}
new WTE_Ajax();
