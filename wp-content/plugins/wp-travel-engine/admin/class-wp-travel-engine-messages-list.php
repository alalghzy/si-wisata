<?php
/**
 * Display messages list.
 *
 * @package wp-travel-engine
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Wp_Travel_Engine_Messages_List extends WP_List_Table {
	public function __construct() {
		parent::__construct(
			array(
				'singular' => esc_html__( 'Message', 'wp-travel-engine' ),
				'plural'   => esc_html__( 'Messages', 'wp-travel-engine' ),
				'ajax'     => false,
			)
		);
	}

	public static function get_messages( $per_page = 10, $page_number = 1 ) {
		$prefix        = 'wte_messages_';
		$url           = "https://wptravelengine.com/wp-json/wp/v2/wte_messages?per_page={$per_page}&page={$page_number}";
		$transient_key = $prefix . md5( sanitize_key( $url ) );
		$args          = array(
			'timeout'     => 30,
			'httpversion' => '1.1',
		);

		$data = get_transient( $transient_key );
		if ( false === $data ) {
			$response = wp_safe_remote_get( $url, $args );
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				echo '<p>Error retrieving messages</p>';
				return array();
			}
			$data = wp_remote_retrieve_body( $response );
			$data = json_decode( $data );

			set_transient( $transient_key, $data, 24 * HOUR_IN_SECONDS );

			// Set latest post date.
			if ( isset( $data[0] ) ) {
				$stored_latest_date = get_option( 'wte_messages_latest_post_date' );
				if ( false === $stored_latest_date ) {
					update_option( 'wte_messages_latest_post_date', $data[0]->date );
				} else {
					$parsed_latest_date  = strtotime( $stored_latest_date );
					$parsed_current_date = strtotime( $data[0]->date );
					if ( $parsed_current_date > $parsed_latest_date ) {
						update_option( 'wte_messages_latest_post_date', $data[0]->date );
					}
				}
			}
		}

		return $data;
	}

	public function record_count() {
		$url      = 'https://wptravelengine.com/wp-json/wp/v2/wte_messages';
		$args     = array(
			'timeout'     => 30,
			'httpversion' => '1.1',
		);
		$response = wp_safe_remote_head( $url, $args );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			echo '<p>Error retrieving messages</p>';
			return 0;
		}

		return wp_remote_retrieve_header( $response, 'x-wp-total' );
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array();
		return $sortable_columns;
	}

	public function no_items() {
		esc_html_e( 'No messages available', 'wp-travel-engine' );
	}

	public function column_name( $item ) {
		return esc_html__( 'Date', 'wp-travel-engine' );
	}

	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'date':
				$date_format = get_option( 'date_format' );
				$date        = date_i18n( $date_format, strtotime( $item->date_gmt ) );
				return $date;
			case 'subject':
				return $item->title->rendered;
			case 'message':
				return $item->content->rendered;
			default:
				return print_r( $item, true ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		}
	}

	public function get_columns() {
		$columns = array(
			'date'    => esc_html__( 'Date', 'wp-travel-engine' ),
			'subject' => esc_html__( 'Subject', 'wp-travel-engine' ),
			'message' => esc_html__( 'Message', 'wp-travel-engine' ),
		);

		return $columns;
	}

	public function prepare_items() {
		$this->_column_headers = array( $this->get_columns() );

		$per_page     = $this->get_items_per_page( 'messages_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args(
			array(
				'total_items' => $total_items, // WE have to calculate the total number of items
				'per_page'    => $per_page, // WE have to determine how many items to show on a page
			)
		);

		$this->items = self::get_messages( $per_page, $current_page );
	}
}
