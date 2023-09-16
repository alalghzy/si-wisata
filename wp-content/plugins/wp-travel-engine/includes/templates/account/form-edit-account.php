<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/account/form-edit-account.php.
 *
 * HOWEVER, on occasion WP Travel will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://wptravelengine.com
 * @author  WP Travel Engine
 * @package WP Travel Engine/includes/templates
 * @version 1.3.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'wp_travel_engine_before_edit_account_form' );
$current_user       = wp_get_current_user();
$users_meta         = get_user_meta( $current_user->ID, 'wte_users_meta', true );
$user_custom_avatar = isset( $users_meta['user_profile_image_id'] ) && $users_meta['user_profile_image_id'] ? $users_meta['user_profile_image_id'] : '';
$users_custom_image = isset( $users_meta['user_profile_image_id'] ) && $users_meta['user_profile_image_id'] ? 'custom-image' : '';
if ( ! empty( $current_user ) ) :

	if ( isset( $users_meta['user_profile_image_id'] ) && $users_meta['user_profile_image_id'] && wp_attachment_is_image( $users_meta['user_profile_image_id'] ) ) :
		$user_profile_image     = wp_get_attachment_image( $users_meta['user_profile_image_id'], 'thumbnail' );
		$user_profile_image_src = wp_get_attachment_image_src( $users_meta['user_profile_image_id'], 'thumbnail' );
		$user_profile_image_src = ( isset( $user_profile_image_src[0] ) && $user_profile_image_src[0] ? $user_profile_image_src[0] : false );
	else :
		$user_profile_image     = get_avatar( $current_user->user_email, 'thumbnail' );
		$user_profile_image_src = get_avatar_url( $current_user->user_email, 'thumbnail' );
	endif;

endif;
?>

<form method="post" class="wpte-lrf-form" id="user-dashboard-account-form">

	<?php do_action( 'wp_travel_engine_edit_account_form_start' ); ?>
	<div class="wpte-lrf-field">
	<div class="wte-input-upload-file">
		<div id="profile-img" class="wte-profile-img-holder dropzone">
			<input type="hidden" name="user_profile_image" value="<?php echo esc_attr( $users_custom_image ); ?>">
			<input type="hidden" name="user_profile_image_url" value="<?php echo esc_attr( $user_profile_image_src ); ?>">
			<input type="hidden" name="user_profile_image_nonce" value="<?php echo wp_create_nonce( 'wte-user-profile-image-nonce' ); ?>">
			<div class="img">
				<?php
				if ( $user_custom_avatar ) :
					$image_thumb = wp_get_attachment_image( $user_custom_avatar, 'thumbnail' );
					echo wp_kses_post( $image_thumb );
					else :
						echo get_avatar( $current_user->user_email );
					endif;
					?>

			</div>
			<div class="wpte-img-upload-icon dropzone">
				<?php wptravelengine_svg_by_fa_icon( 'fas fa-camera' ); ?>
			</div>
			<div class="wte-profile-btns">
				<button type="button" class="wte-profile-img-delete" style="<?php echo $user_custom_avatar ? 'display:block' : 'display:none'; ?>">X</button>
			</div>

		</div>
	</div>
	</div>
	<div class="wpte-lrf-field lrf-text">
		<label class="lrf-field-label" for="lrf-first-name"><?php esc_html_e( 'First Name:', 'wp-travel-engine' ); ?><span class="required">*</span> </label>
		<input type="text" name="account_first_name" id="lrf-first-name" required="1"  data-msg="Please enter your first name" data-parsley-required-message="Please enter your first name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</div>

	<div class="wpte-lrf-field lrf-text">
		<label class="lrf-field-label" for="lrf-last-name"><?php esc_html_e( 'Last Name:', 'wp-travel-engine' ); ?><span class="required">*</span></label>
		<input type="text" name="account_last_name" id="lrf-last-name" required="1"  data-msg="Please enter your last name" data-parsley-required-message="Please enter your last name"value="<?php echo esc_attr( $user->last_name ); ?>" />
	</div>

	<div class="wpte-lrf-field lrf-email">
		<label class="lrf-field-label" for="lrf-email"><?php esc_html_e( 'Email:', 'wp-travel-engine' ); ?><span class="required">*</span></label>
		<input type="email" name="account_email" id="lrf-email" required="1"  data-msg="Please enter your email address" data-parsley-required-message="Please enter your email address" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</div>

	<div class="wpte-lrf-field lrf-toggle">
		<label class="lrf-field-label"><?php esc_html_e( 'Change Password:', 'wp-travel-engine' ); ?></label>
		<label class="lrf-toggle-box" for="lrf-change-password">
			<span class="lrf-chkbx-txt"><?php esc_html_e( 'On', 'wp-travel-engine' ); ?></span>
			<span class="lrf-chkbx-txt"><?php esc_html_e( 'Off', 'wp-travel-engine' ); ?></span>
		</label>
	</div>

	<div class="wpte-lrf-popup">
		<div class="wpte-lrf-field lrf-text">
			<label class="lrf-field-label" for="lrf-current-password"><?php esc_html_e( 'Current Password:', 'wp-travel-engine' ); ?><span class="required">*</span> </label>
			<input type="password" name="password_current" id="lrf-current-password" />
		</div>

		<div class="wpte-lrf-field lrf-text">
			<label class="lrf-field-label" for="lrf-new-password"><?php esc_html_e( 'New Password:', 'wp-travel-engine' ); ?> <span class="required">*</span></label>
			<input type="password" name="password_1" id="lrf-new-password" />
			<span class="lrf-tooltip"><?php esc_html_e( 'Leave blank if you do not want to change password.', 'wp-travel-engine' ); ?></span>
		</div>

		<div class="wpte-lrf-field lrf-text">
			<label class="lrf-field-label" for="lrf-confirm-new-password"><?php esc_html_e( 'Confirm New Password:', 'wp-travel-engine' ); ?><span class="required">*</span> </label>
			<input type="password" name="password_2" id="lrf-confirm-new-password" />
			<span class="lrf-tooltip"><?php esc_html_e( 'Leave blank if you do not want to change password.', 'wp-travel-engine' ); ?></span>
		</div>
	</div>

	<?php
	do_action( 'wp_travel_engine_edit_account_form' );

		wp_nonce_field( 'wp_travel_engine_save_account_details', 'wp_account_details_security' );
	?>
		<div class="wpte-lrf-field lrf-submit">
			<input type="submit" class="wpte-lrf-btn" name="wp_travel_engine_save_account_details" value="<?php esc_attr_e( 'Save changes', 'wp-travel-engine' ); ?>">
		</div>
		<input type="hidden" name="action" value="wp_travel_engine_save_account_details" />

	<?php do_action( 'wp_travel_engine_edit_account_form_end' ); ?>

</form>
<?php
do_action( 'wp_travel_engine_after_edit_account_form' );
