import apiFetch from '@wordpress/api-fetch';
import { useCallback, useEffect, useState } from '@wordpress/element';
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

	const fetchLandmarks = useCallback( async () => {
		setLoading( true );
		setError( null );

		try {
			const response = await apiFetch( {
				path: '/pageflash/v1/landmark',
				method: 'GET',
			} );
			dispatch( setLandmarks( response.data ) );
		} catch ( err ) {
			setError( err.message || 'Failed to fetch landmarks' );
		} finally {
			setLoading( false );
		}
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