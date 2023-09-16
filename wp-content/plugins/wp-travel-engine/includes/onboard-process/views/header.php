<?php
/**
 * Setup wizard header template.
 *
 * @package    WP_Travel_Engine
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta name="viewport" content="width=device-width"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php esc_html_e( 'WP Travel Engine - User Onboarding', 'wp-travel-engine' ); ?></title>
	<?php wp_print_head_scripts(); ?>
	<?php wp_print_styles( 'wpte-user-onboarding' ); ?>
	<?php wp_print_styles( 'wpte-user-onboarding-core' ); ?>
</head>
<body class="wte-user-onboarding-wizard wpte-activated wte-user-onboarding-wizard-body-<?php echo is_rtl() ? ' rtl' : ''; ?>">
