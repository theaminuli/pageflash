<?php

namespace PageFlash\AssetsManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * AssetsManager Class.
 *
 * This class handles the loading and registration of assets (styles and scripts) for the PageFlash plugin.
 *
 * @package pageflash
 * @since PageFlash 1.0.0
 */
class AssetsManager {

	public function __construct() {
		// Constructor code
		// Define asset loading and registration here
		add_action( 'wp_default_scripts', array( $this, 'pageflash_wp_default_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'pageflash_frontend_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'pageflash_admin_enqueue_scripts' ) );
	}

	/**
	 * Enqueue scripts for the PageFlash plugin frontend.
	 *
	 * This method enqueues scripts necessary for the PageFlash plugin's functionality.
	 * Add quicklink to the default scripts to make it available earlier in the runtime.
	 *
	 * @param WP_Scripts $scripts The WP_Scripts instance.
	 * @return void
	 * @since PageFlash 1.0.0
	 */
	public function pageflash_wp_default_scripts( $scripts ) {
		$min_suffix        = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$quicklink_version = defined( 'PAGEFLASH_VERSION' ) && empty( PAGEFLASH_VERSION ) ? '' : '2.3.0';
		$scripts->add( 'pageflash-quicklink', PAGEFLASH_ASSETS_URL . 'libs/quicklink/dist/quicklink.umd.js', array(), $quicklink_version, true );
		$scripts->add( 'pageflash-frontend', PAGEFLASH_ASSETS_URL . 'js/frontend/pageflash-frontend' . $min_suffix . '.js', array(), PAGEFLASH_VERSION, true );
		return $scripts;
	}

	/**
	 * Enqueue scripts and styles for the PageFlash plugin frontend.
	 *
	 * This method enqueues scripts and styles necessary for the PageFlash plugin's functionality.
	 *
	 * @return void
	 * @since PageFlash 1.0.0
	 */
	public function pageflash_frontend_assets() {

		$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_style( 'pageflash-frontend', PAGEFLASH_ASSETS_URL . 'css/frontend' . $min_suffix . '.css', array(), PAGEFLASH_VERSION );
		wp_enqueue_script( 'pageflash-frontend' );
		// wp_enqueue_script('pageflash-quicklink');
	}

	/**
	 * Enqueue scripts and styles for the PageFlash plugin Admin.
	 *
	 * This method enqueues scripts and styles necessary for the PageFlash plugin's functionality.
	 *
	 * @return void
	 * @since PageFlash 1.0.0
	 */
	public function pageflash_admin_enqueue_scripts() {

		$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'jquery' );

		wp_enqueue_script(
			'pageflash-admin',
			PAGEFLASH_ASSETS_URL . 'js/admin/pageflash-admin' . $min_suffix . '.js',
			array( 'jquery' ),
			PAGEFLASH_VERSION,
			true
		);

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style(
			'pageflash-admin',
			PAGEFLASH_ASSETS_URL . 'css/admin' . $min_suffix . '.css',
			array(),
			PAGEFLASH_VERSION
		);
	}
}
