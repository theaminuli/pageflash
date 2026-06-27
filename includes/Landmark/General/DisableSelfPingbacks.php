<?php
/**
 * Disable Self Pingbacks Feature
 *
 * @package TheAminul\PageFlash\Landmark\General
 * @since 1.4.0
 */

namespace TheAminul\PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class DisableSelfPingbacks
 *
 * Prevents WordPress from sending pingbacks to itself.
 *
 * @since 1.4.0
 */
class DisableSelfPingbacks {

	/**
	 * Constructor
	 *
	 * @since 1.4.0
	 */
	public function __construct() {
		add_action( 'pre_ping', array( $this, 'disable_self_pingbacks' ) );
	}

	/**
	 * Remove self-referencing links from the pingback list.
	 *
	 * @since 1.4.0
	 * @param array $links Array of pingback links (passed by reference).
	 * @return void
	 */
	public function disable_self_pingbacks( &$links ) {
		$home = get_option( 'home' );
		foreach ( $links as $l => $link ) {
			if ( strpos( $link, $home ) === 0 ) {
				unset( $links[ $l ] );
			}
		}
	}
}
