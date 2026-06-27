<?php
/**
 * Remove Shortlink Feature
 *
 * @package TheAminul\PageFlash\Landmark\General
 * @since 1.4.0
 */

namespace TheAminul\PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class RemoveShortlink
 *
 * Removes the WordPress shortlink from the <head> and HTTP headers.
 *
 * @since 1.4.0
 */
class RemoveShortlink {

	/**
	 * Constructor
	 *
	 * @since 1.4.0
	 */
	public function __construct() {
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
	}
}
