<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 */
class Wp_Travel_Engine_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		include_once sprintf( '%s/includes/wp-travel-engine-helpers.php', WP_TRAVEL_ENGINE_ABSPATH );

		// Run old activation function.
		self::activate_old();

		// Create Roles.
		self::create_roles();

		$already_shown = get_transient( 'wte_getting_started_page_shown' );

		if ( ! $already_shown ) :

			set_transient( 'wte_show_getting_started_page', true );

		endif;

		/**
		 * Insert cart and checkout pages
		 *
		 * @since 3.0.3
		 */

		$pages = apply_filters(
			'wp_travel_engine_create_pages',
			array(
				'wp-travel-engine-cart'     => array(
					'name'    => 'wp-travel-engine-cart',
					'title'   => _x( 'WP Travel Engine Cart', 'Page title', 'wp-travel-engine' ),
					'content' => '[' . apply_filters( 'wp_travel_engine_cart_shortcode_tag', 'wp_travel_engine_cart' ) . ']',
				),
				'wp-travel-engine-checkout' => array(
					'name'    => 'wp-travel-engine-checkout',
					'title'   => _x( 'WP Travel Engine Checkout', 'Page title', 'wp-travel-engine' ),
					'content' => '[' . apply_filters( 'wp_travel_engine_checkout_shortcode_tag', 'WP_TRAVEL_ENGINE_PLACE_ORDER' ) . ']',
				),
				'my-account'                => array(
					'name'    => 'my-account',
					'title'   => _x( 'My account', 'Page title', 'wp-travel-engine' ),
					'content' => '[' . apply_filters( 'wp_travel_engine_account_shortcode_tag', 'wp_travel_engine_dashboard' ) . ']',
				)
			)
		);

		foreach ( $pages as $key => $page ) {
			wp_travel_engine_create_page( esc_sql( $page['name'] ),
			'wp_travel_engine_' . $key . '_page_id', $page['title'],
			$page['content'], ! empty( $page['parent'] ) ? wp_travel_engine_get_page_id( $page['parent'] ) : '' );
		}

	}
	/**
	 * Create roles and capabilities.
	 */
	public static function create_roles() {
		global $wp_roles;

		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles(); // @codingStandardsIgnoreLine
		}

		// Customer role.
		add_role(
			'wp-travel-engine-customer',
			__( 'Customer', 'wp-travel-engine' ),
			array(
				'read' => true,
			)
		);
	}
	/**
	 * Old activation hook.
	 *
	 * @return void
	 */
	public static function activate_old() {

		if ( is_plugin_active( 'wte-paypal-gateway/wte-paypal-gateway.php' ) ) {
			deactivate_plugins( 'wte-paypal-gateway/wte-paypal-gateway.php' );
		}

		$wte_doc_tax_post_args = array(
			'post_type'      => 'trip', // Your Post type Name that You Registered
			'posts_per_page' => -1,
			'order'          => 'ASC',
		);
		$wte_doc_tax_post_qry  = new WP_Query( $wte_doc_tax_post_args );
		$cost                  = 0;
		if ( $wte_doc_tax_post_qry->have_posts() ) :
			while ( $wte_doc_tax_post_qry->have_posts() ) :
				$wte_doc_tax_post_qry->the_post();
				$wp_travel_engine_setting = get_post_meta( get_the_ID(), 'wp_travel_engine_setting', true );
				if ( isset( $wp_travel_engine_setting['trip_price'] ) ) {
					$cost = $wp_travel_engine_setting['trip_price'];
					update_post_meta( get_the_ID(), 'wp_travel_engine_setting_trip_price', $cost );
				}
				if ( isset( $wp_travel_engine_setting['trip_prev_price'] ) ) {
					$prev_cost = $wp_travel_engine_setting['trip_prev_price'];
					update_post_meta( get_the_ID(), 'wp_travel_engine_setting_trip_prev_price', $prev_cost );
				}
				if ( isset( $wp_travel_engine_setting['trip_duration'] ) ) {
					$duration = $wp_travel_engine_setting['trip_duration'];
					update_post_meta( get_the_ID(), 'wp_travel_engine_setting_trip_duration', $duration );
				}
				$actual_price = wp_travel_engine_get_actual_trip_price( get_the_ID(), true );
				update_post_meta( get_the_ID(), 'wp_travel_engine_setting_trip_actual_price', $actual_price );
			endwhile;
			wp_reset_postdata();
		endif;
		wp_reset_query();

		$options = get_option( 'wp_travel_engine_settings', array() );
		$wp_travel_engine_option_settings = get_option( 'wp_travel_engine_settings', array() );
		$wishlist__page = get_page_by_title( 'Wishlist' );

		// Add Wishlist Page.
		if ( empty( $wishlist__page ) ) {
			unset( $options['pages']['wp_travel_engine_wishlist'] );
			$new_page                = array(
				'post_title'   => 'Wishlist',
				'post_content' => '[WP_TRAVEL_ENGINE_WISHLIST]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			);
			$post_id                 = wp_insert_post( $new_page );
			$arr['pages']['wp_travel_engine_wishlist'] = $post_id;
			$wishlist_page            = array_merge_recursive( $options, $arr );
			update_option( 'wp_travel_engine_settings', $wishlist_page );
		}

		// Add Trip Search Page.
		if ( empty( $wp_travel_engine_option_settings['pages']['search'] ) ) {
			$page_title         = 'Trip Search Result';
			$search_page_exists = get_page_by_title( $page_title );
			if ( is_null( $search_page_exists ) ) {
				$post_id = wp_insert_post(
					array(
						'post_title'   => $page_title,
						'post_content' => '[WTE_Trip_Search]',
						'post_status'  => 'publish',
						'post_type'    => 'page',
					)
				);
				$wp_travel_engine_option_settings['pages']['search'] = $post_id;
				update_option( 'wp_travel_engine_settings', $wp_travel_engine_option_settings );
			}
		}

		if ( isset( $options ) && ! empty( $options ) ) {
			update_option( 'wp_travel_engine_first_time_activation_flag', 'true' );
			return;
		}

		$template_pages = array(
			'trip_types'  => array(
				'title'    => 'Trip Types',
				'template' => 'templates/template-trip_types.php',
			),
			'destination' => array(
				'title'    => 'Destination',
				'template' => 'templates/template-destination.php',
			),
			'activities'  => array(
				'title'    => 'Activities',
				'template' => 'templates/template-activities.php',
			),
		);
		foreach ( $template_pages as $key => $value ) {
			$existing_page = get_page_by_title( $value['title'] );
			if ( ! empty( $existing_page ) && 'page' === $existing_page->post_type ) {
				$val = get_post_meta( $existing_page->ID, '_wp_page_template', true );
				if ( $val == $value['template'] ) {
					break;
				}
			} else {
				$new_page = array(
					'post_title'   => $value['title'],
					'post_content' => '',
					'post_status'  => 'publish',
					'post_type'    => 'page',
				);
				$postID   = wp_insert_post( $new_page );
				update_post_meta( $postID, '_wp_page_template', $value['template'] );
			}
		}

		$arr           = array();
		$existing_page = get_page_by_title( 'Enquiry Thank You Page' );

		delete_option( 'wp_travel_engine_settings' );
		$pages = array(
			'wp_travel_engine_thank_you'            => array(
				'title'     => 'Thank You',
				'shortcode' => 'THANK_YOU',
			),
			'wp_travel_engine_place_order'          => array(
				'title'     => 'Checkout',
				'shortcode' => 'PLACE_ORDER',
			),
			'wp_travel_engine_terms_and_conditions' => array(
				'title'     => 'Terms and Conditions',
				'shortcode' => '',
			),
			'enquiry'                               => array(
				'title'     => 'Enquiry Thank You Page',
				'shortcode' => '',
			),
			'wp_travel_engine_confirmation_page'    => array(
				'title'     => 'Travellers Information',
				'shortcode' => 'BOOK_CONFIRMATION',
			),
			'wp_travel_engine_dashboard_page'    => array(
				'title'     => 'My Account',
				'shortcode' => 'dashboard',
			)
		);
		foreach ( $pages as $key => $value ) {
			$shortcode = 'WP_TRAVEL_ENGINE_' . $value['shortcode'];
			if( $key == 'wp_travel_engine_dashboard_page'){
				$shortcode = 'wp_travel_engine_' . $value['shortcode'];
			}
			$title     = ucfirst( $value['title'] );
			// Check if this page already exists, with shortcode
			$existing_page = get_page_by_title( $title );

			if ( ! empty( $existing_page ) && 'page' === $existing_page->post_type ) {
				$content = $existing_page->post_content;

				if ( strtolower( $title ) == 'terms and conditions' ) {
					$page = get_page_by_title( 'TERMS AND CONDITIONS' );
					wp_delete_post( $page->ID, true );
				} elseif ( $title == 'Enquiry Thank You Page' ) {
					$page = get_page_by_title( 'Enquiry Thank You Page' );
					wp_delete_post( $page->ID, true );
				} else {
					if ( has_shortcode( $content, $shortcode ) ) {
						wp_delete_post( $existing_page->ID, true );
					}
				}
			}
		}
		$existing_page = get_page_by_title( 'Enquiry Thank You Page' );

		if ( empty( $existing_page ) ) {
			$new_page                = array(
				'post_title'   => 'Enquiry Thank You Page',
				'post_content' => 'Thank you for the enquiry. We will soon get in touch with you.',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			);
			$post_id                 = wp_insert_post( $new_page );
			$arr['pages']['enquiry'] = $post_id;
			$enquiry_page            = array_merge_recursive( $options, $arr );
			update_option( 'wp_travel_engine_settings', $enquiry_page );
		}

		$pages = array(
			'wp_travel_engine_thank_you'            => array(
				'title'     => 'Thank You',
				'shortcode' => 'THANK_YOU',
			),
			'wp_travel_engine_place_order'          => array(
				'title'     => 'Checkout',
				'shortcode' => 'PLACE_ORDER',
			),
			'wp_travel_engine_terms_and_conditions' => array(
				'title'     => 'Terms and Conditions',
				'shortcode' => '',
			),
			'wp_travel_engine_confirmation_page'    => array(
				'title'     => 'Travellers Information',
				'shortcode' => 'BOOK_CONFIRMATION',
			),
			'wp_travel_engine_dashboard_page'    => array(
				'title'     => 'My Account',
				'shortcode' => 'dashboard',
			),
			'wp_travel_engine_wishlist'          => array(
				'title'     => 'Wishlist',
				'shortcode' => 'WISHLIST',
			)
		);
		foreach ( $pages as $key => $value ) {
			$shortcode = 'WP_TRAVEL_ENGINE_' . $value['shortcode'];
			if( $key == 'wp_travel_engine_dashboard_page'){
				$shortcode = 'wp_travel_engine_' . $value['shortcode'];
			}
			$title     = ucfirst( $value['title'] );

			// Check if this page already exists, with shortcode
			$existing_page = get_page_by_title( $title );

			if ( ! empty( $existing_page ) && 'page' === $existing_page->post_type ) {
				$content = $existing_page->post_content;

				if ( has_shortcode( $content, $shortcode ) ) {
					return false;
				}
			} else {
				// If the page doesn't exist, create it
				if ( strtolower( $title ) == 'terms and conditions' ) {
					$options = get_option( 'wp_travel_engine_settings', array() );
					if ( ! isset( $options['pages'][ $key ] ) ) {

						$new_page             = array(
							'post_title'   => $title,
							'post_content' => '',
							'post_status'  => 'publish',
							'post_type'    => 'page',
						);
						$post_id              = wp_insert_post( $new_page );
						$arr['pages'][ $key ] = $post_id;
						update_option( 'wp_travel_engine_settings', $arr );
					}
				} elseif ( strtolower( $title ) == 'thank you' ) {
					$options = get_option( 'wp_travel_engine_settings', array() );
					if ( ! isset( $options['pages'][ $key ] ) ) {

						$new_page             = array(
							'post_title'   => $title,
							'post_content' => '[WP_TRAVEL_ENGINE_THANK_YOU]',
							'post_status'  => 'publish',
							'post_type'    => 'page',
						);
						$post_id              = wp_insert_post( $new_page );
						$arr['pages'][ $key ] = $post_id;
						update_option( 'wp_travel_engine_settings', $arr );
					}
				} else {
					$options = get_option( 'wp_travel_engine_settings', array() );
					if ( ! isset( $options['pages'][ $key ] ) ) {
						$new_page             = array(
							'post_title'   => $title,
							'post_content' => '[' . $shortcode . ']',
							'post_status'  => 'publish',
							'post_type'    => 'page',
						);
						$post_id              = wp_insert_post( $new_page );
						$arr['pages'][ $key ] = $post_id;
						update_option( 'wp_travel_engine_settings', $arr );
					}
				}
			}
		}

			$default_tabs = array(
				'trip_tabs'  => wte_get_default_settings_tab(),
				'trip_facts' =>
				array(
					'field_id'   => array(
						'1'  => esc_html__( 'Transportation', 'wp-travel-engine' ),
						'2'  => esc_html__( 'Group Size', 'wp-travel-engine' ),
						'3'  => esc_html__( 'Maximum Altitude', 'wp-travel-engine' ),
						'4'  => esc_html__( 'Accomodation', 'wp-travel-engine' ),
						'5'  => esc_html__( 'Fitness level', 'wp-travel-engine' ),
						'6'  => esc_html__( 'Arrival on', 'wp-travel-engine' ),
						'7'  => esc_html__( 'Departure from', 'wp-travel-engine' ),
						'8'  => esc_html__( 'Best season', 'wp-travel-engine' ),
						'9'  => esc_html__( 'Guiding method', 'wp-travel-engine' ),
						'10' => esc_html__( 'Tour type', 'wp-travel-engine' ),
						'11' => esc_html__( 'Meals', 'wp-travel-engine' ),
						'12' => esc_html__( 'Language', 'wp-travel-engine' ),
					),

					'field_icon' => array(
						'1'  => 'fas fa-bus',
						'2'  => 'fas fa-user',
						'3'  => 'fas fa-mountain',
						'4'  => 'fas fa-hotel',
						'5'  => 'fas fa-running',
						'6'  => 'fas fa-plane-arrival',
						'7'  => 'fas fa-plane-departure',
						'8'  => 'fas fa-cloud-sun',
						'9'  => 'fas fa-map-signs',
						'10' => 'fas fa-hiking',
						'11' => 'fas fa-utensils',
						'12' => 'fas fa-language',
					),

					'field_type' => array(
						'1'  => 'text',
						'2'  => 'text',
						'3'  => 'text',
						'4'  => 'text',
						'5'  => 'text',
						'6'  => 'text',
						'7'  => 'text',
						'8'  => 'text',
						'9'  => 'text',
						'10' => 'text',
						'11' => 'text',
						'12' => 'text',
					),

					'fid'        => array(
						'1'  => '1',
						'2'  => '2',
						'3'  => '3',
						'4'  => '4',
						'5'  => '5',
						'6'  => '6',
						'7'  => '7',
						'8'  => '8',
						'9'  => '9',
						'10' => '10',
						'11' => '11',
						'12' => '12',
					),
				),
				'email'      =>
				array(
					'emails' => get_option( 'admin_email' ),
				),
			);

			$wp_travel_engine_option_settings = get_option( 'wp_travel_engine_settings', array() );

			// if( isset( $wp_travel_engine_option_settings['trip_tabs'] ) && $wp_travel_engine_option_settings['trip_tabs']!='' && is_array( $wp_travel_engine_option_settings['trip_tabs'] ) )
			// {
			if ( ! isset( $wp_travel_engine_option_settings['trip_tabs'] ) && ! isset( $wp_travel_engine_option_settings['trip_facts'] ) ) {
					$default_tab_settings = array_merge_recursive( $wp_travel_engine_option_settings, $default_tabs );
					update_option( 'wp_travel_engine_settings', $default_tab_settings );
			}

	}

}
