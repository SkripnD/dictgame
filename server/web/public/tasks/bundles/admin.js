var gulp   = require('gulp'),
    bundle = require('../bundle.js'),
    // params
    destFolder = './web/public/compiled',
    destFile   = 'admin';

gulp.task('admin-css', function() {
    bundle('css', destFile + '.css', destFolder, [
        './web/public/plugins/bootstrap/bootstrap.min.css',
        './web/public/plugins/bootstrap/bootstrap-datetimepicker.min.css',
        './web/public/plugins/nprogress/nprogress.css',
        './web/public/css/admin/style.css',
    ]);
});

gulp.task('admin-js', function() {
    bundle('js', destFile + '.js', destFolder, [
        './web/public/plugins/underscore/underscore-min.js',
        './web/public/plugins/jquery/jquery-1.10.2.min.js',
        './web/public/plugins/jquery-ui/jquery.ui-1.9.2.min.js',
        './web/public/plugins/nprogress/nprogress.js',
        './web/public/plugins/bootstrap/bootstrap.min.js',
        './web/public/plugins/bootstrap/bootstrap-datetimepicker.min.js',
        './web/public/plugins/jquery-form/jquery.form.min.js',
        './web/public/js/admin/forms.js',
        './web/public/js/admin/global.js',
        './web/public/js/admin/roles.js',
    ]);
});