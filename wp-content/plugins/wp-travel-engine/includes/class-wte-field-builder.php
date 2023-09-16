<?php
/**
 * Field Renderer.
 *
 * @since 5.3.0
 */

 /**
  *
  * @since 5.0.0
  */
class WTE_Field {

	public $attributes;

	public $classes = '';

	public $wrapper_classes = '';

	public $type = 'text';

	public $readonly = false;

	public $id = '';

	public $label = '';

	public $name;

	public $value = '';

	public $default_value = '';

	public $options = array();

	public $tooltip = '';

	public function __construct( $type, $field ) {
		foreach ( array(
			'label',
			'attributes',
			'classes',
			'type',
			'readonly',
			'name',
			'value',
			'default_value',
			'placeholder',
			'id',
			'tooltip',
			'subfields',
			'wrapper_classes',
			'after_field',
			'before_field',
			'options',
			'checked_classname',
		) as $attribute ) {
			if ( isset( $field[ $attribute ] ) ) {
				$this->{$attribute} = $field[ $attribute ];
			}
		}
	}

	private function valid_attributes() {   }

	private function get_classes() {
		$typed_classes = array(
			'text'       => 'wpte-text',
			'datepicker' => 'wte-flatpickr',
		);
		$classes       = '';
		if ( isset( $typed_classes[ $this->type ] ) ) {
			$classes .= " {$typed_classes[$this->type]}";
		}
		if ( is_array( $this->classes ) ) {
			$classes .= implode( ' ', $this->classes );
		} else {
			$classes .= " {$classes}";
		}

		return $classes;
	}

	private function get_attributes( array $attr_names = array(
		'attributes',
		'type',
		'readonly',
		'name',
		'id',
		'value',
		'classes',
		'placeholder',
	) ) {
		$attributes_string = array();
		foreach ( $attr_names as $aname ) {
			if ( 'value' === $aname ) {
				if ( 'textarea' === $this->type ) {
					continue;
				}
				if ( $this->value ) {
					$attributes_string[] = "value=\"{$this->value}\"";
				} else {
					$attributes_string[] = "value=\"{$this->default_value}\"";
				}
				continue;
			}
			if ( isset( $this->{$aname} ) ) {
				if ( 'attributes' === $aname ) {
					foreach ( $this->attributes as $attr_name => $attr_value ) {
						if ( in_array( $attr_name, array( 'selected', 'checked' ), ! 0 ) ) {
							$attributes_string[] = $attr_value;
							continue;
						}
						$attributes_string[] = "{$attr_name}=\"$attr_value\"";
					}
					continue;
				}
				if ( 'classes' === $aname ) {
					$attributes_string[] = 'class="' . $this->get_classes() . '"';
					continue;
				}
				if ( 'readonly' === $aname ) {
					$attributes_string[] = $this->readonly ? 'readonly' : '';
					continue;
				}
				$value               = $this->{$aname};
				$attributes_string[] = "{$aname}=\"$value\"";
			}
		}
		return implode( ' ', $attributes_string );
	}

	public function field() {
		$field           = '';
		$wrapper_classes = 'wpte-field wpte-floated ';
		if ( $this->wrapper_classes ) {
			$wrapper_classes .= $this->wrapper_classes;
		}
		switch ( $this->type ) {
			case 'text':
				$value = $this->value ? $this->value : $this->default_value;
				$field = '<input ' . $this->get_attributes() . '/>';
				break;
			case 'textarea':
				$value = $this->value ? $this->value : $this->default_value;
				$field = '<textarea ' . $this->get_attributes() . '>' . $this->value . '</textarea>';
				break;
			case 'number':
				$value = $this->value ? $this->value : $this->default_value;
				$field = '<input ' . $this->get_attributes() . '/>';
				break;
			case 'select':
				$value = $this->value ? $this->value : $this->default_value;
				ob_start();
				?>
				<select <?php echo $this->get_attributes(); ?>>
					<?php
					foreach ( $this->options as $value => $option_args ) :
						$option_attributes = '';
						if ( isset( $option_args['attributes'] ) && is_array( $option_args['attributes'] ) ) {
							foreach ( $option_args['attributes'] as $attr_name => $attr_value ) {
								if ( 'selected' === $attr_name ) {
									$option_attributes .= $attr_value;
								} else {
									$option_attributes .= ' ' . $attr_name . '=' . $attr_value;
								}
							}
						}
						?>
						<option <?php echo $option_attributes; ?> <?php selected( $value, $this->value ); ?> value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $option_args['label'] ); ?></option>
					<?php endforeach; ?>
				</select>
				<?php
				$field = ob_get_clean();
				break;
			case 'checkbox':
				ob_start();
				?>
				<a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo isset( $this->checked_classname ) ? $this->checked_classname : ''; ?>">
					<label for="<?php echo $this->id; ?>" class="wpte-field-label"><?php echo $this->label; ?>
						<span class="wpte-onoff-btn"></span>
					</label>
				</a>
				<input <?php echo $this->get_attributes(); ?> />
				<?php
				$field           = ob_get_clean();
				$wrapper_classes = str_replace( 'wpte-floated', 'wpte-onoff-block', $wrapper_classes );
				break;
			case 'multifields':
				if ( isset( $this->subfields ) ) {
					foreach ( $this->subfields as $sf ) {
						ob_start();
						( new self( $sf['type'], $sf ) )->field();
						$field .= ob_get_clean();
					}
				}
				break;
			case 'custom':
				$field = $this->value;
				break;
			case 'datepicker':
				ob_start();
				?>
				<input type="text" <?php echo $this->get_attributes(); ?> />
				<?php
				$field = ob_get_clean();
				break;
		}

		?>
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
			<?php
			if ( $this->label && ! in_array( $this->type, array( 'checkbox', 'custom', 'radio' ) ) ) {
				echo "<label class=\"wpte-field-label\" for=\"" , esc_attr( $this->id ) . "\">" . esc_html( $this->label ) . "</label>";
			}
			echo isset( $this->before_field ) ? $this->before_field : '';
			echo $field;
			echo isset( $this->after_field ) ? $this->after_field : '';
			if ( $this->tooltip ) {
				echo "<span class=\"wpte-tooltip\">" . wp_kses( $this->tooltip, ['a' => ['href' => [], 'target' => []]] ) . "</span>";
			}
			?>
		</div>
		<?php
	}
}

class WTE_Field_Builder {

	public $fields = array();

	public $attributes;

	public $classes;

	public $type;

	public $readonly = false;

	public $id;

	public function __construct( $fields ) {
		$this->fields = $fields;
	}

	public function render() {
		foreach ( $this->fields as $field ) {
			// $field = (object) $field;
			( new WTE_Field( $field['type'], $field ) )->field();
		}
	}
}



class WTE_Field_Builder_Admin extends WTE_Field_Builder {

	public function __construct( $fields ) {
		parent::__construct( $fields );
	}
}
