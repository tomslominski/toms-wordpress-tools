<?php

namespace TomsWordPressTools\Modules\Essentials;

use TomsWordPressTools\BaseModule;

/**
 * Essentials module.
 *
 * @package TomsWordPressTools\Modules\Essentials
 */
class Module extends BaseModule
{
	/**
	 * Instantiate parts of module.
	 */
	public function __construct()
	{
		$this->disable_default_theme_site_status();
		$this->add_plugins_to_admin_bar();
		$this->disable_post_tags();
		$this->enable_svg_support();
		$this->disable_auto_updates();
		$this->disable_image_title_attr();

		parent::construct();
	}

	/**
	 * Disable the "Have a default theme available" warning under Site Status.
	 */
	public function disable_default_theme_site_status()
	{
		if (apply_filters('TomsWordPressTools/disable_default_theme_site_status', true)) {
			add_filter('site_status_test_result', function ($result) {
				if ('theme_version' === $result['test'] && __('Have a default theme available') === $result['label']) {
					$result['status'] = 'good';

					$result['label'] = __('Your themes are all up to date');

					$result['description'] = sprintf(
						'<p>%s</p>',
						__('Themes add your site&#8217;s look and feel. It&#8217;s important to keep them up to date, to stay consistent with your brand and keep your site secure.')
					);
				}

				return $result;
			});
		}
	}

	/**
	 * Add Plugins link to site name admin bar menu.
	 */
	public function add_plugins_to_admin_bar()
	{
		if (apply_filters('TomsWordPressTools/add_plugins_to_admin_bar', true)) {
			add_action('admin_bar_menu', function (\WP_Admin_Bar $admin_bar) {
				if (!is_user_logged_in() || !current_user_can('activate_plugins') || is_admin()) {
					return;
				}

				$admin_bar->add_menu([
					'id' => 'ts-plugins',
					'parent' => 'appearance',
					'title' => __('Plugins', 'toms-wordpress-tools'),
					'href' => admin_url('plugins.php'),
				]);
			}, 100);
		}
	}

	/**
	 * Disable post tag taxonomy.
	 */
	public function disable_post_tags()
	{
		if (apply_filters('TomsWordPressTools/disable_post_tags', true)) {
			unregister_taxonomy_for_object_type('post_tag', 'post');
		}
	}

	/**
	 * Enable SVG support on non-Elementor sites.
	 */
	public function enable_svg_support()
	{
		add_filter('upload_mimes', function ($mimes) {
			if (current_user_can('manage_options')) {
				$mimes['svg'] = 'image/svg+xml';
			}

			return $mimes;
		});
	}

	/**
	 * Disable theme and plugin auto-update UI.
	 */
	public function disable_auto_updates()
	{
		if (apply_filters('TomsWordPressTools/disable_auto_updates', true)) {
			add_filter('plugins_auto_update_enabled', '__return_false');
			add_filter('themes_auto_update_enabled', '__return_false');
		}
	}

	/**
	 * Disable the image title attribute being automatically set on image upload.
	 */
	public function disable_image_title_attr()
	{
		if (apply_filters('TomsWordPressTools/disable_image_title_attr', true)) {
			add_filter('wp_insert_attachment_data', function (array $data, array $post_array) {
				if (
					empty($post_array['ID'])
					&& isset($post_array['post_mime_type'])
					&& wp_match_mime_types('image', $post_array['post_mime_type'])
				) {
					$data['post_title'] = '';
				}

				return $data;
			}, 10, 2);
		}
	}
}
