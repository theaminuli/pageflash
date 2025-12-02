<?php

namespace PageFlash\Landmark;

use PageFlash\Landmark\LandmarkList;
use PageFlash\Landmark\LandmarkAPI;
use PageFlash\Landmark\NoReload\NoReload;             
// use PageFlash\Landmark\General\General;

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
