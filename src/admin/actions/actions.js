import { ACTIVE_MENU, SET_LANDMARKS } from './actionTypes';

export const setActiveMenu = (activeMenu) => ({
	type: ACTIVE_MENU,
	payload: activeMenu,
});

export const setLandmarks = (landmarks) => ({
	type: SET_LANDMARKS,
	payload: landmarks,
}); 