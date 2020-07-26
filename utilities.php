<?php
/**
 * Utility functions for Tom's WordPress Tools.
 *
 * @package TomsWordPressTools
 * @since 1.0.0
 */

/**
 * Get the plugin main file name.
 *
 * @return string
 */
function twpt_get_plugin_file(): string
{
	return trailingslashit(__DIR__) . 'toms-wordpress-tools.php';
}

/**
 * Get the plugin version.
 *
 * @return string|null
 */
function twpt_get_plugin_version()
{
	if (!function_exists('get_plugin_data')) {
		require_once(ABSPATH . 'wp-admin/includes/plugin.php');
	}

	$plugin = get_plugin_data(twpt_get_plugin_file(), false, false);

	return $plugin['Version'] ?? null;
}
