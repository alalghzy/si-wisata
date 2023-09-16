<?php
	// shortcode for trip facts
function wptravelengine_trip_facts_shortcode( $atts = '' ) {
	ob_start();
	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts,
		'trip_facts_shortcode'
	);

	include __DIR__ . '/trip-facts.php';

	return ob_get_clean();
}
add_shortcode( 'Trip_Info_Shortcode', 'wptravelengine_trip_facts_shortcode' );
