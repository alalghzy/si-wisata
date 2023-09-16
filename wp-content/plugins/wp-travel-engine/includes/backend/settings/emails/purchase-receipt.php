<?php
/**
 * Purchase Receipt template
 */
$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
?>
<div class="wpte-field wpte-text wpte-floated">
	<label class="wpte-field-label" for="wp_travel_engine_settings[email][name]"><?php esc_html_e( 'From Name', 'wp-travel-engine' ); ?></label>
		<input type="text" name="wp_travel_engine_settings[email][name]" id="wp_travel_engine_settings[email][name]"
		value="<?php echo ! empty( $wp_travel_engine_settings['email']['name'] ) ? esc_attr( $wp_travel_engine_settings['email']['name'] ) : esc_attr( get_bloginfo( 'name' ) ); ?>" />
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter the name the purchase receipts are sent from. This should probably be your site or shop name.', 'wp-travel-engine' ); ?></span>
</div>

<div class="wpte-field wpte-email wpte-floated">
	<label class="wpte-field-label" for="wp_travel_engine_settings[email][from]"><?php esc_html_e( 'From Email', 'wp-travel-engine' ); ?></label>
		<input type="text" name="wp_travel_engine_settings[email][from]" id="wp_travel_engine_settings[email][from]"
		value="<?php echo ! empty( $wp_travel_engine_settings['email']['from'] ) ? esc_attr( $wp_travel_engine_settings['email']['from'] ) : esc_attr( get_option( 'admin_email' ) ); ?>" />
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter the mail address from which the purchase receipts will be sent. This will act as as the from and reply-to address.', 'wp-travel-engine' ); ?></span>
</div>
<?php
$subject = WTE_Booking_Emails::get_subject( 'order', 'customer' );
?>
<div class="wpte-field wpte-text wpte-floated">
	<label class="wpte-field-label" data-wte-update="wte_new_430" for="wp_travel_engine_settings[email][booking_notification_subject_customer]"><?php esc_html_e( 'Booking Email Subject', 'wp-travel-engine' ); ?></label>
		<input type="text" name="wp_travel_engine_settings[email][booking_notification_subject_customer]" id="wp_travel_engine_settings[email][booking_notification_subject_customer]"
		value="<?php echo esc_attr( $subject ); ?>" />
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter the subject line for the purchase receipt email.', 'wp-travel-engine' ); ?></span>
</div>
<div class="wpte-field wpte-textarea wpte-floated">
	<label class="wpte-field-label" data-wte-update="wte_new_430" for="booking_notification_template_customer"><?php esc_html_e( 'Booking Notification', 'wp-travel-engine' ); ?></label>
		<?php
		$value_wysiwyg = WTE_Booking_Emails::get_template_content( 'order', 'emails/booking/notification.php', 'customer' ); // $email_class->get_email_template( 'booking', 'admin', true );

		$editor_id = 'booking_notification_template_customer';
		$settings  = array(
			'media_buttons' => true,
			'textarea_name' => 'wp_travel_engine_settings[email][' . $editor_id . ']',
		);
		?>
		<div class="wpte-field wpte-textarea wpte-floated wpte-rich-textarea delay">
			<textarea
				placeholder="<?php esc_attr_e( 'Email Message', 'wp-travel-engine' ); ?>"
				name="wp_travel_engine_settings[email][<?php echo esc_attr( $editor_id ); ?>]"
				class="wte-editor-area" id="<?php echo esc_attr( $editor_id ); ?>"><?php echo wp_kses_post( $value_wysiwyg ); ?></textarea>
		</div>
		<span class="wpte-tooltip"><?php esc_html_e( 'This template will be used when a booking request has been made by the customer.', 'wp-travel-engine' ); ?> <a class="wte-email-template-preview-link" href="<?php echo esc_url( home_url( '/' ) ); ?>?_action=email-template-preview&template_type=order&pid=0&to=customer"><?php esc_html_e( 'Preview Template', 'wp-travel-engine' ); ?></a></span>

</div>
<?php
$subject = WTE_Booking_Emails::get_subject( 'order_confirmation', 'customer' );
?>

<div class="wpte-field wpte-text wpte-floated">
	<label class="wpte-field-label" for="wp_travel_engine_settings[email][subject]"><?php esc_html_e( 'Purchase Email Subject', 'wp-travel-engine' ); ?></label>
		<input type="text" name="wp_travel_engine_settings[email][subject]" id="wp_travel_engine_settings[email][subject]"
		value="<?php echo esc_attr( $subject ); ?>" />
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter the subject line for the booking notification email.', 'wp-travel-engine' ); ?></span>
</div>
<div class="wpte-field wpte-textarea wpte-floated">
	<label class="wpte-field-label" data-wte-update="wte_updated_430" for="purchase_wpeditor"><?php esc_html_e( 'Purchase Receipt', 'wp-travel-engine' ); ?></label>
		<?php
		$value_wysiwyg = WTE_Booking_Emails::get_template_content( 'order_confirmation', '', 'customer' ); // $email_class->get_email_template( 'booking', 'admin', true );
		$editor_id     = 'purchase_wpeditor';
		$settings      = array(
			'media_buttons' => true,
			'textarea_name' => 'wp_travel_engine_settings[email][' . $editor_id . ']',
		);
		?>
		<div class="wpte-field wpte-textarea wpte-floated wpte-rich-textarea delay">
			<textarea
				placeholder="<?php esc_attr_e( 'Email Message', 'wp-travel-engine' ); ?>"
				name="wp_travel_engine_settings[email][<?php echo esc_attr( $editor_id ); ?>]"
				class="wte-editor-area wp-editor-area" id="<?php echo esc_attr( $editor_id ); ?>"><?php echo wp_kses_post( $value_wysiwyg ); ?></textarea>
		</div>
		<span class="wpte-tooltip"><?php esc_html_e( 'This email template will be used when ever a payment received.', 'wp-travel-engine' ); ?><a class="wte-email-template-preview-link" href="<?php echo esc_url( home_url( '/' ) ); ?>?_action=email-template-preview&template_type=order_confirmation&pid=0&to=customer"><?php esc_html_e( 'Preview Template', 'wp-travel-engine' ); ?></a></span>
		<?php if ( version_compare( get_option( 'payment_notification_customer_version', '1.0.0' ), '2.0.0', '<' ) ) : ?>
		<div style="margin-left: 145px;" class="wpte-info-block _wte_update_notice_430">
			<form action="" method="POST" id="wte-payment-notification-customer">
				<input type="hidden" name="_action" value="wte-email-template-update"/>
				<input type="hidden" name="field" value="email.purchase_wpeditor"/>
			</form>
			<b><?php esc_html_e( 'Note:', 'wp-travel-engine' ); ?></b>
			<p>
			<?php
			echo wp_kses(
				sprintf( __( 'This is the default template from previous WP Travel Engine versions. The template has been updated since %1$sv4.3.0%2$s. %3$sClick here%4$s to update', 'wp-travel-engine' ), '<code>', '</code>', '<a href="#" data-target="wte-payment-notification-customer" class="wte-email-template-updater">', '</a>' ),
				array(
					'code' => array(),
					'a'    => array(
						'href'        => array(),
						'class'       => array(),
						'data-target' => array(),
					),
				)
			);
			?>
			</p>
		</div>
		<?php endif; ?>
</div>

<div class="wpte-field wpte-tags">
	<p><?php esc_html_e( 'Enter the text that is sent as purchase receipt email to users after completion of a successful purchase. HTML is accepted.', 'wp-travel-engine' ); ?></p>
	<p><b><?php esc_html_e( 'Available Template Tags', 'wp-travel-engine' ); ?>-</b></p>
	<ul class="wpte-list">
		<li>
			<b>{trip_url}</b>
			<span><?php esc_html_e( 'The trip URL for each booked trip', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{name}</b>
			<span><?php esc_html_e( 'The buyer\'s first name', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{fullname}</b>
			<span><?php esc_html_e( 'The buyer\'s full name, first and last', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{user_email}</b>
			<span><?php esc_html_e( 'The buyer\'s email address', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{billing_address}</b>
			<span><?php esc_html_e( 'The buyer\'s billing address', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{city}</b>
			<span><?php esc_html_e( 'The buyer\'s city', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{country}</b>
			<span><?php esc_html_e( 'The buyer\'s country', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{tdate}</b>
			<span><?php esc_html_e( 'The starting date of the trip', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{date}</b>
			<span><?php esc_html_e( 'The trip booking date', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{traveler}</b>
			<span><?php esc_html_e( 'The total number of traveller(s)', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{child-traveler}</b>
			<span><?php esc_html_e( 'The total number of child traveller(s)', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{tprice}</b>
			<span><?php esc_html_e( 'The trip price', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{price}</b>
			<span><?php esc_html_e( 'The total payment made of the booking', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{total_cost}</b>
			<span><?php esc_html_e( 'The total price of the booking', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{due}</b>
			<span><?php esc_html_e( 'The due balance', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{sitename}</b>
			<span><?php esc_html_e( 'Your site name', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{booking_url}</b>
			<span><?php esc_html_e( 'The trip booking link', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{ip_address}</b>
			<span><?php esc_html_e( 'The buyer\'s IP Address', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{booking_id}</b>
			<span><?php esc_html_e( 'The booking order ID', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{bank_details}</b>
			<span><?php esc_html_e( 'Banks Accounts Details. This tag will be replaced with the bank details and sent to the customer receipt email when Bank Transfer method has chosen by the customer.', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{check_payment_instruction}</b>
			<span><?php esc_html_e( 'Instructions to make check payment.', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{booking_details}</b>
			<span><?php esc_html_e( 'The booking details: Booked trips, Extra Services, Traveller details etc', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{traveler_data}</b>
			<span><?php esc_html_e( 'The traveller details: Traveller details and Emergency Contact Details', 'wp-travel-engine' ); ?></span>
		</li>
	</ul>
	<?php
		/**
		 * Hook to add additional e-mail tags by addons.
		 */
		do_action( 'wte_additional_payment_email_tags' );
	?>
</div>
