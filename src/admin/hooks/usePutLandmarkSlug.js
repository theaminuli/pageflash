import apiFetch from '@wordpress/api-fetch';
import { useState } from 'react';

/**
 * Custom hook to update a landmark by slug
 *
 * @returns {Object} Object containing updateLandmark function, loading state, and error state
 */
const usePutLandmarkSlug = () => {
	const [ loading, setLoading ] = useState( false );
	const [ error, setError ] = useState( null );

	/**
	 * Updates a landmark with any provided data
	 *
	 * @param {string} slug - The landmark slug
	 * @param {Object} updateData - Object containing fields to update (e.g., { active: true }, { input: { behavior: 'value' } })
	 * @returns {Promise<Object>} The updated landmark data
	 */
	const updateLandmark = async ( slug, updateData ) => {
		setLoading( true );
		setError( null );

		try {
			const response = await apiFetch( {
				path: `/pageflash/v1/landmark/${ slug }`,
				method: 'PUT',
				data: updateData,
			} );

			setLoading( false );
			return response;
		} catch ( err ) {
			setError( err.message || 'Failed to update landmark' );
			setLoading( false );
			throw err;
		}
	};

	return {
		updateLandmark,
		loading,
		error,
	};
};

export default usePutLandmarkSlug;
