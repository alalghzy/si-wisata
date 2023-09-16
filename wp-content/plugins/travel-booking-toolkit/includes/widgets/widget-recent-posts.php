<?php
/**
 * Widget Recent Post
 *
 * @package Travel_Booking_Toolkit
 */
 
// register Travel_Booking_Toolkit_Recent_Post widget
function travel_booking_toolkit_register_recent_post_widget() {
    register_widget( 'Travel_Booking_Toolkit_Recent_Post' );
}
add_action( 'widgets_init', 'travel_booking_toolkit_register_recent_post_widget' );
 
 /**
 * Adds Travel_Booking_Toolkit_Recent_Post widget.
 */
class Travel_Booking_Toolkit_Recent_Post extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'travel_booking_toolkit_recent_post', // Base ID
            __( 'WP Travel Engine: Recent Posts', 'travel-booking-toolkit' ), // Name
            array( 'description' => __( 'A Recent Post Widget', 'travel-booking-toolkit' ), ) // Args
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
        $title      = ! empty( $instance['title'] ) ?  $instance['title'] : __( 'Recent Posts', 'travel-booking-toolkit' );
        $num_post   = ! empty( $instance['num_post'] ) ? $instance['num_post'] : 3 ;
        $show_thumb = ! empty( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : '';
        $show_date  = ! empty( $instance['show_postdate'] ) ? $instance['show_postdate'] : '';
        $cats[]     = '';
        $cat        = apply_filters( 'tbt_pro_exclude_categories', $cats );
        $style      = ! empty( $instance['style'] ) ? $instance['style'] : 'style-one';
        $target = 'target="_self"';
        if( isset($instance['target']) && $instance['target']!='' )
        {
            $target = 'target="_blank"';
        }

        global $post;
        $qry = new WP_Query( array(
            'post_type'             => 'post',
            'post_status'           => 'publish',
            'posts_per_page'        => $num_post,
            'ignore_sticky_posts'   => true,
            'category__not_in'      => $cat
        ) );
        if( $qry->have_posts() ){
            echo $args['before_widget'];
            ob_start();
            if( $title ) echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title'];
            ?>
            <ul class="<?php echo esc_attr($style);?>">
                <?php 
                while( $qry->have_posts() ){
                    $qry->the_post();
                ?>
                    <li>
                        <?php if( has_post_thumbnail() && $show_thumb ){ ?>
                            <a <?php echo $target; ?> href="<?php the_permalink();?>" class="post-thumbnail">
                                <?php
                                $recent_img_size = apply_filters('tbt_recent_img_size','post-slider-thumb-size');
                                    the_post_thumbnail( $recent_img_size ); 
                                ?>
                            </a>
                        <?php 
                        }
                        if( $show_thumb && !has_post_thumbnail() ){
                            echo '<a '.$target.' href="'.esc_url(TBT_FILE_URL).'/public/css/image/no-featured-img.jpg" class="post-thumbnail"><img src="'.esc_url(TBT_FILE_URL).'/public/css/image/no-featured-img.jpg"></a>';
                        }
                        ?>
                        <div class="entry-header">
                                <?php
                                $category_detail = get_the_category($post->ID);//$post->ID
                                echo '<span class="cat-links">';
                                foreach($category_detail as $cd){
                                echo '<a '.$target.' href="' . esc_url( get_category_link( $cd->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'travel-booking-toolkit' ), $cd->name ) ) . '">' . esc_html( $cd->name ) . '</a>';
                                }
                                echo '</span>';
                                ?>
                            <h3 class="entry-title"><a <?php echo $target; ?> href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>
                            <?php if( $show_date ) { ?>
                                <div class="entry-meta">
                                    <span class="posted-on"><a <?php echo $target; ?>href="<?php the_permalink(); ?>">
                                        <time datetime="<?php printf( __( '%1$s', 'travel-booking-toolkit' ), get_the_date('Y-m-d') ); ?>"><?php printf( __( '%1$s', 'travel-booking-toolkit' ), get_the_date() ); ?></time></a>
                                    </span>
                                </div>
                            <?php } ?>
                        </div>                        
                    </li>        
                <?php    
                }
            ?>
            </ul>
            <?php
            $html = ob_get_clean();
            echo apply_filters( 'travelbooking_recent_posts_widget_filter', $html, $args, $instance);
            echo $args['after_widget'];   
        }
        wp_reset_postdata();  
    }

    //function to add different styling classes
    public function travel_booking_toolkit_add_recent_post_class()
    {
       $arr = array(
            'style-one'   => __('Style One', 'travel-booking-toolkit'),
            'style-two'   => __('Style Two', 'travel-booking-toolkit'),
            'style-three' => __('Style Three', 'travel-booking-toolkit'),
        );
        $arr = apply_filters( 'tbt_add_recent_post_class', $arr );
        return $arr;
    }
    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        
        $title          = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Posts', 'travel-booking-toolkit' );      
        $num_post       = ! empty( $instance['num_post'] ) ? $instance['num_post'] : 3 ;
        $show_thumbnail = ! empty( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : '';
        $show_postdate  = ! empty( $instance['show_postdate'] ) ? $instance['show_postdate'] : '';
        $style          = ! empty( $instance['style'] ) ? $instance['style'] : 'style-one';
        $target    = ! empty( $instance['target'] ) ? $instance['target'] : '';
        
        ?>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'travel-booking-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'num_post' ) ); ?>"><?php esc_html_e( 'Number of Posts', 'travel-booking-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'num_post' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'num_post' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $num_post ); ?>" />
        </p>
        
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumbnail' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_thumbnail ); ?>/>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>"><?php esc_html_e( 'Show Post Thumbnail', 'travel-booking-toolkit' ); ?></label>
        </p>
        
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'show_postdate' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_postdate' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_postdate ); ?>/>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_postdate' ) ); ?>"><?php esc_html_e( 'Show Post Date', 'travel-booking-toolkit' ); ?></label>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked($target,1);?> /><?php esc_html_e( 'Open in New Tab', 'travel-booking-toolkit' ); ?> </label>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Layout:', 'travel-booking-toolkit' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" class="based-on">
                <?php
                $styles = $this->travel_booking_toolkit_add_recent_post_class();
                foreach ($styles as $key => $value) { ?>
                    <option value="<?php echo $key; ?>" <?php selected($style,$key);?>><?php echo $value;?></option>
                <?php
                }
                ?>     
            </select>
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
        
        $instance['title']          = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : __( 'Recent Posts', 'travel-booking-toolkit' );
        $instance['num_post']       = ! empty( $new_instance['num_post'] ) ? absint( $new_instance['num_post'] ) : 3 ;        
        $instance['show_thumbnail'] = ! empty( $new_instance['show_thumbnail'] ) ? absint( $new_instance['show_thumbnail'] ) : '';
        $instance['show_postdate']  = ! empty( $new_instance['show_postdate'] ) ? absint( $new_instance['show_postdate'] ) : '';
        $instance['style']          = ! empty( $new_instance['style'] ) ? esc_attr( $new_instance['style'] ) : 'style-one';
        $instance['target']         = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';

        return $instance;
        
    }

} // class Travel_Booking_Toolkit_Recent_Post / class Travel_Booking_Toolkit_Recent_Post / class Travel_Booking_Toolkit_Recent_Post / class Travel_Booking_Toolkit_Recent_Post 