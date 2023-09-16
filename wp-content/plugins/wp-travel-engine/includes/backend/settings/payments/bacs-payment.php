<?php
/**
 * Direct Bank Transfer Settings.
 */
$settings      = get_option( 'wp_travel_engine_settings', array() );
$bank_transfer = isset( $settings['bank_transfer'] ) ? $settings['bank_transfer'] : array();
$label         = ! empty( $bank_transfer['title'] ) ? $bank_transfer['title'] : __( 'Bank Transfer', 'wp-travel-engine' );
$description   = ! empty( $bank_transfer['description'] ) ? $bank_transfer['description'] : __( 'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.', 'wp-travel-engine' );
$instruction   = ! empty( $bank_transfer['instruction'] ) ? $bank_transfer['instruction'] : __( 'Please make your payment on the provided bank accounts.', 'wp-travel-engine' );
$accounts      = ! empty( $bank_transfer['accounts'] ) && is_array( $bank_transfer['accounts'] ) ? $bank_transfer['accounts'] : array();
?>
<div class="wpte-field wpte-floated">
	<label
		class="wpte-field-label"
		for="wp_travel_engine_settings[bank_transfer][title]"><?php esc_html_e( 'Title', 'wp-travel-engine' ); ?></label>
	<input
		type="text"
		id="wp_travel_engine_settings[bank_transfer]"
		name="wp_travel_engine_settings[bank_transfer][title]"
		value="<?php echo esc_attr( $label ); ?>" />
	<span
		class="wpte-tooltip"><?php esc_html_e( 'The title which the user see during checkout.', 'wp-travel-engine' ); ?></span>
</div>
<div class="wpte-field wpte-floated">
	<label
		class="wpte-field-label"
		for="wp_travel_engine_settings[bank_transfer][description]"><?php esc_html_e( 'Description', 'wp-travel-engine' ); ?></label>
	<textarea
		type="text"
		id="wp_travel_engine_settings[bank_transfer][description]"
		name="wp_travel_engine_settings[bank_transfer][description]"><?php echo wp_kses( $description, 'wte_formats' ); ?></textarea>
	<span class="wpte-tooltip"><?php esc_html_e( 'Payment method description.', 'wp-travel-engine' ); ?></span>
</div>
<div class="wpte-field wpte-floated">
	<label
		class="wpte-field-label"
		for="wp_travel_engine_settings[bank_transfer][instruction]"><?php esc_html_e( 'Instructions', 'wp-travel-engine' ); ?></label>
	<textarea
		type="text"
		id="wp_travel_engine_settings[bank_transfer][instruction]"
		name="wp_travel_engine_settings[bank_transfer][instruction]"><?php echo wp_kses( $instruction, 'wte_formats' ); ?></textarea>
	<span
		class="wpte-tooltip"><?php esc_html_e( 'Instructions to the user, displays on the thankyou page and email.', 'wp-travel-engine' ); ?></span>
</div>
<div class="wpte-field wpte-floated">
	<label
		class="wpte-field-label"
		for="wp_travel_engine_settings[bank_transfer][accounts]"><?php esc_html_e( 'Account Details', 'wp-travel-engine' ); ?></label>
	<table id="wte-bank-transfer-accounts-table">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th><?php esc_html_e( 'Account Name', 'wp-travel-engine' ); ?></th>
				<th><?php esc_html_e( 'Account Number', 'wp-travel-engine' ); ?></th>
				<th><?php esc_html_e( 'Bank Name', 'wp-travel-engine' ); ?></th>
				<th><?php esc_html_e( 'Sort code', 'wp-travel-engine' ); ?></th>
				<th><?php esc_html_e( 'IBAN', 'wp-travel-engine' ); ?></th>
				<th><?php esc_html_e( 'BIC/Swift', 'wp-travel-engine' ); ?></th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $accounts as $index => $account ) :
				?>
			<tr>
				<td></td>
				<td>
					<input
						type="text"
						name="wp_travel_engine_settings[bank_transfer][accounts][<?php echo (int) $index; ?>][account_name]"
						value="<?php echo esc_attr( $account['account_name'] ); ?>" />
				</td>
				<td>
					<input
						type="text"
						name="wp_travel_engine_settings[bank_transfer][accounts][<?php echo (int) $index; ?>][account_number]"
						value="<?php echo esc_attr( $account['account_number'] ); ?>" />
				</td>
				<td>
					<input
						type="text"
						name="wp_travel_engine_settings[bank_transfer][accounts][<?php echo (int) $index; ?>][bank_name]"
						value="<?php echo esc_attr( $account['bank_name'] ); ?>" />
				</td>
				<td>
					<input
						type="text"
						name="wp_travel_engine_settings[bank_transfer][accounts][<?php echo (int) $index; ?>][sort_code]"
						value="<?php echo esc_attr( $account['sort_code'] ); ?>" />
				</td>
				<td>
					<input
						type="text"
						name="wp_travel_engine_settings[bank_transfer][accounts][<?php echo (int) $index; ?>][iban]"
						value="<?php echo esc_attr( $account['iban'] ); ?>" />
				</td>
				<td>
					<input
						type="text"
						name="wp_travel_engine_settings[bank_transfer][accounts][<?php echo (int) $index; ?>][swift]"
						value="<?php echo esc_attr( $account['swift'] ); ?>" />
				</td>
				<td><button class="wpte-btn wpte-danger wpte-remove-account">X</button></td>
			</tr>
				<?php
				endforeach;
			?>
		</tbody>
	</table>
	<div class="wpte-accounts-actions">
		<button class="wpte-btn wpte-primary wpte-add-account"><?php esc_htmL_e( '+ Add Account', 'wp-travel-engine' ); ?></button>
	</div>
</div>
