<?php
/**
 * Terms Listing Layout 1
 */

list( $settings, $term_object, $results ) = $args;

$thumbnail = null;
if ( wte_array_get( (array) $settings, 'layoutFilters.showFeaturedImage', false ) ) {
	$thumbnail = wp_get_attachment_image_src( $term_object->thumbnail, 'trip-single-size' );
}

$show_cta_button       = isset( $settings->{'layoutFilters'}['showCTAButton'] ) && $settings->{'layoutFilters'}['showCTAButton'];
$show_view_more_button = isset( $settings->{'layoutFilters'}['showViewMoreButton'] ) && $settings->{'layoutFilters'}['showViewMoreButton'];
$show_view_more_button = isset( $settings->{'layoutFilters'}['showViewMoreButton'] ) && $settings->{'layoutFilters'}['showViewMoreButton'];
$show_trip_counts      = isset( $settings->{'layoutFilters'}['showTripCounts'] ) && $settings->{'layoutFilters'}['showTripCounts'];
?>
<div class="wpte-trip-category">
	<div class="wpte-trip-category-img-wrap">
		<figure class="thumbnail">
			<?php if ( isset( $thumbnail[0] ) ) : ?>
			<a href="<?php echo esc_url( $term_object->link ); ?>">
				<img src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="" />
			</a>
			<?php endif; ?>
		</figure>
		<div class="wpte-trip-category-overlay">
			<?php if ( count( $term_object->children ) > 0 ) : ?>
			<div class="wpte-trip-subcat-wrap">
				<?php
				foreach ( $term_object->children as $term_child_id ) {
					$term_child_object = $results[ $term_child_id ];
					printf( '<a href="%1$s">%2$s</a>', esc_url( $term_child_object->link ), esc_html( $term_child_object->name ) );
				}
				?>
			</div>
			<?php endif; ?>
			<?php
			if ( $show_view_more_button ) :
				?>
				<div class="wpte-trip-category-btn">
					<a href="<?php echo esc_url( $term_object->link ); ?>" class="wpte-trip-cat-btn"><?php echo ! empty( $attributes->{'linkText'} ) ? esc_html( $attributes->{'linkText'} ) : esc_html__( 'View All', 'wp-travel-engine' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( $show_cta_button ) : ?>
		<div class="wpte-trip-category-text-wrap">
			<h2 class="wpte-trip-category-title"><a href="<?php echo esc_url( $term_object->link ); ?>"><?php echo esc_html( $term_object->name ); ?></a>
				<?php if ( $show_trip_counts ) : ?>
					<span class="trip-count">(<?php echo esc_html( sprintf( '%1$d %2$s', $term_object->count, $settings->{'countLabel'} ) ); ?>)</span>
				<?php endif; ?>
			</h2>
		</div>
	<?php endif; ?>
</div>
