<?php
/**
 * Update Template.
 *
 * @since 5.5
 */
wp_enqueue_style( 'wte-getting-started' );
$major_version = WP_TRAVEL_ENGINE_VERSION;

$assets_path_url = plugin_dir_url( __FILE__ ) . str_replace( '.', '', WP_TRAVEL_ENGINE_VERSION ) . '/assets/';
$assets_path_url = plugin_dir_url( __FILE__ ) . implode( '', array_slice( explode( '.', $major_version ), 0, 2 ) ) . '0/assets/';

$data     = $template_data;
$header   = isset( $data->header ) ? $data->header : null;
$sections = isset( $data->sections ) ? $data->sections : null;
?>
<div class="wrap wte_welcome__container">
	<?php if ( $header ) : ?>
		<div class="wte_about__header">
			<div class="wte_about__header-title">
				<h1>
					<span><?php echo $data->header->title; ?></span>
				</h1>
				<span></span>
			</div>
			<div class="wte_about__header-badge">
				<img src="https://ps.w.org/wp-travel-engine/assets/icon-128x128.png" alt="" />
			</div>

			<div class="wte_about__header-text">
				<p><?php echo esc_html( $data->header->description ); ?></p>
			</div>

			<nav
				class="wte_about__header-navigation nav-tab-wrapper wp-clearfix"
				aria-label="Secondary menu">
				<a
					style="pointer-events:none;"
					href="#"
					class="nav-tab nav-tab-active"
					aria-current="page"><?php esc_html_e( 'What’s New', 'wp-travel-engine' ); ?></a>
			</nav>
		</div>
	<?php endif; ?>

	<?php
	if ( $sections ) :
		foreach ( $sections as $section ) :
			$attributes = array();
			if ( isset( $section->attributes ) ) {
				foreach ( (array) $section->attributes as $attr_name => $attr_value ) {
					$attributes[] = "{$attr_name}=\"{$attr_value}\"";
				}
			}
			$class_names = array( 'wte_about__section' );
			if ( isset( $section->classNames ) ) {
				$class_names = array_merge( $class_names, $section->classNames );
			}
			$attributes[] = 'class="' . implode( ' ', $class_names ) . '"';
			?>
			<div <?php echo implode( ' ', $attributes ); ?>>
				<div class="column">
					<?php isset( $section->title ) && printf( '<h2>%s</h2>', $section->title ); ?>
					<?php isset( $section->content ) && printf( '<p>%s</p>', implode( '</p><p>', $section->content ) ); ?>
					<?php
					if ( isset( $section->images ) ) {
						foreach ( $section->images as $image ) {
							printf(
								'<div class="about__image">
								<figure>
									<img src="%1$s"/>
									%2$s
								</figure>
							</div>',
								str_replace( '%assets_path_url%', "{$assets_path_url}", $image->src ),
								isset( $image->caption ) ? '<figcaption>' . $image->caption . '</figcaption>' : ''
							);
						}
					}
					?>
				</div>
			</div>
			<?php
		endforeach;
	endif;
	?>
	<div class="wte_about__section changelog">
		<div class="column">
			<h2><?php esc_html_e( 'Important Notes:', 'wp-travel-engine' ); ?></h2>
			<p>
				<?php esc_html_e( 'If you are using any of the following addons,', 'wp-travel-engine' ); ?>
				<?php esc_html_e( 'we strongly recommend you to update the addons to the minimum required version to ensure the productivity with the new features.', 'wp-travel-engine' ); ?>
			</p>
			<table>
				<thead>
					<tr>
						<th>Plugin Name</th>
						<th>Compatible Version</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( isset( $data->addonCompatibilities ) ) {
						foreach ( $data->addonCompatibilities as $addon ) {
							printf(
								'<tr>'
								. '<td>WP Travel Engine - %1$s</td>'
								. '<td align="center"><code>%2$s</code></td>'
								. '</tr>',
								esc_html( $addon->name ),
								esc_html( $addon->version )
							);
						}
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<hr/>
	<div class="wte_about__section">
		<h2 class="is-section-header"><?php esc_html_e( 'Need further assistance?', 'wp-travel-engine' ); ?></h2>

		<div class="column">
			<h3><?php esc_html_e( 'Contact Support', 'wp-travel-engine' ); ?></h3>
			<p>
				<?php esc_html_e( 'As always, if you have any queries regarding the features or any add-ons, just send us an email to', 'wp-travel-engine' ); ?>
				<a
					href="mailto:support@wptravelengine.com"
					target="_blank"><?php echo esc_url( 'support@wptravelengine.com' ); ?></a>
				<?php esc_html_e( 'or raise a ticket at', 'wp-travel-engine' ); ?> <a
					href="https://wptravelengine.com/support-ticket/"
					target="_blank"><?php echo esc_url( 'https://wptravelengine.com/support-ticket/' ); ?></a>
			</p>
		</div>
	</div>
	<hr />
	<div class="return-to-dashboard">
		<a href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'Go to Dashboard → Home', 'wp-travel-engine' ); ?></a>
	</div>
</div>
