<?php
/**
 * Travel Booking Toolkit Helper Functions
 * 
 * @package Travel_Booking_Toolkit
 */
/**
 * Checks is WP Travel Engine is activated.
 *
 * @return void
 */
if ( ! function_exists( 'tbt_is_tbt_activated' ) ) {
    function tbt_is_tbt_activated() {
        return class_exists( 'Wp_Travel_Engine' ) ? true : false;
    }
}

function travel_booking_toolkit_is_header_five_activated(){
    return ( get_theme_mod( 'header_layout','five' ) === 'five' ) ? true : false;        
}

/**
 * Check if WP Travel Engine - Trip Reviews Plugin is installed
*/
function travel_booking_toolkit_is_wte_trip_review_activated(){
    return class_exists( 'Wte_Trip_Review_Init' ) ? true : false;
}


if ( ! function_exists( 'travel_booking_toolkit_get_fontawesome_ajax' ) ) :
/**
 * Return an array of all icons.
 */
function travel_booking_toolkit_get_fontawesome_ajax() {
    // Bail if the nonce doesn't check out
    if ( ! isset( $_POST['travel_booking_toolkit_customize_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['travel_booking_toolkit_customize_nonce'] ), 'travel_booking_toolkit_customize_nonce' ) ) {
        wp_die();
    }

    // Do another nonce check
    check_ajax_referer( 'travel_booking_toolkit_customize_nonce', 'travel_booking_toolkit_customize_nonce' );

    // Bail if user can't edit theme options
    if ( ! current_user_can( 'edit_theme_options' ) ) {
        wp_die();
    }

    // Get all of our fonts
    $fonts = travel_booking_toolkit_get_fontawesome_list();
    
    ob_start();
    if( $fonts ){ ?>
        <ul class="font-group">
            <?php 
                foreach( $fonts as $font ){
                    echo '<li data-font="' . esc_attr( $font ) . '"><i class="' . esc_attr( $font ) . '"></i></li>';                        
                }
            ?>
        </ul>
        <?php
    }
    echo ob_get_clean();

    // Exit
    wp_die();
}
endif;
add_action( 'wp_ajax_travel_booking_toolkit_get_fontawesome_ajax', 'travel_booking_toolkit_get_fontawesome_ajax' );