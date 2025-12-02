import { useContext } from '@wordpress/element';
import { AdminContext } from '../context';

/**
 * Custom hook to access the Pageflash context.
 *
 * @throws {Error} Throws an error if the Pageflash context is not found.
 * @return {Object} The current value of the Pageflash context.
 */
const usePageflashContext = () => {
	const pageflashContext = useContext( AdminContext );

	if ( ! pageflashContext ) {
		throw new Error(
			'usePageflashContext must be used within a PageflashProvider'
		);
	}

	return pageflashContext;
};

export default usePageflashContext;
