<?php
add_filter( 'wp_privacy_personal_data_erasers', 'register_wp_travel_engine_plugin_eraser', 10 );

function wp_travel_engine_plugin_eraser( $email_address, $page = 1 ) {
	$args = array(
		'post_type' => 'enquiry',
		'order'     => 'ASC',
	);
	global $post;
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$wp_travel_engine_setting = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
			if ( isset( $wp_travel_engine_setting['enquiry']['email'] ) && $wp_travel_engine_setting['enquiry']['email'] == $email_address ) {
				wp_delete_post( $post->ID, true );
			}
		}
	}

	$args = array(
		'post_type' => 'trip',
		'order'     => 'ASC',
	);
	global $post;
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$wp_travel_engine_setting = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
			if ( isset( $wp_travel_engine_booking_setting['place_order']['booking']['email'] ) && $wp_travel_engine_booking_setting['place_order']['booking']['email'] == $email_address ) {
				wp_delete_post( $post->ID, true );
			}
		}
	}

	return array(
		'items_removed'  => true,
		'items_retained' => false,
		'messages'       => array( sprintf( __( 'Customer for %s has been anonymized.', 'wp-travel-engine' ), $email_address ) ),
		'done'           => true,
	);
}

function wpte_register_privacy_policy_template() {

	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}

	$content = wp_kses_post(
		apply_filters(
			'wpte_privacy_policy_content',
			__(
				'
	We collect information about you during the checkout process on our store. This information may include, but is not limited to, your name, billing address, shipping address, email address, phone number, credit card/payment details and any other details that might be requested from you for the purpose of processing your orders.
	Handling this data also allows us to:
	- Send you important account/order/service information.
	- Respond to your queries, refund requests, or complaints.
	- Process payments and to prevent fraudulent transactions. We do this on the basis of our legitimate business interests.
	- Set up and administer your account, provide technical and/or customer support, and to verify your identity.
	',
				'wp-travel-engine'
			)
		)
	);

	$content .= "\n\n";

	$additional_collection = array(
		__( 'Location and traffic data (including IP address and browser type) if you place an order, or if we need to estimate taxes and shipping costs based on your location.', 'wp-travel-engine' ),
		__( 'Product pages visited and content viewed while your session is active.', 'wp-travel-engine' ),
		__( 'Your comments and product reviews if you choose to leave them on our website.', 'wp-travel-engine' ),
		__( 'Account email/password to allow you to access your account, if you have one.', 'wp-travel-engine' ),
		__( 'If you choose to create an account with us, your name, address, and email address, which will be used to populate the checkout for future orders.', 'wp-travel-engine' ),
	);

	$additional_collection = apply_filters( 'wpte_privacy_policy_additional_collection', $additional_collection );

	$content .= __( 'Additionally we may also collect the following information:', 'wp-travel-engine' ) . "\n";
	if ( ! empty( $additional_collection ) ) {
		foreach ( $additional_collection as $item ) {
			$content .= '- ' . $item . "\n";
		}
	}

	wp_add_privacy_policy_content( 'WP Travel Engine', wpautop( $content ) );
}
add_action( 'admin_init', 'wpte_register_privacy_policy_template' );


function register_wp_travel_engine_plugin_eraser( $erasers = array() ) {
	$erasers[] = array(
		'eraser_friendly_name' => __( 'WP Travel Engine', 'wp-travel-engine' ),
		'callback'             => 'wp_travel_engine_plugin_eraser',
	);
	return $erasers;
}

function wp_travel_engine_plugin_exporter( $email_address, $page = 1 ) {
	$page = (int) $page;

	$export_items = array();

	$args = array(
		'post_type' => 'enquiry',
		'order'     => 'ASC',
	);
	global $post;
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			$wp_travel_engine_setting = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

			// Plugins can add as many items in the item data array as they want
			$data = array(
				array(
					'name'  => __( 'Name', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['enquiry']['name'] ) ? esc_attr( $wp_travel_engine_setting['enquiry']['name'] ) : '',
				),
				array(
					'name'  => __( 'Email', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['enquiry']['email'] ) ? esc_attr( $wp_travel_engine_setting['enquiry']['email'] ) : '',
				),
				array(
					'name'  => __( 'Country', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['enquiry']['country'] ) ? esc_attr( $wp_travel_engine_setting['enquiry']['country'] ) : '',
				),
				array(
					'name'  => __( 'Contact Number', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['enquiry']['contact'] ) ? esc_attr( $wp_travel_engine_setting['enquiry']['contact'] ) : '',
				),
				array(
					'name'  => __( 'Number of Adults', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['enquiry']['adults'] ) ? esc_attr( $wp_travel_engine_setting['enquiry']['adults'] ) : '',
				),
				array(
					'name'  => __( 'Number of Children', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['enquiry']['children'] ) ? esc_attr( $wp_travel_engine_setting['enquiry']['children'] ) : '',
				),
				array(
					'name'  => __( 'Message', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['enquiry']['message'] ) ? esc_attr( $wp_travel_engine_setting['enquiry']['message'] ) : '',
				),
			);

			$export_items[] = array(
				'group_id'    => 'wpte-enquiry-record',
				'group_label' => __( 'Enquiry Record', 'wp-travel-engine' ),
				'item_id'     => $post->ID,
				'data'        => $data,
			);
		}
	}

	$args = array(
		'post_type' => 'customer',
		'order'     => 'ASC',
	);
	global $post;
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			$wp_travel_engine_setting = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

			// Plugins can add as many items in the item data array as they want
			$data = array(
				array(
					'name'  => __( 'First Name', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['place_order']['booking']['fname'] ) ? esc_attr( $wp_travel_engine_setting['place_order']['booking']['fname'] ) : '',
				),
				array(
					'name'  => __( 'Last Name', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['place_order']['booking']['lname'] ) ? esc_attr( $wp_travel_engine_setting['place_order']['booking']['lname'] ) : '',
				),
				array(
					'name'  => __( 'Email', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['place_order']['booking']['email'] ) ? esc_attr( $wp_travel_engine_setting['place_order']['booking']['email'] ) : '',
				),
				array(
					'name'  => __( 'Address', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['place_order']['booking']['address'] ) ? esc_attr( $wp_travel_engine_setting['place_order']['booking']['address'] ) : '',
				),
				array(
					'name'  => __( 'City', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['place_order']['booking']['city'] ) ? esc_attr( $wp_travel_engine_setting['place_order']['booking']['city'] ) : '',
				),
				array(
					'name'  => __( 'Country', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['place_order']['booking']['country'] ) ? esc_attr( $wp_travel_engine_setting['place_order']['booking']['country'] ) : '',
				),
				array(
					'name'  => __( 'Post Code', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['place_order']['booking']['postcode'] ) ? esc_attr( $wp_travel_engine_setting['place_order']['booking']['postcode'] ) : '',
				),
			);

			$export_items[] = array(
				'group_id'    => 'wpte-enquiry-record',
				'group_label' => __( 'Enquiry Record', 'wp-travel-engine' ),
				'item_id'     => $post->ID,
				'data'        => $data,
			);
		}
	}

	$args = array(
		'post_type' => 'booking',
		'order'     => 'ASC',
	);
	global $post;
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			$wp_travel_engine_setting = get_post_meta( $post->ID, 'wp_travel_engine_booking_setting', true );

			// Plugins can add as many items in the item data array as they want
			$data = array(
				array(
					'name'  => __( 'First Name', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['place_order']['booking']['fname'] ) ? esc_attr( $wp_travel_engine_setting['place_order']['booking']['fname'] ) : '',
				),
				array(
					'name'  => __( 'Last Name', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['place_order']['booking']['lname'] ) ? esc_attr( $wp_travel_engine_setting['place_order']['booking']['lname'] ) : '',
				),
				array(
					'name'  => __( 'Email', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['place_order']['booking']['email'] ) ? esc_attr( $wp_travel_engine_setting['place_order']['booking']['email'] ) : '',
				),
				array(
					'name'  => __( 'Address', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['place_order']['booking']['address'] ) ? esc_attr( $wp_travel_engine_setting['place_order']['booking']['address'] ) : '',
				),
				array(
					'name'  => __( 'City', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['place_order']['booking']['city'] ) ? esc_attr( $wp_travel_engine_setting['place_order']['booking']['city'] ) : '',
				),
				array(
					'name'  => __( 'Country', 'wp-travel-engine' ),
					'value' => isset( $wp_travel_engine_setting['place_order']['booking']['country'] ) ? esc_attr( $wp_travel_engine_setting['place_order']['booking']['country'] ) : '',
				),
			);

			$export_items[] = array(
				'group_id'    => 'wpte-enquiry-record',
				'group_label' => __( 'Enquiry & Customer Record', 'wp-travel-engine' ),
				'item_id'     => $post->ID,
				'data'        => $data,
			);
		}
	}
	// Tell core if we have more comments to work on still
	$done = true;
	return array(
		'data' => $export_items,
		'done' => $done,
	);
}

function register_wp_travel_engine_plugin_exporter( $exporters ) {
	$exporters[] = array(
		'exporter_friendly_name' => __( 'WP Travel Engine', 'wp-travel-engine' ),
		'callback'               => 'wp_travel_engine_plugin_exporter',
	);
	return $exporters;
}
add_filter( 'wp_privacy_personal_data_exporters', 'register_wp_travel_engine_plugin_exporter', 10 );

/**
 * WP Travel Engine Post Duplicator.
 *
 * @param   Array  $actions    Action.
 * @param   Object $post       Post Object.
 *
 * @since   1.7.6
 *
 * @return  Array $actions;
 */
function wp_travel_engine_post_duplicator_action_row( $actions, $post ) {
	// Get the post type object
	$post_type = get_post_type_object( $post->post_type );
	if ( 'trip' === $post_type->name && function_exists( 'wp_travel_engine_post_duplicator_action_row_link' ) ) {
		$actions['wp_travel_engine_clone_post'] = wp_travel_engine_post_duplicator_action_row_link( $post );
	}
	return $actions;
}
add_filter( 'post_row_actions', 'wp_travel_engine_post_duplicator_action_row', 10, 2 );

/**
 * Duplication action
 *
 * @param [type] $post
 * @return void
 */
function wp_travel_engine_post_duplicator_action_row_link( $post ) {

	// Get the post type object
	$post_type = get_post_type_object( $post->post_type );

	if ( 'trip' !== $post_type->name ) {
		return;
	}

	// Set the button label
	$label = __( 'Clone', 'wp-travel-engine' );

	// Create a nonce & add an action
	$nonce = wp_create_nonce( 'wte_clone_post_nonce' );

	// Return the link
	return '<a title="' . __( 'Clone ', 'wp-travel-engine' ) . esc_attr( get_the_title( $post->ID ) ) . '" class="wte-clone-post" data-security="' . esc_attr( $nonce ) . '" href="#" data-post_id="' . esc_attr( $post->ID ) . '">' . esc_html( $label ) . '</a>';
}
