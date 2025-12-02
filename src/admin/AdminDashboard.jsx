/**
 * WordPress dependencies.
 */

import { HashRouter, Route, Routes } from 'react-router';
import { Addons } from './components/addons';
import { General } from './components/landmark';
import WithHeaderLayout from './components/layout';

function AdminDashboard() {
	return (
		<HashRouter>
			<Routes>
				<Route element={ <WithHeaderLayout /> }>
					<Route path="/" element={ <General /> } />
					<Route path="/addons" element={ <Addons /> } />
					<Route path="/settings" element={ <h1>Settings</h1> } />
					<Route path="/support" element={ <h1>Support</h1> } />
				</Route>
			</Routes>
		</HashRouter>
	);
}

export default AdminDashboard;
