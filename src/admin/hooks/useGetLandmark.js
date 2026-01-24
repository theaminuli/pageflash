/**
 * WordPress dependencies
 */

import apiFetch from '@wordpress/api-fetch';
import { useCallback, useEffect, useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { setLandmarks } from '../actions';
import usePageflashContext from './usePageflashContext';

/**
 * Custom hook to fetch all landmarks
 *
 * @return {Object} { landmarks, loading, error, refetch }
 */
const useGetLandmarks = () => {
	const { landmarks, dispatch } = usePageflashContext();
	const [ loading, setLoading ] = useState( true );
	const [ error, setError ] = useState( null );

	const fetchLandmarks = useCallback( () => {
		setLoading( true );
		setError( null );

		apiFetch( {
			path: '/pageflash/v1/landmark',
			method: 'GET',
		} )
			.then( ( response ) => {
				dispatch( setLandmarks( response.data ) );
				setLoading( false );
			} )
			.catch( ( err ) => {
				setError( err.message || 'Failed to fetch landmarks' );
				setLoading( false );
			} );
	}, [] );

	useEffect( () => {
		fetchLandmarks();
	}, [] );

	return {
		landmarks,
		loading,
		error,
	};
};
export default useGetLandmarks;
