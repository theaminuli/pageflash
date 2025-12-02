import {
	Card,
	CardBody,
	Flex,
	FlexItem,
	__experimentalHeading as Heading,
	__experimentalText as Text,
	ToggleControl,
} from '@wordpress/components';

import { useEffect } from 'react';
import { LiaExternalLinkAltSolid } from 'react-icons/lia';

const Switch = ( { heading, description, checked, onToggle } ) => {
	return (
		<Card size="large" style={ { borderRadius: '8px' } }>
			<CardBody style={ { padding: '20px' } }>
				<Flex gap={ 2 }>
					<FlexItem>
						<Flex
							direction="row"
							gap={ 1 }
							justify="start"
							align="center"
						>
							<Heading level={ 4 }>{ heading }</Heading>
							<a
								href="#"
								target="_blank"
								style={ { textDecoration: 'none' } }
							>
								<Text>
									<LiaExternalLinkAltSolid size={ 22 } />
								</Text>
							</a>
						</Flex>
						<Text
							size={ 14 }
							lineHeight={ 1.6 }
							weight={ 400 }
							style={ { maxWidth: '900px' } }
							variant="muted"
						>
							{ description }
						</Text>
					</FlexItem>

					<FlexItem>
						<ToggleControl
							__nextHasNoMarginBottom
							checked={ checked }
							onChange={ onToggle }
						/>
					</FlexItem>
				</Flex>
			</CardBody>
		</Card>
	);
};

export default Switch;
