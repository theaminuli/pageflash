<?php

/**
 * Returns the namespace → directory map.
 *
 * @since 1.0.0
 * @return array
 */
function pageflash_get_namespace_map() {
	return array(
		'TheAminul\\PageFlash' => PAGEFLASH_DIR . '/includes/',
	);
}

/**
 * Resolves a fully-qualified class name into a file path if exists.
 * (Loop separated from autoloader)
 *
 * @since 1.0.0
 * @param string $class_name Fully qualified class name.
 * @return string|false Return file path or false if not found.
 */
function pageflash_locate_class_file( $class_name ) {
	$namespace_map = pageflash_get_namespace_map();

	foreach ( $namespace_map as $namespace => $base_dir ) {

		if ( strpos( $class_name, $namespace ) === 0 ) {

			$relative_class = substr( $class_name, strlen( $namespace ) );
			$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

			return file_exists( $file ) ? $file : false;
		}
	}

	return false;
}

/**
 * Autoloader — now only calls the resolver + loads file.
 *
 * @since 1.0.0
 * @param string $class_name The class name being instantiated.
 * @return void
 */
function pageflash_autoloader( $class_name ) {

	$file = pageflash_locate_class_file( $class_name );

	if ( $file ) {
		require_once $file;
		return;
	}

	// Debug only in development mode
	if ( defined('PAGEFLASH_ENV') && PAGEFLASH_ENV === 'development' && WP_DEBUG ) {
		error_log( "[PageFlash Autoload] Class not found: {$class_name}" ); // phpcs:ignore
	}
}

/**
 * Registers autoloader so classes load automatically.
 *
 * @since 1.0.0
 * @return void
 */
function pageflash_register_autoloader() {
	spl_autoload_register( 'pageflash_autoloader' );
}

// Boot autoloader
pageflash_register_autoloader();
