import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';
import '../scss/admin.scss';
import App from './App';

domReady( () => {
	const pageflashAdmin = document.getElementById( 'pageflash-admin' );
	if ( pageflashAdmin ) {
		const root = createRoot( pageflashAdmin );
		root.render( <App /> );
	}
} );
