import { ACTIVE_MENU, SET_LANDMARKS, UPDATE_LANDMARK } from '../actions';

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
		case UPDATE_LANDMARK: {
			const { slug, updates } = action.payload;
			const updatedLandmarks = { ...state.landmarks };

			if (updatedLandmarks[slug]) {
				updatedLandmarks[slug] = {
					...updatedLandmarks[slug],
					...updates,
				};
			}

			return {
				...state,
				landmarks: updatedLandmarks,
			};
		}

		default:
			return state;
	}
};

export default rootReducer;
