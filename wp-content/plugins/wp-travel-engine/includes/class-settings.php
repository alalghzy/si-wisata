<?php
/**
 *
 * @since 5.5.3
 */

namespace WPTravelEngine\Core;

class Settings {

    protected static $instance = null;

    public function __construct() {
        $this->global_settings = get_option( 'wp_travel_engine_settings', array() );
    }

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __get( $name ) {
        if ( array_key_exists( $name, (array) $this->global_settings ) ) {
            return $this->global_settings[ $name ];
        }

        return null;
    }

    public function __isset( $name ) {
        return isset( $this->global_settings[ $name ] );
    }

}
