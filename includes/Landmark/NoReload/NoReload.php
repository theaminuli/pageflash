<?php

namespace PageFlash\Landmark\NoReload;
use PageFlash\Helpers\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * NoReload Class.
 *
 * This class provides functionality for something in your PageFlash plugin.
 *
 * @package pageflash
 * @since PageFlash 1.0.0
 */
class NoReload {

	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize feature classes based on persisted settings.
	 *
	 * This method retrieves plugin settings via Helper::get_settings(), defines a
	 * mapping of feature keys to their implementing classes, and instantiates each
	 * feature class when the corresponding settings entry contains an 'active'
	 * value that evaluates to true.
	 *
	 * Expected settings format:
	 *   [
	 *     'instantpage' => ['active' => bool],
	 *     'quicklink'   => ['active' => bool],
	 *     // ...
	 *   ]
	 *
	 * Notes:
	 * - Feature classes are instantiated without arguments; they are expected to
	 *   perform their own registration/bootstrap in their constructors.
	 * - Only features present in the $features map are considered here.
	 *
	 * @return void
	 * @see Helper::get_settings()
	 * @see InstantPage
	 * @see Quicklink
	 */
	private function init() {
		$settings = Helper::get_settings();

		$features = [
			'instantpage' => InstantPage::class,
			'quicklink'   => Quicklink::class,
		];

		foreach ($features as $key => $class) {
			if (!empty($settings[$key]['active'])) {
				new $class();
			}
		}
	}

}
