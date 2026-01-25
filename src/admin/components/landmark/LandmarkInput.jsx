/**
 * WordPress dependencies
 */
import { useEffect, useState } from '@wordpress/element';

/**
 * External dependencies
 */
import { toast } from 'react-toastify';

/**
 * Internal dependencies
 */
import { FormField, Select } from '../../common';
import useDebounce from '../../hooks/useDebounce';
import { formatSelectOptions } from '../../utils';
import { usePutLandmarkSlug } from '../../hooks';

/**
 * Renders an input component based on the field type for landmark configuration.
 *
 * @param {Object} props          - The component props
 * @param {string} props.slug     - The landmark slug identifier
 * @param {string} props.inputKey - The key/name of the input field
 * @param {Object} props.field    - The field configuration object containing type, label, description, value, default, and options
 * @return {JSX.Element|null} The rendered input component or null if field type is not supported
 */
const LandmarkInput = ( { slug, inputKey, field } ) => {
	const { updateLandmark, loading: updating } = usePutLandmarkSlug();
	const [ localValue, setLocalValue ] = useState(
		field.value ?? field.default ?? ''
	);
	const debouncedValue = useDebounce( localValue, 1000 );

	useEffect( () => {
		if (
			( field.type === 'text' ||
				field.type === 'textarea' ||
				field.type === 'input' ) &&
			debouncedValue !== ( field.value ?? field.default ?? '' )
		) {
			handleInputChange( slug, inputKey, debouncedValue );
		}
	}, [ debouncedValue ] );

	/**
	 * Handles input changes for landmark settings and updates the landmark data.
	 *
	 * @function handleInputChange
	 * @param {string} landmarkSlug - The unique identifier/slug for the landmark to be updated.
	 * @param {string} key          - The key of the input field being updated.
	 * @param {*}      newValue     - The new value to be set for the input field.
	 */
	const handleInputChange = ( landmarkSlug, key, newValue ) => {
		updateLandmark( landmarkSlug, {
			input: {
				[ key ]: {
					value: newValue,
				},
			},
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

	switch ( field.type ) {
		case 'select':
			return (
				<Select
					titleSize={ 5 }
					externalLink={ false }
					heading={ field.label }
					description={ field.description }
					value={ field.value ?? field.default }
					options={ formatSelectOptions( field.options ) }
					onChange={ ( value ) =>
						handleInputChange( slug, inputKey, value )
					}
				/>
			);
		case 'text':
			return (
				<FormField
					type="text"
					titleSize={ 5 }
					externalLink={ false }
					heading={ field.label }
					description={ field.description }
					value={ localValue }
					onChange={ setLocalValue }
				/>
			);
		case 'textarea':
			return (
				<FormField
					type="textarea"
					titleSize={ 5 }
					externalLink={ false }
					heading={ field.label }
					description={ field.description }
					value={ localValue }
					onChange={ setLocalValue }
				/>
			);
		case 'input':
			return (
				<FormField
					type="input"
					titleSize={ 5 }
					externalLink={ false }
					heading={ field.label }
					description={ field.description }
					value={ localValue }
					onChange={ setLocalValue }
				/>
			);
		default:
			return null;
	}
};

export default LandmarkInput;
