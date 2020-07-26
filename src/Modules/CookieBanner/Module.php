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

		$this->addScript('cookie-banner', plugin_dir_url(__FILE__) . 'assets/js/cookie-banner.js');
		$this->addStyle('cookie-banner', plugin_dir_url(__FILE__) . 'assets/css/cookie-banner.css');

		parent::construct();
	}

	/**
	 * Output banner HTML.
	 */
	public function output_banner()
	{
		if ($privacy_policy = get_option('page_for_privacy_policy')): ?>

			<div class="ts-cookie-banner ts-hidden">
				<p><?php printf(__('We use cookies and similar technologies on our website to help us understand how you use it and how we can improve our services. For more information, please read our <a href="%s">privacy policy</a>.', 'toms-wordpress-tools'), get_the_permalink($privacy_policy)); ?></p>

				<button class="ts-cookie-banner-close"><?php _e('Close', 'toms-wordpress-tools'); ?></button>
			</div>

		<?php endif;
	}
}
