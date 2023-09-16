<?php
/**
 * All date and pricing migration to new db tables.
 *
 * @package wp-travel-engine/upgrade
 * @since 4.2.2
 */

namespace WTE\Upgrade500;

/**
 * Creates Trip Package for the trip.
 *
 * @param object $trip Trip Object.
 * @param string $prefix Prefix for Package Name.
 * @return int Package ID.
 */
function wte_create_trip_package( $trip, $prefix = '' ) {
	$package_id = wp_insert_post(
		array(
			'post_title'  => 'Default',
			'post_status' => 'publish',
			'post_type'   => 'trip-packages',
		)
	);

	return $package_id;
}

/**
 * Prepares Date Date for Package.
 *
 * @param array $fsds Old date meta.
 * @return array New Date Data.
 */
function wte_get_dates_meta_for_pricing( $fsds ) {
	$dates = array();

	if ( isset( $fsds['departure_dates'] ) && ! empty( $fsds['departure_dates']['sdate'] ) ) {
		$keys = array_keys( $fsds['departure_dates']['sdate'] );

		$sdates = $fsds['departure_dates']['sdate'];
		$edates = $fsds['departure_dates']['edate'];
		$costs  = $fsds['departure_dates']['cost'];
		$seats  = $fsds['departure_dates']['seats_available'];

		$availability_types = $fsds['departure_dates']['availability_type'];

		$meta_key__dates = array();

		foreach ( $keys as $key ) {
			$recurring     = isset( $fsds['departure_dates'][ $key ]['recurring'] ) && is_array( $fsds['departure_dates'][ $key ]['recurring'] ) ? $fsds['departure_dates'][ $key ]['recurring'] : array();
			$recurring_obj = array(
				'enable'      => isset( $recurring['enable'] ) && in_array( $recurring['enable'], array( 'true', true ), true ),
				'r_frequency' => ! empty( $recurring['type'] ) && in_array( $recurring['type'], array( 'DAILY', 'WEEKLY', 'MONTHLY', 'YEARLY' ), true ) ? $recurring['type'] : 'DAILY',
				'r_dtstart'   => $sdates[ $key ],
				'r_until'     => '',
				'r_count'     => ! empty( $recurring['limit'] ) ? +$recurring['limit'] : 10,
				'r_weekdays'  => isset( $recurring['week_days'] ) ? $recurring['week_days'] : array(),
				'r_months'    => isset( $recurring['months'] ) ? $recurring['months'] : array(),
			);

			$dates[] = array(
				'dtstart'            => $sdates[ $key ],
				'seats'              => isset( $seats[ $key ] ) ? $seats[ $key ] : '',
				'availability_label' => $availability_types[ $key ],
				'is_recurring'       => isset( $recurring['enable'] ) && in_array( $recurring['enable'], array( 'true', true ), true ),
				'rrule'              => $recurring_obj,
				'_deprecated_cost'   => $costs[ $key ],
			);
		}
	}
	return $dates;
}

/**
 * Migrates Trip Date and Pricings.
 *
 * @param object  $trip Trip Object.
 * @param boolena $force Force Migration.
 *
 * @return void
 */
function migrate_trip_dates_and_pricings( $trip, $force = false ) {

	$trip_version = get_post_meta( $trip->ID, 'trip_version', true );
	$packages_ids = get_post_meta( $trip->ID, 'packages_ids', true );
	$has_packages = is_array( $packages_ids ) && ! empty( $packages_ids );

	if ( ( ( '2.0.0' === $trip_version ) || $has_packages ) && ! $force ) {
		return;
	}

	$fsds         = get_post_meta( $trip->ID, 'WTE_Fixed_Starting_Dates_setting', true );
	$fsds         = ! empty( $fsds ) ? (array) $fsds : array();
	$booked_seats = get_post_meta( $trip->ID, 'wte_fsd_booked_seats', true );
	$booked_seats = ! empty( $booked_seats ) ? (array) $booked_seats : array();
	$trip_setting = get_post_meta( $trip->ID, 'wp_travel_engine_setting', true );
	$trip_setting = ! empty( $trip_setting ) ? (array) $trip_setting : array();

	$legacy_multiple_pricings = ! empty( $trip_setting['multiple_pricing'] ) ? $trip_setting['multiple_pricing'] : array(
		'adult' => array(
			'label'       => 'Adult',
			'price'       => $trip_setting['trip_prev_price'],
			'sale_price'  => $trip_setting['trip_price'],
			'enable_sale' => $trip_setting['sale'],
			'price_type'  => 'per-person',
		),
	);

	$new_fsds = wte_get_dates_meta_for_pricing( $fsds );

	$package_ids = array();

	$package_id = wte_create_trip_package( $trip );

	if ( ! $package_id ) {
		return;
	}

	$categories = array();

	foreach ( $legacy_multiple_pricings as $name => $pricing ) {
		$category_id = 0;
		$term        = get_term_by( 'slug', $name, 'trip-packages-categories' );
		if ( ! $term ) {
			$result = wp_insert_term( $pricing['label'], 'trip-packages-categories', array( 'slug' => $name ) );
			if ( ! \is_wp_error( $result ) ) {
				$category_id = $result['term_id'];
			} else {
				continue;
			}
		} else {
			$category_id = $term->term_id;
		}

		if ( 'adult' === $name ) {
			update_option( 'primary_pricing_category', $category_id );
		}

		$categories['c_ids'][ $category_id ]        = $category_id;
		$categories['labels'][ $category_id ]       = $pricing['label'];
		$categories['prices'][ $category_id ]       = isset( $pricing['price'] ) ? $pricing['price'] : '';
		$categories['enabled_sale'][ $category_id ] = ! empty( $pricing['enable_sale'] );
		$categories['sale_prices'][ $category_id ]  = isset( $pricing['sale_price'] ) ? $pricing['sale_price'] : '';
		$categories['price_types'][ $category_id ]  = 'group' === $name ? 'per-group' : $pricing['price_type'];
		$categories['min_paxes'][ $category_id ]    = 0;

		$categories['group-pricing'][ $category_id ] = array();
		if ( isset( $trip_setting['group'] ) && is_array( $trip_setting['group'] ) ) {
			$group_pricing = array();
			if ( 'adult' === $name && isset( $trip_setting['group']['traveler'] ) ) {
				$chunk_count = is_array( $trip_setting['group']['traveler'] ) ? array_chunk( $trip_setting['group']['traveler'], 2 ) : array();
				$cost        = $trip_setting['group']['cost'];
				$categories['enabled_group_discount'][ $category_id ] = ! ! $trip_setting['group']['discount'];
			} elseif ( isset( $trip_setting['group'][ $name ] ) ) {
				$chunk_count = is_array( $trip_setting['group'][ $name ] ) ? array_chunk( $trip_setting['group'][ $name ], 2 ) : array();
				$cost        = $trip_setting['group'][ "{$name}_cost" ];
				$categories['enabled_group_discount'][ $category_id ] = ! ! $trip_setting[ "{$name}-group" ]['discount'];
			}
			foreach ( $chunk_count as $index => $counts ) {
				$group_pricing[] = array(
					'from'  => +$counts[0],
					'to'    => ! empty( $counts[1] ) ? +$counts[1] : '',
					'price' => ! empty( $cost[ ( +$index + 1 ) ] ) ? (float) $cost[ ( +$index + 1 ) ] : '',
				);
			}
			$categories['group-pricing'][ $category_id ] = $group_pricing;
		}
	}

	update_post_meta( $package_id, 'package-dates', $new_fsds );
	update_post_meta( $package_id, 'package-categories', $categories );
	update_post_meta( $package_id, 'group-pricing', $categories['group-pricing'] );
	update_post_meta( $package_id, 'trip_ID', $trip->ID );
	$package_ids[] = $package_id;

	// Add New Package ID to the Trip.
	update_post_meta( $trip->ID, 'packages_ids', $package_ids );
	update_post_meta( $trip->ID, 'trip_version', '2.0.0' );
}

/**
 * Process migration for all the trips.
 *
 * @param array $tables Tables.
 * @return void;
 */
function wte_migrate_dates_and_pricings( $tables = array() ) {
	global $wpdb; // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery

	if ( get_option( 'wte_migrated_to_multiple_pricing', false ) === 'done' ) {
		return;
	}

	$where  = $wpdb->prepare( "{$wpdb->posts}.post_type = %s", \WP_TRAVEL_ENGINE_POST_TYPE );
	$where .= " AND {$wpdb->posts}.post_status IN ( 'publish','draft' )";

	$trip_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE {$where}" ); // phpcs:ignore

	if ( $trip_ids ) {
		global $wp_query;
		$wp_query->in_the_lopp = true;
		while ( $next_trips = array_splice( $trip_ids, 0, 20 ) ) { // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
			$where = 'WHERE ID IN (' . join( ',', $next_trips ) . ')';
			$trips = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} $where" ); // phpcs:ignore
			foreach ( $trips as $trip ) {
				migrate_trip_dates_and_pricings( $trip );
			}
		}
	}

	update_option( 'wte_migrated_to_multiple_pricing', 'done' );
}

/**
 * Triggers Migration Process.
 */
function wte_process_migration() {
	wte_migrate_dates_and_pricings();
}
