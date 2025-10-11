<?php

namespace PageFlash\Landmark;

use PageFlash\Landmark\NoReload\NoReload;
use PageFlash\Landmark\NoReload\Quicklink;
use PageFlash\Landmark\LandmarkList;
use PageFlash\Landmark\LandmarkAPI;

/**
 * Class Landmark
 *
 * Initializes and manages all PageFlash landmark features.
 *
 * @package PageFlash\Landmark
 * @since 1.0.0
 */
class Landmark {

    /**
     * Landmark constructor.
     *
     * Initializes the landmarks and their features.
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->pageflash_register_landmarks();
        $this->pageflash_init_landmark();
    }

    /**
     * Initialize active landmark features.
     *
     * Checks saved landmark data and instantiates corresponding classes
     * if the 'active' flag is set to true.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function pageflash_init_landmark() {
        $landmarks = get_option( 'pageflash_landmarks', [] );
        $landmark_data = $landmarks['data'] ?? [];

        foreach ( $landmark_data as $slug => $item ) {
            if ( ! empty( $item['active'] ) ) {
                switch ( $slug ) {
                    case 'quicklink':
                        new NoReload();
                        new Quicklink();
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
     *
     * Instantiates the classes responsible for:
     * - Managing the default landmarks.
     * - Handling REST API endpoints for landmarks.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function pageflash_register_landmarks() {
        new LandmarkList();
        new LandmarkAPI();
    }
}
