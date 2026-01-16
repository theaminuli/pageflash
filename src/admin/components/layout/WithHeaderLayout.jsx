/**
 * External dependencies
 */
import { Outlet } from 'react-router';

/**
 * Internal dependencies
 */
import { Header } from '../header';

const WithHeaderLayout = ({ menus }) => {

	return (
		<Header menus={menus}>
			<main className="pageflash-main-content">
				<Outlet />
			</main>
		</Header>
	);
};
export default WithHeaderLayout;
