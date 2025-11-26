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
			'data'    => array(
				'quicklink' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type' 		  => 'switch',
					'label'       => __( 'Quicklink', 'pageflash' ),
					'description' => __( "Quicklink, an active plugin, you'll experience a 50% increase in conversions and enjoy 4x faster page loading. Boost your website's speed, increase user engagement", 'pageflash' ),
					'active'      => true,
					'slug'        => 'quicklink',
					'menu'        => 'preloading',	
					'package'     => 'free',
				),
				'instantpage' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type' 		  => 'switch',
					'label'       => __( 'InstantPage', 'pageflash' ),
					'description' => __( 'InstantPage uses just-in-time preloading — it preloads a page right before a user clicks on it.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'instantpage',
					'menu'        => 'preloading',
					'package'     => 'free',
				),
				'dashicons' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type' 		  => 'switch',
					'label'       => __( 'Disable Dashicons', 'pageflash' ),
					'description' => __( 'Disable Dashicons on the frontend for non-logged-in users to improve performance.', 'pageflash' ),
					'active'      => true,
					'slug'        => 'dashicons',
					'menu'        => 'general',
					'package'     => 'free',
				),
				'embeds' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type' 		  => 'switch',
					'label'       => __( 'Disable Embeds', 'pageflash' ),
					'description' => __( 'Disable the WordPress oEmbed feature to reduce unnecessary HTTP requests and improve page load times.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'embeds',
					'menu'        => 'general',
					'package'     => 'free',
				),
				'emojis' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'		  => 'switch',
					'label'       => __( 'Disable Emojis', 'pageflash' ),
					'description' => __( 'Disable the built-in WordPress emoji scripts and styles to reduce page size and improve load times.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'emojis',
					'menu'        => 'general',
					'package'     => 'free',
				),
				'heartbeat' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'		  => 'switch',
					'label'       => __( 'Disable Heartbeat', 'pageflash' ),
					'description' => __( 'Disable WordPress Heartbeat everywhere or in certain areas (used for auto saving and revision tracking).', 'pageflash' ),
					'active'      => false,
					'slug'        => 'heartbeat',
					'menu'        => 'general',
					'package'     => 'free',
					'input'       => array(
						'behavior' => array(
							'type'        => 'select',
							'label'       => __( 'Behavior', 'pageflash' ),
							'description' => __( 'Choose where to disable the Heartbeat API.', 'pageflash' ),
							'value'       => 'disable_everywhere',
							'default'     => 'disable_everywhere',
							'options'     => array(
								'default'        => __( 'Default Behavior', 'pageflash' ),
								'disable_everywhere' => __( 'Disable Everywhere', 'pageflash' ),
								'allow_posts'   => __( 'Only Allow When Editing Posts/Pages', 'pageflash' ),
							),
						),
						'frequency' => array(
							'type'        => 'select',
							'label'       => __( 'Frequency', 'pageflash' ),
							'description' => __( 'Controls how often the WordPress Heartbeat API is allowed to run.', 'pageflash' ),
							'value'       => 60,
							'default'     => 60,
							'options'     => array(
								15  => __( '15 Seconds', 'pageflash' ),
								30  => __( '30 Seconds', 'pageflash' ),
								45  => __( '45 Seconds', 'pageflash' ),
								60  => __( '60 Seconds', 'pageflash' ),
								120 => __( '120 Seconds', 'pageflash' ),
								300 => __( '300 Seconds', 'pageflash' ),
							),
						),
					),
					
				),
			),
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
