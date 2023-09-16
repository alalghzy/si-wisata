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
$show_trip_counts = isset( $settings->{'layoutFilters'}['showTripCounts'] ) && $settings->{'layoutFilters'}['showTripCounts'];
?>
<div class="wpte-trip-category style-3">
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
			<h2 class="wpte-trip-category-title">
				<a href="<?php echo esc_url( $term_object->link ); ?>">
					<?php echo esc_html( $term_object->name ); ?> <svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.8933 8.49342C15.8299 8.32975 15.7347 8.18022 15.6133 8.05342L8.94667 1.38675C8.82235 1.26243 8.67476 1.16382 8.51233 1.09654C8.3499 1.02926 8.17581 0.994629 8 0.994629C7.64493 0.994629 7.30441 1.13568 7.05333 1.38675C6.92902 1.51107 6.8304 1.65866 6.76312 1.82109C6.69584 1.98352 6.66121 2.15761 6.66121 2.33342C6.66121 2.68849 6.80226 3.02901 7.05333 3.28008L11.4533 7.66675H1.33333C0.979711 7.66675 0.640573 7.80723 0.390525 8.05728C0.140476 8.30733 0 8.64646 0 9.00009C0 9.35371 0.140476 9.69285 0.390525 9.94289C0.640573 10.1929 0.979711 10.3334 1.33333 10.3334H11.4533L7.05333 14.7201C6.92836 14.844 6.82917 14.9915 6.76148 15.154C6.69379 15.3165 6.65894 15.4907 6.65894 15.6668C6.65894 15.8428 6.69379 16.017 6.76148 16.1795C6.82917 16.342 6.92836 16.4895 7.05333 16.6134C7.17728 16.7384 7.32475 16.8376 7.48723 16.9053C7.64971 16.973 7.82398 17.0078 8 17.0078C8.17602 17.0078 8.35029 16.973 8.51277 16.9053C8.67525 16.8376 8.82272 16.7384 8.94667 16.6134L15.6133 9.94675C15.7347 9.81995 15.8299 9.67042 15.8933 9.50675C16.0267 9.18214 16.0267 8.81803 15.8933 8.49342Z" fill="#2183DF" /></svg>
				</a>
				<?php if ( $show_trip_counts ) : ?>
					<span class="trip-count"><?php echo esc_html( sprintf( '%1$d %2$s', $term_object->count, $settings->{'countLabel'} ) ); ?></span>
				<?php endif; ?>
			</h2>
		</div>
	<?php endif; ?>
</div>
