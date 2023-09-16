<?php
/**
 * Display messages.
 */
?>
<div class="wrap">
	<h2><?php esc_html_e( 'Messages', 'wp-travel-engine' ); ?></h2>
	<div class="" id="poststuff">
		<div style="text-align: right;">
			<a href="https://wptravelengine.com/wp-travel-engine-documentation/" target="_blank" class="button"><?php wptravelengine_svg_by_fa_icon( "fas fa-book" ); ?> Documentation</a>
			<a href="https://wptravelengine.com/support-ticket/" class="button" target="_blank"><?php wptravelengine_svg_by_fa_icon( "fas fa-paper-plane" ); ?> Contact Us</a>
			<a href="https://wptravelengine.com/feature-request/" target="_blank" class="button"><?php wptravelengine_svg_by_fa_icon( "far fa-lightbulb" ); ?> Feature Requests</a>
			<a href="https://wordpress.org/plugins/wp-travel-engine/#reviews" target="_blank" class="button"><?php wptravelengine_svg_by_fa_icon( "fas fa-comment-dots" ); ?> Feedback</a>
		</div>
		<div class="metabox-holder columns-3" id="post-body">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="post">
					<?php
						$message_list->prepare_items();
						$message_list->display();
					?>
					</form>
				</div>
			</div>
			<br class="clear">
		</div>
	</div>
</div>
