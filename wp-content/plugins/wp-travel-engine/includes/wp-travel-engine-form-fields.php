<?php
/**
 * Form fields default values and functions.
 *
 * @package WP Travel Engine
 */
/**
 * Checkout default fields.
 *
 * @return void
 */
function wp_travel_engine_get_checkout_form_fields() {

	$options                           = get_option( 'wp_travel_engine_settings', true );
	$wp_travel_engine_terms_conditions = isset( $options['pages']['wp_travel_engine_terms_and_conditions'] ) ? esc_attr( $options['pages']['wp_travel_engine_terms_and_conditions'] ) : '';

	$checkout_fields = array(
		'datetime'   => array(
			'type'          => 'hidden',
			'wrapper_class' => 'wp-travel-engine-billing-details-field-wrap',
			'field_label'   => __( 'First Name', 'wp-travel-engine' ),
			'name'          => 'wp_travel_engine_booking_setting[place_order][datetime]',
			'id'            => 'wp_travel_engine_booking_setting[place_order][datetime]',
			'validations'   => array(
				'required'  => true,
				'maxlength' => '50',
				'type'      => 'alphanum',
			),
			'default'       => isset( $_SESSION['trip-date'] ) ? wte_clean( wp_unslash( $_SESSION['trip-date'] ) ) : '',
			'priority'      => 10,
		),
		'first_name' => array(
			'type'          => 'text',
			'wrapper_class' => 'wp-travel-engine-billing-details-field-wrap',
			'field_label'   => __( 'First Name', 'wp-travel-engine' ),
			'label_class'   => 'wpte-bf-label',
			'name'          => 'wp_travel_engine_booking_setting[place_order][booking][fname]',
			'id'            => 'wp_travel_engine_booking_setting[place_order][booking][fname]',
			'validations'   => array(
				'required'  => true,
				'maxlength' => '50',
				'type'      => 'alphanum',
			),
			'attributes'    => array(
				'data-msg'                      => __( 'Please enter your first name', 'wp-travel-engine' ),
				'data-parsley-required-message' => __( 'Please enter your first name', 'wp-travel-engine' ),
			),
			'default'       => '',
			'priority'      => 10,
			'default_field' => true,
		),
		'last_name'  => array(
			'type'          => 'text',
			'wrapper_class' => 'wp-travel-engine-billing-details-field-wrap',
			'field_label'   => __( 'Last Name', 'wp-travel-engine' ),
			'label_class'   => 'wpte-bf-label',
			'name'          => 'wp_travel_engine_booking_setting[place_order][booking][lname]',
			'id'            => 'wp_travel_engine_booking_setting[place_order][booking][lname]',
			'validations'   => array(
				'required'  => true,
				'maxlength' => '50',
				'type'      => 'alphanum',
			),
			'attributes'    => array(
				'data-msg'                      => __( 'Please enter your last name', 'wp-travel-engine' ),
				'data-parsley-required-message' => __( 'Please enter your last name', 'wp-travel-engine' ),
			),
			'default'       => '',
			'priority'      => 20,
			'default_field' => true,
		),
		'email'      => array(
			'type'          => 'email',
			'wrapper_class' => 'wp-travel-engine-billing-details-field-wrap',
			'field_label'   => __( 'Email', 'wp-travel-engine' ),
			'label_class'   => 'wpte-bf-label',
			'name'          => 'wp_travel_engine_booking_setting[place_order][booking][email]',
			'id'            => 'email',
			'validations'   => array(
				'required' => true,
			),
			'attributes'    => array(
				'data-msg'                      => __( 'Please enter a valid email address', 'wp-travel-engine' ),
				'data-parsley-required-message' => __( 'Please enter a valid email address', 'wp-travel-engine' ),
			),
			'default'       => '',
			'priority'      => 30,
			'default_field' => true,
		),
		'address'    => array(
			'type'          => 'text',
			'wrapper_class' => 'wp-travel-engine-billing-details-field-wrap',
			'field_label'   => __( 'Address', 'wp-travel-engine' ),
			'label_class'   => 'wpte-bf-label',
			'name'          => 'wp_travel_engine_booking_setting[place_order][booking][address]',
			'id'            => 'wp_travel_engine_booking_setting[place_order][booking][address]',
			'validations'   => array(
				'required'  => true,
				'maxlength' => '100',
				'type'      => 'alphanum',
			),
			'attributes'    => array(
				'data-msg'                      => __( 'Please enter your address details', 'wp-travel-engine' ),
				'data-parsley-required-message' => __( 'Please enter your address details', 'wp-travel-engine' ),
			),
			'default'       => '',
			'priority'      => 40,
			'default_field' => true,
		),
		'city'       => array(
			'type'          => 'text',
			'wrapper_class' => 'wp-travel-engine-billing-details-field-wrap',
			'field_label'   => __( 'City', 'wp-travel-engine' ),
			'label_class'   => 'wpte-bf-label',
			'name'          => 'wp_travel_engine_booking_setting[place_order][booking][city]',
			'id'            => 'wp_travel_engine_booking_setting[place_order][booking][city]',
			'validations'   => array(
				'required' => true,
			),
			'attributes'    => array(
				'data-msg'                      => __( 'Please enter your city name', 'wp-travel-engine' ),
				'data-parsley-required-message' => __( 'Please enter your city name', 'wp-travel-engine' ),
			),
			'default'       => '',
			'priority'      => 50,
			'default_field' => true,
		),
		'country'    => array(
			'type'          => 'country_dropdown',
			'field_label'   => __( 'Country', 'wp-travel-engine' ),
			'label_class'   => 'wpte-bf-label',
			'wrapper_class' => 'wp-travel-engine-billing-details-field-wrap',
			'name'          => 'wp_travel_engine_booking_setting[place_order][booking][country]',
			'id'            => 'country',
			'validations'   => array(
				'required' => true,
			),
			'attributes'    => array(
				'data-msg'                      => __( 'Please choose your country from the list', 'wp-travel-engine' ),
				'data-parsley-required-message' => __( 'Please choose your country from the list', 'wp-travel-engine' ),
			),
			'default'       => '',
			'priority'      => 60,
			'default_field' => true,
		),
	);

	if ( function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url() ) {

		$privacy_policy_lbl = sprintf( __( 'Check the box to confirm you\'ve read and agree to our <a href="%1$s" id="terms-and-conditions" target="_blank"> Terms and Conditions</a> and <a href="%2$s" id="privacy-policy" target="_blank">Privacy Policy</a>.', 'wp-travel-engine' ), esc_url( get_permalink( $wp_travel_engine_terms_conditions ) ), esc_url( get_privacy_policy_url() ) );

		$checkout_fields['privacy_policy_info'] = array(
			'type'              => 'checkbox',
			'options'           => array( '0' => $privacy_policy_lbl ),
			'name'              => 'wp_travel_engine_booking_setting[terms_conditions]',
			'wrapper_class'     => 'wp-travel-engine-terms',
			'id'                => 'wp_travel_engine_booking_setting[terms_conditions]',
			'default'           => '',
			'validations'       => array(
				'required' => true,
			),
			'option_attributes' => array(
				'required'                      => true,
				'data-msg'                      => __( 'Please make sure to check the privacy policy checkbox', 'wp-travel-engine' ),
				'data-parsley-required-message' => __( 'Please make sure to check the privacy policy checkbox', 'wp-travel-engine' ),
			),
			'priority'          => 70,
		);

	} elseif ( current_user_can( 'edit_theme_options' ) ) {

		$privacy_policy_lbl = sprintf( __( '%1$sPrivacy Policy page not set or not published, please check Admin Dashboard > Settings > Privacy.%2$s', 'wp-travel-engine' ), '<p style="color:red;">', '</p>' );

		$checkout_fields['privacy_policy_info'] = array(
			'type'     => 'text_info',
			// 'label'             => __( 'Privacy Policy', 'wp-travel-engine' ),
			'id'       => 'wp-travel-engine-privacy-info',
			'default'  => $privacy_policy_lbl,
			'priority' => 80,
		);

	}

	return apply_filters( 'wp_travel_engine_checkout_default_fields', $checkout_fields );

}

/**
 * Get enquiry form fields.
 *
 * @return void
 */
function wp_travel_engine_get_enquiry_form_fields() {

	global $post;

	if ( isset( $post->ID ) ) :
		$post_id = $post->ID;
	endif;

	$enquiry_fields = array(
		'enquiry_name'     => array(
			'type'          => 'text',
			'wrapper_class' => 'row-repeater name-holder',
			'field_label'   => __( 'Your name:', 'wp-travel-engine' ),
			'name'          => 'enquiry_name',
			'id'            => 'enquiry_name',
			'class'         => 'input',
			'placeholder'   => __( 'Enter Your Name *', 'wp-travel-engine' ),
			'validations'   => array(
				'required'  => true,
				'maxlength' => '50',
				'type'      => 'alphanum',
			),
			'default'       => '',
			'priority'      => 10,
			'default_field' => true,
		),
		'enquiry_email'    => array(
			'type'          => 'email',
			'wrapper_class' => 'row-repeater email-holder',
			'class'         => 'input',
			'placeholder'   => __( 'Enter Your Email *', 'wp-travel-engine' ),
			'field_label'   => __( 'Your email:', 'wp-travel-engine' ),
			'name'          => 'enquiry_email',
			'id'            => 'enquiry_email',
			'validations'   => array(
				'required' => true,
			),
			'default'       => '',
			'priority'      => 20,
			'default_field' => true,
		),
		'enquiry_country'  => array(
			'type'          => 'country_dropdown',
			'field_label'   => __( 'Country', 'wp-travel-engine' ),
			'wrapper_class' => 'row-repeater country-holder',
			'name'          => 'enquiry_country',
			'class'         => 'wc-enhanced-select',
			'id'            => 'enquiry_country',
			'validations'   => array(
				'required' => true,
			),
			'default'       => '',
			'priority'      => 30,
			'default_field' => true,
		),
		'enquiry_contact'  => array(
			'type'          => 'text',
			'wrapper_class' => 'row-repeater contact',
			'field_label'   => __( 'Contact number:', 'wp-travel-engine' ),
			'name'          => 'enquiry_contact',
			'class'         => 'input',
			'id'            => 'enquiry_contact',
			'placeholder'   => __( 'Enter Your Contact Number*', 'wp-travel-engine' ),
			'validations'   => array(
				'required'  => true,
				'maxlength' => '50',
				'type'      => 'alphanum',
			),
			'default'       => '',
			'priority'      => 40,
			'default_field' => true,
		),
		'enquiry_adult'    => array(
			'type'          => 'number',
			'wrapper_class' => 'row-repeater adult',
			'field_label'   => __( 'No. of Adults', 'wp-travel-engine' ),
			'name'          => 'enquiry_adult',
			'id'            => 'enquiry_adult',
			'class'         => 'input',
			'placeholder'   => __( 'Enter Number of Adults*', 'wp-travel-engine' ),
			'validations'   => array(
				'required'  => true,
				'maxlength' => '50',
				'type'      => 'alphanum',
				'min'       => 1,
			),
			'default'       => '',
			'priority'      => 50,
			'default_field' => true,
		),
		'enquiry_children' => array(
			'type'          => 'number',
			'wrapper_class' => 'row-repeater children',
			'field_label'   => __( 'No. of children', 'wp-travel-engine' ),
			'name'          => 'enquiry_children',
			'class'         => 'input',
			'id'            => 'enquiry_children',
			'placeholder'   => __( 'Enter Number of Children', 'wp-travel-engine' ),
			'validations'   => array(
				'type' => 'alphanum',
				'min'  => 1,
			),
			'default'       => '',
			'priority'      => 60,
		),
		'enquiry_message'  => array(
			'type'          => 'textarea',
			'wrapper_class' => 'row-repeater confirm-msg',
			'field_label'   => __( 'Your Message', 'wp-travel-engine' ),
			'name'          => 'enquiry_message',
			'class'         => 'input',
			'id'            => 'enquiry_message',
			'placeholder'   => __( 'Enter Your message*', 'wp-travel-engine' ),
			'validations'   => array(
				'required' => true,
				'type'     => 'alphanum',
				'min'      => 1,
			),
			'attributes'    => array(
				'rows' => 7,
				'cols' => 30,
			),
			'default'       => '',
			'priority'      => 70,
			'default_field' => true,
		),
	);

	if ( is_singular( 'trip' ) ) {
		$enquiry_fields['package_id']    = array(
			'type'          => 'hidden',
			'name'          => 'package_id',
			'wrapper_class' => 'row-repeater package-name-holder',
			'id'            => 'package_id',
			'default'       => esc_attr( $post_id ),
			'priority'      => 8,
		);
		$enquiry_fields['package_label'] = array(
			'type'          => 'text_info',
			'wrapper_class' => 'row-repeater package-name-holder',
			'field_label'   => __( 'Package name:', 'wp-travel-engine' ),
			'name'          => 'package_label',
			'id'            => 'package_label',
			'validations'   => array(
				'required' => true,
			),
			'remove_wrap'   => true,
			'default'       => get_the_title( $post_id ),
			'priority'      => 9,
		);
		$enquiry_fields['package_name']  = array(
			'type'     => 'hidden',
			'name'     => 'package_name',
			'id'       => 'package_name',
			'default'  => get_the_title( $post_id ),
			'priority' => 7,
		);

		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		$url                       = '';

		if ( isset( $wp_travel_engine_settings['pages']['enquiry'] ) && $wp_travel_engine_settings['pages']['enquiry'] != '' ) {

			$url = $wp_travel_engine_settings['pages']['enquiry'];
			$url = get_permalink( $url );

		}

		$enquiry_fields['redirect-url'] = array(
			'type'          => 'hidden',
			'name'          => 'redirect-url',
			'wrapper_class' => 'row-repeater package-name-holder',
			'id'            => 'redirect-url',
			'default'       => esc_url( $url ),
			'priority'      => 8,
		);

		if ( function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url() ) {

			$enquiry_fields['enquiry_confirmation'] = array(
				'type'              => 'checkbox',
				'label'             => __( 'Privacy Policy', 'wp-travel-engine' ),
				'options'           => array( 'on' => isset( $wp_travel_engine_settings['gdpr_msg'] ) ? esc_attr( $wp_travel_engine_settings['gdpr_msg'] ) . get_the_privacy_policy_link() . '.' : sprintf( __( 'By contacting us, you agree to our <a href="%1$s">Privacy Ploicy</a>', 'wp-travel-engine' ), get_privacy_policy_url() ) ),
				'name'              => 'enquiry_confirmation',
				'wrapper_class'     => 'row-form confirm-holder',
				'id'                => 'enquiry_confirmation',
				'validations'       => array(
					'required' => true,
				),
				'option_attributes' => array(
					'required' => true,
				),
				'priority'          => 80,
			);

		} elseif ( current_user_can( 'edit_theme_options' ) ) {

			$privacy_policy_lbl = sprintf( __( '%1$sPrivacy Policy page not set or not published, please check Admin Dashboard > Settings > Privacy.%2$s', 'wp-travel-engine' ), '<p style="color:red;">', '</p>' );

			$enquiry_fields['enquiry_confirmation'] = array(
				'type'     => 'text_info',
				'label'    => __( 'Privacy Policy', 'wp-travel-engine' ),
				'id'       => 'enquiry_confirmation',
				'default'  => $privacy_policy_lbl,
				'priority' => 80,
			);
		}
	}

	return apply_filters( 'wp_travel_engine_enquiry_default_fields', $enquiry_fields );

}

/**
 * Get traveller information form fields.
 *
 * @return void
 */
function wp_travel_engine_traveller_information_fields( $no_travellers = 1 ) {
	wp_enqueue_script( "jquery-ui-datepicker" );

	$wp_travel_engine_settings_options = get_option( 'wp_travel_engine_settings', true );
	$travellers_information_fields     = array();
	$travellers_information_emergency  = array();

	for ( $i = 1; $i <= $no_travellers; $i++ ) :

		$travellers_information_fields[ $i ] = array(

			'traveller_details_heading' => array(
				'type'     => 'heading',
				'class'    => 'relation-options-title',
				'tag'      => 'div',
				'title'    => __( 'Personal details for Traveller: ' . $i, 'wp-travel-engine' ),
				'priority' => 9,
			),

			'traveller_title'           => array(
				'type'          => 'select',
				'field_label'   => __( 'Title', 'wp-travel-engine' ),
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][title][' . $i . ']',
				'class'         => '',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][title][' . $i . ']',
				'validations'   => array(
					'required' => true,
				),
				'options'       => array(
					''      => __( 'Please choose...', 'wp-travel-engine' ),
					'Mr'    => __( 'Mr', 'wp-travel-engine' ),
					'Mrs'   => __( 'Mrs', 'wp-travel-engine' ),
					'Ms'    => __( 'Ms', 'wp-travel-engine' ),
					'Miss'  => __( 'Miss', 'wp-travel-engine' ),
					'Other' => __( 'Other', 'wp-travel-engine' ),
				),
				'default'       => '',
				'priority'      => 10,
			),

			'traveller_first_name'      => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'First Name', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][fname][' . $i . ']',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][fname][' . $i . ']',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 20,
			),

			'traveller_last_name'       => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'Last Name', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][lname][' . $i . ']',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][lname][' . $i . ']',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 30,
			),

			'traveller_passport_number' => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'Passport Number', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][passport]',
				'id'            => 'passport',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 40,
			),

			'traveller_email'           => array(
				'type'          => 'email',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'class'         => 'input',
				'field_label'   => __( 'Email', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][email][' . $i . ']',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][email][' . $i . ']',
				'validations'   => array(
					'required' => true,
				),
				'default'       => '',
				'priority'      => 50,
			),

			'traveller_address'         => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'Address', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][address][' . $i . ']',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][address][' . $i . ']',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 60,
			),

			'traveller_city'            => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'City', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][city][' . $i . ']',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][city][' . $i . ']',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 70,
			),

			'traveller_country'         => array(
				'type'          => 'country_dropdown',
				'field_label'   => __( 'Country', 'wp-travel-engine' ),
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][country][' . $i . ']',
				'class'         => 'wc-enhanced-select',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][country][' . $i . ']',
				'validations'   => array(
					'required' => true,
				),
				'default'       => '',
				'priority'      => 80,
			),

			'traveller_postcode'        => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'Post-code', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][postcode][' . $i . ']',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][postcode][' . $i . ']',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 90,
			),

			'traveller_phone'           => array(
				'type'          => 'tel',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'Phone', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][phone][' . $i . ']',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][phone][' . $i . ']',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 100,
			),

			'traveller_dob'             => array(
				'type'          => 'datepicker',
				'class'         => 'wp-travel-engine-datetime hasDatepicker',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'Date of Birth', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][dob][' . $i . ']',
				'id'            => 'wp_travel_engine_placeorder_setting-place_order-travelers-dob-' . $i,
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 110,
			),

		);

		if ( ! isset( $wp_travel_engine_settings_options['emergency'] ) ) :

			$travellers_information_emergency = array(

				'traveller_emergency_heading'    => array(
					'type'     => 'heading',
					'class'    => 'relation-options-title',
					'tag'      => 'div',
					'title'    => __( 'Emergency contact details for Traveller: ' . $i, 'wp-travel-engine' ),
					'priority' => 120,
				),

				'traveller_emergency_title'      => array(
					'type'          => 'select',
					'field_label'   => __( 'Title', 'wp-travel-engine' ),
					'wrapper_class' => 'wp-travel-engine-relation-details',
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][title][' . $i . ']',
					'class'         => '',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][title][' . $i . ']',
					'validations'   => array(
						'required' => true,
					),
					'options'       => array(
						''      => __( 'Please choose...', 'wp-travel-engine' ),
						'Mr'    => __( 'Mr', 'wp-travel-engine' ),
						'Mrs'   => __( 'Mrs', 'wp-travel-engine' ),
						'Ms'    => __( 'Ms', 'wp-travel-engine' ),
						'Miss'  => __( 'Miss', 'wp-travel-engine' ),
						'Other' => __( 'Other', 'wp-travel-engine' ),
					),
					'default'       => '',
					'priority'      => 130,
				),

				'traveller_emergency_first_name' => array(
					'type'          => 'text',
					'wrapper_class' => 'wp-travel-engine-relation-details',
					'field_label'   => __( 'First Name', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][fname][' . $i . ']',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][fname][' . $i . ']',
					'validations'   => array(
						'required'  => true,
						'maxlength' => '50',
						'type'      => 'alphanum',
					),
					'default'       => '',
					'priority'      => 140,
				),

				'traveller_emergency_last_name'  => array(
					'type'          => 'text',
					'wrapper_class' => 'wp-travel-engine-relation-details',
					'field_label'   => __( 'Last Name', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][lname][' . $i . ']',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][lname][' . $i . ']',
					'validations'   => array(
						'required'  => true,
						'maxlength' => '50',
						'type'      => 'alphanum',
					),
					'default'       => '',
					'priority'      => 150,
				),

				'traveller_emergency_phone'      => array(
					'type'          => 'tel',
					'wrapper_class' => 'wp-travel-engine-relation-details',
					'field_label'   => __( 'Phone', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][phone][' . $i . ']',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][phone][' . $i . ']',
					'validations'   => array(
						'required'  => true,
						'maxlength' => '50',
						'type'      => 'alphanum',
					),
					'default'       => '',
					'priority'      => 160,
				),

				'traveller_emergency_relation'   => array(
					'type'          => 'text',
					'wrapper_class' => 'wp-travel-engine-relation-details',
					'field_label'   => __( 'Relationship', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][relation][' . $i . ']',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][relation][' . $i . ']',
					'validations'   => array(
						'required'  => true,
						'maxlength' => '50',
						'type'      => 'alphanum',
					),
					'default'       => '',
					'priority'      => 170,
				),
			);

		endif;

		if ( ! empty( $travellers_information_emergency ) ) :

			$travellers_information_fields[ $i ] = $travellers_information_fields[ $i ] + $travellers_information_emergency;

		endif;

	endfor;

	return apply_filters( 'wp_travel_engine_enquiry_default_fields', $travellers_information_fields );

}
