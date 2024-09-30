module.exports = {
    entry: "./assets/jsx/tst-gutenberg.js",
    output: {
        path: __dirname + '/dist/js',
        filename: "tst-gutenberg.js"
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx)$/,
                use: {
                    loader: "babel-loader",
                    options: {
                        presets: ['react']
                    }
                },
                exclude: /(node_modules|bower_components)/
            }
        ]
    }
};
