var gulp = require('gulp');
var gutil = require('gulp-util');
var bower = require('bower');
var concat = require('gulp-concat');
var sass = require('gulp-sass');
var minifyCss = require('gulp-minify-css');
var rename = require('gulp-rename');

var publicPath = './app/public/';
var paths = {
  main: [publicPath + 'scss/main.scss', publicPath + 'scss/main/**/*.scss'],
  loading: [publicPath + 'scss/loading.scss', publicPath + 'scss/loading/**/*.scss']
};

gulp.task('default', ['sass']);

gulp.task('sass', function(done) {
  gulp.src([publicPath + 'scss/main.scss', publicPath + 'scss/loading.scss'])
    .pipe(sass({errLogToConsole: true}))
    .pipe(gulp.dest(publicPath + 'css/'))
    .pipe(minifyCss({keepSpecialComments: 0}))
    .pipe(rename({extname: '.min.css'}))
    .pipe(gulp.dest(publicPath + 'css/'))
    .on('end', done);
});

gulp.task('watch', ['sass'], function() {
  gulp.watch(paths.main, ['sass']);
  gulp.watch(paths.loading, ['sass']);
});
