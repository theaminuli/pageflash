/**
 * WordPress dependencies.
 */
import {
	Card,
	CardBody,
	__experimentalGrid as Grid,
	__experimentalHeading as Heading,
	__experimentalHStack as HStack,
	Icon,
	__experimentalText as Text,
	__experimentalVStack as VStack,
} from '@wordpress/components';
import { FiExternalLink } from 'react-icons/fi';
import { ADDONS } from '../../constants';

/**
 * Render Cards
 */
const Addons = () => {
	return (
		<Grid columns={ [ 1, 2, 4 ] } gap={ 5 } className="pageflash-addons">
			{ ADDONS.plugins.map( ( addon ) => (
				<Card key={ addon.id }>
					<CardBody>
						<VStack spacing={ 4 }>
							<HStack>
								<CardBody
									style={ { padding: '0px' } }
									size="XSmall"
								>
									<img
										className="pageflash-addons__image"
										src={ addon.logo }
										alt="Addon logo"
									/>
								</CardBody>
								<span className="pageflash-addons__badge">
									{ addon.badge }
								</span>
							</HStack>
							<VStack>
								<Heading level={ 4 } weight={ 500 }>
									{ addon.name }
								</Heading>
								<Text
									size={ 15 }
									lineHeight={ 1.6 }
									variant="muted"
								>
									{ addon.description }
								</Text>
								<div className="pageflash-addons__explore-link">
									<a
										href={ addon.link }
										target="_blank"
										rel="noopener noreferrer"
									>
										Let's Explore
										<Icon
											icon={ FiExternalLink }
											size={ 16 }
											style={ { marginLeft: '4px' } }
										/>
									</a>
								</div>
							</VStack>
						</VStack>
					</CardBody>
				</Card>
			) ) }
		</Grid>
	);
};

export default Addons;
