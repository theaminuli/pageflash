<?php
/**
 * Disable XML-RPC Feature
 *
 * @package PageFlash\Landmark\General
 * @since 1.2.0
 */

namespace PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class DisableXMLRPC
 *
 * Disables XML-RPC for improved security and performance.
 *
 * @package PageFlash\Landmark\General
 * @since 1.2.0
 */
class DisableXMLRPC {

	/**
	 * Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_filter( 'xmlrpc_enabled', '__return_false' );
		add_filter( 'wp_headers', array( $this, 'remove_x_pingback' ) );
		add_filter( 'pings_open', '__return_false', 9999 );
		add_action( 'init', array( $this, 'intercept_xmlrpc_header' ) );
	}

	/**
	 * Remove X-Pingback header
	 *
	 * @since 1.2.0
	 * @param array $headers HTTP headers.
	 * @return array Modified headers array.
	 */
	public function remove_x_pingback( $headers ) {
		unset( $headers['X-Pingback'], $headers['x-pingback'] );
		return $headers;
	}

	/**
	 * Intercept XML-RPC requests
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function intercept_xmlrpc_header() {
		if ( ! isset( $_SERVER['SCRIPT_FILENAME'] ) ) {
			return;
		}

		if ( 'xmlrpc.php' !== basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
			return;
		}

		$header = 'HTTP/1.1 403 Forbidden';
		header( $header );
		echo esc_html( $header );
		die();
	}
}
