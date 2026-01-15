import { __ } from '@wordpress/i18n';
import { cog } from '@wordpress/icons';
import { LiaExternalLinkAltSolid } from 'react-icons/lia';
import { LuPlug2 } from 'react-icons/lu';
import { RxLapTimer } from 'react-icons/rx';

import { createRandomId } from '../utils';
export const MENU_LIST = [
	{
		id: createRandomId(),
		key: 'general',
		label: __( 'General', 'pageflash' ),
		icon: <RxLapTimer style={ { width: '20px' } } />,
	},
	{
		id: createRandomId(),
		key: 'settings',
		label: __( 'Settings', 'pageflash' ),
		icon: cog,
	},
	{
		id: createRandomId(),
		key: 'addons',
		label: __( 'Addons', 'pageflash' ),
		icon: <LuPlug2 />,
	},
	// {
	// 	id: createRandomId(),
	// 	key: 'license',
	// 	label: __( 'License', 'pageflash' ),
	// 	icon: key,
	// },
	{
		id: createRandomId(),
		key: 'support',
		label: __( 'Support', 'pageflash' ),
		icon: <LiaExternalLinkAltSolid />,
	},
];

export const ADDONS = {
	id: createRandomId(),
	key: 'addons',
	label: __( 'Addons', 'pageflash' ),
	description: __(
		'Explore and install addons to extend your website functionality.',
		'pageflash'
	),
	plugins: [
		{
			id: createRandomId(),
			name: __( 'Contact Form 7', 'pageflash' ),
			slug: 'contact-form-7',
			author: __( 'Takayuki Miyoshi', 'pageflash' ),
			description: __(
				'A minimal and flexible plugin for creating contact forms easily.',
				'pageflash'
			),
			badge: __( 'Free', 'pageflash' ),
			logo: 'https://ps.w.org/contact-form-7/assets/icon-256x256.png',
			link: 'https://wordpress.org/plugins/contact-form-7/',
		},
		{
			id: createRandomId(),
			name: __( 'Site Kit by Google', 'pageflash' ),
			slug: 'google-site-kit',
			author: __( 'Google', 'pageflash' ),
			description: __(
				'The official plugin for Google services insights and site stats.',
				'pageflash'
			),
			badge: __( 'Free', 'pageflash' ),
			logo: 'https://ps.w.org/google-site-kit/assets/icon-256x256.png',
			link: 'https://wordpress.org/plugins/google-site-kit/',
		},
		{
			id: createRandomId(),
			name: __( 'WPForms Lite', 'pageflash' ),
			slug: 'wpforms-lite',
			author: __( 'WPForms', 'pageflash' ),
			description: __(
				'A beginner-friendly drag and drop builder for contact forms.',
				'pageflash'
			),
			badge: __( 'Free', 'pageflash' ),
			logo: 'https://ps.w.org/wpforms-lite/assets/icon-256x256.png',
			link: 'https://wordpress.org/plugins/wpforms-lite/',
		},
		{
			id: createRandomId(),
			name: __( 'Yoast SEO', 'pageflash' ),
			slug: 'wordpress-seo',
			author: __( 'Team Yoast', 'pageflash' ),
			description: __(
				'Boost your site ranking with smart SEO suggestions and tools.',
				'pageflash'
			),
			badge: __( 'Free', 'pageflash' ),
			logo: 'https://ps.w.org/wordpress-seo/assets/icon-128x128.gif',
			link: 'https://wordpress.org/plugins/wordpress-seo/',
		},
		{
			id: createRandomId(),
			name: __( 'Really Simple SSL', 'pageflash' ),
			slug: 'really-simple-ssl',
			author: __( 'Really Simple Plugins', 'pageflash' ),
			description: __(
				'Easily enable HTTPS and fix insecure content with one click.',
				'pageflash'
			),
			badge: __( 'Free', 'pageflash' ),
			logo: 'https://ps.w.org/really-simple-ssl/assets/icon-256x256.png',
			link: 'https://wordpress.org/plugins/really-simple-ssl/',
		},
		{
			id: createRandomId(),
			name: __( 'Wordfence Security', 'pageflash' ),
			slug: 'wordfence',
			author: __( 'Wordfence', 'pageflash' ),
			description: __(
				'Firewall and malware scanner for WordPress.',
				'pageflash'
			),
			badge: __( 'Free', 'pageflash' ),
			logo: 'https://ps.w.org/wordfence/assets/icon-256x256.png',
			link: 'https://wordpress.org/plugins/wordfence/',
		},
		{
			id: createRandomId(),
			name: __( 'LiteSpeed Cache', 'pageflash' ),
			slug: 'litespeed-cache',
			author: __( 'LiteSpeed Technologies', 'pageflash' ),
			description: __(
				'Speed up your site with all-in-one site acceleration plugin.',
				'pageflash'
			),
			badge: __( 'Free', 'pageflash' ),
			logo: 'https://ps.w.org/litespeed-cache/assets/icon-256x256.png',
			link: 'https://wordpress.org/plugins/litespeed-cache/',
		},
		{
			id: createRandomId(),
			name: __( 'MailPoet', 'pageflash' ),
			slug: 'mailpoet',
			author: __( 'MailPoet', 'pageflash' ),
			description: __(
				'Create and send email newsletters directly from WordPress dashboard.',
				'pageflash'
			),
			badge: __( 'Free', 'pageflash' ),
			logo: 'https://ps.w.org/mailpoet/assets/icon-256x256.png',
			link: 'https://wordpress.org/plugins/mailpoet/',
		},
	],
};
