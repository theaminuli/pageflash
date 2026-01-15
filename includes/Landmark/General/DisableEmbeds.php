<?php
/**
 * Disable Embeds Feature
 *
 * @package PageFlash\Landmark\General
 * @since 1.2.0
 */

namespace PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class DisableEmbeds
 *
 * Disables WordPress oEmbed functionality to improve performance.
 *
 * @package PageFlash\Landmark\General
 * @since 1.2.0
 */
class DisableEmbeds {

	/**
	 * Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'disable_embeds' ), 9999 );
	}

	/**
	 * Disable embeds
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function disable_embeds() {
		global $wp;
		$wp->public_query_vars = array_diff( $wp->public_query_vars, array( 'embed' ) );

		remove_action( 'rest_api_init', 'wp_oembed_register_route' );
		remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );

		add_filter( 'tiny_mce_plugins', array( $this, 'disable_embeds_tiny_mce_plugin' ) );
		add_filter( 'rewrite_rules_array', array( $this, 'disable_embeds_rewrites' ) );
	}

	/**
	 * Disable embeds in TinyMCE
	 *
	 * @since 1.2.0
	 * @param array $plugins TinyMCE plugins.
	 * @return array Modified plugins array.
	 */
	public function disable_embeds_tiny_mce_plugin( $plugins ) {
		return array_diff( $plugins, array( 'wpembed' ) );
	}

	/**
	 * Remove embed rewrite rules
	 *
	 * @since 1.2.0
	 * @param array $rules Rewrite rules.
	 * @return array Modified rules array.
	 */
	public function disable_embeds_rewrites( $rules ) {
		foreach ( $rules as $rule => $rewrite ) {
			if ( false !== strpos( $rewrite, 'embed=true' ) ) {
				unset( $rules[ $rule ] );
			}
		}
		return $rules;
	}
}
