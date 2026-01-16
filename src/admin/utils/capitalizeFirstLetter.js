/**
 * Capitalizes the first letter of a given string.
 *
 * @param {string} string - The string to capitalize.
 * @return {string} The string with the first letter capitalized. If input is not a string or is empty, returns the input as is.
 */
export const capitalizeFirstLetter = ( string ) => {
	if ( typeof string !== 'string' || string.length === 0 ) {
		return string;
	}

	return string.charAt( 0 ).toUpperCase() + string.slice( 1 );
};
