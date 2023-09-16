<?php
/**
 * Trips List field class
 *
 * @package WP Travel Engine
 */
class WP_Travel_Engine_Form_Field_Trips_List extends WP_Travel_Engine_Form_Field_Select {

	/**
	 * Field type name
	 *
	 * @var string
	 */
	protected $field_type = 'trips_list';

	/**
	 * Initialize class
	 *
	 * @param obj $field
	 * @return void
	 */
	function init( $field ) {

		$wte           = \wte_functions();
		$trips_options = wp_travel_engine_get_trips_array( $use_titles = true );

		$trips_options = array( '' => __( 'Choose a Trip', 'wp-travel-engine' ) ) + $trips_options;

		$this->field = $field;

		$this->field['options'] = $trips_options;

		return $this;
	}
}
