
import {
	Card,
	CardBody,
	Flex,
	FlexItem,
	__experimentalHeading as Heading,
	SelectControl,
	__experimentalText as Text,
} from '@wordpress/components';

/**
 * A reusable Select component that renders a card with a heading, description, and dropdown select control.
 * 
 * @param {Object} props - The component props.
 * @param {string} props.heading - The heading text displayed at the top of the card.
 * @param {string} [props.description] - Optional description text displayed below the heading.
 * @param {string|number} props.value - The currently selected value in the dropdown.
 * @param {Function} props.onChange - Callback function triggered when the select value changes.
 * @param {Array<{label: string, value: string|number}>} props.options - Array of option objects for the select dropdown.
 * @param {number} [props.titleSize=4] - The heading level (1-6) for the title. Defaults to 4.
 * @returns {JSX.Element} A Card component containing a SelectControl with heading and description.
 */
const Select = ({ heading, description, value, onChange, options, titleSize = 4 }) => {
	return (
		<Card className="pageflash-select" size="large">
			<CardBody>
				<Flex gap={4} direction="column">
					<FlexItem>
						<Heading level={titleSize}>{heading}</Heading>
						{description && (
							<Text
								size={13}
								lineHeight={1.6}
								weight={400}
								style={{ marginTop: '8px' }}
								variant="muted"
							>
								{description}
							</Text>
						)}
					</FlexItem>
					<FlexItem>
						<SelectControl
							size="default"
							value={value}
							onChange={onChange}
							options={options}
							__nextHasNoMarginBottom
						/>
					</FlexItem>
				</Flex>
			</CardBody>
		</Card>
	);
};

export default Select;