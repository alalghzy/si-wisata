<?php
/**
 * Trip Tabs Content Template
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/tabs-content.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$i = 1;

// Filter to control tabs open behaviour.
$show_all_tabs = apply_filters( 'wte_single_trip_show_all_tabs', false );

if ( isset( $tabs['id'] ) ) : ?>

	<div class="tab-content">

		<?php
		foreach ( $tabs['id'] as $index => $id ) :
			$field = $tabs['field'][ $index ];
			/**
			 * @hook - wte_single_before_trip_tab_{field_name}
			 * Dynamic hooks before Tab wrapper - for themes to hook content into.
			 */
			do_action( "wte_single_before_trip_tab_{$field}" );
			?>
			<div id="nb-<?php echo esc_attr( $index ); ?>-configurations" class="nb-<?php echo esc_attr( $index ); ?>-configurations nb-configurations"
				<?php
				if ( 1 !== $i && ! $show_all_tabs ) {
					echo 'style=" display:none;"';}
				?>
				>
				<?php
				$name = $tabs['name'][ $index ];
				$icon = isset( $tabs['icon'][ $index ] ) && '' != $tabs['icon'][ $index ] ?
				$tabs['icon'][ $index ] : '';
				do_action( "wte_single_trip_tab_content_{$field}", $id, $field, $name, $icon );
				?>
			</div>
			<?php
				/**
				 * @hook - wte_single_after_trip_tab_{field_name}
				 * Dynamic hooks after Tab wrapper - for themes to hook content into.
				 */
				do_action( "wte_single_after_trip_tab_{$field}" );
			$i++;
		endforeach;
		?>
	</div>
	<!-- ./tab-content -->
</div>
<!-- /#tabs-container -->

	<?php
endif;

do_action( 'wp_travel_engine_after_trip_tabs' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
