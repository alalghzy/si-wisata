<?php
/**
 * Widget class.
 */
class Wte_Widget_Area {

	public function init() {
		add_action( 'wp_travel_engine_sidebar', array( $this, 'wte_dynamic_sidebar' ) );
	}

	/**
	 * Load required files.
	 *
	 * @since 1.0.0
	 */
	function wte_dynamic_sidebar() {
		if ( is_active_sidebar( 'wte-sidebar-id' ) ) : ?>
			<ul id="sidebar">
				<?php dynamic_sidebar( 'wte-sidebar-id' ); ?>
			</ul>
			<?php
		endif;
	}
}
$obj = new \Wte_Widget_Area();
$obj->init();
