<?php
/**
 * Disable Heartbeat Feature
 *
 * @package PageFlash\Landmark\General
 * @since 1.2.0
 */

namespace PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class DisableHeartbeat
 *
 * Controls WordPress Heartbeat API for improved performance.
 *
 * @package PageFlash\Landmark\General
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
		add_filter( 'heartbeat_settings', array( $this, 'heartbeat_frequency' ) );
	}

	/**
	 * Disable heartbeat based on behavior setting
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function disable_heartbeat() {
		if ( 'disable_everywhere' === $this->behavior ) {
			wp_deregister_script( 'heartbeat' );
		} elseif ( 'allow_posts' === $this->behavior ) {
			global $pagenow;
			if ( 'post.php' !== $pagenow && 'post-new.php' !== $pagenow ) {
				wp_deregister_script( 'heartbeat' );
			}
		} elseif ( 'disable_dashboard' === $this->behavior ) {
			global $pagenow;
			if ( 'index.php' === $pagenow ) {
				wp_deregister_script( 'heartbeat' );
			}
		}
	}

	/**
	 * Modify heartbeat frequency
	 *
	 * @since 1.2.0
	 * @param array $settings Heartbeat settings.
	 * @return array Modified settings.
	 */
	public function heartbeat_frequency( $settings ) {
		if ( ! empty( $this->frequency ) ) {
			$settings['interval'] = $this->frequency;
		}
		return $settings;
	}
}
