<?php
/**
 * Widget area definition.
 */
class Wte_Widget_Area_Admin {

	public function init() {
		$args = array(
			'name'          => __( 'WP Travel Engine Sidebar', 'wp-travel-engine' ),
			'id'            => 'wte-sidebar-id',
			'description'   => 'This is the widget area for single trip page.',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		);
		register_sidebar( $args );
		add_action( 'wp_travel_engine_wte_sidebar', array( $this, 'wte_widget_sidebar' ) );
	}

	/**
	 * Load sidebar.
	 *
	 * @since 1.0.0
	 */
	function wte_widget_sidebar() {
		if ( is_active_sidebar( 'wte-sidebar-id' ) ) :
			dynamic_sidebar( 'wte-sidebar-id' );
		endif;
	}
}
$obj = new Wte_Widget_Area_Admin();
$obj->init();
