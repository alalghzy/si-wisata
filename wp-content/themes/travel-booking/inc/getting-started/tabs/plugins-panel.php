<?php
/**
 * Help Panel.
 *
 * @package Travel_Booking
 */
?>
<!-- Updates panel -->
<div id="plugins-panel" class="panel-left visible">
	<h4><?php esc_html_e( 'Recommended Plugins', 'travel-booking' ); ?></h4>

	<p><?php printf( esc_html__( 'Below is a list of recommended plugins to install that will help you get the best out of Travel Booking. Though every plugin is optional, it is recommended that you at least install the Travel Booking Toolkit, WP Travel Engine, One Click Demo Import & Contact Form 7 plugin to create a website similar to the Travel Booking demo.', 'travel-booking' ), TRAVEL_BOOKING_THEME_NAME ); ?></p>

	<hr/>

	<?php 
	$free_plugins = array(
		'travel-booking-toolkit' => array(
			'slug'     	=> 'travel-booking-toolkit',
			'filename' 	=> 'travel-booking-toolkit.php',
		),                
		'wp-travel-engine' => array(
			'slug' 	 	=> 'wp-travel-engine',
			'filename'  => 'wp-travel-engine.php',
		),                
		'contact-form-7' => array(
			'slug' 		=> 'contact-form-7',
			'filename' 	=> 'wp-contact-form-7.php',
		),              
	);

	if( $free_plugins ){ ?>
		<h4 class="recomplug-title"><?php esc_html_e( 'Free Plugins', 'travel-booking' ); ?></h4>
		<p><?php esc_html_e( 'These Free Plugins might be handy for you.', 'travel-booking' ); ?></p>
		<div class="recomended-plugin-wrap">
    		<?php
    		foreach( $free_plugins as $plugin ) {
    			$info     = travel_booking_call_plugin_api( $plugin['slug'] );
    			$icon_url = travel_booking_check_for_icon( $info->icons ); ?>    
    			<div class="recom-plugin-wrap">
    				<div class="plugin-img-wrap">
    					<img src="<?php echo esc_url( $icon_url ); ?>" />
    					<div class="version-author-info">
    						<span class="version"><?php printf( esc_html__( 'Version %s', 'travel-booking' ), $info->version ); ?></span>
    						<span class="seperator">|</span>
    						<span class="author"><?php echo esc_html( strip_tags( $info->author ) ); ?></span>
    					</div>
    				</div>
    				<div class="plugin-title-install clearfix">
    					<span class="title" title="<?php echo esc_attr( $info->name ); ?>">
    						<?php echo esc_html( $info->name ); ?>	
    					</span>
                        <div class="button-wrap">
    					   <?php echo Travel_Booking_Getting_Started_Page_Plugin_Helper::instance()->get_button_html( $plugin['slug'], $plugin['filename'] ); ?>
                        </div>
    				</div>
    			</div>
    			<?php
    		} 
            ?>
		</div>
	<?php
	} 
?>
</div><!-- .panel-left -->