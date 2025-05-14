<?php

namespace PageFlash\Landmark\NoReload;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Quicklink Class.
 *
 * This class provides functionality for something in your PageFlash plugin.
 *
 * @package pageflash
 * @since PageFlash 1.0.0
 */
class Quicklink {

	public function __construct() {
		// Constructor code
		add_filter( 'script_loader_tag', array( $this, 'pageflash_async_script_loader' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'pageflash_settings_config' ) );
	}

	/**
	 * Filters PageFlash Quicklink settings.
	 *
	 * @since PageFlash 1.0.0
	 * @access public
	 * @link https://github.com/GoogleChromeLabs/quicklink
	 * @param array {
	 *     Configuration options for PageFlash Quicklink.
	 *
	 *     @param string   $el        CSS selector for the DOM element to observe for in-viewport links to prefetch.
	 *     @param int      $limit     The total requests that can be prefetched while observing the $el container.
	 *     @param int      $throttle  The concurrency limit for simultaneous requests while observing the $el container.
	 *     @param int      $timeout   Timeout after which prefetching will occur.
	 *     @param string   $timeoutFn Custom timeout function. Must refer to a named global function in JS.
	 *     @param bool     $priority  Attempt higher priority fetch (low or high). Default false.
	 *     @param string[] $origins   Allowed origins to prefetch (empty allows all). Defaults to host for the current home URL.
	 *     @param string[] $ignores   Regular expression patterns to determine whether a URL is ignored. Runs after origin checks.
	 * }
	 */
	public function pageflash_settings_config() {
		$current_request_uri = esc_url_raw( $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		$ignore_pattern      = $current_request_uri . '(#.*)?$/';

		$pageflash_settings = array(
			'el'        => '', // CSS selector for in-viewport links to prefetch
			'urls'      => array( site_url( '/' ) ), // Static array of URLs to prefetch.
			'timeout'   => 2000,   // Set the timeout
			'timeoutFn' => 'requestIdleCallback', // Use requestIdleCallback for timing
			'priority'  => false,  // Use fetch() API where supported
			'origins'   => array(
				wp_parse_url( home_url( '/' ), PHP_URL_HOST ),
				site_url( '/' ),
				$current_request_uri,
			), // Static array of URL hostname strings that are allowed to be prefetched.
			'ignores'   => array(
				preg_quote( 'feed=', '/' ),
				preg_quote( '/feed/', '/' ),
				admin_url( '/' ),
				wp_login_url( '/' ),
				content_url(),
				$ignore_pattern,
			), // Don't pre-fetch links to the admin since they could be nonce links.
		);

		// Apply filters
		$pageflash_settings['ignores'] = apply_filters( 'wp_pageflash_quicklink_ignore_urls', $pageflash_settings['ignores'] );
		$pageflash_settings            = apply_filters( 'wp_pageflash_quicklink', $pageflash_settings );

		wp_localize_script( 'pageflash-frontend', 'pageflashSettings', $pageflash_settings );
	}

	/**
	 * Add 'async' attribute to PageFlash script loader tag.
	 *
	 * This function adds the 'async' attribute to the script tag for PageFlash to improve page loading speed.
	 *
	 * @since PageFlash 1.0.0
	 * @access public
	 * @link https://github.com/WordPress/twentynineteen/pull/646
	 * @link https://github.com/wprig/wprig/blob/9a7c23d8d3db735259de6c338ddbb7cb7fd0ada1/dev/inc/template-functions.php#L41-L70
	 * @link https://core.trac.wordpress.org/ticket/12009
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @return string The modified script tag.
	 */
	public function pageflash_async_script_loader( $tag, $handle ) {

		$async_handles = array( 'pageflash-quicklink', 'pageflash-frontend' );
		if ( in_array( $handle, $async_handles ) && false === strpos( $tag, 'async' ) ) {
			$tag = str_replace( '></script>', ' async></script>', $tag );
		}

		return $tag;
	}
}
