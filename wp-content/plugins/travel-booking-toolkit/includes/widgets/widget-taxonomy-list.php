<?php
/**
 * Widget Recent Post
 *
 * @package Travel_Booking_Toolkit
 */
 
// register Travel_Booking_Toolkit_Taxonomy_List widget
function travel_booking_toolkit_register_taxonomy_list_widget() {
    register_widget( 'Travel_Booking_Toolkit_Taxonomy_List' );
}
add_action( 'widgets_init', 'travel_booking_toolkit_register_taxonomy_list_widget' );
 
 /**
 * Adds Travel_Booking_Toolkit_Taxonomy_List widget.
 */
class Travel_Booking_Toolkit_Taxonomy_List extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'travel_booking_toolkit_taxonomy_list', // Base ID
            __( 'WP Travel Engine: Category List', 'travel-booking-toolkit' ), // Name
            array( 'description' => __( 'A Taxonomy List Widget', 'travel-booking-toolkit' ), ) // Args
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
        $title      = ! empty( $instance['title'] ) ?  $instance['title'] :'';
        $num_post   = ! empty( $instance['num_post'] ) ? $instance['num_post'] : 5;
        $tax        = ! empty( $instance['tax'] ) ? $instance['tax'] : '';
        $target = 'target="_blank"';
        if( isset($instance['target']) && $instance['target']!='' )
        {
            $target = 'target="_self"';
        }
        
        if($tax == '')return;
        
        echo $args['before_widget'];
        ob_start();
        if( $title ) echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title'];
        ?>
        <ul class="travel_booking_toolkit-taxonomy-list">
            <?php
            $terms = get_terms( array(
                'taxonomy'   => $tax,
                'hide_empty' => true,
            ) );
            $i = 0;
            $max = wp_count_terms($tax);               
            foreach ($terms as $key) {
                if( $i<=$num_post && $i <= $max )
                {
                    echo '<li><a '.$target.' href='.get_term_link($key).'>'.$key->name.'</a></li>';
                    $i++;        
                }
            }
            ?>
        </ul>
        <?php
        $html = ob_get_clean();
        echo apply_filters( 'travelbooking_taxonomy_list_widget_filter', $html, $args, $instance);
        echo $args['after_widget'];   
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        
        $title          = ! empty( $instance['title'] ) ? $instance['title'] :'';      
        $num_post       = ! empty( $instance['num_post'] ) ? $instance['num_post'] : 5 ;        
        $tax            = ! empty( $instance['tax'] ) ? $instance['tax'] : '' ;
        $target     = ! empty( $instance['target'] ) ? $instance['target'] : '';        
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'travel-booking-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'num_post' ) ); ?>"><?php esc_html_e( 'Number of List Items', 'travel-booking-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'num_post' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'num_post' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $num_post ); ?>" />
        </p>
        <?php
        $taxonomy_objects = get_object_taxonomies( 'trip', 'objects' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'tax' ) ); ?>"><?php esc_html_e( 'Trip Category', 'travel-booking-toolkit' ); ?></label>
            <select class="taxonomy-list" name="<?php echo esc_attr( $this->get_field_name( 'tax' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'tax' ) ); ?>">
            <option value=""><?php _e('Select Category', 'travel-booking-toolkit') ?></option>
            <?php     
            foreach ($taxonomy_objects as $key => $value) { ?>
                <option value="<?php echo esc_attr($key);?>" <?php selected( $tax,$key );?>><?php echo $key;?></option>
            <?php
            }
            ?>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked($target,1);?> /><?php esc_html_e( 'Open in Same Tab', 'travel-booking-toolkit' ); ?> </label>
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
        
        $instance = array();
        $instance['title']          = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['num_post']       = ! empty( $new_instance['num_post'] ) ? absint( $new_instance['num_post'] ) : 5;        
        $instance['tax']            = ! empty( $new_instance['tax'] ) ? esc_attr( $new_instance['tax'] ) : '' ;
        $instance['target']                  = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';        
        return $instance;
    }
}