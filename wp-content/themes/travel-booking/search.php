<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Travel_Booking
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<?php
			/**
			 * @hooked travel_booking_get_search_page_header - 10
			 */
			do_action( 'travel_booking_search_page_header' );
		?>
        
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) :

			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );

			endwhile;

			/**
	         * Navigation
	         * 
	         * @hooked travel_booking_pagination
	        */
	        do_action( 'travel_booking_after_content' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_sidebar();
get_footer();
