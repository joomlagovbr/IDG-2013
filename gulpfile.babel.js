import gulp from 'gulp';
import less from 'gulp-less';
import cleanCSS from 'gulp-clean-css';
import del from 'del';

const paths = {
	styles: {
		src: ['./templates/padraogoverno01/less/*.less', '!./templates/padraogoverno01/less/_*.less'],
		dest: './templates/padraogoverno01/css/'
	}
};

/**
 * Limpa a pasta de destino dos css
 */
export const clean = () => del([paths.styles.dest, '!./templates/padraogoverno01/css/custom.css']);

/**
 * Processa os arquivos LESS e comprime o CSS
 */
export const styles = () => {
	return gulp
		.src(paths.styles.src)
		.pipe(less())
		.pipe(cleanCSS({ compatibility: 'ie7' }))
		.pipe(gulp.dest(paths.styles.dest));
};

/**
 * Monitora e processa os arquivos LESS quando alterados sem comprimir.
 * Usado para desenvolvimento
 */
const watchFiles = () => {
	gulp.watch(paths.styles.src, styles);
};

export const dev = gulp.series(clean, styles, watchFiles);
