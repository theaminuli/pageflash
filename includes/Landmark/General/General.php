<?php
/**
 * General Features Manager
 *
 * @package PageFlash\Landmark\General
 * @since 1.2.0
 */

namespace PageFlash\Landmark\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class General
 *
 * Manages and initializes all general optimization features.
 *
 * @package PageFlash\Landmark\General
 * @since 1.2.0
 */
class General {

	/**
	 * Feature instances
	 *
	 * @since 1.2.0
	 * @var array
	 */
	private $features = array();

	/**
	 * Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		$this->init_features();
	}

	/**
	 * Initialize features based on settings
	 *
	 * @since 1.2.0
	 * @return void
	 */
	private function init_features() {
		$options = get_option( 'pageflash_options', array() );

		// Disable Emojis
		if ( ! empty( $options['disable_emojis'] ) ) {
			$this->features['disable_emojis'] = new DisableEmojis();
		}

		// Disable Embeds
		if ( ! empty( $options['disable_embeds'] ) ) {
			$this->features['disable_embeds'] = new DisableEmbeds();
		}

		// Disable Dashicons
		if ( ! empty( $options['disable_dashicons'] ) ) {
			$this->features['disable_dashicons'] = new DisableDashicons();
		}

		// Remove jQuery Migrate
		if ( ! empty( $options['remove_jquery_migrate'] ) && ! $this->is_page_builder() ) {
			$this->features['remove_jquery_migrate'] = new RemoveJQueryMigrate();
		}

		// Disable XML-RPC
		if ( ! empty( $options['disable_xmlrpc'] ) ) {
			$this->features['disable_xmlrpc'] = new DisableXMLRPC();
		}

		// Hide WordPress Version
		if ( ! empty( $options['hide_wp_version'] ) ) {
			$this->features['hide_wp_version'] = new HideWPVersion();
		}

		// Disable REST API
		if ( ! empty( $options['disable_rest_api'] ) ) {
			$this->features['disable_rest_api'] = new DisableRestAPI();
		}

		// Disable Heartbeat
		if ( ! empty( $options['disable_heartbeat'] ) ) {
			$behavior  = $options['heartbeat_behavior'] ?? 'disable_everywhere';
			$frequency = $options['heartbeat_frequency'] ?? 60;
			$this->features['disable_heartbeat'] = new DisableHeartbeat( $behavior, $frequency );
		}
	}

	/**
	 * Check if page builder is active
	 *
	 * @since 1.2.0
	 * @return bool True if page builder is detected.
	 */
	private function is_page_builder() {
		// Check for common page builder query args
		$page_builders = array(
			'elementor-preview',
			'fl_builder',
			'et_fb',
			'ct_builder',
			'tve',
			'bricks',
		);

		foreach ( $page_builders as $builder ) {
			if ( isset( $_GET[ $builder ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return true;
			}
		}

		return false;
	}

	/**
	 * Get feature instance
	 *
	 * @since 1.2.0
	 * @param string $key Feature key.
	 * @return mixed|null Feature instance or null.
	 */
	public function get_feature( $key ) {
		return $this->features[ $key ] ?? null;
	}

	/**
	 * Get all features
	 *
	 * @since 1.2.0
	 * @return array All feature instances.
	 */
	public function get_features() {
		return $this->features;
	}
}
