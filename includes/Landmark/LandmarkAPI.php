<?php

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
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Registers REST API routes for landmarks.
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
	 * Handles POST requests to add or update landmarks.
	 *
	 * @param WP_REST_Request $request The REST request containing the landmark data.
	 * @return WP_REST_Response|WP_Error
	 */

	public function pageflash_handle_get_landmarks( WP_REST_Request $request ) {
		$id    = $request->get_param( 'id' );
		$data  = get_option( 'pageflash_landmarks', [] );
		$items = $data['data'] ?? $data;

		if ( $id ) {
			$id = (int) $id;
			foreach ( $items as $item ) {
				if ( isset( $item['id'] ) && (int) $item['id'] === $id ) {
					return rest_ensure_response( $item );
				}
			}
			return new WP_Error( 'not_found', 'Landmark not found', [ 'status' => 404 ] );
		}

		// No ID - return all landmarks
		return rest_ensure_response( $data );
	}

	/**
	 * Handles POST requests to add or update landmarks.
	 *
	 * If no ID is provided, it replaces all landmarks with the new data.
	 * If an ID is provided, it updates the specific landmark with that ID.
	 *
	 * @param WP_REST_Request $request The REST request containing the landmark data.
	 * @return WP_REST_Response|WP_Error
	 */

	public function pageflash_handle_post_landmarks( WP_REST_Request $request ) {
		$id       = $request->get_param( 'id' );
		$new_data = $request->get_json_params();
		$data     = get_option( 'pageflash_landmarks', [] );

		// If no id param, replace all landmarks
		if ( ! $id ) {
			if ( ! is_array( $new_data ) ) {
				return new WP_Error( 'invalid_data', 'Invalid landmarks format.', [ 'status' => 400 ] );
			}
			update_option( 'pageflash_landmarks', $new_data );
			return rest_ensure_response( [
				'message' => 'All landmarks updated successfully.',
				'data'    => $new_data,
			] );
		}

		// If ID param exists, update specific landmark
		$id = (int) $id;

		if ( ! isset( $data['data'] ) || ! is_array( $data['data'] ) ) {
			return new WP_Error( 'no_data', 'Landmark data is not initialized', [ 'status' => 400 ] );
		}

		foreach ( $data['data'] as $key => $item ) {
			if ( isset( $item['id'] ) && (int) $item['id'] === $id ) {
				$data['data'][ $key ] = array_merge( $item, $new_data );
				update_option( 'pageflash_landmarks', $data );
				return rest_ensure_response( $data['data'][ $key ] );
			}
		}

		return new WP_Error( 'not_found', 'Landmark not found', [ 'status' => 404 ] );
	}

	/**
	 * Checks if current user has permission to update data.
	 *
	 * @return bool
	 */
	public function check_permissions() {
		return current_user_can( 'manage_options' );
	}
}
