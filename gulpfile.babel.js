'use strict';

import browserSync from 'browser-sync';
import del from 'del';
import gulp from 'gulp';
import cleanCSS from 'gulp-clean-css';
import less from 'gulp-less';
import rename from 'gulp-rename';
import uglify from 'gulp-uglify';
import yargs from 'yargs';

const argv = yargs.option('host', {
  default: 'http://localhost'
}).argv;

const paths = {
  php: './templates/padraogoverno01/**/*.php',
  styles: {
    src: './templates/padraogoverno01/less/*.less',
    dest: './templates/padraogoverno01/css/'
  },
  scripts: {
    src: [
      './templates/padraogoverno01/js/*.js',
      '!./templates/padraogoverno01/js/*.min.js'
    ],
    dest: './templates/padraogoverno01/js/'
  }
};

const reload = (done) => {
  browserSync.reload();
  done();
};

const serve = (done) => {
  browserSync.init({
    proxy: argv.host,
    open: false
  });

  done();
};

/**
 * Limpa a pasta de destino dos css
 */
export const clean = () =>
  del([paths.styles.dest, '!./templates/padraogoverno01/css/custom.css']);

/**
 * Processa os arquivos LESS e comprime o CSS
 */
export const styles = () => {
  return gulp
    .src([paths.styles.src, '!./templates/padraogoverno01/less/_*.less'])
    .pipe(less())
    .pipe(cleanCSS({ compatibility: 'ie7' }))
    .pipe(gulp.dest(paths.styles.dest));
};

/**
 * Minifica arquivos javascripts
 */
export const scripts = () => {
  return gulp
    .src(paths.scripts.src)
    .pipe(rename({ suffix: '.min' }))
    .pipe(uglify())
    .pipe(gulp.dest(paths.scripts.dest));
};

/**
 * Monitora e processa os arquivos quando alterados.
 * Usado para desenvolvimento
 */
const watchFiles = () => {
  const watchOptions = { usePolling: true };

  gulp.watch(paths.php, watchOptions, reload);
  gulp.watch(paths.styles.src, watchOptions, gulp.series(styles, reload));
  gulp.watch(paths.scripts.src, watchOptions, gulp.series(scripts, reload));
};

export const dev = gulp.series(
  clean,
  gulp.parallel(styles, scripts),
  serve,
  watchFiles
);
export const build = gulp.series(clean, gulp.parallel(styles, scripts));
export default dev;
