<?php

/**
 * Returns the namespace → directory map.
 *
 * @since 1.0.0
 * @return array
 */
function pageflash_get_namespace_map() {
	return array(
		'TheAminul\\PageFlash\\' => trailingslashit( PAGEFLASH_DIR ) . 'includes/',
	);
}

/**
 * Resolves a fully-qualified class name into a file path if it exists.
 *
 * @since 1.0.0
 * @param string $class_name Fully qualified class name.
 * @return string|false File path or false if not found.
 */
function pageflash_locate_class_file( $class_name ) {

	if ( ! defined( 'PAGEFLASH_DIR' ) ) {
		return false;
	}

	$namespace_map = pageflash_get_namespace_map();

	foreach ( $namespace_map as $namespace => $base_dir ) {

		if ( strpos( $class_name, $namespace ) !== 0 ) {
			continue;
		}

		$relative_class = substr( $class_name, strlen( $namespace ) );
		$file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		return file_exists( $file ) ? $file : false;
	}

	return false;
}

/**
 * Autoloader.
 *
 * @since 1.0.0
 * @param string $class_name The class name being instantiated.
 * @return void
 */
function pageflash_autoloader( $class_name ) {

	// Only handle PageFlash namespace
	if ( strpos( $class_name, 'TheAminul\\PageFlash\\' ) !== 0 ) {
		return;
	}

	$file = pageflash_locate_class_file( $class_name );

	if ( $file ) {
		require_once $file;
		return;
	}

	// Log ONLY missing PageFlash classes (dev only)
	if (
		defined( 'PAGEFLASH_ENV' ) &&
		PAGEFLASH_ENV === 'development' &&
		defined( 'WP_DEBUG' ) &&
		WP_DEBUG
	) {
		error_log( "[PageFlash Autoload] Class not found: {$class_name}" ); // phpcs:ignore
	}
}


/**
 * Registers the autoloader.
 *
 * @since 1.0.0
 * @return void
 */
function pageflash_register_autoloader() {

	if ( ! defined( 'PAGEFLASH_DIR' ) ) {
		return;
	}

	spl_autoload_register( 'pageflash_autoloader' );
}

// Boot autoloader
pageflash_register_autoloader();
