<?php
/**
 * Tabs Settings and Controller.
 *
 * @package wp-travel-engine
 */

/**
 * Class Wp_Travel_Engine_Tabs.
 */
class Wp_Travel_Engine_Tabs {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'save_post', array( $this, 'wp_travel_engine_save_trip_price_meta_box_data' ) );
		add_action( 'set_object_terms', array( $this, 'save_difficulty_as_meta' ), 10, 4 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
	}

	public function save_difficulty_as_meta( $object_id, $terms, $tt_ids, $taxonomy ) {
		if ( 'difficulty' === $taxonomy ) {
			update_post_meta( $object_id, '_s_difficulty', $terms[0] );
		}
	}

	/**
	 * Add Metaboxes.
	 */
	public function add_meta_boxes() {
		$screens = array(
			'trip'    => array(
				'trip_pricing_id',
				__( 'WP Travel Engine - Trip Settings', 'wp-travel-engine' ),
				array( $this, 'wp_travel_engine_trip_price_metabox_callback' ),
				'trip',
				'normal',
				'high',
			),
			'enquiry' => array(
				'enquiry_tab_id',
				__( 'Enquiry Details', 'wp-travel-engine' ),
				array( $this, 'wp_travel_engine_enquiry_metabox_callback' ),
				'enquiry',
				'normal',
				'high',
			),
		);

		$screens = apply_filters( 'wte_add_meta_boxes', $screens );
		foreach ( $screens as $args ) {
			list( $id, $title, $callback ) = $args;
			$screen                        = isset( $args[3] ) ? $args[3] : null;
			$context                       = isset( $args[4] ) ? $args[4] : 'advanced';
			$priority                      = isset( $args[5] ) ? $args[5] : 'default';
			add_meta_box( $id, $title, $callback, $screen, $context, $priority );
		}
	}

	/**
	 * Tab for notice listing and settings.
	 *
	 * @param array $tab_args Tab Arguments.
	 */
	public function wp_travel_engine_trip_price_metabox_callback( $tab_args ) {
		include plugin_dir_path( __FILE__ ) . 'meta-parts/trip-metas.php';
	}

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function wp_travel_engine_save_trip_price_meta_box_data( $post_id ) {

		if ( WP_TRAVEL_ENGINE_POST_TYPE !== get_post( $post_id )->post_type ) {
			return;
		}

		$post_data = Wp_Travel_Engine_Admin::sanitize_post_data( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

		// Alter post type for previews.
		if ( isset( $post_data['wp-preview'] ) && 'dopreview' === $post_data['wp-preview'] ) {
			$post_id = (int) $post_data['post_ID'];
		}
		/*
		* We need to verify this came from our screen and with proper authorization,
		* because the save_post action can be triggered at other times.
		*/
		$wp_travel_engine_setting_saved = get_post_meta( $post_id, 'wp_travel_engine_setting', true );

		$obj = \wte_functions();

		if ( empty( $wp_travel_engine_setting_saved ) ) {
			$wp_travel_engine_setting_saved = array();
		}
		// Sanitize user input.
		if ( isset( $post_data['wp_travel_engine_setting'] ) ) {

			$wp_travel_engine_setting_saved = $obj->recursive_html_entity_decode( $wp_travel_engine_setting_saved );

			$meta_to_save = $post_data['wp_travel_engine_setting'];
			// Merge data.
			$metadata_merged_with_saved = array_merge( $wp_travel_engine_setting_saved, $meta_to_save );

			$trip_meta_checkboxes = apply_filters(
				'wp_travel_engine_trip_meta_checkboxes',
				array(
					'trip_cutoff_enable',
					'min_max_age_enable',
					'minmax_pax_enable',
				)
			);

			foreach ( $trip_meta_checkboxes as $checkbox ) {
				if ( isset( $metadata_merged_with_saved[ $checkbox ] ) && ! isset( $meta_to_save[ $checkbox ] ) ) {
					unset( $metadata_merged_with_saved[ $checkbox ] );
				}
			}

			$arrays_in_meta = array(
				'itinerary',
				'faq',
				'trip_facts',
				'trip_highlights',
			);

			$arrays_in_meta = apply_filters( 'wpte_trip_meta_array_key_bases', $arrays_in_meta );

			foreach ( $arrays_in_meta as $arr_key ) {
				if ( isset( $meta_to_save[ $arr_key ] ) && ! is_array( $meta_to_save[ $arr_key ] ) ) {
					unset( $metadata_merged_with_saved[ $arr_key ] );
				}
			}

			$settings = $metadata_merged_with_saved;

			update_post_meta( $post_id, 'wp_travel_engine_setting', $settings );

			if ( isset( $settings['trip_price'] ) ) {
				$cost = $settings['trip_price'];
				update_post_meta( $post_id, 'wp_travel_engine_setting_trip_price', $cost );
			}

			if ( isset( $settings['trip_prev_price'] ) ) {
				$prev_cost = $settings['trip_prev_price'];
				update_post_meta( $post_id, 'wp_travel_engine_setting_trip_prev_price', $prev_cost );
			}

			if ( isset( $settings['trip_duration'] ) ) {
				$duration = $settings['trip_duration'];
				update_post_meta( $post_id, 'wp_travel_engine_setting_trip_duration', $duration );
			}

			if ( isset( $post_data['wpte_gallery_id'] ) ) {
				update_post_meta( $post_id, 'wpte_gallery_id', $post_data['wpte_gallery_id'] );
			}

			// Update / Save gallery metas.
			if ( isset( $post_data['wpte_vid_gallery'] ) ) {
				update_post_meta( $post_id, 'wpte_vid_gallery', $post_data['wpte_vid_gallery'] );
			}

			if ( isset( $post_data['wp_travel_engine_trip_min_age'] ) ) {
				update_post_meta( $post_id, 'wp_travel_engine_trip_min_age', $post_data['wp_travel_engine_trip_min_age'] );
			}

			if ( isset( $post_data['wp_travel_engine_trip_max_age'] ) ) {
				update_post_meta( $post_id, 'wp_travel_engine_trip_max_age', $post_data['wp_travel_engine_trip_max_age'] );
			}
		}
	}

	/**
	 * Tab for notice listing and settings.
	 *
	 * @param array $tab_args Tab Arguments.
	 */
	public function wp_travel_engine_enquiry_metabox_callback( $tab_args ) {
		include WP_TRAVEL_ENGINE_BASE_PATH . '/admin/meta-parts/enquiry.php';
	}

}

new Wp_Travel_Engine_Tabs();
