<?php
/**
* Register all the sections for travel agency theme.
*
*
* @package    Travel_Booking_Toolkit
* @subpackage Travel_Booking_Toolkit/includes
*/
add_action( 'customize_register',  'travel_booking_toolkit_common_controls_register' );

/**
 * Include common customizer controls.
 *
 * @param [type] $wp_customize
 * @return void
 */
function travel_booking_toolkit_common_controls_register( $wp_customize ) {

    /**
     * The class responsible for defining control repeater
     */
    require_once TBT_BASE_PATH . '/includes/customize-controls/repeater/class-control-repeater.php';
    
    /**
     * The class responsible for defining repeater settings
     */
    require_once TBT_BASE_PATH . '/includes/customize-controls/repeater/class-repeater-setting.php';

    
    $current_theme = wp_get_theme();
    if ( 'travel-muni' === $current_theme->get( 'TextDomain' ) ) {
        //getting travel muni installer only when travel muni is installed
        if( class_exists( 'Travel_Muni_Plugin_Install_Helper') ){
            /**
             * The class responsible for creating control for plugin recommendation
             */
            require_once TBT_BASE_PATH . '/includes/customize-controls/class-travel-booking-toolkit-plugin-recommend.php';
        }
    }else{
         if( class_exists( 'Travel_Booking_Plugin_Install_Helper') ){
            /**
             * The class responsible for creating control for plugin recommendation
             */
            require_once TBT_BASE_PATH . '/includes/customize-controls/class-travel-booking-toolkit-plugin-recommend.php';
        }
    }
   
    if( class_exists('WP_Customize_Control') ) {
        require_once TBT_BASE_PATH . '/includes/customize-controls/select/class-select-control.php';
        $wp_customize->register_control_type( 'Travel_Booking_Toolkit_Select_Control' );
    }

	if( class_exists('WP_Customize_Control') ) {
        require_once TBT_BASE_PATH . '/includes/customize-controls/class-editor-control.php';
	}

	if ( class_exists( 'WP_Customize_control' ) && ! class_exists('Travel_Booking_Toolkit_Info_Text') ) {
        require_once TBT_BASE_PATH . '/includes/customize-controls/class-info-text.php';
	}
}
