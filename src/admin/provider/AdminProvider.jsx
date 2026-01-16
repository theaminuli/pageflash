/* eslint-disable react/prop-types */

/**
 * WordPress dependencies
 */
import { useReducer } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { AdminContext } from '../context';
import { initialState, rootReducer } from '../reducers';
/**
 * AppProvider component that sets up the application context and routing.
 *
 * @param {Object}                    props          - The properties object.
 * @param {import('react').ReactNode} props.children - The child components to be wrapped by the provider.
 * @return {JSX.Element} The provider component with routing and context.
 */
const AdminProvider = ({ children }) => {
	const [state, dispatch] = useReducer(rootReducer, initialState);

	return (
		<AdminContext.Provider value={{ ...state, dispatch }}>
			{children}
		</AdminContext.Provider>
	);
};

export default AdminProvider;
