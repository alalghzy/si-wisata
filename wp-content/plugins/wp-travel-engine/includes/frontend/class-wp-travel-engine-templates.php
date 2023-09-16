<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * The template loader of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Travel_Triping
 * @subpackage Travel_Triping/admin
 * @author     WP Travel Engine <https://wptravelengine.com/>
 */
class Wp_Travel_Engine_Templates {

	/**
	 * Hook in methods.
	 */
	public function __construct() {
		add_filter( 'template_include', array( $this, 'include_template_function' ) );
	}

	/**
	 * Template over-ride for single trip.
	 *
	 * @since    1.0.0
	 */
	function include_template_function( $template_path ) {
		if ( get_post_type() == 'trip' ) {
			if ( is_single() ) {
				$template_path = wte_locate_template( 'single-trip.php' );
			}
			if ( is_archive() ) {
				$template_path = wte_locate_template( 'archive-trip.php' );
			}
			$taxonomies = array( 'trip_types', 'destination', 'activities' );
			foreach ( $taxonomies as $tax ) {
				if ( is_tax( $tax ) ) {
					$template_path = wte_locate_template( 'taxonomy-' . $tax . '.php' );
				}
			}
		}
		return $template_path;
	}
}
new Wp_Travel_Engine_Templates();
