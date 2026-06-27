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
					'description' => esc_html__( "Quicklink, an active plugin, you'll experience a 50% increase in conversions and enjoy 4x faster page loading. Boost your website speed, increase user engagement", 'pageflash' ),
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
							'child_id'    => wp_unique_id( 'pf-' ),
							'type'        => 'select',
							'label'       => esc_html__( 'Behavior', 'pageflash' ),
							'description' => esc_html__( 'Choose where to disable the Heartbeat API.', 'pageflash' ),
							'slug'        => 'behavior',
							'value'       => 'disable_everywhere',
							'default'     => 'disable_everywhere',
							'options'     => array(
								'default'            => esc_html__( 'Default Behavior', 'pageflash' ),
								'disable_everywhere' => esc_html__( 'Disable Everywhere', 'pageflash' ),
								'allow_posts'        => esc_html__( 'Only Allow When Editing Posts/Pages', 'pageflash' ),
								'disable_dashboard'  => esc_html__( 'Disable on admin Dashboard Only', 'pageflash' ),
							),
						),
						'frequency' => array(
							'child_id'    => wp_unique_id( 'pf-' ),
							'type'        => 'select',
							'label'       => esc_html__( 'Frequency', 'pageflash' ),
							'description' => esc_html__( 'Controls how often the WordPress Heartbeat API is allowed to run.', 'pageflash' ),
							'slug'        => 'frequency',
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
				'rest_api' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'select',
					'label'       => esc_html__( 'Disable REST API', 'pageflash' ),
					'description' => esc_html__( 'Disable the WordPress REST API for non-authenticated users to enhance security and reduce unnecessary requests.', 'pageflash' ),
					'value'       => '',
					'default'     => '',
					'options'     => array(
						''                         => esc_html__( 'Default Behavior (Enabled)', 'pageflash' ),
						'disabled'                 => esc_html__( 'Disable for Non-Authenticated Users', 'pageflash' ),
						'disabled_when_logged_out' => esc_html__( 'Disable When Logged Out.', 'pageflash' ),
					),
					'slug'        => 'rest_api',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
				),
				'rest_api_link' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Remove REST API Link', 'pageflash' ),
					'description' => esc_html__( 'Removes REST API link tag from the front end and the REST API header link from page requests.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'rest_api_link',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
				),
				'wp_version' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Hide WordPress Version', 'pageflash' ),
					'description' => esc_html__( 'Hide the WordPress version number from the site header, Meta tag and RSS feeds to improve security.', 'pageflash' ),
					'active'      => true,
					'slug'        => 'wp_version',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
				),
				'xmlrpc' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Disable XML-RPC', 'pageflash' ),
					'description' => esc_html__( 'Disable the XML-RPC feature to enhance security and reduce unnecessary requests.', 'pageflash' ),
					'active'      => true,
					'slug'        => 'xmlrpc',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
				),
				'jquery_migrate' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Remove jQuery Migrate', 'pageflash' ),
					'description' => esc_html__( 'Remove jQuery Migrate script to improve performance on the frontend (jquery-migrate.min.js).', 'pageflash' ),
					'active'      => false,
					'slug'        => 'jquery_migrate',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
				),
				'remove_rsd_link' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Remove RSD Link', 'pageflash' ),
					'description' => esc_html__( 'Remove the Really Simple Discovery (RSD) link tag from the <head> section. This link is used for blog clients and is generally not needed.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'remove_rsd_link',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
				),
				'remove_shortlink' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Remove Shortlink', 'pageflash' ),
					'description' => esc_html__( 'Remove the WordPress shortlink tag from the <head> section and the HTTP Link header to reduce page bloat.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'remove_shortlink',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
				),
				'remove_feed_links' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Remove Feed Links', 'pageflash' ),
					'description' => esc_html__( 'Remove RSS/Atom feed link tags from the <head> section. Useful if you do not use RSS feeds on your site.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'remove_feed_links',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
				),
				'disable_self_pingbacks' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Disable Self Pingbacks', 'pageflash' ),
					'description' => esc_html__( 'Prevent WordPress from sending pingbacks to your own site when you link to your own content.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'disable_self_pingbacks',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
				),
				'disable_comments' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Disable Comments', 'pageflash' ),
					'description' => esc_html__( 'Completely disable the WordPress commenting system across all post types. Removes comment forms, menus, feeds, and admin pages.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'disable_comments',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
				),
				'limit_post_revisions' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'select',
					'label'       => esc_html__( 'Limit Post Revisions', 'pageflash' ),
					'description' => esc_html__( 'Limit the number of post revisions stored in the database to reduce database bloat.', 'pageflash' ),
					'value'       => '',
					'default'     => '',
					'options'     => array(
						''   => esc_html__( 'Default Behavior', 'pageflash' ),
						'0'  => esc_html__( 'Disable Revisions', 'pageflash' ),
						'1'  => esc_html__( 'Keep 1 Revision', 'pageflash' ),
						'2'  => esc_html__( 'Keep 2 Revisions', 'pageflash' ),
						'3'  => esc_html__( 'Keep 3 Revisions', 'pageflash' ),
						'5'  => esc_html__( 'Keep 5 Revisions', 'pageflash' ),
						'10' => esc_html__( 'Keep 10 Revisions', 'pageflash' ),
					),
					'slug'        => 'limit_post_revisions',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
				),
				'remove_global_styles' => array(
					'id'          => wp_unique_id( 'pf-' ),
					'type'        => 'switch',
					'label'       => esc_html__( 'Remove Global Styles', 'pageflash' ),
					'description' => esc_html__( 'Remove the WordPress global styles inline CSS added in WP 5.9+. Useful when using a classic theme or custom CSS framework.', 'pageflash' ),
					'active'      => false,
					'slug'        => 'remove_global_styles',
					'menu'        => 'general',
					'menuOrder'   => 1,
					'menuLabel'   => esc_html__( 'General', 'pageflash' ),
					'package'     => 'free',
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
