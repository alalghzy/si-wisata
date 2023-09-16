<?php
/**
 * Email Functionality
 *
 * Maintain a list of tags and functions that are used in email templates.
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 * @author
 */
class Wp_Travel_Engine_Mail_Template {

	function wpte_get_client_ip() {
		 $ipaddress = '';
		if ( getenv( 'HTTP_CLIENT_IP' ) ) {
			$ipaddress = getenv( 'HTTP_CLIENT_IP' );
		} elseif ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_X_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED' );
		} elseif ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED' );
		} elseif ( getenv( 'REMOTE_ADDR' ) ) {
			$ipaddress = getenv( 'REMOTE_ADDR' );
		} else {
			$ipaddress = 'UNKNOWN';
		}

		return $ipaddress;
	}

	function mail_editor( $settings, $pid ) {
		global $wte_cart;

		$cart_items = $wte_cart->getItems();

		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
		$booking_url               = get_edit_post_link( $pid );
		$booking_url               = '#<a href="' . esc_url( $booking_url ) . '">' . $pid . '</a>';
		$subject_receipt           = __( 'Booking Confirmation', 'wp-travel-engine' );
		if ( isset( $wp_travel_engine_settings['email']['subject'] ) && $wp_travel_engine_settings['email']['subject'] != '' ) {
			$subject_receipt = $wp_travel_engine_settings['email']['subject'];
		}

		$from_name = get_bloginfo( 'name' );
		if ( isset( $wp_travel_engine_settings['email']['name'] ) && $wp_travel_engine_settings['email']['name'] != '' ) {
			$from_name = $wp_travel_engine_settings['email']['name'];
		}

		$from_email = get_option( 'admin_email' );
		if ( isset( $wp_travel_engine_settings['email']['from'] ) && $wp_travel_engine_settings['email']['from'] != '' ) {
			$from_email = $wp_travel_engine_settings['email']['from'];
		}

		// Add support for Attachments.
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		$uploadedfile = $_FILES;
		$attachments  = array();
		foreach ( $uploadedfile as $key => $file ) {
			$upload_file = wp_handle_upload( $file, array( 'test_form' => false ) );
			if ( $upload_file && ! isset( $upload_file['error'] ) ) {
				$attachments[ $key ] = $upload_file['file'];
			}
		}

		$from_receipt = $from_name . ' <' . $from_email . '>';
		// $from_receipt = trim($from_receipt);
		  // To send HTML mail, the Content-type header must be set
		$headers_receipt  = 'MIME-Version: 1.0' . "\r\n";
		$charset          = apply_filters( 'wp_travel_engine_mail_charset', 'Content-type: text/html; charset=UTF-8' );
		$headers_receipt .= $charset . "\r\n";
		  // Create email headers
		$headers_receipt .= 'From:' . $from_receipt . "\r\n" .
			'Reply-To: ' . $from_receipt . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
		$sitename         = get_bloginfo( 'name' );
		$purchase_open    = '<html><body style="font-family: Arial, Helvetica, sans-serif; font-size: 1rem; line-height: 1.35em;"><div style="line-height: 2em; margin: 0 auto; background: #fafafa; padding: 50px;">';
		$post             = get_post( (int) $_SESSION['trip-id'] );
		$slug             = $post->post_title;
		$trip             = '<a href=' . esc_url( get_permalink( (int) $_SESSION['trip-id'] ) ) . '>' . $slug . '</a>';
		$traveler         = wte_clean( wp_unslash( $_SESSION['travelers'] ) );

		$price      = wte_get_formated_price( $settings['place_order']['cost'], '', '', false, false, true );
		$due        = isset( $settings['place_order']['due'] ) ? wte_get_formated_price( $settings['place_order']['due'], '', '', false, false, true ) : '';
		$total_cost = isset( $settings['place_order']['due'] ) && ( $settings['place_order']['due'] != '' || $settings['place_order']['due'] != 0 ) ? floatval( $settings['place_order']['cost'] ) + floatval( $settings['place_order']['due'] ) : $_SESSION['trip-cost'];
		$total_cost = wte_get_formated_price( $total_cost, '', '', false, false, true );

		$fullname      = $settings['place_order']['booking']['fname'] . ' ' . $settings['place_order']['booking']['lname'];
		$trip_settings = get_post_meta( (int) $_SESSION['trip-id'], 'wp_travel_engine_setting', true );
		$cost          = isset( $trip_settings['trip_price'] ) ? $trip_settings['trip_price'] : '';
		if ( $cost != '' && isset( $trip_settings['sale'] ) ) {
			$tripprice = $cost;
		} else {
			if ( isset( $trip_settings['trip_prev_price'] ) && $trip_settings['trip_prev_price'] != '' ) {
				$tripprice = $trip_settings['trip_prev_price'];
			}
		}

		$tprice = wte_get_formated_price_html( $tripprice, null, true );

		$city = '';
		// phpcs:disable
		if( isset( $_POST["wp_travel_engine_booking_setting"]["place_order"]["booking"]["city"] ) ){
			$city = sanitize_text_field( wp_unslash( $_POST["wp_travel_engine_booking_setting"]["place_order"]["booking"]["city"] ) );
		}

		$country = '';
		if( isset( $_POST["wp_travel_engine_booking_setting"]["place_order"]["booking"]["country"] ) ){
			$country = sanitize_text_field( wp_unslash( $_POST["wp_travel_engine_booking_setting"]["place_order"]["booking"]["country"] ) );
		}
		// phpcs:enable

		if ( isset( $wp_travel_engine_settings['email']['purchase_wpeditor'] ) && $wp_travel_engine_settings['email']['purchase_wpeditor'] != '' ) {
			$wp_travel_engine_settings['email']['purchase_wpeditor'] = wpautop( html_entity_decode( $wp_travel_engine_settings['email']['purchase_wpeditor'], 3, 'UTF-8' ) );

			$purchase_receipt = apply_filters( 'meta_content', $wp_travel_engine_settings['email']['purchase_wpeditor'] );
			$purchase_receipt = str_replace( '{name}', $settings['place_order']['booking']['fname'], $purchase_receipt );
			$purchase_receipt = str_replace( '{fullname}', $fullname, $purchase_receipt );
			$purchase_receipt = str_replace( '{user_email}', $settings['place_order']['booking']['email'], $purchase_receipt );
			$purchase_receipt = str_replace( '{billing_address}', $settings['place_order']['booking']['address'], $purchase_receipt );
			$purchase_receipt = str_replace( '{sitename}', $sitename, $purchase_receipt );
			$purchase_receipt = str_replace( '{price}', $price, $purchase_receipt );
			$purchase_receipt = str_replace( '{tprice}', $tprice, $purchase_receipt );
			$purchase_receipt = str_replace( '{trip_url}', '#' . $trip, $purchase_receipt );
			$purchase_receipt = str_replace( '{tdate}', wte_clean( wp_unslash( $_SESSION['trip-date'] ) ), $purchase_receipt );
			$purchase_receipt = str_replace( '{city}', $city, $purchase_receipt );
			$purchase_receipt = str_replace( '{country}', $country, $purchase_receipt );

			foreach ( $cart_items as $key => $cart_item ) :
				if ( isset( $cart_item['multi_pricing_used'] ) && $cart_item['multi_pricing_used'] ) :
					$multiple_pricing_html = '';
					foreach ( $cart_item['pax'] as $pax_key => $pax ) :
						if ( '0' == $pax || empty( $pax ) ) {
							continue;
						}
						$pax_label              = wte_get_pricing_label_by_key_invoices( $cart_item['trip_id'], $pax_key, $pax );
						$multiple_pricing_html .= '<p>' . $pax_label . ': ' . $pax . '</p>';
					endforeach;
					$purchase_receipt = str_replace( '{traveler}', $multiple_pricing_html, $purchase_receipt );
					$purchase_receipt = str_replace( '{child-traveler}', isset( $_SESSION['child-travelers'] ) ? wte_clean( wte_unslash( $_SESSION['child-travelers'] ) ) : '', $purchase_receipt );
				else :
					$purchase_receipt = str_replace( '{traveler}', $traveler, $purchase_receipt );
					$purchase_receipt = str_replace( '{child-traveler}', isset( $_SESSION['child-travelers'] ) ? wte_clean( wte_unslash( $_SESSION['child-travelers'] ) ) : '', $purchase_receipt );
				endif;
			endforeach;

			$purchase_receipt = str_replace( '{booking_url}', $booking_url, $purchase_receipt );
			$purchase_receipt = str_replace( '{total_cost}', $total_cost, $purchase_receipt );
			$purchase_receipt = str_replace( '{due}', $due, $purchase_receipt );
			$ip               = $this->wpte_get_client_ip();
			$purchase_receipt = str_replace( '{ip_address}', $ip, $purchase_receipt );
		} else {
			$purchase_receipt  = '<p>' . __( 'Dear {name},', 'wp-travel-engine' ) . '<br />';
			$purchase_receipt .= '</p>' . '<p>' . __( 'You have successfully made the trip booking. Your booking information is below.', 'wp-travel-engine' ) . '</p>';
			$purchase_receipt .= '' . '<br />';
			$purchase_receipt .= __( 'Trip Name: {trip_url}', 'wp-travel-engine' ) . '<br />';
			$purchase_receipt .= __( 'Trip Cost: {tprice}', 'wp-travel-engine' ) . '<br />';
			$purchase_receipt .= __( 'Trip Start Date : {tdate}', 'wp-travel-engine' ) . '<br />';
			$purchase_receipt .= __( 'Total Number of Traveller(s): {traveler}', 'wp-travel-engine' ) . '<br />';
			$purchase_receipt .= __( 'Total Number of Child Traveller(s): {child-traveler}', 'wp-travel-engine' ) . '<br />';
			$purchase_receipt .= __( 'Booking Url: {booking_url}', 'wp-travel-engine' ) . '<br />';
			$purchase_receipt .= __( 'Total Cost: {price}', 'wp-travel-engine' ) . '<br />';
			$purchase_receipt .= __( 'Thank you.', 'wp-travel-engine' ) . '<br />';
			$purchase_receipt .= __( 'Best regards,', 'wp-travel-engine' ) . '<br />';
			$purchase_receipt .= get_bloginfo( 'name' ) . '<br />';

			$purchase_receipt = str_replace( '{name}', $settings['place_order']['booking']['fname'], $purchase_receipt );
			$purchase_receipt = str_replace( '{fullname}', $fullname, $purchase_receipt );
			$purchase_receipt = str_replace( '{user_email}', $settings['place_order']['booking']['email'], $purchase_receipt );
			$purchase_receipt = str_replace( '{billing_address}', $settings['place_order']['booking']['address'], $purchase_receipt );
			$purchase_receipt = str_replace( '{date}', date( 'Y-m-d H:i:s' ), $purchase_receipt );
			$purchase_receipt = str_replace( '{sitename}', $sitename, $purchase_receipt );
			$purchase_receipt = str_replace( '{price}', $price, $purchase_receipt );
			$purchase_receipt = str_replace( '{tprice}', $tprice, $purchase_receipt );
			$purchase_receipt = str_replace( '{trip_url}', '#' . $trip . '<br />', $purchase_receipt );
			$purchase_receipt = str_replace( '{tdate}', wte_clean( wp_unslash( $_SESSION['trip-date'] ) ), $purchase_receipt );
			$purchase_receipt = str_replace( '{city}', $city, $purchase_receipt );
			$purchase_receipt = str_replace( '{country}', $country, $purchase_receipt );

			foreach ( $cart_items as $key => $cart_item ) :
				if ( isset( $cart_item['multi_pricing_used'] ) && $cart_item['multi_pricing_used'] ) :
					$multiple_pricing_html = '';
					foreach ( $cart_item['pax'] as $pax_key => $pax ) :
						if ( '0' == $pax || empty( $pax ) ) {
							continue;
						}
						$pax_label              = wte_get_pricing_label_by_key_invoices( $cart_item['trip_id'], $pax_key, $pax );
						$multiple_pricing_html .= '<p>' . $pax_label . ': ' . $pax . '</p>';
					endforeach;

					$purchase_receipt = str_replace( '{traveler}', $multiple_pricing_html, $purchase_receipt );
					$purchase_receipt = str_replace( '{child-traveler}', isset( $_SESSION['child-travelers'] ) ? wte_clean( wp_unslash( $_SESSION['child-travelers'] ) ) : '', $purchase_receipt );
				else :
					$purchase_receipt = str_replace( '{traveler}', $traveler, $purchase_receipt );
					$purchase_receipt = str_replace( '{child-traveler}', isset( $_SESSION['child-travelers'] ) ? wte_clean( wp_unslash( $_SESSION['child-travelers'] ) ) : '', $purchase_receipt );
				endif;
			endforeach;
			$purchase_receipt = str_replace( '{booking_url}', $booking_url . '<br />', $purchase_receipt );
			$ip               = $this->wpte_get_client_ip();

			$purchase_receipt = str_replace( '{ip_address}', $ip, $purchase_receipt );
		}

		$booking_extra_fields = isset( $settings['additional_fields'] ) && is_array( $settings['additional_fields'] ) ? $settings['additional_fields'] : array();

		if ( ! empty( $booking_extra_fields ) ) {
			$booking_mappable_array = array();
			foreach ( $booking_extra_fields as $key => $value ) {
				$new_key                            = '{' . $key . '}';
				$booking_mappable_array[ $new_key ] = $value;
			}

			$purchase_receipt = str_replace( array_keys( $booking_mappable_array ), $booking_mappable_array, $purchase_receipt );
		}

		$purchase_receipt = str_replace( '{booking_id}', $pid, $purchase_receipt );

		/**
		 * Purchase reciept contents filter.
		 */
		$purchase_receipt = apply_filters( 'wte_purchase_reciept_email_content', $purchase_receipt, $pid );

		$purchase_close   = '</div></body></html>';
		$purchase_receipt = $purchase_open . $purchase_receipt . $purchase_close;
		$purchase_receipt = wpautop( html_entity_decode( $purchase_receipt, 3, 'UTF-8' ) );
		// die;
		wp_mail( $settings['place_order']['booking']['email'], $subject_receipt, $purchase_receipt, $headers_receipt, $attachments );

		// Mail for Admin
		if ( isset( $wp_travel_engine_settings['email']['sale_subject'] ) && $wp_travel_engine_settings['email']['sale_subject'] != '' ) {
			$subject_book = esc_attr( $wp_travel_engine_settings['email']['sale_subject'] );
		}
		$subject_book = 'New Booking Order #' . $pid;
		$from_book    = $from_name . ' <' . $from_email . '>';
		// $from_book = trim($from_book);
		  // To send HTML mail, the Content-type header must be set
		$headers_book  = 'MIME-Version: 1.0' . "\r\n";
		$charset       = apply_filters( 'wp_travel_engine_mail_charset', 'Content-type: text/html; charset=UTF-8' );
		$headers_book .= $charset . "\r\n";
		  // Create email headers
		$headers_book .= 'From: ' . $from_book . "\r\n" .
			'Reply-To: ' . $from_receipt . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		$book_open = '<html><body style="font-family: Arial, Helvetica, sans-serif; font-size: 1rem; line-height: 1.35em;"><div style="line-height: 2em; margin: 0 auto; background: #fafafa; padding: 50px;">';
		$post      = get_post( (int) $_SESSION['trip-id'] );
		$slug      = $post->post_title;
		$trip      = '<a href=' . esc_url( get_permalink( $_SESSION['trip-id'] ) ) . '>' . $slug . '</a>';

		if ( isset( $wp_travel_engine_settings['email']['sales_wpeditor'] ) && $wp_travel_engine_settings['email']['sales_wpeditor'] != '' ) {
			$wp_travel_engine_settings['email']['sales_wpeditor'] = wpautop( html_entity_decode( $wp_travel_engine_settings['email']['sales_wpeditor'], 3, 'UTF-8' ) );
			$book_receipt = apply_filters( 'meta_content', $wp_travel_engine_settings['email']['sales_wpeditor'] );
			$book_receipt = str_replace( '{name}', $settings['place_order']['booking']['fname'], $book_receipt );
			$book_receipt = str_replace( '{fullname}', $fullname, $book_receipt );
			$book_receipt = str_replace( '{user_email}', $settings['place_order']['booking']['email'], $book_receipt );
			$book_receipt = str_replace( '{billing_address}', $settings['place_order']['booking']['address'], $book_receipt );
			$book_receipt = str_replace( '{date}', date( 'Y-m-d H:i:s' ), $book_receipt );
			$book_receipt = str_replace( '{sitename}', $sitename, $book_receipt );
			$book_receipt = str_replace( '{price}', $price, $book_receipt );
			$book_receipt = str_replace( '{tprice}', $tprice, $book_receipt );
			$book_receipt = str_replace( '{trip_url}', '#' . $trip, $book_receipt );
			$book_receipt = str_replace( '{tdate}', wte_clean( wp_unslash( $_SESSION['trip-date'] ) ), $book_receipt );
			$book_receipt = str_replace( '{booking_url}', $booking_url, $book_receipt );
			$book_receipt = str_replace( '{city}', $city, $book_receipt );
			$book_receipt = str_replace( '{country}', $country, $book_receipt );

			foreach ( $cart_items as $key => $cart_item ) :

				if ( isset( $cart_item['multi_pricing_used'] ) && $cart_item['multi_pricing_used'] ) :

					$multiple_pricing_html = '';

					foreach ( $cart_item['pax'] as $pax_key => $pax ) :

						if ( '0' == $pax || empty( $pax ) ) {
							continue;
						}

						$pax_label = wte_get_pricing_label_by_key_invoices( $cart_item['trip_id'], $pax_key, $pax );

						$multiple_pricing_html .= '<p>' . $pax_label . ': ' . $pax . '</p>';

					endforeach;

					$book_receipt = str_replace( '{traveler}', $multiple_pricing_html, $book_receipt );
					$book_receipt = str_replace( '{child-traveler}', isset( $_SESSION['child-travelers'] ) ? esc_attr( $_SESSION['child-travelers'] ) : '', $book_receipt );

				else :

					$book_receipt = str_replace( '{traveler}', $traveler, $book_receipt );
					$book_receipt = str_replace( '{child-traveler}', isset( $_SESSION['child-travelers'] ) ? wte_clean( wp_unslash( $_SESSION['child-travelers'] ) ) : '', $book_receipt );

				endif;

			endforeach;

			$book_receipt = str_replace( '{total_cost}', $total_cost, $book_receipt );

			$book_receipt = str_replace( '{due}', $due, $book_receipt );
			$ip           = $this->wpte_get_client_ip();
			$book_receipt = str_replace( '{ip_address}', $ip, $book_receipt );
		} else {

			$book_receipt  = '<p>' . __( 'Dear Admin,', 'wp-travel-engine' ) . '</p>' . '<br />';
			$book_receipt .= __( 'The following booking has been successfully made.', 'wp-travel-engine' ) . '<br />';
			$book_receipt .= '<br />' . __( 'Trip Name : {trip_url}', 'wp-travel-engine' ) . '<br />';
			$book_receipt .= __( 'Trip Cost:  {tprice}', 'wp-travel-engine' ) . '<br />';
			$book_receipt .= __( 'Trip Start Date : {tdate}', 'wp-travel-engine' ) . '<br />';
			$book_receipt .= __( 'Total Number of Traveller(s): {traveler}', 'wp-travel-engine' ) . '<br />';
			$book_receipt .= __( 'Total Number of Child Traveller(s): {child-traveler}', 'wp-travel-engine' ) . '<br />';
			$book_receipt .= __( 'Trip Booking URL: {booking_url}', 'wp-travel-engine' ) . '<br />';
			$book_receipt .= __( 'Total Cost: {price}', 'wp-travel-engine' ) . '<br />';
			$book_receipt .= __( 'Thank you.', 'wp-travel-engine' ) . '<br />';
			$book_receipt .= __( 'Best regards,', 'wp-travel-engine' ) . '<br />';
			$book_receipt .= get_bloginfo( 'name' ) . '<br />';

			$book_receipt = str_replace( '{name}', $settings['place_order']['booking']['fname'], $book_receipt );
			$book_receipt = str_replace( '{fullname}', $fullname, $book_receipt );
			$book_receipt = str_replace( '{user_email}', $settings['place_order']['booking']['email'], $book_receipt );
			$book_receipt = str_replace( '{billing_address}', $settings['place_order']['booking']['address'], $book_receipt );
			$book_receipt = str_replace( '{date}', date( 'Y-m-d H:i:s' ), $book_receipt );
			$book_receipt = str_replace( '{sitename}', $sitename, $book_receipt );
			$book_receipt = str_replace( '{price}', $price, $book_receipt );
			$book_receipt = str_replace( '{tprice}', $tprice, $book_receipt );
			$book_receipt = str_replace( '{trip_url}', '#' . $trip, $book_receipt );
			$book_receipt = str_replace( '{tdate}', wte_clean( wp_unslash( $_SESSION['trip-date'] ) ), $book_receipt );
			$book_receipt = str_replace( '{booking_url}', $booking_url, $book_receipt );
			$book_receipt = str_replace( '{city}', $city, $book_receipt );
			$book_receipt = str_replace( '{country}', $country, $book_receipt );

			foreach ( $cart_items as $key => $cart_item ) :

				if ( isset( $cart_item['multi_pricing_used'] ) && $cart_item['multi_pricing_used'] ) :

					$multiple_pricing_html = '';

					foreach ( $cart_item['pax'] as $pax_key => $pax ) :

						if ( '0' == $pax || empty( $pax ) ) {
							continue;
						}

						$pax_label = wte_get_pricing_label_by_key_invoices( $cart_item['trip_id'], $pax_key, $pax );

						$multiple_pricing_html .= '<p>' . $pax_label . ': ' . $pax . '</p>';

					endforeach;

					$book_receipt = str_replace( '{traveler}', $multiple_pricing_html, $book_receipt );
					$book_receipt = str_replace( '{child-traveler}', isset( $_SESSION['child-travelers'] ) ? esc_attr( $_SESSION['child-travelers'] ) : '', $book_receipt );

				else :

					$book_receipt = str_replace( '{traveler}', $traveler, $book_receipt );
					$book_receipt = str_replace( '{child-traveler}', isset( $_SESSION['child-travelers'] ) ? wte_clean( wp_unslash( $_SESSION['child-travelers'] ) ) : '', $book_receipt );

				endif;

			endforeach;
		}

		if ( ! empty( $booking_extra_fields ) ) {

			$booking_mappable_array = array();
			foreach ( $booking_extra_fields as $key => $value ) {
				$new_key                            = '{' . $key . '}';
				$booking_mappable_array[ $new_key ] = $value;
			}

			$book_receipt = str_replace( array_keys( $booking_mappable_array ), $booking_mappable_array, $book_receipt );
		}

		$book_receipt = str_replace( '{booking_id}', $pid, $book_receipt );

		/**
		 * Booking reciept contents filter.
		 */
		$book_receipt = apply_filters( 'wte_booking_reciept_email_content', $book_receipt, $pid );

		$book_close = '</div></body></html>';

		if ( ! isset( $wp_travel_engine_settings['email']['disable_notif'] ) || $wp_travel_engine_settings['email']['disable_notif'] != '1' ) {
			if ( strpos( $wp_travel_engine_settings['email']['emails'], ',' ) !== false ) {
				$wp_travel_engine_settings['email']['emails'] = str_replace( ' ', '', $wp_travel_engine_settings['email']['emails'] );
				$admin_emails                                 = explode( ',', $wp_travel_engine_settings['email']['emails'] );
				$book_receipt                                 = $book_open . $book_receipt . $book_close;
				$book_receipt                                 = wpautop( html_entity_decode( $book_receipt, 3, 'UTF-8' ) );
				foreach ( $admin_emails as $key => $value ) {
					$a = 1;
					wp_mail( $value, $subject_book, $book_receipt, $headers_book, $attachments );
				}
			} else {
				$wp_travel_engine_settings['email']['emails'] = str_replace( ' ', '', $wp_travel_engine_settings['email']['emails'] );
				$book_receipt                                 = $book_open . $book_receipt . $book_close;
				$book_receipt                                 = wpautop( html_entity_decode( $book_receipt, 3, 'UTF-8' ) );
				wp_mail( $wp_travel_engine_settings['email']['emails'], $subject_book, $book_receipt, $headers_book, $attachments );
			}
		}
	}
}
