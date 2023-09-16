<?php
/**
 * Blossom Recipe Maker SEO Functions
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/includes
 * @since       1.0.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wp_Travel_Engine_SEO {

	public function init() {

		add_action( 'display_wte_rich_snippet', array( $this, 'wp_travel_engine_json_ld' ) );
	}

	public static function wp_travel_engine_json_ld( $post_id = false ) {

		$schema_values_json = wp_json_encode( self::wp_travel_engine_schema_values( $post_id ) );

		$schema_html      = '<script type="application/ld+json">';
			$schema_html .= $schema_values_json;
		$schema_html     .= '</script>';

		echo $schema_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

	public static function wp_travel_engine_schema_values( $post_id = false ) {

		if ( empty( $post_id ) ) {
			global $post;
			$post_id = $post->ID;
		}
		$post                      = get_post( $post_id );
		$content                   = $post->post_content;
		$content                   = strip_tags( strip_shortcodes( $content ) );
		$obj                       = \wte_functions();
		$wp_travel_engine_settings = get_post_meta( $post_id, 'wp_travel_engine_setting', true );

		$trip_thumbnail = ( has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, 'wp_travel_engine_single_trip_feat_img_size' ) : '' );

		$blog          = get_bloginfo( 'name' );
		$url           = get_bloginfo( 'url' );
		$trip_url      = get_permalink( $post_id );
		$priceCurrency = $obj->trip_currency_code( $post );
		$price         = $obj->trip_price( $post_id );

		$cost = isset( $wp_travel_engine_settings['trip_price'] ) ? $wp_travel_engine_settings['trip_price'] : '';

		$prev_cost = isset( $wp_travel_engine_settings['trip_prev_price'] ) ? $wp_travel_engine_settings['trip_prev_price'] : '';

		// Itinerary Schema
		if ( isset( $wp_travel_engine_settings['itinerary']['itinerary_title'] ) && ! empty( $wp_travel_engine_settings['itinerary']['itinerary_title'] ) ) {
			$max_itinerary = max( array_keys( $wp_travel_engine_settings['itinerary']['itinerary_title'] ) );

			$arr_keys = array_keys( $wp_travel_engine_settings['itinerary']['itinerary_title'] );
			foreach ( $arr_keys as $key => $value ) {
				if ( array_key_exists( $value, $wp_travel_engine_settings['itinerary']['itinerary_title'] ) ) {
					$title = isset( $wp_travel_engine_settings['itinerary']['itinerary_title'][ $value ] ) ? esc_attr( $wp_travel_engine_settings['itinerary']['itinerary_title'][ $value ] ) : '';
					if ( isset( $wp_travel_engine_settings['itinerary']['itinerary_content_inner'][ $value ] ) && $wp_travel_engine_settings['itinerary']['itinerary_content_inner'][ $value ] != '' ) {
						$content_itinerary = $wp_travel_engine_settings['itinerary']['itinerary_content_inner'][ $value ];
					} else {
						$content_itinerary = $wp_travel_engine_settings['itinerary']['itinerary_content'][ $value ];
					}
					$content_itinerary = strip_tags( $content_itinerary );
					$content_itinerary = preg_replace( '/<p\b[^>]*>(.*?)<\/p>/i', '', $content_itinerary );
					$items[]           = array(
						'@type'    => 'ListItem',
						'position' => $value,
						'item'     => array(
							'@type'       => 'TouristAttraction',
							'name'        => $title,
							'description' => $content_itinerary,
						),
					);

				}
			}
			$itinerary[] = array(
				'@type'           => 'ItemList',
				'numberOfItems'   => $max_itinerary,
				'itemListElement' => $items,
			);
		}

		// FAQ Page Schema
		if ( isset( $wp_travel_engine_settings['faq']['title'] ) && $wp_travel_engine_settings['faq']['title'] != '' ) {

			$faqs = array();

			// Conditional check to avoid errors.
			if ( isset( $wp_travel_engine_settings['faq']['faq_title'] ) && is_array( $wp_travel_engine_settings['faq']['faq_title'] ) && ! empty( $wp_travel_engine_settings['faq']['faq_title'] ) ) :

				$maxlen = max( array_keys( $wp_travel_engine_settings['faq']['faq_title'] ) );

				$arr_keys = array_keys( $wp_travel_engine_settings['faq']['faq_title'] );

				foreach ( $arr_keys as $key => $value ) {

					if ( array_key_exists( $value, $wp_travel_engine_settings['faq']['faq_title'] ) ) {

						$question = isset( $wp_travel_engine_settings['faq']['faq_title'][ $value ] ) ? esc_attr( $wp_travel_engine_settings['faq']['faq_title'][ $value ] ) : '';

						$question = strip_tags( $question );
						$question = preg_replace( '/<p\b[^>]*>(.*?)<\/p>/i', '', $question );

						$answer = isset( $wp_travel_engine_settings['faq']['faq_content'][ $value ] ) ? html_entity_decode( $wp_travel_engine_settings['faq']['faq_content'][ $value ] ) : '';

						$answer = strip_tags( $answer );
						$answer = preg_replace( '/<p\b[^>]*>(.*?)<\/p>/i', '', $answer );
						$faqs[] = array(
							'@type'          => 'Question',
							'name'           => $question,
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text'  => $answer,
							),
						);
					}
				}

			endif;

			$faq_schema_array = apply_filters(
				'wp_travel_engine_faq_schema_array',
				array(
					'@context'   => 'http://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => $faqs,
				),
				$post_id,
				$wp_travel_engine_settings
			);

			$faq_schema_values_json = wp_json_encode( $faq_schema_array );

			$schema_html  = '<script type="application/ld+json">';
			$schema_html .= $faq_schema_values_json;
			$schema_html .= '</script>';

			echo $schema_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		}

		$schema_array = apply_filters(
			'wp_travel_engine_schema_array',
			array(
				'@context'    => 'http://schema.org',
				'@type'       => 'Trip',
				'name'        => get_the_title( $post_id ),
				'description' => ( isset( $content ) ? $content : '' ),
				'image'       => $trip_thumbnail,
				'url'         => $trip_url,
				'itinerary'   => ( isset( $itinerary ) ? $itinerary : '' ),
				'provider'    => array(
					'@type' => 'Organization',
					'name'  => $blog,
					'url'   => $url,
				),
				'offers'      => array(
					'@type'     => 'AggregateOffer',
					'highPrice' => ( isset( $prev_cost ) ? $prev_cost : '' ),
					'lowPrice'  => ( isset( $cost ) ? $cost : '' ),
					'offers'    => array(
						'@type'         => 'Offer',
						'name'          => get_the_title( $post_id ),
						'availability'  => 'http://schema.org/InStock',
						'price'         => ( isset( $price ) ? $price : '' ),
						'priceCurrency' => ( isset( $priceCurrency ) ? $priceCurrency : 'USD' ),
						'url'           => $trip_url,
					),
				),

			),
			$post_id,
			$wp_travel_engine_settings
		);

		return $schema_array;

	}

}
$obj = new Wp_Travel_Engine_SEO();
$obj->init();
