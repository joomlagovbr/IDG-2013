var gulp = require('gulp');
var less = require('gulp-less');
var watchLess = require('gulp-watch-less');
var path = require('path');
var cleanCSS = require('gulp-clean-css');
var clean = require('gulp-clean');

/**
 * Limpa a pasta de destino dos css
 */
gulp.task('less-clean', function () {
	return gulp.src(['./templates/padraogoverno01/css/*.css', '!./templates/padraogoverno01/css/custom.css'], {read: false})
		.pipe(clean());
});

/**
 * Processa os arquivos LESS e comprime o CSS
 */
gulp.task('less', ['less-clean'], function () {
	return gulp.src(['./templates/padraogoverno01/less/*.less', '!./templates/padraogoverno01/less/_*.less'])
		.pipe(less())
		.pipe(cleanCSS({compatibility: 'ie7'}))
		.pipe(gulp.dest('./templates/padraogoverno01/css'));
});

/**
 * Monitora e processa os arquivos LESS quando alterados sem comprimir.
 * Usado para desenvolvimento
 */
gulp.task('watch', ['less-clean'], function () {
	return gulp.src(['./templates/padraogoverno01/less/*.less', '!./templates/padraogoverno01/less/_*.less'])
		.pipe(watchLess(['./templates/padraogoverno01/less/*.less', '!./templates/padraogoverno01/less/_*.less']))
		.pipe(less())
		.pipe(gulp.dest('./templates/padraogoverno01/css'));
});
