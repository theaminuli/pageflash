<?php

namespace PageFlash\Landmark;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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
	 * Initialize REST API routes.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register REST API routes for landmarks.
	 *
	 * GET /wp-json/pageflash/v1/landmarks
	 * POST /wp-json/pageflash/v1/landmarks
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {
		register_rest_route( 'pageflash/v1', '/landmarks', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'pageflash_handle_get_landmarks' ],
			'permission_callback' => '__return_true',
		] );

		register_rest_route( 'pageflash/v1', '/landmarks', [
			'methods'             => 'POST',
			'callback'            => [ $this, 'pageflash_handle_post_landmarks' ],
			'permission_callback' => [ $this, 'check_permissions' ],
		] );
	}

	/**
	 * GET /landmarks
	 *
	 * Retrieve all landmarks or a specific landmark by ID.
	 *
	 * @param {WP_REST_Request} $request
	 * @param {string} [$request->id] Optional landmark ID to fetch a single item
	 *
	 * @return {WP_REST_Response|WP_Error}
	 */
	public function pageflash_handle_get_landmarks( WP_REST_Request $request ) {
		$id    = $request->get_param( 'id' );
		$data  = get_option( 'pageflash_landmarks', [] );
		$items = $data['data'] ?? [];

		if ( $id ) {
			foreach ( $items as $item ) {
				if ( isset( $item['id'] ) && $item['id'] === $id ) {
					return rest_ensure_response( $item );
				}
			}
			return new WP_Error( 'not_found', 'Landmark not found', [ 'status' => 404 ] );
		}

		return rest_ensure_response( $data );
	}

	/**
	 * POST /landmarks
	 *
	 * Add or update landmarks.
	 *
	 * If `id` is provided in request, updates specific landmark.
	 * If no `id`, merges new data into existing landmarks.
	 *
	 * @param {WP_REST_Request} $request
	 * @param {string} [$request->id] Optional landmark ID to update
	 * @param {array} $request->get_json_params() Landmark data to add or update
	 *
	 * @return {WP_REST_Response|WP_Error}
	 */
	public function pageflash_handle_post_landmarks( WP_REST_Request $request ) {
		$id       = $request->get_param( 'id' );
		$new_data = $request->get_json_params();
		$data     = get_option( 'pageflash_landmarks', [] );

		if ( ! isset( $data['data'] ) || ! is_array( $data['data'] ) ) {
			$data['data'] = [];
		}

		// Update specific landmark by ID
		if ( $id ) {
			foreach ( $data['data'] as $key => $item ) {
				if ( isset( $item['id'] ) && $item['id'] === $id ) {
					$data['data'][ $key ] = array_merge( $item, $new_data );
					update_option( 'pageflash_landmarks', $data );
					return rest_ensure_response( $data['data'][ $key ] );
				}
			}
			return new WP_Error( 'not_found', 'Landmark not found', [ 'status' => 404 ] );
		}

		// No ID - merge new data into existing
		if ( ! is_array( $new_data ) ) {
			return new WP_Error( 'invalid_data', 'Invalid landmarks format.', [ 'status' => 400 ] );
		}

		$data['data'] = array_replace_recursive( $data['data'], $new_data );
		update_option( 'pageflash_landmarks', $data );

		return rest_ensure_response( [
			'message' => 'Landmarks merged successfully.',
			'data'    => $data['data'],
		] );
	}

	/**
	 * Permission check for POST requests.
	 *
	 * @return {boolean} True if current user can manage options
	 */
	public function check_permissions() {
		return current_user_can( 'manage_options' );
	}
}
