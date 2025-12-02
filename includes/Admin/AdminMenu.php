<?php

namespace PageFlash\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * PageFlash Admin Menu Class.
 * AdminMenu class handles the registration of the admin menu for the PageFlash plugin.
 *
 * @package PageFlash
 * @since PageFlash 1.0.0
 */
class AdminMenu {

	/**
	 * Constructor for the AdminMenu class.
	 *
	 * Initializes the settings adds necessary actions.
	 *
	 * @since PageFlash 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'pageflash_admin_menu' ) );
	}

	/**
	 * Registers the admin menu for the PageFlash plugin.
	 *
	 * This method adds a top-level menu item and a submenu item under the Settings menu.
	 *
	 * @since PageFlash 1.0.0
	 * @access public
	 */
	public function pageflash_admin_menu() {
		add_menu_page(
			__( 'PageFlash', 'pageflash' ),
			__( 'PageFlash', 'pageflash' ),
			'manage_options',
			'pageflash',
			array( $this, 'pageflash_settings_page' ),
			PAGEFLASH_ICON,
			100
		);
	}
	/**
	 * Callback function for the settings page.
	 *
	 * This method is called when the settings page is accessed.
	 *
	 * @since PageFlash 1.0.0
	 * @access public
	 */
	public function pageflash_settings_page() {
		?>
		<div class="wrap pageflash-admin" id="pageflash-admin"></div>
		<?php
	}
}
