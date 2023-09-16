<?php
/**
 * Email Template and functions.
 *
 * @package WP_Travel_Engine
 */

 use WPTravelEngine\Booking\Email\Template_Tags;

/**
 * WP Travel Engine Emails.
 */
class WP_Travel_Engine_Emails {

	/**
	 * Class Constructor.
	 */
	public function __construct() {

	}

	/**
	 * Get email template headers.
	 */
	public function get_email_template( $type, $sent_to, $content_only = false, $data = false ) {
		$strings = array(
			'heading'         => __( 'New Booking', 'wp-travel-engine' ),
			'greeting'        => __( 'Dear Admin,', 'wp-travel-engine' ),
			'greeting_byline' => __( 'A new booking has been made on your website. Booking details are listed below.', 'wp-travel-engine' ),
		);
		if ( $sent_to === 'customer' ) {
			$strings = array(
				'heading'         => __( 'Booking Confirmation', 'wp-travel-engine' ),
				'greeting'        => __( 'Dear {name},', 'wp-travel-engine' ),
				'greeting_byline' => __( 'You have successfully made the trip booking. Your booking information is listed below.', 'wp-travel-engine' ),
			);
		}
		$args = array(
			'sent_to' => $sent_to,
			'strings' => $strings,
		);

		if ( $data ) {
			$args['form_data'] = $data;
		}

		ob_start();
		if ( $content_only ) {

			// Email Content.
			wte_get_template( "emails/{$type}.php", $args );

			$template = ob_get_clean();
			return $template;
		}
			// Get email Header.
			wte_get_template( 'emails/email-header.php' );

				// Email Content.
				wte_get_template( "emails/{$type}.php", $args );

			// Get email footer.
			wte_get_template( 'emails/email-footer.php' );
		$template = ob_get_clean();
		return $template;
	}

	/**
	 * Get traveller email template.
	 */
	public function get_traveller_template( $type, $pno, $personal_options ) {
		ob_start();
		$args = array(
			'data'    => $personal_options,
			'numbers' => $pno,
		);

			// Email Content.
			wte_get_template( "emails/{$type}.php", $args );

			$template = ob_get_clean();

			return $template;
	}

	/**
	 * Get Email header.
	 *
	 * @return void
	 */
	public function get_email_header() {
		ob_start();
		 // Get email Header.
		wte_get_template( 'emails/email-header.php' );

		$template = ob_get_clean();
		return $template;
	}

	/**
	 * Get Email Footer.
	 *
	 * @return void
	 */
	public function get_email_footer() {
		ob_start();
		// Get email Header.
		wte_get_template( 'emails/email-footer.php' );

		$template = ob_get_clean();
		return $template;
	}

	/**
	 * Send emails.
	 */
	public function send_booking_emails( $order_details, $booking_id ) {

		if ( ! $booking_id ) {
			return false;
		}

		// get cartdata.
		global $wte_cart;

		// cart items array.
		$cart_items     = $wte_cart->getItems();
		$totals         = $wte_cart->get_total();
		$cart_discounts = $wte_cart->get_discounts();
		$total          = $totals['total'];
		$trip_ids       = $wte_cart->get_cart_trip_ids();
		$trip_id        = $trip_ids['0'];
		$trip_name      = get_the_title( $trip_id );
		$trip_link      = '<a href=' . esc_url( get_permalink( $trip_id ) ) . '>' . esc_html( $trip_name ) . '</a>';

		// get settings.
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );

		$obj           = \wte_functions();
		$code          = isset( $wp_travel_engine_settings['currency_code'] ) ? $wp_travel_engine_settings['currency_code'] : 'USD';
		$currency_sign = $obj->wp_travel_engine_currencies_symbol( $code );

		// Define variables.
		$booking_url      = get_edit_post_link( $booking_id );
		$booking_url_link = '#<a href="' . esc_url( $booking_url ) . '">' . $booking_id . '</a>';
		$fullname         = $order_details['place_order']['booking']['fname'] . ' ' . $order_details['place_order']['booking']['lname'];
		$sitename         = get_bloginfo( 'name' );
		$city             = $order_details['place_order']['booking']['city'];

		foreach ( $cart_items as $key => $cart_item ) :
			$traveller       = array_sum( $cart_item['pax'] );
			$child_traveller = isset( $cart_item['pax']['child'] ) ? $cart_item['pax']['child'] : '';

			if ( isset( $cart_item['multi_pricing_used'] ) && $cart_item['multi_pricing_used'] ) :
				$multiple_pricing_html = '';
				foreach ( $cart_item['pax'] as $pax_key => $pax ) :
					if ( '0' == $pax || empty( $pax ) ) {
						continue;
					}
					$pax_label              = wte_get_pricing_label_by_key_invoices( $cart_item['trip_id'], $pax_key, $pax );
					$per_pricing_price      = ( $cart_item['pax_cost'][ $pax_key ] / $pax );
					$multiple_pricing_html .= '<p>' . $pax_label . ': ' . $pax . 'X ' . wte_get_formated_price( $per_pricing_price, '', '', false, false, true ) . '</p>';
				endforeach;
				$traveller = $multiple_pricing_html;
			endif;
		endforeach;

		// Mapping Mail tags with values.
		$default_mail_tags = array(
			'{trip_url}'                  => $trip_link,
			'{name}'                      => $order_details['place_order']['booking']['fname'],
			'{fullname}'                  => $fullname,
			'{user_email}'                => $order_details['place_order']['booking']['email'],
			'{billing_address}'           => $order_details['place_order']['booking']['address'],
			'{city}'                      => $city,
			'{test}'                      => $city,
			'{country}'                   => $order_details['place_order']['booking']['country'],
			'{tdate}'                     => $order_details['place_order']['datetime'],
			'{traveler}'                  => $traveller,
			'{child-traveler}'            => $child_traveller,
			'{tprice}'                    => wte_get_formated_price( wp_travel_engine_get_actual_trip_price( $trip_id, true ), '', '', false, false, true ),
			'{price}'                     => wte_get_formated_price( $order_details['place_order']['cost'], '', '', false, false, true ),
			'{total_cost}'                => wte_get_formated_price( $total, '', '', false, false, true ),
			'{due}'                       => wte_get_formated_price( $order_details['place_order']['due'], '', '', false, false, true ),
			'{sitename}'                  => $sitename,
			'{booking_url}'               => $booking_url,
			'{ip_address}'                => '',
			'{date}'                      => date( 'Y-m-d H:i:s' ),
			'{booking_id}'                => sprintf( __( 'Booking id: #%1$s', 'wp-travel-engine' ), $booking_id ),
			'{bank_details}'              => '',
			'{check_payment_instruction}' => '',
		);

		// Bank Details.
		if ( isset( $_REQUEST['wpte_checkout_paymnet_method'] ) && 'direct_bank_transfer' === $_REQUEST['wpte_checkout_paymnet_method'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$bank_details_labels = array(
				// 'account_name',
				'account_number' => __( 'Account Number', 'wp-travel-engine' ),
				'bank_name'      => __( 'Bank Name', 'wp-travel-engine' ),
				'sort_code'      => __( 'Sort Code', 'wp-travel-engine' ),
				'iban'           => __( 'IBAN', 'wp-travel-engine' ),
				'swift'          => __( 'BIC/Swift', 'wp-travel-engine' ),
			);

			$bank_accounts = isset( $wp_travel_engine_settings['bank_transfer']['accounts'] ) && is_array( $wp_travel_engine_settings['bank_transfer']['accounts'] ) ? $wp_travel_engine_settings['bank_transfer']['accounts'] : array();
			ob_start();
			echo '<table class="invoice-items">';
			echo '<tr>';
			echo '<td colspan="2">';
			echo '<h3>' . esc_html__( 'Bank Details:', 'wp-travel-engine' ) . '</h3>';
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td colspan="2">';
			echo isset( $wp_travel_engine_settings['bank_transfer']['instruction'] ) ? wp_kses_post( $wp_travel_engine_settings['bank_transfer']['instruction'] ) : '';
			echo '</td>';
			echo '</tr>';
			foreach ( $bank_accounts as $account ) {
				echo '<tr>';
				echo '<td colspan="2">';
				echo '<h5>' . esc_html( $account['account_name'] ) . '</h5>';
				echo '</td>';
				echo '</tr>';
				foreach ( $bank_details_labels as $key => $label ) {
					?>
					<tr>
						<td><?php echo esc_html( $label ); ?></td>
						<td class="alignright"><?php echo isset( $account[ $key ] ) ? esc_html( $account[ $key ] ) : ''; ?></td>
					</tr>
					<?php
				}
			}
			echo '</table>';
			$default_mail_tags['{bank_details}'] = ob_get_clean();
		}

		// Check Payment Instructions.
		if ( isset( $_REQUEST['wpte_checkout_paymnet_method'] ) && 'check_payments' === $_REQUEST['wpte_checkout_paymnet_method'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			ob_start();
			?>
			<table class="invoice-items">
				<tr>
					<td colspan="2">
						<h3><?php echo esc_html__( 'Check Payment Instructions:', 'wp-travel-engine' ); ?></h3>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php echo isset( $wp_travel_engine_settings['check_payment']['instruction'] ) ? wp_kses_post( $wp_travel_engine_settings['check_payment']['instruction'] ) : ''; ?>
					</td>
				</tr>
			</table>
			<?php
			$default_mail_tags['{check_payment_instruction}'] = ob_get_clean();
		}

		// Prepare client emails emails.
		$customer_email_template_content = $this->get_email_template( 'booking', 'customer', true );
		$admin_email_template_content    = $this->get_email_template( 'booking', 'admin', true );

		if ( isset( $wp_travel_engine_settings['email']['purchase_wpeditor'] ) && $wp_travel_engine_settings['email']['purchase_wpeditor'] != '' ) {
			$customer_email_template_content = wp_kses_post( $wp_travel_engine_settings['email']['purchase_wpeditor'] );
		}

		if ( isset( $wp_travel_engine_settings['email']['sales_wpeditor'] ) && $wp_travel_engine_settings['email']['sales_wpeditor'] != '' ) {
			$admin_email_template_content = wp_kses_post( $wp_travel_engine_settings['email']['sales_wpeditor'] );
		}

		// Prepare customer email template.
		$customer_email_template  = $this->get_email_header();
		$customer_email_template .= $customer_email_template_content;
		$customer_email_template .= $this->get_email_footer();

		// Prepare admin email template.
		$admin_email_template  = $this->get_email_header();
		$admin_email_template .= $admin_email_template_content;
		$admin_email_template .= $this->get_email_footer();

		$customer_email_template = str_replace( array_keys( $default_mail_tags ), $default_mail_tags, $customer_email_template );
		$admin_email_template    = str_replace( array_keys( $default_mail_tags ), $default_mail_tags, $admin_email_template );

		$booking_extra_fields = isset( $order_details['additional_fields'] ) && is_array( $order_details['additional_fields'] ) ? $order_details['additional_fields'] : array();

		if ( ! empty( $booking_extra_fields ) ) {
			$booking_mappable_array = array();
			foreach ( $booking_extra_fields as $key => $value ) {
				$new_key                            = '{' . $key . '}';
				$booking_mappable_array[ $new_key ] = $value;
			}

			$customer_email_template = str_replace( array_keys( $booking_mappable_array ), $booking_mappable_array, $customer_email_template );

			$admin_email_template = str_replace( array_keys( $booking_mappable_array ), $booking_mappable_array, $admin_email_template );
		}

		/** For Discount */
		$cart_discounts_mappable_array = array();
		if ( ! empty( $cart_discounts ) ) {
			foreach ( $cart_discounts as $key => $value ) {
				foreach ( $value as $k => $v ) {
					$new_key                                   = '{discount_' . $k . '}';
					$cart_discounts_mappable_array[ $new_key ] = $v;
				}
				/** Actual discount Amount */
				if ( $value['type'] == 'percentage' ) {
					$percentage_discount_amount                         = number_format( ( ( wp_travel_engine_get_actual_trip_price( $trip_id ) * $value['value'] ) / 100 ), '2', '.', '' );
					$cart_discounts_mappable_array['{discount_amount}'] = wte_get_formated_price( $percentage_discount_amount );
					$cart_discounts_mappable_array['{discount_sign}']   = '%';
				} else {
					 $cart_discounts_mappable_array['{discount_amount}'] = wte_get_formated_price( $value['value'] );
					 $cart_discounts_mappable_array['{discount_sign}']   = $currency_sign;
				}
			}
		} else {
			$cart_discounts_mappable_array = array(
				'{discount_name}'   => '',
				'{discount_amount}' => '',
				'{discount_sign}'   => '',
				'{discount_value}'  => '',
			);
		}

		$customer_email_template = str_replace( array_keys( $cart_discounts_mappable_array ), $cart_discounts_mappable_array, $customer_email_template );
		$admin_email_template    = str_replace( array_keys( $cart_discounts_mappable_array ), $cart_discounts_mappable_array, $admin_email_template );

		/** End For Discount */

		$customer_from_name = get_bloginfo( 'name' );
		if ( isset( $wp_travel_engine_settings['email']['name'] ) && $wp_travel_engine_settings['email']['name'] != '' ) {
			$customer_from_name = $wp_travel_engine_settings['email']['name'];
		}

		$customer_from_email = get_option( 'admin_email' );
		if ( isset( $wp_travel_engine_settings['email']['from'] ) && $wp_travel_engine_settings['email']['from'] != '' ) {
			$customer_from_email = $wp_travel_engine_settings['email']['from'];
		}

		$subject_receipt = __( 'Booking Confirmation', 'wp-travel-engine' );
		if ( isset( $wp_travel_engine_settings['email']['subject'] ) && $wp_travel_engine_settings['email']['subject'] != '' ) {
			$subject_receipt = $wp_travel_engine_settings['email']['subject'];
		}

		$customer_headers  = 'MIME-Version: 1.0' . "\r\n";
		$charset           = apply_filters( 'wp_travel_engine_mail_charset', 'Content-type: text/html; charset=UTF-8' );
		$customer_headers .= $charset . "\r\n";
		$from_receipt      = $customer_from_name . ' <' . $customer_from_email . '>';
		$customer_headers .= 'From:' . $from_receipt . "\r\n" .
			'Reply-To: ' . $from_receipt . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		/**
		 * Purchase reciept contents filter.
		 */
		$customer_email_template = apply_filters( 'wte_purchase_reciept_email_content', $customer_email_template, $booking_id );

		// Send email to customer.
		wp_mail( $order_details['place_order']['booking']['email'], $subject_receipt, $customer_email_template, $customer_headers );

		// Prepare emails to Admin.

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

		// Mail for Admin
		if ( isset( $wp_travel_engine_settings['email']['sale_subject'] ) && $wp_travel_engine_settings['email']['sale_subject'] != '' ) {
			$subject_book = esc_attr( $wp_travel_engine_settings['email']['sale_subject'] );
		}
		$subject_book = 'New Booking Order Test#' . $booking_id;
		$from_book    = $customer_from_name . ' <' . $customer_from_email . '>';

		// To send HTML mail, the Content-type header must be set
		$headers_book  = 'MIME-Version: 1.0' . "\r\n";
		$charset       = apply_filters( 'wp_travel_engine_mail_charset', 'Content-type: text/html; charset=UTF-8' );
		$headers_book .= $charset . "\r\n";

		// Create email headers
		$headers_book .= 'From: ' . $from_book . "\r\n" .
			'Reply-To: ' . $from_receipt . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		/**
		 * Book reciept contents filter.
		 */
		$admin_email_template = apply_filters( 'wte_booking_reciept_email_content', $admin_email_template, $booking_id );

		if ( ! isset( $wp_travel_engine_settings['email']['disable_notif'] ) || $wp_travel_engine_settings['email']['disable_notif'] != '1' ) {
			if ( strpos( $wp_travel_engine_settings['email']['emails'], ',' ) !== false ) {
				$wp_travel_engine_settings['email']['emails'] = str_replace( ' ', '', $wp_travel_engine_settings['email']['emails'] );
				$admin_emails                                 = explode( ',', $wp_travel_engine_settings['email']['emails'] );
				foreach ( $admin_emails as $key => $value ) {
					$a = 1;
					wp_mail( $value, $subject_book, $admin_email_template, $headers_book, $attachments );
				}
			} else {
				$wp_travel_engine_settings['email']['emails'] = str_replace( ' ', '', $wp_travel_engine_settings['email']['emails'] );
				wp_mail( $wp_travel_engine_settings['email']['emails'], $subject_book, $admin_email_template, $headers_book, $attachments );
			}
		}
	}
}

class WTE_Booking_Emails extends WP_Travel_Engine_Emails {

	private $booking  = null;
	private $payment  = null;
	private $traveler = null;

	private $from = null;

	private $to = null;

	private $subject = null;

	private $settings = null;

	private $templates = array();

	private $content = null;

	public function __construct() {
		parent::__construct();

		$this->settings = wte_array_get( get_option( 'wp_travel_engine_settings', array() ), 'email' );

		$this->from['name']  = wte_array_get( $this->settings, 'name', get_bloginfo( 'name' ) );
		$this->from['email'] = wte_array_get( $this->settings, 'from', get_option( 'admin_email' ) );

	}

	public static function sample_template_preview( $email_template_type, $to ) {
		header( $_SERVER['SERVER_PROTOCOL'] . ' 200 OK' ); // phpcs:ignore
		wte_get_template( 'emails/email-header.php' );
		echo wp_kses_post( self::get_template_content( $email_template_type, '', $to ) );
		wte_get_template( 'emails/email-footer.php' );
		exit;
	}

	public static function template_preview( $payment_id, $email_template_type = 'order', $to = 'customer' ) {
		if ( ! defined( 'WTE_EMAIL_TEMPLATE_PREVIEW' ) ) {
			define( 'WTE_EMAIL_TEMPLATE_PREVIEW', ! 0 );
		}
		if ( +$payment_id === 0 ) {
			self::sample_template_preview( $email_template_type, $to );
		}
		$payment = get_post( $payment_id );
		if ( is_null( $payment ) || 'wte-payments' !== $payment->post_type ) {
			wp_die(
				new WP_Error(
					'WTE_ERROR',
					__( 'Invalid Payment ID.', 'wp-travel-engine' )
				)
			);
		}
		$booking_id = get_post_meta( $payment->ID, 'booking_id', ! 0 );
		$booking    = get_post( $booking_id );
		if ( is_null( $booking ) || 'booking' !== $booking->post_type ) {
			wp_die(
				new WP_Error(
					'WTE_ERROR',
					__( 'Invalid Booking ID.', 'wp-travel-engine' )
				)
			);
		}

		$object = new WTE_Booking_Emails();
		$object->prepare( $payment->ID, $email_template_type )->to( $to );
		$templates = array(
			'order'              => 'emails/booking/notification.php',
			'order_confirmation' => 'emails/booking/confirmation.php',
		);
		header( $_SERVER['SERVER_PROTOCOL'] . ' 200 OK' ); // phpcs:ignore
		echo $object->get_template( $templates[ $email_template_type ] );
		exit;
	}

	private function header() {
		$header       = 'MIME-Version: 1.0' . "\r\n";
		$charset      = apply_filters( 'wp_travel_engine_mail_charset', 'Content-type: text/html; charset=UTF-8' );
		$header      .= $charset . "\r\n";
		$from_receipt = "{$this->from['name']} <{$this->from['email']}>";
		$header      .= 'From:' . $from_receipt . "\r\n" .
			'Reply-To: ' . $from_receipt . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		return $header;
	}

	private function get_bank_details() {
		if ( $this->payment && 'direct_bank_transfer' === $this->payment->payment_gateway ) {
			$bank_details_labels = array(
				// 'account_name',
				'account_number' => __( 'Account Number', 'wp-travel-engine' ),
				'bank_name'      => __( 'Bank Name', 'wp-travel-engine' ),
				'sort_code'      => __( 'Sort Code', 'wp-travel-engine' ),
				'iban'           => __( 'IBAN', 'wp-travel-engine' ),
				'swift'          => __( 'BIC/Swift', 'wp-travel-engine' ),
			);

			$settings = get_option( 'wp_travel_engine_settings', array() );

			$bank_accounts = wte_array_get( $settings, 'bank_transfer.accounts', array() );
			ob_start();
			echo '<table class="invoice-items">';
			echo '<tr>';
			echo '<td colspan="2">';
			echo '<h3>' . esc_html__( 'Bank Details:', 'wp-travel-engine' ) . '</h3>';
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td colspan="2">';
			echo wp_kses_post( wte_array_get( $settings, 'bank_transfer.instruction', '' ) );
			echo '</td>';
			echo '</tr>';
			foreach ( $bank_accounts as $account ) {
				echo '<tr>';
				echo '<td colspan="2">';
				echo '<h5>' . esc_html( $account['account_name'] ) . '</h5>';
				echo '</td>';
				echo '</tr>';
				foreach ( $bank_details_labels as $key => $label ) {
					?>
					<tr>
						<td><?php echo esc_html( $label ); ?></td>
						<td class="alignright"><?php echo isset( $account[ $key ] ) ? esc_html( $account[ $key ] ) : ''; ?></td>
					</tr>
					<?php
				}
			}
			echo '</table>';
			return ob_get_clean();
		}
		return '';
	}

	private function get_check_payment_details() {
		if ( $this->payment && 'check_payments' === $this->payment->payment_gateway ) {
			ob_start();
			?>
			<table class="invoice-items">
				<tr>
					<td colspan="2">
						<h3><?php echo esc_html__( 'Check Payment Instructions:', 'wp-travel-engine' ); ?></h3>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php echo wp_kses_post( wte_array_get( get_option( 'wp_travel_engine_settings', array() ), 'check_payment.instruction', '' ) ); ?>
					</td>
				</tr>
			</table>
			<?php
			return ob_get_clean();
		}
		return '';
	}

	private function discount_amount() {
		$cart_info = (object) $this->booking->cart_info;

		if ( is_array( $cart_info->discounts ) ) {
			$discounts = $cart_info->discounts;
			$discount  = array_shift( $discounts );
			if ( ! is_array( $discount ) ) {
				return 0;
			}

			extract( $discount );
			if ( 'percentage' === $type ) {
				return +$cart_info->subtotal * ( +$value / 100 );
			}
			return $value;
		}
	}

	private function replace_content_tags( $content ) {
		// Mapping Mail tags with values.
		$template_tags = new Template_Tags( $this->booking->ID, $this->payment->ID );

		$default_mail_tags = $template_tags->get_email_tags();

		return str_replace( array_keys( $default_mail_tags ), $default_mail_tags, $content );
	}

	private function get_booking_details() {
		$order_trips = $this->booking->order_trips;
		$cart_info   = $this->booking->cart_info;

		$currency = $cart_info['currency'];
		if ( is_array( $order_trips ) ) :
			ob_start();
			$count              = 1;
			$pricing_categories = get_terms(
				array(
					'taxonomy'   => 'trip-packages-categories',
					'hide_empty' => false,
					'orderby'    => 'term_id',
					'fields'     => 'id=>name',
				)
			);
			foreach ( $order_trips as $trip ) :
				$trip = (object) $trip;
				?>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="2"><b><?php echo esc_html( $trip->title ); ?></b></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Trip Date', 'wp-travel-engine' ); ?></td>
						<td class="alignright"><?php echo $trip->has_time ? wp_date( 'Y-m-d H:i', strtotime( $trip->datetime ) ) : wp_date( get_option( 'date-format', 'Y-m-d' ), strtotime( $trip->datetime ) ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Travellers', 'wp-travel-engine' ); ?></td>
						<td class="alignright"><?php echo esc_html( array_sum( $trip->pax ) ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Trip Cost', 'wp-travel-engine' ); ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td class="alignright">
							<table width="100%" cellpadding="0" cellspacing="0">
								<?php
								$sum = 0;

								foreach ( $trip->pax as $pricing_category_id => $tcount ) {
									if ( +$tcount < 1 ) {
										continue;
									}
									$pax_cost = +$trip->pax_cost[ $pricing_category_id ] / +$tcount;
									$sum     += +$trip->pax_cost[ $pricing_category_id ];

									$label = isset( $pricing_categories[ $pricing_category_id ] ) ? $pricing_categories[ $pricing_category_id ] : $pricing_category_id;
									?>
									<tr>
										<td class="alignright"><?php echo esc_html( $label ); ?></td>
										<td><?php echo (int) $tcount . ' X ' . wte_esc_price( wte_get_formated_price( $pax_cost, $currency, '', ! 0 ) ) . ' = ' . wte_esc_price( wte_get_formated_price( $trip->pax_cost[ $pricing_category_id ], $currency, '', ! 0 ) ); ?></td>
									</tr>
									<?php
								}
								?>
								<tr>
									<td width="50%"><?php esc_html_e( 'Subtotal', 'wp-travel-engine' ); ?></td>
									<td width="50%"><?php echo wte_esc_price( wte_get_formated_price( +$sum, $currency, '', ! 0 ) ); ?></td>
								</tr>
							</table>
						</td>
					</tr>
						<?php if ( $trip->trip_extras && is_array( $trip->trip_extras ) ) : ?>
					<tr>
						<td colspan="2"><?php esc_html_e( 'Extra Services', 'wp-travel-engine' ); ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td class="alignright">
							<table width="100%" cellpadding="0" cellspacing="0">
								<?php
								$sum = 0;
								foreach ( $trip->trip_extras as $index => $tx ) {
									$tx_total = +$tx['qty'] * +$tx['price'];
									$sum     += $tx_total;
									?>
									<tr>
										<td><?php echo esc_html( $tx['extra_service'] ); ?></td>
										<td><?php echo (int) $tx['qty'] . ' X ' . wte_esc_price( wte_get_formated_price( +$tx['price'], $currency, '', ! 0 ) ) . ' = ' . wte_esc_price( wte_get_formated_price( +$tx_total, $currency, '', ! 0 ) ); ?></td>
									</tr>
									<?php
								}
								?>
								<tr>
									<td width="50%"><?php esc_html_e( 'Subtotal', 'wp-travel-engine' ); ?></td>
									<td widht="50%"><?php echo wte_esc_price( wte_get_formated_price( +$sum, $currency, '', ! 0 ) ); ?></td>
								</tr>
							</table>
						</td>
					</tr>
					<?php endif; ?>
				</table>
				<?php
				$count++;
				endforeach;
				echo '<hr/>';
			?>
			<table width="100%">
				<tr>
					<td width="50%">&nbsp;</td>
					<td width="50%">
						<table width="100%">
							<tr>
								<td><?php esc_html_e( 'Subtotal', 'wp-travel-engine' ); ?></td>
								<td class="alignright"><?php echo wte_esc_price( wte_get_formated_price( +$cart_info['subtotal'], $currency, '', ! 0 ) ); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'Discount', 'wp-travel-engine' ); ?></td>
								<?php
								$discount_figure = 0;
								if ( ! empty( $cart_info['discounts'] ) ) {
									$discounts       = $cart_info['discounts'];
									$discount        = array_shift( $discounts );
									$discount_figure = 'percentage' === $discount['type'] ? +$cart_info['subtotal'] * ( +$discount['value'] / 100 ) : $discount['value'];
								}
								$discount_amount = wte_get_formated_price( +$discount_figure, $currency, '', ! 0 );
								?>
								<td class="alignright"><?php echo $discount_amount; ?></td>
							</tr>

							<?php if ( ! empty( $cart_info['tax_amount'] ) ) {?>
							<tr>
								<td><?php echo sprintf( __( 'Tax (%s)', 'wp-travel-engine' ), $cart_info['tax_amount'] . '%' ); ?></td>
								<?php
								$tax_figure = 0;
								$tax_amount = wp_travel_engine_get_tax_detail($cart_info);
								$tax_amount_total = wte_get_formated_price( +$tax_amount['tax_actual'], $currency, '', ! 0 );
								?>
								<td class="alignright"><?php echo $tax_amount_total; ?></td>
							</tr>
							<?php }?>
							<tr>
								<td><?php esc_html_e( 'Total', 'wp-travel-engine' ); ?></td>
								<td class="alignright"><?php echo wte_esc_price( wte_get_formated_price( $cart_info['total'], $currency, '', ! 0 ) ); ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<?php
			endif;
			return ob_get_clean();
	}

	public static function get_string( $email_template_type = 'order', $sendto = 'customer', $for = 'heading' ) {
		$strings = self::get_strings( $email_template_type, $sendto );
		return wte_array_get( $strings, "{$for}", '' );
	}

	private static function get_strings( $email_template_type, $sentto ) {
		$strings = apply_filters(
			'wte_booking_mail_strings',
			array(
				'order'              => array(
					'admin'    => array(
						'heading'         => __( 'New Booking', 'wp-travel-engine' ),
						'greeting'        => __( 'Dear Admin,', 'wp-travel-engine' ),
						'greeting_byline' => __( 'A new booking has been made on your website. Booking details are listed below.', 'wp-travel-engine' ),
					),
					'customer' => array(
						'heading'         => __( 'Booking Confirmation', 'wp-travel-engine' ),
						'greeting'        => __( 'Dear {name},', 'wp-travel-engine' ),
						'greeting_byline' => __( 'You have successfully made the trip booking. Your booking information is listed below.', 'wp-travel-engine' ),
					),
				),
				'order_confirmation' => array(
					'admin'    => array(
						'heading'         => __( 'A Payment has been received for {booking_id}', 'wp-travel-engine' ),
						'greeting'        => __( 'Dear Admin,', 'wp-travel-engine' ),
						'greeting_byline' => __( 'A payment has been received for {booking_id}. The payment details are listed below.', 'wp-travel-engine' ),
					),
					'customer' => array(
						'heading'         => __( 'Your booking has been confirmed.', 'wp-travel-engine' ),
						'greeting'        => __( 'Dear {name},', 'wp-travel-engine' ),
						'greeting_byline' => __( 'Your booking has been confirmed. Your booking and payment information is listed below.', 'wp-travel-engine' ),
					),
				),
			)
		);

		if ( isset( $strings[ $email_template_type ][ $sentto ] ) ) {
			return $strings[ $email_template_type ][ $sentto ];
		}
		return array();
	}

	public static function get_template_content( $email_template_type = 'order', $template = '', $sendto = 'admin' ) {
		$settings  = get_option( 'wp_travel_engine_settings', array() );
		$templates = array(
			'order'              => array(
				'customer' => wte_array_get( $settings, 'email.booking_notification_template_customer', '' ),
				'admin'    => wte_array_get( $settings, 'email.booking_notification_template_admin', '' ),
			),
			'order_confirmation' => array(
				'customer' => wte_array_get( $settings, 'email.purchase_wpeditor', '' ),
				'admin'    => wte_array_get( $settings, 'email.sales_wpeditor', '' ),
			),
		);

		$content = empty( $templates[ $email_template_type ][ $sendto ] ) ? '' : $templates[ $email_template_type ][ $sendto ];

		if ( ! empty( $content ) ) {
			return $content;
		}
		if ( empty( $template ) ) {
			switch ( $email_template_type ) {
				case 'order':
					$template = 'emails/booking/notification.php';
					break;
				case 'order_confirmation':
					$template = 'emails/booking/confirmation.php';
					break;
				default:
					$template = 'emails/booking/notification.php';
					break;
			}
		}

		$args = array(
			'sent_to' => $sendto,
			'strings' => self::get_strings( $email_template_type, $sendto ),
		);
		ob_start();
		wte_get_template( $template, $args );
		return ob_get_clean();
	}

	public function get_email_content( $template ) {
		$content = self::get_template_content( $this->email_template_type, $template, $this->sendto );

		if ( ! defined( 'WTE_EMAIL_TEMPLATE_PREVIEW' ) || ! WTE_EMAIL_TEMPLATE_PREVIEW ) {
			// $content = apply_filters( 'wte_booking_reciept_email_content', $content, $this->booking->ID );
		}

		return $this->replace_content_tags( $content );

	}

	private function get_attachments() {
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

		return $attachments;
	}

	public static function get_subject( $email_template_type, $to ) {
		$options = array(
			'order_confirmation' => array(
				'admin'    => 'email.sale_subject',
				'customer' => 'email.subject',
			),
			'order'              => array(
				'admin'    => 'email.booking_notification_subject_admin',
				'customer' => 'email.booking_notification_subject_customer',
			),
		);
		$subject = wte_array_get( get_option( 'wp_travel_engine_settings', array() ), $options[ $email_template_type ][ $to ], '' );
		if ( ! empty( trim( $subject ) ) ) {
			return $subject;
		}
		$subjects = array(
			'order'              => array(
				'customer' => sprintf( __( 'Your Booking Order has been placed (%s)', 'wp-travel-engine' ), '{booking_id}' ),
				'admin'    => sprintf( __( 'New Booking Order has been received tests  (%s)', 'wp-travel-engine' ), '{booking_id}' ),
			),
			'order_confirmation' => array(
				'customer' => sprintf( __( 'Your payment has been confirmed for %1$s', 'wp-travel-engine' ), '{booking_id}' ),
				'admin'    => sprintf( __( 'Payment has been received for %1$s', 'wp-travel-engine' ), '{booking_id}' ),
			),
			'due_payment'        => array(
				'customer' => sprintf( __( 'Your due payment of booking %1$s has been confirmed', 'wp-travel-engine' ), '{booking_id}' ),
				'admin'    => sprintf( __( 'Due payment has been received for booking %1$s', 'wp-travel-engine' ), '{booking_id}' ),
			),
		);
		return isset( $subjects[ $email_template_type ][ $to ] ) ? $subjects[ $email_template_type ][ $to ] : '';
	}

	private function subject( $email_template_type = 'order' ) {
		$subject = self::get_subject( $email_template_type, $this->sendto );

		return str_replace( array( '{booking_id}', '{payment_id}' ), array( $this->booking->ID, $this->payment->ID ), $subject );
	}

	public function generate_email_template( $template ) {

		ob_start();
			// Get Email Header.
			wte_get_template( 'emails/email-header.php' );

			// Email Content.
			echo wp_kses_post( $this->get_email_content( $template ) );

			// Get email footer.
			wte_get_template( 'emails/email-footer.php' );
		return ob_get_clean();
	}

	public function template( $email_template_type = 'order' ) {
		$templates     = array(
			'order'              => 'emails/booking/notification.php',
			'order_confirmation' => 'emails/booking/confirmation.php',
		);
		$this->content = $this->get_template( $templates[ $email_template_type ] );
		return $this;
	}

	public function get_template( $template = 'emails/booking.php' ) {
		return $this->generate_email_template( $template );
	}

	public function send() {
		wp_mail( $this->to, $this->subject( $this->email_template_type ), is_null( $this->content ) ? $this->template( $this->email_template_type )->content : $this->content, $this->header(), $this->get_attachments() );
	}

	public function set_send_to( $to ) {
		$this->sendto = $to;
		return $this;
	}

	public function to( $to ) {
		if ( is_email( $to ) ) {
			$this->to = $to;
			return;
		}
		if ( in_array( $to, array( 'customer', 'admin' ) ) ) {
			switch ( $to ) {
				case 'admin':
					$this->to = str_replace( ' ', '', trim( wte_array_get( $this->settings, 'emails', '' ) ) );
					$this->set_send_to( 'admin' );
					break;

				case 'customer':
					$customer_info = $this->booking->billing_info;
					$this->to      = $customer_info['email'];
					$this->set_send_to( 'customer' );
					break;
			}
		}
		return $this;
	}

	public function prepare( $payment_id, $email_template_type = 'order' ) {
		$this->payment             = get_post( $payment_id );
		$booking_id                = get_post_meta( $this->payment->ID, 'booking_id', true );
		$this->booking             = get_post( $booking_id );
		$this->email_template_type = $email_template_type;
		return $this;
	}

}
