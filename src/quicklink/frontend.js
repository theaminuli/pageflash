/* eslint-disable no-console */
/* eslint-disable no-unused-vars */

/**
 * PageFlash settings object.
 *
 * @typedef {Object} PageFlashSettings
 * @property {string}   el        - CSS selector for the DOM element to observe for in-viewport links to prefetch.
 * @property {number}   limit     - The total requests that can be prefetched while observing the $el container.
 * @property {number}   throttle  - The concurrency limit for simultaneous requests while observing the $el container.
 * @property {number}   timeout   - Timeout after which prefetching will occur.
 * @property {string}   timeoutFn - Custom timeout function. Must refer to a named global function in JS.
 * @property {boolean}  priority  - Attempt higher priority fetch (low or high). Default false.
 * @property {string[]} origins   - Allowed origins to prefetch (empty allows all). Defaults to host for the current home URL.
 * @property {RegExp[]} ignores   - Regular expression patterns to determine whether a URL is ignored. Runs after origin checks.
 */

/**
 * Initialize PageFlash on page load.
 * @since PageFlash 1.0.0
 * @listens window#load
 */
window.addEventListener( 'load', () => {
	const settings = window.pageflashSettings || {};
	const listenerOptions = buildListenerOptions( settings );

	// Initialize quicklink listener and prefetch URLs
	window.quicklink.listen( listenerOptions );
	prefetchUrls( settings );
} );

/**
 * Build PageFlash listener options from user settings.
 *
 * @param {PageFlashSettings} settings - User settings for PageFlash.
 * @return {Object} - PageFlash listener options.
 * @since PageFlash 1.0.0
 */
function buildListenerOptions( settings ) {
	return {
		el: validateElement( settings.el ),
		timeout: validateNumber( settings.timeout ),
		limit: validatePositiveNumber( settings.limit ),
		throttle: validatePositiveNumber( settings.throttle ),
		timeoutFn: getFunctionReference( settings.timeoutFn ),
		// onError: getFunctionReference(settings.onError),
		priority: validateBoolean( settings.priority ),
		origins: validateOrigins( settings.origins ),
		ignores: validateIgnores( settings.ignores ),
	};
}

/**
 * Validate and get an HTML element based on the selector.
 *
 * @param {string} selector - CSS selector for the HTML element.
 * @return {Element|null} - HTML element or null if selector is empty or invalid.
 * @since PageFlash 1.0.0
 */
function validateElement( selector ) {
	return typeof selector === 'string' && selector.trim() !== ''
		? document.querySelector( selector )
		: null;
}

/**
 * Validate and get a number.
 *
 * @param {number} value - Number to validate.
 * @return {number} - Validated number or default (2000) if invalid.
 * @since PageFlash 1.0.0
 */
function validateNumber( value ) {
	return typeof value === 'string' ? Number( value ) : 2000;
}

/**
 * Validate and get a positive number.
 *
 * @param {number} value - Number to validate.
 * @return {number} - Validated positive number or default (Infinity) if invalid.
 * @since PageFlash 1.0.0
 */
function validatePositiveNumber( value ) {
	return typeof value === 'string' && Number( value ) > 0
		? Number( value )
		: Infinity;
}

/**
 * Validate and get a boolean.
 *
 * @param {boolean} value - Boolean to validate.
 * @return {boolean} - Validated boolean or default (false) if invalid.
 * @since PageFlash 1.0.0
 */
function validateBoolean( value ) {
	return typeof value === 'string' && value !== '' ? true : false;
}

/**
 * Get a function reference based on the function name.
 *
 * @param {string} functionName - Name of the function.
 * @return {Function|null} - Function reference or null if invalid.
 * @since PageFlash 1.0.0
 */
function getFunctionReference( functionName ) {
	return typeof functionName === 'string' &&
		typeof window[ functionName ] === 'function'
		? ( ...args ) => window[ functionName ]( ...args )
		: null;
}

/**
 * Validate and get an array of origins.
 *
 * @param {Array} origins - Array of origin strings.
 * @return {Array} - Validated array of origins or default (empty array) if invalid.
 * @since PageFlash 1.0.0
 */
function validateOrigins( origins ) {
	return Array.isArray( origins ) && origins.length > 0 ? origins : [];
}

/**
 * Convert an array of ignores to an array of regular expressions.
 *
 * @param {Array} ignores - Array of ignore patterns.
 * @return {Array} - Array of regular expressions or default (empty array) if invalid.
 * @since PageFlash 1.0.0
 */
function validateIgnores( ignores ) {
	return Array.isArray( ignores ) && ignores.length > 0
		? ignores.map( ( str ) => new RegExp( str ) )
		: [];
}

/**
 * Prefetch URLs provided in settings.
 *
 * @param {PageFlashSettings} settings - User settings for PageFlash.
 * @since PageFlash 1.0.0
 */
function prefetchUrls( settings ) {
	if ( Array.isArray( settings.urls ) && settings.urls.length > 0 ) {
		window.quicklink.prefetch( settings.urls );
	}
}
