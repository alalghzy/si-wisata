<?php
/**
 * Booking Inventory.
 *
 * @since 5.5.3
 */
namespace WPTravelEngine\Core;

class Booking_Inventory {

	/**
	 * Booking Object.
	 */
	protected $trip_booking;

	public function set( $key, $value ) {
		$this->{$key} = $value;
	}

	public function get_booking_inventory_record( $trip_id ) {
		$dates = get_post_meta( $trip_id, '_booking_inventory', true );

		if ( ! is_array( $dates ) ) {
			$dates = array();
		}

		return $dates;

	}

	public function update_booking_inventory_record( $trip_id, $data = array() ) {
		update_post_meta( $trip_id, '_booking_inventory', $data );
	}

	public function update_pax( $date_key, $pax = 0, $trip_id = 0, $booking_id = 0 ) {
		if ( ! $booking_id ) {
			return;
		}
		$dates = $this->get_booking_inventory_record( $trip_id );
		if ( $pax <= 0 ) {
			unset( $dates[ $date_key ][ $booking_id ] );
		} else {
			$dates[ $date_key ][ $booking_id ] = $pax;
		}
		$this->update_booking_inventory_record( $trip_id, $dates );
	}

	public function update_inventory_by_booking( $booking ) {
		$items = get_post_meta( $booking->ID, 'order_trips', true );

		foreach ( $items as $cart_key => $item ) {
			$item    = (object) $item;
			$trip_id = $item->ID;

			$pax = 0;
			if ( is_array( $item->pax ) ) {
				$pax = array_sum( $item->pax );
			}

			if ( $pax <= 0 ) {
				continue;
			}

			$this->update_pax( $cart_key, $pax, $trip_id, $booking->ID );
		}
	}

	public static function get_date_from_cart_key( $cart_key ) {
		preg_match( '/(cart)_(\d+)_(\d+)_([\d-]+)_([\d-]+)/', $cart_key, $chunks );
		$datetime = new \DateTime();

		$datetime->setTimezone( new \DateTimeZone('utc') );
		$datetime->setDate( ...explode( '-', $chunks[4] ) );
		$datetime->setTime( ...explode( '-', $chunks[5] ) );

		return $datetime;
	}

	public static function booking_inventory( $data, $trip_id ) {
		$instance   = new self();
		$inventory  = $instance->get_booking_inventory_record( $trip_id );
		$_inventory = array();
		if ( is_array( $inventory ) ) {
			foreach ( $inventory as $cart_id => $pax ) {
				$datetime                                = self::get_date_from_cart_key( $cart_id );
				$_inventory[ $datetime->getTimeStamp() ] = array(
					'booked'  => array_sum( $pax ),
					'datestr' => $datetime->getTimeStamp(),
				);
			}
		}

		$old_booking_record = get_post_meta( $trip_id, 'wte_fsd_booked_seats', true );
		if ( is_array( $old_booking_record ) ) {
			$_inventory = array_column( array_merge( $old_booking_record, $_inventory ), null, 'datestr' );
		}

		return $_inventory;

	}
}
