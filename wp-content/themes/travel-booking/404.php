<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Travel_Booking
 */

get_header(); ?>
    
    <div class="error-holder">
		<h2><?php esc_html_e( '404 Page Not Found', 'travel-booking' ); ?></h2>
		<p><?php esc_html_e( 'Hey! You&rsquo;ve gone off the map. The page you are looking for doesn&rsquo;t exist, but you can try again searching below:', 'travel-booking' ); ?></p>
		
		<?php get_search_form(); ?>

	</div>
	
<?php
get_footer();