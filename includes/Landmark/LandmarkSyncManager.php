<?php
/**
 * Landmark sync manager.
 *
 * @package PageFlash
 */

namespace TheAminul\PageFlash\Landmark;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * LandmarkSyncManager Class.
 *
 * Handles synchronization of landmark data between defaults and stored options.
 *
 * @package PageFlash
 * @since PageFlash 1.0.0
 */
class LandmarkSyncManager {

    /**
     * Recursively synchronizes landmark properties between existing and new data.
     *
     * @param array $existing  The current landmarks data stored in the database, expected to contain 'data' and 'version' keys.
     * @param array $landmarks The new landmarks data to sync with, expected to contain 'data' and optionally 'version' keys.
     *
     * @return bool True if updated, false otherwise.
     * @since 1.0.0
     */
    public function pageflash_sync_properties( $existing, $landmarks ) {
        // Type validation
        if ( ! is_array( $existing ) || ! is_array( $landmarks ) ) {
            return false;
        }

        // Check required data keys
        if ( ! isset( $existing['data'], $landmarks['data'] ) ) {
            return false;
        }

        $merged         = $existing;
        $merged['data'] = $this->pageflash_recursive_sync( $existing['data'], $landmarks['data'] );

        // Update version if changed
        if ( isset( $landmarks['version'] ) ) {
            $existing_version = $existing['version'] ?? '';
            if ( $landmarks['version'] !== $existing_version ) {
                $merged['version'] = $landmarks['version'];
            }
        }

        // Use serialize for accurate deep comparison
        if ( serialize( $merged ) !== serialize( $existing ) ) {
            return update_option( 'pageflash_landmarks', $merged );
        }

        return false;
    }

    /**
     * Recursively sync $existing with $new:
     * - Adds new keys from $new
     * - Removes keys not present in $new
     * - Preserves user-modified values for existing keys
     *
     * @param array $existing The existing array (from DB)
     * @param array $new The new default array
     * @return array The synced array
     * @since 1.0.0
     */
    private function pageflash_recursive_sync( $existing, $new ) {
        // Remove keys not in $new
        foreach ( array_keys( $existing ) as $key ) {
            if ( ! array_key_exists( $key, $new ) ) {
                unset( $existing[ $key ] );
            }
        }

        // Add new keys from $new, update changed scalars, preserve user-modifiable data
        foreach ( $new as $key => $value ) {
            if ( is_array( $value ) ) {
                $existing[ $key ] = isset( $existing[ $key ] ) && is_array( $existing[ $key ] )
                    ? $this->pageflash_recursive_sync( $existing[ $key ], $value )
                    : $value;
            } else {
                // Preserve user-modifiable fields: 'active' (toggle state) and 'value' (user input).
                if ( ( 'value' === $key || 'active' === $key ) && isset( $existing[ $key ] ) ) {
                    continue;
                }
                // Update all other scalar config fields.
                if ( ! isset( $existing[ $key ] ) || $value !== $existing[ $key ] ) {
                    $existing[ $key ] = $value;
                }
            }
        }

        return $existing;
    }
}