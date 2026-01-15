/**
 * WordPress dependencies.
 */

import { HashRouter, Route, Routes } from 'react-router';
import { Addons } from './components/addons';
import { LandmarkMenu } from './components/landmark';
import WithHeaderLayout from './components/layout';
import { MENU_ICONS } from './constants';
import { useLandmarkMenus } from './hooks';
import { mergeMenus } from './utils';

function AdminDashboard() {
	const { menus: landmarkMenus, loading } = useLandmarkMenus();
	console.log('Landmark menus:', landmarkMenus);

	// Define custom menus with their order
	const customMenus = [
		{ slug: 'addons', order: 100, label: 'Addons', icon: MENU_ICONS.addons },
		{ slug: 'settings', order: 200, label: 'Settings', icon: MENU_ICONS.settings },
		{ slug: 'support', order: 300, label: 'Support', icon: MENU_ICONS.support },
	];

	// Merge landmark menus with custom menus and sort by order
	const menus = mergeMenus(landmarkMenus, customMenus);
	
	if (loading || menus.length === 0) {
		return <div>Loading...</div>;
	}

	return (
		<HashRouter>
			<Routes>
				<Route element={<WithHeaderLayout menus={menus} />}>
					{menus.map((menu, index) => {
						// Render custom routes
						if (menu.slug === 'addons') {
							return <Route key={menu.slug} path="/addons" element={<Addons />} />;
						}
						if (menu.slug === 'settings') {
							return <Route key={menu.slug} path="/settings" element={<h1>Settings</h1>} />;
						}
						if (menu.slug === 'support') {
							return <Route key={menu.slug} path="/support" element={<h1>Support</h1>} />;
						}
						// Render landmark menus
						return (
							<Route
								key={menu.slug}
								path={index === 0 ? '/' : `/${menu.slug}`}
								element={<LandmarkMenu menuSlug={menu.slug} />}
							/>
						);
					})}
				</Route>
			</Routes>
		</HashRouter>
	);
}

export default AdminDashboard;
