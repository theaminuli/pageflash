/**
 * WordPress dependencies.
 */

import { useEffect } from "react";
import { setActiveMenu } from "./actions";
import { Addons } from "./components/addons";
import { Settings } from "./components/settings";
import { Welcome } from "./components/welcome";
import { usePageflashContext } from "./hooks";

function AdminDashboard() {
	const { activeMenu, dispatch } = usePageflashContext();
	const ROUTE_COMPONENTS = {
		welcome: Welcome,
		settings: Settings,
		addons: Addons,
	};

	// Update URL hash when activeMenu changes (exclude support)
	useEffect(() => {
		if (activeMenu && activeMenu !== "welcome" && activeMenu !== "support") {
			window.location.hash = `#${activeMenu}`;
		} else if (activeMenu === "welcome") {
			if (window.location.hash) {
				window.history.replaceState(
					null,
					null,
					window.location.pathname + window.location.search,
				);
			}
		}
		// Do nothing for support - no URL change
	}, [activeMenu]);

	// Listen for hash changes and update activeMenu
	useEffect(() => {
		const handleHashChange = () => {
			const hash = window.location.hash.replace("#", "");
			if (hash && ROUTE_COMPONENTS[hash] && hash !== activeMenu) {
				dispatch(setActiveMenu(hash));
			} else if (
				!hash &&
				activeMenu !== "welcome" &&
				activeMenu !== "support"
			) {
				dispatch(setActiveMenu("welcome"));
			}
		};

		handleHashChange();
		window.addEventListener("hashchange", handleHashChange);

		return () => {
			window.removeEventListener("hashchange", handleHashChange);
		};
	}, [activeMenu, dispatch]);

	// Get the active component - support shows the previous component
	const getActiveComponent = () => {
		if (activeMenu === "support") {
			// Show Welcome component when support is selected
			return ROUTE_COMPONENTS.welcome;
		}
		return ROUTE_COMPONENTS[activeMenu] || ROUTE_COMPONENTS.welcome;
	};

	const ActiveComponent = getActiveComponent();

	return <ActiveComponent />;
}

export default AdminDashboard;
