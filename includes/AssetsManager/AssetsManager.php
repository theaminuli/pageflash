<?php

namespace TheAminul\PageFlash\AssetsManager;

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
		add_action( 'admin_enqueue_scripts', array( $this, 'pageflash_admin_enqueue_scripts' ) );
		add_action( 'admin_init', array( $this, 'pageflash_admin_icon' ) );
	}

	/**
	 * Enqueue scripts and styles for the PageFlash plugin Admin.
	 *
	 * This method enqueues scripts and styles necessary for the PageFlash plugin's functionality.
	 *
	 * @return void
	 * @since PageFlash 1.0.0
	 */
	public function pageflash_admin_enqueue_scripts( $admin_page ) {
		if ( 'toplevel_page_pageflash' !== $admin_page ) {
			return;
		}
		$asset_file = PAGEFLASH_PATH . 'build/admin/admin.asset.php';
		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$script_asset = include $asset_file;
		// Enqueue admin style with dependencies and version from asset file
		wp_enqueue_style(
			'pageflash-admin',
			PAGEFLASH_BUILD_URL . 'admin/admin.css',
			array( 'wp-components' ),
			isset( $script_asset['version'] ) ? $script_asset['version'] : '1.0.0'
		);
		// Enqueue admin script with dependencies and version from asset file
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
				'isDevelopment' => PAGEFLASH_ENV === 'development' ? true : false,
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'pageflash_admin_nonce' ),
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
		wp_add_inline_style(
			'wp-admin',
			'#adminmenu #toplevel_page_pageflash div.wp-menu-image:before {
				content: "\e900";
				font-family: "pageflash";
				font-size: 22px;
				margin-top: 0;
			}
			'
		);
		// Icon style dependency
		wp_enqueue_style(
			'pageflash-icomoon',
			PAGEFLASH_ASSETS_URL . 'icomoon/style.css',
			array(),
			PAGEFLASH_VERSION
		);
	}
}
