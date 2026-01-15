<?php
/**
 * Disable Dashicons Feature
 *
 * @package PageFlash\Landmark\General
 * @since 1.2.0
 */

namespace PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class DisableDashicons
 *
 * Disables dashicons on the frontend for non-logged-in users.
 *
 * @package PageFlash\Landmark\General
 * @since 1.2.0
 */
class DisableDashicons {

	/**
	 * Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'disable_dashicons' ) );
	}

	/**
	 * Disable dashicons for non-logged-in users
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function disable_dashicons() {
		if ( ! is_user_logged_in() ) {
			wp_dequeue_style( 'dashicons' );
			wp_deregister_style( 'dashicons' );
		}
	}
}
