<?php

namespace PageFlash\Compatibility;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Compatibility Class
 *
 * Handles third-party plugin compatibility for PageFlash.
 * Initializes compatibility modules for supported plugins.
 *
 * @package PageFlash\Compatibility
 * @since 1.2.0
 */

class Compatibility {

	/**
	 * Constructor
	 *
	 * Initializes compatibility modules for supported plugins.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		// Initialize WooCommerce compatibility if WooCommerce is active.
	}
}
