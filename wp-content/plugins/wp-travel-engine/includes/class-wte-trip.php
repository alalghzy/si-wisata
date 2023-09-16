<?php
/**
 * Trip Handler Class.
 */
namespace WPTravelEngine\Posttype;

class Trip {

	/**
	 * Class instance holder.
	 *
	 * @var WPTravelEngine\Posttype\Trip
	 */
	protected static $instance = null;

	/**
	 * WP post Object.
	 *
	 * @var WP_Post
	 */
	public $post = null;

	/**
	 * Trip Packages post objects.
	 *
	 * @var array
	 */
	public $packages = array();

	public function __construct( $post ) {
		// add_action( 'wp', array( $this, 'initialize' ) );
		$this->post = $post;

		$this->initialize();
	}

	public function initialize() {
		$_post = $this->post;

		$trip_version = get_post_meta( $_post->ID, 'trip_version', true );
		if ( empty( $trip_version ) ) {
			$trip_version = '1.0.0';
		}

		$this->trip_version = $trip_version;

		$this->use_legacy_trip = defined( 'USE_WTE_LEGACY_VERSION' ) && USE_WTE_LEGACY_VERSION;

		$this->set_packages();
		$this->set_default_package();
	}

	public function set_packages() {

		if ( ! $this->post ) {
			return array();
		}

		// Get Trip package Ids.
		$package_ids = $this->{'packages_ids'};

		if ( ! is_array( $package_ids ) ) {
			$package_ids = array();
		}

		if ( ! empty( $package_ids ) ) {
			$packages = \get_posts(
				array(
					'post_type' => 'trip-packages',
					'include'   => $package_ids,
				)
			);
		} else {
			$packages = array();
		}

		$_packages = array();

		foreach ( $packages as $package ) {
			$_packages[ $package->ID ] = $package;
		}

		$this->packages = $_packages;
	}

	public function set_default_package() {
		$packages = $this->packages;

		$lowest_price              = 0;
		$primary_pricing_category  = get_option( 'primary_pricing_category', 0 );
		$package_with_lowest_price = is_array( $packages ) ? current( $packages ) : null;
		reset( $packages );

		$has_sale           = false;
		$sale_in_percentage = 0;

		$default_trip_price = 0;
		$default_sale_price = 0;
		foreach ( $packages as $package ) {
			$package_categories = (object) $package->{'package-categories'};

			// Temporary fix.
			$term = get_term_by( 'slug', 'adult', 'trip-packages-categories' );

			if ( ! $primary_pricing_category ) {
				return $package;
			}

			$term = get_term( $primary_pricing_category );

			if ( ! ( $term instanceof \WP_Term ) ) {
				return $package;
			}

			if ( is_object( $term ) && isset( $package_categories->prices[ $term->term_id ] ) ) {
				$category_price    = (float) $package_categories->prices[ $term->term_id ];
				$trip_price        = $category_price;
				$category_has_sale = isset( $package_categories->enabled_sale[ $term->term_id ] ) && ( 1 === (int) $package_categories->enabled_sale[ $term->term_id ] );
				if ( $category_has_sale ) {
					$trip_price     = $category_price;
					$category_price = (float) $package_categories->sale_prices[ $term->term_id ];
				}
				if ( $lowest_price > 0 ) {
					if ( (float) $category_price < $lowest_price ) {
						$lowest_price              = (float) $category_price;
						$package_with_lowest_price = $package;
						$default_trip_price        = $trip_price;
						if ( $category_has_sale && $trip_price && $trip_price > $category_price ) {
							$has_sale           = $category_has_sale;
							$sale_in_percentage = ( $trip_price - $category_price ) / $trip_price * 100;
							$default_sale_price = $category_price;
						}
					}
				} else {
					if ( (float) $category_price > 0 ) {
						$lowest_price              = (float) $category_price;
						$package_with_lowest_price = $package;
						$default_trip_price        = $trip_price;
						if ( $category_has_sale && $trip_price && $trip_price > $category_price ) {
							$has_sale           = $category_has_sale;
							$sale_in_percentage = ( $trip_price - $category_price ) / $trip_price * 100;
							$default_sale_price = $category_price;
						}
					}
				}
			}
		}

		$this->has_sale        = $has_sale;
		$this->price           = (float) $default_trip_price;
		$this->sale_price      = (float) $default_sale_price;
		$this->sale_percentage = round( $sale_in_percentage );

		$this->default_package = $package_with_lowest_price;
	}

	public function __isset( $key ) {
		return isset( $this->{$key} );
	}

	public function __get( $key ) {

		if ( $this->__isset( $key ) ) {
			return $this->{$key};
		}
		switch ( $key ) {
			case 'has_group_discount':
				return \apply_filters( 'has_packages_group_discounts', false, $this->post->ID );
			default:
				return \get_post_meta( $this->post->ID, $key, true );
		}
	}

	public function has_group_discount() {
		$packages = $this->packages;

		$primary_pricing_category_id = get_option( 'primary_pricing_category', 0 );

		if ( $primary_pricing_category_id ) {
			$term = get_term( $primary_pricing_category_id );
		}
		foreach ( $packages as $package ) {
			$package_categories = (object) $package->{'package-categories'};

			$package_categories_ids = ( isset( $package_categories->{'c_ids'} ) ) ? $package_categories->{'c_ids'} : array();

			if ( ! $primary_pricing_category_id ) {
				$primary_pricing_category_id = ! empty( $package_categories_ids ) && is_array( $package_categories_ids ) ? array_shift( $package_categories_ids ) : 0;
			}
			if ( ! $primary_pricing_category_id ) {
				return false;
			}

			$term = get_term( $primary_pricing_category_id );

			if ( ! ( $term instanceof \WP_Term ) ) {
				return false;
			}

			if ( isset( $package_categories->enabled_group_discount[ $term->term_id ] ) && '1' == $package_categories->enabled_group_discount[ $term->term_id ] ) {
				return true;
			}
		}

		return false;
	}

	public static function instance( $trip_id ) {

		$trip_id = (int) $trip_id;
		if ( ! $trip_id ) {
			return false;
		}

		$_trip = wp_cache_get( $trip_id, 'trips' );

		if ( ! $_trip ) {
			$_trip = new self( get_post( $trip_id ) );
			wp_cache_add( $trip_id, $_trip, 'trips' );
		}

		return $_trip;
	}

}

add_action(
	'wp',
	function() {
		global $post;

		if ( $post ) {
			$GLOBALS['wtetrip'] = Trip::instance( $post->ID );
		}
	}
);
