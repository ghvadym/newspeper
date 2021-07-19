"use strict";

var gulp = require("gulp");
var plumber = require("gulp-plumber");
var sass = require("gulp-sass");
var server = require("browser-sync").create();
var autoprefixer = require('gulp-autoprefixer');
const px2rem = require('gulp-px2rem');


gulp.task("css", function () {
    return gulp.src("scss/app.scss")
        .pipe(plumber())
        .pipe(sass())
        .pipe(autoprefixer({
            overrideBrowserslist: ['last 2 versions'],
            cascade             : false
        }))
        .pipe(gulp.dest("../assets/css/"))
        .pipe(server.stream());
});


gulp.task("refresh", function (done) {
    server.reload();
    done();
});


gulp.task("server", function () {
    server.init({
        proxy         : 'newspaper.loc/',
        open          : true,
        cors          : true,
        ui            : false,
        snippetOptions: {
            rule: {
                match: /$/i,
                fn   : function (snippet, match) {
                    return snippet + match;
                }
            }
        }
    });

    gulp.watch("scss/**/*.scss", gulp.series("css", "refresh"));
});


gulp.task("build", gulp.series("css"));
gulp.task("start", gulp.series("build", "server"));
