<?php
/**
 * Disable REST API Feature
 *
 * @package PageFlash\Landmark\General
 * @since 1.2.0
 */

namespace PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class DisableRestAPI
 *
 * Disables REST API for non-authenticated users.
 *
 * @package PageFlash\Landmark\General
 * @since 1.2.0
 */
class DisableRestAPI {

	/**
	 * Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_filter( 'rest_authentication_errors', array( $this, 'rest_authentication_errors' ), 20 );
		remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
		remove_action( 'template_redirect', 'rest_output_link_header', 11 );
	}

	/**
	 * Disable REST API for non-authenticated users
	 *
	 * @since 1.2.0
	 * @param mixed $result Authentication result.
	 * @return mixed Modified authentication result.
	 */
	public function rest_authentication_errors( $result ) {
		if ( ! empty( $result ) ) {
			return $result;
		}

		if ( ! is_user_logged_in() ) {
			return new \WP_Error(
				'rest_disabled',
				__( 'The REST API is disabled.', 'pageflash' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return $result;
	}
}
