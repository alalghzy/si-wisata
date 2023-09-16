<?php
/**
 * Text info field type
 *
 * @package WP Travel Engine
 */
class WP_Travel_Engine_Form_Field_Text_Info {

	/**
	 * Field.
	 *
	 * @var obj
	 */
	protected $field;

	/**
	 * Field type object.
	 *
	 * @var string
	 */
	protected $field_type = 'text_info';

	/**
	 * Initialize the field class.
	 *
	 * @param [type] $field
	 * @return void
	 */
	function init( $field ) {

		$this->field = $field;

		return $this;
	}

	/**
	 * Render field layout.
	 *
	 * @param boolean $display
	 * @return void
	 */
	function render( $display = true ) {

		$before_field = isset( $this->field['before_field'] ) ? $this->field['before_field'] : '';

		$output = '';

		if ( ! $this->field['remove_wrap'] ) :
			$output .= '<div class="wp-travel-engine-info-wrap">';
		endif;

		if ( ! empty( $before_field ) ) {

			$output .= sprintf( '<span>%s</span>', $before_field );
		}

		$output .= sprintf( '<span class="wp-travel-engine-info" id="%s">%s</span>', $this->field['id'], $this->field['default'] );

		if ( ! $this->field['remove_wrap'] ) :

				$output .= '</div>';

		endif;

		if ( ! $display ) {

			return $output;

		}

		echo $output;
	}
}
