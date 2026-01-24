<?php
/**
 * General Boot Manager
 *
 * @package TheAminul\PageFlash\Landmark\General
 * @since 1.3.0
 */

namespace TheAminul\PageFlash\Landmark\General;

use TheAminul\PageFlash\Landmark\BootManager;
use TheAminul\PageFlash\Landmark\Boot;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class General
 *
 * Manages and initializes all general optimization features.
 *
 * @since 1.3.0
 */
class General extends BootManager {

	/**
	 * Namespace for this manager
	 *
	 * @var string
	 */
	protected string $namespace = 'general';

	/**
	 * Register all General features.
	 *
	 * This method is called from Landmark.php during initialization.
	 * All features in the general namespace are registered here.
	 *
	 * @since 1.3.0
	 * @return void
	 */
	public static function init_register() {
		Boot::register(
			'emojis',
			array(
				'class'     => DisableEmojis::class,
				'namespace' => 'general',
				'package'   => 'free',
				'priority'  => 10,
			)
		);

		Boot::register(
			'embeds',
			array(
				'class'     => DisableEmbeds::class,
				'namespace' => 'general',
				'package'   => 'free',
				'priority'  => 10,
			)
		);

		Boot::register(
			'dashicons',
			array(
				'class'     => DisableDashicons::class,
				'namespace' => 'general',
				'package'   => 'free',
				'priority'  => 10,
			)
		);

		Boot::register(
			'jquery_migrate',
			array(
				'class'     => RemoveJQueryMigrate::class,
				'namespace' => 'general',
				'package'   => 'free',
				'priority'  => 10,
			)
		);

		Boot::register(
			'xmlrpc',
			array(
				'class'     => DisableXMLRPC::class,
				'namespace' => 'general',
				'package'   => 'free',
				'priority'  => 10,
			)
		);

		Boot::register(
			'wp_version',
			array(
				'class'     => HideWPVersion::class,
				'namespace' => 'general',
				'package'   => 'free',
				'priority'  => 10,
			)
		);

		Boot::register(
			'rest_api',
			array(
				'class'     => DisableRestAPI::class,
				'namespace' => 'general',
				'package'   => 'free',
				'priority'  => 10,
			)
		);

		Boot::register(
			'rest_api_link',
			array(
				'class'     => RemoveRestAPILink::class,
				'namespace' => 'general',
				'package'   => 'free',
				'priority'  => 10,
			)
		);

		Boot::register(
			'heartbeat',
			array(
				'class'     => DisableHeartbeat::class,
				'namespace' => 'general',
				'package'   => 'free',
				'priority'  => 10,
			)
		);
	}

	/**
	 * Custom check before loading features
	 *
	 * Prevents jQuery Migrate from loading on page builders.
	 *
	 * @since 1.3.0
	 * @param string $key Feature key.
	 * @param array  $config Feature config.
	 * @param array  $settings All settings.
	 * @return bool True to allow loading.
	 */
	protected function can_load_feature( string $key, array $config, array $settings ): bool {
		// Skip jQuery Migrate on page builders
		if ( 'jquery_migrate' === $key && $this->is_page_builder() ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if page builder is active
	 *
	 * @since 1.3.0
	 * @return bool True if page builder is detected.
	 */
	private function is_page_builder() {
		// Never run in admin, ajax, rest
		if ( is_admin() || wp_doing_ajax() || wp_is_json_request() ) {
			return false;
		}

		$page_builders = array(
			'customizer',
			'elementor-preview',
			'fl_builder', // beaver builder
			'et_pb_preview', // divi
			'et_fb',
			'ct_builder', // oxygen
			'tve',
			'bricks',
			'gb-template-viewer', // generateblocks
			'trp-edit-translation', // translatepress
			'gform_ajax', // gravity forms
		);
		$page_builders = apply_filters( 'pageflash_detected_page_builders', $page_builders );

		foreach ( $page_builders as $builder ) {
			if ( isset( $_GET[ $builder ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return true;
			}
		}

		return false;
	}
}
