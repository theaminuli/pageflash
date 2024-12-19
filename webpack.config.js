/**
 * Grunt webpack task config
 *
 * @package
 */
const path = require('path');

const CopyPlugin = require('copy-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');

const copyPluginConfig = {
	patterns: [
		{
			from: '**/*',
			context: __dirname,
			to: path.resolve(__dirname, 'build'),
			// Terser skip this file for minimization
			info: { minimized: true },
			globOptions: {
				ignore: [
					'**.zip',
					'**.css',
					'**/assets/**',
					'**/bin/**',
					'**/src/**',
					'**/build/**',
					'**/scripts/**',
					'**/composer.json',
					'**/composer.lock',
					'**/Gruntfile.js',
					'**/node_modules/**',
					'**/npm-debug.log',
					'**/package-lock.json',
					'**/package.json',
					'**/phpcs.xml',
					'**/README.md',
					'**/webpack.config.js',

					'**/vendor/wp-coding-standards/**',
					'**/vendor/tareq1988/**',
					'**/vendor/squizlabs/**',
					'**/vendor/phpcsstandards/**',
					'**/vendor/phpcompatibility/**',
					'**/vendor/dealerdirect/**',
					'**/vendor/bin/**',
				],
			},
		},
	],
};

const commonCopyConfig = {
	patterns: [
		{
			from: 'src/images/', // Source directory
			to: './images', // Destination directory
		},
		{
			from: 'src/libs/', // Source directory
			to: './libs', // Destination directory
		},
	],
};

const commonConfig = {
	patterns: [
		...commonCopyConfig.patterns, // Merge patterns from commonCopyConfig
	],
};
const mergedConfig = {
	patterns: [
		...commonCopyConfig.patterns, // Merge patterns from commonCopyConfig
		...copyPluginConfig.patterns, // Merge patterns from copyPluginConfig
	],
};

const moduleRules = {
	rules: [
		{
			test: /\.js$/,
			exclude: /node_modules/,
			use: [
				{
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env'],
						plugins: [
							['@babel/plugin-proposal-class-properties'],
							['@babel/plugin-transform-runtime'],
							['@babel/plugin-transform-modules-commonjs'],
							['@babel/plugin-proposal-optional-chaining'],
						],
					},
				},
			],
		},
	],
};

const entry = {
	'js/admin/pageflash-admin': path.resolve(__dirname, './src/js/admin/pageflash-admin.js'),
	'js/frontend/pageflash-frontend': path.resolve(__dirname, './src/js/frontend/index.js'),
};

const webpackConfig = {
	target: 'web',
	context: __dirname,
	module: moduleRules,
	entry,
	mode: 'development',
	output: {
		path: path.resolve(__dirname, './build/assets/'),
		filename: '[name].js',
		devtoolModuleFilenameTemplate: './[resource]',
	},
};

const webpackProductionConfig = {
	target: 'web',
	context: __dirname,
	module: moduleRules,
	entry: {
		...entry,
	},
	optimization: {
		minimize: true,
		minimizer: [
			new TerserPlugin({
				terserOptions: {
					keep_fnames: true,
				},
				include: /\.min\.js$/,
				exclude: /\.min\.js\.map$/
			}),
		],
	},
	mode: 'production',
	output: {
		path: path.resolve(__dirname, './build/assets/'),
		filename: '[name].js',
	},
	performance: { hints: false },
};

// Add minified entry points
Object.entries(webpackProductionConfig.entry).forEach(([wpEntry, value]) => {
	webpackProductionConfig.entry[wpEntry + '.min'] = value;

	delete webpackProductionConfig.entry[wpEntry];
});

const localOutputPath = {
	...webpackProductionConfig.output,
	path: path.resolve(__dirname, './assets')
};

module.exports = (env) => {
	if (env.developmentLocalWithWatch) {
		return {
			...webpackConfig,
			plugins: [new CopyPlugin(commonConfig)],
			watch: true,
			devtool: 'source-map',
			output: localOutputPath
		};
	}

	if (env.productionLocalWithWatch) {
		return {
			...webpackProductionConfig,
			watch: true,
			devtool: 'source-map',
			output: localOutputPath
		};
	}

	if (env.productionLocal) {
		return {
			...webpackProductionConfig,
			devtool: 'source-map',
			output: localOutputPath
		};
	}

	if (env.developmentLocal) {
		return {
			...webpackConfig,
			plugins: [new CopyPlugin(commonConfig)],
			devtool: 'source-map',
			output: localOutputPath
		};
	}

	if (env.production) {
		return webpackProductionConfig;
	}

	if (env.development) {
		return {
			...webpackConfig,
			plugins: [new CopyPlugin(mergedConfig)]
		};
	}

	throw new Error('missing or invalid --env= development/production/developmentWithWatch/productionWithWatch');
};