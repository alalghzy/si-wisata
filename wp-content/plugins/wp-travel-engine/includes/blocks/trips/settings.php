<?php
/**
 * Trips Block and Elementor Settings.
 */
return array(
	'excerptLength'          => array(
		'type'    => 'number',
		'default' => 20,
	),
	'showSectionHeading'     => array(
		'type'    => 'boolean',
		'default' => true,
	),
	'sectionHeading'         => array(
		'type'    => 'string',
		'default' => '',
	),
	'showSectionDescription' => array(
		'type'    => 'boolean',
		'default' => true,
	),
	'sectionDescription'     => array(
		'type'    => 'string',
		'default' => '',
	),
	'sectionHeadingLevel'    => array(
		'type'    => 'number',
		'default' => 2,
	),
	'layout'                 => array(
		'type'    => 'string',
		'default' => 'grid',
	),
	'tripsCountPerRow'       => array(
		'type'    => 'number',
		'default' => 3,
	),
	'cardlayout'             => array(
		'type'    => 'number',
		'default' => 1,
	),
	'viewMoreButtonText'     => array(
		'type'    => 'string',
		'default' => __( 'View Details', 'wp-travel-engine' ),
	),
	'filters'                => array(
		'type'    => 'object',
		'default' => array(
			'tripsCount'     => 6,
			'listby'         => 'latest',
			'tripsToDisplay' => array(),
		),
	),
	'layoutFilters'          => array(
		'type'    => 'object',
		'default' => array(
			'showFeaturedRibbon'    => true,
			'showDescription'       => true,
			'showFeaturedImage'     => true,
			'showTitle'             => true,
			'showPrice'             => true,
			'showStrikedPrice'      => true,
			'showDuration'          => true,
			'showLocation'          => true,
			'showReviews'           => false,
			'showDiscount'          => true,
			'showActivities'        => false,
			'showTripType'          => false,
			'showGroupSize'         => false,
			'showTripAvailableTime' => false,
			'showViewMoreButton'    => true,
		),
	),
	'controls'               => array(
		'type'    => 'object',
		'default' => array(
			'trip_section_settings' => array(
				'type'        => 'control_section',
				'subcontrols' => array(
					'showSectionHeading'     => array(
						'label' => __( 'Show Section Heading', 'wp-travel-engine' ),
						'type'  => 'SWITCHER',
					),
					'sectionHeading'         => array(
						'label' => __( 'Section Heading', 'wp-travel-engine' ),
						'type'  => 'TEXT',
					),
					'showSectionDescription' => array(
						'label' => __( 'Show Section Description', 'wp-travel-engine' ),
						'type'  => 'SWITCHER',
					),
					'sectionDescription'     => array(
						'label' => __( 'Section Description', 'wp-travel-engine' ),
						'type'  => 'TEXTAREA',
					),
				),
			),
			'excerptLength'         => array(
				'label' => __( 'Excerpt Length', 'wp-travel-engine' ),
				'type'  => 'NUMBER',
			),
			'layout'                => array(
				'label'   => __( 'Card View', 'wp-travel-engine' ),
				'type'    => 'SELECT',
				'options' => array(
					'grid' => __( 'Grid', 'wp-travel-engine' ),
					'list' => __( 'List', 'wp-travel-engine' ),
				),
			),
			'cardlayout'            => array(
				'label'   => __( 'Widget Layouts', 'wp-travel-engine' ),
				'type'    => 'SELECT',
				'options' => array(
					1 => __( 'Layout 1', 'wp-travel-engine' ),
					2 => __( 'Layout 2', 'wp-travel-engine' ),
					3 => __( 'Layout 3', 'wp-travel-engine' ),
					4 => __( 'Layout 4', 'wp-travel-engine' ),
				),
			),
			'tripsCountPerRow'      => array(
				'label' => __( 'Columns', 'wp-travel-engine' ),
				'type'  => 'NUMBER',
			),
			'tripsCount'            => array(
				'label'   => __( 'Number of Trips', 'wp-travel-engine' ),
				'type'    => 'NUMBER',
				'default' => 6,
			),
		),
	),
);
