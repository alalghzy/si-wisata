<?php
namespace WPTravelEngine\Core;

/**
 * REST API.
 */
class REST_API {
	public function __construct() {
		$this->post_type = WP_TRAVEL_ENGINE_POST_TYPE;
		$this->init_hooks();
	}

	private function init_hooks() {
		add_filter( "rest_prepare_{$this->post_type}", array( __CLASS__, 'filter_rest_data_trip' ), 10, 3 );
		add_filter( "rest_{$this->post_type}_query", array( __CLASS__, 'filter_rest_query_trip' ), 10, 2 );

		add_filter( "rest_trip-packages_query", array( __CLASS__, 'filter_rest_query_trip_packages' ), 10, 2 );

		foreach ( array( 'trip_types', 'destination', 'activities' ) as $taxonomy ) {
			add_filter( "rest_prepare_{$taxonomy}", array( __CLASS__, 'rest_prepare_taxonomy' ), 10, 3 );
		}
	}

	public static function filter_rest_query_trip_packages( $args, $request ) {
		$params = (object) $request->get_params();
		if ( isset( $params->trip_id ) ) {
			$package_ids = get_post_meta( $params->trip_id, 'packages_ids', true );
			$args['post__in'] = is_array( $package_ids ) ? $package_ids : array();
		}

		return $args;
	}

	public static function filter_rest_query_trip( $args, $request ) {
		$params = (object) $request->get_params();
		$listby = array(
			'featured' => array(
				'meta_key'   => 'wp_travel_engine_featured_trip',
				'meta_value' => 'yes',
			),
			'onsale'   => array(
				'meta_key'   => 'wp_travel_engine_setting',
				'meta_value' => 's:4:"sale";s:1:"1";',
			),
		);
		if ( isset( $params->listby ) && in_array( $params->listby, array_keys( $listby ), true ) ) {
			$args['meta_query'] = array(
				array(
					'key'     => $listby[ $params->listby ]['meta_key'],
					'value'   => $listby[ $params->listby ]['meta_value'],
					'compare' => 'LIKE',
				),
			);
		}
		return $args;
	}

	public static function filter_rest_data_trip( $response, $post, $request ) {

		$additional_data = (array) \wte_trip_get_trip_rest_metadata( $post->ID );
		$data            = (object) $response->data;
		foreach ( $additional_data as $data_key => $data_value ) {
			$data->{$data_key} = $data_value;
		}

		$response->data = (array) $data;

		return $response;
	}

	public static function rest_prepare_taxonomy( $response, $item, $request ) {
		$data = (object) $response->data;

		$thumbnail_id = \get_term_meta( $item->term_id, 'category-image-id', true );

		$data->thumbnail = \wte_get_media_details( $thumbnail_id );

		$response->data = (array) $data;

		return $response;
	}

	/**
	 * Default rest field get callback while registering rest field.
	 *
	 * @since 5.3.0
	 */
	public static function default_rest_field_get_callback( $prepared, $field ) {
		return get_post_meta( $prepared['id'], $field, true );
	}
}

new REST_API();
