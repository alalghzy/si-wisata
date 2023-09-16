<?php
namespace WPTravelEngine\Modules;

/**
 * Custom Filters.
 *
 * @since __addonmigration__
 */
class Filters {
	public function __construct() {
		$this->init_hooks();
	}

	private function init_hooks() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action(
			'admin_menu',
			function() {
				global $menu;
				global $submenu;
				add_submenu_page(
					'edit.php?post_type=trip',
					__( 'WP Travel Engine Custom Filters', 'wp-travel-engine' ),
					__( 'Custom Filters', 'wp-travel-engine' ),
					'manage_options',
					'custom-filters',
					function() {
						include dirname( __FILE__ ) . '/views/admin-custom-filters-page.php';
					},
					18
				);
			}
		);

		add_action( 'init', array( $this, 'init' ), 15 );
	}

	public function init() {
		$this->register_taxonomies();
	}

	private function register_taxonomies() {
		$filters = get_option( 'wte_custom_filters', array() );

		foreach ( $filters as $filter ) {
			register_taxonomy(
				$filter['slug'],
				WP_TRAVEL_ENGINE_POST_TYPE,
				array(
					'labels'       => array(
						'name' => $filter['label'],
					),
					'show_in_rest' => true,
					'hierarchical' => $filter['hierarchical'],
					'sort'         => true,
				)
			);
		}

		\add_filter(
			'trip_filters_sections',
			function( $_filters ) use ( $filters ) {
				foreach ( $filters as $filter ) {
					$filter                    = (object) $filter;
					$_filters[ $filter->slug ] = array(
						'label'  => $filter->label,
						'show'   => ! ! $filter->show,
						'render' => function( $_filter ) use ( $filter ) {
							$_filter = (object) $_filter;
							$categories = get_categories( "taxonomy={$filter->slug}" );
							if ( empty( $categories ) ) {
								return;
							}
							\WPTravelEngine\Modules\TripSearch::filter_taxonomies_render( $filter->slug, (array) $_filter );
						},
					);
				}
				return $_filters;
			}
		);

		\add_filter(
			'wte_filter_categories',
			function( $categories ) use ( $filters ) {
				foreach ( $filters as $filter ) {
					$categories[ $filter['slug'] ] = array(
						'taxonomy'         => $filter['slug'],
						'field'            => 'slug',
						'include_children' => true,
					);
				}
				return $categories;
			}
		);

		\add_filter(
			'wte_register_block_types',
			function( $args ) use ( $filters ) {
				if ( isset( $args['trip-search']['attributes']['searchFilters']['default'] ) ) {
					foreach ( $filters as $filter ) {
						$search_filter = array(
							'label'   => $filter['label'],
							'show'    => false,
							'default' => $filter['label'],
						);
						$args['trip-search']['attributes']['searchFilters']['default'][ $filter['slug'] ] = $search_filter;
					}
				}
				return $args;
			}
		);
	}

	public function admin_init() {
		if ( $this->filter_modify_requests() ) {
			$this->update_filter();
		}
	}

	private function filter_modify_requests() {
		if ( ( isset( $_REQUEST['wte_action'] ) && 'add_filter' === $_REQUEST['wte_action'] && ! empty( $_REQUEST['filter_label'] ) ) || ( ! empty( $_REQUEST['edit_filter'] ) && ! empty( $_REQUEST['filter_label'] ) ) || ! empty( $_REQUEST['delete_filter'] ) ) {
			return \wp_verify_nonce( wte_clean( wp_unslash( $_REQUEST['_nonce'] ) ), '_add_filter_nonce' );
		}
		return false;
	}

	private function update_filter() {
		$request = $_REQUEST; // phpcs:ignore
		$filters = get_option( 'wte_custom_filters', array() );
		if ( isset( $request['delete_filter'] ) ) {
			if ( isset( $filters[ $request['delete_filter'] ] ) ) {
				unset( $filters[ $request['delete_filter'] ] );
			}
		} else {
			$label           = wp_kses( wp_unslash( $request['filter_label'] ), array() );
			$is_hierarchical = isset( $request['filter_is_hierarchical'] ) && 'yes' === $request['filter_is_hierarchical'];
			$show            = isset( $request['show_in_filters'] ) && 'yes' === $request['show_in_filters'];
			$slug            = ! empty( $request['filter_slug'] ) ? \sanitize_title( $request['filter_slug'], '', 'save' ) : \sanitize_title( $label, '', 'save' );

			$filters[ $slug ] = array(
				'label'        => $label,
				'slug'         => $slug,
				'hierarchical' => $is_hierarchical,
				'show'         => $show,
			);
		}
		update_option( 'wte_custom_filters', $filters, true );

		wp_safe_redirect(
			add_query_arg(
				array(
					'post_type' => 'trip',
					'page'      => 'custom-filters',
				),
				admin_url( 'edit.php' )
			)
		);
		exit;
	}

}

new Filters();
