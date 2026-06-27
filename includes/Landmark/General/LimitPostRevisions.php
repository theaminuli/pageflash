<?php
/**
 * Limit Post Revisions Feature
 *
 * @package TheAminul\PageFlash\Landmark\General
 * @since 1.4.0
 */

namespace TheAminul\PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class LimitPostRevisions
 *
 * Limits the number of post revisions stored in the database.
 *
 * @since 1.4.0
 */
class LimitPostRevisions {

	/**
	 * Maximum number of revisions to keep.
	 *
	 * @since 1.4.0
	 * @var int
	 */
	private $limit;

	/**
	 * Constructor
	 *
	 * @since 1.4.0
	 * @param int $limit Number of revisions to keep (0 = disable revisions).
	 */
	public function __construct( $limit = 3 ) {
		$this->limit = (int) $limit;

		if ( defined( 'WP_POST_REVISIONS' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_conflict' ) );
		} else {
			define( 'WP_POST_REVISIONS', $this->limit );
		}
	}

	/**
	 * Show admin notice when WP_POST_REVISIONS is already defined elsewhere.
	 *
	 * @since 1.4.0
	 * @return void
	 */
	public function admin_notice_conflict() {
		echo '<div class="notice notice-error"><p>';
		echo '<strong>' . esc_html__( 'PageFlash Warning', 'pageflash' ) . ':</strong> ';
		echo esc_html__( 'WP_POST_REVISIONS is already defined elsewhere on your site. Limit Post Revisions can only be set in one place.', 'pageflash' );
		echo '</p></div>';
	}
}
