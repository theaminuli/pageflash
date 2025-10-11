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
					'id'          => 1001,
					'label'       => __( 'Quicklink', 'pageflash' ),
					'description' => __( "Quicklink, an active plugin, you'll experience a 50% increase in conversions and enjoy 4x faster page loading. Boost your website's speed, increase user engagement", 'pageflash' ),
					'active'      => true,
					'slug'        => 'quicklink',
					'package'     => 'free',
				),
				'instantpage' => array(
					'id'          =>  1002,
					'label'       => __( 'InstantPage', 'pageflash' ),
					'description' => __( 'InstantPage uses just-in-time preloading — it preloads a page right before a user clicks on it.', 'pageflash' ),
					'active'      => true,
					'slug'        => 'instantpage',
					'package'     => 'free',
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
