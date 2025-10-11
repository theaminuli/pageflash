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

		// GET /landmark/:id - Get landmark by ID.
		register_rest_route(
			'pageflash/v1',
			'/landmark/(?P<id>\d+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_landmark_by_id' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'id' => array(
						'required'          => true,
						'validate_callback' => function ( $param ) {
							return is_numeric( $param );
						},
						'sanitize_callback' => 'absint',
					),
				),
			)
		);

		// PUT /landmark/:id - Update landmark by ID.
		register_rest_route(
			'pageflash/v1',
			'/landmark/(?P<id>\d+)',
			array(
				'methods'             => 'PUT',
				'callback'            => array( $this, 'update_landmark_by_id' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'                => array(
					'id'     => array(
						'required'          => true,
						'validate_callback' => function ( $param ) {
							return is_numeric( $param );
						},
						'sanitize_callback' => 'absint',
					),
					'active' => array(
						'required'          => false,
						'validate_callback' => function ( $param ) {
							return is_bool( $param );
						},
						'sanitize_callback' => 'rest_sanitize_boolean',
					),
				),
			)
		);

		// POST /landmarks - Legacy endpoint (kept for backward compatibility).
		register_rest_route(
			'pageflash/v1',
			'/landmarks',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'pageflash_handle_post_landmarks' ),
				'permission_callback' => array( $this, 'check_permissions' ),
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
	// phpcs:enable Generic.CodeAnalysis.UnusedFunctionParameter.Found

	/**
	 * Get landmark by ID.
	 *
	 * Handles GET requests to /landmark/:id endpoint.
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $request The REST request object.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error on failure.
	 */
	public function get_landmark_by_id( WP_REST_Request $request ) {
		$id   = $request->get_param( 'id' );
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

		// Search for the landmark by ID.
		foreach ( $items as $item ) {
			if ( isset( $item['id'] ) && (int) $item['id'] === (int) $id ) {
				return rest_ensure_response( $item );
			}
		}

		return new WP_Error(
			'not_found',
			__( 'Landmark not found', 'pageflash' ),
			array( 'status' => 404 )
		);
	}

	/**
	 * Update landmark by ID.
	 *
	 * Handles PUT requests to /landmark/:id endpoint.
	 * Updates the landmark with the provided data (supports 'active' field).
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $request The REST request object.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error on failure.
	 */
	public function update_landmark_by_id( WP_REST_Request $request ) {
		// Verify nonce for security.
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_Error(
				'invalid_nonce',
				__( 'Security check failed', 'pageflash' ),
				array( 'status' => 403 )
			);
		}

		$id   = $request->get_param( 'id' );
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

		// Sanitize the active field if provided.
		$update_data = array();
		if ( isset( $body_params['active'] ) ) {
			$update_data['active'] = rest_sanitize_boolean( $body_params['active'] );
		}

		if ( empty( $update_data ) ) {
			return new WP_Error(
				'missing_data',
				__( 'No valid update data provided', 'pageflash' ),
				array( 'status' => 400 )
			);
		}

		// Find and update the landmark.
		$found = false;
		foreach ( $data['data'] as $key => $item ) {
			if ( isset( $item['id'] ) && (int) $item['id'] === (int) $id ) {
				$data['data'][ $key ] = array_merge( $item, $update_data );
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

		// Update the option.
		update_option( 'pageflash_landmarks', $data );

		// Return the updated landmark.
		return rest_ensure_response(
			array(
				'message' => __( 'Landmark updated successfully', 'pageflash' ),
				'data'    => $data['data'][ $key ],
			)
		);
	}

	/**
	 * Handles POST requests to add or update landmarks (legacy endpoint).
	 *
	 * If no ID is provided, it replaces all landmarks with the new data.
	 * If an ID is provided, it updates the specific landmark with that ID.
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $request The REST request containing the landmark data.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error on failure.
	 */
	public function pageflash_handle_post_landmarks( WP_REST_Request $request ) {
		// Verify nonce for security.
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_Error(
				'invalid_nonce',
				__( 'Security check failed', 'pageflash' ),
				array( 'status' => 403 )
			);
		}

		$id       = $request->get_param( 'id' );
		$new_data = $request->get_json_params();
		$data     = get_option( 'pageflash_landmarks', array() );

		// If no id param, replace all landmarks.
		if ( ! $id ) {
			if ( ! is_array( $new_data ) ) {
				return new WP_Error(
					'invalid_data',
					__( 'Invalid landmarks format', 'pageflash' ),
					array( 'status' => 400 )
				);
			}
			update_option( 'pageflash_landmarks', $new_data );
			return rest_ensure_response(
				array(
					'message' => __( 'All landmarks updated successfully', 'pageflash' ),
					'data'    => $new_data,
				)
			);
		}

		// If ID param exists, update specific landmark.
		$id = (int) $id;

		if ( ! isset( $data['data'] ) || ! is_array( $data['data'] ) ) {
			return new WP_Error(
				'no_data',
				__( 'Landmark data is not initialized', 'pageflash' ),
				array( 'status' => 400 )
			);
		}

		foreach ( $data['data'] as $key => $item ) {
			if ( isset( $item['id'] ) && (int) $item['id'] === $id ) {
				$data['data'][ $key ] = array_merge( $item, $new_data );
				update_option( 'pageflash_landmarks', $data );
				return rest_ensure_response( $data['data'][ $key ] );
			}
		}

		return new WP_Error(
			'not_found',
			__( 'Landmark not found', 'pageflash' ),
			array( 'status' => 404 )
		);
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
