<?php
/**
 * Default form fields for forms used in WP Travel Engine.
 *
 * @package WP_Travel_Engine
 */
/**
 * WP Travel Engine default form fields.
 *
 * @since 3.0.8
 */
class WTE_Default_Form_Fields {

	/**
	 * Enquiry form default fields.
	 *
	 * @return void
	 */
	public static function enquiry() {
		$fields = array(
			'enquiry_name'     => array(
				'type'           => 'text',
				'wrapper_class'  => 'row-repeater name-holder',
				'field_label'    => __( 'Your name:', 'wp-travel-engine' ),
				'name'           => 'enquiry_name',
				'id'             => 'enquiry_name',
				'class'          => 'input',
				'placeholder'    => __( 'Enter Your Name *', 'wp-travel-engine' ),
				'validations'    => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'        => '',
				'priority'       => 10,
				'default_field'  => true,
				'required_field' => true,
			),
			'enquiry_email'    => array(
				'type'           => 'email',
				'wrapper_class'  => 'row-repeater email-holder',
				'class'          => 'input',
				'placeholder'    => __( 'Enter Your Email *', 'wp-travel-engine' ),
				'field_label'    => __( 'Your email:', 'wp-travel-engine' ),
				'name'           => 'enquiry_email',
				'id'             => 'enquiry_email',
				'validations'    => array(
					'required' => true,
				),
				'default'        => '',
				'priority'       => 20,
				'default_field'  => true,
				'required_field' => true,
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
				'default'       => '',
				'priority'      => 60,
				'default_field' => true,
			),
			'enquiry_subject'  => array(
				'type'          => 'text',
				'wrapper_class' => 'row-repeater subject-holder',
				'field_label'   => __( 'Enquiry Subject:', 'wp-travel-engine' ),
				'name'          => 'enquiry_subject',
				'id'            => 'enquiry_subject',
				'class'         => 'input',
				'placeholder'   => __( 'Enquiry Subject', 'wp-travel-engine' ),
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 10,
				'default_field' => true,
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
		// return the fields array
		return $fields;
	}

	/**
	 * Booking form default fields for display and modifications.
	 *
	 * @return void
	 */
	public static function booking() {

		// Booking form defaults
		$booking_fname = '';
		$booking_lname = '';
		$booking_email = '';

		$booking_address = '';
		$booking_city    = '';
		$booking_country = '';

		// Get user values.
		if ( is_user_logged_in() ) {

			$user = wp_get_current_user();

			if ( in_array( 'wp-travel-engine-customer', (array) $user->roles ) ) {

				$booking_fname = isset( $user->first_name ) ? $user->first_name : '';
				$booking_lname = isset( $user->last_name ) ? $user->last_name : '';
				$booking_email = isset( $user->user_email ) ? $user->user_email : '';

				$user_data = get_user_meta( $user->ID, 'wp_travel_engine_customer_billing_details', true );

				$booking_address = isset( $user_data['billing_address'] ) ? $user_data['billing_address'] : '';
				$booking_city    = isset( $user_data['billing_city'] ) ? $user_data['billing_city'] : '';

				$booking_country = isset( $user_data['billing_country'] ) ? $user_data['billing_country'] : '';
			}
		}

		$fields = array(
			'booking_first_name' => array(
				'type'           => 'text',
				'wrapper_class'  => 'wp-travel-engine-billing-details-field-wrap',
				'field_label'    => __( 'First Name', 'wp-travel-engine' ),
				'label_class'    => 'wpte-bf-label',
				'name'           => 'wp_travel_engine_booking_setting[place_order][booking][fname]',
				'id'             => 'wp_travel_engine_booking_setting[place_order][booking][fname]',
				'validations'    => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'attributes'     => array(
					'data-msg'                      => __( 'Please enter your first name', 'wp-travel-engine' ),
					'data-parsley-required-message' => __( 'Please enter your first name', 'wp-travel-engine' ),
				),
				'default'        => $booking_fname,
				'priority'       => 10,
				'default_field'  => true,
				'required_field' => true,
			),
			'booking_last_name'  => array(
				'type'           => 'text',
				'wrapper_class'  => 'wp-travel-engine-billing-details-field-wrap',
				'field_label'    => __( 'Last Name', 'wp-travel-engine' ),
				'label_class'    => 'wpte-bf-label',
				'name'           => 'wp_travel_engine_booking_setting[place_order][booking][lname]',
				'id'             => 'wp_travel_engine_booking_setting[place_order][booking][lname]',
				'validations'    => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'attributes'     => array(
					'data-msg'                      => __( 'Please enter your last name', 'wp-travel-engine' ),
					'data-parsley-required-message' => __( 'Please enter your last name', 'wp-travel-engine' ),
				),
				'default'        => $booking_lname,
				'priority'       => 20,
				'default_field'  => true,
				'required_field' => true,
			),
			'booking_email'      => array(
				'type'           => 'email',
				'wrapper_class'  => 'wp-travel-engine-billing-details-field-wrap',
				'field_label'    => __( 'Email', 'wp-travel-engine' ),
				'label_class'    => 'wpte-bf-label',
				'name'           => 'wp_travel_engine_booking_setting[place_order][booking][email]',
				'id'             => 'wp_travel_engine_booking_setting[place_order][booking][email]',
				'validations'    => array(
					'required' => true,
				),
				'attributes'     => array(
					'data-msg'                      => __( 'Please enter a valid email address', 'wp-travel-engine' ),
					'data-parsley-required-message' => __( 'Please enter a valid email address', 'wp-travel-engine' ),
				),
				'default'        => $booking_email,
				'priority'       => 30,
				'default_field'  => true,
				'required_field' => true,
			),
			'booking_address'    => array(
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
				'default'       => $booking_address,
				'priority'      => 40,
				'default_field' => true,
			),
			'booking_city'       => array(
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
				'default'       => $booking_city,
				'priority'      => 50,
				'default_field' => true,
			),
			'booking_country'    => array(
				'type'          => 'country_dropdown',
				'field_label'   => __( 'Country', 'wp-travel-engine' ),
				'label_class'   => 'wpte-bf-label',
				'wrapper_class' => 'wp-travel-engine-billing-details-field-wrap',
				'name'          => 'wp_travel_engine_booking_setting[place_order][booking][country]',
				'id'            => 'wp_travel_engine_booking_setting[place_order][booking][country]',
				'validations'   => array(
					'required' => true,
				),
				'attributes'    => array(
					'data-msg'                      => __( 'Please choose your country from the list', 'wp-travel-engine' ),
					'data-parsley-required-message' => __( 'Please choose your country from the list', 'wp-travel-engine' ),
				),
				'default'       => $booking_country,
				'priority'      => 60,
				'default_field' => true,
			),
		);

		return $fields;

	}

	/**
	 * Traveller Information form fields
	 *
	 * @return void
	 */
	public static function traveller_information() {

		wp_enqueue_script( "jquery-ui-datepicker" );

		$fields = array(
			'traveller_title'           => array(
				'type'          => 'select',
				'field_label'   => __( 'Title', 'wp-travel-engine' ),
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][title]',
				'class'         => '',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][title]',
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
				'default_field' => true,
			),

			'traveller_first_name'      => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'First Name', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][fname]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][fname]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 20,
				'default_field' => true,
			),

			'traveller_last_name'       => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'Last Name', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][lname]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][lname]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 30,
				'default_field' => true,
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
				'default_field' => true,
			),

			'traveller_email'           => array(
				'type'          => 'email',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'class'         => 'input',
				'field_label'   => __( 'Email', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][email]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][email]',
				'validations'   => array(
					'required' => true,
				),
				'default'       => '',
				'priority'      => 50,
				'default_field' => true,
			),

			'traveller_address'         => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'Address', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][address]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][address]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 60,
				'default_field' => true,
			),

			'traveller_city'            => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'City', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][city]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][city]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 70,
				'default_field' => true,
			),

			'traveller_country'         => array(
				'type'          => 'country_dropdown',
				'field_label'   => __( 'Country', 'wp-travel-engine' ),
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][country]',
				'class'         => 'wc-enhanced-select',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][country]',
				'validations'   => array(
					'required' => true,
				),
				'default'       => '',
				'priority'      => 80,
				'default_field' => true,
			),

			'traveller_postcode'        => array(
				'type'          => 'number',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'Post-code', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][postcode]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][postcode]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 90,
				'default_field' => true,
			),

			'traveller_phone'           => array(
				'type'          => 'tel',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'Phone', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][phone]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][phone]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 100,
				'default_field' => true,
			),

			'traveller_dob'             => array(
				'type'          => 'datepicker',
				'class'         => 'wp-travel-engine-datetime hasDatepicker',
				'wrapper_class' => 'wp-travel-engine-personal-details',
				'field_label'   => __( 'Date of Birth', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][dob]',
				'id'            => 'wp_travel_engine_placeorder_setting-place_order-travelers-dob',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 110,
				'default_field' => true,
			),

		);

		return $fields;
	}

	/**
	 * Emergency Contact Field.
	 *
	 * @return void
	 */
	public static function emergency_contact() {
		$fields = array(
			'traveller_emergency_title'      => array(
				'type'          => 'select',
				'field_label'   => __( 'Title', 'wp-travel-engine' ),
				'wrapper_class' => 'wp-travel-engine-relation-details',
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][title]',
				'class'         => '',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][title]',
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
				'default_field' => true,
			),

			'traveller_emergency_first_name' => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-relation-details',
				'field_label'   => __( 'First Name', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][fname]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][fname]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 140,
				'default_field' => true,
			),

			'traveller_emergency_last_name'  => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-relation-details',
				'field_label'   => __( 'Last Name', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][lname]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][lname]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 150,
				'default_field' => true,
			),

			'traveller_emergency_phone'      => array(
				'type'          => 'tel',
				'wrapper_class' => 'wp-travel-engine-relation-details',
				'field_label'   => __( 'Phone', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][phone]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][phone]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 160,
				'default_field' => true,
			),

			'traveller_emergency_relation'   => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-relation-details',
				'field_label'   => __( 'Relationship', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][relation]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][relation]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'default'       => '',
				'priority'      => 170,
				'default_field' => true,
			),
		);

		return $fields;
	}

}
