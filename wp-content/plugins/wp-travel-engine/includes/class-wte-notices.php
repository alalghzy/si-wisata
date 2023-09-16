<?php
/**
 * WP Travel Engine Global Notices.
 *
 * @package WP_Travel_Engine.
 */

/**
 * WTE_Notices class
 *
 * Handle custom notices display.
 */

class WTE_Notices {

	/**
	 * Errors array
	 *
	 * @var array
	 */
	private $errors = array();

	/**
	 * Success messages.
	 *
	 * @var array
	 */
	private $success = array();

	/**
	 * Class Constructor.
	 */
	public function __construct() {

	}

	/**
	 * Add notices
	 *
	 * @param [type] $value
	 * @param string $type
	 * @return void
	 */
	function add( $value, $type = 'error' ) {

		if ( empty( $value ) ) {
			return;
		}

		if ( 'error' === $type ) {

			$this->errors = wp_parse_args( array( $value ), $this->errors );

			WTE()->session->set( 'wp_travel_engine_errors', $this->errors );

		} elseif ( 'success' === $type ) {

			$this->success = wp_parse_args( array( $value ), $this->success );

			WTE()->session->set( 'wp_travel_engine_success', $this->success );
		}
	}

	/**
	 * Get notices
	 *
	 * @param string  $type
	 * @param boolean $destroy
	 * @return void
	 */
	function get( $type = 'error', $destroy = true ) {

		if ( 'error' === $type ) {

			$errors = WTE()->session->get( 'wp_travel_engine_errors' );

			if ( $destroy ) {

				$this->destroy( $type );

			}

			return $errors;

		} elseif ( 'success' === $type ) {

			$success = WTE()->session->get( 'wp_travel_engine_success' );

			if ( $destroy ) {
				$this->destroy( $type );
			}

			return $success;
		}
	}

	/**
	 * Destroy message.
	 *
	 * @param [type] $type
	 * @return void
	 */
	function destroy( $type ) {

		if ( 'error' === $type ) {

			$this->errors = array();

			WTE()->session->set( 'wp_travel_engine_errors', $this->errors );

		} elseif ( 'success' === $type ) {

			$this->success = array();

			WTE()->session->set( 'wp_travel_engine_success', $this->success );
		}
	}

	/**
	 * Print notices.
	 *
	 * @param [type]  $type
	 * @param boolean $destroy
	 * @return void
	 */
	function print_notices( $type, $destroy = true ) {
		$notices = $this->get( $type, $destroy );

		if ( empty( $notices ) ) {
			return;
		}

		if ( $notices && 'error' === $type ) {
			foreach ( $notices as $key => $notice ) {
				if ( 'error ' === $notice ) {
					return;
				}
				echo '<div class="wp-travel-engine-error-msg">' . esc_html( $notice ) . '</div>';
			}
			return;
		} elseif ( $notices && 'success' === $type ) {
			foreach ( $notices as $key => $notice ) {
				echo '<div class="wp-travel-engine-success-msg">' . esc_html( $notice ) . '</div>';
			}
			return;
		}
		return false;

	}
}
