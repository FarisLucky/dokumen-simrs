// vue.config.js
const NODE_ENV = 'PRODUCTION'
module.exports = {
    configureWebpack: {
        module: {
            rules: [
                {
                    test: /\.mjs$/,
                    include: /node_modules/,
                    type: "javascript/auto"
                }
            ]
        }
    },
    publicPath: NODE_ENV === 'PRODUCTION' ? '/dokumen-simrs/' : '/',
    devServer: {
        port: 8080
    },
}