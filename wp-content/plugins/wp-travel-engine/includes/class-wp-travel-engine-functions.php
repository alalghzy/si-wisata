<?php
namespace WPTravelEngine\Core;

/**
 * Basic functions for the plugin.
 *
 * Maintain a list of functions that are used in the plugin for basic purposes
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 * @author
 */

/**
 * class \WPTravelEngine\Core\Functions
 * Utility functions.
 */
class Functions {

	function trip_price( $postid ) {
		$wp_travel_engine_setting = \get_post_meta( $postid, 'wp_travel_engine_setting', true );

		$cost = isset( $wp_travel_engine_setting['trip_price'] ) ? $wp_travel_engine_setting['trip_price'] : '';

		$prev_cost = isset( $wp_travel_engine_setting['trip_prev_price'] ) ? $wp_travel_engine_setting['trip_prev_price'] : '';

		if ( $cost != '' && isset( $wp_travel_engine_setting['sale'] ) ) {
			return $cost;
		} else {
			if ( $prev_cost != '' ) {
				$cost = $prev_cost;
			}
		}
		return $cost;
	}

	function init() {
		add_filter(
			'pre_term_description',
			function( $value, $taxonomy ) {
				$taxonomies = apply_filters( 'wp_travel_engine_taxonomies', array( 'destination', 'activities', 'trip_types' ) );
				if ( in_array( $taxonomy, $taxonomies ) ) {
					/**
					 * The taxonomies uses wp editor for as term description
					 * using wp_filter_kses strip all the tags so it
					 * is removed from pre term description.
					 * However we filters input using wp_kses_post() later.
					 */
					remove_filter( 'pre_term_description', 'wp_filter_kses' );
					return wp_kses_post( $value );
				}
				return $value;
			},
			9,
			2
		);

		foreach ( array( 'destination', 'activities', 'trip_types' ) as $taxonomy ) {
			$field = 'description';
			add_filter(
				"{$taxonomy}_{$field}",
				function( $value ) {
					if ( is_admin() ) {
						return substr( $value, 0, 50 ) . '...';
					}
					return $value;
				}
			);
		}

		add_filter( 'term_description', 'shortcode_unautop' );
		add_filter( 'term_description', 'do_shortcode' );
		add_filter( 'the_content', array( __CLASS__, 'wte_remove_empty_p' ), 20, 1 );
		add_filter( 'term_description', array( __CLASS__, 'wte_remove_empty_p' ), 20, 1 );
		add_filter( 'pll_get_post_types', array( __CLASS__, 'wte_add_cpt_to_pll' ), 10, 2 );
		add_filter( 'pll_get_taxonomies', array( __CLASS__, 'wte_add_tax_to_pll' ), 10, 2 );
		add_filter( 'wp_travel_engine_setting', 'do_shortcode', 10 );
		add_action( 'wp_ajax_wte_user_profile_image_upload', array( __CLASS__, 'upload_user_profile_image' ) );

	}
	/**
	 * Upload profile image from form.
	 *
	 * @return void
	 */
	public static function upload_user_profile_image() {

		if ( ! empty( $_FILES ) && wp_verify_nonce( $_REQUEST['nonce'], 'wte-user-profile-image-nonce' ) ) :

			$allowed_filetypes = array( 'image/jpeg', 'image/png', 'image/gif' );

			$uploaddir   = wp_upload_dir();
			$wte_temp_dir = trailingslashit( $uploaddir['basedir'] ) . 'wp-travel-engine/tmp';
			$wte_temp_url = str_replace( array( 'http://', 'https://' ), '//', trailingslashit( $uploaddir['baseurl'] ) . 'wp-travel-engine/tmp' );

			$source      = $_FILES['file']['tmp_name'];
			$salt        = md5( $_FILES['file']['name'] . time() );
			$file_name   = $salt . '-' . $_FILES['file']['name'];
			$img_file_location = trailingslashit( $wte_temp_dir ) . $file_name;

			$upload_url        = trailingslashit( $wte_temp_url ) . $file_name;
			$uploaded_filetype = wp_check_filetype( basename( $img_file_location ), null );

			$uploaded_filesize = $_FILES['file']['size'];
			$max_upload_size   = wp_max_upload_size();

			if ( $uploaded_filesize > $max_upload_size ) {
				wp_send_json_error( array( 'message' => __( 'File size too large.', 'wp-travel-engine' ) ) );
			}

			if ( ! in_array( $uploaded_filetype['type'], $allowed_filetypes ) ) {
				wp_send_json_error( array( 'message' => __( 'Unsupported file type uploaded.', 'wp-travel-engine' ) ) );
			}

			if ( wp_mkdir_p( $wte_temp_dir ) ) :
				if ( move_uploaded_file( $source, $img_file_location ) ) :

					$file_array = array(
						'file' => $img_file_location,
						'url'  => $upload_url,
						'type' => $uploaded_filetype,
					);
					echo json_encode( $file_array );
					wp_die();

				endif;
			endif;
		endif;

		wp_send_json_error( __( 'Invalid request. Nonce verification failed.', 'wp-travel-engine' ) );
	}

	public static function wte_add_cpt_to_pll( $post_types, $is_settings ) {
		if ( $is_settings ) {
			unset( $post_types['my_cpt'] );
			unset( $post_types['my_cpt1'] );
			unset( $post_types['my_cpt2'] );
			unset( $post_types['my_cpt3'] );
		} else {
			$post_types['my_cpt']  = 'trip';
			$post_types['my_cpt1'] = 'booking';
			$post_types['my_cpt2'] = 'customer';
			$post_types['my_cpt3'] = 'enquiry';
		}
		return $post_types;
	}


	public static function wte_add_tax_to_pll( $taxonomies, $is_settings ) {
		if ( $is_settings ) {
			unset( $taxonomies['my_tax'] );
			unset( $taxonomies['my_tax1'] );
			unset( $taxonomies['my_tax2'] );
		} else {
			$taxonomies['my_tax']  = 'destination';
			$taxonomies['my_tax1'] = 'activities';
			$taxonomies['my_tax2'] = 'trip_types';
		}
		return $taxonomies;
	}

	// get vendor or admin currency
	function trip_vendor_admin_currency_code( $post ) {
		 $code                                   = 'USD';
		$wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', true );
		$user                                    = get_userdata( $post->post_author );
		if ( class_exists( 'Vendor_Wp_Travel_Engine' ) && $user && in_array( 'trip_vendor', $user->roles ) ) {
			$userid = $user->ID;
			$user   = get_user_meta( $userid, 'wpte_vendor', true );
			if ( isset( $user['currency_code'] ) && $user['currency_code'] != '' ) {
				$code = $user['currency_code'];
			}
		} else {
			$code = isset( $wp_travel_engine_setting_option_setting['currency_code'] ) ? esc_attr( $wp_travel_engine_setting_option_setting['currency_code'] ) : 'USD';
		}
		return $code;
	}

	function trip_currency_code( $post ) {
		$wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', true );
		$user                                    = get_userdata( $post->post_author );
		if ( class_exists( 'Vendor_Wp_Travel_Engine' ) && $user && in_array( 'trip_vendor', $user->roles ) ) {
			$userid = $user->ID;
			$user   = get_user_meta( $userid, 'wpte_vendor', true );
			if ( isset( $user['currency_code'] ) && $user['currency_code'] != '' ) {
				$code = $user['currency_code'];
			}
		} elseif ( isset( $wp_travel_engine_setting_option_setting['currency_code'] ) && $wp_travel_engine_setting_option_setting['currency_code'] != '' ) {
			$code = esc_attr( $wp_travel_engine_setting_option_setting['currency_code'] );
		} else {
			$code = 'USD';
		}
		$apiKey = isset( $wp_travel_engine_setting_option_setting['currency_converter_api'] ) && $wp_travel_engine_setting_option_setting['currency_converter_api'] != '' ? esc_attr( $wp_travel_engine_setting_option_setting['currency_converter_api'] ) : '';

		if ( class_exists( 'Wte_Trip_Currency_Converter_Init' ) && $apiKey != '' ) {
			$obj  = new \Wte_Trip_Currency_Converter_Init();
			$code = $obj->wte_trip_currency_code_converter( $code );
		}
		return $code;
	}

	function convert_trip_price( $post, $trip_price ) {
		 $code                                   = 'USD';
		$userid                                  = '';
		$wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', true );
		$user                                    = get_userdata( $post->post_author );
		if ( $user && in_array( 'trip_vendor', $user->roles ) ) {
			$userid = $user->ID;
			$user   = get_user_meta( $userid, 'wpte_vendor', true );
			if ( isset( $user['currency_code'] ) && $user['currency_code'] != '' ) {
				$code = $user['currency_code'];
			}
		} elseif ( isset( $wp_travel_engine_setting_option_setting['currency_code'] ) && $wp_travel_engine_setting_option_setting['currency_code'] != '' ) {
			$code = esc_attr( $wp_travel_engine_setting_option_setting['currency_code'] );
		}

		$global_code = $code;
		$obj         = new \Wte_Trip_Currency_Converter_Init();
		$code        = $obj->wte_trip_currency_code_converter( $global_code );
		$apiKey      = isset( $wp_travel_engine_setting_option_setting['currency_converter_api'] ) && $wp_travel_engine_setting_option_setting['currency_converter_api'] != '' ? esc_attr( $wp_travel_engine_setting_option_setting['currency_converter_api'] ) : '';

		if ( $global_code != $code && $apiKey != '' ) {
			$trip_price = $obj->wte_trip_price_converter( $userid, $global_code, $code, $trip_price );
		}
		return $trip_price;
	}

	function wte_trip_review() {
		if ( class_exists( 'Wte_Trip_Review_Init' ) ) {
			echo '<div class="star-holder">';
			global $post;
			$comments = get_comments(
				array(
					'post_id' => $post->ID,
					'status'  => 'approve',
				)
			);
			if ( ! empty( $comments ) ) {
				echo '<div class="review-wrap"><div class="average-rating">';
				$sum = 0;
				$i   = 0;
				$ran = rand( 1, 1000 );
				$ran++;
				foreach ( $comments as $comment ) {
					$rating = get_comment_meta( $comment->comment_ID, 'stars', true );
					$sum    = $sum + $rating;
					$i++;
				}
				$aggregate = $sum / $i;
				$aggregate = round( $aggregate, 2 );

				?>
				<script>
					jQuery(document).ready(function($){
						// $("#agg-rating-<?php echo (float) $ran; ?>").rateYo({
						// 	rating: <?php floatval( $aggregate ); ?>
						// });
					});
				</script>
				<div id="agg-rating-<?php echo esc_attr( $ran ); ?>" class="agg-rating"></div><div class="aggregate-rating">
				<span class="rating-star"><?php echo (float) $aggregate; ?></span><span><?php echo (float) $i; ?></span><?php echo esc_html( _nx( 'review', 'reviews', $i, 'reviews count', 'wp-travel-engine' ) ); ?></div>
				</div></div><!-- .review-wrap -->
				<?php
			}
			echo '</div>';
		}
	}

	// search value in array
	function wp_travel_engine_in_array_r( $needle, $haystack, $strict = false ) {
		foreach ( $haystack as $item ) {
			if ( ( $strict ? $item === $needle : $item == $needle ) || ( is_array( $item ) && \wte_functions()->wp_travel_engine_in_array_r( $needle, $item, $strict ) ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get Base Currency Code.
	 *
	 * @return string
	 */
	function wp_travel_engine_currency() {
		$option        = '';
		$option        = get_option( 'wp_travel_engine_settings' );
		$currency_type = $option['currency_code'];
		return apply_filters( 'wp_travel_engine_currency', $currency_type );
	}

	public static function wte_remove_empty_p( $content ) {
		/**
		 * Commented to resolve the conflict with ninja form.
		 *
		 * @since 4.3.6
		 */
		// $content = force_balance_tags( $content );
		$content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
		$content = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content );
		return $content;
	}


	/**
	 * Get Pagination.
	 *
	 * @return string
	 */
	function pagination_bar( $custom_query ) {

		$total_pages = $custom_query->max_num_pages;
		$big         = 999999999; // need an unlikely integer

		if ( $total_pages > 1 ) {
			$current_page = max( 1, get_query_var( 'paged' ) );

			echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format'  => '?paged=%#%',
					'current' => $current_page,
					'total'   => $total_pages,
				)
			);
		}
	}

	function wpte_pagination_option() {
		$pagination_type = get_theme_mod( 'pagination_type' );
		if ( $pagination_type == 'pagination_type-radio-numbered' ) {
			\wte_functions()->pagination_bar();
		} elseif ( $pagination_type == 'pagination_type-radio-default' ) {
			$args = array(
				'base'               => '%_%',
				'format'             => '?paged=%#%',
				'total'              => 1,
				'current'            => 0,
				'show_all'           => false,
				'end_size'           => 1,
				'mid_size'           => 2,
				'prev_next'          => true,
				'prev_text'          => __( '« Previous', 'wp-travel-engine' ),
				'next_text'          => __( 'Next »', 'wp-travel-engine' ),
				'type'               => 'plain',
				'add_args'           => false,
				'add_fragment'       => '',
				'before_page_number' => '',
				'after_page_number'  => '',
			);
			echo paginate_links( $args ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Get currency codes or currency symbol.
	 *
	 * @return array
	 */
	function wte_trip_symbol_options( $code, $currency, $cost ) {
		$obj      = \wte_functions();
		$settings = get_option( 'wp_travel_engine_settings' );
		$option   = isset( $settings['currency_option'] ) && $settings['currency_option'] != '' ? esc_attr( $settings['currency_option'] ) : 'symbol';

		if ( isset( $option ) && $option == 'symbol' ) {
			return '<span class="price-holder"><span>' . esc_attr( $currency ) . '&nbsp;' . esc_attr( $obj->wp_travel_engine_price_format( $cost ) ) . '</span></span>';
		} else {
			return '<span class="price-holder"><span>' . esc_attr( $code ) . '&nbsp;' . esc_attr( $obj->wp_travel_engine_price_format( $cost ) ) . '</span></span>';
		}
	}

	/**
	 * Get full list of currency codes.
	 *
	 * @return array
	 */
	function wp_travel_engine_currencies() {
		return array_unique(
			apply_filters(
				'wp_travel_engine_currencies',
				array(
					'AED' => __( 'United Arab Emirates dirham', 'wp-travel-engine' ),
					'AFN' => __( 'Afghan afghani', 'wp-travel-engine' ),
					'ALL' => __( 'Albanian lek', 'wp-travel-engine' ),
					'AMD' => __( 'Armenian dram', 'wp-travel-engine' ),
					'ANG' => __( 'Netherlands Antillean guilder', 'wp-travel-engine' ),
					'AOA' => __( 'Angolan kwanza', 'wp-travel-engine' ),
					'ARS' => __( 'Argentine peso', 'wp-travel-engine' ),
					'AUD' => __( 'Australian dollar', 'wp-travel-engine' ),
					'AWG' => __( 'Aruban florin', 'wp-travel-engine' ),
					'AZN' => __( 'Azerbaijani manat', 'wp-travel-engine' ),
					'BAM' => __( 'Bosnia and Herzegovina convertible mark', 'wp-travel-engine' ),
					'BBD' => __( 'Barbadian dollar', 'wp-travel-engine' ),
					'BDT' => __( 'Bangladeshi taka', 'wp-travel-engine' ),
					'BGN' => __( 'Bulgarian lev', 'wp-travel-engine' ),
					'BHD' => __( 'Bahraini dinar', 'wp-travel-engine' ),
					'BIF' => __( 'Burundian franc', 'wp-travel-engine' ),
					'BMD' => __( 'Bermudian dollar', 'wp-travel-engine' ),
					'BND' => __( 'Brunei dollar', 'wp-travel-engine' ),
					'BOB' => __( 'Bolivian boliviano', 'wp-travel-engine' ),
					'BRL' => __( 'Brazilian real', 'wp-travel-engine' ),
					'BSD' => __( 'Bahamian dollar', 'wp-travel-engine' ),
					'BTC' => __( 'Bitcoin', 'wp-travel-engine' ),
					'BTN' => __( 'Bhutanese ngultrum', 'wp-travel-engine' ),
					'BWP' => __( 'Botswana pula', 'wp-travel-engine' ),
					'BYR' => __( 'Belarusian ruble (old)', 'wp-travel-engine' ),
					'BYN' => __( 'Belarusian ruble', 'wp-travel-engine' ),
					'BZD' => __( 'Belize dollar', 'wp-travel-engine' ),
					'CAD' => __( 'Canadian dollar', 'wp-travel-engine' ),
					'CDF' => __( 'Congolese franc', 'wp-travel-engine' ),
					'CHF' => __( 'Swiss franc', 'wp-travel-engine' ),
					'CLP' => __( 'Chilean peso', 'wp-travel-engine' ),
					'CNY' => __( 'Chinese yuan', 'wp-travel-engine' ),
					'COP' => __( 'Colombian peso', 'wp-travel-engine' ),
					'CRC' => __( 'Costa Rican col&oacute;n', 'wp-travel-engine' ),
					'CUC' => __( 'Cuban convertible peso', 'wp-travel-engine' ),
					'CUP' => __( 'Cuban peso', 'wp-travel-engine' ),
					'CVE' => __( 'Cape Verdean escudo', 'wp-travel-engine' ),
					'CZK' => __( 'Czech koruna', 'wp-travel-engine' ),
					'DJF' => __( 'Djiboutian franc', 'wp-travel-engine' ),
					'DKK' => __( 'Danish krone', 'wp-travel-engine' ),
					'DOP' => __( 'Dominican peso', 'wp-travel-engine' ),
					'DZD' => __( 'Algerian dinar', 'wp-travel-engine' ),
					'EGP' => __( 'Egyptian pound', 'wp-travel-engine' ),
					'ERN' => __( 'Eritrean nakfa', 'wp-travel-engine' ),
					'ETB' => __( 'Ethiopian birr', 'wp-travel-engine' ),
					'EUR' => __( 'Euro', 'wp-travel-engine' ),
					'FJD' => __( 'Fijian dollar', 'wp-travel-engine' ),
					'FKP' => __( 'Falkland Islands pound', 'wp-travel-engine' ),
					'GBP' => __( 'Pound sterling', 'wp-travel-engine' ),
					'GEL' => __( 'Georgian lari', 'wp-travel-engine' ),
					'GGP' => __( 'Guernsey pound', 'wp-travel-engine' ),
					'GHS' => __( 'Ghana cedi', 'wp-travel-engine' ),
					'GIP' => __( 'Gibraltar pound', 'wp-travel-engine' ),
					'GMD' => __( 'Gambian dalasi', 'wp-travel-engine' ),
					'GNF' => __( 'Guinean franc', 'wp-travel-engine' ),
					'GTQ' => __( 'Guatemalan quetzal', 'wp-travel-engine' ),
					'GYD' => __( 'Guyanese dollar', 'wp-travel-engine' ),
					'HKD' => __( 'Hong Kong dollar', 'wp-travel-engine' ),
					'HNL' => __( 'Honduran lempira', 'wp-travel-engine' ),
					'HRK' => __( 'Croatian kuna', 'wp-travel-engine' ),
					'HTG' => __( 'Haitian gourde', 'wp-travel-engine' ),
					'HUF' => __( 'Hungarian forint', 'wp-travel-engine' ),
					'IDR' => __( 'Indonesian rupiah', 'wp-travel-engine' ),
					'ILS' => __( 'Israeli new shekel', 'wp-travel-engine' ),
					'IMP' => __( 'Manx pound', 'wp-travel-engine' ),
					'INR' => __( 'Indian rupee', 'wp-travel-engine' ),
					'IQD' => __( 'Iraqi dinar', 'wp-travel-engine' ),
					'IRR' => __( 'Iranian rial', 'wp-travel-engine' ),
					'IRT' => __( 'Iranian toman', 'wp-travel-engine' ),
					'ISK' => __( 'Icelandic kr&oacute;na', 'wp-travel-engine' ),
					'JEP' => __( 'Jersey pound', 'wp-travel-engine' ),
					'JMD' => __( 'Jamaican dollar', 'wp-travel-engine' ),
					'JOD' => __( 'Jordanian dinar', 'wp-travel-engine' ),
					'JPY' => __( 'Japanese yen', 'wp-travel-engine' ),
					'KES' => __( 'Kenyan shilling', 'wp-travel-engine' ),
					'KGS' => __( 'Kyrgyzstani som', 'wp-travel-engine' ),
					'KHR' => __( 'Cambodian riel', 'wp-travel-engine' ),
					'KMF' => __( 'Comorian franc', 'wp-travel-engine' ),
					'KPW' => __( 'North Korean won', 'wp-travel-engine' ),
					'KRW' => __( 'South Korean won', 'wp-travel-engine' ),
					'KWD' => __( 'Kuwaiti dinar', 'wp-travel-engine' ),
					'KYD' => __( 'Cayman Islands dollar', 'wp-travel-engine' ),
					'KZT' => __( 'Kazakhstani tenge', 'wp-travel-engine' ),
					'LAK' => __( 'Lao kip', 'wp-travel-engine' ),
					'LBP' => __( 'Lebanese pound', 'wp-travel-engine' ),
					'LKR' => __( 'Sri Lankan rupee', 'wp-travel-engine' ),
					'LRD' => __( 'Liberian dollar', 'wp-travel-engine' ),
					'LSL' => __( 'Lesotho loti', 'wp-travel-engine' ),
					'LYD' => __( 'Libyan dinar', 'wp-travel-engine' ),
					'MAD' => __( 'Moroccan dirham', 'wp-travel-engine' ),
					'MDL' => __( 'Moldovan leu', 'wp-travel-engine' ),
					'MGA' => __( 'Malagasy ariary', 'wp-travel-engine' ),
					'MKD' => __( 'Macedonian denar', 'wp-travel-engine' ),
					'MMK' => __( 'Burmese kyat', 'wp-travel-engine' ),
					'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', 'wp-travel-engine' ),
					'MOP' => __( 'Macanese pataca', 'wp-travel-engine' ),
					'MRO' => __( 'Mauritanian ouguiya', 'wp-travel-engine' ),
					'MUR' => __( 'Mauritian rupee', 'wp-travel-engine' ),
					'MVR' => __( 'Maldivian rufiyaa', 'wp-travel-engine' ),
					'MWK' => __( 'Malawian kwacha', 'wp-travel-engine' ),
					'MXN' => __( 'Mexican peso', 'wp-travel-engine' ),
					'MYR' => __( 'Malaysian ringgit', 'wp-travel-engine' ),
					'MZN' => __( 'Mozambican metical', 'wp-travel-engine' ),
					'NAD' => __( 'Namibian dollar', 'wp-travel-engine' ),
					'NGN' => __( 'Nigerian naira', 'wp-travel-engine' ),
					'NIO' => __( 'Nicaraguan c&oacute;rdoba', 'wp-travel-engine' ),
					'NOK' => __( 'Norwegian krone', 'wp-travel-engine' ),
					'NPR' => __( 'Nepalese rupee', 'wp-travel-engine' ),
					'NZD' => __( 'New Zealand dollar', 'wp-travel-engine' ),
					'OMR' => __( 'Omani rial', 'wp-travel-engine' ),
					'PAB' => __( 'Panamanian balboa', 'wp-travel-engine' ),
					'PEN' => __( 'Peruvian nuevo sol', 'wp-travel-engine' ),
					'PGK' => __( 'Papua New Guinean kina', 'wp-travel-engine' ),
					'PHP' => __( 'Philippine peso', 'wp-travel-engine' ),
					'PKR' => __( 'Pakistani rupee', 'wp-travel-engine' ),
					'PLN' => __( 'Polish z&#x142;oty', 'wp-travel-engine' ),
					'PRB' => __( 'Transnistrian ruble', 'wp-travel-engine' ),
					'PYG' => __( 'Paraguayan guaran&iacute;', 'wp-travel-engine' ),
					'QAR' => __( 'Qatari riyal', 'wp-travel-engine' ),
					'RON' => __( 'Romanian leu', 'wp-travel-engine' ),
					'RSD' => __( 'Serbian dinar', 'wp-travel-engine' ),
					'RUB' => __( 'Russian ruble', 'wp-travel-engine' ),
					'RWF' => __( 'Rwandan franc', 'wp-travel-engine' ),
					'SAR' => __( 'Saudi riyal', 'wp-travel-engine' ),
					'SBD' => __( 'Solomon Islands dollar', 'wp-travel-engine' ),
					'SCR' => __( 'Seychellois rupee', 'wp-travel-engine' ),
					'SDG' => __( 'Sudanese pound', 'wp-travel-engine' ),
					'SEK' => __( 'Swedish krona', 'wp-travel-engine' ),
					'SGD' => __( 'Singapore dollar', 'wp-travel-engine' ),
					'SHP' => __( 'Saint Helena pound', 'wp-travel-engine' ),
					'SLL' => __( 'Sierra Leonean leone', 'wp-travel-engine' ),
					'SOS' => __( 'Somali shilling', 'wp-travel-engine' ),
					'SRD' => __( 'Surinamese dollar', 'wp-travel-engine' ),
					'SSP' => __( 'South Sudanese pound', 'wp-travel-engine' ),
					'STD' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'wp-travel-engine' ),
					'SYP' => __( 'Syrian pound', 'wp-travel-engine' ),
					'SZL' => __( 'Swazi lilangeni', 'wp-travel-engine' ),
					'THB' => __( 'Thai baht', 'wp-travel-engine' ),
					'TJS' => __( 'Tajikistani somoni', 'wp-travel-engine' ),
					'TMT' => __( 'Turkmenistan manat', 'wp-travel-engine' ),
					'TND' => __( 'Tunisian dinar', 'wp-travel-engine' ),
					'TOP' => __( 'Tongan pa&#x2bb;anga', 'wp-travel-engine' ),
					'TRY' => __( 'Turkish lira', 'wp-travel-engine' ),
					'TTD' => __( 'Trinidad and Tobago dollar', 'wp-travel-engine' ),
					'TWD' => __( 'New Taiwan dollar', 'wp-travel-engine' ),
					'TZS' => __( 'Tanzanian shilling', 'wp-travel-engine' ),
					'UAH' => __( 'Ukrainian hryvnia', 'wp-travel-engine' ),
					'UGX' => __( 'Ugandan shilling', 'wp-travel-engine' ),
					'USD' => __( 'United States (US) dollar', 'wp-travel-engine' ),
					'UYU' => __( 'Uruguayan peso', 'wp-travel-engine' ),
					'UZS' => __( 'Uzbekistani som', 'wp-travel-engine' ),
					'VEF' => __( 'Venezuelan bol&iacute;var', 'wp-travel-engine' ),
					'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', 'wp-travel-engine' ),
					'VUV' => __( 'Vanuatu vatu', 'wp-travel-engine' ),
					'WST' => __( 'Samoan t&#x101;l&#x101;', 'wp-travel-engine' ),
					'XAF' => __( 'Central African CFA franc', 'wp-travel-engine' ),
					'XCD' => __( 'East Caribbean dollar', 'wp-travel-engine' ),
					'XOF' => __( 'West African CFA franc', 'wp-travel-engine' ),
					'XPF' => __( 'CFP franc', 'wp-travel-engine' ),
					'YER' => __( 'Yemeni rial', 'wp-travel-engine' ),
					'ZAR' => __( 'South African rand', 'wp-travel-engine' ),
					'ZMW' => __( 'Zambian kwacha', 'wp-travel-engine' ),
				)
			)
		);
	}

	public static function currency_symbol_by_code( $currency = '' ) {
		if ( ! $currency ) {
			$currency = \wte_functions()->wp_travel_engine_currency();
		}

		$symbols = apply_filters(
			'wp_travel_engine_currency_symbols',
			array(
				'AED' => '&#x62f;.&#x625;',
				'AFN' => '&#x60b;',
				'ALL' => 'L',
				'AMD' => 'AMD',
				'ANG' => '&fnof;',
				'AOA' => 'Kz',
				'ARS' => '&#36;',
				'AUD' => '&#36;',
				'AWG' => 'Afl.',
				'AZN' => 'AZN',
				'BAM' => 'KM',
				'BBD' => '&#36;',
				'BDT' => '&#2547;&nbsp;',
				'BGN' => '&#1083;&#1074;.',
				'BHD' => '.&#x62f;.&#x628;',
				'BIF' => 'Fr',
				'BMD' => '&#36;',
				'BND' => '&#36;',
				'BOB' => 'Bs.',
				'BRL' => '&#82;&#36;',
				'BSD' => '&#36;',
				'BTC' => '&#3647;',
				'BTN' => 'Nu.',
				'BWP' => 'P',
				'BYR' => 'Br',
				'BYN' => 'Br',
				'BZD' => '&#36;',
				'CAD' => '&#36;',
				'CDF' => 'Fr',
				'CHF' => '&#67;&#72;&#70;',
				'CLP' => '&#36;',
				'CNY' => '&yen;',
				'COP' => '&#36;',
				'CRC' => '&#x20a1;',
				'CUC' => '&#36;',
				'CUP' => '&#36;',
				'CVE' => '&#36;',
				'CZK' => '&#75;&#269;',
				'DJF' => 'Fr',
				'DKK' => 'DKK',
				'DOP' => 'RD&#36;',
				'DZD' => '&#x62f;.&#x62c;',
				'EGP' => 'EGP',
				'ERN' => 'Nfk',
				'ETB' => 'Br',
				'EUR' => '&euro;',
				'FJD' => '&#36;',
				'FKP' => '&pound;',
				'GBP' => '&pound;',
				'GEL' => '&#x20be;',
				'GGP' => '&pound;',
				'GHS' => '&#x20b5;',
				'GIP' => '&pound;',
				'GMD' => 'D',
				'GNF' => 'Fr',
				'GTQ' => 'Q',
				'GYD' => '&#36;',
				'HKD' => '&#36;',
				'HNL' => 'L',
				'HRK' => 'kn',
				'HTG' => 'G',
				'HUF' => '&#70;&#116;',
				'IDR' => 'Rp',
				'ILS' => '&#8362;',
				'IMP' => '&pound;',
				'INR' => '&#8377;',
				'IQD' => '&#x639;.&#x62f;',
				'IRR' => '&#xfdfc;',
				'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
				'ISK' => 'kr.',
				'JEP' => '&pound;',
				'JMD' => '&#36;',
				'JOD' => '&#x62f;.&#x627;',
				'JPY' => '&yen;',
				'KES' => 'KSh',
				'KGS' => '&#x441;&#x43e;&#x43c;',
				'KHR' => '&#x17db;',
				'KMF' => 'Fr',
				'KPW' => '&#x20a9;',
				'KRW' => '&#8361;',
				'KWD' => '&#x62f;.&#x643;',
				'KYD' => '&#36;',
				'KZT' => 'KZT',
				'LAK' => '&#8365;',
				'LBP' => '&#x644;.&#x644;',
				'LKR' => '&#xdbb;&#xdd4;',
				'LRD' => '&#36;',
				'LSL' => 'L',
				'LYD' => '&#x644;.&#x62f;',
				'MAD' => '&#x62f;.&#x645;.',
				'MDL' => 'MDL',
				'MGA' => 'Ar',
				'MKD' => '&#x434;&#x435;&#x43d;',
				'MMK' => 'Ks',
				'MNT' => '&#x20ae;',
				'MOP' => 'P',
				'MRO' => 'UM',
				'MUR' => '&#x20a8;',
				'MVR' => '.&#x783;',
				'MWK' => 'MK',
				'MXN' => '&#36;',
				'MYR' => '&#82;&#77;',
				'MZN' => 'MT',
				'NAD' => '&#36;',
				'NGN' => '&#8358;',
				'NIO' => 'C&#36;',
				'NOK' => '&#107;&#114;',
				'NPR' => '&#8360;',
				'NZD' => '&#36;',
				'OMR' => '&#x631;.&#x639;.',
				'PAB' => 'B/.',
				'PEN' => 'S/.',
				'PGK' => 'K',
				'PHP' => '&#8369;',
				'PKR' => '&#8360;',
				'PLN' => '&#122;&#322;',
				'PRB' => '&#x440;.',
				'PYG' => '&#8370;',
				'QAR' => '&#x631;.&#x642;',
				'RMB' => '&yen;',
				'RON' => 'lei',
				'RSD' => '&#x434;&#x438;&#x43d;.',
				'RUB' => '&#8381;',
				'RWF' => 'Fr',
				'SAR' => '&#x631;.&#x633;',
				'SBD' => '&#36;',
				'SCR' => '&#x20a8;',
				'SDG' => '&#x62c;.&#x633;.',
				'SEK' => '&#107;&#114;',
				'SGD' => '&#36;',
				'SHP' => '&pound;',
				'SLL' => 'Le',
				'SOS' => 'Sh',
				'SRD' => '&#36;',
				'SSP' => '&pound;',
				'STD' => 'Db',
				'SYP' => '&#x644;.&#x633;',
				'SZL' => 'L',
				'THB' => '&#3647;',
				'TJS' => '&#x405;&#x41c;',
				'TMT' => 'm',
				'TND' => '&#x62f;.&#x62a;',
				'TOP' => 'T&#36;',
				'TRY' => '&#8378;',
				'TTD' => '&#36;',
				'TWD' => '&#78;&#84;&#36;',
				'TZS' => 'Sh',
				'UAH' => '&#8372;',
				'UGX' => 'UGX',
				'USD' => '&#36;',
				'UYU' => '&#36;',
				'UZS' => 'UZS',
				'VEF' => 'Bs F',
				'VND' => '&#8363;',
				'VUV' => 'Vt',
				'WST' => 'T',
				'XAF' => 'CFA',
				'XCD' => '&#36;',
				'XOF' => 'CFA',
				'XPF' => 'Fr',
				'YER' => '&#xfdfc;',
				'ZAR' => '&#82;',
				'ZMW' => 'ZK',
			)
		);

		$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

		return apply_filters( 'wp_travel_engine_currency_symbol', $currency_symbol, $currency );
	}

	/**
	 * Get Currency symbol.
	 *
	 * @param string $currency (default: '')
	 * @return string
	 */
	function wp_travel_engine_currencies_symbol( $currency = '' ) {
		return self::currency_symbol_by_code( $currency );
	}

	/**
	 * Get default settings when no settings are saved
	 *
	 * @return array of default settings
	 */
	public function wp_travel_engine_get_default_settings() {

		$default_settings = array(
			'currency_code' => 'USD',
			'price'         => '0.01',
			'charges'       => '50.01',
		);
		$default_settings = apply_filters( 'wp_travel_engine_default_settings', $default_settings );
		return $default_settings;
	}

	/**
	 * Get clean special characters free string
	 *
	 * @return clean string
	 */
	public function wpte_clean( $string ) {
		$string = str_replace( ' ', '-', $string ); // Replaces all spaces with hyphens.
		$string = preg_replace( '/[^A-Za-z0-9\-]/', '', $string ); // Removes special chars.
		$string = strtolower( $string ); // Convert to lowercase
		return $string;
	}

	/**
	 * Get field options for trip facts.
	 *
	 * @return array
	 */
	function trip_facts_field_options() {
		$options = array(
			'text'     => 'text',
			'number'   => 'number',
			'select'   => 'select',
			'textarea' => 'textarea',
			'duration' => 'duration',
		);
		$options = apply_filters( 'wp_travel_engine_trip_facts_field_options', $options );
		return $options;
	}

	/**
	 * Get options for title while booking trip.
	 *
	 * @param string $title (default: '')
	 * @return string
	 */
	function order_form_title_options() {
		$options = array(
			'Mr'    => __( 'Mr', 'wp-travel-engine' ),
			'Mrs'   => __( 'Mrs', 'wp-travel-engine' ),
			'Ms'    => __( 'Ms', 'wp-travel-engine' ),
			'Miss'  => __( 'Miss', 'wp-travel-engine' ),
			'Other' => __( 'Other', 'wp-travel-engine' ),
		);
		$options = apply_filters( 'wp_travel_engine_order_form_title_options', $options );
		return $options;
	}

	/**
	 * Get default payment method.
	 *
	 * @param string $options (default: '')
	 * @return string
	 */
	function payment_gateway_options() {
		$options = array(
			'paypal_standard' => 'PayPal Standard',
			'test_payment'    => 'Test Payment',
			'amazon'          => 'Amazon',
		);
		$options = apply_filters( 'wp_travel_engine_default_payment_gateway_options', $options );
		return $options;
	}

	/**
	 * Get field options for place order form.
	 */
	function wp_travel_engine_place_order_field_options() {
		$options = array(
			'text'         => 'text',
			'number'       => 'number',
			'select'       => 'select',
			'textarea'     => 'textarea',
			'country-list' => 'countrylist',
			'datetime'     => 'datetime',
			'email'        => 'email',
		);
		$options = apply_filters( 'wp_travel_engine_place_order_field_options', $options );
		return $options;
	}

	/**
	 * Get template options for place order form.
	 */
	function wp_travel_engine_template_options() {
		$options = array(
			'default-template' => 'default-template',
		);
		$options = apply_filters( 'wp_travel_engine_template_options', $options );
		return $options;
	}

	function getLen( $var ) {
		$settings            = get_option( 'wp_travel_engine_settings' );
		$thousands_separator = isset( $settings['thousands_separator'] ) && $settings['thousands_separator'] != '' ? esc_attr( $settings['thousands_separator'] ) : ',';
		$tmp                 = explode( $thousands_separator, $var );
		if ( count( $tmp ) > 1 ) {
			return strlen( $tmp[1] );
		}
	}

	/**
	 * Get formatted cost.
	 *
	 * @param string $formatted_cost (default: '')
	 * @return string
	 */
	function wp_travel_engine_price_format( $cost = '' ) {
		$settings            = get_option( 'wp_travel_engine_settings' );
		$thousands_separator = isset( $settings['thousands_separator'] ) && $settings['thousands_separator'] != '' ? esc_attr( $settings['thousands_separator'] ) : ',';
		// $formatted_cost = number_format($cost, $this->getLen($cost));
		$formatted_cost = number_format( (int) $cost, 0, '', apply_filters( 'wp_travel_engine_default_separator', $thousands_separator ) );
		return $formatted_cost;
	}

	/**
	 * Get country list for dropdown.
	 *
	 * @since 1.0.0
	 */
	function wp_travel_engine_country_list() {
		$options = array(
			'AF' => __( 'Afghanistan', 'wp-travel-engine' ),
			'AX' => __( 'Åland Islands', 'wp-travel-engine' ),
			'AL' => __( 'Albania', 'wp-travel-engine' ),
			'DZ' => __( 'Algeria', 'wp-travel-engine' ),
			'AS' => __( 'American Samoa', 'wp-travel-engine' ),
			'AD' => __( 'Andorra', 'wp-travel-engine' ),
			'AO' => __( 'Angola', 'wp-travel-engine' ),
			'AI' => __( 'Anguilla', 'wp-travel-engine' ),
			'AQ' => __( 'Antarctica', 'wp-travel-engine' ),
			'AG' => __( 'Antigua and Barbuda', 'wp-travel-engine' ),
			'AR' => __( 'Argentina', 'wp-travel-engine' ),
			'AM' => __( 'Armenia', 'wp-travel-engine' ),
			'AW' => __( 'Aruba', 'wp-travel-engine' ),
			'AU' => __( 'Australia', 'wp-travel-engine' ),
			'AT' => __( 'Austria', 'wp-travel-engine' ),
			'AZ' => __( 'Azerbaijan', 'wp-travel-engine' ),
			'BS' => __( 'Bahamas', 'wp-travel-engine' ),
			'BH' => __( 'Bahrain', 'wp-travel-engine' ),
			'BD' => __( 'Bangladesh', 'wp-travel-engine' ),
			'BB' => __( 'Barbados', 'wp-travel-engine' ),
			'BY' => __( 'Belarus', 'wp-travel-engine' ),
			'BE' => __( 'Belgium', 'wp-travel-engine' ),
			'PW' => __( 'Belau', 'wp-travel-engine' ),
			'BZ' => __( 'Belize', 'wp-travel-engine' ),
			'BJ' => __( 'Benin', 'wp-travel-engine' ),
			'BM' => __( 'Bermuda', 'wp-travel-engine' ),
			'BT' => __( 'Bhutan', 'wp-travel-engine' ),
			'BO' => __( 'Bolivia', 'wp-travel-engine' ),
			'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'wp-travel-engine' ),
			'BA' => __( 'Bosnia and Herzegovina', 'wp-travel-engine' ),
			'BW' => __( 'Botswana', 'wp-travel-engine' ),
			'BV' => __( 'Bouvet Island', 'wp-travel-engine' ),
			'BR' => __( 'Brazil', 'wp-travel-engine' ),
			'IO' => __( 'British Indian Ocean Territory', 'wp-travel-engine' ),
			'BN' => __( 'Brunei', 'wp-travel-engine' ),
			'BG' => __( 'Bulgaria', 'wp-travel-engine' ),
			'BF' => __( 'Burkina Faso', 'wp-travel-engine' ),
			'BI' => __( 'Burundi', 'wp-travel-engine' ),
			'KH' => __( 'Cambodia', 'wp-travel-engine' ),
			'CM' => __( 'Cameroon', 'wp-travel-engine' ),
			'CA' => __( 'Canada', 'wp-travel-engine' ),
			'CV' => __( 'Cape Verde', 'wp-travel-engine' ),
			'KY' => __( 'Cayman Islands', 'wp-travel-engine' ),
			'CF' => __( 'Central African Republic', 'wp-travel-engine' ),
			'TD' => __( 'Chad', 'wp-travel-engine' ),
			'CL' => __( 'Chile', 'wp-travel-engine' ),
			'CN' => __( 'China', 'wp-travel-engine' ),
			'CX' => __( 'Christmas Island', 'wp-travel-engine' ),
			'CC' => __( 'Cocos (Keeling) Islands', 'wp-travel-engine' ),
			'CO' => __( 'Colombia', 'wp-travel-engine' ),
			'KM' => __( 'Comoros', 'wp-travel-engine' ),
			'CG' => __( 'Congo (Brazzaville)', 'wp-travel-engine' ),
			'CD' => __( 'Congo (Kinshasa)', 'wp-travel-engine' ),
			'CK' => __( 'Cook Islands', 'wp-travel-engine' ),
			'CR' => __( 'Costa Rica', 'wp-travel-engine' ),
			'HR' => __( 'Croatia', 'wp-travel-engine' ),
			'CU' => __( 'Cuba', 'wp-travel-engine' ),
			'CW' => __( 'Cura&ccedil;ao', 'wp-travel-engine' ),
			'CY' => __( 'Cyprus', 'wp-travel-engine' ),
			'CZ' => __( 'Czech Republic', 'wp-travel-engine' ),
			'DK' => __( 'Denmark', 'wp-travel-engine' ),
			'DJ' => __( 'Djibouti', 'wp-travel-engine' ),
			'DM' => __( 'Dominica', 'wp-travel-engine' ),
			'DO' => __( 'Dominican Republic', 'wp-travel-engine' ),
			'EC' => __( 'Ecuador', 'wp-travel-engine' ),
			'EG' => __( 'Egypt', 'wp-travel-engine' ),
			'SV' => __( 'El Salvador', 'wp-travel-engine' ),
			'GQ' => __( 'Equatorial Guinea', 'wp-travel-engine' ),
			'ER' => __( 'Eritrea', 'wp-travel-engine' ),
			'EE' => __( 'Estonia', 'wp-travel-engine' ),
			'ET' => __( 'Ethiopia', 'wp-travel-engine' ),
			'FK' => __( 'Falkland Islands', 'wp-travel-engine' ),
			'FO' => __( 'Faroe Islands', 'wp-travel-engine' ),
			'FJ' => __( 'Fiji', 'wp-travel-engine' ),
			'FI' => __( 'Finland', 'wp-travel-engine' ),
			'FR' => __( 'France', 'wp-travel-engine' ),
			'GF' => __( 'French Guiana', 'wp-travel-engine' ),
			'PF' => __( 'French Polynesia', 'wp-travel-engine' ),
			'TF' => __( 'French Southern Territories', 'wp-travel-engine' ),
			'GA' => __( 'Gabon', 'wp-travel-engine' ),
			'GM' => __( 'Gambia', 'wp-travel-engine' ),
			'GE' => __( 'Georgia', 'wp-travel-engine' ),
			'DE' => __( 'Germany', 'wp-travel-engine' ),
			'GH' => __( 'Ghana', 'wp-travel-engine' ),
			'GI' => __( 'Gibraltar', 'wp-travel-engine' ),
			'GR' => __( 'Greece', 'wp-travel-engine' ),
			'GL' => __( 'Greenland', 'wp-travel-engine' ),
			'GD' => __( 'Grenada', 'wp-travel-engine' ),
			'GP' => __( 'Guadeloupe', 'wp-travel-engine' ),
			'GU' => __( 'Guam', 'wp-travel-engine' ),
			'GT' => __( 'Guatemala', 'wp-travel-engine' ),
			'GG' => __( 'Guernsey', 'wp-travel-engine' ),
			'GN' => __( 'Guinea', 'wp-travel-engine' ),
			'GW' => __( 'Guinea-Bissau', 'wp-travel-engine' ),
			'GY' => __( 'Guyana', 'wp-travel-engine' ),
			'HT' => __( 'Haiti', 'wp-travel-engine' ),
			'HM' => __( 'Heard Island and McDonald Islands', 'wp-travel-engine' ),
			'HN' => __( 'Honduras', 'wp-travel-engine' ),
			'HK' => __( 'Hong Kong', 'wp-travel-engine' ),
			'HU' => __( 'Hungary', 'wp-travel-engine' ),
			'IS' => __( 'Iceland', 'wp-travel-engine' ),
			'IN' => __( 'India', 'wp-travel-engine' ),
			'ID' => __( 'Indonesia', 'wp-travel-engine' ),
			'IR' => __( 'Iran', 'wp-travel-engine' ),
			'IQ' => __( 'Iraq', 'wp-travel-engine' ),
			'IE' => __( 'Ireland', 'wp-travel-engine' ),
			'IM' => __( 'Isle of Man', 'wp-travel-engine' ),
			'IL' => __( 'Israel', 'wp-travel-engine' ),
			'IT' => __( 'Italy', 'wp-travel-engine' ),
			'CI' => __( 'Ivory Coast', 'wp-travel-engine' ),
			'JM' => __( 'Jamaica', 'wp-travel-engine' ),
			'JP' => __( 'Japan', 'wp-travel-engine' ),
			'JE' => __( 'Jersey', 'wp-travel-engine' ),
			'JO' => __( 'Jordan', 'wp-travel-engine' ),
			'KZ' => __( 'Kazakhstan', 'wp-travel-engine' ),
			'KE' => __( 'Kenya', 'wp-travel-engine' ),
			'KI' => __( 'Kiribati', 'wp-travel-engine' ),
			'KW' => __( 'Kuwait', 'wp-travel-engine' ),
			'KG' => __( 'Kyrgyzstan', 'wp-travel-engine' ),
			'LA' => __( 'Laos', 'wp-travel-engine' ),
			'LV' => __( 'Latvia', 'wp-travel-engine' ),
			'LB' => __( 'Lebanon', 'wp-travel-engine' ),
			'LS' => __( 'Lesotho', 'wp-travel-engine' ),
			'LR' => __( 'Liberia', 'wp-travel-engine' ),
			'LY' => __( 'Libya', 'wp-travel-engine' ),
			'LI' => __( 'Liechtenstein', 'wp-travel-engine' ),
			'LT' => __( 'Lithuania', 'wp-travel-engine' ),
			'LU' => __( 'Luxembourg', 'wp-travel-engine' ),
			'MO' => __( 'Macao', 'wp-travel-engine' ),
			'MK' => __( 'North Macedonia', 'wp-travel-engine' ),
			'MG' => __( 'Madagascar', 'wp-travel-engine' ),
			'MW' => __( 'Malawi', 'wp-travel-engine' ),
			'MY' => __( 'Malaysia', 'wp-travel-engine' ),
			'MV' => __( 'Maldives', 'wp-travel-engine' ),
			'ML' => __( 'Mali', 'wp-travel-engine' ),
			'MT' => __( 'Malta', 'wp-travel-engine' ),
			'MH' => __( 'Marshall Islands', 'wp-travel-engine' ),
			'MQ' => __( 'Martinique', 'wp-travel-engine' ),
			'MR' => __( 'Mauritania', 'wp-travel-engine' ),
			'MU' => __( 'Mauritius', 'wp-travel-engine' ),
			'YT' => __( 'Mayotte', 'wp-travel-engine' ),
			'MX' => __( 'Mexico', 'wp-travel-engine' ),
			'FM' => __( 'Micronesia', 'wp-travel-engine' ),
			'MD' => __( 'Moldova', 'wp-travel-engine' ),
			'MC' => __( 'Monaco', 'wp-travel-engine' ),
			'MN' => __( 'Mongolia', 'wp-travel-engine' ),
			'ME' => __( 'Montenegro', 'wp-travel-engine' ),
			'MS' => __( 'Montserrat', 'wp-travel-engine' ),
			'MA' => __( 'Morocco', 'wp-travel-engine' ),
			'MZ' => __( 'Mozambique', 'wp-travel-engine' ),
			'MM' => __( 'Myanmar', 'wp-travel-engine' ),
			'NA' => __( 'Namibia', 'wp-travel-engine' ),
			'NR' => __( 'Nauru', 'wp-travel-engine' ),
			'NP' => __( 'Nepal', 'wp-travel-engine' ),
			'NL' => __( 'Netherlands', 'wp-travel-engine' ),
			'NC' => __( 'New Caledonia', 'wp-travel-engine' ),
			'NZ' => __( 'New Zealand', 'wp-travel-engine' ),
			'NI' => __( 'Nicaragua', 'wp-travel-engine' ),
			'NE' => __( 'Niger', 'wp-travel-engine' ),
			'NG' => __( 'Nigeria', 'wp-travel-engine' ),
			'NU' => __( 'Niue', 'wp-travel-engine' ),
			'NF' => __( 'Norfolk Island', 'wp-travel-engine' ),
			'MP' => __( 'Northern Mariana Islands', 'wp-travel-engine' ),
			'KP' => __( 'North Korea', 'wp-travel-engine' ),
			'NO' => __( 'Norway', 'wp-travel-engine' ),
			'OM' => __( 'Oman', 'wp-travel-engine' ),
			'PK' => __( 'Pakistan', 'wp-travel-engine' ),
			'PS' => __( 'Palestinian Territory', 'wp-travel-engine' ),
			'PA' => __( 'Panama', 'wp-travel-engine' ),
			'PG' => __( 'Papua New Guinea', 'wp-travel-engine' ),
			'PY' => __( 'Paraguay', 'wp-travel-engine' ),
			'PE' => __( 'Peru', 'wp-travel-engine' ),
			'PH' => __( 'Philippines', 'wp-travel-engine' ),
			'PN' => __( 'Pitcairn', 'wp-travel-engine' ),
			'PL' => __( 'Poland', 'wp-travel-engine' ),
			'PT' => __( 'Portugal', 'wp-travel-engine' ),
			'PR' => __( 'Puerto Rico', 'wp-travel-engine' ),
			'QA' => __( 'Qatar', 'wp-travel-engine' ),
			'RE' => __( 'Reunion', 'wp-travel-engine' ),
			'RO' => __( 'Romania', 'wp-travel-engine' ),
			'RU' => __( 'Russia', 'wp-travel-engine' ),
			'RW' => __( 'Rwanda', 'wp-travel-engine' ),
			'BL' => __( 'Saint Barth&eacute;lemy', 'wp-travel-engine' ),
			'SH' => __( 'Saint Helena', 'wp-travel-engine' ),
			'KN' => __( 'Saint Kitts and Nevis', 'wp-travel-engine' ),
			'LC' => __( 'Saint Lucia', 'wp-travel-engine' ),
			'MF' => __( 'Saint Martin (French part)', 'wp-travel-engine' ),
			'SX' => __( 'Saint Martin (Dutch part)', 'wp-travel-engine' ),
			'PM' => __( 'Saint Pierre and Miquelon', 'wp-travel-engine' ),
			'VC' => __( 'Saint Vincent and the Grenadines', 'wp-travel-engine' ),
			'SM' => __( 'San Marino', 'wp-travel-engine' ),
			'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'wp-travel-engine' ),
			'SA' => __( 'Saudi Arabia', 'wp-travel-engine' ),
			'SN' => __( 'Senegal', 'wp-travel-engine' ),
			'RS' => __( 'Serbia', 'wp-travel-engine' ),
			'SC' => __( 'Seychelles', 'wp-travel-engine' ),
			'SL' => __( 'Sierra Leone', 'wp-travel-engine' ),
			'SG' => __( 'Singapore', 'wp-travel-engine' ),
			'SK' => __( 'Slovakia', 'wp-travel-engine' ),
			'SI' => __( 'Slovenia', 'wp-travel-engine' ),
			'SB' => __( 'Solomon Islands', 'wp-travel-engine' ),
			'SO' => __( 'Somalia', 'wp-travel-engine' ),
			'ZA' => __( 'South Africa', 'wp-travel-engine' ),
			'GS' => __( 'South Georgia/Sandwich Islands', 'wp-travel-engine' ),
			'KR' => __( 'South Korea', 'wp-travel-engine' ),
			'SS' => __( 'South Sudan', 'wp-travel-engine' ),
			'ES' => __( 'Spain', 'wp-travel-engine' ),
			'LK' => __( 'Sri Lanka', 'wp-travel-engine' ),
			'SD' => __( 'Sudan', 'wp-travel-engine' ),
			'SR' => __( 'Suriname', 'wp-travel-engine' ),
			'SJ' => __( 'Svalbard and Jan Mayen', 'wp-travel-engine' ),
			'SZ' => __( 'Swaziland', 'wp-travel-engine' ),
			'SE' => __( 'Sweden', 'wp-travel-engine' ),
			'CH' => __( 'Switzerland', 'wp-travel-engine' ),
			'SY' => __( 'Syria', 'wp-travel-engine' ),
			'TW' => __( 'Taiwan', 'wp-travel-engine' ),
			'TJ' => __( 'Tajikistan', 'wp-travel-engine' ),
			'TZ' => __( 'Tanzania', 'wp-travel-engine' ),
			'TH' => __( 'Thailand', 'wp-travel-engine' ),
			'TL' => __( 'Timor-Leste', 'wp-travel-engine' ),
			'TG' => __( 'Togo', 'wp-travel-engine' ),
			'TK' => __( 'Tokelau', 'wp-travel-engine' ),
			'TO' => __( 'Tonga', 'wp-travel-engine' ),
			'TT' => __( 'Trinidad and Tobago', 'wp-travel-engine' ),
			'TN' => __( 'Tunisia', 'wp-travel-engine' ),
			'TR' => __( 'Turkey', 'wp-travel-engine' ),
			'TM' => __( 'Turkmenistan', 'wp-travel-engine' ),
			'TC' => __( 'Turks and Caicos Islands', 'wp-travel-engine' ),
			'TV' => __( 'Tuvalu', 'wp-travel-engine' ),
			'UG' => __( 'Uganda', 'wp-travel-engine' ),
			'UA' => __( 'Ukraine', 'wp-travel-engine' ),
			'AE' => __( 'United Arab Emirates', 'wp-travel-engine' ),
			'GB' => __( 'United Kingdom (UK)', 'wp-travel-engine' ),
			'US' => __( 'United States (US)', 'wp-travel-engine' ),
			'UM' => __( 'United States (US) Minor Outlying Islands', 'wp-travel-engine' ),
			'UY' => __( 'Uruguay', 'wp-travel-engine' ),
			'UZ' => __( 'Uzbekistan', 'wp-travel-engine' ),
			'VU' => __( 'Vanuatu', 'wp-travel-engine' ),
			'VA' => __( 'Vatican', 'wp-travel-engine' ),
			'VE' => __( 'Venezuela', 'wp-travel-engine' ),
			'VN' => __( 'Vietnam', 'wp-travel-engine' ),
			'VG' => __( 'Virgin Islands (British)', 'wp-travel-engine' ),
			'VI' => __( 'Virgin Islands (US)', 'wp-travel-engine' ),
			'WF' => __( 'Wallis and Futuna', 'wp-travel-engine' ),
			'EH' => __( 'Western Sahara', 'wp-travel-engine' ),
			'WS' => __( 'Samoa', 'wp-travel-engine' ),
			'YE' => __( 'Yemen', 'wp-travel-engine' ),
			'ZM' => __( 'Zambia', 'wp-travel-engine' ),
			'ZW' => __( 'Zimbabwe', 'wp-travel-engine' ),
		);
		$options = apply_filters( 'wp_travel_engine_country_options', $options );
		return $options;
	}

	function order_form_billing_options() {
		$options = array(
			'fname'   => array(
				'label'       => __( 'First Name', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your First Name', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'lname'   => array(
				'label'       => __( 'Last Name', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your Last Name', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'email'   => array(
				'label'       => __( 'Email', 'wp-travel-engine' ),
				'type'        => 'email',
				'placeholder' => __( 'Your Valid Email', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'address' => array(
				'label'       => __( 'Address', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your Address', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'city'    => array(
				'label'       => __( 'City', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your City', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'country' => array(
				'label'    => __( 'Country', 'wp-travel-engine' ),
				'type'     => 'country-list',
				'required' => '1',
			),
		);
		$options = apply_filters( 'wp_travel_engine_order_form_billing_options', $options );
		return $options;
	}

	function order_form_personal_options() {
		$options = array(
			'title'    => array(
				'label'    => __( 'Title', 'wp-travel-engine' ),
				'type'     => 'select',
				'required' => '1',
				'options'  => array(
					'Mr'    => __( 'Mr', 'wp-travel-engine' ),
					'Mrs'   => __( 'Mrs', 'wp-travel-engine' ),
					'Ms'    => __( 'Ms', 'wp-travel-engine' ),
					'Miss'  => __( 'Miss', 'wp-travel-engine' ),
					'Other' => __( 'Other', 'wp-travel-engine' ),
				),
			),
			'fname'    => array(
				'label'       => __( 'First Name', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your First Name', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'lname'    => array(
				'label'       => __( 'Last Name', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your Last Name', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'passport' => array(
				'label'       => __( 'Passport Number', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your Valid Passport Number', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'email'    => array(
				'label'       => __( 'Email', 'wp-travel-engine' ),
				'type'        => 'email',
				'placeholder' => __( 'Your Valid Email', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'address'  => array(
				'label'       => __( 'Address', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your Address', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'city'     => array(
				'label'       => __( 'City', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your City', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'country'  => array(
				'label'    => __( 'Country', 'wp-travel-engine' ),
				'type'     => 'country-list',
				'required' => '1',
			),
			'postcode' => array(
				'label'    => __( 'Post-code', 'wp-travel-engine' ),
				'type'     => 'number',
				'required' => '1',
			),
			'phone'    => array(
				'label'    => __( 'Phone', 'wp-travel-engine' ),
				'type'     => 'tel',
				'required' => '1',
			),
			'dob'      => array(
				'label'    => __( 'Date of Birth', 'wp-travel-engine' ),
				'type'     => 'text',
				'required' => '1',
			),
			'special'  => array(
				'label'    => __( 'Special Requirements', 'wp-travel-engine' ),
				'type'     => 'textarea',
				'required' => '1',
			),
		);
		$options = apply_filters( 'wp_travel_engine_order_form_personal_options', $options );
		return $options;
	}

	function wpte_enquiry_options() {
		$options = array(

			'country'  => array(
				'label'       => __( 'Country', 'wp-travel-engine' ),
				'type'        => 'country-list',
				'placeholder' => __( 'Choose a country&hellip;', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'contact'  => array(
				'label'       => __( 'Contact No.', 'wp-travel-engine' ),
				'type'        => 'tel',
				'placeholder' => __( 'Enter Your Contact Number', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'adults'   => array(
				'label'       => __( 'Adults', 'wp-travel-engine' ),
				'type'        => 'number',
				'placeholder' => __( 'Enter Number of Adults', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'children' => array(
				'label'       => __( 'Children', 'wp-travel-engine' ),
				'type'        => 'number',
				'placeholder' => __( 'Enter Number of Children', 'wp-travel-engine' ),
				'required'    => '0',
			),
			'message'  => array(
				'label'       => __( 'Your Message', 'wp-travel-engine' ),
				'type'        => 'textarea',
				'placeholder' => __( 'Enter Your message', 'wp-travel-engine' ),
				'required'    => '1',
			),
		);
		$options = apply_filters( 'wp_travel_engine_inquiry_form_options', $options );
		return $options;
	}


	function order_form_relation_options() {
		$options = array(
			'title'    => array(
				'label'    => __( 'Title', 'wp-travel-engine' ),
				'type'     => 'select',
				'required' => '1',
				'options'  => array(
					'Mr'    => __( 'Mr', 'wp-travel-engine' ),
					'Mrs'   => __( 'Mrs', 'wp-travel-engine' ),
					'Ms'    => __( 'Ms', 'wp-travel-engine' ),
					'Miss'  => __( 'Miss', 'wp-travel-engine' ),
					'Other' => __( 'Other', 'wp-travel-engine' ),
				),
			),
			'fname'    => array(
				'label'       => __( 'First Name', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your First Name', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'lname'    => array(
				'label'       => __( 'Last Name', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your Last Name', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'phone'    => array(
				'label'    => __( 'Phone', 'wp-travel-engine' ),
				'type'     => 'tel',
				'required' => '1',
			),
			'relation' => array(
				'label'    => __( 'Relationship', 'wp-travel-engine' ),
				'type'     => 'text',
				'required' => '1',
			),
		);
		$options = apply_filters( 'wp_travel_engine_order_form_relation_options', $options );
		return $options;
	}

	/**
	 * Get gender options.
	 *
	 * @param string $options (default: '')
	 * @return string
	 */
	function gender_options() {
		 $options = array(
			 'male'   => __( 'male', 'wp-travel-engine' ),
			 'female' => __( 'female', 'wp-travel-engine' ),
			 'other'  => __( 'other', 'wp-travel-engine' ),
		 );
		 $options = apply_filters( 'wp_travel_engine_gender_options', $options );
		 return $options;
	}

	function wp_mail_from() {
		$current_site = get_option( 'blogname' );
		return 'wordpress@' . $current_site;
	}

	/**
	 * Sanitize a multidimensional array
	 *
	 * @uses htmlspecialchars
	 *
	 * @param (array)
	 * @return (array) the sanitized array
	 */
	function wte_sanitize_array( $data = array() ) {
		if ( ! is_array( $data ) || ! count( $data ) ) {
			return array();
		}
		foreach ( $data as $k => $v ) {
			if ( ! is_array( $v ) && ! is_object( $v ) ) {
				$data[ $k ] = htmlspecialchars( trim( $v ) );
			}
			if ( is_array( $v ) ) {
				$data[ $k ] = \wte_functions()->wte_sanitize_array( $v );
			}
		}
		return $data;
	}

	function recursive_html_entity_decode( $data = array() ) {
		if ( ! is_array( $data ) || ! count( $data ) ) {
			return array();
		}
		foreach ( $data as $key => &$value ) {
			if ( is_array( $value ) ) {
				$value = \wte_functions()->recursive_html_entity_decode( $value );
			} else {
				$value = html_entity_decode( $value );
			}
		}
		return $data;
	}

	// Get country name from codes
	function Wte_countryCodeToName( $code ) {
		switch ( $code ) {
			case 'AF':
				return 'Afghanistan';
			case 'AX':
				return 'Aland Islands';
			case 'AL':
				return 'Albania';
			case 'DZ':
				return 'Algeria';
			case 'AS':
				return 'American Samoa';
			case 'AD':
				return 'Andorra';
			case 'AO':
				return 'Angola';
			case 'AI':
				return 'Anguilla';
			case 'AQ':
				return 'Antarctica';
			case 'AG':
				return 'Antigua and Barbuda';
			case 'AR':
				return 'Argentina';
			case 'AM':
				return 'Armenia';
			case 'AW':
				return 'Aruba';
			case 'AU':
				return 'Australia';
			case 'AT':
				return 'Austria';
			case 'AZ':
				return 'Azerbaijan';
			case 'BS':
				return 'Bahamas the';
			case 'BH':
				return 'Bahrain';
			case 'BD':
				return 'Bangladesh';
			case 'BB':
				return 'Barbados';
			case 'BY':
				return 'Belarus';
			case 'BE':
				return 'Belgium';
			case 'BZ':
				return 'Belize';
			case 'BJ':
				return 'Benin';
			case 'BM':
				return 'Bermuda';
			case 'BT':
				return 'Bhutan';
			case 'BO':
				return 'Bolivia';
			case 'BA':
				return 'Bosnia and Herzegovina';
			case 'BW':
				return 'Botswana';
			case 'BV':
				return 'Bouvet Island (Bouvetoya)';
			case 'BR':
				return 'Brazil';
			case 'IO':
				return 'British Indian Ocean Territory (Chagos Archipelago)';
			case 'VG':
				return 'British Virgin Islands';
			case 'BN':
				return 'Brunei Darussalam';
			case 'BG':
				return 'Bulgaria';
			case 'BF':
				return 'Burkina Faso';
			case 'BI':
				return 'Burundi';
			case 'KH':
				return 'Cambodia';
			case 'CM':
				return 'Cameroon';
			case 'CA':
				return 'Canada';
			case 'CV':
				return 'Cape Verde';
			case 'KY':
				return 'Cayman Islands';
			case 'CF':
				return 'Central African Republic';
			case 'TD':
				return 'Chad';
			case 'CL':
				return 'Chile';
			case 'CN':
				return 'China';
			case 'CX':
				return 'Christmas Island';
			case 'CC':
				return 'Cocos (Keeling) Islands';
			case 'CO':
				return 'Colombia';
			case 'KM':
				return 'Comoros the';
			case 'CD':
				return 'Congo';
			case 'CG':
				return 'Congo the';
			case 'CK':
				return 'Cook Islands';
			case 'CR':
				return 'Costa Rica';
			case 'CI':
				return 'Cote d\'Ivoire';
			case 'HR':
				return 'Croatia';
			case 'CU':
				return 'Cuba';
			case 'CY':
				return 'Cyprus';
			case 'CZ':
				return 'Czech Republic';
			case 'DK':
				return 'Denmark';
			case 'DJ':
				return 'Djibouti';
			case 'DM':
				return 'Dominica';
			case 'DO':
				return 'Dominican Republic';
			case 'EC':
				return 'Ecuador';
			case 'EG':
				return 'Egypt';
			case 'SV':
				return 'El Salvador';
			case 'GQ':
				return 'Equatorial Guinea';
			case 'ER':
				return 'Eritrea';
			case 'EE':
				return 'Estonia';
			case 'ET':
				return 'Ethiopia';
			case 'FO':
				return 'Faroe Islands';
			case 'FK':
				return 'Falkland Islands (Malvinas)';
			case 'FJ':
				return 'Fiji the Fiji Islands';
			case 'FI':
				return 'Finland';
			case 'FR':
				return 'France, French Republic';
			case 'GF':
				return 'French Guiana';
			case 'PF':
				return 'French Polynesia';
			case 'TF':
				return 'French Southern Territories';
			case 'GA':
				return 'Gabon';
			case 'GM':
				return 'Gambia the';
			case 'GE':
				return 'Georgia';
			case 'DE':
				return 'Germany';
			case 'GH':
				return 'Ghana';
			case 'GI':
				return 'Gibraltar';
			case 'GR':
				return 'Greece';
			case 'GL':
				return 'Greenland';
			case 'GD':
				return 'Grenada';
			case 'GP':
				return 'Guadeloupe';
			case 'GU':
				return 'Guam';
			case 'GT':
				return 'Guatemala';
			case 'GG':
				return 'Guernsey';
			case 'GN':
				return 'Guinea';
			case 'GW':
				return 'Guinea-Bissau';
			case 'GY':
				return 'Guyana';
			case 'HT':
				return 'Haiti';
			case 'HM':
				return 'Heard Island and McDonald Islands';
			case 'VA':
				return 'Holy See (Vatican City State)';
			case 'HN':
				return 'Honduras';
			case 'HK':
				return 'Hong Kong';
			case 'HU':
				return 'Hungary';
			case 'IS':
				return 'Iceland';
			case 'IN':
				return 'India';
			case 'ID':
				return 'Indonesia';
			case 'IR':
				return 'Iran';
			case 'IQ':
				return 'Iraq';
			case 'IE':
				return 'Ireland';
			case 'IM':
				return 'Isle of Man';
			case 'IL':
				return 'Israel';
			case 'IT':
				return 'Italy';
			case 'JM':
				return 'Jamaica';
			case 'JP':
				return 'Japan';
			case 'JE':
				return 'Jersey';
			case 'JO':
				return 'Jordan';
			case 'KZ':
				return 'Kazakhstan';
			case 'KE':
				return 'Kenya';
			case 'KI':
				return 'Kiribati';
			case 'KP':
				return 'Korea';
			case 'KR':
				return 'Korea';
			case 'KW':
				return 'Kuwait';
			case 'KG':
				return 'Kyrgyz Republic';
			case 'LA':
				return 'Lao';
			case 'LV':
				return 'Latvia';
			case 'LB':
				return 'Lebanon';
			case 'LS':
				return 'Lesotho';
			case 'LR':
				return 'Liberia';
			case 'LY':
				return 'Libyan Arab Jamahiriya';
			case 'LI':
				return 'Liechtenstein';
			case 'LT':
				return 'Lithuania';
			case 'LU':
				return 'Luxembourg';
			case 'MO':
				return 'Macao';
			case 'MK':
				return 'Macedonia';
			case 'MG':
				return 'Madagascar';
			case 'MW':
				return 'Malawi';
			case 'MY':
				return 'Malaysia';
			case 'MV':
				return 'Maldives';
			case 'ML':
				return 'Mali';
			case 'MT':
				return 'Malta';
			case 'MH':
				return 'Marshall Islands';
			case 'MQ':
				return 'Martinique';
			case 'MR':
				return 'Mauritania';
			case 'MU':
				return 'Mauritius';
			case 'YT':
				return 'Mayotte';
			case 'MX':
				return 'Mexico';
			case 'FM':
				return 'Micronesia';
			case 'MD':
				return 'Moldova';
			case 'MC':
				return 'Monaco';
			case 'MN':
				return 'Mongolia';
			case 'ME':
				return 'Montenegro';
			case 'MS':
				return 'Montserrat';
			case 'MA':
				return 'Morocco';
			case 'MZ':
				return 'Mozambique';
			case 'MM':
				return 'Myanmar';
			case 'NA':
				return 'Namibia';
			case 'NR':
				return 'Nauru';
			case 'NP':
				return 'Nepal';
			case 'AN':
				return 'Netherlands Antilles';
			case 'NL':
				return 'Netherlands the';
			case 'NC':
				return 'New Caledonia';
			case 'NZ':
				return 'New Zealand';
			case 'NI':
				return 'Nicaragua';
			case 'NE':
				return 'Niger';
			case 'NG':
				return 'Nigeria';
			case 'NU':
				return 'Niue';
			case 'NF':
				return 'Norfolk Island';
			case 'MP':
				return 'Northern Mariana Islands';
			case 'NO':
				return 'Norway';
			case 'OM':
				return 'Oman';
			case 'PK':
				return 'Pakistan';
			case 'PW':
				return 'Palau';
			case 'PS':
				return 'Palestinian Territory';
			case 'PA':
				return 'Panama';
			case 'PG':
				return 'Papua New Guinea';
			case 'PY':
				return 'Paraguay';
			case 'PE':
				return 'Peru';
			case 'PH':
				return 'Philippines';
			case 'PN':
				return 'Pitcairn Islands';
			case 'PL':
				return 'Poland';
			case 'PT':
				return 'Portugal, Portuguese Republic';
			case 'PR':
				return 'Puerto Rico';
			case 'QA':
				return 'Qatar';
			case 'RE':
				return 'Reunion';
			case 'RO':
				return 'Romania';
			case 'RU':
				return 'Russian Federation';
			case 'RW':
				return 'Rwanda';
			case 'BL':
				return 'Saint Barthelemy';
			case 'SH':
				return 'Saint Helena';
			case 'KN':
				return 'Saint Kitts and Nevis';
			case 'LC':
				return 'Saint Lucia';
			case 'MF':
				return 'Saint Martin';
			case 'PM':
				return 'Saint Pierre and Miquelon';
			case 'VC':
				return 'Saint Vincent and the Grenadines';
			case 'WS':
				return 'Samoa';
			case 'SM':
				return 'San Marino';
			case 'ST':
				return 'Sao Tome and Principe';
			case 'SA':
				return 'Saudi Arabia';
			case 'SN':
				return 'Senegal';
			case 'RS':
				return 'Serbia';
			case 'SC':
				return 'Seychelles';
			case 'SL':
				return 'Sierra Leone';
			case 'SG':
				return 'Singapore';
			case 'SK':
				return 'Slovakia (Slovak Republic)';
			case 'SI':
				return 'Slovenia';
			case 'SB':
				return 'Solomon Islands';
			case 'SO':
				return 'Somalia, Somali Republic';
			case 'ZA':
				return 'South Africa';
			case 'GS':
				return 'South Georgia and the South Sandwich Islands';
			case 'ES':
				return 'Spain';
			case 'LK':
				return 'Sri Lanka';
			case 'SD':
				return 'Sudan';
			case 'SR':
				return 'Suriname';
			case 'SJ':
				return 'Svalbard & Jan Mayen Islands';
			case 'SZ':
				return 'Swaziland';
			case 'SE':
				return 'Sweden';
			case 'CH':
				return 'Switzerland, Swiss Confederation';
			case 'SY':
				return 'Syrian Arab Republic';
			case 'TW':
				return 'Taiwan';
			case 'TJ':
				return 'Tajikistan';
			case 'TZ':
				return 'Tanzania';
			case 'TH':
				return 'Thailand';
			case 'TL':
				return 'Timor-Leste';
			case 'TG':
				return 'Togo';
			case 'TK':
				return 'Tokelau';
			case 'TO':
				return 'Tonga';
			case 'TT':
				return 'Trinidad and Tobago';
			case 'TN':
				return 'Tunisia';
			case 'TR':
				return 'Turkey';
			case 'TM':
				return 'Turkmenistan';
			case 'TC':
				return 'Turks and Caicos Islands';
			case 'TV':
				return 'Tuvalu';
			case 'UG':
				return 'Uganda';
			case 'UA':
				return 'Ukraine';
			case 'AE':
				return 'United Arab Emirates';
			case 'GB':
				return 'United Kingdom';
			case 'US':
				return 'United States of America';
			case 'UM':
				return 'United States Minor Outlying Islands';
			case 'VI':
				return 'United States Virgin Islands';
			case 'UY':
				return 'Uruguay, Eastern Republic of';
			case 'UZ':
				return 'Uzbekistan';
			case 'VU':
				return 'Vanuatu';
			case 'VE':
				return 'Venezuela';
			case 'VN':
				return 'Vietnam';
			case 'WF':
				return 'Wallis and Futuna';
			case 'EH':
				return 'Western Sahara';
			case 'YE':
				return 'Yemen';
			case 'ZM':
				return 'Zambia';
			case 'ZW':
				return 'Zimbabwe';
		}
		return false;
	}

	/**
	 * Return template.
	 *
	 * @param  String $template_name Path of template.
	 * @param  array  $args arguments.
	 * @return Mixed
	 */
	public static function get_template( $template_name, $args = array() ) {
		$template_path = 'template-parts/';
		$default_path  = \WP_TRAVEL_ENGINE_BASE_PATH . '/includes/templates/';

		extract( $args );
		// Look templates in theme first.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}
		if ( file_exists( $template ) ) {
			include $template;
		}
		return false;
	}

	/**
	 * Helper function to check if fixed starting dates are valid.
	 */
	public function wte_is_fixed_starting_dates_valid( $WTE_Fixed_Starting_Dates_setting, $sortable_settings ) {

		$today = strtotime( date( 'Y-m-d' ) ) * 1000;
		$i     = 0;

		foreach ( $sortable_settings as $content ) {
			$test_date = strtotime( $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][ $content->id ] ) * 1000;

			if ( $today > $test_date ) {
				$i++;
			}
		}

		return count( $sortable_settings ) !== $i;
	}
}
\wte_functions()->init();

\class_alias( 'WPTravelEngine\Core\Functions', '\Wp_Travel_Engine_Functions' );
