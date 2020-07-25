<?php
namespace TomsWordPressTools\Modules\Analytics;

use TomsWordPressTools\BaseModule;

/**
 * Analytics module.
 *
 * @package TomsWordPressTools\Modules\Analytics
 * @since 1.0.0
 */
class Module extends BaseModule
{
	/**
	 * Module constructor.
	 */
	public function __construct()
	{
		$this->addSetting( 'google_tracking_id', __( 'Google Analytics Tracking ID', 'toms-wordpress-tools' ), 'general', 'string', 'sanitize_text_field' );

		parent::construct();

		add_action( 'wp_head', [$this, 'head'], 0 );
	}

	/**
	 * Output Google Analytics code in the head.
	 */
	public function head()
	{
		$id = esc_attr( get_option( 'ts_google_tracking_id' ) );

		if( $id ) { ?>
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $id; ?>"></script>
			<script>
				window.dataLayer = window.dataLayer || [];
				function gtag(){dataLayer.push(arguments);}
				gtag('js', new Date());

				gtag('config', '<?php echo $id; ?>');
			</script>
		<?php }
	}
}
