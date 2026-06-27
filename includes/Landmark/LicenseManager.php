<?php
/**
 * License Manager with OAuth Token System
 *
 * Manages license validation using access token and refresh token.
 * Access token: Short-lived (1 hour) for API requests
 * Refresh token: Long-lived (30 days) for renewing access token
 *
 * @package TheAminul\PageFlash\Landmark
 * @since 1.3.0
 */

namespace TheAminul\PageFlash\Landmark;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class LicenseManager
 *
 * OAuth-based license management with real-time validation.
 *
 * @since 1.3.0
 */
class LicenseManager {

	/**
	 * License options keys
	 */
	const LICENSE_KEY_OPTION   = 'pageflash_license_key';
	const ACCESS_TOKEN_OPTION  = 'pageflash_access_token';
	const REFRESH_TOKEN_OPTION = 'pageflash_refresh_token';
	const TOKEN_EXPIRES_OPTION = 'pageflash_token_expires';
	const LICENSE_DATA_OPTION  = 'pageflash_license_data';

	/**
	 * API endpoints (replace with your actual endpoints)
	 */
	const API_BASE_URL           = 'https://api.pageflash.io';
	const ENDPOINT_ACTIVATE      = '/v1/license/activate';
	const ENDPOINT_VALIDATE      = '/v1/license/validate';
	const ENDPOINT_REFRESH_TOKEN = '/v1/license/refresh';
	const ENDPOINT_DEACTIVATE    = '/v1/license/deactivate';

	/**
	 * Token expiration times
	 */
	const ACCESS_TOKEN_LIFETIME  = HOUR_IN_SECONDS;      // 1 hour
	const REFRESH_TOKEN_LIFETIME = 30 * DAY_IN_SECONDS;  // 30 days

	/**
	 * Get stored license key
	 *
	 * @since 1.3.0
	 * @return string License key or empty string.
	 */
	public static function get_license_key() {
		// Check wp-config.php constant first (for managed hosting/agencies)
		if ( defined( 'PAGEFLASH_LICENSE_KEY' ) ) {
			return PAGEFLASH_LICENSE_KEY;
		}

		return get_option( self::LICENSE_KEY_OPTION, '' );
	}

	/**
	 * Get access token
	 *
	 * @since 1.3.0
	 * @return string Access token or empty string.
	 */
	private static function get_access_token() {
		return get_option( self::ACCESS_TOKEN_OPTION, '' );
	}

	/**
	 * Get refresh token
	 *
	 * @since 1.3.0
	 * @return string Refresh token or empty string.
	 */
	private static function get_refresh_token() {
		return get_option( self::REFRESH_TOKEN_OPTION, '' );
	}

	/**
	 * Check if access token is expired
	 *
	 * @since 1.3.0
	 * @return bool True if expired.
	 */
	private static function is_access_token_expired() {
		$expires = get_option( self::TOKEN_EXPIRES_OPTION, 0 );
		return time() >= $expires;
	}

	/**
	 * Check if license is valid (real-time validation)
	 *
	 * This method:
	 * 1. Checks if access token exists and is valid
	 * 2. If expired, tries to refresh using refresh token
	 * 3. Validates with remote server
	 *
	 * @since 1.3.0
	 * @param bool $force_refresh Force token refresh.
	 * @return bool True if license is valid.
	 */
	public static function has_valid_license( $force_refresh = false ) {
		$license_key = self::get_license_key();

		if ( empty( $license_key ) ) {
			return false;
		}

		$access_token = self::get_access_token();

		// No access token, try to activate first
		if ( empty( $access_token ) ) {
			return false;
		}

		// Check if token expired, refresh if needed
		if ( $force_refresh || self::is_access_token_expired() ) {
			$refreshed = self::refresh_access_token();
			if ( ! $refreshed ) {
				return false;
			}
			$access_token = self::get_access_token();
		}

		// Validate with server using access token
		return self::validate_with_access_token( $access_token );
	}

	/**
	 * Activate license and get tokens
	 *
	 * @since 1.3.0
	 * @param string $license_key License key to activate.
	 * @return bool|\WP_Error True on success, WP_Error on failure.
	 */
	public static function activate_license( $license_key ) {
		$license_key = sanitize_text_field( trim( $license_key ) );

		if ( empty( $license_key ) ) {
			return new \WP_Error(
				'empty_license',
				__( 'License key cannot be empty', 'pageflash' )
			);
		}

		// Call activation endpoint
		$response = wp_remote_post(
			self::API_BASE_URL . self::ENDPOINT_ACTIVATE,
			array(
				'timeout' => 15,
				'body'    => array(
					'license_key' => $license_key,
					'site_url'    => home_url(),
					'site_name'   => get_bloginfo( 'name' ),
					'version'     => defined( 'PAGEFLASH_VERSION' ) ? PAGEFLASH_VERSION : '1.0.0',
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return new \WP_Error(
				'activation_failed',
				sprintf(
					/* translators: %s: Error message */
					__( 'License activation failed: %s', 'pageflash' ),
					$response->get_error_message()
				)
			);
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( empty( $data['success'] ) ) {
			return new \WP_Error(
				'activation_failed',
				$data['message'] ?? __( 'License activation failed', 'pageflash' )
			);
		}

		// Store tokens and license data
		update_option( self::LICENSE_KEY_OPTION, $license_key );
		update_option( self::ACCESS_TOKEN_OPTION, $data['access_token'] );
		update_option( self::REFRESH_TOKEN_OPTION, $data['refresh_token'] );
		update_option( self::TOKEN_EXPIRES_OPTION, time() + self::ACCESS_TOKEN_LIFETIME );
		update_option(
			self::LICENSE_DATA_OPTION,
			array(
				'license_type'  => $data['license_type'] ?? 'free',
				'expires_at'    => $data['expires_at'] ?? null,
				'max_sites'     => $data['max_sites'] ?? 1,
				'customer_name' => $data['customer_name'] ?? '',
				'activated_at'  => current_time( 'mysql' ),
			)
		);

		/**
		 * Fires after successful license activation.
		 *
		 * @since 1.3.0
		 * @param string $license_key Activated license key.
		 * @param array  $data Response data from server.
		 */
		do_action( 'pageflash_license_activated', $license_key, $data );

		return true;
	}

	/**
	 * Refresh access token using refresh token
	 *
	 * @since 1.3.0
	 * @return bool True on success.
	 */
	private static function refresh_access_token() {
		$refresh_token = self::get_refresh_token();

		if ( empty( $refresh_token ) ) {
			return false;
		}

		$response = wp_remote_post(
			self::API_BASE_URL . self::ENDPOINT_REFRESH_TOKEN,
			array(
				'timeout' => 10,
				'body'    => array(
					'refresh_token' => $refresh_token,
					'site_url'      => home_url(),
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( empty( $data['success'] ) || empty( $data['access_token'] ) ) {
			// Refresh token expired, need to reactivate
			self::clear_license_data();
			return false;
		}

		// Update access token and expiration
		update_option( self::ACCESS_TOKEN_OPTION, $data['access_token'] );
		update_option( self::TOKEN_EXPIRES_OPTION, time() + self::ACCESS_TOKEN_LIFETIME );

		// Optionally update refresh token if server sends new one (token rotation)
		if ( ! empty( $data['refresh_token'] ) ) {
			update_option( self::REFRESH_TOKEN_OPTION, $data['refresh_token'] );
		}

		return true;
	}

	/**
	 * Validate license with access token
	 *
	 * @since 1.3.0
	 * @param string $access_token Access token.
	 * @return bool True if valid.
	 */
	private static function validate_with_access_token( $access_token ) {
		$response = wp_remote_get(
			self::API_BASE_URL . self::ENDPOINT_VALIDATE,
			array(
				'timeout' => 10,
				'headers' => array(
					'Authorization' => 'Bearer ' . $access_token,
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			// On network error, use grace period (allow for 24 hours)
			$last_validated = get_option( 'pageflash_last_validated', 0 );
			if ( time() - $last_validated < DAY_IN_SECONDS ) {
				return true; // Grace period
			}
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		$is_valid = ! empty( $data['valid'] );

		if ( $is_valid ) {
			// Update last validated time
			update_option( 'pageflash_last_validated', time() );

			// Update license data if provided
			if ( ! empty( $data['license_data'] ) ) {
				update_option( self::LICENSE_DATA_OPTION, $data['license_data'] );
			}
		}

		return $is_valid;
	}

	/**
	 * Get license type
	 *
	 * @since 1.3.0
	 * @return string License type (free|pro|agency).
	 */
	public static function get_license_type() {
		$license_data = get_option( self::LICENSE_DATA_OPTION, array() );
		return $license_data['license_type'] ?? 'free';
	}

	/**
	 * Deactivate license
	 *
	 * @since 1.3.0
	 * @return bool|\WP_Error True on success.
	 */
	public static function deactivate_license() {
		$access_token = self::get_access_token();

		if ( ! empty( $access_token ) ) {
			// Notify server about deactivation
			wp_remote_post(
				self::API_BASE_URL . self::ENDPOINT_DEACTIVATE,
				array(
					'timeout' => 10,
					'headers' => array(
						'Authorization' => 'Bearer ' . $access_token,
					),
					'body'    => array(
						'site_url' => home_url(),
					),
				)
			);
		}

		// Clear all license data
		self::clear_license_data();

		/**
		 * Fires after license deactivation.
		 *
		 * @since 1.3.0
		 */
		do_action( 'pageflash_license_deactivated' );

		return true;
	}

	/**
	 * Clear all license data
	 *
	 * @since 1.3.0
	 * @return void
	 */
	private static function clear_license_data() {
		delete_option( self::LICENSE_KEY_OPTION );
		delete_option( self::ACCESS_TOKEN_OPTION );
		delete_option( self::REFRESH_TOKEN_OPTION );
		delete_option( self::TOKEN_EXPIRES_OPTION );
		delete_option( self::LICENSE_DATA_OPTION );
		delete_option( 'pageflash_last_validated' );
	}

	/**
	 * Get license data for display
	 *
	 * @since 1.3.0
	 * @return array License information.
	 */
	public static function get_license_data() {
		$license_key  = self::get_license_key();
		$license_data = get_option( self::LICENSE_DATA_OPTION, array() );
		$is_valid     = ! empty( $license_key ) && self::has_valid_license();

		return array(
			'has_license'   => ! empty( $license_key ),
			'is_valid'      => $is_valid,
			'license_type'  => $license_data['license_type'] ?? 'free',
			'expires_at'    => $license_data['expires_at'] ?? null,
			'max_sites'     => $license_data['max_sites'] ?? 1,
			'customer_name' => $license_data['customer_name'] ?? '',
			'activated_at'  => $license_data['activated_at'] ?? null,
			'masked_key'    => $license_key ? self::mask_license_key( $license_key ) : '',
			'token_expires' => get_option( self::TOKEN_EXPIRES_OPTION, 0 ),
		);
	}

	/**
	 * Force refresh license status
	 *
	 * @since 1.3.0
	 * @return bool True if still valid.
	 */
	public static function force_refresh() {
		return self::has_valid_license( true );
	}

	/**
	 * Mask license key for display
	 *
	 * @since 1.3.0
	 * @param string $license_key License key.
	 * @return string Masked key.
	 */
	private static function mask_license_key( $license_key ) {
		$length = strlen( $license_key );
		if ( $length <= 8 ) {
			return str_repeat( '*', $length );
		}

		return substr( $license_key, 0, 4 ) . str_repeat( '*', $length - 8 ) . substr( $license_key, -4 );
	}

	/**
	 * Check if feature is allowed based on license type
	 *
	 * @since 1.3.0
	 * @param string $required_package Required package (free|pro|agency).
	 * @return bool True if allowed.
	 */
	public static function can_use_feature( $required_package ) {
		if ( 'free' === $required_package ) {
			return true;
		}

		if ( ! self::has_valid_license() ) {
			return false;
		}

		$license_type = self::get_license_type();

		// Agency license can use everything
		if ( 'agency' === $license_type ) {
			return true;
		}

		// Pro license can use pro features
		if ( 'pro' === $license_type && in_array( $required_package, array( 'free', 'pro' ), true ) ) {
			return true;
		}

		return false;
	}
}
