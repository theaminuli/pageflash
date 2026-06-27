<?php
/**
 * Remove Feed Links Feature
 *
 * @package TheAminul\PageFlash\Landmark\General
 * @since 1.4.0
 */

namespace TheAminul\PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class RemoveFeedLinks
 *
 * Removes RSS/Atom feed links from the <head> section.
 *
 * @since 1.4.0
 */
class RemoveFeedLinks {

	/**
	 * Constructor
	 *
	 * @since 1.4.0
	 */
	public function __construct() {
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}
}
