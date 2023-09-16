<?php
/**
 * Extentions EDD Fetcah products showcase
 */
// Get addons data from marketplace.
$addons_data = wptravelengine_get_products_from_store( 'addons' );
?>
<div class="wrap" id="wpte-add-ons">
	<?php if ( $addons_data ) : ?>
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Extensions', 'wp-travel-engine' ); ?></h1>
	<hr class="wp-header-end">
	<br>
	<div class="wpte-list-wrapper">
		<div class="wpte-list-header">
			<div class="wpte-list-container">
				<a href="https://wptravelengine.com/plugins/?utm_source=setting&utm_medium=customer_site&utm_campaign=setting_addon" class="wpte-link-btn" target="_blank"><?php esc_html_e( 'View All Extensions', 'wp-travel-engine' ); ?></a>
				<p><?php esc_html_e( 'These extensions add functionality to your travel booking website.', 'wp-travel-engine' ); ?></p>
			</div>
		</div>
		<div class="wpte-list-container">
			<div class="wpte-list-grid">
				<?php
				foreach ( $addons_data as $key => $product ) :
					$prod_info = $product->info;
					$product_link = "https://wptravelengine.com/plugins/{$prod_info->slug}/?utm_source=setting&utm_medium=customer_site&utm_campaign=setting_addon";
					?>
				<div class="wpte-list-grid-item">
					<div class="wpte-list-item-inner">
						<div class="wpte-list-item-thumbnail">
							<a href="<?php echo esc_url( $product_link ); ?>" title="<?php echo esc_html( $prod_info->title ); ?>" target="_blank">
								<img src="<?php echo esc_url( $prod_info->thumbnail ); ?>" class="attachment-showcase wp-post-image" alt="<?php echo esc_html( $prod_info->title ); ?>" title="<?php echo esc_attr( $prod_info->title ); ?>">
							</a>
						</div>
						<div class="wpte-list-item-content-wrap">
							<h3 class="wpte-list-item-title"><a href="<?php echo esc_url( $product_link ); ?>" target="_blank"><?php echo esc_html( $prod_info->title ); ?></a></h3>
							<p><?php echo esc_html( $prod_info->excerpt ); ?></p>
							<a href="<?php echo esc_url( $product_link ); ?>" title="<?php echo esc_html( $prod_info->title ); ?>" class="wpte-link-btn" target="_blank"><?php esc_html_e( 'Get the Extension!', 'wp-travel-engine' ); ?></a>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<div class="wpte-add-ons-footer">
				<a href="https://wptravelengine.com/plugins/?utm_source=setting&utm_medium=customer_site&utm_campaign=setting_addon" class="wpte-link-btn" target="_blank"><?php esc_html_e( 'View All Extensions', 'wp-travel-engine' ); ?></a>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>
