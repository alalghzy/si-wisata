<?php
/**
 * Singleton trait for plugin.
 *
 * @package wp-travel-engine
 * @since 5.3.0
 */

namespace WPTravelEngine\Traits;

/**
 * Singleton trait.
 */
trait Singleton {
	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function __construct() {}

	/**
	 * Initialize singleton instance of the class. will return this instance if created otherwise create new instance first.
	 *
	 * @since 1.0.0
	 * @return object WPTravelEngine Main singleton instance.
	 */
	final public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Prevent cloning.
	 *
	 * @since 1.0.0
	 */
	private function __clone() {}

	/**
	 * Prevent unserializing.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {}
}
