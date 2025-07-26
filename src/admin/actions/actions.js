import { ACTIVE_MENU } from './actionTypes';

export const setActiveMenu = ( activeMenu ) => ( {
	type: ACTIVE_MENU,
	payload: activeMenu,
} );
