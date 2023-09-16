<?php
/**
 * Price and currency helpers
 *
 * @package WP_Tarvel_Engine
 */

require_once 'class-wte-price.php';
use WPTravelEngine\Utilities\Price;

/**
 * Used For Calculation purpose. for display purpose use wp_travel_engine_get_formated_price_with_currency.
 *
 * @param int $price Amount to be formatted.
 * @param bool $format If true should be formatted according to the WP Travel Number fomatting Setting @since WP Travel v3.0.4
 * @param int $number_of_decimals Number after decimal .00.
 */

/**
 * Currency code in db
 *
 * @since 3.0.2
 *
 * @return string Currency code stored in db.
 */

/**
 * Currency code in db.
 *
 * @return string Return currency code in db.
 */
function wte_currency_code_in_db() {
	// If the currency stored in the database is not in the
	// currency converter list, append it as well.
	$wte_settings = get_option( 'wp_travel_engine_settings' );
	if ( ! isset( $wte_settings['currency_code'] ) ) {
		$wte_settings['currency_code'] = 'USD';
	}
	$code_in_db = $wte_settings['currency_code'];

	return $code_in_db;
}

/**
 * Formats a number.
 * Use only to print number not for saving data to database or sending payment amount to payment gateway use number format instead.
 *
 * @since 4.3.0
 */
function wte_number_format( $num, $decimals = '', $decimal_separator = '', $thousands_separator = '' ) {
	if ( is_string( $num ) ) {
		$num = floatval( $num );
	}
	$settings = get_option( 'wp_travel_engine_settings', array() );
	if ( $decimals === '' ) {
		$decimals = wte_array_get( $settings, 'decimal_digits', '0' );
	}
	if ( empty( $decimal_separator ) ) {
		$decimal_separator = wte_array_get( $settings, 'decimal_separator', '.' );
	}
	if ( empty( $thousands_separator ) ) {
		$thousands_separator = wte_array_get( $settings, 'thousands_separator', ',' );
	}

	return number_format( $num, $decimals, $decimal_separator, $thousands_separator );
}

/**
 * Print the price.
 *
 * @since 5.3.1
 */
function wte_the_formated_price( ...$args ) {
	echo wp_kses(
		wte_get_formated_price( ...$args ),
		array(
			'span' => array(
				'class' => array(),
			),
		)
	);
}

/**
 * Decorate price with Currency code and symbol.
 * This function will convert amount if converter plugin is being used and $currency_code is not provided or $use_default_currency_code is true.
 * Use only to print number not for saving data to database or sending payment amount to payment gateway use number_fromat instead.
 *
 * @since 4.3.0
 *
 * @param int    $num Amount figure.
 * @param string $currency_code Currency Code.
 * @param string $format Format String.
 * @param bool   $use_currency_symbol Use Currency Symbol.
 * @return string Decorated Price with currency.
 */
function wte_get_formated_price( $num, $currency_code = '', $format = '', $use_currency_symbol = false, $use_html = false, $use_default_currency_code = false ) {

	$convert = ! \wp_travel_engine_is_checkout_page() && ! $use_default_currency_code;

	$price_object = new Price( $num );

	if ( ! empty( $currency_code ) ) {
		$price_object->set_currency( $currency_code );
		$convert = false;
	}

	return $price_object->set_format( $format )->use_html( $use_html )->format( $convert )->output;
}

/**
 * Return html formated Price.
 *
 * @param int $num Number.
 * @return void
 */
function wte_get_formated_price_html( $num, $trip_id = null, $use_default_currency_code = false ) {
	return wte_get_formated_price( $num, '', '', false, true, $use_default_currency_code );
}

/**
 * Format Price Value converts price if necesary.
 *
 * @param float $price Price to format.
 * @since 4.3.0
 * @return void
 */
function wte_price_value_format( $price, $convert = true, $deprecated = array(
	'trip_id'                   => ! 1,
	'use_default_currency_code' => ! 1,
) ) {
	$price = $price;
	// TODO : Move to filter.
	if ( class_exists( 'Wte_Trip_Currency_Converter_Init' ) && $deprecated['trip_id'] ) {
		$price = \wte_functions()->convert_trip_price( get_post( $deprecated['trip_id'] ), $price );
	}

	if ( $convert ) {
		$price = wte_number_format( apply_filters( 'wte_price_value', (float) $price ) );
	}

	return wte_number_format( $price );
}

/**
 * Undocumented function
 *
 * @param [type]  $price
 * @param boolean $format
 * @param integer $number_of_decimals
 * @deprecated 4.3.0
 * @return void
 */
function wp_travel_engine_get_formated_price( $price, $format = true, $number_of_decimals = 2 ) {
	_deprecated_function( __FUNCTION__, '4.3.0', 'wte_number_format' );
	return wte_number_format( $price, $number_of_decimals, '.', '' );
}

/**
 * Undocumented function
 *
 * @param [type] $cost
 * @return void
 */
function wp_travel_engine_get_formated_price_separator( $cost, $trip_id = false, $use_default_currency_code = false ) {

	_deprecated_function( __FUNCTION__, '4.3.0', 'wte_price_value_format' );

	return wte_price_value_format(
		$cost,
		false,
		array(
			'trip_id'                   => $trip_id,
			'use_default_currency_code' => $use_default_currency_code,
		)
	);
}

/**
 * Get formatted price with currency for output.
 *
 * @deprecated 4.3.0
 */
function wp_travel_engine_get_formated_price_with_currency( $price, $trip_id = null, $use_default_currency_code = false ) {
	_deprecated_function( __FUNCTION__, '4.3.0', 'wte_get_formated_price' );

	return wte_get_formated_price( (float) $price, '', '%CURRENCY_SYMBOL%%FORMATED_AMOUNT% %CURRENCY_CODE%' );
}

/**
 * Get formatted price with currency for output.
 *
 * @deprecated 4.3.0
 */
function wp_travel_engine_get_formated_price_with_currency_symbol( $price, $trip_id = null, $use_default_currency_code = false ) {

	_deprecated_function( __FUNCTION__, '4.3.0', 'wte_get_formated_price' );

	return wte_get_formated_price( (float) $price, '', '%CURRENCY_SYMBOL%%FORMATED_AMOUNT%' );
}

/**
 * Get formatted price with currency for output with currency code.
 *
 * @deprecated 4.3.0
 */
function wp_travel_engine_get_formated_price_with_currency_code( $price, $trip_id = null, $use_default_currency_code = false ) {
	_deprecated_function( __FUNCTION__, '4.3.0', 'wte_get_formated_price' );

	return wte_get_formated_price( (float) $price, '', '' );
}

/**
 * Get formatted price with currency for output.
 *
 * @deprecated 4.3.0
 */
function wp_travel_engine_get_formated_price_with_currency_code_symbol( $price, $trip_id = null, $use_default_currency_code = false ) {
	_deprecated_function( __FUNCTION__, '4.3.0', 'wte_get_formated_price' );

	$format = '';

	$settings = get_option( 'wp_travel_engine_settings' );
	$option   = isset( $settings['currency_option'] ) && $settings['currency_option'] != '' ? esc_attr( $settings['currency_option'] ) : 'symbol';

	$format .= '<span class="wpte-currency-code">';
	$format .= 'code' === $option ? '%CURRENCY_CODE%' : '%CURRENCY_SYMBOL%';
	$format .= '</span><span class="wpte-price">';
	$format .= '%FORMATED_AMOUNT%';
	$format .= '</span>';
	return wte_get_formated_price( (float) $price, '', '', false, true );
}

/**
 * Get formatted price with currency for output.
 *
 * @deprecated 4.3.0
 */
function wpte_get_formated_price_with_currency_code_symbol( $price, $trip_id = null, $use_default_currency_code = false ) {
	_deprecated_function( __FUNCTION__, '4.3.0', 'wte_get_formated_price' );

	$format = '';

	$settings = get_option( 'wp_travel_engine_settings' );
	$option   = isset( $settings['currency_option'] ) && $settings['currency_option'] != '' ? esc_attr( $settings['currency_option'] ) : 'symbol';

	$format .= '<span class="wpte-currency-code">';
	$format .= 'code' === $option ? '%CURRENCY_CODE%' : '%CURRENCY_SYMBOL%';
	$format .= '</span><span class="wpte-price">';
	$format .= '%FORMATED_AMOUNT%';
	$format .= '</span>';
	return wte_get_formated_price( (float) $price, '', '', false, true );
}

/**
 * Get price by key.
 *
 * @param boolean $pricing_key
 * @return void
 */
function wp_travel_engine_get_price_by_pricing_key( $trip_id, $pricing_key = false ) {

	$price = 0;

	// If no trip ID supplied.
	if ( ! $trip_id ) {
		return $price;
	}

	if ( ! $pricing_key ) :

		return wp_travel_engine_get_actual_trip_price( $trip_id );

	endif;

	return $price;

}

/**
 * Is partially payable for trip id.
 *
 * @param [type] $trip_id
 * @return void
 */
function wp_travel_engine_is_trip_partially_payable( $trip_id ) {

	if ( ! $trip_id ) {
		return false;
	}

	$wte_options                  = get_option( 'wp_travel_engine_settings', true );
	$wte_trip_metas               = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
	$trip_partial_payment_enabled = isset( $wte_trip_metas['partial_payment_enable'] ) && in_array( $wte_trip_metas['partial_payment_enable'], array( 'yes', '1' ) );
	$global_partial_pay_enable    = isset( $wte_options['partial_payment_enable'] ) && in_array( $wte_options['partial_payment_enable'], array( 'yes', '1' ) );

	return class_exists( 'Wte_Partial_Payment_Admin' ) && $global_partial_pay_enable && $trip_partial_payment_enabled;

}

/**
 * Get partial payment data for trip.
 *
 * @return void
 */
function wp_travel_engine_get_trip_partial_payment_data( $trip_id ) {

	$partial_payment    = array();
	$trip_price_partial = 0;
	$wte_options        = get_option( 'wp_travel_engine_settings', true );
	$wte_trip_metas     = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );

	if ( ! $trip_id ) {
		return $partial_payment;
	}

	if ( wp_travel_engine_is_trip_partially_payable( $trip_id ) ) :

		$partial_type = $wte_options['partial_payment_option'];

		if ( 'amount' === $partial_type ) :

			$trip_price_partial = isset( $wte_options['partial_payment_amount'] ) && ! empty( $wte_options['partial_payment_amount'] ) ? $wte_options['partial_payment_amount'] : 0;

			$trip_price_partial = isset( $wte_trip_metas['partial_payment_amount'] ) && ! empty( $wte_trip_metas['partial_payment_amount'] ) ? $wte_trip_metas['partial_payment_amount'] : $trip_price_partial;

			$partial_payment = array(
				'type'  => 'amount',
				'value' => $trip_price_partial,
			);

		elseif ( 'percent' === $partial_type ) :

			$trip_partial_percentage = isset( $wte_options['partial_payment_percent'] ) && ! empty( $wte_options['partial_payment_percent'] ) ? $wte_options['partial_payment_percent'] : 0;

			$trip_partial_percentage = isset( $wte_trip_metas['partial_payment_percent'] ) && ! empty( $wte_trip_metas['partial_payment_percent'] ) ? $wte_trip_metas['partial_payment_percent'] : $trip_partial_percentage;

			$partial_payment = array(
				'type'  => 'percentage',
				'value' => (float) $trip_partial_percentage,
			);

		endif;

	endif;

	return $partial_payment;

}

/**
 * Get tax data for trip.
 *
 * @return void
 */
function wp_travel_engine_get_tax_percentage() {

	$wte_options    = get_option( 'wp_travel_engine_settings', true );
	$tax_percentage = isset($wte_options['tax_percentage']) ? $wte_options['tax_percentage']:'';
	$tax_type = isset($wte_options['tax_type_option']) ? $wte_options['tax_type_option']:'';
	if(isset($wte_options['tax_enable']) && $wte_options['tax_enable'] == 'yes'){
		if( $tax_type == 'exclusive'){
			$tax_details = array(
				'type' => 'exclusive',
				'value' => $tax_percentage
			)
			;
		}
		else{
			$tax_details = array(
				'type' => 'inclusive',
				'value' => 0
			)
			;
		}
	}
	else{
		$tax_details = array(
			'type' => 'notenabled',
			'value' => 0
		)
		;
	}
	

	return $tax_details;

}

/**
 * Calculate tax data.
 *
 * @return void
 */
function wp_travel_engine_get_tax_detail($cart_info){
	if ( ! empty( $cart_info['discounts'] ) ) {
		$discounts = isset( $cart_info['discounts'] ) ? $cart_info['discounts'] : array();
		// if ( ! empty( $discounts ) && is_array( $discounts ) ) {	
		foreach ( $discounts as $key => $discount ) {

			if( $discount['type'] == 'percentage' ){
				$new_tcost = number_format( ( $cart_info['subtotal'] -  $cart_info['subtotal'] * ( +$discount['value'] / 100 ) ), '2', '.', '' );
			}
			else{
				$new_tcost = $cart_info['subtotal'] - $discount['value'];
			}
		}
		
		$tax_actual = number_format( ( ( $new_tcost * $cart_info['tax_amount'] ) / 100 ), '2', '.', '' );
		$new_totalcost = number_format( ( $new_tcost + $tax_actual ), '2', '.', '' );
	}
	else{
		$tax_actual = number_format( ( ( $cart_info['subtotal'] * $cart_info['tax_amount'] ) / 100 ), '2', '.', '' );
		$new_totalcost = number_format( ( $cart_info['subtotal'] + $tax_actual ), '2', '.', '' );
	}

	$tax_data = array(
		'tax_actual' => $tax_actual,
		'new_totalcost' => $new_totalcost
	);

	return $tax_data;
}
/**
 * Check if cart is partially payable.
 *
 * @return void
 */
function wp_travel_engine_is_cart_partially_payable() {

	global $wte_cart;

	$cart_items = $wte_cart->getItems();

	if ( ! empty( $cart_items ) ) :

		$cart_items = array_filter(
			$cart_items,
			function( $item ) {
				return wp_travel_engine_is_trip_partially_payable( $item['trip_id'] );
			}
		);

		return ( ! empty( $cart_items ) );

	endif;

	return false;

}

/**
 * Get person format.
 *
 * @since 3.0.0
 *
 * @return string Person format
 */
function wte_get_person_format() {

	$wte_settings = wp_travel_engine_get_settings();

	$per_person = __( '/person', 'wp-travel-engine' );

	if ( $wte_settings ) :

		// Set default per person format.
		if ( ! isset( $wte_settings['person_format'] ) || empty( $wte_settings['person_format'] ) ) {
			$wte_settings['person_format'] = __( '/person', 'wp-travel-engine' );
		}
		$per_person = $wte_settings['person_format'];

	endif;

	return apply_filters( 'wte_person_format', $per_person );
}

/**
 * Get book now text.
 *
 * @since 3.0.0
 *
 * @return String book now text.
 */
function wte_get_book_now_text() {

	$wte_settings = wp_travel_engine_get_settings();

	$per_person = wte_default_labels( 'checkout.submitButtonText' );

	if ( $wte_settings ) :

		if ( ! isset( $wte_settings['book_btn_txt'] ) || empty( $wte_settings['book_btn_txt'] ) ) {
			$wte_settings['book_btn_txt'] = $per_person;
		}
		$per_person = $wte_settings['book_btn_txt'];

	endif;

	return apply_filters( 'wte_book_now', $per_person );
}

/**
 * Get Total text.
 *
 * @since 3.0.0
 *
 * @return String Total text.
 */
function wte_get_total_text() {

	$total = __( 'Total:', 'wp-travel-engine' );

	return apply_filters( 'wte_total_text', $total );
}

/**
 * is multiple pricing enabled for the trip?
 *
 * @param [type] $trip_id
 * @return void
 */
function wp_travel_engine_is_trip_multiple_pricing_enabled( $trip_id ) {

	if ( ! $trip_id ) {
		return false;
	}

	$trip_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );

	return isset( $trip_settings['multiple_pricing_enable'] ) && '1' === $trip_settings['multiple_pricing_enable'];

}

/**
 * Undocumented function
 *
 * @param [type] $pricing_key
 * @return void
 */
function wte_get_pricing_label_by_key( $trip_id, $pricing_key ) {

	if ( ! $pricing_key || ! $trip_id ) {
		return false;
	}

	$trip_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );

	$multiple_pricing_options = isset( $trip_settings['multiple_pricing'] ) && ! empty( $trip_settings['multiple_pricing'] ) ? $trip_settings['multiple_pricing'] : array();

	if ( ! empty( $multiple_pricing_options ) && isset( $multiple_pricing_options[ $pricing_key ] ) ) :

		return isset( $multiple_pricing_options[ $pricing_key ]['label'] ) ? $multiple_pricing_options[ $pricing_key ]['label'] : $pricing_key;

	endif;

	return false;

}

function wte_multi_pricing_labels( $trip_id ) {

	$labels = array();

	$trip_settings            = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
	$multiple_pricing_options = isset( $trip_settings['multiple_pricing'] ) && ! empty( $trip_settings['multiple_pricing'] ) ? $trip_settings['multiple_pricing'] : false;

	if ( $multiple_pricing_options ) :
		foreach ( $multiple_pricing_options as $key => $pricing_option ) :

			$pricing_label  = isset( $pricing_option['label'] ) ? $pricing_option['label'] : ucfirst( $key );
			$labels[ $key ] = $pricing_label;

		endforeach;
	endif;

	return $labels;

}

/**
 * Undocumented function
 *
 * @param [type] $pricing_key
 * @return void
 */
function wte_get_pricing_label_by_key_invoices( $trip_id, $pricing_key, $pax ) {

	if ( ! $pricing_key || ! $trip_id ) {
		return false;
	}

	$pax_label = wte_get_pricing_label_by_key( $trip_id, $pricing_key );
	if ( ! $pax_label ) {
		$pax_label = ucfirst( $pricing_key );
	}

	$trip_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );

	$multiple_pricing_options = isset( $trip_settings['multiple_pricing'] ) && ! empty( $trip_settings['multiple_pricing'] ) ? $trip_settings['multiple_pricing'] : array();

	if ( ! empty( $multiple_pricing_options ) && isset( $multiple_pricing_options[ $pricing_key ] ) ) :

		$pax_label_str = sprintf( _nx( 'Number of %1$s', 'Number of %1$s(s)', $pax, 'number of travellers', 'wp-travel-engine' ), $pax_label );

		if ( 'child' === $pricing_key ) :

			$pax_label_str = sprintf( _nx( 'Number of %1$s', 'Number of Children', $pax, 'number of travellers', 'wp-travel-engine' ), $pax_label );

		endif;

		if ( 'group' === $pricing_key ) :

			$pax_label_str = __( 'Number of pax in Group', 'wp-travel-engine' );

		endif;

		if ( isset( $multiple_pricing_options[ $pricing_key ]['label'] ) ) :

			$pax_label_str = sprintf( _nx( 'Number of pax in %1$s', 'Number of pax in %1$s', $pax, 'number of travellers', 'wp-travel-engine' ), ucfirst( $multiple_pricing_options[ $pricing_key ]['label'] ) );

		endif;

		return $pax_label_str;

	endif;

	return false;

}

/**
 * Get currency code or symbol.
 *
 * @return void
 */
function wp_travel_engine_get_currency_code_or_symbol() {
	$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
	$code                      = 'USD';

	if ( isset( $wp_travel_engine_settings['currency_code'] ) && $wp_travel_engine_settings['currency_code'] != '' ) {
		$code = $wp_travel_engine_settings['currency_code'];
	}

	$symbol = wp_travel_engine_get_currency_symbol( $code );

	$currency_option = isset( $wp_travel_engine_settings['currency_option'] ) && ! empty( $wp_travel_engine_settings['currency_option'] ) ? $wp_travel_engine_settings['currency_option'] : 'symbol';

	return 'symbol' === $currency_option ? $symbol : $code;
}

/**
 *
 */
function wte_price( $num ) {
	return new Price( $num );
}

/**
 * Sanitize WTE Price.
 *
 * @since 5.3.1
 */
function wte_esc_price( $value ) {
	if ( is_array( $value ) ) {
		return '';
	}
	$allowed_html = array(
		'span'   => array(
			'class' => array(),
		),
		'del'    => array(),
		'em'     => array(),
		'strong' => array(),
		'b'      => array(),
	);
	return wp_kses( $value, apply_filters( 'wte_kses_allowed_html', $allowed_html, 'display_price' ) );
}
