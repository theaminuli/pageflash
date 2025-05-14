<?php
/*
 * @package     PageFlash
 * @author      theaminul
 * @copyright   2024 theaminul.com
 * @license     GNU General Public License v3 or later
 * @license URI https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @wordpress-plugin
 * Plugin Name: PageFlash
 * Plugin URI:  https://github.com/theaminuldev/pageflash
 * Author:      theaminul
 * Author URI:  https://theaminul.com
 * Version:     1.1.0
 * Stable tag:  1.1.0
 * Requires at least: 6.0
 * Tested up to: 6.5.5
 * Requires PHP: 7.4
 * License:     GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: pageflash
 * Description: PageFlash - Fast and Efficient Headless Browser WordPress Plugin. By using PageFlash, an active plugin, you'll experience a 50% increase in conversions and enjoy 4x faster page loading. ⚡️ Boost your website's speed, increase user engagement 💬, and supercharge your online presence 🚀. - NewEgg
 * Tags:        headless-browser, pageflash, prefetches, quicklink, quickload, performance, speed, fast, prefetch, seo preconnect, optimization
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$pageflash_version = get_file_data( __FILE__, array( 'Version' => 'Version' ) );
define( 'PAGEFLASH_VERSION', $pageflash_version['Version'] );
define( 'PAGEFLASH_DIR', __DIR__ );
define( 'PAGEFLASH_FILE', __FILE__ );
define( 'PAGEFLASH_PLUGIN_BASE', plugin_basename( PAGEFLASH_FILE ) );
define( 'PAGEFLASH_PATH', plugin_dir_path( PAGEFLASH_FILE ) );
define( 'PAGEFLASH_URL', plugins_url( '/', PAGEFLASH_FILE ) );
define( 'PAGEFLASH_ASSETS_PATH', PAGEFLASH_PATH . 'assets/' );
define( 'PAGEFLASH_ASSETS_URL', PAGEFLASH_URL . 'assets/' );
define( 'PAGEFLASH_ENV', WP_DEBUG ? 'development' : 'production' );

add_action( 'plugins_loaded', 'pageflash_load_plugin_textdomain' );

if ( ! version_compare( PHP_VERSION, '7.0', '>=' ) ) {
	add_action( 'admin_notices', 'pageflash_fail_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '5.9', '>=' ) ) {
	add_action( 'admin_notices', 'pageflash_fail_wp_version' );
} else {
	require PAGEFLASH_PATH . 'plugin.php';
}

/**
 * Load PageFlash textdomain.
 *
 * Load gettext translate for PageFlash text domain.
 *
 * @since PageFlash 1.0.0
 *
 * @return void
 */
function pageflash_load_plugin_textdomain() {
	load_plugin_textdomain( 'pageflash' );
}

/**
 * PageFlash admin notice for minimum PHP version.
 *
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @since PageFlash 1.0.0
 *
 * @return void
 */
function pageflash_fail_php_version() {
	$message = sprintf(
		/* translators: 1: `<h3>` opening tag, 2: `</h3>` closing tag, 3: PHP version. 4: Link opening tag, 5: Link closing tag. */
		esc_html__( '%1$sPageFlash isn’t running because PHP is outdated.%2$s Update to PHP version %3$s and get back to using PageFlash! %4$sShow me how%5$s', 'pageflash' ),
		'<h3>',
		'</h3>',
		'7.0',
		'<a href="#" target="_blank">',
		'</a>'
	);
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * PageFlash admin notice for minimum WordPress version.
 *
 * Warning when the site doesn't have the minimum required WordPress version.
 *
 * @since PageFlash 1.0.0
 *
 * @return void
 */
function pageflash_fail_wp_version() {
	$message = sprintf(
		/* translators: 1: `<h3>` opening tag, 2: `</h3>` closing tag, 3: WP version. 4: Link opening tag, 5: Link closing tag. */
		esc_html__( '%1$sPageFlash isn’t running because WordPress is outdated.%2$s Update to version %3$s and get back to using PageFlash! %4$sShow me how%5$s', 'pageflash' ),
		'<h3>',
		'</h3>',
		'5.9',
		'<a href="#" target="_blank">',
		'</a>'
	);
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}
