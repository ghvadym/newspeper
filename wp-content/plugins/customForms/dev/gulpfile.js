var gulp = require('gulp');
var sass = require('gulp-sass');
var cleanCSS = require('gulp-clean-css');
var sourcemaps = require('gulp-sourcemaps');

gulp.task('watch', function () {
    gulp.watch('../assets/scss/**/*.scss', function () {
        return gulp.src('../assets/scss/**/*.scss')
            .pipe(sourcemaps.init())
            .pipe(sass().on('error', sass.logError))
            .pipe(cleanCSS())
            .pipe(sourcemaps.write('.'))
            .pipe(gulp.dest('../dest/css/'));
    } );
});
