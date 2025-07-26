import { ACTIVE_MENU } from '../actions';

const rootReducer = ( state, action ) => {
	switch ( action.type ) {
		case ACTIVE_MENU:
			return {
				...state,
				activeMenu: action.payload,
			};
		default:
			return state;
	}
};

export default rootReducer;
