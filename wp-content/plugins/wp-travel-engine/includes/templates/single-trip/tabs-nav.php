<?php
/**
 * Trip Tabs Nav Template
 *
 * Closing "tabs-container" div is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/tabs-nav.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

do_action( 'wp_travel_engine_before_trip_tabs' );

$count                         = 0;
$wp_travel_engine_tab_settings = get_option( 'wp_travel_engine_settings', true );
$make_tabs_sticky              = wte_array_get( get_option( 'wp_travel_engine_settings', array() ), 'wte_sticky_tabs', 'no' ) === 'yes';
if ( ! empty( $tabs['id'] ) ) : ?>

<div id="tabs-container" class="wpte-tabs-container
	<?php
	if ( $make_tabs_sticky ) {
		print esc_attr( 'wpte-tabs-sticky wpte-tabs-scrollable' );
	}
	?>
	 clearfix">
	<div class="nav-tab-wrapper">
		<div class="tab-inner-wrapper">
			<?php foreach ( $tabs['id'] as $key => $value ) : ?>
			<div class="tab-anchor-wrapper">
				<h2 class="wte-tab-title">
					<a href="javascript:void(0);"
						class="nav-tab nb-tab-trigger <?php ( $count == 0 ) && print esc_attr( 'nav-tab-active' ); ?>"
						data-configuration="<?php echo esc_attr( $tabs['id'][ $value ] ); ?>">
						<?php
						if ( isset( $tabs['icon'][ $value ] ) && $tabs['icon'][ $value ] != '' ) {
							echo '<span class="tab-icon">' . wptravelengine_svg_by_fa_icon( $tabs['icon'][ $value ], false ) . '</span>';
						}
						?>
						<?php echo esc_attr( $tabs['name'][ $value ] ); ?>
					</a>
				</h2>
			</div>
			<!-- ./tab-anchor-wrapper -->
				<?php
				$count++;
			endforeach;
			?>
		</div>
		<!-- ./tab-inner-wrapper -->
	</div>
	<!-- ./nav-tab-wrapper -->

	<?php
	endif;

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
