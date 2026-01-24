<?php
/**
 * Feature Manager Base Class
 *
 * Abstract base class for feature managers with OAuth license validation.
 *
 * @package TheAminul\PageFlash\Landmark
 * @since 1.3.0
 */

namespace TheAminul\PageFlash\Landmark;

use TheAminul\PageFlash\Helpers\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract Class BootManager
 *
 * @since 1.3.0
 */
abstract class BootManager {

	/**
	 * Namespace for features managed by this manager.
	 *
	 * @var string
	 */
	protected string $namespace = '';

	/**
	 * Loaded feature instances.
	 *
	 * @var array<string, object>
	 */
	protected array $features = array();

	/**
	 * Constructor.
	 *
	 * @since 1.3.0
	 */
	public function __construct() {
		$this->init_features();
	}

	/**
	 * Initialize features from registry
	 *
	 * This method:
	 * 1. Reads from pageflash_landmarks via Helper::get_settings()
	 * 2. Checks OAuth license for Pro/Agency features (real-time)
	 * 3. Validates dependencies
	 * 4. Instantiates active features with proper parameters
	 *
	 * @since 1.3.0
	 * @return void
	 */
	protected function init_features() {
		$settings = Helper::get_settings();
		$features = Boot::get_features( $this->namespace );
		foreach ( $features as $key => $config ) {
			// Skip if feature not enabled in registry
			if ( ! $config['enabled'] ) {
				continue;
			}

			// Skip if feature not active in settings
			if ( ! $this->is_feature_active( $key, $settings ) ) {
				continue;
			}

			// Check OAuth license for Pro/Agency features (real-time validation)

			// if ( ! LicenseManager::can_use_feature( $config['package'] ) ) {
			// continue; // Skip premium features without valid license
			// }

			// Check dependencies
			if ( ! $this->check_dependencies( $config['requires'], $settings ) ) {
				continue;
			}

			// Allow child classes to add custom checks
			if ( ! $this->can_load_feature( $key, $config, $settings ) ) {
				continue;
			}

			// Load the feature
			$this->load_feature( $key, $config, $settings );
		}
	}

	/**
	 * Check if feature is active in settings
	 *
	 * Handles:
	 * - Switch fields: active: true/false
	 * - Value fields: value: "something" (select, input, textarea)
	 *
	 * @since 1.3.0
	 * @param string $key Feature key.
	 * @param array  $settings All settings.
	 * @return bool True if active.
	 */
	protected function is_feature_active( string $key, array $settings ): bool {
		if ( ! isset( $settings[ $key ] ) ) {
			return false;
		}

		$feature = $settings[ $key ];

		// Check switch type (active field)
		if ( isset( $feature['active'] ) ) {
			return ! empty( $feature['active'] );
		}

		// Check value type (non-empty value for select/input/textarea)
		if ( isset( $feature['value'] ) ) {
			return ! empty( $feature['value'] ) && '' !== $feature['value'];
		}

		return false;
	}

	/**
	 * Load a feature instance
	 *
	 * @since 1.3.0
	 * @param string $key Feature key.
	 * @param array  $config Feature config from registry.
	 * @param array  $settings All settings from database.
	 * @return void
	 */
	protected function load_feature( string $key, array $config, array $settings ): void {

		$class = $config['class'] ?? '';

		if ( $class === '' || ! class_exists( $class ) ) {
			return;
		}

		$feature_settings = $settings[ $key ] ?? array();

		$this->features[ $key ] = $this->instantiate_feature(
			$class,
			$feature_settings
		);
	}

	/**
	 * Dynamically instantiate a class with settings.
	 *
	 * This method tries to map the settings array to the constructor parameters.
	 * It supports nested input structures like $settings['input']['param_name']['value'].
	 *
	 * @param string $class    Fully-qualified class name to instantiate.
	 * @param array  $settings Settings array used to populate constructor arguments.
	 *
	 * @return object Instance of the class.
	 */
	protected function instantiate_feature( string $class, array $settings ): object {
		try {
			// Reflection class for dynamic inspection
			$reflection  = new \ReflectionClass( $class );
			$constructor = $reflection->getConstructor();

			// If no constructor or no parameters, instantiate normally
			if ( ! $constructor || 0 === $constructor->getNumberOfParameters() ) {
				return new $class();
			}

			$args = array();

			// Loop through constructor parameters
			foreach ( $constructor->getParameters() as $param ) {
				$name = $param->getName();

				if ( isset( $settings['input'][ $name ]['value'] ) ) {
					$args[] = $settings['input'][ $name ]['value'];

				} elseif ( isset( $settings['value'] ) ) {
					$args[] = $settings['value'];

				} elseif ( $param->isDefaultValueAvailable() ) {
					$args[] = $param->getDefaultValue();

				} else {
					$args[] = null;
				}
			}

			// Instantiate the class with the collected arguments
			return $reflection->newInstanceArgs( $args );

		} catch ( \Exception $e ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( sprintf( 'instantiate_feature error in %s: %s', $class, $e->getMessage() ) );
			}

			// Fallback to no-args constructor
			return new $class();
		}
	}

	/**
	 * Check feature dependencies
	 *
	 * @since 1.3.0
	 * @param array $requires Array of required feature slugs.
	 * @param array $settings All settings.
	 * @return bool True if dependencies met.
	 */
	protected function check_dependencies( array $requires, array $settings ): bool {
		if ( empty( $requires ) ) {
			return true;
		}

		foreach ( $requires as $required ) {
			if ( ! $this->is_feature_active( $required, $settings ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Custom feature load check (override in child classes)
	 *
	 * Example: Prevent jQuery Migrate on page builders.
	 *
	 * @since 1.3.0
	 * @param string $key Feature key.
	 * @param array  $config Feature config.
	 * @param array  $settings All settings.
	 * @return bool True to allow loading.
	 */
	protected function can_load_feature( string $key, array $config, array $settings ): bool {
		return true;
	}

	/**
	 * Get loaded feature instance
	 *
	 * @since 1.3.0
	 * @param string $key Feature key.
	 * @return object|null Feature instance or null.
	 */
	public function get_feature( string $key ): ?object {
		return $this->features[ $key ] ?? null;
	}

	/**
	 * Get all loaded features
	 *
	 * @since 1.3.0
	 * @return array All feature instances.
	 */
	public function get_features(): array {
		return $this->features;
	}


	/**
	 * Check if feature is loaded
	 *
	 * @since 1.3.0
	 * @param string $key Feature key.
	 * @return bool True if loaded.
	 */
	public function is_loaded( string $key ): bool {
		return isset( $this->features[ $key ] );
	}
}
