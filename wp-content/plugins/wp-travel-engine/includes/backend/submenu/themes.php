<?php
/**
 * WTE Theme page content.
 *
 */
?>
<div class="wrap" id="wpte-add-ons">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Themes', 'wp-travel-engine' ); ?></h1>
	<hr class="wp-header-end">
	<br>
	<div class="wpte-list-wrapper">
		<div class="wpte-list-header">
			<div class="wpte-list-container">
				<h2><?php esc_html_e( 'WordPress Travel Themes by WP Travel Engine.', 'wp-travel-engine' ); ?></h2>
			</div>
		</div>
		<div class="wpte-list-container">
			<div class="wpte-list-grid">
				<?php
				$themes = wptravelengine_get_products_from_store( 'themes' );
				foreach ( $themes as $theme ) {
					$theme_url = "https://wptravelengine.com/wordpress-travel-themes/{$theme->info->slug}/?utm_source=setting&utm_medium=customer_site&utm_campaign=setting_addon";
					?>
					<div class="wpte-list-grid-item">
						<div class="wpte-list-item-inner">
							<div class="wpte-list-item-thumbnail">
								<a href="<?php echo esc_url( $theme_url ); ?>" title="<?php echo esc_attr( $theme->info->title ); ?>" target="_blank">
								<img width="320" height="300" src="<?php echo esc_url( $theme->info->thumbnail ); ?>" class="attachment-showcase wp-post-image" alt="<?php echo esc_attr( $theme->info->title ); ?>" title="<?php echo esc_attr( $theme->info->title ); ?>">
								</a>
							</div>
							<div class="wpte-list-item-content-wrap">
								<h3 class="wpte-list-item-title"><a href="<?php echo esc_url( $theme_url ); ?>" title="<?php echo esc_attr( $theme->info->title ); ?>" target="_blank"><?php esc_html_e( $theme->info->title, 'wp-travel-engine' ); ?></a></h3>
								<a href="<?php echo esc_url( $theme_url ); ?>" target="_blank" title="<?php echo esc_attr( $theme->info->title ); ?>" class="wpte-link-btn"><?php esc_html_e( 'Get this Theme!', 'wp-travel-engine' ); ?></a>
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<div class="wpte-list-header" style="margin-top: 2.5rem;">
			<div class="wpte-list-container">
				<h2><?php esc_html_e( 'WordPress Travel Themes by Community.', 'wp-travel-engine' ); ?></h2>
			</div>
		</div>
		<div class="wpte-list-container">
			<div class="wpte-list-grid">
				<?php
				$community_themes = wptravelengine_get_community_themes();
				foreach ( $community_themes as $theme ) {
					?>
					<div class="wpte-list-grid-item">
						<div class="wpte-list-item-inner">
							<div class="wpte-list-item-thumbnail">
								<a href="<?php echo esc_url( $theme['url'] ); ?>" title="<?php echo esc_attr( $theme['title'] ); ?>" target="_blank">
								<img width="320" height="300" src="<?php echo esc_url( $theme['thumbnail'] ); ?>" class="attachment-showcase wp-post-image" alt="<?php echo esc_attr( $theme['title'] ); ?>" title="<?php echo esc_attr( $theme['title'] ); ?>">
								</a>
							</div>
							<div class="wpte-list-item-content-wrap">
								<h3 class="wpte-list-item-title"><a href="<?php echo esc_url( $theme['url'] ); ?>" title="<?php echo esc_attr( $theme['title'] ); ?>" target="_blank"><?php esc_html_e( $theme['title'], 'wp-travel-engine' ); ?></a></h3>
								<a href="<?php echo esc_url( $theme['url'] ); ?>" target="_blank" title="<?php echo esc_attr( $theme['title'] ); ?>" class="wpte-link-btn"><?php esc_html_e( 'Get this Theme!', 'wp-travel-engine' ); ?></a>
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
