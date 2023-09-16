<?php
/**
 * WP Travel Engine Trip Code.
 *
 * @since __addonmigration__
 */
namespace WPTravelEngine\Modules;

class CouponCode {
	public function __construct() {
		defined( 'WP_TRAVEL_ENGINE_COUPONS_POST_TYPE' ) || define( 'WP_TRAVEL_ENGINE_COUPONS_POST_TYPE', 'wte-coupon' );
		$this->includes();
		$this->init_hooks();
	}

	private function includes() {
		include_once dirname( __FILE__ ) . '/class-coupon-code-ajax.php';
	}

	/**
	 * Initialize Hooks.
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ) );
		// Column Header.
		add_filter(
			'manage_edit-wte-coupon_columns',
			function( $columns ) {
				return array(
					'cb'              => '<input type="checkbox" />',
					'title'           => _x( 'Name', 'column name', 'wp-travel-engine' ),
					'coupon_code'     => _x( 'Code', 'column name', 'wp-travel-engine' ),
					'discount_value'  => _x( 'Discount Value', 'column name', 'wp-travel-engine' ),
					'max_users'       => _x( 'Max Uses', 'column name', 'wp-travel-engine' ),
					'used_so_far'     => _x( 'Usage Count', 'column name', 'wp-travel-engine' ),
					'expiration_date' => _x( 'Expiration Date', 'column name', 'wp-travel-engine' ),
					'coupon_status'   => __( 'Status', 'wp-travel-engine' ),
					'date'            => __( 'Created Date', 'wp-travel-engine' ),
				);
			}
		);

		// Column Rows.
		add_action(
			'manage_wte-coupon_posts_custom_column',
			function( $column_name, $id ) {
				if ( is_callable( array( $this, "get_column_value_{$column_name}" ) ) ) {
					call_user_func( array( $this, "get_column_value_{$column_name}" ), $id );
				}
			},
			10,
			2
		);

		add_filter(
			'post_updated_messages',
			function( $messages ) {
				global $post, $post_ID;

				$post_object = get_post_type_object( 'wte-coupon' );

				$messages['wte-coupon'] = array(
					0  => '', // Unused. Messages start at index 1.
					1  => sprintf( __( '%1$s updated. <a href="%2$s">View %3$s</a>', 'wp-travel-engine' ), $post_object->labels->singular_name, esc_url( get_permalink( $post_ID ) ), $post_object->labels->singular_name ),
					2  => __( 'Custom field updated.', 'wp-travel-engine' ),
					3  => __( 'Custom field deleted.', 'wp-travel-engine' ),
					4  => sprintf( __( '%s updated.', 'wp-travel-engine' ), $post_object->labels->singular_name ),
					5  => isset( $_GET['revision'] ) ? sprintf( __( '%1$s restored to revision from %2$s', 'wp-travel-engine' ), $post_object->labels->singular_name, wp_post_revision_title( (int) wte_clean( wp_unslash( $_GET['revision'] ) ), false ) ) : false, // phpcs:ignore
					6  => sprintf( __( '%1$s published. <a href="%2$s">View %3$s</a>', 'wp-travel-engine' ), $post_object->labels->singular_name, esc_url( get_permalink( $post_ID ) ), $post_object->labels->singular_name ),
					7  => sprintf( __( '%s saved.', 'wp-travel-engine' ), $post_object->labels->singular_name ),
					8  => sprintf( __( '%1$s submitted. <a target="_blank" href="%2$s">Preview %3$s</a>', 'wp-travel-engine' ), $post_object->labels->singular_name, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ), $post_object->labels->singular_name ),
					9  => sprintf( __( '%1$s scheduled for: <strong>%2$s</strong>. <a target="_blank" href="%3$s">Preview %4$s</a>', 'wp-travel-engine' ), $post_object->labels->singular_name, date_i18n( __( 'M j, Y @ G:i', 'wp-travel-engine' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ), $post_object->labels->singular_name ),
					10 => sprintf( __( '%1$s draft updated. <a target="_blank" href="%2$s">Preview %3$s</a>', 'wp-travel-engine' ), $post_object->labels->singular_name, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ), $post_object->labels->singular_name ),
				);
				return $messages;
			}
		);

		add_action(
			'add_meta_boxes',
			function( $post_type ) {
				if ( WP_TRAVEL_ENGINE_COUPONS_POST_TYPE !== $post_type ) {
					return;
				}
				wp_enqueue_style( 'jquery-ui' );
				add_action(
					'admin_head',
					function() {
						echo '<style>span.wp-travel-engine-info-msg{background:#1eb823;padding:10px;color:#fff;font-weight:800}span.wp-travel-engine-error-msg{background:#e63333;padding:10px;color:#fff;font-weight:800}.select2-container-multi .select2-choices{min-height:26px;max-width:440px}</style>';
					}
				);
				\add_meta_box( WP_TRAVEL_ENGINE_COUPONS_POST_TYPE . '-details', __( 'Coupon Options', 'wp-travel-engine' ), array( __CLASS__, 'add_coupon_metabox_field_callback' ), WP_TRAVEL_ENGINE_COUPONS_POST_TYPE, 'normal', 'high' );
			},
			10,
			2
		);

		add_action( 'save_post_wte-coupon', array( __CLASS__, 'save_wte_coupon_metabox' ) );

		add_action( 'wp_travel_engine_before_billing_form', array( __CLASS__, 'checkout_coupon_form' ) );

	}

	/**
	 * Checkout Coupon Form.
	 *
	 * @return void
	 */
	public static function checkout_coupon_form() {
		wte_get_template( 'checkout-coupon-form.php', array(), '', WP_TRAVEL_ENGINE_ABSPATH . 'includes/modules/coupon-code/views' );
	}

	private static function can_update_coupon( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// If this is just a revision, don't save.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		return ! ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) && current_user_can( 'edit_post', $post_id ) && ! wp_is_post_revision( $post_id );
	}

	public static function get_coupon_edit_form_schema() {
		$schema = array(
			'wp_travel_engine_coupon_code' => array(
				'type'              => 'text',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			),
			'wp_travel_engine_coupon'      => array(
				'type'  => 'object',
				'items' => array(
					'general'     => array(
						'type'  => 'object',
						'items' => array(
							'coupon_type'        => array(
								'type'              => 'text',
								'sanitize_callback' => 'wp_filter_nohtml_kses',
							),
							'coupon_value'       => array(
								'type' => 'number',
							),
							'coupon_start_date'  => array(
								'type'              => 'text',
								'sanitize_callback' => 'wp_filter_nohtml_kses',
							),
							'coupon_expiry_date' => array(
								'type'              => 'text',
								'sanitize_callback' => 'wp_filter_nohtml_kses',
							),

						),
					),
					'restriction' => array(
						'type'  => 'object',
						'items' => array(
							'coupon_limit_number' => array(
								'type' => 'number',
							),
						),
					),
				),
			),
		);

		return $schema;
	}

	public static function save_wte_coupon_metabox( $coupon_id ) {
		$coupon = get_post( $coupon_id );
		if ( ! is_null( $coupon ) && WP_TRAVEL_ENGINE_COUPONS_POST_TYPE !== $coupon->post_type ) {
			return; // No works here.
		}
		// phpcs:disable
		empty( $_POST['wp_travel_engine_coupon_code'] ) || update_post_meta( $coupon->ID, 'wp_travel_engine_coupon_code', wp_filter_nohtml_kses( wp_unslash( $_POST['wp_travel_engine_coupon_code'] ) ) );

		if ( isset( $_POST['wp_travel_engine_coupon'] ) ) {
			$_data = array();
			$coupon_data = wte_clean(wp_unslash( $_POST['wp_travel_engine_coupon'] ));
			if ( isset( $coupon_data['general'] ) ) {
				$general = $coupon_data['general'];
				array_walk( $general, function( &$item ) {
					$item = wp_filter_nohtml_kses( $item );
				} );
				$_data['general'] = $general;
			}
			if ( isset( $coupon_data['restriction'] ) ) {
				$restriction = $coupon_data['restriction'];
				array_walk( $restriction, function( &$item ) {
					if( is_array( $item ) ) {
						$item = array_map( 'wp_filter_nohtml_kses', $item );
					} else {
						$item = wp_filter_nohtml_kses( $item );
					}
				}  );
				$_data['restriction'] = $restriction;
			}
			update_post_meta( $coupon->ID, 'wp_travel_engine_coupon_metas', $_data );
		}
		// phpcs:enable
	}

	public static function is_coupon_date_valid( $coupon_id ) {
		if ( empty( $coupon_id ) ) {
			return false;
		}

		$coupon_metas       = get_post_meta( $coupon_id, 'wp_travel_engine_coupon_metas', true );
		$general_tab        = isset( $coupon_metas['general'] ) ? $coupon_metas['general'] : array();
		$coupon_expiry_date = isset( $general_tab['coupon_expiry_date'] ) ? $general_tab['coupon_expiry_date'] : '';

		$coupon_start_date = isset( $general_tab['coupon_start_date'] ) ? $general_tab['coupon_start_date'] : '';

		// Check Coupon Status.
		$coupon_status = get_post_status( $coupon_id );

		if ( 'publish' !== $coupon_status ) {
			return false;
		}

		$date_now = new \DateTime();
		$date_now->setTime( 0, 0, 0, 0 );
		if ( ! empty( $coupon_expiry_date ) ) {

			try {
				$coupon_start_date = new \DateTime( $coupon_start_date );
			} catch ( \Exception $e ) {
				return false;
			}
			try {
				$coupon_end_date = new \DateTime( $coupon_expiry_date );
			} catch ( \Exception $e ) {
				return false;
			}

			return $date_now <= $coupon_end_date && $date_now >= $coupon_start_date;
		} else {
			try {
				$coupon_start_date = new \DateTime( $coupon_start_date );
				return $date_now >= $coupon_start_date;
			} catch ( \Exception $e ) {
				return false;
			}
		}
	}

	public static function coupon_can_be_applied( $coupon_id, $trip_id ) {

		$restricted_trips = self::get_coupon_meta( $coupon_id, 'restriction', 'restricted_trips' );

		return empty( $restricted_trips ) || in_array( $trip_id, $restricted_trips );
	}

	public static function get_usage_count( $coupon_id ) {
		return (int) get_post_meta( $coupon_id, 'wp_travel_engine_coupon_usage_count', true );
	}

	public static function update_usage_count( $coupon_id, $increment_by = 1 ) {
		$count = self::get_usage_count( $coupon_id ) + (int) $increment_by;
		$count = $count < 1 ? 0 : $count;
		update_post_meta( $coupon_id, 'wp_travel_engine_coupon_usage_count', $count );
	}

	public static function get_discount_type( $coupon_id ) {
		$value = self::get_coupon_meta( $coupon_id, 'general', 'coupon_type' );
		return $value ? $value : 'fixed';
	}

	public static function get_discount_value( $coupon_id ) {
		$value = self::get_coupon_meta( $coupon_id, 'general', 'coupon_value' );
		return $value ? (float) $value : 0;
	}

	public static function get_coupon_meta( $coupon_id, $tab, $key ) {

		if ( empty( $coupon_id ) || empty( $key ) || empty( $tab ) ) {
			return false;
		}

		$coupon_metas = get_post_meta( $coupon_id, 'wp_travel_engine_coupon_metas', true );

		return isset( $coupon_metas[ $tab ][ $key ] ) ? $coupon_metas[ $tab ][ $key ] : false;

	}

	public static function get_coupon_status( $coupon_id ) {
		if ( ! $coupon_id || empty( $coupon_id ) ) {
			return false;
		}

		if ( ! self::is_coupon_date_valid( $coupon_id ) ) {
			return 'inactive';
		}

		// Activity by usage count.
		$usage_count = self::get_usage_count( $coupon_id );
		$limit       = self::get_coupon_meta( $coupon_id, 'restriction', 'coupon_limit_number' );

		return '' !== $limit && ( (int) $limit <= $usage_count ) ? 'inactive' : 'active';

	}

	private function get_column_value_coupon_status( $coupon_id ) {
		$coupon_status = self::get_coupon_status( $coupon_id );
		if ( 'active' === $coupon_status ) {
			print(
				'<span class="wp-travel-engine-info-msg">'
				. esc_html__( 'Active', 'wp-travel-engine' )
				. '</span>'
			);
		} else {
			print(
				'<span class="wp-travel-engine-info-msg">'
				. esc_html__( 'Inactive', 'wp-travel-engine' )
				. '</span>'
			);
		}
	}

	private function get_column_value_discount_value( $coupon_id ) {
		$discount_type  = self::get_coupon_meta( $coupon_id, 'general', 'coupon_type' );
		$discount_value = self::get_coupon_meta( $coupon_id, 'general', 'coupon_value' );

		$discount_value = ( 'percentage' === $discount_type ) ? $discount_value . ' (%) ' : wte_get_formated_price( $discount_value );
		printf(
			'<span><strong>%1$s</strong></span>',
			$discount_value
		);
	}

	private function get_column_value_max_users( $coupon_id ) {
		$max_users = self::get_coupon_meta( $coupon_id, 'restriction', 'coupon_limit_number' );
		printf( '<span><strong>%1$s</strong></span>', $max_users );
	}
	private function get_column_value_used_so_far( $coupon_id ) {
		printf( '<span><strong>%1$s</strong></span>', self::get_usage_count( $coupon_id ) );
	}
	private function get_column_value_expiration_date( $coupon_id ) {
		printf( '<span><strong>%1$s</strong></span>', self::get_coupon_meta( $coupon_id, 'general', 'coupon_expiry_date' ) );
	}

	private function get_column_value_coupon_code( $coupon_id ) {
		echo '<span><strong>' . get_post_meta( $coupon_id, 'wp_travel_engine_coupon_code', true ) . '</strong></span>';
	}

	private function register_post_type() {
		$labels = array(
			'name'               => _x( 'Coupons', 'post type general name', 'wp-travel-engine' ),
			'singular_name'      => _x( 'Coupon', 'post type singular name', 'wp-travel-engine' ),
			'menu_name'          => _x( 'Coupons', 'admin menu', 'wp-travel-engine' ),
			'name_admin_bar'     => _x( 'Coupon', 'add new on admin bar', 'wp-travel-engine' ),
			'add_new'            => _x( 'Add New', 'wp-travel-engine-coupons', 'wp-travel-engine' ),
			'add_new_item'       => __( 'Add New Coupon', 'wp-travel-engine' ),
			'new_item'           => __( 'New Coupon', 'wp-travel-engine' ),
			'edit_item'          => __( 'View Coupon', 'wp-travel-engine' ),
			'view_item'          => __( 'View Coupon', 'wp-travel-engine' ),
			'all_items'          => __( 'Coupons', 'wp-travel-engine' ),
			'search_items'       => __( 'Search Coupons', 'wp-travel-engine' ),
			'parent_item_colon'  => __( 'Parent Coupons:', 'wp-travel-engine' ),
			'not_found'          => __( 'No Coupons found.', 'wp-travel-engine' ),
			'not_found_in_trash' => __( 'No Coupons found in Trash.', 'wp-travel-engine' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'wp-travel-engine' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=booking',
			'show_in_admin_bar'  => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'wp-travel-engine-coupon' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
			'menu_icon'          => 'dashicons-location',
			'with_front'         => false,
		);
		/**
		 * Register a itinerary-booking post type.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		\register_post_type( WP_TRAVEL_ENGINE_COUPONS_POST_TYPE, $args );

	}

	public static function coupon_id_by_code( $code ) {
		global $wpdb;

		$meta_key = 'wp_travel_engine_coupon_code';

		$sql = $wpdb->prepare(
			"
			SELECT post_id
			FROM $wpdb->postmeta
			WHERE meta_key = %s
			AND meta_value = %s
		",
			$meta_key,
			esc_sql( $code )
		);

		$results = $wpdb->get_results( $sql );

		if ( empty( $results ) ) {
			return false;
		}
		foreach ( $results as $result ) {
			$coupon_post = get_post( $result->post_id );
			if ( 'publish' === $coupon_post->post_status ) {
				return $coupon_post->ID;
			}
		}

		return $results['0']->post_id;
	}

	public static function add_coupon_metabox_field_callback() {
		include_once plugin_dir_path( __FILE__ ) . 'views/coupon-metabox-content.php';
	}

	public function init() {
		$this->register_post_type();
	}
}

new CouponCode();
