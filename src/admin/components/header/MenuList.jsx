import { Button, __experimentalVStack as VStack } from '@wordpress/components';
import { useNavigate } from 'react-router';
import { MENU_LIST } from '../../constants';

const MenuList = ( { activeMenu, onButtonClick } ) => {
	const navigate = useNavigate();
	return (
		<VStack className="pageflash-header__menu-list" spacing={ 0 }>
			{ MENU_LIST.map( ( { id, key, label, icon } ) => (
				<Button
					key={ id }
					className="pageflash-header__menu-button"
					variant={ activeMenu === key ? 'primary' : 'secondary' }
					icon={ icon }
					onClick={ () => {
						onButtonClick( key );
						navigate( key === 'general' ? '/' : `/${ key }` );
					} }
				>
					{ label }
				</Button>
			) ) }
		</VStack>
	);
};

export default MenuList;
