<?php
/**
 * Template part for displaying featured trip widget content
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/widgets/content-widget-feat-trip.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$is_featured_widget = true;
?>
<div class="category-trips-widget">
	<div class="category-trips-widget-inner-wrap">

		<figure class="category-trip-fig">
			<a href="<?php the_permalink(); ?>">
				<?php
				$size = apply_filters( 'wp_travel_engine_archive_trip_feat_img_size', 'destination-thumb-trip-size' );
				if ( has_post_thumbnail() ) :
					the_post_thumbnail( $size );
				else :
					wte_get_fallback_svg( $size );
				endif;
				?>
			</a>

			<?php if ( $group_discount ) : ?>
			<div class="category-trip-group-avil">
				<span class="pop-trip-grpavil-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="17.492" height="14.72" viewBox="0 0 17.492 14.72">
						<g id="Group_898" data-name="Group 898" transform="translate(-452 -737)">
							<g id="Group_757" data-name="Group 757" transform="translate(12.114)">
								<g id="multiple-users-silhouette" transform="translate(439.886 737)">
									<path id="Path_23387" data-name="Path 23387" d="M10.555,8.875a3.178,3.178,0,0,1,1.479,2.361,2.564,2.564,0,1,0-1.479-2.361ZM8.875,14.127a2.565,2.565,0,1,0-2.566-2.565A2.565,2.565,0,0,0,8.875,14.127Zm1.088.175H7.786A3.289,3.289,0,0,0,4.5,17.587v2.662l.007.042.183.057a14.951,14.951,0,0,0,4.466.72,9.168,9.168,0,0,0,3.9-.732l.171-.087h.018V17.587A3.288,3.288,0,0,0,9.963,14.3Zm4.244-2.648h-2.16a3.162,3.162,0,0,1-.976,2.2,3.9,3.9,0,0,1,2.788,3.735v.82a8.839,8.839,0,0,0,3.443-.723l.171-.087h.018V14.938A3.288,3.288,0,0,0,14.207,11.654Zm-9.834-.175a2.548,2.548,0,0,0,1.364-.4A3.175,3.175,0,0,1,6.931,9.058c0-.048.007-.1.007-.144a2.565,2.565,0,1,0-2.565,2.565Zm2.3,2.377a3.163,3.163,0,0,1-.975-2.19c-.08-.006-.159-.012-.241-.012H3.285A3.288,3.288,0,0,0,0,14.938V17.6l.007.041L.19,17.7a15.4,15.4,0,0,0,3.7.7v-.8A3.9,3.9,0,0,1,6.677,13.856Z" transform="translate(0 -6.348)" fill="#fff"/>
								</g>
							</g>
						</g>
					</svg>
				</span>
				<span class="pop-trip-grpavil-txt"><?php echo esc_html( apply_filters( 'wp_travel_engine_group_discount_available_text', __( 'Group discount Available', 'wp-travel-engine' ) ) ); ?></span>
			</div>
			<?php endif; ?>

			<?php if ( ! empty( $display_price ) ) : ?>
			<div class="category-trip-budget">
				<span class="price-holder">
					<?php if ( $on_sale ) : ?>
					<span class="striked-price"><?php echo esc_html( $currency ) . esc_html( $trip_price ); ?></span>
					<?php endif; ?>
					<span class="actual-price"><?php echo esc_html( $currency ) . esc_html( $display_price ); ?></span>
				</span>
			</div>
			<?php endif; ?>
		</figure>

		<div class="category-trip-detail-wrap">
			<div class="category-trip-prc-title-wrap">
				<?php if ( $discount_percent ) : ?>
				<div class="category-disc-feat-wrap">
					<div class="category-trip-discount">
						<span class="discount-offer">
							<span><?php echo sprintf( __( '%1$s%% ', 'wp-travel-engine' ), (int) $discount_percent ); ?></span>
						<?php esc_html_e( 'Off', 'wp-travel-engine' ); ?></span>
					</div>
				</div>
				<?php endif; ?>

				<h2 class="category-trip-title" itemprop="name">
					<a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h2>
			</div>

			<div class="category-trip-desti">
				<?php if ( ! empty( $destination ) ) : ?>
				<span class="category-trip-loc">
					<i>
						<svg xmlns="http://www.w3.org/2000/svg" width="11.213" height="15.81" viewBox="0 0 11.213 15.81">
							<path id="Path_23393" data-name="Path 23393" d="M5.607,223.81c1.924-2.5,5.607-7.787,5.607-10.2a5.607,5.607,0,0,0-11.213,0C0,216.025,3.682,221.31,5.607,223.81Zm0-13.318a2.492,2.492,0,1,1-2.492,2.492A2.492,2.492,0,0,1,5.607,210.492Zm0,0" transform="translate(0 -208)" opacity="0.8"/>
						</svg>
					</i>
					<span><?php echo wp_kses_post( $destination ); ?></span>
				</span>
				<?php endif; ?>
				<?php if ( $trip_duration ): 
					wte_get_template( 'components/content-trip-card-duration.php', compact( 'trip_duration_unit', 'trip_duration', 'trip_duration_nights', 'set_duration_type' , 'is_featured_widget' ) );
				endif; ?>
			</div>
			<div class="category-trip-review">
				<div class="rating-rev rating-layout-1 smaller-ver">
					<?php do_action( 'wte_trip_average_rating_star' ); ?>
				</div>
				<span class="category-trip-reviewcount">
					<?php do_action( 'wte_trip_average_rating_based_on_text' ); ?>
				</span>
			</div>
		</div>
	</div>
</div>

<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
