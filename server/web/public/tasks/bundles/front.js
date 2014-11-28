var gulp   = require('gulp'),
    bundle = require('../bundle.js'),
    // params
    destFolder = './web/public/compiled',
    destFile   = 'front';

gulp.task('front-css', function() {
});

gulp.task('front-js', function() {
    bundle('js', destFile + '.js', destFolder, [
        './web/public/plugins/jquery/jquery-1.10.2.min.js',
        './web/public/js/front/global.js',
    ]);
});