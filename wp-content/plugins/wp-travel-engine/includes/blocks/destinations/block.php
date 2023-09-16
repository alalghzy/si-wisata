<?php
/**
 * Content for Destination Block.
 *
 * @package wp-travel-engine/blocks
 */

if ( isset( $attributes ) ) {
	$attributes['taxonomy'] = 'destination';
	include dirname( __DIR__ ) . '/terms/block.php';
}
