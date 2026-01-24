<?php

namespace TheAminul\PageFlash\Landmark\NoReload;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * InstantPage Class.
 *
 * This class provides instant.page functionality for the PageFlash plugin.
 * Makes your site's pages instant by prefetching links on hover.
 *
 * @package pageflash
 * @since PageFlash 1.0.0
 * @link https://instant.page/
 */
class InstantPage {

	public function __construct() {
		add_action( 'wp_default_scripts', array( $this, 'pageflash_wp_default_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'pageflash_frontend_assets' ) );
		add_filter( 'script_loader_tag', array( $this, 'pageflash_instantpage_script_loader_tag' ), 10, 2 );
	}

	/**
	 * Register instant.page script in default scripts.
	 *
	 * This method registers the instant.page script to make it available earlier in the runtime.
	 *
	 * @param WP_Scripts $scripts The WP_Scripts instance.
	 * @return void
	 * @since PageFlash 1.0.0
	 */
	public function pageflash_wp_default_scripts( $scripts ) {
		// Define the version for instant.page
		$instantpage_version = defined( 'PAGEFLASH_VERSION' ) && ! empty( PAGEFLASH_VERSION ) ? PAGEFLASH_VERSION : '5.2.0';
		$scripts->add(
			'pageflash-instantpage',
			PAGEFLASH_ASSETS_URL . 'libs/instantpage/instantpage.js',
			array(),
			$instantpage_version,
			true
		);

		return $scripts;
	}

	/**
	 * Enqueue instant.page script for the PageFlash plugin frontend.
	 *
	 * This method enqueues the instant.page script to make pages load instantly.
	 *
	 * @return void
	 * @since PageFlash 1.0.0
	 */
	public function pageflash_frontend_assets() {
		wp_enqueue_script( 'pageflash-instantpage' );
	}

	/**
	 * Add 'type="module"' attribute to instant.page script tag.
	 *
	 * instant.page requires the script to be loaded as a module.
	 * This function modifies the script tag to add type="module" attribute.
	 *
	 * @since PageFlash 1.0.0
	 * @access public
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @return string The modified script tag.
	 */
	public function pageflash_instantpage_script_loader_tag( $tag, $handle ) {
		if ( 'pageflash-instantpage' === $handle ) {
			if ( strpos( $tag, 'text/javascript' ) !== false ) {
				$tag = str_replace( 'text/javascript', 'module', $tag );
			} else {
				$tag = str_replace( '<script ', "<script type='module' ", $tag );
			}
		}

		return $tag;
	}
}
