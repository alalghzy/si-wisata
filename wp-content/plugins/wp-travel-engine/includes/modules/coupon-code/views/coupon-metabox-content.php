<?php
use WPTravelEngine\Modules\CouponCode;
/**
 * Coupons General Tab Contents
 *
 * @package WP Travel Engine Coupons
 */
global $post;
wp_enqueue_script( 'wte-edit--coupon' );

// General Tab Data.
$coupon_metas = get_post_meta( $post->ID, 'wp_travel_engine_coupon_metas', true );
$general_tab  = isset( $coupon_metas['general'] ) ? $coupon_metas['general'] : array();
$coupon_code  = get_post_meta( $post->ID, 'wp_travel_engine_coupon_code', true );

// Field Values.
$coupon_active = isset( $general_tab['coupon_active'] ) ? $general_tab['coupon_active'] : 'yes';
$coupon_code   = ! empty( $coupon_code ) ? $coupon_code : '';
$coupon_type   = isset( $general_tab['coupon_type'] ) ? $general_tab['coupon_type'] : 'fixed';
$coupon_value  = isset( $general_tab['coupon_value'] ) ? $general_tab['coupon_value'] : '';

$date_format = get_option( 'date_format' );

$coupon_expiry_date = isset( $general_tab['coupon_expiry_date'] ) ? $general_tab['coupon_expiry_date'] : '';

try {
	$coupon_expiry_date = ! empty( $general_tab['coupon_expiry_date'] ) ? ( new \DateTime( $general_tab['coupon_expiry_date'] ) )->format( 'Y-m-d' ) : '';
} catch ( \Exception $e ) {
	$coupon_expiry_date = '';
}
try {
	$coupon_start_date = isset( $general_tab['coupon_start_date'] ) ? ( new \DateTime( $general_tab['coupon_start_date'] ) )->format( 'Y-m-d' ) : gmdate( 'Y-m-d' );
} catch ( \Exception $e ) {
	$coupon_start_date = gmdate( 'Y-m-d' );
}

$coupon_id = CouponCode::coupon_id_by_code( $coupon_code );

$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );

$code = ! empty( $wp_travel_engine_settings['currency_code'] ) ? $wp_travel_engine_settings['currency_code'] : 'USD';

$obj      = new \Wp_Travel_Engine_Functions();
$currency = $obj->wp_travel_engine_currencies_symbol( $code );
?>
<div class="wpte-block-content"><!-- Start .wpte-block-content -->
	<div class="wpte-form-block">
		<?php if ( $coupon_id ) : ?>
		<div class="wpte-field wpte-floated departure-dates-options">
			<label
				class="wpte-field-label"
				for="currency"
			>
				<?php esc_html_e( 'Coupon Status ', 'wp-travel-engine' ); ?>
			</label>
			<?php
			$coupon_status = CouponCode::get_coupon_status( $coupon_id );
			if ( 'active' === $coupon_status ) {
				?>
				<span class="wp-travel-engine-info-msg">
					<?php echo esc_html( 'Active', 'wp-travel-engine-coupons' ); ?>
				</span>
				<?php
			} else {
				?>
				<span class="wp-travel-engine-error-msg">
					<?php echo esc_html( 'Inactive', 'wp-travel-engine-coupons' ); ?>
				</span>
				<?php
			}
			?>
			<span
				class="wpte-tooltip"><?php _e( 'Either the coupon is enabled in site or not.', 'wp-travel-engine' ); ?></span>
		</div>
		<?php endif; ?>
		<div class="wpte-field wpte-floated departure-dates-options">
			<label
				class="wpte-field-label"
				for="coupon-code"
			><?php esc_html_e( 'Coupon Code', 'wp-travel-engine' ); ?></label>
			<input
				required="required"
				type="text"
				id="coupon-code"
				name="wp_travel_engine_coupon_code"
				placeholder="<?php echo esc_attr__( 'WP-TRAVEL-ENGINE-SALE', 'wp-travel-engine' ); ?>"
				value="<?php echo esc_attr( $coupon_code ); ?>"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_travel_engine_check_coupon_code' ) ); ?>"
			>
			<input
				id="wp-travel-coupon-id"
				type="hidden"
				value="<?php echo esc_attr( $coupon_id ); ?>"
			>
			<span
				class="wpte-tooltip"><?php esc_html_e( 'Unique Identifier for the coupon.', 'wp-travel-engine' ); ?></span>
			<span
				class="wpte-tooltip wp-travel-coupon_code-error wp-travel-error"
				style="display:none;"
			><strong><?php echo esc_html( 'Warning :', 'wp-travel-engine-coupons' ); ?></strong><?php esc_html_e( ' Coupon Code already in use. Multiple coupouns with same code results to only latest coupon settings being applied.', 'wp-travel-engine' ); ?></span>
		</div>

		<div class="wpte-field wpte-floated wpte-select departure-dates-options">
			<label
				class="wpte-field-label"
				for="coupon-type"
			><?php esc_html_e( 'Discount Type', 'wp-travel-engine' ); ?></label>
			<select
				class="wpte-enhanced-select wte-coupon-code-type"
				id="coupon-type"
				name="wp_travel_engine_coupon[general][coupon_type]"
			>
				<option
					value="fixed"
					<?php selected( $coupon_type, 'fixed' ); ?>
				><?php esc_html_e( 'Fixed', 'wp-travel-engine' ); ?></option>
				<option
					value="percentage"
					<?php selected( $coupon_type, 'percentage' ); ?>
				><?php esc_html_e( 'Percentage', 'wp-travel-engine' ); ?></option>
			</select>
			<span
				class="wpte-tooltip"><?php esc_html_e( 'Coupon Type: Fixed Discount Amount or Percentage discount( Applies to cart total price ).', 'wp-travel-engine' ); ?></span>

		</div>

		<div class="wpte-field wpte-number wpte-floated departure-dates-options">
			<label
				class="wpte-field-label"
				for="coupon-code"
			><?php esc_html_e( 'Discount Value', 'wp-travel-engine' ); ?></label>
			<div class="wpte-floated">
				<input
					required="required"
					type="number"
					min="1"
					<?php echo 'percentage' === $coupon_type ? 'max="100"' : ''; ?>
					step="0.01"
					id="coupon-value"
					name="wp_travel_engine_coupon[general][coupon_value]"
					placeholder="<?php echo esc_attr__( 'Discount Value', 'wp-travel-engine' ); ?>"
					value="<?php echo esc_attr( $coupon_value ); ?>"
				>
				<span
					<?php echo 'percentage' === $coupon_type ? 'style="display:none;"' : ''; ?>
					id="coupon-currency-symbol"
					class="wpte-sublabel"
				>
					<?php echo esc_html( $currency ); ?>
				</span>
				<span
					<?php echo 'fixed' === $coupon_type ? 'style="display:none;"' : ''; ?>
					id="coupon-percentage-symbol"
					class="wpte-sublabel"
				>
					<?php echo '%'; ?>
				</span>
			</div>
			<span
				class="wpte-tooltip"><?php esc_html_e( 'Coupon value amount/percentage.', 'wp-travel-engine' ); ?></span>
		</div>
		<div class="wpte-field wpte-floated departure-dates-options">
			<label
				class="wpte-field-label"
				for="coupon-start-date"
			><?php esc_html_e( 'Coupon Start Date', 'wp-travel-engine' ); ?></label>
			<input
				type="text"
				class="wte-datepicker"
				id="coupon-start-date"
				name="wp_travel_engine_coupon[general][coupon_start_date]"
				value="<?php echo esc_attr( $coupon_start_date ); ?>"
			>
			<span
				class="wpte-tooltip"><?php esc_html_e( 'Coupon start date. Defaults to coupon creation date.', 'wp-travel-engine' ); ?></span>
		</div>
		<div class="wpte-field wpte-floated departure-dates-options">
			<label
				class="wpte-field-label"
				for="coupon-expiry-date"
			><?php esc_html_e( 'Coupon Expiry Date', 'wp-travel-engine' ); ?></label>
			<input
				type="text"
				class="wte-datepicker"
				id="coupon-expiry-date"
				name="wp_travel_engine_coupon[general][coupon_expiry_date]"
				value="<?php echo esc_attr( $coupon_expiry_date ); ?>"
			>
			<span
				class="wpte-tooltip"><?php esc_html_e( 'Coupon expiration date. Leave blank to disable expiration.', 'wp-travel-engine' ); ?></span>
		</div>
		<?php
			// Get Restrictions Tab Data.
			$restrictions_tab = isset( $coupon_metas['restriction'] ) ? $coupon_metas['restriction'] : array();

			// Field Values.
			$restricted_trips    = isset( $restrictions_tab['restricted_trips'] ) ? $restrictions_tab['restricted_trips'] : array();
			$coupon_limit_number = isset( $restrictions_tab['coupon_limit_number'] ) ? $restrictions_tab['coupon_limit_number'] : '';
		?>
		<div class="wpte-field wpte-floated wpte-select">
			<label
				class="wpte-field-label"
				for="wp_travel_engine_coupon[restriction][restricted_trips][]"
			><?php echo esc_html( 'Allow Coupon Use For', 'wp-travel-coupon-pro' ); ?></label>
			<?php
					$trips                = wp_travel_engine_get_trips_array();
					$count_options_data   = count( $restricted_trips );
					$count_trips          = count( $trips );
					$multiple_checked_all = '';

			if ( $count_options_data == $count_trips ) {
				$multiple_checked_all = 'checked=checked';
			}

					$multiple_checked_text = __( 'Select multiple', 'wp-travel-engine' );
			if ( $count_trips > 0 ) {
				$multiple_checked_text = $count_options_data . __( ' item selected', 'wp-travel-engine' );
			}
				// echo esc_html( $multiple_checked_text );
			?>
				<select
					multiple
					class="wp-travel-engine-multi-inner wpte-enhanced-select"
					name="wp_travel_engine_coupon[restriction][restricted_trips][]"
				>
					<?php
					foreach ( $trips as $key => $iti ) {
						$checked            = '';
						$selecte_list_class = '';
						if ( in_array( $key, $restricted_trips ) ) {

							$checked            = 'selected=selected';
							$selecte_list_class = 'selected';
						}
						?>
					<option
						value="<?php echo esc_attr( $key ); ?>"
						<?php echo esc_attr( $checked ); ?>
					><?php echo esc_html( $iti ); ?></option>
					<?php } ?>
				</select>
				<span
					class="wpte-tooltip"><?php esc_html_e( 'Choose to apply coupons to certain trips only. Select none to apply to all trips', 'wp-travel-engine' ); ?></span>

		</div>
		<div class="wpte-field wpte-floated departure-dates-options">
			<label
				class="wpte-field-label"
				for="coupon-limit"
			><?php esc_html_e( 'Coupon Usage Limit', 'wp-travel-engine' ); ?></label>
			<input
				type="number"
				step="1"
				min="0"
				id="coupon-limit"
				name="wp_travel_engine_coupon[restriction][coupon_limit_number]"
				value="<?php echo esc_attr( $coupon_limit_number ); ?>"
			>
			<span
				class="wpte-tooltip"><?php echo esc_attr( 'No. of times coupon can be used before being obsolute.', 'wp-travel-engine-coupons' ); ?></span>
		</div>
	</div>
</div><!-- End .wpte-block-content -->
