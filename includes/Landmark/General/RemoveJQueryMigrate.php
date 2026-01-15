<?php
/**
 * Remove jQuery Migrate Feature
 *
 * @package PageFlash\Landmark\General
 * @since 1.2.0
 */

namespace PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class RemoveJQueryMigrate
 *
 * Removes jQuery Migrate to improve performance.
 *
 * @package PageFlash\Landmark\General
 * @since 1.2.0
 */
class RemoveJQueryMigrate {

	/**
	 * Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_filter( 'wp_default_scripts', array( $this, 'remove_jquery_migrate' ) );
	}

	/**
	 * Remove jQuery Migrate
	 *
	 * @since 1.2.0
	 * @param object $scripts WP_Scripts object.
	 * @return void
	 */
	public function remove_jquery_migrate( &$scripts ) {
		if ( ! is_admin() && ! empty( $scripts->registered['jquery'] ) ) {
			$jquery_dependencies             = $scripts->registered['jquery']->deps;
			$scripts->registered['jquery']->deps = array_diff( $jquery_dependencies, array( 'jquery-migrate' ) );
		}
	}
}
