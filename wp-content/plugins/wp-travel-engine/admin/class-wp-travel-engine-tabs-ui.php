<?php
/**
 * WP Travel Engine - Backend Tabs UI
 *
 * @package WP_Travel_Engine
 */
/**
 * WP Travel Engine Tabs UI
 *
 * @since 3.1.7
 */
class WP_Travel_Engine_Tabs_UI {

	/**
	 * var - admin tab attributes
	 *
	 * @access private
	 */
	private $tab_attributes;

	/**
	 * var - admin tabs
	 *
	 * @access private
	 */
	private $admin_tabs;

	/**
	 * Initialize admin tabs.
	 *
	 * @param [type] $tab_attributes
	 * @return void
	 */
	public function init( $tab_attributes = array() ) {
		$this->tab_attributes['id']    = isset( $tab_attributes['id'] ) ? $tab_attributes['id'] : '';
		$this->tab_attributes['class'] = isset( $tab_attributes['class'] ) ? $tab_attributes['class'] : '';

		return $this;
	}

	/**
	 * Template for the Tabs UI.
	 *
	 * @param $admin_tabs
	 */
	public function template( $admin_tabs ) {
		global $post;
		if ( is_array( $admin_tabs ) && ! empty( $admin_tabs ) ) :
			?>
			<div id="<?php echo esc_attr( $this->tab_attributes['id'] ); ?>" class="wpte-main-wrap <?php echo esc_attr( $this->tab_attributes['class'] ); ?>">
				<div class="wpte-tab-main wpte-vertical-tab">
					<!-- Tabs Navigator -->
					<div class="wpte-tab-wrap">
						<?php
						foreach ( $admin_tabs as $key => $tab ) :
							$next_tab    = next( $admin_tabs );
							$tab_key     = isset( $tab['tab_key'] ) ? $tab['tab_key'] : '';
							$tab_details = array(
								'content_path' => base64_encode( $tab['content_path'] ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
								'content_key'  => $tab['content_key'],
								'tab_heading'  => $tab['tab_heading'],
								'tab_key'      => $tab_key,
							);

							$class  = 'wpte-tab';
							$class .= " {$key}";
							$class .= ' wpte-tab-nav';
							$class .= ( $tab['current'] ) ? ' current' : '';
							$class .= ( $tab['content_loaded'] ) ? ' content_loaded' : '';
							?>
							<a data-next-tab="<?php echo esc_attr( wp_json_encode( $next_tab ) ); ?>"
								data-post-id="<?php echo is_object( $post ) ? esc_attr( $post->ID ) : ''; ?>"
								data-callback="<?php echo esc_attr( $tab['callback_function'] ); ?>"
								href="javascript:void(0);"
								data-tab-details="<?php echo esc_attr( wp_json_encode( $tab_details ) ); ?>"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_admin_load_tab_content' ) ); ?>"
								class="<?php echo esc_attr( $class ); ?>">
							<?php echo esc_html( $tab['tab_label'] ); ?>
						</a>
						<?php endforeach; ?>
					</div>
					<div class="wpte-tab-content-wrap" id="wte-trip-edit-tabs">
						<?php
						foreach ( $admin_tabs as $key => $tab ) :
							$next_tab = next( $admin_tabs );
							if ( isset( $tab['content_loaded'] ) && $tab['content_loaded'] ) {
								if ( file_exists( $tab['content_path'] ) ) {
									?>
									<div data-trigger="<?php echo esc_attr( $tab['content_key'] ); ?>" class="wpte-tab-content <?php echo esc_attr( $key ); ?>-content <?php echo $tab['current'] ? 'current' : ''; ?> <?php echo $tab['content_loaded'] ? 'content_loaded' : ''; ?>">
										<div class="wpte-title-wrap">
											<h2 class="wpte-title"><?php echo esc_html( $tab['tab_heading'] ); ?></h2>
										</div> <!-- .wpte-title-wrap -->
										<div class="wpte-block-content">
											<?php
											global $post;
											if ( isset( $post->ID ) ){
												$args['post_id']  = $post->ID;
											}
											$args['next_tab'] = $next_tab;
											// load template.
											include $tab['content_path'];
											?>
										</div>
									</div>
									<?php
								}
							}
							endforeach;
						?>
					</div> <!-- .wpte-tab-content-wrap -->
					<div style="display:none;" class="wpte-loading-anim"></div>
				</div> <!-- .wpte-tab-main -->
			</div><!-- .wpte-main-wrap -->
			<?php
		endif;
	}
}
