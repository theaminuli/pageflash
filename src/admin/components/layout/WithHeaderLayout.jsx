import { Outlet } from 'react-router';
import { Header } from '../header';

const WithHeaderLayout = () => {
	return (
		<Header>
			<main className="pageflash-main-content">
				<Outlet />
			</main>
		</Header>
	);
};
export default WithHeaderLayout;
