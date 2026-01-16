/**
 * WordPress dependencies
 */

import { cog } from '@wordpress/icons';

/**
 * External dependencies
 */
import { LiaExternalLinkAltSolid } from 'react-icons/lia';
import { LuPlug2 } from 'react-icons/lu';
import { MdSpeed } from 'react-icons/md';
import { RxLapTimer } from 'react-icons/rx';
/**
 * Menu icon mapping.
 *
 * Maps menu slugs to their corresponding React icon components.
 * Add or modify icons here to control menu appearance.
 *
 * @type {Object<string, JSX.Element>}
 */
export const MENU_ICONS = {
	general: <RxLapTimer />,
	preloading: <MdSpeed />,
	addons: <LuPlug2 />,
	settings: cog,
	support: <LiaExternalLinkAltSolid />,
};
