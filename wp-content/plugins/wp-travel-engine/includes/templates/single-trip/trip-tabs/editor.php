<?php
/**
 * Generic Template for custom tabs
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/trip-tabs/editor.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php do_action( "wte_before_{$name}_content" ); ?>

<?php echo "<div class='post-data " . esc_attr( $name ) . "'>"; ?>

<?php
	/**
	 * Hook - Display tab content title, left for themes.
	 */
	do_action( "wte_{$name}_tab_title" );

	/**
	 * Hook - Display tab content title, left for themes.
	 */
	do_action( 'wte_custom_t_tab_title', $id );
?>
	<!-- Display wp_editor content -->
	<?php if ( ! empty( $editor ) ) : ?>
		<?php
		echo apply_filters(
			'the_content',
			html_entity_decode( $editor, 3, 'UTF-8' )
		);
		?>
	<?php endif; ?>
	<!-- ./ Display wp_editor content -->

<?php echo '</div>'; ?>

<?php
do_action( "wte_after_{$name}_content" );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
