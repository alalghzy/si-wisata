<?php
/**
 * Content for Trip Types Block.
 *
 * @package wp-travel-engine/blocks
 */

if ( isset( $attributes ) ) {
	$attributes['taxonomy'] = 'trip_types';
	include dirname( __DIR__ ) . '/terms/block.php';
}
