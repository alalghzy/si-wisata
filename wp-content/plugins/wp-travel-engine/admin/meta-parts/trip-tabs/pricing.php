<?php
/**
 * Admin pricing Tab content - Trip Meta
 *
 * @package Wp_Travel_Engine/Admin/Meta_parts
 */
global $post;
global $wtetrip;
// Get post ID.
if ( ! is_object( $post ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	$post_id  = $args['post_id'];
	$next_tab = $args['next_tab'];
	$trip     = wte_get_trip( $post_id );
} else {
	$post_id = $post->ID;
	$trip    = $wtetrip;
}
/**
 * Pricing Options Settings.
 */
$wte_trip_settings = get_post_meta( $post_id, 'wp_travel_engine_setting', true );
// default options.
$default_pricing_options   = apply_filters(
	'wte_default_pricing_options',
	array(
		'adult'  => esc_html__( 'Adult', 'wp-travel-engine' ),
		'child'  => esc_html__( 'Child', 'wp-travel-engine' ),
		'infant' => esc_html__( 'Infant', 'wp-travel-engine' ),
		'group'  => esc_html__( 'Group', 'wp-travel-engine' ),
	)
);
	$saved_pricing_options = isset( $wte_trip_settings['multiple_pricing'] ) && ! empty( $wte_trip_settings['multiple_pricing'] ) ? $wte_trip_settings['multiple_pricing'] : $default_pricing_options;
?>
<?php
if ( $trip && version_compare( $trip->trip_version, '2.0.0', '<' ) ) {
	?>
<div class="wpte-info-block">
	<p>
		<?php
		$migrate_url = add_query_arg(
			array(
				'wte-action' => 'trip-migration-500',
				'trip'       => $post_id,
			),
			admin_url( 'index.php' )
		);
		?>
		<a href="<?php echo esc_url( $migrate_url ); ?>"><?php echo esc_html__( 'Migrate trip', 'wp-travel-engine' ); ?></a>
	</p>
</div>
	<?php
}
?>
<div class="wpte-multi-pricing-wrap">
	<?php
		// Pricing Loop Start.
	foreach ( $saved_pricing_options as $option => $label ) :

		$trip_prev_price  = '';
		$trip_sale_price  = '';
		$trip_sale_enable = false;
		$bind             = '';
		$bind_sale        = '';

		if ( 'adult' === $option && ! isset( $wte_trip_settings['multiple_pricing'] ) ) :

			$trip_prev_price  = isset( $wte_trip_settings['trip_prev_price'] ) && ! empty( $wte_trip_settings['trip_prev_price'] ) ? $wte_trip_settings['trip_prev_price'] : '';
			$trip_sale_enable = isset( $wte_trip_settings['sale'] ) && '1' === $wte_trip_settings['sale'] ? true : false;
			$trip_sale_price  = isset( $wte_trip_settings['trip_price'] ) && ! empty( $wte_trip_settings['trip_price'] ) ? $wte_trip_settings['trip_price'] : '';

			endif;

		if ( 'child' === $option && ! isset( $wte_trip_settings['multiple_pricing'] ) ) :

			$trip_prev_price = apply_filters( 'wte_apply_group_discount_default', $trip_prev_price );

			endif;

		$pricing_option_label = isset( $wte_trip_settings['multiple_pricing'][ esc_attr( $option ) ]['label'] ) ? $wte_trip_settings['multiple_pricing'][ esc_attr( $option ) ]['label'] : ucfirst( $option );

		// $price =  $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['price'];

		$pricing_option_type = isset( $wte_trip_settings['multiple_pricing'][ esc_attr( $option ) ]['price_type'] ) ? $wte_trip_settings['multiple_pricing'][ esc_attr( $option ) ]['price_type'] : 'per-person';

		$pricing_option_price = isset( $wte_trip_settings['multiple_pricing'][ esc_attr( $option ) ]['price'] ) ? $wte_trip_settings['multiple_pricing'][ esc_attr( $option ) ]['price'] : $trip_prev_price;

		$pricing_option_sale_price = isset( $wte_trip_settings['multiple_pricing'][ esc_attr( $option ) ]['sale_price'] ) ? $wte_trip_settings['multiple_pricing'][ esc_attr( $option ) ]['sale_price'] : $trip_sale_price;

		$enable_sale_option = isset( $wte_trip_settings['multiple_pricing'][ esc_attr( $option ) ]['enable_sale'] ) && '1' === $wte_trip_settings['multiple_pricing'][ esc_attr( $option ) ]['enable_sale'] ? true : $trip_sale_enable;

		$sale_display = $enable_sale_option ? true : false;

		if ( 'adult' === $option ) :
			$bind      = 'bind="wpte-trip-mn-price"';
			$bind_sale = 'bindSale="wpte-trip-mn-sale-price"';
			?>
	<input id="wpte-trip-default-pper" type="hidden" name="wp_travel_engine_setting[trip_price_per]" value="<?php echo esc_attr( $pricing_option_type ); ?>">
	<input <?php echo esc_attr( $bind ); ?> type="hidden" name="wp_travel_engine_setting[trip_prev_price]" value="<?php echo esc_attr( $pricing_option_price ); ?>">
	<input <?php echo esc_attr( $bind_sale ); ?> type="hidden" name="wp_travel_engine_setting[trip_price]" value="<?php echo esc_attr( $pricing_option_sale_price ); ?>">
	<input
		type="checkbox"
		style="display:none"
		id="wpte-trip-enb-sale-price"
		name="wp_travel_engine_setting[sale]"
		value="1"
			<?php checked( $enable_sale_option, true ); ?> />
			<?php
			endif;
		?>
	<div class="wpte-field wpte-multi-fields">
		<div class="wpte-field wpte-floated">
			<label for="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][label]" class="wpte-field-label">
			<?php
			$mp_label = ucfirst( $option );
			echo esc_html( sprintf( __( '%1$s Label', 'wp-travel-engine' ), $mp_label ) );
			?>
			</label>
			<div class="wpte-floated">
				<input required type="text" name="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][label]" id="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][label]"
					value="<?php echo esc_attr( $pricing_option_label ); ?>"
					placeholder="<?php esc_attr_e( 'Pricing option name', 'wp-travel-engine' ); ?>" />
			</div>
			<span class="wpte-tooltip"><?php echo esc_html( sprintf( __( 'The label for %1$s pricing option. This label will be displayed on the traveller selection on the booking form.', 'wp-travel-engine' ), $mp_label ) ); ?></span>
		</div>
		<div class="wpte-field wpte-number wpte-floated">
			<label class="wpte-field-label">
				<?php
							$mp_label = ucfirst( $option );
							echo esc_html( sprintf( __( '%1$s Price', 'wp-travel-engine' ), $mp_label ) );
				?>
			</label>
			<div class="wpte-floated">
				<input type="number" name="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][price]"
					id="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][price]"
					<?php echo esc_attr( $bind ); ?>
					value="<?php echo esc_attr( $pricing_option_price ); ?>"
					placeholder="<?php esc_attr_e( 'Regular price', 'wp-travel-engine' ); ?>" />
				<span class="wpte-sublabel"><?php echo esc_html( wp_travel_engine_get_currency_code() ); ?></span>
			</div>
			<span class="wpte-tooltip"><?php echo esc_html( sprintf( __( 'Enter the regular price for the %1$s pricing option. The price will be applied as base price for %2$s.', 'wp-travel-engine' ), $mp_label, $mp_label ) ); ?></span>
		</div>
		<div class="wpte-onoff-block wpte-floated">
			<a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $sale_display ? 'active' : ''; ?>">
				<label for="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][enable_sale]" class="wpte-field-label"><?php echo esc_html( 'Enable Sale Price' ); ?><span class="wpte-onoff-btn"></span></label>
			</a>
			<input
				type="checkbox"
				class="wp-travel-engine-setting-enable-pricing-sale"
				id="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][enable_sale]"
				name="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][enable_sale]"
				value="1"
				<?php checked( $enable_sale_option, true ); ?> />
			<div <?php echo $sale_display ? 'style="display:block;"' : ''; ?> class="wpte-onoff-popup">
				<div class="wpte-field wpte-number">
					<div class="wpte-floated">
						<input <?php echo esc_attr( $bind_sale ); ?> type="number" name="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][sale_price]"
							id="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][sale_price]"
							value="<?php echo esc_attr( $pricing_option_sale_price ); ?>"
							placeholder="<?php esc_attr_e( 'Sale price', 'wp-travel-engine' ); ?>" />
						<span class="wpte-sublabel"><?php echo esc_html( wp_travel_engine_get_currency_code() ); ?></span>
					</div>
				</div>
			</div>
			<span class="wpte-tooltip"><?php echo esc_html__( 'Enable sale price for this pricing option.', 'wp-travel-engine' ); ?></span>
		</div>
		<?php if ( 'group' !== $option ) : ?>
		<div style="margin-top:20px;" class="wpte-field wpte-floated">
			<label for="wpte-adult-price-pertype-sel" class="wpte-field-label"><?php echo esc_html__( 'Pricing Type', 'wp-travel-engine' ); ?></label>
			<div class="wpte-floated">
				<select id="wpte-adult-price-pertype-sel" name="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][price_type]">
					<option <?php selected( $pricing_option_type, 'per-person' ); ?> value="per-person"><?php esc_html_e( 'Per Person', 'wp-travel-engine' ); ?></option>
					<option <?php selected( $pricing_option_type, 'per-group' ); ?> value="per-group"><?php esc_html_e( 'Per Group', 'wp-travel-engine' ); ?></option>
				</select>
			</div>
			<span class="wpte-tooltip"><?php echo esc_html__( 'Change pricing type for this pricing option. Selecting "Per Group" will treat the price on total regardless of number of travellers.', 'wp-travel-engine' ); ?></span>
		</div>
		<?php endif; ?>
		<?php
					/**
					 * Hook for pax limits and advanced options.
					 */
					do_action( 'wte_after_pricing_option_setting_fields' );
		?>
	</div>
	<?php endforeach; ?>
</div>
<?php
		/**
		 * Hook for Group Discount, Partial Payment addons Upsell Notes.
		 */
		do_action( 'wte_after_pricing_upsell_notes' );
?>

<?php if ( $next_tab ) : ?>
<div class="wpte-field wpte-submit">
	<input data-tab="pricing" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php esc_attr_e( 'Save &amp; Continue', 'wp-travel-engine' ); ?>">
</div>
	<?php
	endif;
