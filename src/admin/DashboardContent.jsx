/**
 * Internal dependencies
 */
import DashboardLoader from './DashboardLoader';

/**
 * Renders the dashboard content or a loading indicator based on the loading state.
 *
 * @component
 * @param {Object} props - The component props.
 * @param {boolean} props.isLoading - Indicates if the content is currently loading.
 * @param {React.ReactNode} props.dashboard - The dashboard content to render when not loading.
 * @returns {React.ReactNode} The loading indicator or the dashboard content.
 */
const DashboardContent = ( { isLoading, dashboard } ) => {
	if ( isLoading ) {
		return <DashboardLoader />;
	}

	return dashboard;
};

export default DashboardContent;
