var {src, dest, watch, series} = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');

function scss()
{
    return src('../includes/scss/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
            cascade: false
        }))
        .pipe(dest('../includes/css/'));
}

function watcher()
{
    watch('../includes/scss/**/*.scss', scss);
}

exports.default = series(scss, watcher);
