/**
 * WordPress dependencies.
 */
import { __experimentalHStack as HStack } from '@wordpress/components';

import { toast } from 'react-toastify';
import { Switch } from '../../common';
import { useGetLandmarks, usePutLandmarkSlug } from '../../hooks';
import { filterLandmarksByMenu } from '../../utils';
/**
 * Render Action panel
 */
const General = () => {
	const { landmarks, loading, error } = useGetLandmarks();
	const { updateLandmark, loading: updating } = usePutLandmarkSlug();
	const data = filterLandmarksByMenu( landmarks, 'general' );

	const handleChange = async ( newValue, slug ) => {
		try {
			await updateLandmark( slug, { active: newValue } );
			if ( newValue ) {
				toast.success( 'Settings enabled!', {
					autoClose: 2000,
					hideProgressBar: false,
					closeOnClick: true,
					pauseOnHover: true,
					draggable: true,
					progress: undefined,
				} );
			} else {
				toast.info( 'Settings disabled.', {
					autoClose: 2000,
					hideProgressBar: false,
					closeOnClick: true,
					pauseOnHover: true,
					draggable: true,
					progress: undefined,
				} );
			}
		} catch ( err ) {
			toast.error( 'Failed to update settings.', {
				autoClose: 2000,
			} );
		}
	};
	return (
		<HStack
			alignment="normal"
			direction="column"
			spacing={ 5 }
			className="pageflash-general"
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
						/>
					);
				}
				if ( item.type === 'text' ) {
					return <p key={ item.id }>{ item.content }</p>;
				}
			} ) }
		</HStack>
	);
};

export default General;
