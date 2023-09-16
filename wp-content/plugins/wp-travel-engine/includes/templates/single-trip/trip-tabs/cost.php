<?php
/**
 * Single Trip Cost Include/Exclude Template
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/trip-tabs/cost.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php do_action( 'wte_before_cost_content' ); ?>

<div class="post-data cost">
	<?php
		/**
		 * Hook - Display tab content title, left for themes.
		 */
		do_action( 'wte_cost_tab_title' );
	?>
	<div class="content">
		<?php
		if ( isset( $cost['includes_title'] ) && $cost['includes_title'] != '' ) {
			echo '<h3>' . esc_attr( $cost['includes_title'] ) . '</h3>';
		}
		?>
		<ul id="include-result">
			<?php
				$cost_includes = preg_split( '/\r\n|[\r\n]/', $cost['cost_includes'] );
			foreach ( $cost_includes as $key => $include ) {
				echo '<li>' . esc_html( $include ) . '</li>';
			}
			?>
		</ul>
	</div>
	<div class="content">
		<?php
		if ( isset( $cost['excludes_title'] ) && $cost['excludes_title'] != '' ) {
			echo '<h3>' . esc_attr( $cost['excludes_title'] ) . '</h3>';
		}
		?>
		<ul id="exclude-result">
			<?php
			$cost_excludes = preg_split( '/\r\n|[\r\n]/', $cost['cost_excludes'] );
			foreach ( $cost_excludes as $key => $exclude ) {
				echo '<li>' . esc_html( $exclude ) . '</li>';
			}
			?>
		</ul>
	</div>
</div>

<?php
do_action( 'wte_after_cost_content' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
