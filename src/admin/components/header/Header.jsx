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
import { useViewportMatch } from '@wordpress/compose';
import { close, menu } from '@wordpress/icons';
import { AiTwotoneRocket } from 'react-icons/ai';
import { LiaExternalLinkAltSolid } from 'react-icons/lia';
import { toast } from 'react-toastify';
/**
 * Internal dependencies.
 */
import { useState } from 'react';
import { setActiveMenu } from '../../actions';
import { usePageflashContext } from '../../hooks';
import { capitalizeFirstLetter } from '../../utils';
import MenuList from './MenuList';

/**
 * Render Shell 2
 */
const Header = ( { children } ) => {
	const { activeMenu, dispatch } = usePageflashContext();
	const isDesktop = useViewportMatch( 'medium', '>=' );
	const isMobile = useViewportMatch( 'medium', '<' );
	const [ showButtons, setShowButtons ] = useState( false );

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
					expanded={ true }
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
										PageFlash
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
								/>
							) }
						</VStack>
					</CardBody>
					<ZStack isReversed className="pageflash-header__z-stack">
						{ isMobile && showButtons && (
							<MenuList
								activeMenu={ activeMenu }
								onButtonClick={ handleButtonClick }
							/>
						) }
						<CardBody className="pageflash-header__card-body">
							<HStack
								expanded={ false }
								className={ 'pageflash-header__h-stack' }
							>
								<Heading>
									{ capitalizeFirstLetter( activeMenu ) }
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
									Get Feature
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
