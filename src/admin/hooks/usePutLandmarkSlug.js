import apiFetch from '@wordpress/api-fetch';
import { useState } from '@wordpress/element';
import { updateLandmark as updateLandmarkAction } from '../actions';
import usePageflashContext from './usePageflashContext';

/**
 * Custom hook to update a landmark by slug
 *
 * @return {Object} Object containing updateLandmark function, loading state, and error state
 */
const usePutLandmarkSlug = () => {
	const { dispatch } = usePageflashContext();
	const [loading, setLoading] = useState(false);
	const [error, setError] = useState(null);

	/**
	 * Updates a landmark with any provided data
	 *
	 * @param {string} slug       - The landmark slug
	 * @param {Object} updateData - Object containing fields to update (e.g., { active: true }, { input: { behavior: 'value' } })
	 * @return {Promise<Object>} The updated landmark data
	 */
	const updateLandmark = async (slug, updateData) => {
		setLoading(true);
		setError(null);

		try {
			console.log('Sending update request:', { slug, updateData });

			const response = await apiFetch({
				path: `/pageflash/v1/landmark/${slug}`,
				method: 'PUT',
				data: updateData,
			});

			console.log('Update response:', response);

			// Update local state with the returned data
			if (response.data) {
				dispatch(updateLandmarkAction(slug, response.data));
			}

			setLoading(false);
			return response;
		} catch (err) {
			setError(err.message || 'Failed to update landmark');
			setLoading(false);
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
