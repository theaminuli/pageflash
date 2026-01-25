<?php
namespace TheAminul\PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class RemoveRestAPILink
 *
 * Handles removal of REST API links from frontend and headers.
 */
class RemoveRestAPILink {

	/**
	 * Constructor
	 *
	 * @param array $options Plugin options array
	 */
	public function __construct() {
		// Initialize if removal is enabled
		add_action( 'init', array( $this, 'remove_rest_api_links' ) );
	}

	/**
	 * Remove REST API links from header and response
	 */
	public function remove_rest_api_links() {
		remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
		remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
	}
}
