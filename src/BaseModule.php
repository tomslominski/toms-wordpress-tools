<?php

namespace TomsWordPressTools;

/**
 * Base module containing helper functions for actual modules to use.
 *
 * @package TomsWordPressTools
 * @since 1.0.0
 */
class BaseModule
{

	/**
	 * @var array Registered settings for module.
	 */
	private $settings = [];

	/**
	 * @var array Registered scripts for module.
	 */
	private $scripts = [];

	/**
	 * @var array Registered styles for module.
	 */
	private $styles = [];

	/**
	 * Instantiate module.
	 */
	public function construct()
	{
		$this->registerSettings();
		$this->enqueueScripts();
		$this->enqueueStyles();
	}

	/**
	 * Register admin panel setting.
	 *
	 * @param string $id Alphanumeric setting ID.
	 * @param string $name User-visible setting name.
	 * @param string $group Settings page slug.
	 * @param string $type Setting value type.
	 * @param callable $sanitizer Sanitizing function.
	 * @param string $default Default value.
	 */
	public function addSetting(string $id, string $name, string $group, string $type, callable $sanitizer, $default = '')
	{
		$id = 'ts_' . $id;
		$value = get_option($id, $default);

		$this->settings[$id] = [
			'id' => $id,
			'name' => $name,
			'group' => $group,
			'type' => $type,
			'sanitizer' => $sanitizer,
			'default' => $default,
			'value' => $value,
		];
	}

	/**
	 * Register settings with WordPress.
	 */
	public function registerSettings()
	{
		if ($this->settings) {
			add_action('admin_init', function () {
				foreach ($this->settings as $setting) {
					register_setting($setting['group'], $setting['id'], [
						'type' => $setting['type'],
						'sanitize_callback' => $setting['sanitizer'],
						'show_in_rest' => false,
						'default' => $setting['default'],
					]);

					add_settings_field(
						$setting['id'],
						$setting['name'],
						[$this, 'displayField'],
						$setting['group'],
						'default',
						[
							'label_for' => $setting['id'],
							'ts_setting' => $setting,
						]
					);
				}
			});
		}
	}

	/**
	 * Display settings field.
	 *
	 * @param array $args Field settings.
	 */
	public function displayField(array $args)
	{
		$setting = $args['ts_setting'];
		?>

		<input name="<?php echo esc_attr($setting['id']); ?>" type="text" id="<?php echo esc_attr($setting['id']); ?>"
			   value="<?php echo esc_attr($setting['value']); ?>" class="regular-text">

		<?php
	}

	/**
	 * Enqueue front-end script.
	 *
	 * @param string $id Alphanumeric script ID.
	 * @param string $src Script source.
	 * @param array $deps Script dependencies.
	 * @param bool $async Whether to load the script asynchronously.
	 */
	public function addScript(string $id, string $src, array $deps = [], bool $async = true)
	{
		$this->scripts[$id] = [
			'id' => $id,
			'src' => $src,
			'deps' => $deps,
			'async' => $async,
		];
	}

	/**
	 * Enqueue registered scripts.
	 */
	public function enqueueScripts()
	{
		if ($this->scripts) {
			add_action('wp_enqueue_scripts', function () {
				foreach ($this->scripts as $script) {
					wp_enqueue_script($script['id'], $script['src'], $script['deps'], twpt_get_plugin_version(), true);
				}
			});

			$this->asyncScripts();
		}
	}

	/**
	 * Add async attribute to registered scripts.
	 */
	public function asyncScripts()
	{
		add_filter('script_loader_tag', function ($tag, $id) {
			if ($this->scripts[$id]['async'] ?? null) {
				if (false === stripos($tag, 'async')) {
					$tag = str_replace(' src', ' async="async" src', $tag);
				}
			}

			return $tag;
		}, 10, 2);
	}

	/**
	 * Enqueue front-end styles.
	 *
	 * @param string $id Alphanumeric style ID.
	 * @param string $src Style source.
	 * @param array $deps Style dependencies.
	 */
	public function addStyle(string $id, string $src, array $deps = [])
	{
		$this->styles[$id] = [
			'id' => $id,
			'src' => $src,
			'deps' => $deps,
		];
	}

	/**
	 * Enqueue registered stylesheets.
	 */
	public function enqueueStyles()
	{
		if ($this->styles) {
			add_action('wp_enqueue_scripts', function () {
				foreach ($this->styles as $style) {
					wp_enqueue_style($style['id'], $style['src'], $style['deps'], twpt_get_plugin_version());
				}
			});
		}
	}

}
