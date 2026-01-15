/**
 * WordPress dependencies.
 */
import { useMemo } from '@wordpress/element';
import { MENU_ICONS } from '../constants';
import { useGetLandmarks } from '../hooks';

/**
 * Custom hook to dynamically extract unique menu routes from landmark data.
 * 
 * @returns {Object} Object containing menus array and loading state
 * @returns {Array<Object>} menus - Array of menu objects with slug and order
 * @returns {boolean} loading - Loading state
 */
const useLandmarkMenus = () => {
	const { landmarks, loading } = useGetLandmarks();
	const menus = useMemo(() => {

		if (!landmarks) return [];

		// Handle both landmarks.data structure and direct landmarks object
		const landmarksData = landmarks.data || landmarks;

		if (typeof landmarksData !== 'object') return [];

		// Extract unique menu values with their order, label, and icon from all landmarks
		const menuMap = new Map();
		Object.values(landmarksData).forEach((landmark) => {
			if (landmark?.menu) {
				// If menu already exists, keep the one with lowest order number
				if (!menuMap.has(landmark.menu)) {
					menuMap.set(landmark.menu, {
						slug: landmark.menu,
						order: landmark.menuOrder ?? 999, // Default high order if not specified
						label: landmark.menuLabel || landmark.menu,
						icon: MENU_ICONS[landmark.menu] || null, // Get icon from mapping
					});
				} else {
					const existing = menuMap.get(landmark.menu);
					const currentOrder = landmark.menuOrder ?? 999;
					if (currentOrder < existing.order) {
						menuMap.set(landmark.menu, {
							slug: landmark.menu,
							order: currentOrder,
							label: landmark.menuLabel || landmark.menu,
							icon: MENU_ICONS[landmark.menu] || existing.icon,
						});
					}
				}
			}
		});

		// Convert to array and sort by order
		return Array.from(menuMap.values()).sort((a, b) => a.order - b.order);
	}, [landmarks]);

	return { menus, loading };
};
export default useLandmarkMenus;