<?php
/**
 * Cart page template.
 *
 * @package WP Travel ENgine.
 */

global $wte_cart;

$cart_items = $wte_cart->getItems();
$totals     = $wte_cart->get_total();

if ( empty( $cart_items ) ) {
	_e( 'Your cart is currently empty. Please add trips to view your cart.', 'wp-travel-engine' );
} else {
	?>
	<div class="wrap">
		<header class="cart-header cf">
			<strong><?php esc_html_e( 'Cart Trips', 'wp-travel-engine' ); ?></strong>
			<a href="<?php echo esc_url( wp_travel_engine_get_checkout_url() ); ?>" class="btn"><?php esc_html_e( 'Checkout', 'wp-travel-engine' ); ?></a>
		</header>
		<div class="cart-table">
			<ul>
				<?php
				foreach ( $cart_items as $key => $item ) :
							$trip_id   = $item['trip_id'];
							$thumbnail = get_the_post_thumbnail_url( $trip_id, 'thumbnail' );
							$trip_name = get_the_title( $trip_id );
					?>
				<!-- begin variation product w/ option -->
				<li class="item">
					<div class="item-main cf">
						<div class="item-block ib-info cf">
							<img class="product-img" src="<?php echo esc_url( $thumbnail ); ?>" />
							<div class="ib-info-meta">
								<span class="title"><?php echo esc_html( $trip_name ); ?></span>
								<span class="itemno">#<?php echo esc_html( $trip_id ); ?></span>
								<span class="styles">
									<span><strong><?php _e( 'Start Date:', 'wp-travel-engine' ); ?></strong><?php echo esc_html( $item['trip_date'] ); ?></span>
							</div>
						</div>
						<div class="item-block ib-qty">
							<span class="qty">1</span>
							<span class="price"><span>x</span> <?php echo wte_esc_price( wte_get_formated_price_html( $item['trip_price'] ) ); ?></span>
						</div>
						<div class="item-block ib-total-price">
							<span class="tp-price"><?php echo wte_esc_price( wte_get_formated_price_html( $item['trip_price'] ) ); ?></span>
						</div>
					</div>
				</li>
				<!-- end variation product w/ option -->
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="sub-table cf">
			<div class="summary-block">
				<ul>
					<li class="subtotal"><span class="sb-label"><?php esc_html_e( 'Cart Total', 'wp-travel-engine' ); ?></span><span class="sb-value"><?php echo wte_esc_price( wte_get_formated_price_html( $totals['cart_total'] ) ); ?></span></li>
					<li class="subtotal"><span class="sb-label"><?php esc_html_e( 'Discount', 'wp-travel-engine' ); ?></span><span class="sb-value"><?php echo wte_esc_price( wte_get_formated_price_html( $totals['discount'] ) ); ?></span></li>
					<li class="subtotal"><span class="sb-label"><?php esc_html_e( 'Subtotal', 'wp-travel-engine' ); ?></span><span class="sb-value"><?php echo wte_esc_price( wte_get_formated_price_html( $totals['sub_total'] ) ); ?></span></li>
					<li class="grand-total"><span class="sb-label"><?php esc_html_e( 'Total', 'wp-travel-engine' ); ?></span><span class="sb-value"><?php echo wte_esc_price( wte_get_formated_price_html( $totals['total'] ) ); ?></span></li>
				</ul>
			</div>
		</div>
		<div class="cart-footer cf">
			<a href="<?php echo esc_url( wp_travel_engine_get_checkout_url() ); ?>" class="btn"><?php esc_html_e( 'Checkout', 'wp-travel-engine' ); ?></a>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'trip' ) ); ?>" class="cont-shopping"><i class="i-angle-left"></i><?php esc_html_e( 'Book another trip', 'wp-travel-engine' ); ?></a>
		</div>
	</div>
	<?php
}
