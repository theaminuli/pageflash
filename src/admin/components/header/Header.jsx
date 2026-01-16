/**
 * WordPress dependencies
 */
import {
	Button,
	Card,
	CardBody,
	Flex,
	__experimentalHeading as Heading,
	__experimentalHStack as HStack,
	__experimentalVStack as VStack,
	__experimentalZStack as ZStack,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useViewportMatch } from '@wordpress/compose';
import { close, menu } from '@wordpress/icons';

/**
 * External dependencies
 */
import { useState } from 'react';
import { AiTwotoneRocket } from 'react-icons/ai';
import { LiaExternalLinkAltSolid } from 'react-icons/lia';
import { toast } from 'react-toastify';

/**
 * Internal dependencies
 */

import { setActiveMenu } from '../../actions';
import { usePageflashContext } from '../../hooks';
import { capitalizeFirstLetter } from '../../utils';
import MenuList from './MenuList';

/**
 * Header component that renders the main navigation and content area.
 *
 * @param {Object}                    props          - The properties object.
 * @param {import('react').ReactNode} props.children - The child components to be rendered in the content area.
 * @param {Array<Object>}             props.menus    - The menu items to display.
 * @return {JSX.Element} The header component with navigation and content.
 */
const Header = ( { children, menus = [] } ) => {
	const { activeMenu, dispatch } = usePageflashContext();
	const isDesktop = useViewportMatch( 'medium', '>=' );
	const isMobile = useViewportMatch( 'medium', '<' );
	const [ showButtons, setShowButtons ] = useState( false );

	/**
	 * Handles button click events for menu navigation.
	 *
	 * @param {string} buttonKey - The key of the button that was clicked.
	 */
	const handleButtonClick = ( buttonKey ) => {
		dispatch( setActiveMenu( buttonKey ) );
		// if (buttonKey === 'support') {
		// 	window.open(
		// 		'https://github.com/theaminuli/pageflash/issues',
		// 		'_blank'
		// 	);
		// }
	};

	return (
		<>
			<Card className="pageflash-header">
				<Flex
					expanded
					gap={ 0 }
					align="top"
					direction={ [ 'column', 'column', 'row' ] }
				>
					<CardBody
						style={ { width: isDesktop ? '280px' : '100%' } }
						className="pageflash-header__menu"
					>
						<VStack spacing={ 8 } style={ { marginTop: '15px' } }>
							<HStack
								style={ {
									marginLeft: '8px',
									marginBottom: isDesktop ? '' : '10px',
								} }
							>
								<Flex
									className="pageflash-header__logo"
									justify="left"
								>
									<AiTwotoneRocket
										size={ 40 }
										color="#1c1e24"
									/>
									<Heading
										level={ 2 }
										style={ { marginRight: '10px' } }
									>
										{ __( 'PageFlash', 'pageflash' ) }
									</Heading>
								</Flex>
								{ isMobile && (
									<Button
										icon={ showButtons ? close : menu }
										onClick={ () =>
											setShowButtons(
												( prevState ) => ! prevState
											)
										}
										style={ {
											marginRight: '8px',
											color: '#1c1e24',
										} }
									></Button>
								) }
							</HStack>
							{ isDesktop && (
								<MenuList
									activeMenu={ activeMenu }
									onButtonClick={ handleButtonClick }
									menus={ menus }
								/>
							) }
						</VStack>
					</CardBody>
					<ZStack isReversed className="pageflash-header__z-stack">
						{ isMobile && showButtons && (
							<MenuList
								activeMenu={ activeMenu }
								onButtonClick={ handleButtonClick }
								menus={ menus }
							/>
						) }
						<CardBody className="pageflash-header__card-body">
							<HStack
								expanded={ false }
								className={ 'pageflash-header__h-stack' }
							>
								<Heading>
									{ menus.find(
										( m ) => m.slug === activeMenu
									)?.label ||
										capitalizeFirstLetter( activeMenu ) }
								</Heading>
								<Button
									variant="primary"
									icon={ <LiaExternalLinkAltSolid /> }
									onClick={ () =>
										toast.success(
											'Get feature coming soon!',
											{
												position: 'top-right',
												autoClose: 3000,
												hideProgressBar: false,
												closeOnClick: true,
												pauseOnHover: true,
												draggable: true,
												progress: undefined,
												theme: 'light',
											}
										)
									}
								>
									{ __( 'Get Pro', 'pageflash' ) }
								</Button>
							</HStack>
							{ children }
						</CardBody>
					</ZStack>
				</Flex>
			</Card>
		</>
	);
};

export default Header;
