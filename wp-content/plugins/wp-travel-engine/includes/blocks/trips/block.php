<?php
/**
 * Content for Trips Block.
 */

$results = array();
if ( ! empty( $attributes['filters']['tripsToDisplay'] ) ) {
	$results = get_posts(
		array(
			'post_type'      => WP_TRAVEL_ENGINE_POST_TYPE,
			'post__in'       => $attributes['filters']['tripsToDisplay'],
			'posts_per_page' => 100,
		)
	);
	if ( ! is_array( $results ) ) {
		return;
	}
}

$results = array_combine( array_column( $results, 'ID' ), $results );

$layout   = wte_array_get( $attributes, 'layout', 'grid' );
$column   = wte_array_get( $attributes, 'tripsCountPerRow', 3 );
$settings = get_option( 'wp_travel_engine_settings', array() );

$dates_layout = ! empty( $settings['fsd_dates_layout'] ) ? $settings['fsd_dates_layout'] : 'dates_list';
$show_heading = wte_array_get( $attributes, 'showSectionHeading', false );

$show_section_description = wte_array_get( $attributes, 'showSectionDescription', false );

$viewMoreLink = wte_array_get( $attributes, 'viewAllLink', '' ) != '' ? trailingslashit( $attributes['viewAllLink'] ) : trailingslashit( get_post_type_archive_link( WP_TRAVEL_ENGINE_POST_TYPE ) );

$slider_settings = array(
	'speed'             => wte_array_get( $attributes, 'slider.speed', 300 ),
	'effect'            => wte_array_get( $attributes, 'slider.effect', 'slide' ),
	'loop'              => wte_array_get( $attributes, 'slider.loop', 'yes' ) === 'yes',
	'wrapperClass'      => 'wpte-swiper-wrapper',
	'pauseOnMouseEnter' => wte_array_get( $attributes, 'slider.pauseOnMouseEnter', 'yes' ) === 'yes',
	'slidesPerView'     => wte_array_get( $attributes, 'slider.slidesPerViewMobile', 1 ),
	'spaceBetween'      => wte_array_get( $attributes, 'slider.spaceBetween', 30 ),
	'breakpoints'       => wte_array_get(
		$attributes,
		'slider.breakpoints',
		array(
			576 => array(
				'slidesPerView' => (int) wte_array_get( $attributes, 'slider.slidesPerViewTablet', 2 ),
			),
			768 => array(
				'slidesPerView' => (int) wte_array_get( $attributes, 'slider.slidesPerViewDesktop', 3 ),
			),
		)
	),
);
if ( wte_array_get( $attributes, 'slider.autoplay', 'yes' ) === 'yes' ) {
	$slider_settings['autoplay'] = array(
		'delay' => (int) wte_array_get( $attributes, 'slider.autoplaydelay', 3000 ),
	);
}

echo '<div class="wp-block-wptravelengine-trips wpte-gblock-wrapper">';
if ( $results && is_array( $results ) ) :
	if ( $show_heading || $show_section_description ) {

		echo '<div class="wpte-gblock-title-wrap">';
		if ( $show_heading ) {
			$heading_level = isset( $attributes['sectionHeadingLevel'] ) && $attributes['sectionHeadingLevel'] ? $attributes['sectionHeadingLevel'] : 0;
			$heading       = $heading_level ? "<h{$heading_level} class=\"wpte-gblock-title\">%s</h{$heading_level}>" : '<p>%s</p>';
			printf( $heading, esc_html( wte_array_get( $attributes, 'sectionHeading', '' ) ) );
		}
		if ( $show_section_description ) {
			printf( '<p>%s</p>', wp_kses_post( wte_array_get( $attributes, 'sectionDescription', '' ) ) );
		}
		echo '</div>';
	}
	?>
	<div class="<?php echo esc_attr( "category-{$layout} wte-d-flex wte-col-{$column} wpte-trip-list-wrapper columns-{$column}" ); ?>">
	<?php
	( 'slider' === $layout ) && print( '<div class="wpte-swiper swiper" data-swiper-options="' . esc_attr( wp_json_encode( $slider_settings ) ) . '"><div class="wpte-swiper-wrapper swiper-wrapper">' );
	$position = 1;
	$wte_global = get_option( 'wp_travel_engine_settings', array() );
	$details      = wte_get_trip_details( get_the_ID() );
	foreach ( $attributes['filters']['tripsToDisplay'] as $trip_id ) :
		if ( ! isset( $results[ $trip_id ] ) ) {
			continue;
		}
		$trip        = $results[ $trip_id ];
		$is_featured = wte_is_trip_featured( $trip->ID );
		$meta        = \wte_trip_get_trip_rest_metadata( $trip->ID );
		$args        = array( $attributes, $trip, $results, $meta, $is_featured, $wte_global, $details );
		( 'slider' === $layout ) && print( '<div class="swiper-slide">' );
		include __DIR__ . '/layouts/layout-' . $attributes['cardlayout'] . '.php';
		( 'slider' === $layout ) && print( '</div>' );
		$position++;
			endforeach;
	if ( 'slider' === $layout ) :
		?>
			</div><!-- .wpte-swiper-wrapper -->
		</div><!-- .wpte-swiper -->

		<div class="wpte-swiper-navigation">
			<?php if ( in_array( wte_array_get( $attributes, 'slider.navigation', 'arrowsanddots' ), array( 'arrowsanddots', 'dots' ) ) ) : ?>
				<!-- If we need pagination -->
				<div class="wpte-swiper-pagination"></div>
			<?php endif; ?>

			<?php if ( in_array( wte_array_get( $attributes, 'slider.navigation', 'arrowsanddots' ), array( 'arrowsanddots', 'arrows' ) ) ) : ?>
				<!-- If we need navigation buttons -->
				<div class="wpte-swiper-button-prev"></div>
				<div class="wpte-swiper-button-next"></div>
				<?php
			endif;
			?>
		</div><!-- .wpte-swiper-navigation -->
		<?php
	endif;
	echo '</div><!-- .category-{$layout} -->';
endif;
if ( wte_array_get( $attributes, 'layoutFilters.showViewAll', false ) ) :
	?>
	<div class="wte-block-btn-wrapper">
		<a href="<?php echo esc_url( trailingslashit( $viewMoreLink ) ); ?>" class="wte-view-all-trips-btn"><?php echo esc_html( wte_array_get( $attributes, 'viewAllButtonText', __( 'View All', 'wp-travel-engine' ) ) ); ?></a>
	</div>
	<?php
endif;
echo '</div>';
