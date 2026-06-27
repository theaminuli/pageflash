<?php
/**
 * Remove RSD Link Feature
 *
 * @package TheAminul\PageFlash\Landmark\General
 * @since 1.4.0
 */

namespace TheAminul\PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class RemoveRsdLink
 *
 * Removes the Really Simple Discovery (RSD) link from the <head> section.
 *
 * @since 1.4.0
 */
class RemoveRsdLink {

	/**
	 * Constructor
	 *
	 * @since 1.4.0
	 */
	public function __construct() {
		remove_action( 'wp_head', 'rsd_link' );
	}
}
