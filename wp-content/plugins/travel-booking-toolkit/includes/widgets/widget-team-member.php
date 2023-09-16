<?php
/**
 * Team Member Widget
 *
 * @package Travel_Booking_Toolkit
 */

// register Travel_Booking_Toolkit_Team_Member_Widget widget
function travel_booking_toolkit_register_team_member_widget(){
    register_widget( 'Travel_Booking_Toolkit_Team_Member_Widget' );
}
add_action('widgets_init', 'travel_booking_toolkit_register_team_member_widget');
 
 /**
 * Adds Travel_Booking_Toolkit_Team_Member_Widget widget.
 */
class Travel_Booking_Toolkit_Team_Member_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'travel_booking_toolkit_description_widget', // Base ID
            __( 'WP Travel Engine: Team Member', 'travel-booking-toolkit' ), // Name
            array( 'description' => __( 'A Team Member Widget.', 'travel-booking-toolkit' ), ) // Args
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
        
        $obj = new Travel_Booking_Toolkit_Functions();
        $name   = ! empty( $instance['name'] ) ? $instance['name'] : '' ;        
        $designation   = ! empty( $instance['designation'] ) ? $instance['designation'] : '' ;        
        $description = ! empty( $instance['description'] ) ? $instance['description'] : '';
        $linkedin   = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $twitter   = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
        $facebook   = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
        $instagram   = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
        $youtube   = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
        $dribbble   = ! empty( $instance['dribbble'] ) ? $instance['dribbble'] : '';
        $behence   = ! empty( $instance['behence'] ) ? $instance['behence'] : '';
        $image   = ! empty( $instance['image'] ) ? $instance['image'] : '';

        echo $args['before_widget']; 
        ?>
            <div class="travel_booking_toolkit-team-holder">
                <div class="travel_booking_toolkit-team-inner-holder">
                    <?php
                    if( $image ){                        
                        $attachment_id = $obj->travel_booking_toolkit_get_attachment_id( $image );
                        $icon_img_size = apply_filters( 'tbt_team_member_icon_img_size', 'thumbnail' ); 
                    }
                    ?>
                    <?php if( $image ){ ?>
                        <div class="image-holder">
                            <?php echo wp_get_attachment_image( $attachment_id, $icon_img_size, false, 
                                        array( 'alt' => esc_attr( $name ))); ?>
                        </div>
                    <?php } ?>

                    <div class="text-holder">
                    <?php 
                        if( $name ) { echo '<span class="name">' . esc_html( $name ) . '</span>'; }
                        if( isset( $designation ) && $designation!='' ){
                            echo '<span class="designation">' . esc_html( $designation ) .  '</span>';
                        }
                        if( $description ) echo '<div class="description">' . wpautop( wp_kses_post( $description ) ) . '</div>';
                    ?>                              
                    </div>
                    <ul class="social-profile">
                        <?php if( isset( $linkedin ) && $linkedin!='' ) { echo '<li><a target="_blank" href="'.esc_url($linkedin).'"><i class="fa fa-linkedin"></i></a></li>'; }?>
                        <?php if( isset( $twitter ) && $twitter!='' ) { echo '<li><a target="_blank" href="'.esc_url($twitter).'"><i class="fa fa-twitter"></i></a></li>'; }?>
                        <?php if( isset( $facebook ) && $facebook!='' ) { echo '<li><a target="_blank" href="'.esc_url($facebook).'"><i class="fa fa-facebook"></i></a></li>'; }?>
                        <?php if( isset( $instagram ) && $instagram!='' ) { echo '<li><a target="_blank" href="'.esc_url($instagram).'"><i class="fa fa-instagram"></i></a></li>'; }?>
                        <?php if( isset( $youtube ) && $youtube!='' ) { echo '<li><a target="_blank" href="'.esc_url($youtube).'"><i class="fa fa-youtube"></i></a></li>'; }?>
                        <?php if( isset( $dribbble ) && $dribbble!='' ) { echo '<li><a target="_blank" href="'.esc_url($dribbble).'"><i class="fa fa-dribbble"></i></a></li>'; }?>
                        <?php if( isset( $behence ) && $behence!='' ) { echo '<li><a target="_blank" href="'.esc_url($behence).'"><i class="fa fa-behance"></i></a></li>'; }?>
                    </ul>
                </div>
            </div>

            <div class="travel_booking_toolkit-team-holder-modal">
                <div class="travel_booking_toolkit-team-inner-holder-modal">
                    <?php if( $image ){ ?>
                        <div class="image-holder">
                            <?php echo wp_get_attachment_image( $attachment_id, $icon_img_size, false, 
                                        array( 'alt' => esc_attr( $name ))); ?>
                        </div>
                    <?php } ?>

                    <div class="text-holder">
                    <?php 
                        if( $name ) { echo '<span class="name"> ' . esc_html( $name ) . '</span>'; }
                        if( isset( $designation ) && $designation!='' ){
                            echo '<span class="designation">' . esc_html( $designation ) . '</span>';
                        }
                        if( $description ) echo '<div class="description">' . wpautop( wp_kses_post( $description ) ) . '</div>';
                    ?>                              
                    </div>
                    <ul class="social-profile">
                        <?php if( isset( $linkedin ) && $linkedin!='' ) { echo '<li><a target="_blank" href="'.esc_url($linkedin).'"><i class="fa fa-linkedin"></i></a></li>'; }?>
                        <?php if( isset( $twitter ) && $twitter!='' ) { echo '<li><a target="_blank" href="'.esc_url($twitter).'"><i class="fa fa-twitter"></i></a></li>'; }?>
                        <?php if( isset( $facebook ) && $facebook!='' ) { echo '<li><a target="_blank" href="'.esc_url($facebook).'"><i class="fa fa-facebook"></i></a></li>'; }?>
                        <?php if( isset( $instagram ) && $instagram!='' ) { echo '<li><a target="_blank" href="'.esc_url($instagram).'"><i class="fa fa-instagram"></i></a></li>'; }?>
                        <?php if( isset( $youtube ) && $youtube!='' ) { echo '<li><a target="_blank" href="'.esc_url($youtube).'"><i class="fa fa-youtube"></i></a></li>'; }?>
                        <?php if( isset( $dribbble ) && $dribbble!='' ) { echo '<li><a target="_blank" href="'.esc_url($dribbble).'"><i class="fa fa-dribbble"></i></a></li>'; }?>
                        <?php if( isset( $behence ) && $behence!='' ) { echo '<li><a target="_blank" href="'.esc_url($behence).'"><i class="fa fa-behance"></i></a></li>'; }?>
                    </ul>
                </div>
                <a href="javascript:void(0);" class="close_popup"></a>
            </div>
        <?php
        echo 
        "
        <style>
            .travel_booking_toolkit-team-holder-modal{
                display: none;
            }
        </style>
        <script>
            jQuery(document).ready(function($) {
              $('.travel_booking_toolkit-team-holder').click(function(){
                $(this).siblings('.travel_booking_toolkit-team-holder-modal').addClass('show');
                $(this).siblings('.travel_booking_toolkit-team-holder-modal').css('display', 'block');
              });

              $('.close_popup').click(function(){
                $(this).parent('.travel_booking_toolkit-team-holder-modal').removeClass('show');
                $(this).parent().css('display', 'none');
              }); 
            });
        </script>";    
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
        
        $obj = new Travel_Booking_Toolkit_Functions();
        $name               = ! empty( $instance['name'] ) ? $instance['name'] : '' ;        
        $description        = ! empty( $instance['description'] ) ? $instance['description'] : '';
        $linkedin           = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $twitter            = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
        $facebook           = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
        $instagram          = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
        $youtube            = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
        $dribbble           = ! empty( $instance['dribbble'] ) ? $instance['dribbble'] : '';
        $behence            = ! empty( $instance['behence'] ) ? $instance['behence'] : '';
        $designation        = ! empty( $instance['designation'] ) ? $instance['designation'] : '';
        $image              = ! empty( $instance['image'] ) ? $instance['image'] : '';
        ?>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"><?php esc_html_e( 'Name', 'travel-booking-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name' ) ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>" />            
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'designation' ) ); ?>"><?php esc_html_e( 'Designation', 'travel-booking-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'designation' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'designation' ) ); ?>" type="text" value="<?php echo esc_attr( $designation ); ?>" />            
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description', 'travel-booking-toolkit' ); ?></label>
            <textarea name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php print $description; ?></textarea>
        </p>

       
        
        <?php $obj->travel_booking_toolkit_get_image_field( $this->get_field_id( 'image' ), $this->get_field_name( 'image' ), $image, __( 'Upload Photo', 'travel-booking-toolkit' ) ); ?>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_html_e( 'LinkedIn Profile', 'travel-booking-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="text" value="<?php echo esc_url( $linkedin ); ?>" />            
        </p>
        
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_html_e( 'Twitter Profile', 'travel-booking-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="text" value="<?php echo esc_url( $twitter ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_html_e( 'Facebook Profile', 'travel-booking-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="text" value="<?php echo esc_url( $facebook ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php esc_html_e( 'Instagram Profile', 'travel-booking-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" type="text" value="<?php echo esc_url( $instagram ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>"><?php esc_html_e( 'YouTube Profile', 'travel-booking-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'youtube' ) ); ?>" type="text" value="<?php echo esc_url( $youtube ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'dribbble' ) ); ?>"><?php esc_html_e( 'Dribbble Profile', 'travel-booking-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'dribbble' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'dribbble' ) ); ?>" type="text" value="<?php echo esc_url( $dribbble ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'behence' ) ); ?>"><?php esc_html_e( 'Behance Profile', 'travel-booking-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'behence' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'behence' ) ); ?>" type="text" value="<?php echo esc_url( $behence ); ?>" />            
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
        
        $instance['name']               = ! empty( $new_instance['name'] ) ? sanitize_text_field( $new_instance['name'] ) : '' ;
        $instance['description']        = ! empty( $new_instance['description'] ) ? wp_kses_post( $new_instance['description'] ) : '';
        $instance['designation']        = ! empty( $new_instance['designation'] ) ? esc_attr( $new_instance['designation'] ) : '';
        $instance['linkedin']           = ! empty( $new_instance['linkedin'] ) ? esc_url_raw( $new_instance['linkedin'] ) : '';
        $instance['twitter']            = ! empty( $new_instance['twitter'] ) ? esc_url_raw( $new_instance['twitter'] ) : '';
        $instance['facebook']           = ! empty( $new_instance['facebook'] ) ? esc_url_raw( $new_instance['facebook'] ) : '';
        $instance['instagram']          = ! empty( $new_instance['instagram'] ) ? esc_url_raw( $new_instance['instagram'] ) : '';
        $instance['youtube']            = ! empty( $new_instance['youtube'] ) ? esc_url_raw( $new_instance['youtube'] ) : '';
        $instance['dribbble']           = ! empty( $new_instance['dribbble'] ) ? esc_url_raw( $new_instance['dribbble'] ) : '';
        $instance['behence']            = ! empty( $new_instance['behence'] ) ? esc_url_raw( $new_instance['behence'] ) : '';
        $instance['image']              = ! empty( $new_instance['image'] ) ? esc_url_raw( $new_instance['image'] ) : '';

        return $instance;
    }
    
}  
// class Travel_Booking_Toolkit_Team_Member_Widget