<?php
/**
 * Template part for displaying grid posts in  single trip related section
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/content-related-trip.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */
wp_enqueue_script("wte-popper");
wp_enqueue_script("wte-tippyjs");

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;
$is_featured = wte_is_trip_featured( get_the_ID() );
$settings         = wp_travel_engine_get_settings();
$related_new_trip_listing         = isset( $settings['related_display_new_trip_listing'] ) && $settings['related_display_new_trip_listing'] == 'yes';
$set_duration_type = isset( $settings['set_duration_type'] ) && ! empty( $settings['set_duration_type'] ) ? $settings['set_duration_type'] : 'days';
$wp_travel_engine_setting = get_post_meta(get_the_ID(), 'wp_travel_engine_setting', true);
$wpte_trip_images         = get_post_meta(get_the_ID(), 'wpte_gallery_id', true);
?>
<div data-thumbnail="default" class="category-trips-single<?php echo $is_featured ? ' __featured-trip' : ''; echo $related_new_trip_listing ? ' wpte_new-layout' : ''; ?>" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
	<div class="category-trips-single-inner-wrap">
		<figure class="category-trip-fig">
			<?php 
			if ( $related_new_trip_listing && $is_featured  && $show_related_featured_tag ) :?>
			<div class="category-feat-ribbon">
				<span class="category-feat-ribbon-txt"><?php echo esc_html__('Featured', 'wp-travel-engine'); ?></span>
				<span class="cat-feat-shadow"></span>
			</div>
			<?php endif;
			if ( ( !isset( $settings['related_display_new_trip_listing'] ) || $settings['related_display_new_trip_listing'] == 'no' ) && $is_featured ) : ?>
				<div class="category-feat-ribbon">
					<span class="category-feat-ribbon-txt"><?php echo esc_html__('Featured', 'wp-travel-engine'); ?></span>
					<span class="cat-feat-shadow"></span>
				</div>
			<?php endif;

			// Trip thumbnail.
			if ($show_related_trip_carousel && $wpte_trip_images['enable'] == 1 && count( $wpte_trip_images ) > 1 ) {
				wte_get_template('single-trip/gallery.php', $args);
			} else { ?>
				<a href="<?php the_permalink(); ?>">
					<?php
					$size = apply_filters('wp_travel_engine_archive_trip_feat_img_size', 'trip-single-size');
					if (has_post_thumbnail()) :
						the_post_thumbnail( $size, array( 'loading'  => 'lazy' )  );
					endif;?>
				</a>
				<?php
			}

			// Group Discount.
			if ($group_discount) : ?>
				<div class="category-trip-group-avil">
					<span class="pop-trip-grpavil-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="17.492" height="14.72" viewBox="0 0 17.492 14.72">
							<g id="Group_898" data-name="Group 898" transform="translate(-452 -737)">
								<g id="Group_757" data-name="Group 757" transform="translate(12.114)">
									<g id="multiple-users-silhouette" transform="translate(439.886 737)">
										<path id="Path_23387" data-name="Path 23387" d="M10.555,8.875a3.178,3.178,0,0,1,1.479,2.361,2.564,2.564,0,1,0-1.479-2.361ZM8.875,14.127a2.565,2.565,0,1,0-2.566-2.565A2.565,2.565,0,0,0,8.875,14.127Zm1.088.175H7.786A3.289,3.289,0,0,0,4.5,17.587v2.662l.007.042.183.057a14.951,14.951,0,0,0,4.466.72,9.168,9.168,0,0,0,3.9-.732l.171-.087h.018V17.587A3.288,3.288,0,0,0,9.963,14.3Zm4.244-2.648h-2.16a3.162,3.162,0,0,1-.976,2.2,3.9,3.9,0,0,1,2.788,3.735v.82a8.839,8.839,0,0,0,3.443-.723l.171-.087h.018V14.938A3.288,3.288,0,0,0,14.207,11.654Zm-9.834-.175a2.548,2.548,0,0,0,1.364-.4A3.175,3.175,0,0,1,6.931,9.058c0-.048.007-.1.007-.144a2.565,2.565,0,1,0-2.565,2.565Zm2.3,2.377a3.163,3.163,0,0,1-.975-2.19c-.08-.006-.159-.012-.241-.012H3.285A3.288,3.288,0,0,0,0,14.938V17.6l.007.041L.19,17.7a15.4,15.4,0,0,0,3.7.7v-.8A3.9,3.9,0,0,1,6.677,13.856Z" transform="translate(0 -6.348)" fill="#fff" />
									</g>
								</g>
							</g>
						</svg>
					</span>
					<span class="pop-trip-grpavil-txt"><?php echo esc_html(apply_filters('wp_travel_engine_group_discount_available_text', __('Group discount Available', 'wp-travel-engine'))); ?></span>
				</div>
			<?php endif; ?>

			<?php
			if ($show_related_map) :?>
				<div class="trip-map-wrapper">
					<?php echo wp_kses(
						\WP_Travel_Engine_Custom_Shortcodes::wte_show_trip_map_shortcodes_callback(['id' => $post->ID, 'show' => 'iframe|image']),
						[
							'div' => ['class' => [], 'style' => []],
							'img' => ['class' => [], 'src' => [], 'style' => [], 'loading' => []],
							'iframe' => ['src' => [], 'allowfullscreen' => [], 'loading' => [], 'style' => [], 'width' => [], 'height' => []],
						]
					);
					?>
				</div>
				<button data-thumbnail-toggler class="toggle-map">
					<i><svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M6 0C4.4087 0 2.88258 0.632141 1.75736 1.75736C0.632141 2.88258 0 4.4087 0 6C0 10.05 5.2875 14.625 5.5125 14.82C5.64835 14.9362 5.82124 15 6 15C6.17877 15 6.35165 14.9362 6.4875 14.82C6.75 14.625 12 10.05 12 6C12 4.4087 11.3679 2.88258 10.2426 1.75736C9.11742 0.632141 7.5913 0 6 0ZM6 13.2375C4.4025 11.7375 1.5 8.505 1.5 6C1.5 4.80653 1.97411 3.66193 2.81802 2.81802C3.66193 1.97411 4.80653 1.5 6 1.5C7.19347 1.5 8.33807 1.97411 9.18198 2.81802C10.0259 3.66193 10.5 4.80653 10.5 6C10.5 8.505 7.5975 11.745 6 13.2375ZM6 3C5.40666 3 4.82664 3.17595 4.33329 3.50559C3.83994 3.83524 3.45542 4.30377 3.22836 4.85195C3.0013 5.40013 2.94189 6.00333 3.05764 6.58527C3.1734 7.16721 3.45912 7.70176 3.87868 8.12132C4.29824 8.54088 4.83279 8.8266 5.41473 8.94236C5.99667 9.05811 6.59987 8.9987 7.14805 8.77164C7.69623 8.54458 8.16477 8.16006 8.49441 7.66671C8.82405 7.17336 9 6.59334 9 6C9 5.20435 8.68393 4.44129 8.12132 3.87868C7.55871 3.31607 6.79565 3 6 3ZM6 7.5C5.70333 7.5 5.41332 7.41203 5.16665 7.2472C4.91997 7.08238 4.72771 6.84811 4.61418 6.57403C4.50065 6.29994 4.47094 5.99834 4.52882 5.70736C4.5867 5.41639 4.72956 5.14912 4.93934 4.93934C5.14912 4.72956 5.41639 4.5867 5.70737 4.52882C5.99834 4.47094 6.29994 4.50065 6.57403 4.61418C6.84811 4.72771 7.08238 4.91997 7.2472 5.16665C7.41203 5.41332 7.5 5.70333 7.5 6C7.5 6.39782 7.34197 6.77936 7.06066 7.06066C6.77936 7.34196 6.39783 7.5 6 7.5Z" fill="currentColor"></path>
						</svg>
					</i>
				</button>
			<?php endif;?>
		</figure>

		<div class="category-trip-content-wrap">
			<div class="category-trip-prc-title-wrap">
				<h2 class="category-trip-title" itemprop="name">
					<a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h2>
				<?php
				if ( $show_related_wishlist ) {
					$active_class = '';
					$title_attribute = '';
					if( isset( $user_wishlists ) && is_array( $user_wishlists ) ){
						$active_class = in_array( $post->ID, $user_wishlists ) ? ' active' : '';
						$title_attribute = in_array( $post->ID, $user_wishlists ) ? 'Already in wishlist' : 'Add to wishlist';
					}?>
					<span class="wishlist-title"><?php __("Add to wishlist", "wp-travel-engine"); ?></span>
					<a class="wishlist-toggle<?php echo esc_attr( $active_class ); ?>" data-product="<?php echo esc_attr($post->ID); ?>" href="#" title="<?php echo __( $title_attribute, 'wp-travel-engine' ); ?>">
						<svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M10 19L8.55 17.7C6.86667 16.1834 5.475 14.875 4.375 13.775C3.275 12.675 2.4 11.6874 1.75 10.812C1.1 9.93736 0.646 9.13336 0.388 8.40002C0.129333 7.66669 0 6.91669 0 6.15002C0 4.58336 0.525 3.27502 1.575 2.22502C2.625 1.17502 3.93333 0.650024 5.5 0.650024C6.36667 0.650024 7.19167 0.833358 7.975 1.20002C8.75833 1.56669 9.43333 2.08336 10 2.75002C10.5667 2.08336 11.2417 1.56669 12.025 1.20002C12.8083 0.833358 13.6333 0.650024 14.5 0.650024C16.0667 0.650024 17.375 1.17502 18.425 2.22502C19.475 3.27502 20 4.58336 20 6.15002C20 6.91669 19.871 7.66669 19.613 8.40002C19.3543 9.13336 18.9 9.93736 18.25 10.812C17.6 11.6874 16.725 12.675 15.625 13.775C14.525 14.875 13.1333 16.1834 11.45 17.7L10 19Z" fill="#C6C6C6" />
						</svg>
					</a>
				<?php
				}
				
				if ( ! empty( $j ) ) : ?>
					<meta itemprop="position" content="<?php echo esc_attr( $j ); ?>"/>
				<?php endif; ?>
				<?php wte_the_trip_reviews( get_the_ID() ); ?>
			</div>

			<div class="category-trip-detail-wrap">
				<div class="category-trip-prc-wrap">
					<div class="category-trip-desti">
						<?php
						if ( $show_related_trip_tags ) :
							$tag_terms = get_the_terms($post->ID, 'trip_tag');
							if (!empty($tag_terms) && !is_wp_error($tag_terms)) :?>
							<span class="category-trip-wtetags">
								<?php
								foreach( $tag_terms as $tg):
								$tags_description = term_description( $tg->term_id );
								$tags_attribute = $tags_description ? 'data-content="'. $tags_description .'"' : '';
								isset( $tags_attribute ) && $tags_attribute != '' ? printf('<span class="tippy-exist" '.wp_kses_post( $tags_attribute ).'><a rel="tag" target="_self" href="'.get_term_link( $tg ).'">'.esc_attr($tg->name).'</a></span>') : printf('<span><a rel="tag" target="_self" href="'.get_term_link( $tg ).'">'.esc_attr($tg->name).'</a></span>');	
								endforeach;?>
							</span>
							<?php endif;
						endif;
					
						if ( ! empty( $destination ) ) : ?>
							<span class="category-trip-loc">
								<i><svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 0C4.4087 0 2.88258 0.632141 1.75736 1.75736C0.632141 2.88258 0 4.4087 0 6C0 10.05 5.2875 14.625 5.5125 14.82C5.64835 14.9362 5.82124 15 6 15C6.17877 15 6.35165 14.9362 6.4875 14.82C6.75 14.625 12 10.05 12 6C12 4.4087 11.3679 2.88258 10.2426 1.75736C9.11742 0.632141 7.5913 0 6 0ZM6 13.2375C4.4025 11.7375 1.5 8.505 1.5 6C1.5 4.80653 1.97411 3.66193 2.81802 2.81802C3.66193 1.97411 4.80653 1.5 6 1.5C7.19347 1.5 8.33807 1.97411 9.18198 2.81802C10.0259 3.66193 10.5 4.80653 10.5 6C10.5 8.505 7.5975 11.745 6 13.2375ZM6 3C5.40666 3 4.82664 3.17595 4.33329 3.50559C3.83994 3.83524 3.45542 4.30377 3.22836 4.85195C3.0013 5.40013 2.94189 6.00333 3.05764 6.58527C3.1734 7.16721 3.45912 7.70176 3.87868 8.12132C4.29824 8.54088 4.83279 8.8266 5.41473 8.94236C5.99667 9.05811 6.59987 8.9987 7.14805 8.77164C7.69623 8.54458 8.16477 8.16006 8.49441 7.66671C8.82405 7.17336 9 6.59334 9 6C9 5.20435 8.68393 4.44129 8.12132 3.87868C7.55871 3.31607 6.79565 3 6 3ZM6 7.5C5.70333 7.5 5.41332 7.41203 5.16665 7.2472C4.91997 7.08238 4.72771 6.84811 4.61418 6.57403C4.50065 6.29994 4.47094 5.99834 4.52882 5.70736C4.5867 5.41639 4.72956 5.14912 4.93934 4.93934C5.14912 4.72956 5.41639 4.5867 5.70737 4.52882C5.99834 4.47094 6.29994 4.50065 6.57403 4.61418C6.84811 4.72771 7.08238 4.91997 7.2472 5.16665C7.41203 5.41332 7.5 5.70333 7.5 6C7.5 6.39782 7.34197 6.77936 7.06066 7.06066C6.77936 7.34196 6.39783 7.5 6 7.5Z" fill="currentColor" /></svg></i>
								<span><?php echo wp_kses_post( $destination ); ?></span>
							</span>
						<?php endif;
						wte_get_template( 'components/content-trip-card-duration.php', compact( 'trip_duration_unit', 'trip_duration', 'trip_duration_nights', 'set_duration_type' ) );

						if ( ! empty( $pax ) ) : ?>
							<span class="category-trip-pax">
								<i><svg width="18" height="13" viewBox="0 0 18 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.225 6.665C9.62518 6.3186 9.94616 5.89016 10.1662 5.40877C10.3861 4.92737 10.5 4.40428 10.5 3.875C10.5 2.88044 10.1049 1.92661 9.40165 1.22335C8.69839 0.520088 7.74456 0.125 6.75 0.125C5.75544 0.125 4.80161 0.520088 4.09835 1.22335C3.39509 1.92661 3 2.88044 3 3.875C2.99999 4.40428 3.11385 4.92737 3.33384 5.40877C3.55384 5.89016 3.87482 6.3186 4.275 6.665C3.22511 7.14041 2.33435 7.90815 1.70924 8.87641C1.08412 9.84467 0.751104 10.9725 0.75 12.125C0.75 12.3239 0.829018 12.5147 0.96967 12.6553C1.11032 12.796 1.30109 12.875 1.5 12.875C1.69891 12.875 1.88968 12.796 2.03033 12.6553C2.17098 12.5147 2.25 12.3239 2.25 12.125C2.25 10.9315 2.72411 9.78693 3.56802 8.94302C4.41193 8.09911 5.55653 7.625 6.75 7.625C7.94347 7.625 9.08807 8.09911 9.93198 8.94302C10.7759 9.78693 11.25 10.9315 11.25 12.125C11.25 12.3239 11.329 12.5147 11.4697 12.6553C11.6103 12.796 11.8011 12.875 12 12.875C12.1989 12.875 12.3897 12.796 12.5303 12.6553C12.671 12.5147 12.75 12.3239 12.75 12.125C12.7489 10.9725 12.4159 9.84467 11.7908 8.87641C11.1657 7.90815 10.2749 7.14041 9.225 6.665ZM6.75 6.125C6.30499 6.125 5.86998 5.99304 5.49997 5.74581C5.12996 5.49857 4.84157 5.14717 4.67127 4.73604C4.50097 4.3249 4.45642 3.8725 4.54323 3.43605C4.63005 2.99959 4.84434 2.59868 5.15901 2.28401C5.47368 1.96934 5.87459 1.75505 6.31105 1.66823C6.7475 1.58142 7.1999 1.62597 7.61104 1.79627C8.02217 1.96657 8.37357 2.25496 8.62081 2.62497C8.86804 2.99498 9 3.42999 9 3.875C9 4.47174 8.76295 5.04403 8.34099 5.46599C7.91903 5.88795 7.34674 6.125 6.75 6.125ZM14.055 6.365C14.535 5.8245 14.8485 5.15679 14.9579 4.44225C15.0672 3.72772 14.9677 2.99681 14.6713 2.3375C14.375 1.67819 13.8943 1.1186 13.2874 0.726067C12.6804 0.333538 11.9729 0.124807 11.25 0.125C11.0511 0.125 10.8603 0.204018 10.7197 0.34467C10.579 0.485322 10.5 0.676088 10.5 0.875C10.5 1.07391 10.579 1.26468 10.7197 1.40533C10.8603 1.54598 11.0511 1.625 11.25 1.625C11.8467 1.625 12.419 1.86205 12.841 2.28401C13.2629 2.70597 13.5 3.27826 13.5 3.875C13.4989 4.26893 13.3945 4.65568 13.197 4.99657C12.9996 5.33745 12.7162 5.62054 12.375 5.8175C12.2638 5.88164 12.1709 5.97325 12.1053 6.08356C12.0396 6.19386 12.0034 6.31918 12 6.4475C11.9969 6.57482 12.0262 6.70085 12.0852 6.81369C12.1443 6.92654 12.2311 7.02249 12.3375 7.0925L12.63 7.2875L12.7275 7.34C13.6315 7.76879 14.3942 8.44699 14.9257 9.29474C15.4572 10.1425 15.7354 11.1245 15.7275 12.125C15.7275 12.3239 15.8065 12.5147 15.9472 12.6553C16.0878 12.796 16.2786 12.875 16.4775 12.875C16.6764 12.875 16.8672 12.796 17.0078 12.6553C17.1485 12.5147 17.2275 12.3239 17.2275 12.125C17.2336 10.9741 16.9454 9.84069 16.3901 8.83255C15.8348 7.8244 15.031 6.97499 14.055 6.365Z" fill="currentColor"/></svg></i>
								<span><?php printf( __( "%s People", 'wp-travel-engine' ), implode( '-', $pax ) ); ?></span>
							</span>
						<?php endif; ?>
						<?php
						if ($show_related_difficulty_tax) :
							$taxonomy = 'difficulty';
							$termss = get_the_terms($post->ID, 'difficulty');
							if (!empty($termss)) {
								foreach ($termss as $term) { ?>
									<span class="category-trip-difficulty">
										<?php
										if (isset($term->term_id)) {
											$term_id = $term->term_id;
											$difficulty_level = get_option('difficulty_level_by_terms', array());
											$terms = get_terms(array(
												'taxonomy' => 'difficulty',
												'hide_empty' => false
											));
											$difficulty_levels = '';
											foreach ($difficulty_level as $level) {
												if ($term_id == $level['term_id']) :
													$difficulty_levels = sprintf(__('<span>(%1$s/%2$d)</span>', 'wp-travel-engine'), $level['level'], count($terms)); ?>
													<?php
												endif;
											}
											$term_thumbnail = (int) get_term_meta($term_id, 'category-image-id', true);
											if (isset($term_thumbnail) &&  $term_thumbnail != 0) {?>
												<i>
													<?php
													$term_thumbnail && print(\wp_get_attachment_image(
														$term_thumbnail,
														array('16', '16'),
														false,
														array('itemprop' => 'image')
													));
													?>
												</i>
											<?php
											} else { ?>
												<i><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M3.33333 13.3334V9.33335H5.33333V13.3334H3.33333ZM7.33333 13.3334V6.00002H9.33333V13.3334H7.33333ZM11.3333 13.3334V2.66669H13.3333V13.3334H11.3333Z" fill="currentColor" />
													</svg>
												</i>
											<?php }
										}
										?>
										<?php
										$difficulty_term_description = term_description( $term->term_id, 'difficulty' );
										$difficulty_attribute = $difficulty_term_description ? 'data-content="'.  $difficulty_term_description .'"' : '';
										isset( $difficulty_attribute ) && $difficulty_attribute != '' ? printf('<span class="wte-difficulty-content tippy-exist" '.wp_kses_post( $difficulty_attribute ).'><a rel="difficulty" target="_self" href="'.get_term_link( $term ).'">'.esc_attr($term->name).' '.wp_kses_post($difficulty_levels).'</a></span>') : printf('<span class="wte-difficulty-content"><a rel="difficulty" target="_self" href="'.get_term_link( $term ).'">'.esc_attr($term->name).' '.wp_kses_post($difficulty_levels).'</a></span>');
										?>
									</span>
								<?php }
							}
						endif; ?>
						<?php
						if ( $related_new_trip_listing && $show_excerpt && $show_related_date_layout ) :
							?>
							<div class="category-trip-desc">
								<?php wptravelengine_the_trip_excerpt(); ?>
							</div>
						<?php endif;?>
					</div>
					<?php if ( ! empty( $display_price ) || $show_related_date_layout ) : ?>
						<div class="category-trip-budget">
								<?php if ( $discount_percent ) : ?>
									<div class="category-disc-feat-wrap">
										<div class="category-trip-discount">
											<span class="discount-offer">
												<span><?php echo sprintf( __( '%1$s%% ', 'wp-travel-engine' ), (float) $discount_percent ); ?></span>
											<?php esc_html_e( 'Off', 'wp-travel-engine' ); ?></span>
										</div>
									</div>
								<?php endif;
								if ( ! empty( $display_price ) ):  ?>
									<span class="price-holder">
										<span class="actual-price"><?php echo wte_esc_price( wte_get_formated_price( $display_price ) ); ?></span>
										<?php if ( $on_sale ) : ?>
										<span class="striked-price"><?php echo wte_esc_price( wte_get_formated_price( $trip_price ) ); ?></span>
										<?php endif; ?>
									</span>
								<?php endif;
								$fsds = apply_filters('trip_card_fixed_departure_dates', get_the_ID());
								if ($show_related_date_layout && $fsds && is_array($fsds)) { ?>
									<div class="category-trip-dates">
										<span class="trip-dates-title"><?php echo esc_html__('Next Departure', 'wp-travel-engine'); ?></span>
										<?php
										$settings = get_option('wp_travel_engine_settings', true);

										$list_count = isset($settings['trip_dates']['number']) ? (int) $settings['trip_dates']['number'] : 3;
										$icon       = '<i><svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61"><g transform="translate(283.072 34.13)"><path  d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/><path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/></g></svg></i>';
										$i          = 0;
										foreach ($fsds as $fsd) {
											if (--$list_count < 0) {
												break;
											}
											if ($i <= 4) {?>
												<span class="category-trip-start-date">
													<span>
														<?php
														printf('%1$s', wte_esc_price(wte_get_new_formated_date($fsd['start_date'])));
														?>
													</span>
												</span>
											<?php }
											$i++;
										} ?>
									</div>
								<?php
								}
								if( $show_related_date_layout && ( empty( $fsds ) || is_numeric( $fsds) ) ){
									$dates= date_create("M j");
									$following_dates = array(
										1 => date('Y-m-d', strtotime(' + 1 day')),
										2 => date('Y-m-d', strtotime(' + 2 day')),
										3 => date('Y-m-d', strtotime(' + 3 day')),
									);
									$j = 0;
									?>
									<div class="category-trip-dates">
										<span class="trip-dates-title"><?php echo esc_html__('Next Departure', 'wp-travel-engine'); ?></span>
										<?php
											foreach ($following_dates as $dates) {
												if ($j <= 4) {?>
													<span class="category-trip-start-date">
														<span>
															<?php
															printf('%1$s', wte_esc_price(wte_get_new_formated_date($dates)));
															?>
														</span>
													</span>
												<?php }
												$j++;
											}?>
									</div>
									<?php
								}
							?>
						</div>
					<?php endif;?>
				</div>
				<?php
				if ( ( isset( $settings['related_display_new_trip_listing'] ) && 'yes' !== $settings['related_display_new_trip_listing'] && $show_excerpt ) || !$show_related_date_layout) :
					?>
					<div class="category-trip-desc">
						<?php wptravelengine_the_trip_excerpt(); ?>
					</div>
				<?php endif; ?>
				<div class="wpte_trip-details-btn-wrap">
					<a href="<?php the_permalink(); ?>" class="button category-trip-viewmre-btn"><?php echo esc_html( apply_filters( 'wp_travel_engine_view_detail_txt', __( 'View Details', 'wp-travel-engine' ) ) ); ?></a>
				</div>
			</div>

			<?php
			$fsds = apply_filters( 'trip_card_fixed_departure_dates', get_the_ID() );
			$new_related_date_layout = isset( $settings['related_display_new_trip_listing'] ) &&  ( ( $settings['related_display_new_trip_listing'] == 'yes' && $show_related_available_months ) || ( $settings['related_display_new_trip_listing'] == 'no' && !$show_related_available_months ) );
			if( ( !isset( $settings['related_display_new_trip_listing'] ) || ( $new_related_date_layout ) ) ):
				echo '<div class="category-trip-aval-time">';
				if ( false !== $fsds ) {
					if ( $fsds == get_the_ID() || empty( $fsds ) ) {
						echo '<span class="category-available-trip-text"> ' . __('Available through out the year:', 'wp-travel-engine') . '</span>';
						?>
						<div class="category-trip-avl-tip-inner-wrap<?php echo (!$show_related_available_months) ? '' : ' new-layout'; ?>">
						<?php if ($show_related_available_months):?>	
							<i><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M10.95 18.35L7.4 14.8L8.85 13.35L10.95 15.45L15.15 11.25L16.6 12.7L10.95 18.35ZM5 22C4.45 22 3.979 21.8043 3.587 21.413C3.19567 21.021 3 20.55 3 20V6C3 5.45 3.19567 4.97933 3.587 4.588C3.979 4.196 4.45 4 5 4H6V2H8V4H16V2H18V4H19C19.55 4 20.021 4.196 20.413 4.588C20.8043 4.97933 21 5.45 21 6V20C21 20.55 20.8043 21.021 20.413 21.413C20.021 21.8043 19.55 22 19 22H5ZM5 20H19V10H5V20Z" fill="currentColor" />
								</svg>
							</i>
						<?php endif;
						echo '<ul class="category-available-months">';
							foreach ( range( 1, 12 ) as $month_number ) :
								echo '<li>' . date_i18n( 'M', strtotime( "2021-{$month_number}-01" ) ) . '</li>';
							endforeach;
						echo '</ul></div>';
					} elseif ( is_array( $fsds ) && count( $fsds ) > 0 ) {
						// do_action( 'trip_card_fixed_departure_dates_content', $fsds, get_the_ID(), $dates_layout );
						switch ($dates_layout) {
							case 'months_list':
								$available_months = array_map(
									function ($fsd) {
										return date_i18n('n', strtotime($fsd['start_date']));
									},
									$fsds
								);
								$available_dates_in_month = array();
								$available_dates_in_month = array_count_values($available_months);
								$available_months = array_flip($available_months);
								if (empty($available_months)) {
									echo '<ul class="category-available-months">';
									foreach (range(1, 12) as $month_number) :
										echo '<li>' . date_i18n('n-M', strtotime("2021-{$month_number}-01")) . '</li>';
									endforeach;
									echo '</ul>';
									break;
								}
		
								$availability_txt     = !empty($available_months) && is_array($available_months) ? __('Available in the following months:', 'wp-travel-engine') : __('Available through out the year:', 'wp-travel-engine');
								$available_throughout = apply_filters('wte_available_throughout_txt', $availability_txt);
								?>
									<div class="category-trip-avl-tip-inner-wrap<?php echo (!$show_related_available_months) ? '' : ' new-layout'; ?>">
										<?php
											echo '<span class="category-available-trip-text"> ' . esc_html($available_throughout) . '</span>';
										if($show_related_available_months):?>
										<i><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M10.95 18.35L7.4 14.8L8.85 13.35L10.95 15.45L15.15 11.25L16.6 12.7L10.95 18.35ZM5 22C4.45 22 3.979 21.8043 3.587 21.413C3.19567 21.021 3 20.55 3 20V6C3 5.45 3.19567 4.97933 3.587 4.588C3.979 4.196 4.45 4 5 4H6V2H8V4H16V2H18V4H19C19.55 4 20.021 4.196 20.413 4.588C20.8043 4.97933 21 5.45 21 6V20C21 20.55 20.8043 21.021 20.413 21.413C20.021 21.8043 19.55 22 19 22H5ZM5 20H19V10H5V20Z" fill="currentColor" />
											</svg>
										</i>
										<?php endif;
										$months_list = '';
										$settings = get_option('wp_travel_engine_settings', true);
										$list_count = isset($settings['trip_dates']['number']) ? (int) $settings['trip_dates']['number'] : 3;
										echo '<ul class="category-available-months">';
										foreach (range(1, 12) as $month_number) {
											if (isset($available_months[$month_number]) && $show_related_available_dates) {
												$month_value =  date_i18n('m', strtotime("2021-{$month_number}-01"));
												foreach ($available_dates_in_month as $months => $value) {
													if ($month_value == $months) {
														$dates_available = $value;
														$dates_attribute = $dates_available ? 'data-content="' . $value . ' ' . _n('date', 'dates', $dates_available, 'wp-travel-engine') . ' available"' : '';
													}
												}
												$classname = 'wte-dates-available';
												$classname = isset( $dates_attribute ) && $dates_attribute != '' ? $classname . ' tippy-exist' : $classname; 
											} else {
												$dates_attribute = '';
												$classname = '';
											}
											isset($available_months[$month_number]) ? printf('<li class="'.$classname.'"' . wp_kses_post($dates_attribute) . '><a href="%1$s">%2$s</a></li>', esc_url(get_the_permalink()) . '?month=' . esc_html($available_months[$month_number]) . '#wte-fixed-departure-dates', date_i18n('M', strtotime("2021-{$month_number}-01"))) : printf('<li><a href="#" class="disabled">%1$s</a></li>', date_i18n('M', strtotime("2021-{$month_number}-01")));
										}
										echo '</ul>';
										?>
									</div>
							<?php
							break;
							case 'dates_list':
								$settings = get_option('wp_travel_engine_settings', true);
		
								$list_count = isset($settings['trip_dates']['number']) ? (int) $settings['trip_dates']['number'] : 3;
								$icon       = '<i><svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61"><g transform="translate(283.072 34.13)"><path  d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/><path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/></g></svg></i>';
								echo '<div class="next-trip-info">';
								printf('<div class="fsd-title">%1$s</div>', esc_html__('Next Departure', 'wp-travel-engine'));
								echo '<ul class="next-departure-list">';
								foreach ($fsds as $fsd) {
									if (--$list_count < 0) {
										break;
									}
									printf('<li><span class="left">%1$s %2$s</span><span class="right">%3$s</span></li>', $icon , wte_esc_price(wte_get_formated_date($fsd['start_date'])), sprintf(__('%s Available', 'wp-travel-engine'), (float) $fsd['seats_left']));
								}
								echo '</ul>';
								echo '</div>';
							break;
							default:
							break;
						}
					}
				}
				?>
				</div>
			<?php endif;?>
		</div>
	</div>
</div>
