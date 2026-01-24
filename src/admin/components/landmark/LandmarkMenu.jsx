/**
 * WordPress dependencies
 */
import { __experimentalHStack as HStack } from '@wordpress/components';
import { useMemo } from '@wordpress/element';
/**
 * External dependencies
 */
import { toast } from 'react-toastify';
/**
 * Internal dependencies
 */
import { FormField, Select, Switch } from '../../common';
import { useGetLandmarks, usePutLandmarkSlug } from '../../hooks';
import { filterLandmarksByMenu, formatSelectOptions } from '../../utils';
import LandmarkInput from './LandmarkInput';

/**
 * Generic Landmark Menu Component
 * Dynamically renders landmarks for a specific menu based on menuSlug.
 *
 * @param {Object} props          - Component props
 * @param {string} props.menuSlug - The menu identifier (e.g., 'general', 'preloading', 'advanced')
 * @return {JSX.Element} Rendered landmark menu
 */
const LandmarkMenu = ( { menuSlug } ) => {
	const { landmarks, loading, error } = useGetLandmarks();
	const { updateLandmark, loading: updating } = usePutLandmarkSlug();

	/**
	 * Filtered landmarks data containing only items from the specified menu.
	 * Memoized to recalculate only when the landmarks or menuSlug dependency changes.
	 *
	 * @type {Array}
	 */
	const data = useMemo(
		() => filterLandmarksByMenu( landmarks, menuSlug ),
		[ landmarks, menuSlug ]
	);

	/**
	 * Handles the change event for landmark active status.
	 * Updates the landmark with the new active state and displays a toast notification.
	 *
	 * @function handleChange
	 * @param {boolean} newValue - The new active status value for the landmark
	 * @param {string}  slug     - The unique identifier/slug of the landmark to update
	 */
	const handleChange = ( newValue, slug ) => {
		updateLandmark( slug, {
			active: newValue,
		} )
			.then( () => {
				if ( newValue ) {
					toast.success( 'Settings enabled!', {
						autoClose: 1000,
					} );
				} else {
					toast.info( 'Settings disabled.', {
						autoClose: 1000,
					} );
				}
			} )
			.catch( ( err ) => {
				toast.error( 'Failed to update setting', {
					autoClose: 2000,
				} );
			} );
	};

	/**
	 * Handles the change event for value-based fields (select, input, text, textarea).
	 * Updates the landmark with the new value and displays a toast notification.
	 *
	 * @function handleValueChange
	 * @param {string|number} newValue - The new value for the field
	 * @param {string}        slug     - The unique identifier/slug of the landmark to update
	 */
	const handleValueChange = ( newValue, slug ) => {
		updateLandmark( slug, {
			value: newValue,
		} )
			.then( () => {
				toast.success( 'Setting updated!', {
					autoClose: 1000,
				} );
			} )
			.catch( ( err ) => {
				toast.error( 'Failed to update setting', {
					autoClose: 2000,
				} );
			} );
	};

	return (
		<HStack
			alignment="normal"
			direction="column"
			spacing={ 5 }
			className={ `pageflash-body pageflash-${ menuSlug }` }
		>
			{ data.map( ( item ) => {
				if ( item.type === 'switch' ) {
					return (
						<Switch
							key={ item.id }
							heading={ item.label }
							description={ item.description }
							checked={ item.active || false }
							onToggle={ ( newValue ) =>
								handleChange( newValue, item.slug )
							}
							externalLink={ false }
						>
							{ item.active &&
								item.input &&
								Object.entries( item.input ).map(
									( [ key, field ] ) => (
										<LandmarkInput
											key={ key }
											slug={ item.slug }
											inputKey={ key }
											field={ field }
										/>
									)
								) }
						</Switch>
					);
				}
				if ( item.type === 'select' ) {
					return (
						<Select
							key={ item.id }
							heading={ item.label }
							description={ item.description }
							value={ item.value || '' }
							onChange={ ( newValue ) =>
								handleValueChange( newValue, item.slug )
							}
							options={ formatSelectOptions(
								item.options || []
							) }
							externalLink={ false }
						/>
					);
				}
				if (
					item.type === 'input' ||
					item.type === 'text' ||
					item.type === 'textarea'
				) {
					return (
						<FormField
							key={ item.id }
							type={ item.type }
							heading={ item.label }
							description={ item.description }
							value={ item.value || '' }
							onChange={ ( newValue ) =>
								handleValueChange( newValue, item.slug )
							}
							externalLink={ false }
						/>
					);
				}
				return null;
			} ) }
		</HStack>
	);
};

export default LandmarkMenu;
