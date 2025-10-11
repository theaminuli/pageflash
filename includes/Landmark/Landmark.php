<?php

namespace PageFlash\Landmark;

use PageFlash\Landmark\NoReload\NoReload;
use PageFlash\Landmark\NoReload\Quicklink;
use PageFlash\Landmark\LandmarkList;
use PageFlash\Landmark\LandmarkAPI;

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
     * Conditionally initialize landmark features based on the 'active' flag.
     *
     * Each landmark feature is initialized independently, allowing multiple
     * features to be enabled simultaneously.
     *
     * @since PageFlash 1.0.0
     */
    public function pageflash_init_landmark() {
        $landmarks = get_option( 'pageflash_landmarks', [] );
        $landmark_data = $landmarks['data'] ?? [];

        foreach ( $landmark_data as $slug => $item ) {
            if ( ! empty( $item['active'] ) ) {
                switch ( $slug ) {
                    case 'quicklink':
                        new Quicklink();
                        break;
                    case 'noreload':
                        new NoReload();
                        break;
                    case 'instantpage':
                        // new InstantPage();
                        break;
                    // Add more cases here as you add more landmark features
                }
            }
        }
    }

    /**
     * Register LandmarkList and LandmarkAPI.
     */
    public function pageflash_register_landmarks() {
        new LandmarkList();
        new LandmarkAPI();
    }
}
