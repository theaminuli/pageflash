<?php
/**
 * Disable REST API Feature
 *
 * @package TheAminul\PageFlash\Landmark\General
 * @since 1.2.0
 */

namespace TheAminul\PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DisableRestAPI {
	/**
	 * REST API restriction mode
	 *
	 * @since 1.2.0
	 * @var string
	 */
	private $rest_api;

	/**
	 * Constructor
	 *
	 * @param string $rest_api REST API restriction setting.
	 */
	public function __construct( $rest_api ) {
		$this->rest_api = $rest_api;
		add_filter( 'rest_authentication_errors', array( $this, 'rest_authentication_errors' ), 20 );
	}

	/**
	 * Filter REST API authentication errors
	 *
	 * @param \WP_Error|null|bool $result Current authentication result.
	 * @return \WP_Error|null|bool Modified authentication result or WP_Error if access denied.
	 */
	public function rest_authentication_errors( $result ) {
		if ( ! empty( $result ) ) {
			return $result;
		}

		$disabled = false;

		$rest_route = isset( $GLOBALS['wp']->query_vars['rest_route'] ) ? $GLOBALS['wp']->query_vars['rest_route'] : '';

		if ( $this->is_exception_route( $rest_route ) ) {
			return $result;
		}

		if ( $this->rest_api === 'disabled' && ! current_user_can( 'manage_options' ) ) {
			$disabled = true;
		} elseif ( $this->rest_api === 'disabled_when_logged_out' && ! is_user_logged_in() ) {
			$disabled = true;
		}

		if ( $disabled ) {
			return new \WP_Error(
				'rest_authentication_error',
				__( 'Sorry, you do not have permission to make REST API requests.', 'pageflash' ),
				array( 'status' => 401 )
			);
		}

		return $result;
	}

	/**
	 * Check if the REST route matches exception patterns
	 *
	 * @param string $rest_route The REST API route to check.
	 * @return bool True if route matches an exception, false otherwise.
	 */
	private function is_exception_route( $rest_route ) {
		$exceptions = apply_filters(
			'pageflash_rest_api_exceptions',
			array(
				'contact-form-7',
				'wordfence',
				'elementor',
				'ws-form',
				'litespeed',
				'wp-recipe-maker',
				'iawp',
				'sureforms',
				'surecart',
				'sliderrevolution',
			)
		);

		foreach ( $exceptions as $exception ) {
			if ( strpos( $rest_route, $exception ) !== false ) {
				return true;
			}
		}

		return false;
	}
}
