<?php

namespace TomsWordPressTools\Modules\CookieBanner;

use TomsWordPressTools\BaseModule;

/**
 * Cookie banner module.
 *
 * @package TomsWordPressTools\Modules\CookieBanner
 */
class Module extends BaseModule
{

	/**
	 * Instantiate module.
	 */
	public function __construct()
	{
		add_action('wp_footer', [$this, 'output_banner']);

		$this->addSetting('cookie_banner_message', __('Custom Cookie Banner Message', 'toms-wordpress-tools'), 'reading', 'textarea', 'wp_kses_data');
		$this->addScript('cookie-banner', plugin_dir_url(__FILE__) . 'assets/js/cookie-banner.js', [], false);
		$this->addStyle('cookie-banner', plugin_dir_url(__FILE__) . 'assets/css/cookie-banner.css');

		parent::construct();
	}

	/**
	 * Output banner HTML.
	 */
	public function output_banner()
	{
		$custom_message = get_option('ts_cookie_banner_message');
		$privacy_policy = get_option('wp_page_for_privacy_policy');

		if ($privacy_policy): ?>

			<div class="ts-cookie-banner ts-hidden">
				<p><?php echo $custom_message ? $custom_message : sprintf(__('We use cookies and similar technologies on our website to help us understand how you use it and how we can improve our services. For more information, please read our <a href="%s">privacy policy</a>.', 'toms-wordpress-tools'), get_the_permalink($privacy_policy)); ?></p>

				<button class="ts-cookie-banner-close"><?php _e('Close', 'toms-wordpress-tools'); ?></button>
			</div>

		<?php endif;
	}
}
