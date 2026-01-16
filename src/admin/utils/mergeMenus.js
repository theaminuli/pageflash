/**
 * Merges custom menus with landmark-generated menus and sorts by order.
 *
 * @param {Array<Object>} landmarkMenus - Menus extracted from landmarks
 * @param {Array<Object>} customMenus   - Custom menus to add (e.g., [{slug: 'settings', order: 2, label: 'Settings', icon: cog}])
 * @return {Array<Object>} Sorted and merged menu array
 */
export const mergeMenus = ( landmarkMenus = [], customMenus = [] ) => {
	const menuMap = new Map();

	// Add landmark menus
	landmarkMenus.forEach( ( menu ) => {
		menuMap.set( menu.slug, menu );
	} );

	// Add or override with custom menus
	customMenus.forEach( ( menu ) => {
		if ( menuMap.has( menu.slug ) ) {
			// Merge with existing, custom properties take precedence
			menuMap.set( menu.slug, { ...menuMap.get( menu.slug ), ...menu } );
		} else {
			// Add new custom menu
			menuMap.set( menu.slug, { order: 999, ...menu } );
		}
	} );

	// Convert to array and sort by order
	return Array.from( menuMap.values() ).sort( ( a, b ) => a.order - b.order );
};
