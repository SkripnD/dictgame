var gulp    = require('gulp'),
    gutil   = require('gulp-util'),
    broSync = require('browser-sync'),
    argv    = require('minimist')(process.argv.slice(2));
    
/* Parameters
-------------------------------------------------- */

var params = {
    sync: argv.sync ? argv.sync : false,
    debug: argv.debug ? argv.debug : false
};
    
module.exports = params;

gutil.log('Sync:',  gutil.colors.cyan(params.sync));
gutil.log('Debug:', gutil.colors.cyan(params.debug));

/* Default tasks
-------------------------------------------------- */

var defaultTasks = [
    'admin-css', 'admin-js',
    'front-css', 'front-js',
];

if (params.sync) {
    defaultTasks.push('browser-sync');
}

gulp.task('default', defaultTasks, function() {

    if (params.sync) {
        gulp.watch('./web/public/compiled/*.*', broSync.reload);
    }

    gulp.watch('./web/public/plugins/**/*.css', ['front-css', 'admin-css']);
    gulp.watch('./web/public/plugins/**/*.js', ['front-js', 'admin-js']);
    
    gulp.watch('./web/public/css/admin/**/*.css', ['admin-css']);
    gulp.watch('./web/public/js/admin/**/*.js', ['admin-js']);
    
    gulp.watch('./web/public/css/front/**/*.css', ['front-css']);
    gulp.watch('./web/public/js/front/**/*.js', ['front-js']);
});