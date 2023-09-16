<?php
/**
 * Destination Section
 * 
 * @package Travel_Muni
 */
$ed_recommendation            = get_theme_mod( 'ed_recommendation',false );
$ed_associated                = get_theme_mod( 'ed_associated',false );
$recommendation_section_title = get_theme_mod( 'recommendation_section_title', __( 'Were recommended by', 'travel-booking-toolkit' ) );
$recommendation_desc          = get_theme_mod( 'recommendation_desc', __( 'Travel by water often provided more comfort and speed than land-travel.', 'travel-booking-toolkit' ) );
$recommendation_repeater      = get_theme_mod( 'recommendation_repeater' );

$associated_section_title     = get_theme_mod( 'associated_section_title', __( 'Were associated with', 'travel-booking-toolkit' ) );
$associated_desc              = get_theme_mod( 'associated_desc', __( 'The origin of the word travel is most likely lost to history.', 'travel-booking-toolkit' ) );
$associated_repeater          = get_theme_mod( 'associated_repeater' );
?>  

<?php if( $ed_recommendation && ( $recommendation_section_title || $recommendation_desc || $recommendation_repeater ) ){ ?>
<section id="recommendation_section" class="all-clients recommended-by"><!-- Recommended By -->
    <?php travel_muni_recommendation_section( $recommendation_section_title,$recommendation_desc,$recommendation_repeater  ); ?>
</section><!-- Recommended By -->
<?php } if( $ed_associated && ( $associated_section_title || $associated_desc || $associated_repeater ) ){ ?>
<section id="associated-with-sc" class="all-clients associated-with"><!-- Associated With Starts -->
    <?php travel_muni_recommendation_section( $associated_section_title,$associated_desc,$associated_repeater  ); ?>
</section><!-- Associated With ends -->
<?php
}