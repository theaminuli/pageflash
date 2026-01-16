/**
 * WordPress dependencies
 */
import {
	Card,
	CardBody,
	Flex,
	FlexItem,
	__experimentalHeading as Heading,
	__experimentalText as Text,
	ToggleControl,
} from '@wordpress/components';

/**
 * External dependencies
 */
import { LiaExternalLinkAltSolid } from 'react-icons/lia';

/**
 * A reusable switch component that displays a toggle control with heading, description, and optional children.
 *
 * @component
 * @param {Object}          props             - The component props
 * @param {string}          props.heading     - The heading text displayed at the top of the switch card
 * @param {string}          props.description - The descriptive text explaining the switch functionality
 * @param {boolean}         props.checked     - The current state of the toggle control
 * @param {Function}        props.onToggle    - Callback function triggered when the toggle state changes
 * @param {React.ReactNode} [props.children]  - Optional child elements rendered below the main content with indentation
 *
 * @return {JSX.Element} A Card component containing a toggle switch with heading, description, external link icon, and optional children
 */
const Switch = ( { heading, description, checked, onToggle, children } ) => {
	return (
		<>
			<Card className="pageflash-switch" size="large">
				<CardBody>
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
									rel="noreferrer"
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
					{ children && (
						<div
							style={ { marginTop: '16px', marginLeft: '20px' } }
						>
							{ children }
						</div>
					) }
				</CardBody>
			</Card>
		</>
	);
};

export default Switch;
