<?php
/**
 * Disable Comments Feature
 *
 * @package TheAminul\PageFlash\Landmark\General
 * @since 1.4.0
 */

namespace TheAminul\PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class DisableComments
 *
 * Completely disables the WordPress commenting system site-wide.
 *
 * @since 1.4.0
 */
class DisableComments {

	/**
	 * Constructor
	 *
	 * @since 1.4.0
	 */
	public function __construct() {
		// Disable built-in Recent Comments widget.
		add_action( 'widgets_init', array( $this, 'disable_recent_comments_widget' ) );

		// Remove X-Pingback header (unless XML-RPC is already disabled by another feature).
		add_filter( 'wp_headers', array( $this, 'remove_x_pingback' ) );

		// Remove comment feed link from <head>.
		remove_action( 'wp_head', 'feed_links_extra', 3 );

		// Disable comment feed requests.
		add_action( 'template_redirect', array( $this, 'disable_comment_feed_requests' ), 9 );

		// Remove comment links from the Admin Bar.
		add_action( 'template_redirect', array( $this, 'remove_admin_bar_comment_links' ) );
		add_action( 'admin_init', array( $this, 'remove_admin_bar_comment_links' ) );

		// Finish disabling comments after WP is loaded.
		add_action( 'wp_loaded', array( $this, 'wp_loaded_disable_comments' ) );
	}

	/**
	 * Unregister the Recent Comments widget.
	 *
	 * @since 1.4.0
	 * @return void
	 */
	public function disable_recent_comments_widget() {
		unregister_widget( 'WP_Widget_Recent_Comments' );
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
	}

	/**
	 * Remove X-Pingback header.
	 *
	 * @since 1.4.0
	 * @param array $headers HTTP headers.
	 * @return array Modified headers.
	 */
	public function remove_x_pingback( $headers ) {
		unset( $headers['X-Pingback'], $headers['x-pingback'] );
		return $headers;
	}

	/**
	 * Block comment feed requests with a 403.
	 *
	 * @since 1.4.0
	 * @return void
	 */
	public function disable_comment_feed_requests() {
		if ( is_comment_feed() ) {
			wp_die( esc_html__( 'Comments are disabled.', 'pageflash' ), '', array( 'response' => 403 ) );
		}
	}

	/**
	 * Remove comment links from the Admin Bar.
	 *
	 * @since 1.4.0
	 * @return void
	 */
	public function remove_admin_bar_comment_links() {
		if ( is_admin_bar_showing() ) {
			remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
		}
	}

	/**
	 * Finish disabling comments after WordPress is fully loaded.
	 *
	 * @since 1.4.0
	 * @return void
	 */
	public function wp_loaded_disable_comments() {
		// Remove comment support from all public post types.
		$post_types = get_post_types( array( 'public' => true ), 'names' );
		if ( ! empty( $post_types ) ) {
			foreach ( $post_types as $post_type ) {
				if ( post_type_supports( $post_type, 'comments' ) ) {
					remove_post_type_support( $post_type, 'comments' );
					remove_post_type_support( $post_type, 'trackbacks' );
				}
			}
		}

		// Close all comments via filters.
		add_filter( 'comments_array', '__return_empty_array', 20 );
		add_filter( 'comments_open', '__return_false', 20, 2 );
		add_filter( 'pings_open', '__return_false', 20, 2 );

		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'admin_menu_remove_comments' ), 9999 );
			add_action( 'admin_print_styles-index.php', array( $this, 'hide_dashboard_comments_css' ) );
			add_action( 'admin_print_styles-profile.php', array( $this, 'hide_profile_comments_css' ) );
			add_action( 'wp_dashboard_setup', array( $this, 'remove_recent_comments_meta' ) );
			add_filter( 'pre_option_default_pingback_flag', '__return_zero' );
		} else {
			add_filter( 'comments_template', array( $this, 'blank_comments_template' ), 20 );
			wp_deregister_script( 'comment-reply' );
			add_filter( 'feed_links_show_comments_feed', '__return_false' );
		}
	}

	/**
	 * Remove comments menu and disable comment admin pages.
	 *
	 * @since 1.4.0
	 * @return void
	 */
	public function admin_menu_remove_comments() {
		global $pagenow;

		remove_menu_page( 'edit-comments.php' );
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );

		if ( in_array( $pagenow, array( 'comment.php', 'edit-comments.php' ), true ) ) {
			wp_die( esc_html__( 'Comments are disabled.', 'pageflash' ), '', array( 'response' => 403 ) );
		}

		if ( 'options-discussion.php' === $pagenow ) {
			wp_die( esc_html__( 'Comments are disabled.', 'pageflash' ), '', array( 'response' => 403 ) );
		}
	}

	/**
	 * Hide comment counts from the Dashboard.
	 *
	 * @since 1.4.0
	 * @return void
	 */
	public function hide_dashboard_comments_css() {
		echo '<style>#dashboard_right_now .comment-count, #dashboard_right_now .comment-mod-count, #latest-comments, #welcome-panel .welcome-comments { display: none !important; }</style>';
	}

	/**
	 * Hide comment shortcuts from the Profile page.
	 *
	 * @since 1.4.0
	 * @return void
	 */
	public function hide_profile_comments_css() {
		echo '<style>.user-comment-shortcuts-wrap { display: none !important; }</style>';
	}

	/**
	 * Remove Recent Comments meta box from Dashboard.
	 *
	 * @since 1.4.0
	 * @return void
	 */
	public function remove_recent_comments_meta() {
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}

	/**
	 * Replace comments template with a blank one.
	 *
	 * @since 1.4.0
	 * @return string Path to blank comments template.
	 */
	public function blank_comments_template() {
		return PAGEFLASH_PATH . 'includes/Landmark/General/templates/blank-comments.php';
	}
}
