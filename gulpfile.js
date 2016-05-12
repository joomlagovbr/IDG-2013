var gulp = require('gulp');
var less = require('gulp-less');
var watchLess = require('gulp-watch-less');
var path = require('path');
var cleanCSS = require('gulp-clean-css');

/**
 * Processa os arquivos LESS e comprime o CSS
 */
gulp.task('less', function () {
  return gulp.src(['./templates/padraogoverno01/less/*.less', '!./templates/padraogoverno01/less/_*.less'])
    .pipe(less())
    .pipe(cleanCSS({compatibility: 'ie7'}))
    .pipe(gulp.dest('./templates/padraogoverno01/css'));
});

/**
 * Monitora e processa os arquivos LESS quando alterados sem comprimir.
 * Usado para desenvolvimento
 */
gulp.task('watch', function () {
    return gulp.src(['./templates/padraogoverno01/less/*.less', '!./templates/padraogoverno01/less/_*.less'])
        .pipe(watchLess(['./templates/padraogoverno01/less/*.less', '!./templates/padraogoverno01/less/_*.less']))
        .pipe(less())
        .pipe(gulp.dest('./templates/padraogoverno01/css'));
});
