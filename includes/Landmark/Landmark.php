<?php

namespace TheAminul\PageFlash\Landmark;

use TheAminul\PageFlash\Landmark\LandmarkList;
use TheAminul\PageFlash\Landmark\LandmarkAPI;
use TheAminul\PageFlash\Landmark\NoReload\NoReload;
// use TheAminul\PageFlash\Landmark\General\General;

class Landmark {

	/**
	 * Initialize the Landmark.
	 *
	 * @since PageFlash 1.0.0
	 */
	public function __construct() {
		$this->pageflash_register_landmarks();
		$this->pageflash_init_landmark();
	}

	/**
	 * Initialize Landmark features.
	 */
	public function pageflash_init_landmark() {
		new NoReload();
			// new General();
	}

		/**
		 * Register LandmarkList and LandmarkAPI.
		 */
	public function pageflash_register_landmarks() {
		new LandmarkList();
		new LandmarkAPI();
	}
}
