<?php
/**
 * Customizer section notice class file.
 *
 * @package Travel_Booking
 */

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
class Travel_Booking_Customizer_Section {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
        
        /* ajax callback for dismissable recommended plugins */
		add_action( 'wp_ajax_dismiss_section_notice', array( $this, 'dismiss_section_notice' ) );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @modified 1.1.40
	 * @access public
	 * @param  object $manager WordPress customizer object.
	 * @return void
	 */
	public function sections( $manager ) {
        
		require_once get_template_directory() . '/inc/customizer-plugin-recommend/section-notice/class-section-notice-section.php';
        $manager->register_section_type( 'Travel_Booking_Customizer_Section_Notice' ); 
        
        if ( ! class_exists( 'Travel_Booking_Toolkit' ) ) {
			$manager->add_section(
				new Travel_Booking_Customizer_Section_Notice(
					$manager, 'ta_info_travel_booking_companion', array(
						'section_text' => __( 'To have access to other sections please install and configure Travel Booking Toolkit plugin.', 'travel-booking' ),
						'slug'         =>'travel-booking-toolkit',
						'panel'        => 'home_page_setting',
						'priority'     => 451,
						'capability'   => 'install_plugins',
						'hide_notice'  => (bool) get_option( 'travel_booking_dismissed_ta_info_travel_booking_companion', false ),
						'button_screenreader' => '',
					)
				)
			);
		}
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {
        Travel_Booking_Plugin_Install_Helper::instance()->enqueue_scripts();
        wp_enqueue_style( 'travel-booking-section-notice', get_template_directory_uri() . '/inc/customizer-plugin-recommend/section-notice/css/section-notice.css', array(), TRAVEL_BOOKING_THEME_VERSION );
		wp_enqueue_script( 'travel-booking-section-notice', get_template_directory_uri() . '/inc/customizer-plugin-recommend/section-notice/js/section-notice.js', array( 'customize-controls' ), TRAVEL_BOOKING_THEME_VERSION );
		wp_localize_script(
			'travel-booking-section-notice', 'section_notice_data', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);
	}
    
    /**
     * Dismiss section notice.
     */
    public function dismiss_section_notice() {
    	$control_id = sanitize_text_field( wp_unslash( $_POST['control'] ) );
    	update_option( 'travel_booking_dismissed_' . $control_id, true );
    	echo $control_id;
    	die();
    }
}