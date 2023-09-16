<?php
/**
 * WP Travel Engine - Addons updater.
 */

defined( 'ABSPATH' ) || exit;
/**
 * Pre upgrade notices for Core Plugin.
 *
 * @since 5.0.0
 */
require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'admin/plugin-updates/core-pre-update.php';
/**
 * Extensions Updater.
 */
require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'admin/plugin-updates/extensions-update.php';
