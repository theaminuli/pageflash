/**
 * Filter landmarks by menu type
 * @param {Object|Array} landmarks - Landmarks object or array
 * @param {string} menu - Menu type to filter by (e.g., 'general', 'settings')
 * @returns {Array} Filtered landmarks
 */
export const filterLandmarksByMenu = ( landmarks, menu ) => {
	if ( ! landmarks ) {
		return [];
	}

	// Convert object to array if needed
	const landmarksArray = Array.isArray( landmarks )
		? landmarks
		: Object.values( landmarks );

	if ( ! menu ) {
		return landmarksArray;
	}

	return landmarksArray.filter( ( landmark ) => landmark.menu === menu );
};

/**
 * Get general menu landmarks
 * @param {Object|Array} landmarks - Landmarks object or array
 * @returns {Array} General menu landmarks
 */
export const getGeneralLandmarks = ( landmarks ) => {
	return filterLandmarksByMenu( landmarks, 'general' );
};

/**
 * Get settings menu landmarks
 * @param {Object|Array} landmarks - Landmarks object or array
 * @returns {Array} Settings menu landmarks
 */
export const getSettingsLandmarks = ( landmarks ) => {
	return filterLandmarksByMenu( landmarks, 'settings' );
};
