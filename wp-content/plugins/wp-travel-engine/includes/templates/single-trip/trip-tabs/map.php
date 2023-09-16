<?php
/**
 * Map Template
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/trip-tabs/map.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php do_action( 'wte_before_map_content' ); ?>

<div class="post-data">
	<?php
		/**
		 * Hook - Display tab content title, left for themes.
		 */
		do_action( 'wte_map_tab_title' );
	?>
	<div class="content">
		<?php echo do_shortcode( '[wte_trip_map id=' . $post_id . ']' ); ?>
	</div>
</div>

<?php
do_action( 'wte_after_map_content' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
