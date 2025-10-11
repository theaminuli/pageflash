<?php

namespace PageFlash\Landmark;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * PageFlash LandmarkList Class.
 *
 * Handles the registration and initialization of default landmarks for PageFlash.
 *
 * @package PageFlash
 * @since PageFlash 1.0.0
 */
class LandmarkList {

	/**
	 * Constructor for the LandmarkList class.
	 *
	 * Hooks into the admin_init action to register landmarks.
	 *
	 * @since PageFlash 1.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'pageflash_register_landmarks' ) );
	}

	/**
	 * Generate a unique ID for a landmark based on its slug.
	 *
	 * Uses a consistent hash-based approach to generate unique IDs.
	 *
	 * @since PageFlash 1.0.0
	 * @param string $slug The landmark slug.
	 * @return int The unique ID.
	 */
	private function generate_landmark_id( $slug ) {
		// Use crc32 to generate a consistent numeric ID from the slug.
		// Adding 1000 as base to ensure IDs are in a reasonable range.
		return abs( crc32( 'pageflash_landmark_' . $slug ) % 9000 ) + 1000;
	}

	/**
	 * Registers default landmarks into the WordPress options table.
	 *
	 * If the option already exists, it will update it only if needed.
	 *
	 * @since PageFlash 1.0.0
	 */
	public function pageflash_register_landmarks() {
		$defaults = array(
			'version' => '1.0.0',
			'author'  => 'PageFlash Team',
			'url'     => 'https://github.com/theaminuli/pageflash/',
			'message' => __( 'PageFlash Dashboard Data', 'pageflash' ),
			'data'    => [
				'quicklink' => array(
					'id'          => $this->generate_landmark_id( 'quicklink' ),
					'label'       => __( 'Quicklink', 'pageflash' ),
					'description' => __( "Quicklink, an active plugin, you'll experience a 50% increase in conversions and enjoy 4x faster page loading. Boost your website's speed, increase user engagement", 'pageflash' ),
					'active'      => true,
					'slug'        => 'quicklink',
					'package'     => 'free',
					'unique_id'   => wp_unique_id( 'pageflash-quicklink-' ), // For HTML elements.
				),
				'noreload' => array(
					'id'          => $this->generate_landmark_id( 'noreload' ),
					'label'       => __( 'NoReload', 'pageflash' ),
					'description' => __( 'NoReload prevents page reloads by intercepting navigation and loading content dynamically.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'noreload',
					'package'     => 'free',
					'unique_id'   => wp_unique_id( 'pageflash-noreload-' ), // For HTML elements.
				),
				'instantpage' => array(
					'id'          => $this->generate_landmark_id( 'instantpage' ),
					'label'       => __( 'InstantPage', 'pageflash' ),
					'description' => __( 'InstantPage uses just-in-time preloading — it preloads a page right before a user clicks on it.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'instantpage',
					'package'     => 'free',
					'unique_id'   => wp_unique_id( 'pageflash-instantpage-' ), // For HTML elements.
				),
			]
		);

		// Allow developers to filter and modify default landmarks.
		$landmarks = apply_filters( 'pageflash_landmarks', $defaults );

		$existing = get_option( 'pageflash_landmarks' );

		// If no existing data, add it
		if ( false === $existing ) {
			add_option( 'pageflash_landmarks', $landmarks );
		}
		// If data already exists and is different, update it
		elseif ( $existing !== $landmarks ) {
			update_option( 'pageflash_landmarks', $landmarks );
		}
	}
}
