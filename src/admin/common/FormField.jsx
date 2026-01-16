import {
	Card,
	CardBody,
	Flex,
	FlexItem,
	__experimentalHeading as Heading,
	__experimentalInputControl as InputControl,
	__experimentalText as Text,
	TextareaControl,
	TextControl,
} from '@wordpress/components';

/**
 * FormField - A unified input component for WordPress Gutenberg forms.
 * Renders TextControl, InputControl, or TextareaControl based on the `type` prop.
 *
 * @param {Object} props - Component props
 * @param {'text'|'input'|'textarea'} props.type - Type of input to render
 * @param {string} props.label - Label for the input field
 * @param {string} [props.help] - Help text below the input
 * @param {string|number} props.value - Controlled value
 * @param {Function} props.onChange - Change handler
 * @param {Object} [props.inputProps] - Additional props for the input control
 * @param {string} [props.heading] - Optional card heading
 * @param {string} [props.description] - Optional card description
 * @param {number} [props.titleSize=4] - Heading level
 * @returns {JSX.Element}
 */
const FormField = ( {
	type = 'text',
	label,
	help,
	value,
	onChange,
	inputProps = {},
	heading,
	description,
	titleSize = 4,
} ) => {
	let Control;
	if ( type === 'textarea' ) Control = TextareaControl;
	else if ( type === 'input' ) Control = InputControl;
	else Control = TextControl;

	return (
		<Card className="pageflash-formfield" size="large">
			<CardBody>
				<Flex gap={ 4 } direction="column">
					{ heading && (
						<FlexItem>
							<Heading level={ titleSize }>{ heading }</Heading>
							{ description && (
								<Text
									size={ 13 }
									lineHeight={ 1.6 }
									weight={ 400 }
									style={ { marginTop: '8px' } }
									variant="muted"
								>
									{ description }
								</Text>
							) }
						</FlexItem>
					) }
					<FlexItem>
						<Control
							label={ label }
							help={ help }
							value={ value }
							onChange={ onChange }
							{ ...inputProps }
						/>
					</FlexItem>
				</Flex>
			</CardBody>
		</Card>
	);
};

export default FormField;
