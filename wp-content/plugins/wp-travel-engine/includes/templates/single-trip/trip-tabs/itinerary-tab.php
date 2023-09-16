<?php
/**
 * Itinerary Template
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/trip-tabs/itinerary-tab.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

do_action( 'wte_before_itinerary_content' );

global $post;
$tabs = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

/**
 * Hook - Display tab content title, left for themes.
 */
do_action( 'wte_itinerary_tab_title' );
?>
<div class="post-data itinerary wte-trip-itinerary-v2">
	<?php
	$maxlen   = max( array_keys( $tabs['itinerary']['itinerary_title'] ) );
	$arr_keys = array_keys( $tabs['itinerary']['itinerary_title'] );
	foreach ( $arr_keys as $key => $value ) {
		if ( array_key_exists( $value, $tabs['itinerary']['itinerary_title'] ) && ! empty( $value ) ) {
			?>
			<div class="itinerary-row">
				<div class="wte-itinerary-head-wrap">
					<div class="title"><?php echo sprintf( __( 'Day %s : ', 'wp-travel-engine' ), esc_attr( $value ) ); ?></div>
					<a class="accordion-tabs-toggle active" href="javascript:void(0);">
						<span class="dashicons dashicons-arrow-down custom-toggle-tabs rotator open"></span>
						<div class="itinerary-title">
							<?php $title = isset( $tabs['itinerary']['itinerary_title'][ $value ] ) ? esc_attr( $tabs['itinerary']['itinerary_title'][ $value ] ) : ''; ?>
							<span>
							<?php
							echo wp_kses(
								$title,
								array(
									'span'   => array(),
									'strong' => array(),
								)
							);
							?>
							</span>
						</div>
					</a>
				</div>
				<div class="itinerary-content active">
					<div class="content">
						<?php
						$content = wte_array_get( $tabs, 'itinerary.itinerary_content_inner.' . $value, '' );
						if ( empty( $content ) ) {
							$content = wte_array_get( $tabs, 'itinerary.itinerary_content.' . $value, '' );
						}
						echo wp_kses_post( wpautop( $content ) );
						?>
					</div>
				</div>
			</div>
			<?php
		}
	}
	?>
</div>
<script>
	;(function() {
		var toggleTab = function(row, force = null) {
			var content = row.querySelector(".itinerary-content")
			var toggler = row.querySelector(".accordion-tabs-toggle")
			var condition = force === null ? ! toggler.classList.contains("active") : force
			var height = content.scrollHeight
			content.classList.toggle("active", condition)
			if(condition) content.style.maxHeight = height + "px"
			else content.style.maxHeight = "0px"
			toggler.classList.toggle("active", condition)
		}

		var handleToggleClick = function(row) {
			return function(event) {
				var target =  event.target
				if(!!target.closest(".wte-itinerary-head-wrap")) {
					toggleTab(row)
				}
			}
		}

		var setContentHeight = function(row) {
			var content = row.querySelector(".itinerary-content")
			var scrollHeight = content.scrollHeight
			if(content.classList.contains("active"))
				content.style.maxHeight = (scrollHeight) + "px"
			else	content.style.maxHeight = "0px"
		}

		var wrapper = document.querySelector(".wte-trip-itinerary-v2")
		if ( wrapper ) {
			var expandall = document.getElementById('itinerary-toggle-button')
			var rows = wrapper.querySelectorAll('.itinerary-row')
			if(expandall) {
				expandall.addEventListener("change", function() {
					if(rows) rows.forEach(row => {
						toggleTab(row, this.checked)
					})
				})
			}
			if ( rows ) {
				rows.forEach(function(row) {
					// setContentHeight(row)
					row.addEventListener('click', handleToggleClick(row))
				});
			}
		}
	})();
</script>
<?php
do_action( 'wte_after_itinerary_content' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
