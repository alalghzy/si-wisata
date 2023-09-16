<?php
/**
 * The template for displaying trips according to trip_types .
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since 1.0.0
 */

get_header();
	wte_get_template( 'content--template-taxonomy.php', array( 'taxonomy' => 'trip_types' ) );
get_footer();
