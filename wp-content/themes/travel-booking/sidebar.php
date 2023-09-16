<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Travel_Booking
 */

$sidebar_layout = travel_booking_sidebar_layout();

if ( 'fullwidth' == $sidebar_layout ) {
	return;
}
?>

<aside id="secondary" class="widget-area" itemscope itemtype="https://schema.org/WPSideBar">
	<?php dynamic_sidebar( 'sidebar' ); ?>
</aside><!-- #secondary -->