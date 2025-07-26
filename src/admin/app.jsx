// pages/AdminDashboard.jsx
import { ToastContainer } from "react-toastify";
import AdminDashboard from "./AdminDashboard";
import withHeaderLayout from "./HOC/withHeaderLayout";
import AdminProvider from "./provider/AdminProvider";
/**
 * * Main application component that wraps the AdminDashboard with the AdminProvider.
 * @returns {JSX.Element} The main application component.
 */

const App = () => {
	const AppWithHeader = withHeaderLayout(AdminDashboard);
	return (
		<AdminProvider>
			<ToastContainer
				position="top-right"
				className="pageflash-toast-container"
				autoClose={2000}
				hideProgressBar={false}
				newestOnTop={false}
				closeOnClick
				rtl={false}
				pauseOnFocusLoss
				draggable
				pauseOnHover
				theme="light"
			/>
			<AppWithHeader />
		</AdminProvider>
	);
};
export default App;
