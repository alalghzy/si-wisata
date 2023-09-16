<?php
/**
 * Adds Featured Trip Widget.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/widgets
 * @since @release-version //TODO: change after travel muni is live
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register WTE_Featured_Trips_Widget widget.
 */
function wte_register_featured_trips_widget() {
	register_widget( 'WTE_Featured_Trips_Widget' );
}
add_action( 'widgets_init', 'wte_register_featured_trips_widget' );

class WTE_Featured_Trips_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'wte_featured_trips_widget', // Base ID
			'WP Travel Engine: Featured Trips Widget', // Name
			array( 'description' => __( 'A Featured Trips Widget for WP Travel Engine.', 'wp-travel-engine' ) ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title    = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );
		$num_post = ! empty( $instance['num_post'] ) ? $instance['num_post'] : 3;

		//phpcs:disable
		echo $before_widget;
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		$args = array(
			'post_type'      => 'trip',
			'posts_per_page' => $num_post,
			'meta_key'       => 'wp_travel_engine_featured_trip',
			'meta_value'     => 'yes',
			'meta_compare'   => '=',

		);
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$details = wte_get_trip_details( get_the_ID() );
				wte_get_template( 'widgets/content-widget-feat-trip.php', $details );
			}
		}
        wp_reset_postdata();

		echo $after_widget;
		//phpcs:enable

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title    = isset( $instance['title'] ) ? $instance['title'] : __( 'Featured Trips', 'wp-travel-engine' );
		$num_post = isset( $instance['num_post'] ) ? $instance['num_post'] : 3;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'wp-travel-engine' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_name( 'num_post' ) ); ?>"><?php esc_html_e( 'Number of Posts:', 'wp-travel-engine' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'num_post' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'num_post' ) ); ?>" type="text" value="<?php echo esc_attr( $num_post ); ?>" />
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance             = array();
		$instance['title']    = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['num_post'] = ! empty( $new_instance['num_post'] ) ? absint( $new_instance['num_post'] ) : '';

		return $instance;
	}

} // class WTE_Featured_Trips_Widget
