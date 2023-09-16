<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin UI pointer to guide users through new changes in plugin UI.
 */
// Create as a class
class WP_TRAVEL_ENGINE_ADMIN_UI_POINTERS {
	// Initiate construct
	function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Defining footer scripts
	 * 'id' - should correspond to an html element id on the page.
	 * 'content' - will be displayed inside the pointer overlay window.
	 * 'button2' - is the text to show for the 'action' button in the pointer overlay window.
	 * 'function' - is the method used to reload the window (or relocate to a new window). This also creates a
	 * query variable to add to the end of the url. The query variable is used to determine which pointer to display.
	 */
	function admin_print_footer_scripts() {
		global $pagenow;
		global $current_user;
		$current_screen = get_current_screen();

		$tour = array(
			'wp_travel_engine_settings' => array(
				'id'                => '#menu-posts-booking',
				'content'           => '<h3>' . __( 'WP Travel Engine - Settings', 'wp-travel-engine' ) . '</h3>'
					. '<p><strong>' . __( 'Moved to new menu under WP Travel Engine', 'wp-travel-engine' ) . '</strong></p>'
					. '<p>' . __( 'WP Travel Engine Settings has been moved to new menu under menu head <strong>WP Travel Engine</strong>, along with other settings such as Booking, Customers, Enquiries and other menus created by our addons. We have also updated our backend User Interface.', 'wp-travel-engine' ) . '</p>'
					. '<p>' . __( 'For Full documentation about this changes please visit', 'wp-travel-engine' ) . ' ' . '<a href="https://wptravelengine.com/wp-travel-engine-version-4/" target="_blank" rel="noopener">' . __( 'full documentation', 'wp-travel-engine' ) . '</a>.' . '</p>',
				'wte_usermeta_name' => 'wte_admin_tour_wp_pointer',
			),
		);

		$tour_trip_meta    = array(
			'wp_travel_engine_setting' => array(
				'id'                => '#wpte-edit-trip',
				'content'           => '<h3>' . __( 'WP Travel Engine - Trip Data', 'wp-travel-engine' ) . '</h3>'
					. '<p><strong>' . __( 'New Admin User Interface', 'wp-travel-engine' ) . '</strong></p>'
					. '<p>' . __( 'New Admin UI integrated.', 'wp-travel-engine' ) . '</p>'
					. '<p>' . __( 'For Full documentation about these changes please visit', 'wp-travel-engine' ) . ' ' . '<a href="https://wptravelengine.com/wp-travel-engine-version-4/" target="_blank" rel="noopener">' . __( 'full documentation', 'wp-travel-engine' ) . '</a>.' . '</p>',
				'wte_usermeta_name' => 'wte_admin_trip_wp_pointer',
			),
		);
		$tour_trip_options = array(
			'content'  => $tour_trip_meta['wp_travel_engine_setting']['content'],
			'position' => array(
				'edge'         => 'bottom',
				'align'        => 'left',
				'pointerClass' => 'wp-pointer wte-pointer',
			),
			'target'   => $tour_trip_meta['wp_travel_engine_setting']['id'],
		);
		// Determine which tab is set in the query variable
		$tab = isset( $_GET['tab'] ) ? wte_clean( wp_unslash( $_GET['tab'] ) ) : ''; // phpcs:ignore
		// Define other variables
		$function        = '';
		$button2         = '';
		$options         = array();
		$show_pointer    = false;
		$dismissed       = explode( ',', get_user_meta( wp_get_current_user()->ID, 'dismissed_wp_pointers', true ) );
		$do_tour         = ! in_array( 'wte_admin_tour_wp_pointer', $dismissed );
		$do_trip_pointer = ! in_array( 'wte_admin_trip_wp_pointer', $dismissed );

		/**
		* This will be the first pointer tour start
		* If no query variable is set in the url.. then the 'tab' cannot be determined... and we start with this pointer.
		*/
		if ( ! array_key_exists( $tab, $tour ) ) {

			$show_pointer = true;
			$file_error   = true;

			$id       = '#menu-posts-trip';
			$content  = '<h3>' . sprintf( __( 'WP Travel Engine - Version %s', 'wp-travel-engine' ), WP_TRAVEL_ENGINE_VERSION ) . '</h3>';
			$content .= __( '<p>Welcome to New WP Travel Engine!</p>', 'wp-travel-engine' );
			$content .= __( '<p>We have made changes to ensure you have even better and faster plugin experience.</p>', 'wp-travel-engine' );
			$content .= '<p>' . __( 'Click the <em>Begin Tour</em> button to get started.', 'wp-travel-engine' ) . '</p>';

			$options           = array(
				'content'  => $content,
				'position' => array(
					'edge'  => 'left',
					'align' => 'left',
				),
				'target'   => $id,
			);
			$wte_usermeta_name = 'wte_admin_tour_wp_pointer';

			$button2  = __( 'Begin Tour', 'wp-travel-engine' );
			$function = 'document.location="' . $this->get_admin_url( 'edit.php?post_type=booking&page=class-wp-travel-engine-admin.php', 'wp_travel_engine_settings' ) . '";';
		}
		// Else if the 'tab' is set in the query variable.. then we can determine which pointer to display
		else {

			if ( $tab != '' && in_array( $tab, array_keys( $tour ) ) ) {

				$show_pointer = true;

				if ( isset( $tour[ $tab ]['id'] ) ) {
					$id = $tour[ $tab ]['id'];
				}

				$options           = array(
					'content'  => $tour[ $tab ]['content'],
					'position' => array(
						'edge'  => 'left',
						'align' => 'left',
					),
					'target'   => $id,
				);
				$wte_usermeta_name = $tour[ $tab ]['wte_usermeta_name'];
				$button2           = false;
				$function          = '';

				if ( isset( $tour[ $tab ]['button2'] ) ) {
					$button2 = $tour[ $tab ]['button2'];
				}
				if ( isset( $tour[ $tab ]['function'] ) ) {
					$function = $tour[ $tab ]['function'];
				}
			}
		}

		// If we are showing a pointer... let's load the jQuery.
		if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) && $show_pointer && $do_tour ) {
			$this->make_pointer_script( $id, $options, __( 'Close', 'wp-travel-engine' ), $button2, $function, $wte_usermeta_name );
		}
		if ( $current_screen->id === 'trip' && in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) && $do_trip_pointer ) {
			$this->make_pointer_script( $tour_trip_meta['wp_travel_engine_setting']['id'], $tour_trip_options, __( 'Close', 'wp-travel-engine' ), $button2 = '', $function = '', $tour_trip_meta['wp_travel_engine_setting']['wte_usermeta_name'] );
		}
	}

	/**
	 * Admin Enqueue Required Scripts
	 */
	function admin_enqueue_scripts() {

		// Check to see if user has already dismissed the pointer tour
		$dismissed       = explode( ',', get_user_meta( wp_get_current_user()->ID, 'dismissed_wp_pointers', true ) );
		$do_tour         = ! in_array( 'wte_admin_tour_wp_pointer', $dismissed );
		$do_trip_pointer = ! in_array( 'wte_admin_trip_wp_pointer', $dismissed );
		// If not, we are good to continue
		if ( $do_tour || $do_trip_pointer || true ) {
			  // Enqueue necessary WP scripts and styles
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer' );

			// Finish hooking to WP admin areas
			add_action( 'admin_print_footer_scripts', array( $this, 'admin_print_footer_scripts' ) );  // Hook to admin footer scripts
			add_action( 'admin_head', array( $this, 'admin_head' ) );  // Hook to admin head
		}
	}

	/**
	 * Used to add spacing between the two buttons in the pointer overlay window.
	 */
	function admin_head() {
		?>
		<style type="text/css" media="screen">
		   #pointer-primary {
				margin: 0 5px 0 0;
			}
		</style>
		<?php
	}


	/** This function is used to reload the admin page.
	 * $page - Admin page we are passing (index.php or options-general.php)
	 * $tab - NEXT pointer array key we want to display
	 */
	function get_admin_url( $page, $tab ) {
		$url  = admin_url();
		$url .= $page . '&tab=' . $tab;
		return $url;
	}

	// Print footer scripts
	function make_pointer_script( $id, $options, $button1, $button2 = false, $function = '', $wte_usermeta_name = '' ) {
		?>
		<script type="text/javascript">
			;(function ($) {
				// Define pointer options
				var wp_pointers_tour_opts = <?php echo wp_json_encode( $options ); ?>, setup;
				wp_pointers_tour_opts = $.extend(wp_pointers_tour_opts, {
					// Add 'Close' button
					buttons: function (event, t) {
						button = jQuery ('<a id="pointer-close" class="button-secondary">' + '<?php echo esc_html( $button1 ); ?>' + '</a>');
						button.bind ('click.pointer', function () {
							t.element.pointer ('close');
						});
						return button;
					},
					close: function () {

						// Post to admin ajax to disable pointers when user clicks "Close"
						$.post (ajaxurl, {
							pointer: '<?php echo esc_html( $wte_usermeta_name ); ?>',
							action: 'dismiss-wp-pointer'
						});
					}
				});

				// This is used for our "button2" value above (advances the pointers)
				setup = function () {

					$('<?php echo esc_html( $id ); ?>').pointer(wp_pointers_tour_opts).pointer('open');

					<?php if ( $button2 ) { ?>

						jQuery ('#pointer-close').after ('<a id="pointer-primary" class="button-primary">' + '<?php echo esc_html( $button2 ); ?>' + '</a>');
						jQuery ('#pointer-primary').on('click', function () {
							<?php echo $function; ?>  // Execute button2 function
						});
						jQuery ('#pointer-close').on('click', function () {

							// Post to admin ajax to disable pointers when user clicks "Close"
							$.post (ajaxurl, {
								pointer: '<?php echo esc_html( $wte_usermeta_name ); ?>',
								action: 'dismiss-wp-pointer'
							});
						})
					<?php } ?>
				};

				if (wp_pointers_tour_opts.position && wp_pointers_tour_opts.position.defer_loading) {

					$(window).bind('load.wp-pointers', setup);
					$('html,body').animate({
						scrollTop: $('<?php echo esc_html( $id ); ?>').offset().top
					}, 'slow');
				}
				else {
					setup ();
				}
			}) (jQuery);
		</script>
		<?php
	}
}
$WP_TRAVEL_ENGINE_ADMIN_UI_POINTERS = new WP_TRAVEL_ENGINE_ADMIN_UI_POINTERS( $this->get_plugin_name(), $this->get_version() );
