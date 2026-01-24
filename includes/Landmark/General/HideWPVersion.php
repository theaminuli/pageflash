<?php
/**
 * Hide WordPress Version Feature
 *
 * @package TheAminul\PageFlash\Landmark\General
 * @since 1.2.0
 */

namespace TheAminul\PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class HideWPVersion
 *
 * Hides WordPress version for improved security.
 *
 * @since 1.2.0
 */
class HideWPVersion {

	/**
	 * Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		remove_action( 'wp_head', 'wp_generator' );
		add_filter( 'the_generator', array( $this, 'hide_wp_version' ) );
	}

	/**
	 * Hide WordPress version
	 *
	 * @since 1.2.0
	 * @return string Empty string.
	 */
	public function hide_wp_version() {
		return '';
	}
}
