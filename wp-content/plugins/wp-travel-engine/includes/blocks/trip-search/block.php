<?php
/**
 * Content for Trips Search.
 */

/**
 * Template vars $attributes
 */
$results = array();
if ( ! empty( $attributes['listItems'] ) ) {
	$results = wte_get_terms_by_id(
		$attributes['taxonomy'],
		array(
			'taxonomy'   => $attributes['taxonomy'],
			'include'    => $attributes['listItems'],
			'hide_empty' => true,
		)
	);
	if ( ! is_array( $results ) ) {
		return;
	}
}

$attributes = (object) $attributes;

$search_filters = $attributes->{'searchFilters'};

uasort(
	$search_filters,
	function( $sf1, $sf2 ) {
		return $sf1['order'] - $sf2['order'];
	}
);

$layout_filters      = $attributes->{'layoutFilters'};
$show_duration_range = isset( $attributes->{'layoutFilters'}['showDurationRange'] ) && $attributes->{'layoutFilters'}['showDurationRange'];
$show_title          = isset( $attributes->{'layoutFilters'}['showTitle'] ) && $attributes->{'layoutFilters'}['showTitle'];
$show_subtitle       = isset( $attributes->{'layoutFilters'}['showSubtitle'] ) && $attributes->{'layoutFilters'}['showSubtitle'];
// $show_view_all       = isset( $attributes->{'layoutFilters'}['showViewAll'] ) && $attributes->{'layoutFilters'}['showViewAll'];
$title_level   = isset( $attributes->{'titleLevel'} ) ? $attributes->{'titleLevel'} : 3;
$is_horizontal = isset( $attributes->{'searchFormOrientation'} ) && $attributes->{'searchFormOrientation'};

?>
<div class="wpte-gblock-wrapper">
	<?php
	if ( $show_title || $show_subtitle ) :
		;
		?>
		<div class="wpte-gblock-title-wrap">
			<?php
			if ( $show_title ) :
				if ( $title_level ) {
					printf( '<h%1$d>%2$s</h%1$d>', (int) $title_level, esc_html( $attributes->{'title'} ) );
				} else {
					printf( '<p>%1$s</p>', esc_html( $attributes->{'title'} ) );
				}
			endif;
			?>
			<?php
			if ( $show_subtitle ) :
				printf( '<p>%2$s</p>', (int) $title_level, wp_kses_post( $attributes->{'subtitle'} ) );
			endif;
			?>
		</div>
	<?php endif; ?>
	<div class="wpte-trip-sfilter-wrapper">
		<form class="wpte-trip__search-fields<?php $is_horizontal && print( ' horizontal' ); ?>" action="<?php echo esc_url( wp_travel_engine_get_page_permalink( 'search' ) ); ?>">
			<?php
			$icons = array(
				'destination' => '<svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 0C4.4087 0 2.88258 0.632141 1.75736 1.75736C0.632141 2.88258 0 4.4087 0 6C0 10.05 5.2875 14.625 5.5125 14.82C5.64835 14.9362 5.82124 15 6 15C6.17877 15 6.35165 14.9362 6.4875 14.82C6.75 14.625 12 10.05 12 6C12 4.4087 11.3679 2.88258 10.2426 1.75736C9.11742 0.632141 7.5913 0 6 0ZM6 13.2375C4.4025 11.7375 1.5 8.505 1.5 6C1.5 4.80653 1.97411 3.66193 2.81802 2.81802C3.66193 1.97411 4.80653 1.5 6 1.5C7.19347 1.5 8.33807 1.97411 9.18198 2.81802C10.0259 3.66193 10.5 4.80653 10.5 6C10.5 8.505 7.5975 11.745 6 13.2375ZM6 3C5.40666 3 4.82664 3.17595 4.33329 3.50559C3.83994 3.83524 3.45542 4.30377 3.22836 4.85195C3.0013 5.40013 2.94189 6.00333 3.05764 6.58527C3.1734 7.16721 3.45912 7.70176 3.87868 8.12132C4.29824 8.54088 4.83279 8.8266 5.41473 8.94236C5.99667 9.05811 6.59987 8.9987 7.14805 8.77164C7.69623 8.54458 8.16477 8.16006 8.49441 7.66671C8.82405 7.17336 9 6.59334 9 6C9 5.20435 8.68393 4.44129 8.12132 3.87868C7.55871 3.31607 6.79565 3 6 3ZM6 7.5C5.70333 7.5 5.41332 7.41203 5.16665 7.2472C4.91997 7.08238 4.72771 6.84811 4.61418 6.57403C4.50065 6.29994 4.47094 5.99834 4.52882 5.70736C4.5867 5.41639 4.72956 5.14912 4.93934 4.93934C5.14912 4.72956 5.41639 4.5867 5.70737 4.52882C5.99834 4.47094 6.29994 4.50065 6.57403 4.61418C6.84811 4.72771 7.08238 4.91997 7.2472 5.16665C7.41203 5.41332 7.5 5.70333 7.5 6C7.5 6.39782 7.34197 6.77936 7.06066 7.06066C6.77936 7.34196 6.39783 7.5 6 7.5Z" fill="#2183DF" /></svg>',
				'activities'  => '<svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.9192 5.06735C15.4031 5.06735 14.9092 5.16388 14.4543 5.33946C14.0979 4.54743 13.79 3.70981 13.6362 2.93056C13.3892 1.67907 12.2833 0.770752 11.0066 0.770752C10.683 0.770752 10.4206 1.0331 10.4206 1.35669C10.4206 1.68028 10.683 1.94263 11.0066 1.94263C11.146 1.94263 11.2817 1.96208 11.4111 1.99821C11.8041 2.10806 12.1377 2.37392 12.3322 2.73095C12.3165 2.75095 12.3015 2.77169 12.2882 2.79415L12.1061 3.1006L11.1852 4.65099H6.26473L5.98504 3.94704H6.10113C6.42473 3.94704 6.68707 3.6847 6.68707 3.3611C6.68707 3.03751 6.42473 2.77517 6.10113 2.77517H4.08086C3.75727 2.77517 3.49492 3.03751 3.49492 3.3611C3.49492 3.6847 3.75727 3.94704 4.08086 3.94704H4.72406L5.23539 5.23392C4.86914 5.12571 4.48172 5.06731 4.08086 5.06731C1.83066 5.06735 0 6.89802 0 9.14817C0 11.3983 1.83066 13.229 4.08082 13.229C6.13203 13.229 7.83437 11.7077 8.11941 9.73411H9.19547C9.40219 9.73411 9.59363 9.62517 9.69922 9.44743L12.7784 4.2636C12.959 4.82259 13.1851 5.38267 13.4288 5.91774C12.4621 6.6647 11.8384 7.83489 11.8384 9.14821C11.8384 11.3984 13.669 13.229 15.9192 13.229C18.1693 13.229 20 11.3984 20 9.14821C20 6.89806 18.1693 5.06735 15.9192 5.06735ZM4.08082 9.73411H6.93047C6.65859 11.0582 5.48418 12.0571 4.08082 12.0571C2.47684 12.0571 1.17188 10.7522 1.17188 9.14817C1.17188 7.54419 2.47684 6.23923 4.08082 6.23923C5.48418 6.23923 6.65859 7.23813 6.93047 8.56224H4.08082C3.75723 8.56224 3.49488 8.82458 3.49488 9.14817C3.49488 9.47177 3.75723 9.73411 4.08082 9.73411ZM8.86199 8.56224H8.11941C7.98344 7.62087 7.52504 6.78251 6.86 6.16298C6.85391 6.13946 6.84684 6.11603 6.83762 6.09286L6.73035 5.8229H10.4891L8.86199 8.56224ZM15.9192 12.0571C14.3152 12.0571 13.0102 10.7521 13.0102 9.14813C13.0102 8.29841 13.3766 7.5327 13.9595 7.00036C14.6801 8.37755 15.384 9.40419 15.437 9.48099C15.5507 9.64571 15.7336 9.73411 15.9197 9.73411C16.0345 9.73411 16.1504 9.70044 16.252 9.63032C16.5184 9.44649 16.5852 9.08157 16.4014 8.81524C16.3888 8.79696 15.6797 7.76294 14.9697 6.39911C15.2675 6.29595 15.5868 6.23915 15.9192 6.23915C17.5232 6.23915 18.8281 7.54411 18.8281 9.1481C18.8281 10.7521 17.5232 12.0571 15.9192 12.0571Z" fill="#2183DF"/></svg>',
				'default'     => '<svg viewBox="0 0 343.5 343.5"><g><g><path d="M322.05,161.8h-182.6c-5.5,0-10,4.5-10,10s4.5,10,10,10h182.6c5.5,0,10-4.5,10-10C332.05,166.3,327.65,161.8,322.05,161.8 z"/></g></g><g><g><path d="M57.95,125.3c-25.7,0-46.5,20.8-46.5,46.5s20.8,46.5,46.5,46.5s46.5-20.8,46.5-46.5S83.65,125.3,57.95,125.3z M57.95,198.3c-14.7,0-26.5-11.9-26.5-26.5c0-14.7,11.9-26.5,26.5-26.5c14.6,0,26.5,11.9,26.5,26.5S72.55,198.3,57.95,198.3z"/></g></g><g><g><path d="M322.05,36.8h-182.6c-5.5,0-10,4.5-10,10s4.5,10,10,10h182.6c5.5,0,10-4.5,10-10C332.05,41.3,327.65,36.8,322.05,36.8z"/></g></g><g><g><path d="M57.95,0c-25.7,0-46.5,20.8-46.5,46.5c0,25.7,20.8,46.5,46.5,46.5s46.5-20.8,46.5-46.5C104.45,20.9,83.65,0.1,57.95,0z M57.95,73.1c-14.7,0-26.5-11.9-26.5-26.5c0-14.6,11.9-26.5,26.5-26.5c14.7,0,26.5,11.9,26.5,26.5 C84.45,61.2,72.55,73.1,57.95,73.1z"/></g></g><g><g><path d="M322.05,286.8h-182.6c-5.5,0-10,4.5-10,10s4.5,10,10,10h182.6c5.5,0,10-4.5,10-10S327.65,286.8,322.05,286.8z"/></g></g><g><g><path d="M57.95,250.5c-25.7,0-46.5,20.8-46.5,46.5c0,25.7,20.8,46.5,46.5,46.5s46.5-20.8,46.5-46.5 C104.45,271.4,83.65,250.5,57.95,250.5z M57.95,323.6c-14.7,0-26.5-11.9-26.5-26.5c0-14.7,11.9-26.5,26.5-26.5 c14.7,0,26.5,11.9,26.5,26.5S72.55,323.6,57.95,323.6z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>',
			);
			foreach ( $search_filters as $_taxonomy => $_args ) {
				if ( $_args['show'] ) {
					$terms = wte_get_terms_by_id( $_taxonomy );

					$callable_filter = apply_filters( "wptravelengine_search_filter_{$_taxonomy}", null );
					if ( is_callable( $callable_filter ) ) {
						call_user_func( $callable_filter, $_args );
						continue;
					}

					if ( is_array( $terms ) && count( $terms ) > 0 ) {
						?>
						<div class="wpte-trip__adv-field wpte__select-field">
							<span class="icon">
								<?php
								if ( isset( $icons[ $_taxonomy ] ) ) {
									echo wte_esc_svg( $icons[ $_taxonomy ] );
								} else {
									echo wte_esc_svg( $icons['default'] );
								}
								?>
							</span>
							<input type="text" class="wpte__input" placeholder="<?php echo esc_attr( $_args['label'] ); ?>" />
							<input type="hidden" class="wpte__input-value" name="<?php echo esc_attr( $_taxonomy ); ?>" />
							<div class="wpte__select-options">
								<ul>
									<?php
									foreach ( $terms as $term ) {
										if ( (bool) $term->parent ) {
											continue;
										}
										printf( '<li data-value="%2$s" data-label="%3$s"><span>%1$s</span>', esc_html( $term->name ), esc_attr( $term->slug ), esc_attr( $term->name ) );
										if ( count( $term->children ) > 0 ) {
											echo '<ul>';
											foreach ( $term->children as $term_child_id ) {
												$term_child = $terms[ $term_child_id ];
												printf( '<li data-value="%2$s" data-label="%3$s"><span>%1$s</span></li>', esc_html( $term_child->name ), esc_attr( $term_child->slug ), esc_attr( $term_child->name ) );
											}
											echo '</ul>';
										}
										echo '</li>';
									}
									?>
								</ul>
							</div>
						</div>
						<?php
					}
				}
			}
			?>
			<div class="wpte-trip__submit-field">
				<button type="submit" class="wpte-trip__search-submit"><?php echo esc_html__( $attributes->{'searchButtonLabel'}, 'wp-travel-engine' ); ?></button>
			</div>
		</form>
	</div>
</div>
