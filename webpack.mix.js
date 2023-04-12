const mix = require('laravel-mix');
const lodash = require("lodash");
const WebpackRTLPlugin = require('webpack-rtl-plugin');
const folder = {
    src: "resources/", // source files
    dist: "public/", // build files
    // dist_assets: "public/assets/" //build backend assets files
    dist_assets: "public/assets/frontend/" //build frontend assets files
};

// mix.sass('resources/scss/bootstrap.scss', folder.dist_assets + "css").minify(folder.dist_assets + "css/bootstrap.css");
// mix.sass('resources/scss/icons.scss', folder.dist_assets + "css").options({ processCssUrls: false }).minify(folder.dist_assets + "css/icons.css");
mix.sass('resources/scss/app.scss', folder.dist_assets + "css").options({ processCssUrls: false }).minify(folder.dist_assets + "css/app.css");

// mix.sass('resources/scss/bootstrap-dark.scss', folder.dist_assets + "css").minify(folder.dist_assets + "css/bootstrap-dark.css");
// mix.sass('resources/scss/app-dark.scss', folder.dist_assets + "css").options({ processCssUrls: false }).minify(folder.dist_assets + "css/app-dark.css");

mix.webpackConfig({
    plugins: [
        new WebpackRTLPlugin()
    ]
});


// mix.combine('resources/css/main.css', folder.dist_assets + "css/main.css");
// mix.combine('resources/js/main.js', folder.dist_assets + "js/main.js");
// mix.combine('resources/js/app.js', folder.dist_assets + "js/app.min.js");
