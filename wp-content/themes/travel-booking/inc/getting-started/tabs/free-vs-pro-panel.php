<?php
/**
 * Free Vs Pro Panel.
 *
 * @package Travel_Booking
 */
?>
<!-- Free Vs Pro panel -->
<div id="free-pro-panel" class="panel-left">
	<div class="panel-aside">               		
		<img src="<?php echo esc_url( get_template_directory_uri() . '/inc/getting-started/images/Travel-booking-Free-vs-pro.png' ); //@todo change respective images.?>" alt="<?php esc_attr_e( 'Free vs Pro', 'travel-booking' ); ?>"/>
		<a class="button button-primary" href="<?php echo esc_url( 'https://wptravelengine.com/downloads/travel-booking-pro/' ); ?>" title="<?php esc_attr_e( 'View Premium Version', 'travel-booking' ); ?>" target="_blank">
            <?php esc_html_e( 'View Pro', 'travel-booking' ); ?>
        </a>
	</div><!-- .panel-aside -->
</div>