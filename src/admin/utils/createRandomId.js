/**
 * Generates a random alphanumeric string of the specified length.
 *
 * @param {number} [length=8] - The desired length of the generated ID.
 * @return {string} A random alphanumeric string.
 */
export const createRandomId = ( length = 8 ) => {
	return Math.random()
		.toString( 36 )
		.substring( 2, length + 2 );
};
