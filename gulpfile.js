var gulp = require('gulp');
var gutil = require('gulp-util');
var bower = require('bower');
var concat = require('gulp-concat');
var sass = require('gulp-sass');
var minifyCss = require('gulp-minify-css');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');

var publicPath = './app/public/';
var paths = {
  commoncss: [
    publicPath + 'scss/layout.scss',
    publicPath + 'scss/layout/**/*.scss'
  ],
  loadingcss: [
    publicPath + 'scss/loading.scss', 
    publicPath + 'scss/loading/**/*.scss'
  ],
  layoutjs: [
    publicPath + 'vendor/jquery/dist/jquery.min.js', 
    publicPath + 'js/layout.js',
    publicPath + 'js/script.js'
  ],
  contentjs: [
    publicPath + 'js/smooth_scroll.js',
    publicPath + 'vendor/waypoints/waypoints.min.js',
    publicPath + 'vendor/fotorama/fotorama.js',
    publicPath + 'vendor/bootstrap/dist/js/bootstrap.min.js',
    publicPath + 'js/content.js'
  ]
};

gulp.task('default', ['sass']);

gulp.task('sass', function(done) {
  gulp.src([publicPath + 'scss/layout.scss', publicPath + 'scss/loading.scss'])
    .pipe(sass({errLogToConsole: true}))
    .pipe(gulp.dest(publicPath + 'css/'))
    .pipe(minifyCss({keepSpecialComments: 0}))
    .pipe(rename({extname: '.min.css'}))
    .pipe(gulp.dest(publicPath + 'css/'))
    .on('end', done);
});

gulp.task('watch', ['sass'], function() {
  gulp.watch(paths.commoncss, ['sass']);
  gulp.watch(paths.loadingcss, ['sass']);
});

gulp.task('js', function() {
  gulp.src(paths.layoutjs)
    .pipe(concat('layout.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest(publicPath + 'js/dist/'));

  gulp.src(paths.contentjs)
    .pipe(concat('content.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest(publicPath + 'js/dist/'));
});
