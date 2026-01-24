/**
 * WordPress dependencies
 */
import {
	Button,
	Card,
	CardBody,
	CardHeader,
	Flex,
	FlexBlock,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */
import { LiaExternalLinkAltSolid } from 'react-icons/lia';

function Support() {
	return (
		<div className="pageflash-support">
			{ /* Documentation Section */ }
			<Card>
				<CardHeader>
					<h2>{ __( 'Documentation', 'pageflash' ) }</h2>
				</CardHeader>
				<CardBody>
					<p>
						{ __(
							'Need help? Check out our in-depth documentation. Every feature has a step-by-step walkthrough.',
							'pageflash'
						) }
					</p>
					<Button
						variant="secondary"
						icon={ <LiaExternalLinkAltSolid /> }
						iconPosition="right"
						href="#"
						// target="_blank"
						rel="noopener noreferrer"
					>
						{ __( 'Documentation', 'pageflash' ) }
					</Button>
				</CardBody>
			</Card>

			{ /* Contact Us Section */ }
			<Card>
				<CardHeader>
					<h2>{ __( 'Contact Us', 'pageflash' ) }</h2>
				</CardHeader>
				<CardBody>
					<p>
						{ __(
							"If you have questions or problems, please send us a message. We'll get back to you as soon as possible.",
							'pageflash'
						) }
					</p>
					<Button
						variant="secondary"
						icon={ <LiaExternalLinkAltSolid /> }
						iconPosition="right"
						href="#"
						// target="_blank"
						rel="noopener noreferrer"
					>
						{ __( 'Contact Us', 'pageflash' ) }
					</Button>
				</CardBody>
			</Card>

			{ /* FAQ Section */ }
			<Card>
				<CardHeader>
					<h2>{ __( 'Frequently Asked Questions', 'pageflash' ) }</h2>
				</CardHeader>
				<CardBody>
					<Flex gap={ 4 } wrap={ true }>
						<FlexBlock>
							<div className="pageflash-support-faq-column">
								<Button
									variant="link"
									href="#"
									// target="_blank"
									rel="noopener noreferrer"
								>
									{ __(
										'How do I license activate the plugin?',
										'pageflash'
									) }
								</Button>
								<Button
									variant="link"
									href="#"
									// target="_blank"
									rel="noopener noreferrer"
								>
									{ __(
										'How do I update the plugin?',
										'pageflash'
									) }
								</Button>
								<Button
									variant="link"
									href="#"
									// target="_blank"
									rel="noopener noreferrer"
								>
									{ __(
										'How do I upgrade my license?',
										'pageflash'
									) }
								</Button>
								<Button
									variant="link"
									href="#"
									// target="_blank"
									rel="noopener noreferrer"
								>
									{ __(
										'Where can I view the changelog?',
										'pageflash'
									) }
								</Button>
								<Button
									variant="link"
									href="#"
									// target="_blank"
									rel="noopener noreferrer"
								>
									{ __(
										'Where can I sign up for the affiliate program?',
										'pageflash'
									) }
								</Button>
							</div>
						</FlexBlock>
						<FlexBlock>
							<div className="pageflash-support-faq-column">
								<Button
									variant="link"
									href="#"
									// target="_blank"
									rel="noopener noreferrer"
								>
									{ __(
										'How do I disable scripts on a per post/page basis?',
										'pageflash'
									) }
								</Button>
								<Button
									variant="link"
									href="#"
									// target="_blank"
									rel="noopener noreferrer"
								>
									{ __(
										'How do I delay JavaScript?',
										'pageflash'
									) }
								</Button>
								<Button
									variant="link"
									href="#"
									// target="_blank"
									rel="noopener noreferrer"
								>
									{ __(
										'How do I remove unused CSS?',
										'pageflash'
									) }
								</Button>
								<Button
									variant="link"
									href="#"
									// target="_blank"
									rel="noopener noreferrer"
								>
									{ __(
										'How do I lazy load images and videos?',
										'pageflash'
									) }
								</Button>
								<Button
									variant="link"
									href="#"
									// target="_blank"
									rel="noopener noreferrer"
								>
									{ __(
										'How do I host Google Analytics locally?',
										'pageflash'
									) }
								</Button>
							</div>
						</FlexBlock>
					</Flex>
				</CardBody>
			</Card>
		</div>
	);
}

export default Support;
