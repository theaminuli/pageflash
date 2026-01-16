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
 * @param {Object} props                   - The component props
 * @param {string} props.slug              - The landmark slug identifier
 * @param {string} props.inputKey          - The key/name of the input field
 * @param {Object} props.field             - The field configuration object
 * @param {string} props.field.type        - The type of input field (e.g., 'select')
 * @param {string} props.field.label       - The label/heading for the field
 * @param {string} props.field.description - The description text for the field
 * @param {*}      props.field.value       - The current value of the field
 * @param {*}      props.field.default     - The default value if no value is set
 * @param {Array}  props.field.options     - The available options for select-type fields
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
	 * @async
	 * @function handleInputChange
	 * @param {string} slug     - The unique identifier/slug for the landmark to be updated.
	 * @param {string} inputKey - The key of the input field being updated.
	 * @param {*}      newValue - The new value to be set for the input field.
	 * @return {Promise<void>} A promise that resolves when the landmark is updated and the success toast is shown.
	 * @throws {Error} May throw an error if the updateLandmark operation fails.
	 */
	const handleInputChange = async ( slug, inputKey, newValue ) => {
		await updateLandmark( slug, {
			input: {
				[ inputKey ]: {
					value: newValue,
				},
			},
		} );
		toast.success( 'Setting updated!', {
			autoClose: 1000,
		} );
	};
	switch ( field.type ) {
		case 'select':
			return (
				<Select
					titleSize={ 5 }
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
