<?php
/**
 * Terms Listing Layout 2
 */
list( $settings, $term_object ) = $args;

$thumbnail = null;
if ( wte_array_get( (array) $settings, 'layoutFilters.showFeaturedImage', false ) ) {
	$thumbnail = wp_get_attachment_image_src( $term_object->thumbnail, 'trip-single-size' );
}
$show_cta_button  = isset( $settings->{'layoutFilters'}['showCTAButton'] ) && $settings->{'layoutFilters'}['showCTAButton'];
$show_trip_counts = isset( $settings->{'layoutFilters'}['showTripCounts'] ) && $settings->{'layoutFilters'}['showTripCounts'];
?>
<div class="wpte-trip-category style-1">
	<div class="wpte-trip-category-img-wrap">
		<figure class="thumbnail">
			<?php if ( isset( $thumbnail[0] ) ) : ?>
			<a href="<?php echo esc_url( $term_object->link ); ?>">
				<img src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="" />
			</a>
			<?php endif; ?>
		</figure>
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
