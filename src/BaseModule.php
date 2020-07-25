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
	private array $settings = [];

	/**
	 * Instantiate module.
	 */
	public function construct()
	{
		$this->registerSettings();
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

}
