<?php

namespace TheAminul\PageFlash\Landmark;

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
	 * Menu Properties for Dynamic Header:
	 * - 'menu'      => Menu slug (e.g., 'general', 'preloading')
	 * - 'menuOrder' => Sort order (lower = earlier in menu, default 999)
	 * - 'menuLabel' => Display label in header menu (optional, defaults to menu slug)
	 * - 'menuIcon'  => Icon for menu button (JSX string, optional)
	 *
	 * @since PageFlash 1.0.0
	 */
	public function pageflash_register_landmarks() {
		$defaults = array(
			'version' => '1.0.0',
			'author'  => 'PageFlash Team',
			'url'     => 'https://github.com/theaminuli/pageflash/',
			'message' => esc_html__( 'PageFlash Dashboard Data', 'pageflash' ),
			'data'    => array(
				'quicklink' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Quicklink', 'pageflash' ),
					'description' => esc_html__( "Quicklink, an active plugin, you'll experience a 50% increase in conversions and enjoy 4x faster page loading. Boost your website's speed, increase user engagement", 'pageflash' ),
					'active'      => true,
					'slug'        => 'quicklink',
					'menu'        => 'preloading',
					'menuOrder'   => 2,
					'menuLabel'   => esc_html__( 'Preloading', 'pageflash' ),
					'package'     => 'free',
				),
				'instantpage' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'InstantPage', 'pageflash' ),
					'description' => esc_html__( 'InstantPage uses just-in-time preloading — it preloads a page right before a user clicks on it.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'instantpage',
					'menu'        => 'preloading',
					'menuOrder'   => 2,
					'menuLabel'   => esc_html__( 'Preloading', 'pageflash' ),
					'package'     => 'free',
				),
				'dashicons' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Disable Dashicons', 'pageflash' ),
					'description' => esc_html__( 'Disable Dashicons on the frontend for non-logged-in users to improve performance.', 'pageflash' ),
					'active'      => true,
					'slug'        => 'dashicons',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
				),
				'embeds' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Disable Embeds', 'pageflash' ),
					'description' => esc_html__( 'Disable the WordPress oEmbed feature to reduce unnecessary HTTP requests and improve page load times.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'embeds',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
				),
				'emojis' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Disable Emojis', 'pageflash' ),
					'description' => esc_html__( 'Disable the built-in WordPress emoji scripts and styles to reduce page size and improve load times.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'emojis',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
				),
				'heartbeat' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Disable Heartbeat', 'pageflash' ),
					'description' => esc_html__( 'Disable WordPress Heartbeat everywhere or in certain areas (used for auto saving and revision tracking).', 'pageflash' ),
					'active'      => false,
					'slug'        => 'heartbeat',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
					'input'       => array(
						'behavior' => array(
							'type'        => 'select',
							'label'       => esc_html__( 'Behavior', 'pageflash' ),
							'description' => esc_html__( 'Choose where to disable the Heartbeat API.', 'pageflash' ),
							'value'       => 'disable_everywhere',
							'default'     => 'disable_everywhere',
							'options'     => array(
								'default'            => esc_html__( 'Default Behavior', 'pageflash' ),
								'disable_everywhere' => esc_html__( 'Disable Everywhere', 'pageflash' ),
								'allow_posts'        => esc_html__( 'Only Allow When Editing Posts/Pages', 'pageflash' ),
							),
						),
						'frequency' => array(
							'type'        => 'select',
							'label'       => esc_html__( 'Frequency', 'pageflash' ),
							'description' => esc_html__( 'Controls how often the WordPress Heartbeat API is allowed to run.', 'pageflash' ),
							'value'       => 60,
							'default'     => 60,
							'options'     => array(
								15  => esc_html__( '15 Seconds', 'pageflash' ),
								30  => esc_html__( '30 Seconds', 'pageflash' ),
								45  => esc_html__( '45 Seconds', 'pageflash' ),
								60  => esc_html__( '60 Seconds', 'pageflash' ),
								120 => esc_html__( '120 Seconds', 'pageflash' ),
								300 => esc_html__( '300 Seconds', 'pageflash' ),
							),
						),
					),
				),
			),
		);

		// Allow developers to filter and modify default landmarks.
		$landmarks = apply_filters( 'pageflash_landmarks', $defaults );
		$existing  = get_option( 'pageflash_landmarks' );

		// If no existing data, add it.
		if ( false === $existing ) {
			add_option( 'pageflash_landmarks', $landmarks );
			return;
		}

		// Recursively sync existing data with defaults.
		$sync_manager = new LandmarkSyncManager();
		$sync_manager->pageflash_sync_properties( $existing, $landmarks );
	}
}
