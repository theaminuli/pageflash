/**
 * WordPress dependencies
 */
import { useMemo } from '@wordpress/element';
/**
 * Internal dependencies
 */
import { MENU_ICONS } from '../constants';
import { useGetLandmarks } from '../hooks';

/**
 * Custom hook to dynamically extract unique menu routes from landmark data.
 *
 * @return {Object} Object containing menus array and loading state
 * @return {Array<Object>} menus - Array of menu objects with slug and order
 * @return {boolean} loading - Loading state
 */
const useLandmarkMenus = () => {
	const { landmarks, loading } = useGetLandmarks();
	const menus = useMemo( () => {
		if ( ! landmarks ) {
			return [];
		}
		const landmarksData = landmarks.data || landmarks;

		if ( typeof landmarksData !== 'object' ) {
			return [];
		}

		const menuMap = new Map();
		Object.values( landmarksData ).forEach( ( landmark ) => {
			if ( landmark?.menu ) {
				// If menu already exists, keep the one with lowest order number
				if ( ! menuMap.has( landmark.menu ) ) {
					menuMap.set( landmark.menu, {
						slug: landmark.menu,
						order: landmark.menuOrder ?? 999, // Default high order if not specified
						label: landmark.menuLabel || landmark.menu,
						icon: MENU_ICONS[ landmark.menu ] || null,
					} );
				} else {
					const existing = menuMap.get( landmark.menu );
					const currentOrder = landmark.menuOrder ?? 999;
					if ( currentOrder < existing.order ) {
						menuMap.set( landmark.menu, {
							slug: landmark.menu,
							order: currentOrder,
							label: landmark.menuLabel || landmark.menu,
							icon: MENU_ICONS[ landmark.menu ] || existing.icon,
						} );
					}
				}
			}
		} );

		// Convert to array and sort by order
		return Array.from( menuMap.values() ).sort(
			( a, b ) => a.order - b.order
		);
	}, [ landmarks ] );

	return { menus, loading };
};
export default useLandmarkMenus;
