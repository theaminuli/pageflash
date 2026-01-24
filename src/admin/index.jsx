/**
 * WordPress dependencies
 */

import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';
import { Fragment, StrictMode } from 'react';
/**
 * Internal dependencies
 */
import '../scss/admin.scss';
import App from './app';

// Initialize the admin dashboard application
const initAdminApp = () => {
	const rootElement = document.getElementById( 'pageflash-admin' );
	const isDevelopment = window?.pageflashAdmin?.isDevelopment;
	const AppWrapper = Boolean( isDevelopment ) ? StrictMode : Fragment;

	if ( ! rootElement ) {
		console.error(
			'Root element not found. Make sure #pageflash-admin exists in the DOM.'
		);
		return;
	}

	const root = createRoot( rootElement );

	root.render(
		<AppWrapper>
			<App />
		</AppWrapper>
	);
};

domReady( () => {
	initAdminApp();
} );
