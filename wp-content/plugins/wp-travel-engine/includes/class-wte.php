<?php
/**
 * WTE - Object.
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WTE' ) ) :

	/**
	 * Main WTE Object
	 *
	 * @since 2.2.6
	 */
	final class WTE {

		/**
		 * The single instance of the class.
		 *
		 * @var WP Travel Engine
		 * @since 2.2.6
		 */
		protected static $_instance = null;

		/**
		 * Main WTE Instance.
		 * Ensures only one instance of WTE is loaded or can be loaded.
		 *
		 * @since 2.2.6
		 * @static
		 * @see WTE()
		 * @return WTE - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * WTE Constructor.
		 *
		 * @since 2.2.6
		 */
		function __construct() {

			include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wte-session.php';
			include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wte-notices.php';

			$this->session = new WTE_Session();
			$this->notices = new WTE_Notices();
		}
	}
endif;
/**
 * Main instance of WP Travel Engine.
 *
 * Returns the main instance of WTE to prevent the need to use globals.
 *
 * @since  2.2.6
 * @return WP Travel Engine Object
 */
function WTE() {
	return WTE::instance();
}

// Start WP Travel Engine Object.
WTE();
