<?php
/**
 * LandmarkAPI class file
 *
 * Handles REST API endpoints for PageFlash landmarks.
 *
 * @package PageFlash\Landmark
 * @since 1.0.0
 */

namespace PageFlash\Landmark;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class LandmarkAPI
 *
 * Handles the REST API endpoints for managing PageFlash landmarks.
 *
 * @package PageFlash\Landmark
 * @since 1.0.0
 */
class LandmarkAPI {

	/**
	 * Constructor to initialize REST API routes.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Registers REST API routes for landmarks.
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {

		// GET /landmark - Get all landmarks.
		register_rest_route(
			'pageflash/v1',
			'/landmark',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_all_landmarks' ),
				'permission_callback' => '__return_true',
			)
		);

		// GET /landmark/:slug - Get landmark by slug.
		register_rest_route(
			'pageflash/v1',
			'/landmark/(?P<slug>[a-zA-Z0-9_-]+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_landmark_by_slug' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'slug' => array(
						'required'          => true,
						'sanitize_callback' => 'sanitize_key',
					),
				),
			)
		);

		// PUT /landmark/:slug - Update landmark by slug.
		register_rest_route(
			'pageflash/v1',
			'/landmark/(?P<slug>[a-zA-Z0-9_-]+)',
			array(
				'methods'             => 'PUT',
				'callback'            => array( $this, 'update_landmark_by_slug' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'                => array(
					'slug' => array(
						'required'          => true,
						'sanitize_callback' => 'sanitize_key',
					),
				),
			)
		);
	}
	/**
	 * Get all landmarks.
	 *
	 * Handles GET requests to /landmark endpoint.
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $request The REST request object.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error on failure.
	 *
	 * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.Found
	 */
	public function get_all_landmarks( WP_REST_Request $request ) {
		$data = get_option( 'pageflash_landmarks', array() );

		// Return all landmarks.
		return rest_ensure_response( $data );
	}

	/**
	 * Get landmark by slug.
	 *
	 * Handles GET requests to /landmark/:slug endpoint.
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $request The REST request object.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error on failure.
	 */
	public function get_landmark_by_slug( WP_REST_Request $request ) {
		$slug = $request->get_param( 'slug' );
		$data = get_option( 'pageflash_landmarks', array() );

		// Get the data array from the landmarks option.
		$items = $data['data'] ?? $data;

		if ( ! is_array( $items ) ) {
			return new WP_Error(
				'invalid_data',
				__( 'Landmark data is not properly formatted', 'pageflash' ),
				array( 'status' => 500 )
			);
		}

		// Search for the landmark by slug.
		foreach ( $items as $item ) {
			if ( isset( $item['slug'] ) && $item['slug'] === $slug ) {
				return rest_ensure_response(
					array(
						'message' => __( 'Landmark retrieved successfully', 'pageflash' ),
						'data'    => $item,
					)
				);
			}
		}

		return new WP_Error(
			'not_found',
			__( 'Landmark not found', 'pageflash' ),
			array( 'status' => 404 )
		);
	}

	/**
	 * Update landmark by slug.
	 *
	 * Handles PUT requests to /landmark/:slug endpoint.
	 * Updates the landmark with the provided data (supports 'active' field).
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $request The REST request object.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error on failure.
	 */
	public function update_landmark_by_slug( WP_REST_Request $request ) {
		// Verify nonce for security.
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_Error(
				'invalid_nonce',
				__( 'Security check failed', 'pageflash' ),
				array( 'status' => 403 )
			);
		}

		$slug = $request->get_param( 'slug' );
		$data = get_option( 'pageflash_landmarks', array() );

		// Get the data array from the landmarks option.
		if ( ! isset( $data['data'] ) || ! is_array( $data['data'] ) ) {
			return new WP_Error(
				'invalid_data',
				__( 'Landmark data is not initialized', 'pageflash' ),
				array( 'status' => 500 )
			);
		}

		// Get the update data from request body.
		$body_params = $request->get_json_params();
		if ( empty( $body_params ) ) {
			$body_params = $request->get_body_params();
		}

		// Remove slug from update data to prevent overwriting.
		unset( $body_params['slug'] );

		if ( empty( $body_params ) ) {
			return new WP_Error(
				'missing_data',
				__( 'No valid update data provided', 'pageflash' ),
				array( 'status' => 400 )
			);
		}

		// Sanitize and prepare update data.
		$update_data = $this->sanitize_landmark_data( $body_params );

		// Find and update the landmark.
		$found = false;
		foreach ( $data['data'] as $key => $item ) {
			if ( isset( $item['slug'] ) && $item['slug'] === $slug ) {
				$data['data'][ $key ] = $this->deep_merge( $item, $update_data );
				$found                = true;
				break;
			}
		}

		if ( ! $found ) {
			return new WP_Error(
				'not_found',
				__( 'Landmark not found', 'pageflash' ),
				array( 'status' => 404 )
			);
		}

		update_option( 'pageflash_landmarks', $data );
		// Return the updated landmark.
		return rest_ensure_response(
			array(
				'message' => __( 'Landmark updated successfully', 'pageflash' ),
				'status'  => 200,
				'data'    => $data['data'][ $key ],
			)
		);
	}

	/**
	 * Recursively sanitize landmark data.
	 *
	 * @since 1.0.0
	 * @param mixed $data Data to sanitize.
	 * @return mixed Sanitized data.
	 */
	private function sanitize_landmark_data( $data ) {
		if ( is_array( $data ) ) {
			$sanitized = array();
			foreach ( $data as $key => $value ) {
				$sanitized_key = sanitize_key( $key );
				$sanitized[ $sanitized_key ] = $this->sanitize_landmark_data( $value );
			}
			return $sanitized;
		} elseif ( is_bool( $data ) ) {
			return (bool) $data;
		} elseif ( is_numeric( $data ) ) {
			return is_float( $data ) ? (float) $data : (int) $data;
		} else {
			return sanitize_text_field( $data );
		}
	}

	/**
	 * Recursively merge arrays, preserving nested structures.
	 *
	 * @since 1.0.0
	 * @param array $original Original array.
	 * @param array $updates Updates to merge.
	 * @return array Merged array.
	 */
	private function deep_merge( $original, $updates ) {
		foreach ( $updates as $key => $value ) {
			if ( is_array( $value ) && isset( $original[ $key ] ) && is_array( $original[ $key ] ) ) {
				$original[ $key ] = $this->deep_merge( $original[ $key ], $value );
			} else {
				$original[ $key ] = $value;
			}
		}
		return $original;
	}

	/**
	 * Checks if current user has permission to update data.
	 *
	 * @since 1.0.0
	 * @return bool True if user has permission, false otherwise.
	 */
	public function check_permissions() {
		return current_user_can( 'manage_options' );
	}
}
