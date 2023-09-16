<?php
/**
 * Public functionality controller.
 *
 * @package wp-travel-engine
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/public
 * @author     WP Travel Engine <https://wptravelengine.com/>
 */
class Wp_Travel_Engine_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private static $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private static $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		self::$plugin_name = $plugin_name;
		self::$version     = $version;

		$this->init_hooks();

	}

	/**
	 * Undocumented function
	 *
	 * @since __addonmigration__
	 * @return void
	 */
	private function init_hooks() {
	}

	/**
	 * Start Session.
	 *
	 * @since    1.0.0
	 */
	function wpte_start_session() {
		if ( ( defined( 'WTE_USE_OLD_BOOKING_PROCESS' ) && ! WTE_USE_OLD_BOOKING_PROCESS )
		|| ( ! defined( 'WTE_USE_OLD_BOOKING_PROCESS' ) ) ) {
			return;
		}

		if ( ! session_id() ) {
			session_start();
		}
	}

	/**
	 * Callback function for add to cart ajax.
	 *
	 * @since    1.0.0
	 */
	public static function wp_add_trip_cart() {
		if ( wte_nonce_verify( 'nonce', 'wp-travel-engine-nonce' ) ) {
			die();
		};

		// phpcs:disable
		if ( array_key_exists( $_SESSION['cart_item'][ wte_clean( wp_unslash( $_REQUEST['trip_id'] ) ) ], $_SESSION['cart_item'] ) ) {
			$result['type']    = 'already';
			$result['message'] = __( 'Already added to cart!', 'wp-travel-engine' );
		} elseif ( ! array_key_exists( $_SESSION['cart_item'][ wte_clean( wp_unslash( $_REQUEST['trip_id'] ) ) ], $_SESSION['cart_item'] ) ) {
			$_SESSION['cart_item'][ wte_clean( wp_unslash( $_REQUEST['trip_id'] ) ) ] = wte_clean( wp_unslash( $_REQUEST['trip_id'] ) );
			$result['type']                                = 'success';
			$result['message']                             = __( 'Added to cart successfully!', 'wp-travel-engine' );
		} else {
			$result['type']    = 'error';
			$result['message'] = __( 'Unable to add to cart!', 'wp-travel-engine' );
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			echo wp_json_encode( $result );
		} else {
			header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
		}
		// phpcs:enable

		die();
	}

	/**
	 * Array of payment gateways
	 */
	function wte_payment_gateways_dropdown() {
		$options = apply_filters( 'wte_payment_gateways_dropdown_options', array() );
		?>
			<?php
			if ( sizeof( $options ) > 0 ) {
				?>
			<div class="payment-method">
				<div class="payment-options">
					<h3><?php esc_html_e( 'Select Payment Method', 'wp-travel-engine' ); ?></h3>
					<div class="payment-options-holder">
						<img src="<?php echo esc_url( WP_TRAVEL_ENGINE_URL . '/public/css/icons/mastercard.png' ); ?>">
						<img src="<?php echo esc_url( WP_TRAVEL_ENGINE_URL . '/public/css/icons/visa.png' ); ?>">
						<img src="<?php echo esc_url( WP_TRAVEL_ENGINE_URL . '/public/css/icons/americanexpress.png' ); ?>">
						<img src="<?php echo esc_url( WP_TRAVEL_ENGINE_URL . '/public/css/icons/discover.png' ); ?>">
						<img src="<?php echo esc_url( WP_TRAVEL_ENGINE_URL . '/public/css/icons/paypal.png' ); ?>">
					</div>
					<select name="wte_payment_options" id="wte_payment_options" required>
						<?php
						echo '<option value="">' . esc_html__( 'Please choose a gateway', 'wp-travel-engine' ) . '</option>';
						foreach ( $options as $option ) {
							echo '<option value="' . esc_attr( $option ) . '">' . esc_attr( $option ) . '</option>';
						}
						?>
					</select>
				</div>
			</div>
				<?php
			}
			?>
		<?php
	}

	/**
	 * Before order form fields.
	 *
	 * @return void
	 */
	function wpte_order_form_before_fields() {

		?>
			<div id="price-loader" style="display: none;">
				<div class="table">
					<div class="table-row">
						<div class="table-cell">
							<svg class="svg-inline--fa fa-spinner fa-w-16 fa-spin" aria-hidden="true" data-prefix="fa" data-icon="spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg><!-- <i class="fa fa-spinner fa-spin" aria-hidden="true"></i> -->
						</div>
					</div>
				</div>
			</div>
			<div class="order-submit">
				<?php
					// Payment fields.
					do_action( 'wte_payment_gateways_dropdown' );
				?>
			</div>
		<?php
	}

	/**
	 * Adding field to user profile.
	 *
	 */
	function wte_wishlist_user_profile_field( $user ) { ?>
		<table class="form-table wishlist-data">
			<tr>
				<th><?php echo esc_attr__("Wishlist","wp-travel-engine"); ?></th>
				<td>
					<input type="text" name="wishlist" id="wishlist" value="<?php echo esc_attr( get_the_author_meta( 'wishlist', $user->ID ) ); ?>" class="regular-text" />
				</td>
			</tr>
		</table>
	<?php 
	}

	/**
	 * Saving field data of user profile.
	 *
	 */
	function wte_save_wishlist_user_profile_field( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
		update_user_meta( $user_id, 'wishlist', $_POST['wishlist'] );
	}
 
	/**
	 * Rest Field.
	 *
	 */
	function rest_register_fields(){
 
		register_rest_field('product',
			'price',
			array(
				'get_callback'    => 'rest_price',
				'update_callback' => null,
				'schema'          => null
			)
		);
	 
		register_rest_field('product',
			'stock',
			array(
				'get_callback'    => 'rest_stock',
				'update_callback' => null,
				'schema'          => null
			)
		);
	 
		register_rest_field('product',
			'image',
			array(
				'get_callback'    => 'rest_img',
				'update_callback' => null,
				'schema'          => null
			)
		);
	}

	function rest_price($object,$field_name,$request){
 
		global $product;
	 
		$id = $product->get_id();
	 
		if ($id == $object['id']) {
			return $product->get_price();
		}
	 
	}
	 
	function rest_stock($object,$field_name,$request){
	 
		global $product;
	 
		$id = $product->get_id();
	 
		if ($id == $object['id']) {
			return $product->get_stock_status();
		}
	 
	}
	 
	function rest_img($object,$field_name,$request){
	 
		global $product;
	 
		$id = $product->get_id();
	 
		if ($id == $object['id']) {
			return $product->get_image();
		}
	 
	}
	 
	function maximum_api_filter($query_params) {
		$query_params['per_page']["maximum"]=100;
		return $query_params;
	}

	/**
	 * After order form fields.
	 *
	 * @return void
	 */
	function wpte_order_form_after_fields() {

		do_action( 'wte_acqusition_form' );
		$checkout_nonce = wp_create_nonce( 'checkout-nonce' );
		do_action( 'wte_mailchimp_confirmation' );
		do_action( 'wte_mailerlite_confirmation' );
		do_action( 'wte_convertkit_confirmation' );

		?>
			<input type="hidden" value="<?php echo esc_attr( $checkout_nonce ); ?>" name="check-nonce">

			<!-- Placeholder for payment fields. -->
			<div id="wte-checkout-payment-fields"></div>
		<?php

	}

	/**
	 * After order form submit button
	 *
	 * @return void
	 */
	function wpte_order_form_before_submit_button() {
		?>
			<div class="error"></div>
			<div class="successful"></div>
		<?php

	}

	/**
	 * After order form submit button
	 *
	 * @return void
	 */
	function wpte_order_form_after_submit_button() {
		?>
			<div id="submit-loader" style="display: none">
				<div class="table">
					<div class="table-row">
						<div class="table-cell">
							<svg class="svg-inline--fa fa-spinner fa-w-16 fa-spin" aria-hidden="true" data-prefix="fa" data-icon="spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg><!-- <i class="fa fa-spinner fa-spin" aria-hidden="true"></i> -->
						</div>
					</div>
				</div>
			</div>
		<?php

	}

	/**
	 * Register tinymce buttons
	 *
	 * @return void
	 */
	public function register_tinymce_buttons( $original, $editor_id ) {
		$original[] = 'table';
		return $original;
	}

	/**
	 * register new tinymce plugins.
	 *
	 * @param [type] $plugin_array
	 * @return void
	 */
	public function register_tinymce_plugin( $plugin_array ) {
		$plugin_array['table'] = plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . '/includes/lib/tinymce/table/plugin.min.js';
		return $plugin_array;
	}

	/**
	 * Enquiry form after submit btn.
	 *
	 * @return void
	 */
	function wte_enquiry_contact_form_after_submit_button() {

		?>
			<div class="row-repeater confirm-msg">
				<span class="success-msg"></span>
				<span class="failed-msg"></span>
			</div>
		<?php
	}

	/**
	 * Shows trips added to cart.
	 *
	 * @since    1.0.2
	 */
	function wte_cart_trips() {
		if ( isset( $_SESSION['cart_item'] ) && $_SESSION['cart_item'] != '' && isset( $_POST['trip-id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$cart_item = wte_clean( wp_unslash( $_SESSION['cart_item'] ) );
			unset( $cart_item[ $_POST['trip-id'] ] );
			$matches = array_unique( $cart_item );
			echo '<h2>' . esc_html__( 'Trips:', 'wp-travel-engine' ) . '</h2>';

			foreach ( $matches as $key => $value ) {
				$wp_travel_engine_trip_setting           = get_post_meta( $value, 'wp_travel_engine_setting', true );
				$wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', true );
				$cost                                    = $wp_travel_engine_trip_setting['trip_price'];
				$nonce                                   = wp_create_nonce( 'wte-remove-nonce' );
				?>
				<div class="wp-travel-engine-order-form-wrapper" id="wp-travel-engine-order-form-wrapper-<?php echo esc_attr( $value ); ?>"><a href="#" class="remove-from-cart" data-id="<?php echo esc_attr( $value ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>"></a>
					<div class="wp-travel-engine-order-left-column">
						<?php echo get_the_post_thumbnail( $value, 'medium', '' ); ?>
					</div>
					<div class="wp-travel-engine-order-right-column">
						<h3 class="trip-title"><?php echo esc_html( get_the_title( $value ) ); ?><input type="hidden" name="trips[]" value="<?php echo esc_attr( $value ); ?>"></h3>
						<ul class="trip-property">
							<li><span><?php esc_html_e( 'Start Date: ', 'wp-travel-engine' ); ?></span> <input type="text" min="1" class="wp-travel-engine-price-datetime" id="wp-travel-engine-trip-datetime-<?php echo esc_attr( $value ); ?>" name="trip-date[]" placeholder="<?php esc_html_e( 'Pick a date', 'wp-travel-engine' ); ?>"></li>
							<li class="trip-price-holder"><span><?php esc_html_e( 'Trip Price: ', 'wp-travel-engine' ); ?></span>
							<?php
							$code = 'USD';
							if ( isset( $wp_travel_engine_setting_option_setting['currency_code'] ) && $wp_travel_engine_setting_option_setting['currency_code'] != '' ) {
								$code = $wp_travel_engine_setting_option_setting['currency_code'];
							}
							$obj      = \wte_functions();
							$currency = $obj->wp_travel_engine_currencies_symbol( $code );
							echo esc_attr( $currency );
							echo '<span class="cart-price-holder">' . esc_attr( $obj->wp_travel_engine_price_format( $cost ) ) . '</span>';
							echo esc_attr( ' ' . $code );
							?>
							</li>
							<li><span><?php esc_html_e( 'Duration: ', 'wp-travel-engine' ); ?></span>
							<?php
							if ( isset( $wp_travel_engine_trip_setting['trip_duration'] ) && $wp_travel_engine_trip_setting['trip_duration'] != '' ) {
								echo (float) $wp_travel_engine_trip_setting['trip_duration'];
								if ( $wp_travel_engine_trip_setting['trip_duration'] > 1 ) {
									esc_html_e( ' Days', 'wp-travel-engine' );
								} else {
									esc_html_e( ' Day', 'wp-travel-engine' ); }
							}
							?>
							</li>
							<li><span>
							<?php
							$no_of_travelers = __( 'Number of Travellers: ', 'wp-travel-engine' );
							echo esc_html( apply_filters( 'wp_travel_engine_no_of_travelers_text', $no_of_travelers ) );
							?>
								<input type="number" min="1" name="travelers[]" class="travelers-number" value="" placeholder="0" required></span></li>
							<li class="cart-trip-total-price"><span><?php esc_html_e( 'Total: ', 'wp-travel-engine' ); ?></span><?php echo esc_attr( $currency ) . '<span class="cart-trip-total-price-holder">0</span>' . esc_attr( ' ' . $code ); ?></li>
						</ul>
					</div>
				</div>
				<?php

			}
		}
	}

	/**
	 * update cart button.
	 *
	 * @since    1.0.2
	 */
	function wte_update_cart() {
		?>
		<div class="wte-update-cart-button-wrapper">
			<div class="wte-update-cart-button">
				<input type="submit" name="submit" value="<?php echo esc_html__( 'Update cart', 'wp-travel-engine' ); ?>" class="wte-update-cart"/>
			</div>
			<div class="wte-update-cart-msg"></div>
		</div>
		<?php
	}

	/**
	 * update cart form.
	 *
	 * @since    1.0.2
	 */
	function wte_cart_form_wrapper() {
		echo '<form method="post" id="wp-travel-engine-cart-form" action="' . esc_url( admin_url( 'admin-ajax.php' ) ) . '">';
	}

	/**
	 * update cart form close.
	 *
	 * @since    1.0.2
	 */
	function wte_cart_form_close() {
		wp_nonce_field( 'update_cart_action_nonce', 'update_cart_action_nonce' );
		echo '</form>';
	}

	/**
	 * Callback function for remove to cart ajax.
	 *
	 * @since    1.0.0
	 */
	public static function wte_remove_from_cart() {
		if ( ! wte_nonce_verify( 'nonce', 'wte-remove-nonce' ) ) {
			die();
		}
		// phpcs:disable
		if ( isset( $_REQUEST['trip_id'] ) && isset( $_SESSION['cart_item'] ) ) {
			unset( $_SESSION['cart_item'][ $_REQUEST['trip_id'] ] );
			$result['type'] = 'success';
		} else {
			$result['type'] = 'error';
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			echo wp_json_encode( $result ); // phpcs:ignore
		} else {
			header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
		}

		die();
		// phpcs:enable
	}


	/**
	 * Callback function for update to cart ajax.
	 *
	 * @since    1.0.0
	 */
	public static function wte_ajax_update_cart() {
		if ( ! wte_nonce_verify( 'nonce', 'update_cart_action_nonce' ) ) {
			die();
		}
		$result['type'] = 'success';

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['data2'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			parse_str( wte_clean( wp_unslash( $_REQUEST['data2'] ) ), $values ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$cost = '';
			foreach ( $values['trips'] as $key => $value ) {
				$option = get_post_meta( $value, 'wp_travel_engine_setting', true );
				$cost   = $option['trip_price'];
				$cost  += $cost;
			}

			$travelers = '';
			foreach ( $values['travelers'] as $key => $value ) {
				$travelers  = $value;
				$travelers += $travelers;
			}
			$len = sizeof( $values['trips'] );

			for ( $i = 0; $i < $len; $i++ ) {
				$option = get_post_meta( $values['trips'][ $i ], 'wp_travel_engine_setting', true );
				$cost   = $option['trip_price'];
				$tc     = $tc + ( $cost * $values['travelers'][ $i ] );
			}

			$post = max( array_keys( $values['trips'] ) );
			$pid  = get_post( $values['trips'][ $post ] );
			$slug = $pid->post_title;
			$arr  = array(
				'place_order' => array(
					'travelers' => esc_attr( $travelers ),
					'trip-cost' => esc_attr( $tc ),
					'trip-id'   => esc_attr( end( $values['trips'] ) ),
					'tname'     => esc_attr( $slug ),
					'trip-date' => esc_attr( end( $values['trip-date'] ) ),
				),
			);
		} else {
			header( 'Location: ' . $_SERVER['HTTP_REFERER'] ); // phpcs:ignore
		}

		die();
	}

	// Ajax load more on activities
	public static function wpte_be_load_more_js() {
		global $wp_query;
		if ( ! isset( get_queried_object()->slug ) ) {
			return;
		}
		$cats              = str_replace( '/', ',', end( $wp_query->query ) );
		$wte_trip_cat_slug = get_queried_object()->slug;
		$args              = array(
			'nonce'        => wp_create_nonce( 'wpte-be-load-more-nonce' ),
			'url'          => admin_url( 'admin-ajax.php' ),
			'query'        => $cats,
			'slug'         => $wte_trip_cat_slug,
			'current_page' => isset( $_POST['page'] ) ? wte_clean( wp_unslash( $_POST['page'] ) ) : 1, // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			'max_page'     => $wp_query->max_num_pages,
		);
		// wp_enqueue_script( 'wpte-be-load-more', plugin_dir_url( __FILE__ ) . 'js/load-more.js', array( 'jquery' ), '1.0', true );
		wp_localize_script( self::$plugin_name, 'beloadmore', $args );
	}
	/**
	 * AJAX Load More
	 */
	public static function wpte_ajax_load_more() {
		check_ajax_referer( 'wpte-be-load-more-nonce', 'nonce' );

		// Prepare our arguments for the query.
		$args                = wte_clean( json_decode( wp_unslash( $_POST['query'] ), true ) );
		$args                = wte_clean( $args );
		$args['paged']       = wte_clean( wp_unslash( $_POST['page'] ) ) + 1; // We need next page to be loaded.
		$args['post_status'] = 'publish';

		$query = new WP_Query( $args );
		ob_start();

		while ( $query->have_posts() ) :
			$query->the_post();
			$details = wte_get_trip_details( get_the_ID() );
			wte_get_template( 'content-grid.php', $details );
		endwhile;
		wp_reset_postdata();

		$output = ob_get_contents();
		ob_end_clean();
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		wp_reset_query();
		exit();
	}

	/**
	 * AJAX Load More Destination
	 */
	public static function wpte_ajax_load_more_destination() {
		check_ajax_referer( 'wpte-be-load-more-nonce', 'nonce' );

		// prepare our arguments for the query
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		$args = json_decode( wte_clean( wp_unslash( $_POST['query'] ) ), true );
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		$args['paged']       = wte_clean( wp_unslash( $_POST['page'] ) ) + 1; // we need next page to be loaded
		$args['post_status'] = 'publish';

		$query = new WP_Query( $args );
		ob_start();

		// $view_mode = $_POST['mode'];

		while ( $query->have_posts() ) :
			$query->the_post();
			$details = wte_get_trip_details( get_the_ID() );
			wte_get_template( 'content-grid.php', $details );
			endwhile;
			wp_reset_postdata();

		$output = ob_get_contents();
		ob_end_clean();
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		wp_reset_query();
		exit();
	}

	function wte_paypal_add_option( $options ) {
		$options[] = 'PayPal';
		return $options;
	}

	function wte_test_add_option( $options ) {
		$options[] = 'Test Payment';
		return $options;
	}

	function do_output_buffer() {
			ob_start();
	}

	public static function wte_payment_gateway() {

		$post_data = wp_clean( wp_unslash( $_POST ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( in_array( $post_data['val'], array( 'Test Payment', 'PayPal' ), true ) ) {
			ob_start();
			$obj             = \wte_functions();
			$billing_options = $obj->order_form_billing_options();
			foreach ( $billing_options as $key => $value ) {
				?>
				<div class='wp-travel-engine-billing-details-field-wrap'>
						<?php
						if ( in_array( $key, array( 'fname', 'lname', 'email', 'passport', 'password', 'address', 'country' ), true ) ) {
							if ( 'country' === $key ) {
								?>
								<label for="<?php echo esc_attr( $key ); ?>"><?php esc_html( $value['label'] ); ?><span class="required">*</span></label>
								<select required id="<?php echo esc_attr( $key ); ?>" name="wp_travel_engine_booking_setting[place_order][booking][<?php echo esc_attr( $key ); ?>]" data-placeholder="<?php esc_attr_e( 'Choose a field type&hellip;', 'wp-travel-engine' ); ?>" class="wc-enhanced-select" >
										<option value=" "><?php esc_html_e( 'Choose country&hellip;', 'wp-travel-engine' ); ?></option>
										<?php
										$obj     = \wte_functions();
										$options = $obj->wp_travel_engine_country_list();
										foreach ( $options as $key => $val ) {
											echo '<option value="' . ( ! empty( $val ) ? esc_attr( $val ) : 'Please select' ) . '">' . esc_html( $val ) . '</option>';
										}
										?>
								</select>
								<?php
							}
							?>
							<label for="wp_travel_engine_booking_setting[place_order][booking][<?php echo esc_attr( $key ); ?>]"><?php echo esc_html( $value['label'] ); ?><span class="required">*</span></label>
							<input type="<?php echo esc_attr( $value['type'] ); ?>"
								name="wp_travel_engine_booking_setting[place_order][booking][<?php echo esc_attr( $key ); ?>]"
								id="wp_travel_engine_booking_setting[place_order][booking][<?php echo esc_attr( $key ); ?>]"
								<?php ( +$value['required'] === 1 ) && print 'required'; ?>/>
							<?php
						}
						?>
				</div>
				<?php
			}
			wp_reset_postdata();
			$data = ob_get_clean();
			wp_send_json_success( $data );
		}
	}

	/**
	 * Check min max pax
	 *
	 * @return void
	 */
	function check_min_max_pax( $trip_id, $attrs ) {

		if ( empty( $attrs['price_key'] ) ) {
			WTE()->notices->add( __( 'Invalid Package.', 'wp-travel-engine' ) );
			return;
		}
		$package_key = $attrs['price_key'];

		$package = get_post( $package_key );

		$package_pricings = isset( $package->{'package-categories'} ) ? $package->{'package-categories'} : array();

		$pax = isset( $attrs['pax'] ) ? $attrs['pax'] : array();

		if ( empty( $attrs['pax'] ) ) {
			WTE()->notices->add( __( 'Number of pax is not eligible for booking. Number of pax should be at least 1 for the package.', 'wp-travel-engine' ) );
			return;
		}

		foreach ( $pax as $pricing_category_id => $pax_count ) {
			if ( isset( $package_pricings['min_paxes'][ $pricing_category_id ] ) ) {
				if ( $pax_count < (int) $package_pricings['min_paxes'][ $pricing_category_id ] ) {
					WTE()->notices->add( sprintf( __( 'Number of pax (%1$s) is not eligible for booking. Number of %2$s should be at least %3$s for the package.', 'wp-travel-engine' ), $pax_count, $attrs['category_info'][ $pricing_category_id ]['label'], $package_pricings['min_paxes'][ $pricing_category_id ] ), 'error' );
					return;
				}
			}
			if ( isset( $package_pricings['max_paxes'][ $pricing_category_id ] ) && '' !== $package_pricings['max_paxes'][ $pricing_category_id ] ) {
				if ( $pax_count > (int) $package_pricings['max_paxes'][ $pricing_category_id ] ) {
					WTE()->notices->add( sprintf( __( 'Number of pax (%1$s) is not eligible for booking. Number of %2$s should be at most %3$s for the package.', 'wp-travel-engine' ), $pax_count, $attrs['category_info'][ $pricing_category_id ]['label'], $package_pricings['max_paxes'][ $pricing_category_id ] ), 'error' );
					return;
				}
			}
		}

		// $travellers = absint( array_sum( $pax ) );

		// $post_meta = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );

		// $min_max_enable = isset( $post_meta['minmax_pax_enable'] ) ? true : false;

		// if ( $min_max_enable ) {
		// $min_pax = isset( $post_meta['trip_minimum_pax'] ) && ! empty( $post_meta['trip_minimum_pax'] ) ? $post_meta['trip_minimum_pax'] : 1;

		// $max_pax = isset( $post_meta['trip_maximum_pax'] ) && ! empty( $post_meta['trip_maximum_pax'] ) ? $post_meta['trip_maximum_pax'] : 99999999999999;

		// if ( ( $travellers < $min_pax ) || ( $travellers > $max_pax ) ) {
		// WTE()->notices->add( sprintf( __( 'Number of pax (%1$s) is not eligible for booking. Please choose travellers number between %2$s and %3$s for this trip.', 'wp-travel-engine' ), $travellers, $min_pax, $max_pax ), 'error' );
		// }
		// }
	}

}
