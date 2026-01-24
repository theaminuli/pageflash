<?php
/**
 * Feature Registry
 *
 * Central registration system for all PageFlash features.
 *
 * @package TheAminul\PageFlash\Landmark
 * @since 1.3.0
 */

namespace TheAminul\PageFlash\Landmark;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Boot
 *
 * Centralized feature registration and management.
 *
 * @since 1.3.0
 */
class Boot {

	/**
	 * Registered features.
	 *
	 * @var array<string, array{
	 *     class: string,
	 *     namespace: string,
	 *     package: string,
	 *     requires: array,
	 *     priority: int,
	 *     enabled: bool
	 * }>
	 */
	private static array $features = array();

	/**
	 * Register a feature
	 *
	 * @since 1.3.0
	 * @param string $key Feature slug.
	 * @param array  $config Feature configuration.
	 * @return void
	 */
	public static function register( string $key, array $config ): void {
		self::$features[ $key ] = wp_parse_args(
			$config,
			array(
				'class'     => '',           // Feature class name
				'namespace' => '',           // Feature namespace (general, noreload, advanced)
				'package'   => 'free',       // free|pro|agency
				'requires'  => array(),      // Dependencies (other feature slugs)
				'priority'  => 10,           // Load order
				'enabled'   => true,         // Can be disabled globally
			)
		);
	}

	/**
	 * Get all features
	 *
	 * @since 1.3.0
	 * @param string $namespace Optional namespace filter.
	 * @param string $package Optional package filter (free|pro|agency).
	 * @return array Features array.
	 */
	public static function get_features( string $namespace = '', string $package = '' ): array {
		$features = self::$features;

		if ( ! empty( $namespace ) ) {
			$features = array_filter(
				$features,
				function ( $feature ) use ( $namespace ) {
					return $feature['namespace'] === $namespace;
				}
			);
		}

		if ( ! empty( $package ) ) {
			$features = array_filter(
				$features,
				function ( $feature ) use ( $package ) {
					return $feature['package'] === $package;
				}
			);
		}

		// Sort by priority
		uasort(
			$features,
			function ( $a, $b ) {
				return $a['priority'] <=> $b['priority'];
			}
		);

		return $features;
	}

	/**
	 * Get single feature config
	 *
	 * @since 1.3.0
	 * @param string $key Feature key.
	 * @return array|null Feature config or null.
	 */
	public static function get( string $key ): ?array {
		return self::$features[ $key ] ?? null;
	}

	/**
	 * Check if feature exists
	 *
	 * @since 1.3.0
	 * @param string $key Feature key.
	 * @return bool True if registered.
	 */
	public static function exists( string $key ): bool {
		return isset( self::$features[ $key ] );
	}

	/**
	 * Check if feature is Pro/Agency
	 *
	 * @since 1.3.0
	 * @param string $key Feature key.
	 * @return bool True if Pro/Agency feature.
	 */
	public static function is_premium( string $key ): bool {
		$feature = self::get( $key );
		return $feature && in_array( $feature['package'], array( 'pro', 'agency' ), true );
	}

	/**
	 * Unregister a feature
	 *
	 * @since 1.3.0
	 * @param string $key Feature key.
	 * @return void
	 */
	public static function unregister( string $key ): void {
		unset( self::$features[ $key ] );
	}

	/**
	 * Get all Pro/Agency features
	 *
	 * @since 1.3.0
	 * @return array Premium features.
	 */
	public static function get_premium_features(): array {
		return array_filter(
			self::$features,
			function ( $feature ) {
				return in_array( $feature['package'], array( 'pro', 'agency' ), true );
			}
		);
	}

	/**
	 * Get features by namespace
	 *
	 * @since 1.3.0
	 * @param string $namespace Namespace to filter by.
	 * @return array Filtered features.
	 */
	public static function get_by_namespace( string $namespace ): array {
		return self::get_features( $namespace );
	}

	/**
	 * Clear all registered features (for testing)
	 *
	 * @since 1.3.0
	 * @return void
	 */
	public static function clear(): void {
		self::$features = array();
	}
}
