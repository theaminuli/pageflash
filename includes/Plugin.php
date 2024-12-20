<?php

namespace PageFlash;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PageFlash plugin.
 *
 * The main plugin handler class is responsible for initializing PageFlash. The
 * class registers all the init_features required to run the plugin.
 *
 * @since PageFlash 1.0.0
 */

class Plugin {

	/**
	 * Instance
	 *
	 * @since PageFlash 1.0.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since PageFlash 1.0.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Register autoload.
	 *
	 * PageFlash autoload loads all the classes needed to run the plugin.
	 *
	 * @since 1.6.0
	 * @access private
	 */
	private function register_autoload() {
		require_once PAGEFLASH_PATH . 'vendor/autoload.php';
	}

	/**
	 * Initialize the assets manager.
	 *
	 * This method initializes the assets manager for your PageFlash plugin.
	 *
	 * @since PageFlash 1.0.0
	 * @access private
	 */
	private function init_assets() {
		new AssetsManager\AssetsManager();
	}


	/**
	 * Initialize admin features.
	 *
	 * This method initializes the admin-related features for your PageFlash plugin.
	 *
	 * @since PageFlash 1.0.0
	 * @access private
	 */
	private function init_admin() {
		if ( is_admin() ) {
			// Initialize your admin-related features here
			new Admin\Admin();
		}
	}

	/**
	 * Init init_features.
	 *
	 * Initialize PageFlash init_features. Register actions, run setting manager,
	 * initialize all the init_features that run PageFlash, and if in the admin page,
	 * initialize admin init_features.
	 *
	 * @since PageFlash 1.0.0
	 * @access private
	 */
	private function init_features() {
		new Features\NoMoreReload\NoMoreReload();
	}

	/**
	 * Init.
	 *
	 * Initialize PageFlash Plugin. Register PageFlash support for all the
	 * supported post types and initialize PageFlash init_features.
	 *
	 * @since PageFlash 1.0.0
	 * @access private
	 */
	private function init() {
		$this->register_autoload();
		$this->init_assets();
		$this->init_admin();
		$this->init_features();
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since PageFlash 1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->init();
	}
}

// Instantiate Plugin Class
Plugin::instance();
