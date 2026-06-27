<?php
/**
 * ValidatesAPI class file
 *
 * Handles REST API endpoints for PageFlash validates.
 *
 * @package PageFlash\Landmark
 * @since 1.0.0
 */
namespace TheAminul\PageFlash\Landmark;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use TheAminul\PageFlash\Landmark\LicenseManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Class ValidatesAPI
 *
 * Handles REST API endpoints for PageFlash validates.
 *
 * @since 1.0.0
 */
class ValidatesAPI {
	/**
	 * Constructor to initialize REST API routes.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}
	/**
	 * Register REST API routes for validates.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_routes() {
		// GET /license/status - Get current license status.
		register_rest_route(
			'pageflash/v1',
			'/license/status',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_license_status' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		// POST /license/activate - Activate license key.
		register_rest_route(
			'pageflash/v1',
			'/license/activate',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'activate_license' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'                => array(
					'license_key' => array(
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			)
		);

		// POST /license/deactivate - Deactivate license.
		register_rest_route(
			'pageflash/v1',
			'/license/deactivate',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'deactivate_license' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		// POST /license/refresh - Refresh access token.
		register_rest_route(
			'pageflash/v1',
			'/license/refresh',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'refresh_license_token' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);
	}

	/**
	 * Get current license status.
	 *
	 * Returns information about the active license including:
	 * - License status (active/inactive/expired)
	 * - License tier (free/pro/agency)
	 * - Expiration date
	 * - Site URL
	 * - Access token status
	 *
	 * @since 1.3.0
	 * @param WP_REST_Request $request The REST request object.
	 * @return WP_REST_Response Response with license status.
	 */
	public function get_license_status( WP_REST_Request $request ) {
		$license_data = LicenseManager::get_license_data();
		$has_token    = ! empty( get_option( LicenseManager::ACCESS_TOKEN_OPTION ) );
		$token_valid  = false;

		if ( $has_token ) {
			$expires = get_option( LicenseManager::TOKEN_EXPIRES_OPTION );
			if ( $expires && time() < $expires ) {
				$token_valid = true;
			}
		}

		return rest_ensure_response(
			array(
				'status'        => $license_data ? 'active' : 'inactive',
				'license_data'  => $license_data,
				'has_token'     => $has_token,
				'token_valid'   => $token_valid,
				'token_expires' => $has_token ? get_option( LicenseManager::TOKEN_EXPIRES_OPTION ) : null,
			)
		);
	}

	/**
	 * Activate license key.
	 *
	 * Validates the license key with the license server and stores
	 * the access token and refresh token for future validation.
	 *
	 * @since 1.3.0
	 * @param WP_REST_Request $request The REST request object.
	 * @return WP_REST_Response|WP_Error Response on success, WP_Error on failure.
	 */
	public function activate_license( WP_REST_Request $request ) {
		$license_key = $request->get_param( 'license_key' );

		if ( empty( $license_key ) ) {
			return new WP_Error(
				'missing_license_key',
				__( 'License key is required', 'pageflash' ),
				array( 'status' => 400 )
			);
		}

		$result = LicenseManager::activate_license( $license_key );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return rest_ensure_response(
			array(
				'message'      => __( 'License activated successfully', 'pageflash' ),
				'status'       => 'active',
				'license_data' => LicenseManager::get_license_data(),
			)
		);
	}

	/**
	 * Deactivate license.
	 *
	 * Deactivates the current license on the license server
	 * and removes all license data from the site.
	 *
	 * @since 1.3.0
	 * @param WP_REST_Request $request The REST request object.
	 * @return WP_REST_Response|WP_Error Response on success, WP_Error on failure.
	 */
	public function deactivate_license( WP_REST_Request $request ) {
		$result = LicenseManager::deactivate_license();

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return rest_ensure_response(
			array(
				'message' => __( 'License deactivated successfully', 'pageflash' ),
				'status'  => 'inactive',
			)
		);
	}

	/**
	 * Refresh access token.
	 *
	 * Uses the refresh token to obtain a new access token
	 * from the license server. This is useful when the access
	 * token has expired but the refresh token is still valid.
	 *
	 * @since 1.3.0
	 * @param WP_REST_Request $request The REST request object.
	 * @return WP_REST_Response|WP_Error Response on success, WP_Error on failure.
	 */
	public function refresh_license_token( WP_REST_Request $request ) {
		$result = LicenseManager::force_refresh();

		if ( ! $result ) {
			return new WP_Error(
				'refresh_failed',
				__( 'Failed to refresh license token. Refresh token may have expired. Please reactivate your license.', 'pageflash' ),
				array( 'status' => 401 )
			);
		}

		$expires = get_option( LicenseManager::TOKEN_EXPIRES_OPTION );

		return rest_ensure_response(
			array(
				'message'       => __( 'Access token refreshed successfully', 'pageflash' ),
				'token_expires' => $expires,
			)
		);
	}

	/**
	 * Check if current user has permission to manage licenses.
	 *
	 * @since 1.3.0
	 * @return bool True if user has permission, false otherwise.
	 */
	public function check_permissions() {
		return current_user_can( 'manage_options' );
	}
}
