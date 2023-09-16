<?php
/**
 * Checkout steps template.
 *
 * @package WP_Travel_Engine
 */

global $wte_cart;
$cart_items = $wte_cart->getItems();

$checkout_completed_steps = apply_filters(
	'wp_travel_engine_checkout_completed_steps',
	array(
		'calendar'  => array(
			'label' => __( 'Select a Date', 'wp-travel-engine' ),
			'class' => 'completed',
		),
		'travelers' => array(
			'label' => __( 'Travellers', 'wp-travel-engine' ),
			'class' => 'completed',
		),
	)
);

$trips_with_extras = array_filter(
	$cart_items,
	function( $item ) {
		return isset( $item['trip_extras'] ) && ! empty( $item['trip_extras'] );
	}
);

if ( count( $trips_with_extras ) > 0 ) :

	$checkout_completed_steps['extra_services'] = array(
		'label' => __( 'Extra Services', 'wp-travel-engine' ),
		'class' => 'completed',
	);

endif;

$checkout_current_step = apply_filters(
	'wp_travel_engine_checkout_current_step',
	array(
		'checkout' => array(
			'label' => __( 'Billing Details', 'wp-travel-engine' ),
			'class' => 'active',
		),
	)
);

$checkout_remaining_step = apply_filters(
	'wp_travel_engine_checkout_remaining_steps',
	array(
		'payment' => array(
			'label' => __( 'Payment', 'wp-travel-engine' ),
			'class' => '',
		),
	)
);

$checkout_steps_crumbs = array_merge( $checkout_completed_steps, $checkout_current_step, $checkout_remaining_step );
$checkout_steps_crumbs = apply_filters( 'wp_travel_engine_checkout_steps', $checkout_steps_crumbs );

if ( is_array( $checkout_steps_crumbs ) && ! empty( $checkout_steps_crumbs ) ) :
	?>
	<div class="wpte-bf-step-wrap">
		<?php
		foreach ( $checkout_steps_crumbs as $key => $step ) :
			$step_class = isset( $step['class'] ) ? $step['class'] : '';
			$step_label = isset( $step['label'] ) ? $step['label'] : '';
			?>
			<span class="wpte-bf-step <?php echo esc_attr( $step_class ); ?>">
				<span class="wpte-bf-step-inner"><?php echo esc_html( $step_label ); ?></span>
			</span>
		<?php endforeach; ?>
	</div>
	<?php
endif;
