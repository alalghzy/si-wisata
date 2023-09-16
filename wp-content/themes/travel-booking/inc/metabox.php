<?php 
/**
* Metabox for Sidebar Layout
*
* @package Travel_Booking
*
*/ 

function travel_booking_add_sidebar_layout_box(){
    add_meta_box( 
        'travel_booking_sidebar_layout',
        __( 'Sidebar Layout', 'travel-booking' ),
        'travel_booking_sidebar_layout_callback', 
        array( 'page', 'post' ),
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'travel_booking_add_sidebar_layout_box' );

$travel_booking_sidebar_layout = array(
    'default-sidebar'   => array(
         'value'     => 'default-sidebar',
         'label'     => __( 'Default Sidebar', 'travel-booking' ),
         'thumbnail' => get_template_directory_uri() . '/images/default-sidebar.png'
    ),
    'left-sidebar'   => array(
         'value'     => 'left-sidebar',
         'label'     => __( 'Left Sidebar', 'travel-booking' ),
         'thumbnail' => get_template_directory_uri() . '/images/left-sidebar.png'
    ),
    'no-sidebar'     => array(
         'value'     => 'no-sidebar',
         'label'     => __( 'No Sidebar', 'travel-booking' ),
         'thumbnail' => get_template_directory_uri() . '/images/no-sidebar.png'
    ),
    'right-sidebar' => array(
         'value'     => 'right-sidebar',
    	 'label'     => __( 'Right Sidebar (default)', 'travel-booking' ),
    	 'thumbnail' => get_template_directory_uri() . '/images/right-sidebar.png'         
    )
);

function travel_booking_sidebar_layout_callback(){
    global $post , $travel_booking_sidebar_layout;
    wp_nonce_field( basename( __FILE__ ), 'travel_booking_nonce' );
?>
 
<table class="form-table">
    <tr>
        <td colspan="4"><em class="f13"><?php esc_html_e( 'Choose Sidebar Template', 'travel-booking' ); ?></em></td>
    </tr>

    <tr>
        <td>
        <?php  
            foreach( $travel_booking_sidebar_layout as $field ){  
                $layout = get_post_meta( $post->ID, '_tb_sidebar_layout', true ); 

                $checked_layout = 'default-sidebar';

                if( ! empty( $layout ) ){
                    $checked_layout = $layout;
                } 
            ?>
            <div class="radio-image-wrapper" style="float:left; margin-right:30px;">
                <label class="description">
                    <span><img src="<?php echo esc_url( $field['thumbnail'] ); ?>" alt="<?php echo esc_attr( $field['label'] ); ?>" /></span><br/>
                    <input type="radio" name="tb_sidebar_layout" value="<?php echo esc_attr( $field['value'] ); ?>" <?php checked( $field['value'], $checked_layout ); ?>/>&nbsp;<?php echo esc_html( $field['label'] ); ?>
                </label>
            </div>
            <?php } // end foreach 
            ?>
            <div class="clear"></div>
        </td>
    </tr>
</table>
 
<?php 
}

function travel_booking_save_sidebar_layout( $post_id ){
    global $travel_booking_sidebar_layout;

    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'travel_booking_nonce' ] ) || !wp_verify_nonce( $_POST[ 'travel_booking_nonce' ], basename( __FILE__ ) ) )
        return;
    
    // Stop WP from clearing custom fields on autosave
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  
        return;

    if( 'page' == $_POST['post_type'] ){  
        if( ! current_user_can( 'edit_page', $post_id ) ) return $post_id;  
    }elseif( ! current_user_can( 'edit_post', $post_id ) ){  
        return $post_id;  
    }

    $layout = isset( $_POST['tb_sidebar_layout'] ) ? sanitize_key( $_POST['tb_sidebar_layout'] ) : 'default-sidebar';

    if( array_key_exists( $layout, $travel_booking_sidebar_layout ) ){
        update_post_meta( $post_id, '_tb_sidebar_layout', $layout );
    }else{
        delete_post_meta( $post_id, '_tb_sidebar_layout' );
    }    
}
add_action( 'save_post', 'travel_booking_save_sidebar_layout' );