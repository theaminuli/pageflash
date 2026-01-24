<?php

namespace TheAminul\PageFlash\Landmark\NoReload;

use TheAminul\PageFlash\Landmark\BootManager;
use TheAminul\PageFlash\Landmark\Boot;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * NoReload Boot Manager
 *
 * Manages preloading features (Quicklink, InstantPage, etc.)
 *
 * @package pageflash
 * @since PageFlash 1.3.0
 */
class NoReload extends BootManager {

	/**
	 * Namespace for this manager
	 *
	 * @var string
	 */
	protected string $namespace = 'noreload';

	/**
	 * Register all NoReload features.
	 *
	 * This method is called from Landmark.php during initialization.
	 * All features in the noreload namespace are registered here.
	 *
	 * @since 1.3.0
	 * @return void
	 */
	public static function init_register() {
		Boot::register(
			'quicklink',
			array(
				'class'     => Quicklink::class,
				'namespace' => 'noreload',
				'package'   => 'free',
				'priority'  => 10,
			)
		);

		Boot::register(
			'instantpage',
			array(
				'class'     => InstantPage::class,
				'namespace' => 'noreload',
				'package'   => 'free',
				'priority'  => 10,
			)
		);
	}
}
