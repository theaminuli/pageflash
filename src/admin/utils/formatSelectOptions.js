/**
 * Convert options object to SelectControl format
 *
 * @param {Object} options - Options object with key-value pairs
 * @return {Array} Array of objects with label and value properties
 */
export const formatSelectOptions = (options = {}) => {
	return Object.entries(options).map(([key, label]) => ({
		label,
		value: key,
	}));
};
