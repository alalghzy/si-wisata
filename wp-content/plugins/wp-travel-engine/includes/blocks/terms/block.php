<?php
/**
 * Content for Terms Listing.
 *
 * @package wp-travel-engine/blocks
 */

/**
 * Template vars: $attribtues
 */
$results = array();
if ( ! empty( $attributes['listItems'] ) ) {
	$results = wte_get_terms_by_id(
		$attributes['taxonomy'],
		array(
			'taxonomy'   => $attributes['taxonomy'],
			// 'include'    => $attributes['listItems'],
			'number'     => 100,
			'hide_empty' => true,
		)
	);
	if ( ! is_array( $results ) ) {
		return;
	}
}
$taxonomies_slugs = array(
	'trip_types' => 'trip-types',
);

$taxonomy_slug = isset( $taxonomies_slugs[ $attributes['taxonomy'] ] ) ? $taxonomies_slugs[ $attributes['taxonomy'] ] : $attributes['taxonomy'];

$show_heading             = wte_array_get( $attributes, 'showTitle', false );
$show_section_description = wte_array_get( $attributes, 'showSubtitle', false );
$view_all_link            = wte_array_get( $attributes, 'viewAllLink', '' ) != '' ? trailingslashit( $attributes['viewAllLink'] ) : home_url( $taxonomy_slug );

$attributes = (object) $attributes;
?>
<div class="wp-block-wptravelengine wpte-gblock-wrapper">
	<?php
	if ( $show_heading || $show_section_description ) {
		?>
		<div class="wpte-gblock-title-wrap">
			<?php
			$show_heading && printf( '<%1$s>%2$s</%1$s>', ( $attributes->{'titleLevel'} ? esc_html( "h{$attributes->{'titleLevel'}}" ) : 'p' ), wp_kses_post( $attributes->title ) );
			$show_section_description && printf( '<p>%1$s</p>', wp_kses_post( $attributes->{'subtitle'} ) );
			?>
		</div>
		<?php
	}
	?>
	<div class="<?php printf( 'wte-d-flex wte-layout-grid wte-col-%1$d wpte-trip-list-wrapper columns-%1$d %2$s', (int) $attributes->{'itemsPerRow'}, ( $attributes->{'cardlayout'} === 2 ) ? 'full-width' : '' ); ?>">
		<?php
		foreach ( $attributes->{'listItems'} as $term_id ) :
			if ( ! isset( $results[ (int) $term_id ] ) ) {
				continue;
			}
			$args = array( $attributes, $results[ $term_id ], $results );
			include __DIR__ . "/layouts/layout-{$attributes->cardlayout}.php";
		endforeach;
		?>
	</div>
	<?php if ( wte_array_get( (array) $attributes, 'layoutFilters.showViewAll', false ) ) : ?>
		<div class="wte-block-btn-wrapper">
			<a href="<?php echo esc_url( trailingslashit( $view_all_link ) ); ?>" class="wte-view-all-trips-btn"><?php echo esc_html( wte_array_get( (array) $attributes, 'viewAllButtonText', __( 'View All', 'wp-travel-engine' ) ) ); ?></a>
		</div>
		<?php
	endif;
	?>
</div>
