<?php

namespace PageFlash\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ActionLinks {

	/**
	 * Constructor for the ActionLinks class.
	 *
	 * Adds filters for plugin action links and row meta links.
	 *
	 * @since PageFlash 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_filter( 'plugin_action_links_' . PAGEFLASH_PLUGIN_BASE, array( $this, 'pageflash_plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'pageflash_plugin_row_meta' ), 10, 2 );
	}

	/**
	 * Plugin action links.
	 *
	 * Adds action links to the plugin list table.
	 *
	 * Fired by the `plugin_action_links` filter.
	 *
	 * @since PageFlash 1.0.0
	 * @access public
	 *
	 * @param array $links An array of plugin action links.
	 *
	 * @return array An array of plugin action links.
	 */
	public function pageflash_plugin_action_links( $links ) {
		$settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'options-general.php?page=pageflash_settings' ), esc_html__( 'Settings', 'pageflash' ) );
		array_unshift( $links, $settings_link );
		// $links['go_pro'] = sprintf('<a href="%1$s" target="_blank" class="pageflash-plugins-gopro">%2$s</a>', '#', esc_html__('Get PageFlash Pro', 'pageflash'));
		return $links;
	}

	/**
	 * Plugin row meta.
	 *
	 * Adds row meta links to the plugin list table.
	 *
	 * Fired by the `plugin_row_meta` filter.
	 *
	 * @since PageFlash 1.0.0
	 * @access public
	 *
	 * @param array  $plugin_meta An array of the plugin's metadata, including
	 *                            the version, author, author URI, and plugin URI.
	 * @param string $plugin_file Path to the plugin file, relative to the plugins
	 *                            directory.
	 *
	 * @return array An array of plugin row meta links.
	 */
	public function pageflash_plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( PAGEFLASH_PLUGIN_BASE === $plugin_file ) {
			// Loop through the array with the reference to modify the original array elements
			foreach ( $plugin_meta as &$value ) {
				if ( strpos( $value, 'Visit plugin site' ) !== false ) {
					$value = str_replace( 'Visit plugin site', 'View Details', $value );
				}
			}

			$row_meta = array(
				'docs' => '<a href="https://github.com/theaminuldev/pageflash" aria-label="' . esc_attr( esc_html__( 'View PageFlash Documentation', 'pageflash' ) ) . '" target="_blank">' . esc_html__( 'Docs & FAQs', 'pageflash' ) . '</a>',
				// 'video' => '<a href="#" aria-label="' . esc_attr(esc_html__('View PageFlash Video Tutorials', 'pageflash')) . '" target="_blank">' . esc_html__('Video Tutorials', 'pageflash') . '</a>',
			);
			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}
}
