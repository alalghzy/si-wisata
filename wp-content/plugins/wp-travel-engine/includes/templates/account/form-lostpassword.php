<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/account/form-lostpassword.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_enqueue_script( "parsely" );

// Notices.
wp_travel_engine_print_notices();
?>
<div class="wpte-lrf-wrap wpte-forgot-pass">
	<div class="wpte-lrf-top">
		<form method="post" class="wpte-lrf">
			<div class="wpte-lrf-left">
				<div class="wpte-lrf-head">
					<h2 class="wpte-lrf-title">
						<?php esc_html_e('Reset your password', 'wp-travel-engine'); ?>
					</h2>
					<div class="wpte-lrf-desc">
						<p><?php echo apply_filters( 'wp_travel_engine_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'wp-travel-engine' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>
					</div>
				</div>
			</div>
			<span class="wpte-spacer"></span>
			<div class="wpte-lrf-right">
				<div class="wpte-lrf-field lrf-email">
					<label for="username"><?php echo esc_attr__( 'Email or username', 'wp-travel-engine' ); ?><span class="required">*</span></label>
					<input id="username" required type="text" name="user_login" id="user_login" value="" placeholder="<?php echo esc_attr__( 'Email or username', 'wp-travel-engine' ); ?>">
				</div>
				<?php do_action( 'wp_travel_engine_lostpassword_form' ); ?>
				<input type="hidden" name="wp_travel_engine_reset_password" value="true" />
				<div class="wpte-lrf-field lrf-submit">
					<input type="submit" name="wp_travel_engine_reset_password_submit" value="<?php echo esc_attr__( 'Reset Password', 'wp-travel-engine' ); ?>">
				</div>
				<?php wp_nonce_field( 'wp_travel_engine_lost_password' ); ?>
			</div>
		</form>
	</div>
</div>
<?php
