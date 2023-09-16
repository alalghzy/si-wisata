<?php
return array(
	'label'       => __( 'Display', 'wp-travel-engine' ),
	'order'       => 3,
	'has_updates' => 'wte_note_5.5.7',
	'sub_tabs'    => array(
		'trip-card'   => array(
			'label'        => __( 'Trip Card/Listing', 'wp-travel-engine' ),
			'content_path' => __DIR__ . '/trip-card.php',
			'current'      => true,
		),
		'single-trip' => array(
			'label'        => __( 'Single Trip', 'wp-travel-engine' ),
			'content_path' => __DIR__ . '/single-trip.php',
		),
		'trip-archive' => array(
			'label'        => __( 'Trip Archive', 'wp-travel-engine' ),
			'content_path' => __DIR__ . '/trip-archive.php',
		),
	),
);
