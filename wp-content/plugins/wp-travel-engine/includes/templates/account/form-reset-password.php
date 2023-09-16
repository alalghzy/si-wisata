<?php
/**
 * Customer Lost Password Reset Form.
 *
 * This template can be overridden by copying it to yourtheme/wp-travel/account/form-reset-password.php.
 *
 * HOWEVER, on occasion wp-travel will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see         http://wptravelengine.com
 * @author      Wp Travel Engine
 * @package     wp-travel/Templates
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_enqueue_script( "parsely" );

// Print Errors / Notices.
wp_travel_engine_print_notices(); ?>
<div class="wpte-lrf-wrap wpte-reset-pass">
	<div class="wpte-lrf-top">
		<div class="wpte-lrf-head">
			<?php if ( has_custom_logo() ) : ?>
				<div class="wpte-lrf-logo">
					<?php the_custom_logo(); ?>
				</div>
			<?php endif; ?>
			<div class="wpte-lrf-desc">
				<p><?php echo apply_filters( 'wp_travel_engine_reset_password_message', esc_html__( 'Enter your new password below.', 'wp-travel-engine' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>
			</div>
		</div>
		<form method="post" class="wpte-lrf">
			<div class="wpte-lrf-field lrf-email">
				<input required name="password_1" id="password_1" type="password" placeholder="<?php esc_html_e( 'New password', 'wp-travel-engine' ); ?>">
			</div>
			<div class="wpte-lrf-field lrf-email">
				<input required name="password_2" id="password_2" type="password" placeholder="<?php esc_html_e( 'Re-enter new password', 'wp-travel-engine' ); ?>">
			</div>

			<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
			<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />

			<?php do_action( 'wp_travel_resetpassword_form' ); ?>

			<div class="wpte-lrf-field lrf-submit">
				<input type="hidden" name="wp_travel_engine_reset_password" value="true" />
				<input type="submit" name="wp_travel_engine_reset_password_submit"value="<?php esc_attr_e( 'Save', 'wp-travel-engine' ); ?>">
			</div>
			<?php wp_nonce_field( 'wp_travel_engine_reset_password_nonce' ); ?>
		</form>
	</div>
</div>
<?php
