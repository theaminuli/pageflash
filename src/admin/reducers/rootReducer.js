import { ACTIVE_MENU, SET_LANDMARKS } from '../actions';

const rootReducer = (state, action) => {
	switch (action.type) {
		case ACTIVE_MENU:
			return {
				...state,
				activeMenu: action.payload,
			};
		case SET_LANDMARKS:
			return {
				...state,
				landmarks: action.payload,
			};
		
		default:
			return state;
	}
};

export default rootReducer;
