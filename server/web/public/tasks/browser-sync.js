var gulp    = require('gulp'),
    broSync = require('browser-sync');

gulp.task('browser-sync', function() {
    broSync({
        proxy: "bootstrap",
        notify: false,
    });
});