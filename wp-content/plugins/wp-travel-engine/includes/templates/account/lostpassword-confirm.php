<?php
/**
 * Lost password confirmation text.
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/account/lost-password-confirmation.php.
 *
 * HOWEVER, on occasion WP Travel Engine will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Print Errors / Notices.
wp_travel_engine_print_notices();
$settings                      = wp_travel_engine_get_settings();
$wp_travel_engine_dashboard_id = isset( $settings['pages']['wp_travel_engine_dashboard_page'] ) ? esc_attr( $settings['pages']['wp_travel_engine_dashboard_page'] ) : wp_travel_engine_get_page_id( 'my-account' );
?>
<p class="col-xs-12 wp-travel-notice-success wp-travel-notice"><?php echo esc_html( apply_filters( 'wp_travel_lost_password_message', __( 'A password reset email has been sent to the email address for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset. Go back to ', 'wp-travel-engine' ) ) ); ?>
<a href="<?php echo esc_url( get_permalink( $wp_travel_engine_dashboard_id ) ); ?>"><?php echo esc_html( 'login page.', 'wp-travel-engine' ); ?></a></p>
<?php
