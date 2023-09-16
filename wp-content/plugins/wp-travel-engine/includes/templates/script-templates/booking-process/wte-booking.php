<?php
wp_enqueue_script( 'wte-fpickr' );
wp_enqueue_style( 'wte-fpickr' );
wp_enqueue_script( 'wp-util' );
wp_enqueue_script( 'wte-rrule' );
wp_enqueue_script( 'moment' );
wp_enqueue_script( 'wte-moment-tz' );

$tablists = apply_filters(
	'wte-booking-process-tabs',
	array(
		'datetime' => array(
			'id'               => 'wte-booking-datetime',
			'tab_title'        => __( 'Date &amp; Time', 'wp-travel-engine' ),
			'tab_icon'         => '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="14" viewBox="0 0 13 14"><g id="Group_2371" data-name="Group 2371" transform="translate(0)"><rect id="Rectangle_1682" data-name="Rectangle 1682" width="13" height="3" transform="translate(0)" fill="#170d44" opacity="0.6"/><g id="Rectangle_1683" data-name="Rectangle 1683" transform="translate(0 3)" fill="none" stroke-width="1" opacity="0.6"><rect width="13" height="11" stroke="none"/><rect x="0.5" y="0.5" width="12" height="10" fill="none"/></g></g></svg>',
			// The content_callback can be callable or template path.
			'content_callback' => function() {
				wte_get_template( 'script-templates/booking-process/tmpl-wte-booking-datetime.php' );
			},
		),
		'packages' => array(
			'id'               => 'wte-booking-packages',
			'tab_title'        => __( 'Package Type', 'wp-travel-engine' ),
			'tab_icon'         => '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="14.812" viewBox="0 0 10 14.812"><g id="Group_2254" data-name="Group 2254" transform="translate(-1072.099 -77)"><g id="Group_2416" data-name="Group 2416" transform="translate(1072.099 77)"><path id="Path_23953" data-name="Path 23953" d="M33.115,6.1h-1.6V4.317a1.055,1.055,0,0,0-1.158-.9h-2.02a1.055,1.055,0,0,0-1.158.9V6.1h-1.6a1.121,1.121,0,0,0-1.232.958v9.236a1.1211.121,0,0,0,1.232.958h.628v.594a.448.448,0,0,0,.492.383h.32a.448.448,0,0,0,.493-.383v-.594h3.67v.594a.448.448,0,0,0,.493.383h.32a.448.448,0,0,0,.493-.383v-.594h.628a1.121,1.121,00,0,1.232-.958V7.057A1.12,1.12,0,0,0,33.115,6.1Zm-5.1-1.782a.292.292,0,0,1,.32-.249h2.02a.292.292,0,0,1,.32.249V6.1h-2.66Z" transform="translate(-24.346 -3.416)"/></g></g></svg>',
			'content_callback' => function() {
				wte_get_template( 'script-templates/booking-process/tmpl-wte-booking-packages.php' );
			},
		),
	)
);
?>
<div id="wte__booking" style="display: none">
	<div class="wte-process-layout wte-popup-booking-wrapper">
		<nav class="wte-process-nav">
			<div class="wte-process-container">
				<ul class="wte-process-nav-list">
					<?php
					$tabindex = 0; // Used for tab switching when clicked on tab navigation link.
					foreach ( $tablists as $tab ) : // foreachtlast.
						if ( ! isset( $tab['content_callback'] ) ) {
							continue;
						}
						?>
						<li class="wte-process-nav-item" data-target="#<?php echo esc_attr( $tab['id'] ); ?>">
							<a href="#<?php echo esc_attr( $tab['id'] ); ?>" data-tab-index="<?php echo (float) $tabindex; ?>">
								<span class="wte-icon">
									<?php echo wte_array_get( $tab, 'tab_icon', '' ); ?>
								</span>
								<?php echo esc_html( wte_array_get( $tab, 'tab_title', __( 'Untitled', 'wp-travel-engine' ) ) ); ?>
								<span class="arrow">
									<svg xmlns="http://www.w3.org/2000/svg" width="5.81" height="10.121" viewBox="0 0 5.81 10.121">
										<path id="Path_23963" data-name="Path 23963" d="M3290.465,368.331l4,4-4,4" transform="translate(-3289.404 -367.271)" fill="none"  stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" opacity="0.8"/>
									</svg>
								</span>
							</a>
						</li>
						<?php
						$tabindex++;
					endforeach; // endforeachtlast.
					?>
				</ul>
			</div>
		</nav>
		<div class="wte-process-tabs">
			<div id="wteProcessTabs" class="wte-process-container">
				<?php
				$tabcontents = array_column( $tablists, 'content_callback', 'id' );

				foreach ( $tabcontents as $tabid => $tabcontent ) : // foreachtcastc
					?>
					<div class="wte-process-tab-item" id="<?php echo esc_attr( $tabid ); ?>">
						<div class="wte-process-tab-content-wrapper">
							<?php
							if ( is_callable( $tabcontent ) ) {
								call_user_func( $tabcontent );
							}
							?>
						</div>
					</div>
					<?php
				endforeach; // endforeachtcastc
				?>
				<div class="wte-process-tab-controller">
					<button type="button" id="wteProcessPrev" class="wte-process-btn wte-process-btn-prev" data-step="-1" style="display:none">
						<svg xmlns="http://www.w3.org/2000/svg" width="5.811" height="10.121" viewBox="0 0 5.811 10.121">
							<path id="Path_23952" data-name="Path 23952" d="M3294.464,368.331l-4,4,4,4" transform="translate(-3289.714 -367.271)" fill="none" stroke="#147dfe" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
						</svg>
						<?php esc_html_e( 'Back', 'wp-travel-engine' ); ?>
					</button>
					<button type="button" id="wteProcessNext" disabled data-step="1" class="wte-process-btn wte-process-btn-next" data-label-default="<?php echo esc_attr__( 'Continue', 'wp-travel-engine' ); ?>" data-label-checkout="<?php echo esc_attr__( 'Proceed to checkout', 'wp-travel-engine' ); ?>"><?php esc_html_e( 'Continue', 'wp-travel-engine' ); ?></button>
				</div>
			</div>
		</div>
		<aside class="wte-popup-sidebar">
			<div class="wte-booking-summary">
				<div id="wte-booking-summary"></div>
				<?php wte_get_template( 'script-templates/booking-process/tmpl-wte-booking-summary.php' ); ?>
			</div>
		</aside>
	</div>
</div>
