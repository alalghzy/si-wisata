<?php
/**
 * Helper functions for trip packages.
 *
 * @since 5.0.0
 */

namespace WPTravelEngine\Packages;

use WPTravelEngine\Posttype\Trip;

function get_packages_by_trip_id( $trip_id ) {
	// Get Trip package Ids.
	global $wtetrip;

	if ( $trip_id ) {
		$wtetrip = \wte_get_trip( $trip_id );
	}
	$packages = array();

	if ( $wtetrip instanceof \WPTravelEngine\Posttype\Trip ) {
		$packages = $wtetrip->packages;
	} else {
		$package_ids = \get_post_meta( $trip_id, 'packages_ids', ! 0 );

		if ( ! is_array( $package_ids ) ) {
			$package_ids = array();
		}

		$packages = \get_posts(
			array(
				'post_type' => 'trip-packages',
				'include'   => $package_ids,
			)
		);

		$_packages = array();
		foreach ( $packages as $package ) {
			$_packages[ $package->ID ] = $package;
		}

		$packages = $_packages;

	}

	return $packages;
}

function get_package_by_id( $package_id ) {
	return get_post( $package_id );
}

function get_package_dates_by_package_id( $package ) {

	if ( $package instanceof \WP_Post ) {
		$_package = $package;
	} else {
		$_package = WP_Post::get_instance( $package );
	}

	// @TODO: Create package date class.
	$package_dates = $_package->{'package-dates'};

	return is_array( $package_dates ) ? $package_dates : array();

}

function get_booked_seats_number_by_date( $trip_id, $date = null ) {
	$fsd_booked_seats = apply_filters( 'wptravelengine_booking_inventory', null, $trip_id );
	if ( is_null( $fsd_booked_seats ) ) {
		$fsd_booked_seats = get_post_meta( $trip_id, 'wte_fsd_booked_seats', true );
	}
	if ( is_null( $date ) ) {
		return is_array( $fsd_booked_seats ) ? $fsd_booked_seats : array();
	}

	return isset( $fsd_booked_seats[ $date ] ) ? $fsd_booked_seats[ $date ] : array(
		'booked'  => 0,
		'datestr' => $date,
	);
}

function get_trip_lowest_price_package( $trip_id = null ) {
	global $wtetrip;
	$_wtetrip = $wtetrip;

	if ( $trip_id ) {
		$_wtetrip = Trip::instance( $trip_id );
	}

	return $_wtetrip->{'default_package'};
}

function get_trip_lowest_price( $trip_id ) {
	$lowest_cost_package = get_trip_lowest_price_package( $trip_id );

	if ( is_null( $lowest_cost_package ) ) {
		return 0;
	}
	$package_categories = (object) $lowest_cost_package->{'package-categories'};

	$primary_pricing_category = get_option( 'primary_pricing_category', 0 );

	$category_price = $package_categories->prices[ $primary_pricing_category ];
	if ( isset( $package_categories->enabled_sale[ $primary_pricing_category ] ) && '1' === $package_categories->enabled_sale[ $primary_pricing_category ] ) {
		$category_price = $package_categories->sale_prices[ $primary_pricing_category ];
	}

	return (float) $category_price;
}

function get_trip_lowest_price_by_package_id( $package_id ) {

	$lowest_cost_package = get_post( $package_id );

	if ( ! $lowest_cost_package || 'trip-packages' !== $lowest_cost_package->post_type ) {
		return 0;
	}
	$package_categories = (object) $lowest_cost_package->{'package-categories'};

	$primary_pricing_category = get_option( 'primary_pricing_category', 0 );

	$category_price = isset( $package_categories->prices[ $primary_pricing_category ] ) ? $package_categories->prices[ $primary_pricing_category ] : 0;
	if ( isset( $package_categories->enabled_sale[ $primary_pricing_category ] ) && '1' === $package_categories->enabled_sale[ $primary_pricing_category ] ) {
		$category_price = $package_categories->sale_prices[ $primary_pricing_category ];
	}

	return (float) $category_price;
}

function get_packages_pricing_categories() {
	global $wpdb;

	$pricing_taxonomy = 'trip-packages-categories';

	$results = wp_cache_get( 'trip_package_categories', 'wptravelengine' );
	if ( ! $results ) {
		$query   = "SELECT {$wpdb->terms}.term_id, {$wpdb->terms}.name FROM {$wpdb->term_taxonomy} INNER JOIN {$wpdb->terms} ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id WHERE taxonomy = '{$pricing_taxonomy}'";
		$results = $wpdb->get_results( $query );

		if ( empty( $results ) && is_array( $results ) && function_exists( 'wp_insert_term' ) ) {
			$term = wp_insert_term( 'Adult', $pricing_taxonomy, array( 'slug' => 'adult' ) );
			if ( ! \is_wp_error( $term ) ) {
				update_option( 'primary_pricing_category', $term['term_id'] );
			}
			$term    = wp_insert_term( 'Child', $pricing_taxonomy, array( 'slug' => 'child' ) );
			$results = $wpdb->get_results( $query );
		}
		wp_cache_add( 'trip_package_categories', $results, 'wptravelengine' );
	}

	return $results;
}

/**
 * Update Trip Package.
 *
 * @since 5.3.0
 */
function update_trip_packages( $post_ID, $posted_data ) {
	$pricing_ids = null;
	$categories  = null;

	if ( ! isset( $posted_data['trip-edit-tab__dates-pricings'] ) ) {
		return;
	}

	foreach ( array( 'packages_ids', 'categories' ) as $meta_key ) {
		if ( ! isset( $posted_data[ $meta_key ] ) ) {
			continue;
		}
		$meta_input[ $meta_key ] = wp_unslash( $posted_data[ $meta_key ] );
	}

	$package_post_type = 'trip-packages';

	$packages_ids               = isset( $posted_data['packages_ids'] ) ? $posted_data['packages_ids'] : array();
	$packages_titles            = isset( $posted_data['packages_titles'] ) ? wp_unslash( $posted_data['packages_titles'] ) : array();
	$packages_descriptions      = isset( $posted_data['packages_descriptions'] ) ? wp_unslash( $posted_data['packages_descriptions'] ) : array();
	$categories                 = isset( $posted_data['categories'] ) ? $posted_data['categories'] : array();
	$primary_pricing_categories = isset( $posted_data['packages_primary_category'] ) ? (array) $posted_data['packages_primary_category'] : array();

	$package_weekly_time_slots  = isset( $posted_data['package_weekly_time_slots'] ) ? $posted_data['package_weekly_time_slots'] : array();

	$package_weekly_time_slots_enable = isset( $posted_data['package_weekly_time_slots_enable'] ) ? $posted_data['package_weekly_time_slots_enable'] : 'no';

	$meta_packages_ids        = array();
	$primary_pricing_category = get_option( 'primary_pricing_category', 0 );
	$lowest_price             = 0;

	foreach ( $packages_ids as $index => $package_id ) {

		if ( empty( trim( $package_id ) ) ) {
			continue;
		}

		$meta_input = array();

		// Update Weekly time slots.
		if ( isset( $package_weekly_time_slots[ $package_id ] ) && is_array( $package_weekly_time_slots[ $package_id ] ) ) {
			$slots = [];

			foreach( $package_weekly_time_slots[ $package_id ] as $index => $time_slots ) {
				if ( is_array( $time_slots ) ) {
					$slots[ $index ] = array_filter( $time_slots, function( $time_slot ) {
						return ! empty( $time_slot );
					} );
				}
			}

			$settings = get_post_meta( $post_ID, 'wp_travel_engine_setting', true );

			$trip_duration = $settings['trip_duration'];

			$now = new \DateTime();
			$dates = array();

			if ( ! empty( $slots  ) ) {
				$weekdays = array_combine( range( 1, 7 ), array( 'MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU' ) );
				foreach ( $slots  as $index => $slot ) {
					if ( empty( $slot ) ) {
						continue;
					}
					$date = (int) $now->format( 'j' ) - ( (int) $now->format( 'N' ) - (int) $index );
					$now->setDate( (int) $now->format( 'Y' ), (int) $now->format( 'm' ), $date );

					$times = array();

					if ( is_array( $slot ) ) {
						foreach ( $slot as $time ) {
							if ( empty( $time ) ) {
								continue;
							}
							$_time = explode( ':', $time );
							$_date_time = new \DateTime();
							$_date_time->setTime( (int) $_time[0], (int) $_time[1], 0, 0 );
							$from = $_date_time->format( 'H:i' );
							$_date_time->add( new \DateInterval( 'PT' . $trip_duration . 'H' ) );
							$to = $_date_time->format( 'H:i' );
							$times[] = array(
								'from' => $from,
								'to'   => $to,
							);
						}
					}
					$dates[ $now->format( 'Ymd' ) ] = array(
						'dtstart'      => $now->format( 'Y-m-d' ),
						'times'        => $times,
						'is_recurring' => true,
						'rrule'        => array(
							'r_frequency' => 'WEEKLY',
							'r_weekdays'  => array( $weekdays[ $index ] ),
							'r_until'     => implode( '-', array( $now->format( 'Y' ) + 1, $now->format( 'm' ), $now->format( 'd' ) ) ),
						),
					);
				}
				update_post_meta( +$package_id, 'package-dates', $dates );
			}

			update_post_meta( +$package_id, 'weekly_time_slots', $slots );

		}

		if ( isset( $package_weekly_time_slots_enable[ $package_id ] ) ) {
			update_post_meta( +$package_id, 'enable_weekly_time_slots', $package_weekly_time_slots_enable[ $package_id ] );
		}

		// Update Categories.
		if ( isset( $categories[ $package_id ] ) ) {
			$package_categories = $categories[ $package_id ];

			$meta_input['package-categories'] = $package_categories;

			if ( $primary_pricing_category && isset( $package_categories['c_ids'][ $primary_pricing_category ] ) ) {
				if ( isset( $package_categories['enabled_sale'][ $primary_pricing_category ] ) && '1' === $package_categories['enabled_sale'][ $primary_pricing_category ] ) {
					$lowest_price = ! empty( $package_categories['sale_prices'][ $primary_pricing_category ] ) && ( 0 === $lowest_price || (float) $package_categories['sale_prices'][ $primary_pricing_category ] < $lowest_price ) ? (float) $package_categories['sale_prices'][ $primary_pricing_category ] : $lowest_price;
				} else {
					$lowest_price = ! empty( $package_categories['prices'][ $primary_pricing_category ] ) && ( 0 === $lowest_price || (float) $package_categories['prices'][ $primary_pricing_category ] < $lowest_price ) ? (float) $package_categories['prices'][ $primary_pricing_category ] : $lowest_price;
				}
			}

			update_post_meta( +$package_id, 'package-categories', $package_categories );
		}

		// Update Primary Pricing Category.
		if ( isset( $primary_pricing_categories[ $package_id ] ) ) {
			$primary_pricing_category = $primary_pricing_categories[ $package_id ];

			$meta_input['primary_pricing_category'] = $primary_pricing_category;

			update_post_meta( +$package_id, 'primary_pricing_category', $primary_pricing_category );
		}

		$postarr             = new \stdClass();
		$postarr->ID         = $package_id;
		$postarr->meta_input = $meta_input;
		if ( isset( $packages_titles[ $package_id ] ) ) {
			$postarr->post_title = $packages_titles[ $package_id ];
		}

		if ( isset( $packages_descriptions[ $package_id ] ) ) {
			$postarr->post_content = $packages_descriptions[ $package_id ];
		}

		$new_package_id = wp_update_post( $postarr );
		if ( $package_id ) {
			$meta_packages_ids[] = $new_package_id;
			update_post_meta( $package_id, 'trip_ID', $post_ID );
		}
		do_action( 'save_trip_package', $new_package_id, $posted_data, $post_ID );
	}

	update_post_meta( $post_ID, 'packages_ids', array_values( $meta_packages_ids ) );
	\Wp_Travel_Engine_Admin::update_search_params_meta( get_post( $post_ID ) );
}
