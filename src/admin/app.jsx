/**
 * External dependencies
 */
import { ToastContainer } from 'react-toastify';

/**
 * Internal dependencies
 */

import AdminDashboard from './AdminDashboard';
import AdminProvider from './provider/AdminProvider';
/**
 * * Main application component that wraps the AdminDashboard with the AdminProvider.
 * @return {JSX.Element} The main application component.
 */

const App = () => {
	return (
		<AdminProvider>
			<ToastContainer
				position="top-right"
				className="pageflash-toast-container"
				autoClose={ 2000 }
				hideProgressBar={ false }
				newestOnTop={ false }
				closeOnClick
				rtl={ false }
				pauseOnFocusLoss
				draggable
				pauseOnHover
				theme="light"
			/>
			<AdminDashboard />
		</AdminProvider>
	);
};
export default App;
