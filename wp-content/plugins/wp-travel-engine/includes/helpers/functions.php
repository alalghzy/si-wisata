<?php
use \Firebase\JWT\JWT;
use WPTravelEngine\Core\Functions;
/**
 * WTE Helper functions.
 */

/**
 * Gets value of provided index.
 *
 * @param array  $array Array to pick value from.
 * @param string $index Index.
 * @param any    $default Default Values.
 * @return mixed
 */
function wte_array_get( $array, $index = null, $default = null ) {
	if ( ! is_array( $array ) ) {
		return $default;
	}
	if ( is_null( $index ) ) {
		return $array;
	}
	$multi_label_indices = explode( '.', $index );
	$value               = $array;
	foreach ( $multi_label_indices as $key ) {
		if ( ! isset( $value[ $key ] ) ) {
			$value = $default;
			break;
		}
		$value = $value[ $key ];
	}
	return $value;
}

/**
 * Generate Random Integer.
 */
function wte_get_random_integer( $min, $max ) {
		$range = ( $max - $min );

	if ( $range < 0 ) {
		// Not so random...
		return $min;
	}

	$log = log( $range, 2 );

	// Length in bytes.
	$bytes = (int) ( $log / 8 ) + 1;

	// Length in bits.
	$bits = (int) $log + 1;

	// Set all lower bits to 1.
	$filter = (int) ( 1 << $bits ) - 1;

	do {
		$rnd = hexdec( bin2hex( openssl_random_pseudo_bytes( $bytes ) ) );

		// Discard irrelevant bits.
		$rnd = $rnd & $filter;

	} while ( $rnd >= $range );

	return ( $min + $rnd );
}

/**
 * Generates uniq ID.
 *
 * @return void
 */
function wte_uniqid( $length = 5 ) {
	if ( ! isset( $length ) || intval( $length ) < 5 ) {
		$length = 5;
	}
	$token      = '';
	$characters = implode( range( 'a', 'z' ) ) . implode( range( 'A', 'Z' ) );
	for ( $i = 0; $i < $length; $i++ ) {
		$random_key = wte_get_random_integer( 0, strlen( $characters ) );
		$token     .= $characters[ $random_key ];
	}

	return $token;

	// if ( function_exists( 'random_bytes' ) ) {
	// return bin2hex( random_bytes( $length ) );
	// }
	// if ( function_exists( 'mcrypt_create_iv' ) ) {
	// return bin2hex( mcrypt_create_iv( $length, MCRYPT_DEV_URANDOM ) );
	// }
	// if ( function_exists( 'openssl_random_pseudo_bytes' ) ) {
	// return bin2hex( openssl_random_pseudo_bytes( $length ) );
	// }
	// return uniqid();
}

/**
 * Generate JWT.
 *
 * @return void
 */
function wte_jwt( array $payload, string $key ) {
	return JWT::encode( $payload, $key );
}

/**
 * Decode JWT.
 */
function wte_jwt_decode( string $jwt, string $key ) {
	return JWT::decode( $jwt, $key, array( 'HS256' ) );
}

/**
 * WTE Log data in json format.
 *
 * @param mixed $data
 * @return void
 */
function wte_log( $data, $name = 'data', $dump = false, $raw = true ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		if ( $raw ) {
			error_log( print_r( $data, true ), 3, WP_CONTENT_DIR .'/wte.log' ); // phpcs:ignore
			return;
		}
		$data = wp_json_encode( array( $name => $data ), JSON_PRETTY_PRINT );
		error_log( $data, 3, WP_CONTENT_DIR .'/wte.log' ); // phpcs:ignore
		if ( $dump ) {
			var_dump( $data );
		} else {
			return $data;
		}
	}
};

/**
 * Returns Booking Email instance.
 *
 * @return WTE_Booking_Emails
 */
function wte_booking_email() {
	// Mail class.
	require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-emails.php';
	return new \WTE_Booking_Emails();
}

/**
 * Undocumented function
 *
 * @since 4.3.8
 * @return void
 */
function wte_form_fields( array $fields, $echo = ! 0 ) {
	ob_start();
	( new WTE_Field_Builder_Admin( $fields ) )->render();
	$html = ob_get_clean();

	if ( $echo ) {
		echo $html;
	} else {
		return $html;
	}
}

/**
 *
 * @since 5.3.1
 */
function wte_admin_form_fields( $fields = array() ) {
	if ( ! class_exists( '\WP_Travel_Engine_Form_Field' ) || ! class_exists( '\WP_Travel_Engine_Form_Field_Admin' ) ) {
		include_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/lib/wte-form-framework/class-wte-form-field.php';
	}

	$wte_form_field_instance = new \WP_Travel_Engine_Form_Field_Admin();

	$wte_form_field_instance->init( $fields );

	return $wte_form_field_instance;
}

/**
 * Availability Options.
 */
function wte_get_availability_options( $key = ! 1 ) {
	$options = apply_filters(
		'wte_date_availability_options',
		array(
			'guaranteed' => __( 'Guaranteed', 'wp-travel-engine' ),
			'available'  => __( 'Available', 'wp-travel-engine' ),
			'limited'    => __( 'Limited', 'wp-travel-engine' ),
		)
	);
	if ( $key && isset( $options[ $key ] ) ) {
		return $options[ $key ];
	} else {
		return $options;
	}
}

/**
 * Get Requested Raw Data.
 *
 * @return void
 */
function wte_get_request_raw_data() {
	// phpcs:disable PHPCompatibility.Variables.RemovedPredefinedGlobalVariables.http_raw_post_dataDeprecatedRemoved
	global $HTTP_RAW_POST_DATA;

	// $HTTP_RAW_POST_DATA was deprecated in PHP 5.6 and removed in PHP 7.0.
	if ( ! isset( $HTTP_RAW_POST_DATA ) ) {
		$HTTP_RAW_POST_DATA = file_get_contents( 'php://input' );
	}

	return $HTTP_RAW_POST_DATA;
	// phpcs:enable
}

/**
 * Timezone info.
 *
 * @return void
 */
function wte_get_timezone_info() {
	$tz_string     = get_option( 'timezone_string' );
	$timezone_info = array();

	if ( $tz_string ) {
		try {
			$tz = new DateTimezone( $tz_string );
		} catch ( Exception $e ) {
			$tz = '';
		}

		if ( $tz ) {
			$now                  = new DateTime( 'now', $tz );
			$formatted_gmt_offset = wte_format_gmt_offset( $tz->getOffset( $now ) / 3600 );
			$tz_name              = str_replace( '_', ' ', $tz->getName() );
		}
	} else {
		$formatted_gmt_offset = wte_format_gmt_offset( (float) get_option( 'gmt_offset', 0 ) );

		$timezone_info['description'] = sprintf(
			/* translators: 1: UTC abbreviation and offset, 2: UTC offset. */
			__( 'Your timezone is set to %1$s (Coordinated Universal Time %2$s).', 'wp-travel-engine' ),
			'<abbr>UTC</abbr>' . $formatted_gmt_offset,
			$formatted_gmt_offset
		);
	}

	return $formatted_gmt_offset;
}

/**
 *
 */
function wte_format_gmt_offset( $offset ) {
	$offset = number_format( $offset, 2 );

	if ( 0 <= (float) $offset ) {
		$formatted_offset = '+' . (string) $offset;
	} else {
		$formatted_offset = (string) $offset;
	}

	preg_match( '/(\+|\-)?(\d+\.\d+)/', $formatted_offset, $matches );

	if ( isset( $matches[2] ) ) {
		$formatted_offset = substr( '0000' . $matches[2], -5 );
	}

	$formatted_offset = $matches[1] . $formatted_offset;

	$formatted_offset = str_replace(
		array( '.25', '.50', '.75', '.00' ),
		array( ':15', ':30', ':45', ':00' ),
		$formatted_offset
	);
	return $formatted_offset;
}

function wte_get_trip( $trip = null ) {
	if ( empty( $trip ) && isset( $GLOBALS['wtetrip'] ) ) {
		$trip = $GLOBALS['wtetrip'];
	}

	if ( $trip instanceof Posttype\Trip ) {
		$_trip = $trip;
	} else {
		$_trip = WPTravelEngine\Posttype\Trip::instance( $trip );
	}

	if ( ! $_trip ) {
		return null;
	}

	return $_trip;
}

function wte_get_engine_extensions() {
	$plugins = get_plugins();

	$matches = array();
	foreach ( $plugins as $file => $plugin ) {
		if ( 'WordPress Travel Booking Plugin - WP Travel Engine' !== $plugin['Name'] && ( stristr( $plugin['Name'], 'wp travel engine' ) || stristr( $plugin['Description'], 'wp travel engine' ) ) ) {
			$matches[ $file ] = $plugin;
		}
	}

	return $matches;
}

function wte_get_extensions_ids( $key = null ) {
	$ids = apply_filters(
		'wp_travel_engine_addons_id',
		array(
			'wte_group_discount'           => 146,
			'wte_currency_converter'       => 30074,
			'wte_fixed_starting_dates'     => 79,
			'wte_midtrans'                 => 31412,
			'wte_hbl_payment'              => 20311,
			'wte_partial_payment'          => 1750,
			'wte_payfast'                  => 1744,
			'wte_paypal_express'           => 7093,
			'wte_payu'                     => 1055,
			'wte_advanced_itinerary'       => 31567,
			'wte_advance_search'           => 1757,
			'wte_authorize_net'            => 577,
			'wte_extra_services'           => 20573,
			'wte_form_editor'              => 33247,
			'wte_payhere_payment'          => 30754,
			'wte_payu_money_bolt_checkout' => 30752,
			'wte_stripe_gateway'           => 557,
			'wte_trip_code'                => 40085,
			'wte_coupons'                  => 42678,
		)
	);
	if ( $key && ! isset( $ids[ $key ] ) ) {
		return false;
	}

	return $key ? $ids[ $key ] : $ids;
}

function wte_functions() {
	return new Functions();
}

function wte_readonly( $value, $check_against, $echo = true ) {
	if ( ( is_array( $check_against ) && in_array( $value, $check_against ) )
		|| ( ! is_array( $check_against ) && $value === $check_against )
		) {
		if ( $echo ) {
			echo 'readonly=\"readonly\"';
		}
		return true;
	}
}

/**
 * Gets Trip Reviews.
 */
function wte_get_trip_reviews( $trip_id ) {
	global $wpdb;

	// SELECT c.comment_content, JSON_OBJECTAGG(wp_commentmeta.`meta_key`, wp_commentmeta.meta_value)  FROM wp_comments as c INNER JOIN wp_commentmeta WHERE c.comment_post_ID = 22 AND c.comment_ID = wp_commentmeta.comment_id GROUP BY wp_commentmeta.comment_id
	$where = "c.comment_ID = cm.comment_id AND c.comment_post_ID = {$trip_id}";
	$query = "SELECT c.comment_ID, c.comment_content, JSON_OBJECTAGG(cm.meta_key, cm.meta_value) as reviews_meta FROM {$wpdb->comments} as c INNER JOIN {$wpdb->commentmeta} as cm WHERE {$where} GROUP BY cm.comment_id";

	$results = $wpdb->get_results( $query );

	$_result = array();
	if ( $results && is_array( $results ) ) {
		$reviews_meta = array(
			'phone'           => '',
			'title'           => '',
			'stars'           => 0,
			'experience_date' => '',
		);
		$i            = 0;
		foreach ( $results as $result ) {
			$_result[ $i ]['ID']      = (int) $result->comment_ID;
			$_result[ $i ]['content'] = $result->comment_content;

			if ( isset( $result->reviews_meta ) && json_decode( $result->reviews_meta ) ) {
				$_metas = json_decode( $result->reviews_meta );
				foreach ( $reviews_meta as $key => $value ) {
					if ( isset( $_metas->$key ) ) {
						$_result[ $i ][ $key ] = 'stars' === $key ? (int) $_metas->{$key} : $_metas->{$key};
					} else {
						$_result[ $i ][ $key ] = $value;
					}
				}
			}
			$i++;
		}
	}

	$stars = array_column( $_result, 'stars' );

	return array(
		'reviews' => $_result,
		'average' => count( $stars ) > 0 ? array_sum( $stars ) / count( $stars ) : 0,
		'count'   => count( $stars ),
	);
}

/**
 * Use it as a templating function inside loop.
 */
function wte_get_the_trip_reviews( $trip_id = null ) {
	if ( ! defined( 'WTE_TRIP_REVIEW_VERSION' ) ) {
		return '';
	}
	if ( is_null( $trip_id ) ) {
		$trip_id = get_the_ID();
	}

	$trip_reviews = (object) wte_get_trip_reviews( $trip_id );

	if ( ! isset( $trip_reviews->average ) || $trip_reviews->average <= 0 )  return;

	// phpcs:disable
	ob_start();
	?>
	<div class="wpte-trip-review-stars">
		<div class="stars-group-wrapper">
			<div class="stars-placeholder-group">
				<?php
				echo implode(
					'',
					array_map(
						function() {
							return '<svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M6.41362 0.718948C6.77878 -0.0301371 7.84622 -0.0301371 8.21138 0.718948L9.68869 3.74946C9.83326 4.04602 10.1148 4.25219 10.4412 4.3005L13.7669 4.79272C14.5829 4.91349 14.91 5.91468 14.3227 6.49393L11.902 8.88136C11.6696 9.1105 11.5637 9.4386 11.6182 9.76034L12.1871 13.1191C12.3258 13.9378 11.464 14.559 10.7311 14.1688L7.78252 12.5986C7.4887 12.4421 7.1363 12.4421 6.84248 12.5986L3.89386 14.1688C3.16097 14.559 2.29922 13.9378 2.43789 13.1191L3.0068 9.76034C3.06129 9.4386 2.95537 9.1105 2.72303 8.88136L0.302324 6.49393C-0.285 5.91468 0.0420871 4.91349 0.85811 4.79272L4.18383 4.3005C4.5102 4.25219 4.79174 4.04602 4.93631 3.74946L6.41362 0.718948Z" fill="#EBAD34"></path></svg>';
						},
						range( 0, 4 )
					)
				);
				?>
			</div>
			<div
				class="stars-rated-group"
				style="width: <?php echo esc_attr( $trip_reviews->average * 20 ); ?>%"
			>
			<?php
				echo implode(
					'',
					array_map(
						function() {
							return '<svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M6.41362 0.718948C6.77878 -0.0301371 7.84622 -0.0301371 8.21138 0.718948L9.68869 3.74946C9.83326 4.04602 10.1148 4.25219 10.4412 4.3005L13.7669 4.79272C14.5829 4.91349 14.91 5.91468 14.3227 6.49393L11.902 8.88136C11.6696 9.1105 11.5637 9.4386 11.6182 9.76034L12.1871 13.1191C12.3258 13.9378 11.464 14.559 10.7311 14.1688L7.78252 12.5986C7.4887 12.4421 7.1363 12.4421 6.84248 12.5986L3.89386 14.1688C3.16097 14.559 2.29922 13.9378 2.43789 13.1191L3.0068 9.76034C3.06129 9.4386 2.95537 9.1105 2.72303 8.88136L0.302324 6.49393C-0.285 5.91468 0.0420871 4.91349 0.85811 4.79272L4.18383 4.3005C4.5102 4.25219 4.79174 4.04602 4.93631 3.74946L6.41362 0.718948Z" fill="#EBAD34"></path></svg>';
						},
						range( 0, 4 )
					)
				);
			?>
			</div>
		</div>
		<?php if ( (float) $trip_reviews->count > 0 ) : ?>
			<a class="wpte-trip-review-count" href="<?php echo esc_url( get_the_permalink() . "#nb-7-configurations" ); ?>"><?php printf( esc_html( _n( '%d Review', '%d Reviews', $trip_reviews->count, 'wp-travel-engine' ) ), (float) $trip_reviews->count ); ?></a>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
	// phpcs:enable
}

function wte_the_trip_reviews() {
	echo wte_get_the_trip_reviews( get_the_ID() ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function wte_get_the_excerpt( $trip_id = null, $words = 25 ) {
	if ( is_null( $trip_id ) ) {
		$trip_id = get_the_ID();
	}

	return wp_trim_words( get_the_excerpt( $trip_id ), $words, '...' );
}

function wte_list( $array, $vars ) {
	$_array = array();
	if ( is_array( $array ) && is_array( $vars ) ) {
		foreach ( $vars as $index => $key ) {
			$_array[ $index ] = isset( $array[ $key ] ) ? $array[ $key ] : null;
		}
	}

	return $_array;
}

function wte_get_media_details( $media_id ) {
	$media_details = \wp_get_attachment_metadata( $media_id );

	// Ensure empty details is an empty object.
	if ( empty( $media_details ) ) {
		$media_details = new \stdClass();
	} elseif ( ! empty( $media_details['sizes'] ) ) {

		foreach ( $media_details['sizes'] as $size => &$size_data ) {

			if ( isset( $size_data['mime-type'] ) ) {
				$size_data['mime_type'] = $size_data['mime-type'];
				unset( $size_data['mime-type'] );
			}

			// Use the same method image_downsize() does.
			$image_src = wp_get_attachment_image_src( $media_id, $size );
			if ( ! $image_src ) {
				continue;
			}

			$size_data['source_url'] = $image_src[0];
		}

		$full_src = wp_get_attachment_image_src( $media_id, 'full' );

		if ( ! empty( $full_src ) ) {
			$media_details['sizes']['full'] = array(
				'file'       => wp_basename( $full_src[0] ),
				'width'      => $full_src[1],
				'height'     => $full_src[2],
				// 'mime_type'  => $post->post_mime_type,
				'source_url' => $full_src[0],
			);
		}
	} else {
		$media_details['sizes'] = new \stdClass();
	}

	unset( $media_details->{'image_meta'} );

	return $media_details;
}

/**
 * Checks if trip has group discount.
 */
function wte_has_trip_group_discount( $trip_id ) {
	return \apply_filters( 'has_packages_group_discounts', false, $trip_id );
}

function wte_get_terms_by_id( $taxonomy, $args = array() ) {
	$terms        = get_terms( $taxonomy, $args );
	$terms_by_ids = array();

	if ( is_array( $terms ) ) {
		foreach ( $terms as $term_object ) {
			$term_object->children  = array();
			$term_object->link      = get_term_link( $term_object->term_id );
			$term_object->thumbnail = (int) get_term_meta( $term_object->term_id, 'category-image-id', true );
			if ( isset( $terms_by_ids[ $term_object->term_id ] ) ) {
				foreach ( (array) $terms_by_ids[ $term_object->term_id ] as $prop_name => $prop_value ) {
					$term_object->{$prop_name} = $prop_value;
				}
			}
			if ( $term_object->parent ) {
				if ( ! isset( $terms_by_ids[ $term_object->parent ] ) ) {
					$terms_by_ids[ $term_object->parent ] = new \stdClass();
				}
				$terms_by_ids[ $term_object->parent ]->children[] = $term_object->term_id;
			}

			$terms_by_ids[ $term_object->term_id ] = $term_object;
		}
	}

	return $terms_by_ids;
}

// wte_trip_get_trip_rest_metadata
function wte_trip_get_trip_rest_metadata( $trip_id ) {

	$post = get_post( $trip_id );

	$trip_details = \wte_get_trip_details( $trip_id );

	$data = new \stdClass();

	$featured_media = get_post_thumbnail_id( $trip_id );

	foreach ( array(
		'code'             => array(
			'key'  => 'trip_settings.trip_code',
			'type' => 'string',
		),
		'price'            => array(
			'key'  => 'display_price',
			'type' => 'number',
		),
		'has_sale'         => array(
			'key'  => 'on_sale',
			'type' => 'boolean',
		),
		'sale_price'       => array(
			'key'  => 'sale_price',
			'type' => 'number',
		),
		'discount_percent' => array(
			'key'     => 'discount_percent',
			'type'    => 'number',
			'decimal' => 0,
		),
		'currency'         => array(
			'type'  => 'array',
			'items' => array(
				'code'   => array(
					'key'  => 'code',
					'type' => 'string',
				),
				'symbol' => array(
					'key'  => 'currency',
					'type' => 'string',
				),
			),
		),
		'duration'         => array(
			'type'  => 'array',
			'items' => array(
				'days'   => array(
					'key'  => 'trip_duration',
					'type' => 'number',
				),
				'nights' => array(
					'key'  => 'trip_duration_nights',
					'type' => 'number',
				),
				'duration_unit' => array(
					'key'  => 'trip_duration_unit',
					'type' => 'string',
				),
				'duration_type' => array(
					'key'  => 'set_duration_type',
					'type' => 'string',
				),
			),
		),
	) as $property_name => $args ) {
		$value = isset( $args['key'] ) ? wte_array_get( $trip_details, $args['key'], '' ) : '';

		if ( 'array' === $args['type'] && isset( $args['items'] ) ) {
			$value = array();
			$items = $args['items'];
			foreach ( $items as $sub_property_name => $item ) {
				if ( isset( $trip_details[ $item['key'] ] ) ) {
					if ( 'number' === $item['type'] ) {
						$decimal                     = isset( $item['decimal'] ) ? (int) $item['decimal'] : 0;
						$value[ $sub_property_name ] = round( (float) $trip_details[ $item['key'] ], $decimal );
					} else {
						$value[ $sub_property_name ] = $trip_details[ $item['key'] ];
					}
				}
			}
			$data->{$property_name} = $value;
			continue;
		}
		$data->{$property_name} = 'number' === $args['type'] ? round( (float) $value, 2 ) : $value;
	}

	// $wte_trip = \wte_get_trip( $trip_id );

	$lowest_package = WPTravelEngine\Packages\get_trip_lowest_price_package( $trip_id );

	$primary_category_id = get_option( 'primary_pricing_category', 0 );
	$primary_category    = new \stdClass();
	if ( isset( $lowest_package->{'package-categories'} ) && $primary_category_id ) {
		$package_categories = $lowest_package->{'package-categories'};

		foreach ( array(
			'prices'        => 'price',
			'labels'        => 'label',
			'pricing_types' => 'pricing_type',
			'enabled_sale'  => 'has_sale',
			'sale_prices'   => 'sale_price',
			'min_paxes'     => 'min_pax',
			'max_paxes'     => 'max_pax',
		) as $source => $key ) {
			if ( isset( $package_categories[ $source ][ $primary_category_id ] ) ) {
				$value = in_array( $key, array( 'price', 'sale_price', 'has_sale' ) ) ? (float) $package_categories[ $source ][ $primary_category_id ] : $package_categories[ $source ][ $primary_category_id ];
			} else {
				$value = in_array( $key, array( 'price', 'sale_price', 'has_sale' ) ) ? 0 : '';
			}
			$primary_category->{$key} = $value;
		}
	}

	$data->price            = isset( $primary_category->price ) && $primary_category->price != '' ? (int) $primary_category->price : '';
	$data->has_sale         = isset( $primary_category->has_sale ) ? $primary_category->has_sale : false;
	$data->sale_price       = isset( $primary_category->sale_price ) && $primary_category->sale_price != '' ? (int) $primary_category->sale_price : '';
	$data->primary_category = $primary_category_id;
	$data->available_times  = array(
		'type'  => 'default',
		'items' => array_map(
			function( $month ) {
				return "2021-{$month}-01";
			},
			range( 1, 12 )
		),
	);

	$trip_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
	// Group Size.
	$data->min_pax = '';
	if ( ! empty( $trip_settings['trip_minimum_pax'] ) ) {
		$data->min_pax = (int) $trip_settings['trip_minimum_pax'];
	}
	$data->max_pax = '';
	if ( ! empty( $trip_settings['trip_maximum_pax'] ) ) {
		$data->max_pax = (int) $trip_settings['trip_maximum_pax'];
	}

	if ( isset( $trip_settings['trip_facts'][2][2] ) ) {
		$data->group_size = $trip_settings['trip_facts'][2][2];
	}

	$data->is_featured = \wte_is_trip_featured( $trip_id );

	if ( defined( 'WTE_TRIP_REVIEW_VERSION' ) ) {
		$data->{'trip_reviews'} = \wte_get_trip_reviews( $trip_id );
	}

	$media_details = \wte_get_media_details( $featured_media );

	$data->featured_image = $media_details;

	return $data;
}

/**
 * Retrive currency code.
 *
 * @since 5.2.0
 */
function wte_currency_code() {
	$code = wte_array_get( get_option( 'wp_travel_engine_settings', array() ), 'currency_code', 'USD' );

	return wte_functions()->wp_travel_engine_currencies_symbol( $code );
}

/**
 * Default Sanitize Callback
 *
 * @since 5.0.0
 */
function wte_default_sanitize_callback( $value ) {
	return $value;
}

/**
 * Update trip packages with posted data.
 *
 * @deprecated deprecated since 5.3.0
 * @since 5.0.0
 */
function wte_update_trip_packages( $post_ID, $posted_data ) {
	_deprecated_function( __FUNCTION__, '5.3.0', '\WPTravelEngine\Packages\update_trip_packages' );
	\WPTravelEngine\Packages\update_trip_packages( $post_ID, $posted_data );
}

/**
 * Default text and labels.
 *
 * @since 5.3.0
 */
function wte_default_labels( $labelof = null ) {
	$defaults = array(
		'checkout.submitButtonText' => __( 'Book Now', 'wp-travel-engine' ),
		'checkout.bookingSummary'   => __( 'Booking Summary', 'wp-travel-engine' ),
		'checkout.totalPayable'     => __( 'Total Payable Now', 'wp-travel-engine' ),
	);

	$labels = apply_filters( 'wte_default_labels', $defaults );

	if ( ! $labelof ) {
		return $labels;
	} else {
		return isset( $labels[ $labelof ] ) ? $labels[ $labelof ] : '';
	}

}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @since 5.3.1
 * @param string|array $var Data to sanitize.
 * @param string       $sanitize_callback Sanitize callback.
 * @return string|array
 */
function wte_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'wte_clean', $var );
	} else {
		if ( is_scalar( $var ) ) {
			return sanitize_text_field( $var );
		} else {
			return $var;
		}
	}
}

/**
 *
 * @since 5.3.1
 */
function wte_nonce_verify( $key, $action ) {
	if ( isset( $_REQUEST[ $key ] ) ) {
		$nonce = wte_clean( wp_unslash( $_REQUEST[ $key ] ) );

		if ( ! is_string( $nonce ) ) {
			return false;
		}
		return wp_verify_nonce( $nonce, $action );
	}
	return false;
}

/**
 *
 * @since 5.3.2
 */
function wte_input_clean( $data, $schema = array() ) {

	if ( is_array( $data ) ) {
		$_data = array();
		foreach ( $data as $index => $value ) {
			if ( isset( $schema['properties'][ $index ] ) ) {
				$_data[ $index ] = call_user_func( 'wte_input_clean', $value, $schema['properties'][ $index ] );
			} else {
				if ( is_array( $value ) ) {
					$_data[ $index ] = call_user_func(
						'wte_input_clean',
						$value,
						array(
							'type'       => 'array',
							'properties' => array(),
						)
					);
				} else {
					if ( is_scalar( $value ) ) {
						if ( isset( $schema['items'] ) ) {
							$_data[ $index ] = call_user_func(
								'wte_input_clean',
								$value,
								array(
									'type'              => $schema['items']['type'],
									'sanitize_callback' => $schema['items']['sanitize_callback'],
								)
							);
						} else {
							$_data[ $index ] = call_user_func( 'wte_input_clean', $value, array( 'type' => 'string' ) );
						}
					}
				}
				continue;
			}
		}
		return $_data;
	} else {
		if ( ! is_scalar( $data ) ) {
			return $data;
		}
		if ( isset( $schema['sanitize_callback'] ) ) {
			return call_user_func( $schema['sanitize_callback'], $data );
		} else {
			return sanitize_text_field( $data );
		}
	}
}

/**
 *
 * Escapes for SVGs.
 *
 * @param strign $value SVG String.
 *
 * 5.3.2
 */
function wte_esc_svg( $value ) {
	return wp_kses(
		$value,
		array(
			'svg'   => array(
				'id'              => array(),
				'class'           => array(),
				'aria-hidden'     => array(),
				'aria-labelledby' => array(),
				'role'            => array(),
				'xmlns'           => array(),
				'width'           => array(),
				'height'          => array(),
				'viewbox'         => array(), // <= Must be lower case!
			),
			'g'     => array(
				'fill' => array(),
				'id'   => array(),
			),
			'title' => array( 'title' => array() ),
			'path'  => array(
				'id'   => array(),
				'd'    => array(),
				'fill' => array(),
			),
			'i'     => array(),
		)
	);
}

/**
 * Booking Meta related to Payment value passed as array for multi use
 *
 * @param [type] $booking_id
 * @return void
 */
function booking_meta_details( $booking_id ) {
	$booking_meta                      = array();
	$booking_metas                     = get_post_meta( $booking_id, 'wp_travel_engine_booking_setting', true );
	$booking_meta['booked_travellers'] = isset( $booking_metas['place_order']['traveler'] ) ? $booking_metas['place_order']['traveler'] : 0;

	$booking_meta['total_paid']                = isset( $booking_metas['place_order']['cost'] ) ? $booking_metas['place_order']['cost'] : 0;
	$booking_meta['remaining_payment']         = isset( $booking_metas['place_order']['due'] ) ? $booking_metas['place_order']['due'] : 0;
	$booking_meta['total_cost']                = isset( $booking_metas['place_order']['due'] ) && $booking_metas['place_order']['due'] != '' ? floatval( $booking_metas['place_order']['cost'] ) + floatval( $booking_metas['place_order']['due'] ) : $booking_meta['total_paid'];
	$booking_meta['partial_due']               = isset( $booking_metas['place_order']['partial_due'] ) ? $booking_metas['place_order']['partial_due'] : 0;
	$booking_meta['partial_cost']              = isset( $booking_metas['place_order']['partial_cost'] ) ? $booking_metas['place_order']['partial_cost'] : 0;
	$booking_meta['trip_id']                   = isset( $booking_metas['place_order']['tid'] ) ? $booking_metas['place_order']['tid'] : 0;
	$booking_meta['trip_name']                 = isset( $booking_metas['place_order']['tid'] ) ? esc_html( get_the_title( $booking_metas['place_order']['tid'] ) ) : '';
	$booking_meta['trip_start_date']           = isset( $booking_metas['place_order']['datetime'] ) ? $booking_metas['place_order']['datetime'] : '';
	$booking_meta['trip_start_date_with_time'] = isset( $booking_metas['place_order']['datewithtime'] ) ? $booking_metas['place_order']['datewithtime'] : '';
	$booking_meta['date_format']               = get_option( 'date_format' );

	return $booking_meta;
}

/**
 * Escape html attributes key-value pair
 *
 * @since 5.3.1
 */
function wte_esc_attr( $value ) {
	if ( is_string( $value ) ) {
		preg_match( "/([\w\-]+)=(['\"](.*)['\"])/", $value, $matches );
		if ( $matches && isset( $matches[1] ) && $matches[3] ) {
			return esc_attr( $matches[1] ) . '="' . $matches[3] . '"';
		}
		return esc_attr( $value );
	}
	return $value;
}

/**
 * Duplicate any post by provided Post ID or Post Object.
 */
function wptravelengine_duplicate_post( $post_id ) {

	global $wpdb;

	$post = get_post( $post_id );

	/*
	* if you don't want current user to be the new post author,
	* then change next couple of lines to this: $new_post_author = $post->post_author;
	*/
	$current_user    = wp_get_current_user();
	$new_post_author = $current_user->ID;

	/*
	* if post data exists, create the post duplicate
	*/
	if ( isset( $post ) && ! is_null( $post ) ) {
		/*
		* new post data array
		*/
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order,
		);

		/*
		* insert the post by wp_insert_post() function
		*/
		$new_post_id = wp_insert_post( $args );

		/*
		* get all current post terms ad set them to the new post draft
		*/
		$taxonomies = get_object_taxonomies( $post->post_type );
		foreach ( $taxonomies as $taxonomy ) {
			$post_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'slugs' ) );
			wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
		}

		/*
		* duplicate all post meta just in two SQL queries
		*/
		$post_meta_infos = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post->ID" ); // phpcs:ignore
		if ( isset( $post_meta_infos[0] ) ) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ( $post_meta_infos as $meta_info ) {
				$meta_key = $meta_info->meta_key;
				if ( '_wp_old_slug' === $meta_key ) {
					continue;
				}
				$meta_value      = addslashes( $meta_info->meta_value );
				$sql_query_sel[] = "SELECT $new_post_id, '{$meta_key}', '{$meta_value}'";
			}
			$sql_query .= implode( ' UNION ALL ', $sql_query_sel );
			$wpdb->query( $sql_query ); // phpcs:ignore
		}

		return $new_post_id;
	} else {
		return null;
	}
}


/**
 * Get Products from store.
 *
 * @since 5.4.3
 */
function wptravelengine_get_products_from_store( $type = 'addons' ) {
	$addons_data = get_transient( "wp_travel_engine_store_{$type}_list" );

	$links_by_type = (object) array(
		'addons'   => 'add-ons',
		'themes'   => 'travel-wordpress-themes',
		'services' => 'services',
	);

	if ( ! $addons_data ) {
		$addons_data = wp_safe_remote_get( WP_TRAVEL_ENGINE_STORE_URL . "/edd-api/v2/products/?category={$links_by_type->{$type}}&number=-1" );

		if ( is_wp_error( $addons_data ) ) {
			return;
		}

		$addons_data = wp_remote_retrieve_body( $addons_data );
		set_transient( "wp_travel_engine_store_{$type}_list", $addons_data, 48 * HOUR_IN_SECONDS );
	}

	if ( ! empty( $addons_data ) ) :

		$addons_data = json_decode( $addons_data );
		$addons_data = $addons_data->products;

	endif;

	return $addons_data;
}

/**
 * Get Community Thems.
 *
 * @since 5.4.3
 */
function wptravelengine_get_community_themes() {
	return array(
		array(
			'title'     => 'Travel Agency',
			'url'       => 'https://rarathemes.com/wordpress-themes/travel-agency/',
			'thumbnail' => WP_TRAVEL_ENGINE_FILE_URL . '/admin/css/images/travel-agency-pro-screenshot.jpg',
		),
		array(
			'title'     => 'Travel Agency Pro',
			'url'       => 'https://rarathemes.com/wordpress-themes/travel-agency-pro/',
			'thumbnail' => WP_TRAVEL_ENGINE_FILE_URL . '/admin/css/images/travel-agency-pro-screenshot.jpg',
		),
		array(
			'title'     => 'WP Tour Package',
			'url'       => 'https://rarathemes.com/wordpress-themes/tour-package/',
			'thumbnail' => WP_TRAVEL_ENGINE_FILE_URL . '/admin/css/images/tour package.png',
		),
		array(
			'title'     => 'Tour Operator',
			'url'       => 'https://rarathemes.com/wordpress-themes/tour-operator/',
			'thumbnail' => WP_TRAVEL_ENGINE_FILE_URL . '/admin/css/images/tour-operator.png',
		),
		array(
			'title'     => 'Travel Tour',
			'url'       => 'https://thebootstrapthemes.com/downloads/free-travel-tour-wordpress-theme/',
			'thumbnail' => WP_TRAVEL_ENGINE_FILE_URL . '/admin/css/images/travel-tour-pro.jpg',
		),
		array(
			'title'     => 'Travel Tour Pro',
			'url'       => 'https://thebootstrapthemes.com/downloads/travel-tour-pro/',
			'thumbnail' => WP_TRAVEL_ENGINE_FILE_URL . '/admin/css/images/travel-tour-pro.jpg',
		),
		// [
		// "title" => "Travel Company",
		// "url" => "https://scorpionthemes.com/downloads/travel-company-wordpress-theme/",
		// "thumbnail" => WP_TRAVEL_ENGINE_FILE_URL . '/admin/css/images/travel-pro.png'
		// ],
		// [
		// "title" => "Travel Company Pro",
		// "url" => "https://justwpthemes.com/downloads/travel-company-pro-wordpress-theme/",
		// "thumbnail" => WP_TRAVEL_ENGINE_FILE_URL . '/admin/css/images/travel-pro.png'
		// ],
	);
}

/**
 * Get the excerpt content.
 *
 * @since 5.4.3
 */
function wptravelengine_get_the_trip_excerpt( $post_id = null, $length = 30 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	$content = '';
	if ( has_excerpt( $post_id ) ) {
		return get_the_excerpt( $post_id );
	} else {
		$content = get_the_content( null, false, $post_id );
		if ( empty( $content ) ) {
			$trip_settings = get_post_meta( $post_id, 'wp_travel_engine_setting', true );
			$content       = isset( $trip_settings['tab_content']['1_wpeditor'] ) ? $trip_settings['tab_content']['1_wpeditor'] : '';
		}
	}
	return wp_trim_words( strip_shortcodes( $content ), $length, '...' );
}

/**
 * Print the excerpt content.
 *
 * @since 5.4.3
 */
function wptravelengine_the_trip_excerpt( $post_id = null, $length = 25 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	echo wp_kses_post( wptravelengine_get_the_trip_excerpt( $post_id, $length ) );
}

/**
 *
 * @since 5.5.0
 */
function wptravelengine_get_fa_icons() {
	$data = wp_cache_get( 'fa_icons', 'wptravelengine' );
	if ( ! $data ) {
		ob_start();
		include_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'assets/lib/fontawesome/icons.json';
		$data = json_decode( ob_get_clean(), true );
		wp_cache_set( 'fa_icons', $data, 'wptravelengine' );
	}
	return $data;
}

/**
 *
 * @since 5.5.0
 */
function wptravelengine_svg_by_fa_icon( $icon_name, $echo = true, $class_names = [] ) {
	$data = wptravelengine_get_fa_icons();

	$svg = isset( $data[ $icon_name ] ) ? $data[ $icon_name ] : '';

	$svg = preg_replace( '/^<svg/', '<svg class="' . implode( ' ', array_merge( ['svg-inline--fa'], $class_names ) ) . '"', $svg );

	if ( ! $echo ) {
		return $svg;
	}

	echo $svg;
}

function wptravelengine_hidden_class( $hidden, $current = true, $echo = true ) {
	if ( $hidden === $current ) {
		if ( $echo ) {
			echo esc_attr( 'hidden' );
		}
		return 'hidden';
	}
}

function wptravelengine_settings() {
	return \WPTravelEngine\Core\Settings::instance();
}

/**
 *
 * Returns Sorting options.
 *
 * @since 5.5.7
 */
function wptravelengine_get_sorting_options() {
	return apply_filters(
		'wp_travel_engine_archive_header_sorting_options',
		array(
			// ''       => esc_html__( 'Default Sorting', 'wp-travel-engine' ),
			'latest' => esc_html__( 'Latest', 'wp-travel-engine' ),
			'rating' => esc_html__( 'Most Reviewed', 'wp-travel-engine' ),
			'price'  => array(
				'label'   => esc_html__( 'Price', 'wp-travel-engine' ),
				'options' => array(
					'price'      => esc_html__( 'Low to High', 'wp-travel-engine' ),
					'price-desc' => esc_html__( 'High to Low', 'wp-travel-engine' ),
				),
			),
			'days'   => array(
				'label'   => esc_html__( 'Days', 'wp-travel-engine' ),
				'options' => array(
					'days'      => esc_html__( 'Low to High', 'wp-travel-engine' ),
					'days-desc' => esc_html__( 'High to Low', 'wp-travel-engine' ),
				),
			),
			'name'   => array(
				'label'   => esc_html__( 'Name', 'wp-travel-engine' ),
				'options' => array(
					'name'      => __( 'a - z', 'wp-travel-engine' ),
					'name-desc' => __( 'z - a', 'wp-travel-engine' ),
				),
			),
		)
	);
}

/**
 *
 * @since 5.5.7
 */
function wptravelengine_get_trip_taxonomies( $output = 'names' ) {
	$taxonomies = get_taxonomies( array( 'object_type' => array( WP_TRAVEL_ENGINE_POST_TYPE ) ), $output );

	return $taxonomies;
}

/**
 * Get User wishlists
 *
 * @since 5.5.7
 */
function wptravelengine_user_wishlists() {
	if ( is_user_logged_in() ) {
		$user_id = get_current_user_id();
		$user_wishlists = get_user_meta( $user_id, 'wptravelengine_wishlists', true );
	} else {
		$user_wishlists = WTE()->session->get( 'user_wishlists' );
	}

	return is_array( $user_wishlists ) ? $user_wishlists : array();
}
