<?php
/**
 * Special Class to format Price in WP Travel Engine.
 *
 * @since 5.0
 */

namespace WPTravelEngine\Utilities;

class Price {

	public $output = '';

	private $settings = null;

	private $use_html = true;

	public function __construct( $num ) {
		$this->number = floatval( $num );
	}

	public function convert() {
		$this->converted_amount = apply_filters( 'wte_before_formatting_price_figure', $this->number );
		return $this;
	}

	public function use_html( $use = true ) {
		$this->use_html = $use;
		return $this;
	}

	private function replacer( $num = false ) {
		$num = ! $num ? $this->number : $num;

		$replacer = array(
			'%CURRENCY_CODE%'   => $this->use_html ? '<span class="wpte-currency-code">' . $this->currency_code . '</span>' : $this->currency_code,
			'%CURRENCY_SYMBOL%' => $this->use_html ? '<span class="wpte-currency-code">' . $this->currency_symbol . '</span>' : $this->currency_symbol,
			'%AMOUNT%'          => $this->use_html ? '<span class="wpte-price">' . $num . '</span>' : $num,
			'%FORMATED_AMOUNT%' => $this->use_html ? '<span class="wpte-price">' . $this->get_formated_number( $num ) . '</span>' : $this->get_formated_number( $num ),
		);

		return str_replace( array_keys( $replacer ), array_values( $replacer ), $this->format );
	}

	public function get_converted_amount() {
		return ! ( $this->converted_amount ) ? $this->convert()->converted_amount : $this->converted_amount;
	}

	public function get_formated_number( $num = false ) {
		$num = ! $num ? $this->number : $num;
		return \wte_number_format( (float) $num );
	}

	public function format( $converted = false ) {
		if ( $converted ) {
			$currency_code = apply_filters( 'wp_travel_engine_currency_code', $this->settings( 'currency_code', 'USD' ), ! $converted );
			$this->set_currency( $currency_code );
		}
		if ( ! $this->currency_code ) {
			$this->set_currency();
		}

		if ( ! $this->format ) {
			$this->set_format();
		}

		$this->output = $this->replacer( $converted ? $this->get_converted_amount() : $this->number );

		return $this;
	}

	public function set_format( $format = null ) {
		if ( $format ) {
			$this->format = $format;
		} else {
			$format = $this->settings( 'amount_display_format', '' );
			if ( empty( $format ) ) {
				$format = '%CURRENCY_SYMBOL% %FORMATED_AMOUNT%';
			}
		}

		$this->format = $format;
		return $this;
	}

	private function settings( $key = null, $default = false ) {
		if ( ! $this->settings ) {
			$this->settings = (object) get_option( 'wp_travel_engine_settings', array() );
		}
		if ( ! ! $key && isset( $this->settings->{$key} ) ) {
			return $this->settings->{$key};
		}
		return $default;
	}

	public function __get( $property ) {
		switch ( $property ) {
			case 'formated_amount':
				return (string) $this->format()->output;
			case 'converted_formated_amount':
				return $this->format( true )->output;
			default:
				break;
		}
	}

	public function set_currency( $code = null ) {
		if ( ! ! $code ) {
			$currency_code = $code;
		} else {
			$currency_code = $this->settings( 'currency_code', 'USD' );
		}
		$this->currency_code   = $currency_code;
		$this->currency_symbol = \wp_travel_engine_get_currency_symbol( $currency_code );
		return $this;
	}

	public function __toString() {
		return (string) $this->formated_amount;
	}
}
