/**
 * WordPress dependencies
 */
import { Button, __experimentalVStack as VStack } from '@wordpress/components';

/**
 * External dependencies
 */
import { useNavigate } from 'react-router';

const MenuList = ({ activeMenu, onButtonClick, menus = [] }) => {
	const navigate = useNavigate();

	return (
		<VStack className="pageflash-header__menu-list" spacing={0}>
			{menus.map(({ slug, label, icon }, index) => (
				<Button
					key={slug}
					className="pageflash-header__menu-button"
					variant={activeMenu === slug ? 'primary' : 'secondary'}
					icon={icon}
					onClick={() => {
						onButtonClick(slug);
						navigate(index === 0 ? '/' : `/${slug}`);
					}}
				>
					{label}
				</Button>
			))}
		</VStack>
	);
};

export default MenuList;
