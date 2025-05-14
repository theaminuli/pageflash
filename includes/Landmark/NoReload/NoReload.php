<?php

namespace PageFlash\Landmark\NoReload;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * NoReload Class.
 *
 * This class provides functionality for something in your PageFlash plugin.
 *
 * @package pageflash
 * @since PageFlash 1.0.0
 */
class NoReload {

	public function __construct() {
		// Constructor code
		add_filter( 'wp_pageflash_quicklink_ignore_urls', array( $this, 'pageflash_quicklink_ignore_urls' ) );
		add_filter( 'wp_pageflash_quicklink', array( $this, 'pageflash_quicklink' ) );
	}

	/**
	 * Filters PageFlash NoReload ignore URLs.
	 *
	 * @since PageFlash 1.0.0
	 * @access public
	 * @param array  $ignores {
	 *      Configuration options for PageFlash NoReload ignore URLs.
	 *
	 *     @param string $ignore_pattern Regular expression pattern to determine whether a URL is ignored.
	 * }
	 */
	public function pageflash_quicklink_ignore_urls( $ignores ) {
		// $ignores[] = preg_quote( 'custom-ignore-pattern', '/' );
		return $ignores;
	}

	/**
	 * Filters PageFlash NoReload settings.
	 *
	 * @since PageFlash 1.0.0
	 * @access public
	 * @param array {
	 *     Configuration options for PageFlash NoReload.
	 *
	 *     @param string  $el CSS selector for the DOM element to observe for in-viewport links to prefetch.
	 *     @param int     $limit The total requests that can be prefetched while observing the $el container.
	 *     @param int     $throttle The concurrency limit for simultaneous requests while observing the $el container.
	 *     @param int     $timeout Timeout after which prefetching will occur.
	 *     @param string  $timeoutFn Custom timeout function. Must refer to a named global function in JS.
	 *     @param bool    $priority Attempt higher priority fetch (low or high). Default false.
	 *     @param string  $origins Allowed origins to prefetch (empty allows all). Defaults to host for the current home URL.
	 *     @param string  $ignores Regular expression patterns to determine whether a URL is ignored. Runs after origin checks.
	 *  }
	 */
	public function pageflash_quicklink( $settings ) {
		// $settings['timeout'] = 3000; // Change the timeout to 3000ms
		// $settings['priority'] = true; // Enable fetch() API where supported
		return $settings;
	}
}
