<?php
/**
 * Remove Global Styles Feature
 *
 * @package TheAminul\PageFlash\Landmark\General
 * @since 1.4.0
 */

namespace TheAminul\PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class RemoveGlobalStyles
 *
 * Removes the WordPress global styles inline CSS added since WP 5.9.
 *
 * @since 1.4.0
 */
class RemoveGlobalStyles {

	/**
	 * Constructor
	 *
	 * @since 1.4.0
	 */
	public function __construct() {
		add_action(
			'after_setup_theme',
			function () {
				remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
				remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
			}
		);
	}
}
