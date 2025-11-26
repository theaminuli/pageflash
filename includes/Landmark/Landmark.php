<?php

namespace PageFlash\Landmark;

use PageFlash\Landmark\LandmarkList;
use PageFlash\Landmark\LandmarkAPI;
use PageFlash\Landmark\NoReload\NoReload;             
use PageFlash\Landmark\General\General;

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
<<<<<<< Updated upstream
		$landmarks     = get_option( 'pageflash_landmarks', array() );
		$landmark_data = $landmarks['data'] ?? array();

		foreach ( $landmark_data as $slug => $item ) {
			if ( ! empty( $item['active'] ) ) {
				switch ( $slug ) {
					case 'quicklink':
						new NoReload\Quicklink();
						break;
					case 'noreload':
						new NoReload\NoReload();
						break;
					case 'instantpage':
						// TODO: Implement InstantPage functionality.
						break;
					// Add more cases here as you add more landmark features.
				}
			}
		}
=======
		new NoReload();
		new General();
>>>>>>> Stashed changes
	}

	/**
	 * Register LandmarkList and LandmarkAPI.
	 */
	public function pageflash_register_landmarks() {
		new LandmarkList();
		new LandmarkAPI();
	}
}
