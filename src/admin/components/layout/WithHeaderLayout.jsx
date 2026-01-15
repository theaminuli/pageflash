import { Outlet } from 'react-router';
import { Header } from '../header';

const WithHeaderLayout = ({ menus }) => {
	console.log('menus in WithHeaderLayout:', menus);
	
	return (
		<Header menus={menus}>
			<main className="pageflash-main-content">
				<Outlet />
			</main>
		</Header>
	);
};
export default WithHeaderLayout;
