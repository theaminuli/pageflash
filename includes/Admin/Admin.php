<?php

namespace PageFlash\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * PageFlash Admin Class.
 *
 * This class handles the administration-related functionality for the PageFlash plugin.
 *
 * @package PageFlash
 * @since PageFlash 1.0.0
 */
class Admin {

	/**
	 * Constructor for the Admin class.
	 *
	 * Initializes the settings API and adds necessary actions.
	 *
	 * @since PageFlash 1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->init_admin();
		// add_action( 'admin_init', array( $this, 'init_admin' ) );
	}


	/**
	 * Initialize admin Landmark.
	 *
	 * This method initializes the admin-related  Register actions for your PageFlash plugin.
	 *
	 * @since PageFlash 1.0.0
	 * @access private
	 */
	private function init_admin() {
		new ActionLinks();
	}
}
