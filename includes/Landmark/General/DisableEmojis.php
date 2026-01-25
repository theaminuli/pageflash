<?php
/**
 * Disable Emojis Feature
 *
 * @package TheAminul\PageFlash\Landmark\General
 * @since 1.2.0
 */

namespace TheAminul\PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class DisableEmojis
 *
 * Removes WordPress emoji scripts and styles to improve performance.
 *
 * @since 1.2.0
 */
class DisableEmojis {

	/**
	 * Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'disable_emojis' ) );
	}

	/**
	 * Disable emojis
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

		add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojis_tinymce' ) );
		add_filter( 'wp_resource_hints', array( $this, 'disable_emojis_dns_prefetch' ), 10, 2 );
		add_filter( 'emoji_svg_url', array( $this, 'disable_emojis_svg_url' ) );
	}

	/**
	 * Disable emojis in TinyMCE
	 *
	 * @since 1.2.0
	 * @param array $plugins TinyMCE plugins.
	 * @return array Modified plugins array.
	 */
	public function disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		}
		return array();
	}

	/**
	 * Remove emoji DNS prefetch
	 *
	 * @since 1.2.0
	 * @param array  $urls          URLs to print for resource hints.
	 * @param string $relation_type The relation type.
	 * @return array Modified URLs array.
	 */
	public function disable_emojis_dns_prefetch( $urls, $relation_type ) {
		if ( 'dns-prefetch' === $relation_type ) {
			$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/15.1.0/svg/' );
			$urls          = array_diff( $urls, array( $emoji_svg_url ) );
		}
		return $urls;
	}

	/**
	 * Disable emoji SVG URL
	 *
	 * @since 1.2.0
	 * @return string Empty string to disable emoji SVG URL.
	 */
	public function disable_emojis_svg_url( $url ) {
		if ( is_admin() ) {
			return $url;
		}
		return '';
	}
}
