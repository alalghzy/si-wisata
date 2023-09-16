<?php
/**
 * Customer Lost Password email Template
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/emails/customer-lost-password.php.
 *
 * HOWEVER, on occasion WP Travel Engine will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see         http://wptravelengine.com
 * @author      Wp Travel Engine
 * @package     wp-travel-engine/includes/templates
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Set Login Data and Reset Keys.
$user_login = $args['user_login'];
$reset_key  = $args['reset_key'];
?>
<p><?php esc_html_e( 'Someone requested that the password be reset for the following account:', 'wp-travel-engine' ); ?></p>
<p><?php printf( __( 'Username: %s', 'wp-travel-engine' ), esc_html( $user_login ) ); ?></p>
<p><?php esc_html_e( 'If this was a mistake, just ignore this email and nothing will happen.', 'wp-travel-engine' ); ?></p>
<p><?php esc_html_e( 'To reset your password, visit the following address:', 'wp-travel-engine' ); ?></p>
<p>
	<a class="link" href="
	<?php
	echo esc_url(
		add_query_arg(
			array(
				'key'   => $reset_key,
				'login' => rawurlencode( $user_login ),
			),
			wp_travel_engine_lostpassword_url()
		)
	);
	?>
	">
	<?php esc_html_e( 'Click here to reset your password', 'wp-travel-engine' ); ?></a>
</p>
<p><?php esc_html_e( 'Powered by', 'wp-travel-engine' ); ?><a href="http://wptravelengine.com" target="_blank"> <?php esc_html_e( 'WP Travel Engine', 'wp-travel-engine' ); ?></a></p>
<p></p>
