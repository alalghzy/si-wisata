<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wptravelengine.com
 * @since      1.0.0
 *
 * @package    Travel_Booking_Toolkit
 * @subpackage Travel_Booking_Toolkit/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Travel_Booking_Toolkit
 * @subpackage Travel_Booking_Toolkit/admin
 * @author     wptravelengine <info@wptravelengine.com>
 */
class Travel_Booking_Toolkit_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = TBT_VERSION;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Travel_Booking_Toolkit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Travel_Booking_Toolkit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/travel-booking-toolkit-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Travel_Booking_Toolkit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Travel_Booking_Toolkit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'font-awesome', plugin_dir_url( __FILE__ ) . 'js/fontawesome/all.js', array( 'jquery'), '5.6.3', true );
		wp_enqueue_script( 'v4-shims', plugin_dir_url( __FILE__ ) . 'js/fontawesome/v4-shims.js', array( 'jquery'), '5.6.3', true );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/travel-booking-toolkit-admin.js', array( 'jquery' ), $this->version, true );

		$confirming = array( 
					'are_you_sure'       => __( 'Are you sure?', 'travel-booking-toolkit' ),
					);
		wp_localize_script( $this->plugin_name, 'travel_booking_toolkit_uploader', array(
        	'upload' => __( 'Upload', 'travel-booking-toolkit' ),
        	'change' => __( 'Change', 'travel-booking-toolkit' ),
        	'msg'    => __( 'Please upload valid image file.', 'travel-booking-toolkit' )
    	));
		wp_localize_script( $this->plugin_name, 'confirming', $confirming );

		wp_localize_script( $this->plugin_name, 'sociconsmsg', array(
				'msg' => __( 'Are you sure you want to delete this Contact?', 'travel-booking-toolkit' )));

	}

	function travel_booking_toolkit_client_logo_template(){ ?>
		<div class="travel_booking_toolkit-client-logo-template">
			<div class="link-image-repeat" data-id=""><span class="cross"><i class="fas fa-times"></i></span>
	            <p>
	            <div class="widget-upload">
	            	<label for="widget-wptravelengine_client_logo_widget-2-image"><?php _e('Upload Image','travel-booking-toolkit');?></label><br>
	            	<input id="widget-wptravelengine_client_logo_widget-2-image" class="travel_booking_toolkit-upload" type="hidden" name="widget-wptravelengine_client_logo_widget[2][image][]" value="" placeholder="No file chosen">
					<input id="upload-widget-wptravelengine_client_logo_widget-2-image" class="travel_booking_toolkit-upload-button button" type="button" value="Upload">
					<div class="travel_booking_toolkit-screenshot" id="widget-wptravelengine_client_logo_widget-2-image-image"></div>
				</div>
				</p>
	            <p>
	                <label for="widget-wptravelengine_client_logo_widget-2-link"><?php _e('Featured Link','travel-booking-toolkit');?></label> 
	                <input class="widefat featured-link" id="widget-wptravelengine_client_logo_widget-2-link" name="widget-wptravelengine_client_logo_widget[2][link][]" type="text" value="">            
	            </p>
        	</div>
	    </div>
	<?php
	echo '<style>.travel_booking_toolkit-client-logo-template{display:none;}</style>';
	}

	public function travel_booking_toolkit_icon_list_enqueue(){
		$obj = new Travel_Booking_Toolkit_Functions;
		$socicons = $obj->travel_booking_toolkit_icon_list();
		echo '<div class="travel_booking_toolkit-icons-wrap-template"><div class="travel_booking_toolkit-icons-wrap"><ul class="travel_booking_toolkit-icons-list">';
		foreach ($socicons as $socicon) {
			if($socicon == 'rss'){
				echo '<li><i class="fas fa-'.$socicon.'"></i></li>';
			}
			else{
				echo '<li><i class="fab fa-'.$socicon.'"></i></li>';
			}
		}
		echo'</ul></div></div>';
		echo '<style>
		.travel_booking_toolkit-icons-wrap{
			display:none;
		}
		</style>';
	}

	function add_tax_to_pll( $taxonomies, $is_settings ) {
	    if ( $is_settings ) {
	        unset( $taxonomies['destination'] );
	        unset( $taxonomies['activities'] );
	        unset( $taxonomies['trip_types'] );
	    } else {
	        $taxonomies['destination'] = 'destination';
	        $taxonomies['activities']  = 'activities';
	        $taxonomies['trip_types']  = 'trip_types';
	    }
	    return $taxonomies;
	}

	/**
    * Paypal activation notice.
    * @since 1.1.1
    */
	function travel_booking_toolkit_premium_addons_activate_notice() {
	    global $current_user;
      	$user_id = $current_user->ID;
      	if (get_user_meta($user_id, 'tbt-premium-addons-notice',true)!='true') {

      		$message = '';

      		if ( ! class_exists( 'Wp_Travel_Engine_Group_Discount' ) ){
      			$message .= '<a href="'. esc_url( 'https://wptravelengine.com/downloads/group-discount/' ).'">'. esc_html__( 'WP Travel Engine - Group Discount', 'travel-booking-toolkit' ) .'</a>, ';
      		}

      		if ( ! class_exists( 'Wte_Trip_Review_Init' ) ){
      			$message .= '<a href="'. esc_url( 'https://wptravelengine.com/downloads/wte-trip-review/' ).'">'. esc_html__( 'WP Travel Engine - Trip Reviews', 'travel-booking-toolkit' ) .'</a>, ';
      		}

      		if ( ! class_exists( 'WTE_Fixed_Starting_Dates' ) ){
      			$message .= '<a href="'. esc_url( 'https://wptravelengine.com/downloads/trip-fixed-starting-dates/' ).'">'. esc_html__( ' WP Travel Engine - Trip Fixed Starting Dates', 'travel-booking-toolkit' ).'</a>&nbsp;';
      		}

      		$premium_addons = apply_filters( 'wte_premium_addons',  $message );

      		if( ! empty( $premium_addons ) ){
				printf( '<div class="notice notice-info is-dismissible"><p>%1$s'. esc_html__( 'Travel Booking Theme', 'travel-booking-toolkit' ) .'%2$s '. esc_html__( 'supports following plugins.', 'travel-booking-toolkit' ).' %3$s. '. esc_html__( 'In order to achieve full functioning of theme like in our demo site, please', 'travel-booking-toolkit' ) .' %4$s '. esc_html__( 'Install and Active', 'travel-booking-toolkit' ) .'%5$s '. esc_html__( 'these recommended plugins.', 'travel-booking-toolkit' ) .' <a href="?tbt-premium-addons-notice=1">'. esc_html__( 'Dismiss', 'travel-booking-toolkit' ) .'</a></p></div>', '<b>', '</b>', wp_kses_post( $premium_addons ), '<b>', '</b>' );  
      		}
		}
	}

	function travel_booking_toolkit_premium_addons_activate_notice_ignore() {
      global $current_user;
      $user_id = $current_user->ID;
      if (isset($_GET['tbt-premium-addons-notice']) && $_GET['tbt-premium-addons-notice']='1') {
        add_user_meta($user_id, 'tbt-premium-addons-notice', 'true', true);
      }
    }

}
