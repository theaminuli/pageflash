const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const RemoveEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );
const path = require( 'path' );

// Add any a new entry point by extending the webpack config.
module.exports = {
	...defaultConfig,
	...{
		/**
		 * @see https://developer.wordpress.org/themes/advanced-topics/build-process/
		 */
		entry: {
			...defaultConfig.entry(),
			'admin/admin': path.resolve(
				process.cwd(),
				'src/admin',
				'index.js'
			),
			'frontend/frontend': path.resolve(
				process.cwd(),
				'src/frontend',
				'index.js'
			),
		},
		plugins: [
			// Include WP's plugin config.
			...defaultConfig.plugins,

			// Removes the empty `.js` files generated by webpack but
			// sets it after WP has generated its `*.asset.php` file.
			new RemoveEmptyScriptsPlugin( {
				stage: RemoveEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS,
			} ),
		],
	},
};
