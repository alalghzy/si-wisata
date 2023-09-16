<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Travel_Booking
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); if( ! is_singular( 'post' ) ) echo ' itemscope itemtype="https://schema.org/Blog"';?>>
	
    <?php
        /**
         * Post Thumbnail
         * 
         * @hooked travel_booking_post_thumbnail - 20
        */
        do_action( 'travel_booking_before_entry_content' );    
    ?>
    
    <div class="text-holder">        
        <?php
            /**
             * 
             * @hooked travel_booking_before_entry_header  - 10
             *
             * @hooked travel_booking_entry_header  - 20
             *
             * @hooked travel_booking_entry_content - 30
             *
             * @hooked travel_booking_entry_footer  - 40
             */
            do_action( 'travel_booking_post_content' );
        ?>
    </div><!-- .text-holder -->
    
</article><!-- #post-<?php the_ID(); ?> -->