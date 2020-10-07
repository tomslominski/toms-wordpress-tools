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
}
