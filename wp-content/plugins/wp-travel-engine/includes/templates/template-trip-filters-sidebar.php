<?php
use WPTravelEngine\Modules\TripSearch;
/**
 * Archive Filters Sidebar.
 *
 * @since __addonmigration__
 */
wp_enqueue_style( 'wte-nouislider' );
$filters = TripSearch::get_filters_sections();
$nonce = wp_create_nonce( 'wte_show_ajax_result' );
$settings = get_option( 'wp_travel_engine_settings', array() );
?>
<div class='advanced-search-wrapper' id="wte__trip-search-filters" data-filter-nonce="<?php echo esc_attr( $nonce ); ?>">
	<div class="sidebar">
		<div class="advanced-search-header">
			<h2><?php echo esc_html( apply_filters( 'wte_advanced_filterby_title', __( 'Criteria', 'wp-travel-engine' ) ) ); ?></h2>
			<button class="clear-search-criteria" id="reset-trip-search-criteria"><?php esc_html_e( 'Clear all', 'wp-travel-engine' ); ?></button>
		</div>
		<?php

		if ( is_array( $filters ) ) {
			foreach ( $filters as $filter ) {
				if(  isset( $settings['trip_search']['apply_in_search_page'] ) && empty( $settings['trip_search']['apply_in_search_page'] ) && '0' === $settings['trip_search']['apply_in_search_page'] ){
					call_user_func( $filter['render'], $filter );
				}
				else{
					isset( $filter['show'] ) && $filter['show'] && 	call_user_func( $filter['render'], $filter );
				}
			}
		}

		$hide_dates = wte_advanced_search_hide_filters_in_search_page( 'dates' );
		if ( ! $hide_dates ) {
			do_action( 'wte_departure_date_dropdown', true );
		}
		?>
	</div>
</div>
