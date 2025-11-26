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
		add_action( 'admin_init', array( $this, 'pageflash_admin_icon' ) );
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
		// Define the version for Quicklink, falling back to a default version if not set
		$quicklink_version = defined( 'PAGEFLASH_VERSION' ) && ! empty( PAGEFLASH_VERSION ) ? PAGEFLASH_VERSION : '2.3.0';

		// Include the asset file for script dependencies and version
		$asset_file = PAGEFLASH_PATH . 'build/quicklink/quicklink.asset.php';
		if ( ! file_exists( $asset_file ) ) {
			return $scripts; 
		}

		$script_asset = include $asset_file;
		$scripts->add(
			'pageflash-quicklink',
			PAGEFLASH_ASSETS_URL . 'libs/quicklink/dist/quicklink.umd.js',
			array(),
			$quicklink_version,
			true
		);

		if ( is_array( $script_asset ) && isset( $script_asset['dependencies'], $script_asset['version'] ) ) {
			$scripts->add(
				'pageflash-frontend',
				PAGEFLASH_URL . 'build/quicklink/quicklink.js',
				$script_asset['dependencies'],
				$script_asset['version'],
				true
			);
		}
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
		wp_enqueue_script( 'pageflash-frontend' );
		wp_enqueue_script( 'pageflash-quicklink' );
	}

	/**
	 * Enqueue scripts and styles for the PageFlash plugin Admin.
	 *
	 * This method enqueues scripts and styles necessary for the PageFlash plugin's functionality.
	 *
	 * @return void
	 * @since PageFlash 1.0.0
	 */
	public function pageflash_admin_enqueue_scripts( $admin_page) {
		if ( 'toplevel_page_pageflash' !== $admin_page ) {
       		 return;
    	}
		$asset_file =  PAGEFLASH_PATH . 'build/admin/admin.asset.php';
		if ( ! file_exists( $asset_file ) ) {
        	return;
   		}

		$script_asset = include $asset_file;
		wp_enqueue_style(
			'pageflash-admin',
			PAGEFLASH_BUILD_URL . 'admin/admin.css',
			array( 'wp-components' ),
			isset( $script_asset['version'] ) ? $script_asset['version'] : '1.0.0'
		);

		wp_enqueue_script(
			'pageflash-admin',
			PAGEFLASH_BUILD_URL . 'admin/admin.js',
			$script_asset['dependencies'],
			$script_asset['version'],
			array(
            	'in_footer' => true,
        	)
		);

		wp_localize_script(
			'pageflash-admin',
			'pageflashAdmin',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'pageflash_admin_nonce' ),
			)
		);

	}
	/**
	 * Enqueue the PageFlash icon in the admin area.
	 *
	 * This method enqueues the icon for use in the admin area.
	 *
	 * @return void
	 * @since PageFlash 1.0.0
	 */
	public function pageflash_admin_icon() {
		wp_enqueue_style( 'wp-admin' );

		wp_add_inline_style(
			'wp-admin',
			'.toplevel_page_pageflash .toplevel_page_pageflash .wp-menu-image:before {
				content: "";
				filter: invert(1);
				width: 25px;
				height: 25px;
				margin-top: -2px;
				background: url("' . esc_url( PAGEFLASH_URL . 'assets/logo/icon.svg' ) . '") no-repeat center;
				background-size: contain;
			
			}
			.toplevel_page_pageflash .wp-menu-image img {
				display: none;
			}'
		);
	}

}
