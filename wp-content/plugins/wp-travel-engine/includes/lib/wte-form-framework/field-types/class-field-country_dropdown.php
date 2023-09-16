<?php
/**
 * Countries dropdown field class
 *
 * @package WP Travel Engine
 */
class WP_Travel_Engine_Form_Field_Country_Dropdown extends WP_Travel_Engine_Form_Field_Select {

	/**
	 * Field type name
	 *
	 * @var string
	 */
	protected $field_type = 'country_dropdown';

	/**
	 * Initialize class
	 *
	 * @param obj $field
	 * @return void
	 */
	function init( $field ) {

		$wte             = \wte_functions();
		$country_options = $wte->wp_travel_engine_country_list();
		$country_options = array_combine( $country_options, $country_options );

		$country_options = array( '' => __( 'Choose a country*', 'wp-travel-engine' ) ) + $country_options;

		$this->field = $field;

		$this->field['options'] = $country_options;

		return $this;
	}
}
