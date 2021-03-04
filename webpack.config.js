const path = require('path');

const Env = (function () {
    const PATH_DEFAULT = 'src/assets/utils/';
    /**      
     * @param {string} param      
     */
    function Env(param) {
        this.mode = param.mode || 'development';
    }

    Env.prototype.isProd = function () {
        return this.mode === 'production';
    };

    Env.prototype.isDev = function () {
        return this.mode === 'development';
    };

    /**    
     * @param {string} ext - расширение
     */
    Env.prototype.getFileName = function (ext) {
        const min = '.min';
        const fileName = `[name]${min}.${ext}`;
        return fileName;
    };

    Env.prototype.sourceMap = function () {
        return false;//this.isProd() ? false : 'source-map';
    };

    Env.path = function (dir = '', pathDefault = true) {
        if (pathDefault) {
            dir = PATH_DEFAULT + dir;
        }
        return path.resolve(__dirname, dir);
    };

    return Env;
})();

const DefaultConfig = {
    resolve(extensions) {
        return {
            modules: [
                Env.path(),
                'node_modules'
            ],
            extensions
        }
    },
    config(env, config) {
        return {
            //Точка входа из папки src
            context: Env.path(),
            mode: env.mode,
            performance: {
                hints: false,
            },
            devtool: env.sourceMap(),
            optimization: {
                minimize: env.isProd(),
            },
            ...config
        }
    }
}

const CSSConfig = (env) => {
    const loaders = [
        {
            loader: 'file-loader',
            options: {
                name: env.getFileName('css'),
            },
        },
        {
            loader: 'extract-loader',
        },
        {
            loader: 'css-loader',
            options: {
                sourceMap: false,
            },
        },
        {
            loader: 'postcss-loader',
            options: {
                postcssOptions: {
                    plugins: [                        
                        require('autoprefixer'),
                    ],
                },
            },
        },
        {
            loader: 'sass-loader',
            options: {
                webpackImporter: false,
                sassOptions: {
                    includePaths: ['./node_modules'],
                },
            },
        },
    ];

    return DefaultConfig.config(env, {
        name: 'css-template',
        entry: {
            'utils': './utils.scss'
        },        
        output: {
            path: Env.path('dist'),
            filename: env.getFileName('css.js'),
        },
        resolve: DefaultConfig.resolve(['.scss']),
        module: {
            rules: [
                {
                    test: /\.s[ac]ss$/i,
                    use: loaders,
                },
            ],
        },
    });
}

const JSConfig = (env) => {

    let output = {
        path: Env.path('dist'),
        filename: env.getFileName('js'),
        library: ['app', '[name]'],
        libraryTarget: 'umd',
        clean: true
    };

    return DefaultConfig.config(env, {
        name: 'js-template',
        entry: {
            'utils': './utils.js'
        },
        output: output,
        resolve: DefaultConfig.resolve(['.js']),
        module: {
            rules: [
                // {
                //     test: /\.ts$/,
                //     exclude: /node_modules/,
                //     use: [
                //         // { loader: 'babel-loader', options: { cacheDirectory: true } },
                //         {
                //             loader: 'ts-loader',
                //             options: {
                //                 configFile: 'tsconfig.json'
                //             }
                //         }
                //     ]
                // },
                {
                    test: /\.m?js$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            cacheDirectory: true,
                            presets: [
                                ['@babel/preset-env', { targets: 'defaults' }],
                            ],
                        },
                    },
                },
            ]
        },
    });
}

module.exports = ({ }, param) => {
    const env = new Env(param);

    const cssConfig = CSSConfig(env);    
    const jsConfig = JSConfig(env);

    return [
        cssConfig,
        jsConfig
    ];
};