<?php
/**
 * Heading form field class
 *
 * @package WP Travel Engine
 */

class WP_Travel_Engine_Form_Field_Heading {

	/**
	 * Field with attributes
	 *
	 * @var [type]
	 */
	protected $field;

	/**
	 * Field type name
	 *
	 * @var string
	 */
	protected $field_type = 'heading';

	/**
	 * Initialize field type class.
	 *
	 * @param array $field
	 * @return void
	 */
	function init( $field ) {

		$this->field = $field;

		return $this;
	}

	/**
	 * Field type render.
	 *
	 * @param boolean $display
	 * @return void
	 */
	function render( $display = true ) {

		$before_field = '';

		if ( isset( $this->field['before_field'] ) ) :

			$before_field_class = isset( $this->field['before_field_class'] ) ? $this->field['before_field_class'] : '';
			$before_field       = sprintf( '<span class="%1$s">%2$s</span>', $before_field_class, $this->field['before_field'] );

		endif;

		$after_field = '';

		if ( isset( $this->field['after_field'] ) ) :

			$after_field_class = isset( $this->field['after_field_class'] ) ? $this->field['after_field_class'] : '';
			$after_field       = sprintf( '<span class="%1$s">%2$s</span>', $after_field_class, $this->field['after_field'] );

		endif;

		$output = sprintf( '%1$s<%2$s class="%3$s">%4$s</%2$s>%5$s', $before_field, $this->field['tag'], $this->field['class'], $this->field['title'], $after_field );

		if ( ! $display ) {

			return $output;

		}

		echo $output;
	}
}
