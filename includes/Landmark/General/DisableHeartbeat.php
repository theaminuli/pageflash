<?php
/**
 * Disable Heartbeat Feature
 *
 * @package TheAminul\PageFlash\Landmark\General
 * @since 1.2.0
 */

namespace TheAminul\PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class DisableHeartbeat
 *
 * Controls WordPress Heartbeat API for improved performance.
 *
 * @since 1.2.0
 */
class DisableHeartbeat {

	/**
	 * Heartbeat behavior
	 *
	 * @since 1.2.0
	 * @var string
	 */
	private $behavior;

	/**
	 * Heartbeat frequency
	 *
	 * @since 1.2.0
	 * @var int
	 */
	private $frequency;

	/**
	 * Constructor
	 *
	 * @since 1.2.0
	 * @param string $behavior  Heartbeat behavior (disable_everywhere, allow_posts, disable_dashboard).
	 * @param int    $frequency Heartbeat frequency in seconds.
	 */
	public function __construct( $behavior = 'disable_everywhere', $frequency = 60 ) {
		$this->behavior  = $behavior;
		$this->frequency = $frequency;

		add_action( 'init', array( $this, 'disable_heartbeat' ), 1 );
		add_filter( 'heartbeat_settings', array( $this, 'set_heartbeat_frequency' ) );
	}

	/**
	 * Disable heartbeat based on behavior and page exceptions
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function disable_heartbeat() {
		if ( is_admin() ) {
			global $pagenow;

			// Exception pages
			if ( 'admin.php' === $pagenow && ! empty( $_GET['page'] ) ) {
				$exceptions = array(
					'gf_edit_forms',
					'gf_entries',
					'gf_settings',
				);
				if ( in_array( $_GET['page'], $exceptions, true ) ) {
					return;
				}
			}

			// Site Health check
			if ( 'site-health.php' === $pagenow ) {
				return;
			}
		}

		$this->replace_heartbeat();
	}

	/**
	 * Replace/deregister heartbeat
	 *
	 * @since 1.2.0
	 * @return void
	 */
	private function replace_heartbeat() {
		global $pagenow;

		switch ( $this->behavior ) {
			case 'disable_everywhere':
				wp_deregister_script( 'heartbeat' );
				break;

			case 'allow_posts':
				if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
					wp_deregister_script( 'heartbeat' );
				}
				break;

			case 'disable_dashboard':
				if ( 'index.php' === $pagenow ) {
					wp_deregister_script( 'heartbeat' );
				}
				break;
		}

		// Optional: replace with custom heartbeat script in admin
		if ( is_admin() && ! wp_script_is( 'heartbeat', 'registered' ) ) {
			wp_register_script(
				'heartbeat',
				PAGEFLASH_ASSETS_URL . 'libs/heartbeat/heartbeat.js',
				array( 'jquery' ),
				false,
				true
			);
			wp_enqueue_script( 'heartbeat' );
		}
	}

	/**
	 * Modify heartbeat frequency
	 *
	 * @since 1.2.0
	 * @param array $settings Heartbeat settings.
	 * @return array Modified settings.
	 */
	public function set_heartbeat_frequency( $settings ) {
		if ( ! empty( $this->frequency ) ) {
			$settings['interval']        = $this->frequency;
			$settings['minimalInterval'] = $this->frequency;
		}
		return $settings;
	}
}
