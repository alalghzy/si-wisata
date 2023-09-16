<?php
/**
 * Single Trip header
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/title.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<header class="entry-header">
	<h1 class="entry-title" itemprop="name">
		<?php the_title(); ?>
	</h1>
	<!-- Display duration -->
	<?php if ( ! empty( $duration ) ) : ?>
		<span class="wte-title-duration">
			<span class="duration">
				<?php echo esc_html( number_format_i18n( $duration ) ); ?>
			</span>
			<span class="days">
				<?php
				if ( 'days' === $duration_unit ) printf( _n( 'Day', 'Days', $duration, 'wp-travel-engine' ) );
				if ( 'hours' === $duration_unit ) printf( _n( 'Hour', 'Hours', $duration, 'wp-travel-engine' ) );
				?>
			</span>
		</span>
	<?php endif; ?>
	<!-- ./ Display duraiton -->
	<?php do_action( 'wp_travel_engine_header_hook' ); ?>
</header>
<!-- ./entry-header -->
<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
