<?php
/**
 * Single Trip Content
 *
 * Closing entry-content div is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/trip-content.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="entry-content">
	<?php
	if ( isset( $settings['departure']['section'] ) ) {
		if ( ! isset( $post_meta['departure_dates']['section'] ) ) {
			do_action( 'Wte_Fixed_Starting_Dates_Action' );
		}
	}
	?>

	<div class="trip-post-content">
		<?php the_content(); ?>
	</div>
	<!-- ./trip-post-content -->

<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
