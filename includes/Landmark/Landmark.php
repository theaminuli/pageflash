<?php

namespace TheAminul\PageFlash\Landmark;

use TheAminul\PageFlash\Landmark\NoReload\NoReload;
use TheAminul\PageFlash\Landmark\General\General;

class Landmark {

	/**
	 * Instance registry.
	 *
	 * @var array<string, object>
	 */
	protected array $instances = array();

	/**
	 * Landmark constructor.
	 *
	 * @since PageFlash 1.3.0
	 */
	public function __construct() {
		add_action( 'pageflash_init', array( $this, 'boot' ) );
		add_action( 'pageflash_init', array( $this, 'register_landmarks' ) );
		// $this->register_landmarks();
	}

	/**
	 * Plugin bootstrap.
	 *
	 * @since 1.3.0
	 */
	public function boot(): void {
		$this->register_features();
		$this->init_managers();
	}

	/**
	 * Feature class list.
	 *
	 * @since 1.3.0
	 * @return array<class-string>
	 */
	protected function features(): array {
		return array(
			NoReload::class,
			General::class,
		);
	}

	/**
	 * Register all features.
	 *
	 * @since 1.3.0
	 */
	protected function register_features(): void {
		foreach ( $this->features() as $feature ) {
			$feature::init_register();
		}

		do_action( 'pageflash_register_features' );
	}

	/**
	 * Initialize feature managers.
	 *
	 * @since 1.3.0
	 */
	protected function init_managers(): void {
		foreach ( $this->features() as $feature ) {
			$this->get_instance( $feature );
		}

		do_action( 'pageflash_init_managers', $this );
	}

	/**
	 * Register landmark services.
	 *
	 * @since PageFlash 1.0.0
	 */
	public function register_landmarks(): void {

		$this->get_instance( LandmarkList::class );
		$this->get_instance( LandmarkAPI::class );
	}

	/**
	 * Get or create an instance.
	 *
	 * @since 1.3.0
	 * @param class-string $class
	 * @return object
	 */
	public function get_instance( string $class ): object {
		if ( isset( $this->instances[ $class ] ) ) {
			return $this->instances[ $class ];
		}

		return $this->instances[ $class ] = new $class();
	}
}
