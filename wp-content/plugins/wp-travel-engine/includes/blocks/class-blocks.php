<?php
namespace WPTravelEngine\Core;

/**
 * WTE Blocks.
 *
 * @since __addonmigration__
 */
class Blocks {
	public function __construct() {
		$this->init_hooks();
	}

	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_scripts' ) );

		add_filter( 'block_type_metadata', array( $this, 'block_type_metadata' ) );

		add_filter( 'wte_register_block_types', array( $this, 'get_core_blocks_settings' ), 9 );
	}

	public function get_core_blocks_settings( $attributes = array(), $elementor = false ) {
		return wp_parse_args(
			$attributes,
			array(
				'trip-search'  => array(
					'title'      => __( 'WP Travel Engine - Trip Search', 'wp-travel-engine' ),
					'attributes' => array(
						'title'                 => array(
							'type'    => 'string',
							'default' => '',
						),
						'subtitle'              => array(
							'type'    => 'string',
							'default' => '',
						),
						'titleLevel'            => array(
							'type'    => 'number',
							'default' => 2,
						),
						'searchFormOrientation' => array(
							'type'    => 'boolean',
							'default' => true,
						),
						'searchButtonLabel'     => array(
							'type'    => 'string',
							'default' => __( 'Search', 'wp-travel-engine' ),
						),
						'searchFilters'         => array(
							'type'    => 'object',
							'default' => array(
								'destination' => array(
									'label'   => __( 'Destination', 'wp-travel-engine' ),
									'default' => __( 'Destination', 'wp-travel-engine' ),
									'show'    => true,
									'order'   => 1,
									'icon'    => 'marker',
								),
								'trip_types'  => array(
									'label'   => __( 'Trip Types', 'wp-travel-engine' ),
									'default' => __( 'Trip Types', 'wp-travel-engine' ),
									'show'    => false,
									'order'   => 2,
									'icon'    => 'cycle',
								),
								'activities'  => array(
									'label'   => __( 'Activity', 'wp-travel-engine' ),
									'default' => __( 'Activity', 'wp-travel-engine' ),
									'show'    => true,
									'order'   => 3,
									'icon'    => 'cycle',
								),
								'duration'    => array(
									'label'   => __( 'Duration', 'wp-travel-engine' ),
									'default' => __( 'Duration', 'wp-travel-engine' ),
									'show'    => true,
									'order'   => 4,
									'icon'    => 'duration',
								),
								'price'       => array(
									'label'   => __( 'Price', 'wp-travel-engine' ),
									'default' => __( 'Price', 'wp-travel-engine' ),
									'show'    => true,
									'order'   => 5,
									'icon'    => 'money',
								),
							),
						),
						'layoutFilters'         => array(
							'type'    => 'object',
							'default' => array(
								'showDropdownIcon'  => true,
								'showIcons'         => true,
								'showFilterLabels'  => true,
								'showDestinations'  => true,
								'showDateSelector'  => false,
								'showActivities'    => true,
								'showDurationRange' => true,
								'showPriceRange'    => true,
								'showTitle'         => true,
								'showSubtitle'      => true,
							),
						),
					),
				),
				'terms'        => array(
					'title' => __( 'WP Travel Engine - Terms', 'wp-travel-engine' ),
				),
				'trip-types'   => array(
					'title' => __( 'WP Travel Engine - Trip Types', 'wp-travel-engine' ),
				),
				'activities'   => array(
					'title' => __( 'WP Travel Engine - Activities', 'wp-travel-engine' ),
				),
				'destinations' => array(
					'title' => __( 'WP Travel Engine - Destinations', 'wp-travel-engine' ),
				),
				'trips'        => array(
					'title'      => __( 'WP Travel Engine - Trips', 'wp-travel-engine' ),
					'attributes' => self::get_block_settings( 'trips' ),
				),
			)
		);
	}

	public function block_type_metadata( $metadata ) {
		if ( isset( $metadata['name'] ) && false !== strpos( $metadata['name'], 'wptravelengine' ) ) {
			switch ( substr( $metadata['name'], strlen( 'wptravelengine/' ), strlen( $metadata['name'] ) ) ) {
				case 'trips':
				case 'trip-types':
				case 'terms':
				case 'destinations':
				case 'activities':
					$metadata['attributes']['viewAllButtonText'] = array(
						'type'    => 'string',
						'default' => 'View All',
					);
					$metadata['attributes']['viewAllLink']       = array(
						'type'    => 'string',
						'default' => '',
					);

					$metadata['attributes']['layoutFilters']['default']['showViewAll'] = false;
					break;
				case 'trip-search':
				default:
					return $metadata;
			}
		}
		return $metadata;
	}

	public function init() {
		$this->register_block_type();
	}

	private function register_block_type() {

		$block_types = apply_filters( 'wte_register_block_types', array() );
		foreach ( $block_types as $block => $args ) {
			$default_args = array(
				'title'           => $args['title'],
				'render_callback' => function( $attributes, $content ) use ( $block, $args ) {
					wp_enqueue_script( "wte-blocks-index" );
					wp_enqueue_style( "wte-blocks-index" );
					\ob_start();
					include __DIR__ . "/{$block}/block.php";
					return \ob_get_clean();
				},
				'editor_script'   => 'wte-blocks-editor',
				'editor_style'    => 'wte-blocks-editor',
			);

			$args = \wp_parse_args( $args, $default_args );

			$result = register_block_type_from_metadata(
				__DIR__ . "/{$block}",
				$args
			);
		}
	}

	private function inline_script_blocks_editor() {
		ob_start();
		?>
		<script>

		</script>
		<?php
		$content = ob_get_clean();
		return preg_replace( array( '<script[^>]*>', '</script>' ), '', $content );
	}

	public function enqueue_scripts() {
		foreach (
		array( 'blocks' => array( 'index', 'editor' ) )
		as $folder => $entries
		) {
			foreach ( $entries as $entry ) {
				$asset = include dirname( \WP_TRAVEL_ENGINE_FILE_PATH ) . "/dist/{$folder}/{$entry}.asset.php";

				list( $dependencies, $version ) = wte_list( $asset, array( 'dependencies', 'version' ) );

				if ( 'editor' === $entry ) {
					$dependencies[] = 'wte-global';
				}
				wp_register_script(
					"wte-blocks-{$entry}",
					plugins_url( "dist/{$folder}/{$entry}.js", \WP_TRAVEL_ENGINE_FILE_PATH ),
					$dependencies,
					$version,
					true
				);
				wp_register_style(
					"wte-blocks-{$entry}",
					plugins_url( "dist/{$folder}/{$entry}.css", \WP_TRAVEL_ENGINE_FILE_PATH ),
					array(),
					$version
				);

				// wp_add_inline_script
			}
		}
	}

	public static function get_block_settings( $block_name, $args = array() ) {
		$filename = 'settings.php';

		$metadata_file = __DIR__ . "/{$block_name}/{$filename}";
		if ( ! file_exists( $metadata_file ) ) {
			return false;
		}

		$metadata = include $metadata_file;

		return $metadata;
	}
}

new Blocks();
