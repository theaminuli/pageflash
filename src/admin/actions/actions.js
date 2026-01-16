/**
 * Internal dependencies
 */
import { ACTIVE_MENU, SET_LANDMARKS, UPDATE_LANDMARK } from './actionTypes';

export const setActiveMenu = (activeMenu) => ({
	type: ACTIVE_MENU,
	payload: activeMenu,
});

export const setLandmarks = (landmarks) => ({
	type: SET_LANDMARKS,
	payload: landmarks,
});

export const updateLandmark = (slug, updates) => ({
	type: UPDATE_LANDMARK,
	payload: { slug, updates },
});
