<?php
/**
 * Client Logo Widget
 *
 * @package Travel_Booking_Toolkit
 */
// register WP_Travel_Engine_Client_Logo_Widget widget
function wptravelengine_register_client_logo_widget(){
    register_widget( 'WP_Travel_Engine_Client_Logo_Widget' );
}
add_action('widgets_init', 'wptravelengine_register_client_logo_widget');
 
 /**
 * Adds WP_Travel_Engine_Client_Logo_Widget widget.
 */
class WP_Travel_Engine_Client_Logo_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'wptravelengine_client_logo_widget', // Base ID
            __( 'WP Travel Engine: Client Logo', 'travel-booking-toolkit' ), // Name
            array( 'description' => __( 'A Client Logo Widget.', 'travel-booking-toolkit' ), ) // Args
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
        
        $obj     = new Travel_Booking_Toolkit_Functions();
        $title   = ! empty( $instance['title'] ) ? $instance['title'] : '' ;
        $image   = ! empty( $instance['image'] ) ? $instance['image'] : '';
        $link    = ! empty( $instance['link'] ) ? $instance['link'] : '';
        $target = 'target="_blank"';
        if( isset($instance['target']) && $instance['target']!='' )
        {
            $target = 'target="_self"';
        }
        $display_bw = '';
        if ( isset( $instance['display_bw'] ) && $instance['display_bw']!= '' )
        {
            $display_bw = 'black-white';
        }
        echo $args['before_widget'];
        ob_start(); 
        ?>
            <div class="wptravelengine-client-logo-holder">
                <div class="wptravelengine-client-logo-inner-holder">
                    <?php
                    if( $title ) { echo $args['before_title']; echo apply_filters( 'widget_title', $title, $instance, $this->id_base ); echo $args['after_title']; }
                        if(isset( $instance['link']) && '' != $instance['link'] ){
                            $image='';
                            foreach ( $instance['link'] as $key => $value) { 
                                if( isset( $instance['image'][$key] ) && $instance['image'][$key]!='' )
                                {
                                    $attachment_id = $instance['image'][$key];
                                    $cta_img_size = apply_filters('tbt_cl_img_size','full');
                                    $image_array   = wp_get_attachment_image_src( $attachment_id, $cta_img_size);
                                    $fimg_url      = $image_array[0];
                                }
                                else{
                                    $fimg_url = esc_url(TBT_FILE_URL).'/public/css/image/no-featured-img.jpg';
                                } 

                                if( '' != $value && '' != $fimg_url ){ ?>
                                    <div class="image-holder <?php echo $display_bw;?>">
                                        <?php
                                            echo '<a href="'.esc_url($value).'" '.$target.'><img src="'.esc_url( $fimg_url ).'" alt="'.esc_html( $title ).'" /></a>';
                                        ?>
                                    </div>
                                <?php
                                }
                            }
                        }
                        ?>                  
                </div>
            </div>
        <?php
        $html = ob_get_clean();
        echo apply_filters( 'travelbooking_client_logo_widget_filter', $html, $args, $instance);
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
        $obj     = new Travel_Booking_Toolkit_Functions();
        $title   = ! empty( $instance['title'] ) ? $instance['title'] : '' ;
        $display_bw   = ! empty( $instance['display_bw'] ) ? $instance['display_bw'] : '' ;
        $image   = ! empty( $instance['image'] ) ? $instance['image'] : '';
        $target     = ! empty( $instance['target'] ) ? $instance['target'] : '';
        $link    = ! empty( $instance['link'] ) ? $instance['link'] : '';
        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function($) {
                $('.widget-client-logo-repeater').sortable({
                    cursor: 'move',
                    update: function (event, ui) {
                        $('.widget-client-logo-repeater .link-image-repeat input').trigger('change');
                    }
                });
                $('.check-btn-wrap').on('click', function( event ){
                    $(this).trigger('change');
                });
            });
        </script>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'travel-booking-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />            
        </p>
        
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'display_bw' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_bw' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $display_bw ); ?>/>
            <label class="check-btn-wrap" for="<?php echo esc_attr( $this->get_field_id( 'display_bw' ) ); ?>"><?php esc_html_e( 'Display logo in black and white', 'travel-booking-toolkit' ); ?></label>
        </p>

        <p>
            
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked($target,1);?> /> 
            <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>"><?php esc_html_e( 'Open in Same Tab', 'travel-booking-toolkit' ); ?></label>
        </p>

        <div class="widget-client-logo-repeater" id="<?php echo esc_attr( $this->get_field_id( 'wptravelenginecompanion-logo-repeater' ) ); ?>">
            <p>
            <?php
            if( isset( $instance['link'] ) && $instance['link']!='' )
            {
                if( count( array_filter( $instance['link'] ) ) != 0 )
                {
                    $arr = $instance['link'];
                    $max = max(array_keys($arr)); 
                    for ($i=1; $i <= $max; $i++) { 
                        if(array_key_exists($i, $arr))
                        { ?>
                            <div class="link-image-repeat" data-id="<?php echo $i;?>"><span class="cross"><i class="fas fa-times"></i></span>
                            <?php
                            $obj->travel_booking_toolkit_get_image_field( $this->get_field_id( 'image['.$i.']' ), $this->get_field_name( 'image['.$i.']' ),  $instance['image'][$i], __( 'Upload Image', 'travel-booking-toolkit' ) ); ?>
                            <label for="<?php echo esc_attr( $this->get_field_id( 'link['.$i.']' ) ); ?>"><?php esc_html_e( 'Featured Link', 'travel-booking-toolkit' ); ?></label> 
                            <input class="widefat demo" id="<?php echo esc_attr( $this->get_field_id( 'link['.$i.']' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link['.$i.']' ) ); ?>" type="text" value="<?php echo esc_url( $instance['link'][$i] ); ?>" />            
                            </div>
                        <?php
                        }
                    }
                }
            }
            ?>
            </p>
        <span class="cl-repeater-holder"></span>
        </div>
        <button id="add-logo" class="button"><?php _e('Add Another Logo','travel-booking-toolkit');?></button>
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
        $instance['title']   = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '' ;
        $instance['display_bw']   = ! empty( $new_instance['display_bw'] ) ? sanitize_text_field( $new_instance['display_bw'] ) : '' ;
        $instance['target']                  = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';
        if(isset($new_instance['image']))
        {
            foreach ( $new_instance['image'] as $key => $value ) {
                $instance['image'][$key]   = $value;
            }
        }

        if(isset($new_instance['link']))
        {
            foreach ( $new_instance['link'] as $key => $value ) {
                $instance['link'][$key]    = $value;
            }
        }
        
        return $instance;
    }
    
}  
// class WP_Travel_Engine_Client_Logo_Widget
