const path = require('path');
const fs  = require('fs');
const webpack = require( 'webpack' );
const BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' );
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );
const CleanWebpackPlugin = require( 'clean-webpack-plugin' );
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );
const lessToJs = require('less-vars-to-js');
// const themeVariables = lessToJs(fs.readFileSync(path.join(__dirname, './ant-theme-vars.less'), 'utf8'));
// const config = require( './config.json' );

const webpackConfig = {
	entry: [
		'whatwg-fetch',
		'./src/socket.js'
	],
	output: {
		filename: 'socket.js',
		path: path.resolve( __dirname, 'dist' ),
		publicPath: '/'
	},
	module: {
		rules: [
			{
				test: /\.(js|jsx)$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['es2017', 'react'],
						plugins: [
							['import', { libraryName: "antd", style: true }]
						]
					}
				}
			},
			{
				test: /\.css$/i,
				use: ExtractTextPlugin.extract( {
					fallback: 'style-loader',
					use: 'css-loader'
				} ),
			},
			{
				test: /\.scss$/i,
				exclude: /node_modules/,
				use: ExtractTextPlugin.extract( {
					use: [ 'css-loader', 'sass-loader' ]
				} )
			},
			{
				test: /\.(png|svg|jpg|gif)$/,
				use: [
					'file-loader'
				]
			}
			// {
			// 	test: /\.less$/,
			// 	use: [
			// 		{loader: "style-loader"},
			// 		{loader: "css-loader"},
			// 		{loader: "less-loader",
			// 			options: {
			// 				modifyVars: themeVariables,
			// 				root: path.resolve(__dirname, './')
			// 			}
			// 		}
			// 	]
			// }
		]
	},
	devtool: 'source-map',
	plugins: [
		new ExtractTextPlugin( {
			disable: false,
			filename: 'style.bundle.css',
			allChunks: true
		} )
	]
};

if ( process.env.NODE_ENV === 'production' ) {

	const buildFolder = path.resolve( __dirname, 'dist' );

	webpackConfig.plugins.push(
		new webpack.DefinePlugin({
			'process.env.NODE_ENV': JSON.stringify('production')
		})
	);

	webpackConfig.plugins.push( new webpack.optimize.UglifyJsPlugin( {
		'mangle': {
			'screw_ie8': true
		},
		'compress': {
			'screw_ie8': true,
			'warnings': false
		},
		'sourceMap': false
	} ) );

	webpackConfig.plugins.push(
		new CleanWebpackPlugin( [ buildFolder ] )
	);

	webpackConfig.plugins.push(
		new CopyWebpackPlugin( [
			{ from: path.resolve( __dirname, 'server' ) + '/**', to: buildFolder },
			{ from: path.resolve( __dirname, '*.php' ), to: buildFolder }
		], {

			// By default, we only copy modified files during
			// a watch or webpack-dev-server build. Setting this
			// to `true` copies all files.
			copyUnmodified: true
		} )
	);


	webpackConfig.output.path = buildFolder + '/dist';
}

module.exports = webpackConfig;