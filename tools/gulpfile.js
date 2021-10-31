
const { src, dest, watch, series, parallel } = require('gulp');
const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass');
var less = require('gulp-less');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const imagemin = require('gulp-imagemin');
const cache = require('gulp-cache');
const livereload = require('gulp-livereload');

// paths
const files = { 
	scssPath: '../src/scss/**/*.scss',
	lessPath: ['../src/less/style.less' , '../src/less/admin.less', '../src/less/editor-style.less', '../src/less/login.less'],
	lessWatchPath: '../src/less/**/*.less',
	jsPath: '../src/js/**/*.js',
	imgPath: '../src/img/**/*',
	output: '../assets/'
}

// timing
const cssPollTime = 500;
const jsPollTime = 1000;

// functional tasks
function imgTask() {
	return gulp .src("./img/*")
	.pipe(imagemin())
	.pipe(gulp.dest(files.output));
}

function scssTask(){
	return src(files.scssPath)
		.pipe(sourcemaps.init()) 
		.pipe(sass()) 
		// .pipe(postcss([ autoprefixer(), cssnano() ])) 
		.pipe(sourcemaps.write('.')) 
		.pipe(dest(files.output) )
		.pipe(livereload());
}

function lessTask(){
	return src(files.lessPath)
		.pipe(sourcemaps.init())
		.pipe(less())
		// .pipe(postcss([ autoprefixer(), cssnano() ])) 
		.pipe(sourcemaps.write('.'))
		.pipe(dest(files.output) ); 
}

function jsTask(){
	return src([
		files.jsPath
			,'!' + 'src/js/jquery.min.js', // to exclude any specific files
		])
		.pipe(concat('scripts.js'))
		.pipe(uglify())
		.pipe(dest(files.output) );
}

function imgTask(){
	return src('../src/img/**/*')
		.pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
		.pipe(dest(files.output) );
}


// watch tasks 
function watchImg(){
	watch([files.imgPath],
		{interval: jsPollTime, usePolling: true}, //Makes docker work
		imgTask
	);
}

function watchTask(){
	watch([files.scssPath, files.jsPath],
		{interval: 2000, usePolling: true}, //Makes docker work
		series(
			parallel(scssTask, jsTask)
		)
	);
}

function watchAllScssTask(){
	watch([files.scssPath, files.jsPath, files.imgPath],
		{interval: 2000, usePolling: true}, //Makes docker work
		series(
			parallel(scssTask, jsTask, imgTask)
		)
	);
}

function watchAllLessTask(){
	watch([files.lessWatchPath, files.jsPath, files.imgPath],
		{interval: 2000, usePolling: true}, //Makes docker work
		series(
			parallel(lessTask, jsTask, imgTask)
		)
	);
}

function watchScss(){
	livereload.listen();
	watch([files.scssPath],
		{interval: cssPollTime, usePolling: true}, //Makes docker work
		scssTask
	);
}

function watchLess(){
	watch([files.lessWatchPath],
		{interval: cssPollTime, usePolling: true}, //Makes docker work
		lessTask
	);
}

function watchJs(){
	watch([files.jsPath],
		{interval: jsPollTime, usePolling: true}, //Makes docker work
		jsTask
	);
}


// action commands
// gulp - default watches only js and scss
exports.default = series(
	parallel(scssTask, jsTask), 
	watchTask
);

// gulp watch all - this watchs for js, scss, and img changes
exports.watchAllScss = series(
	parallel(scssTask, jsTask, imgTask), 
	watchAllScssTask
);

// gulp watch all - this watchs for js, less, and img changes
exports.watchAllLess = series(
	parallel(lessTask, jsTask, imgTask), 
	watchAllLessTask
);

// gulp watchCss - watches only scss
exports.watchScss = series(
	parallel(scssTask), 
	watchScss
);

// gulp watchCss - watches only scss
exports.watchLess = series(
	parallel(lessTask), 
	watchLess
);

// gulp watchJs - watches only js
exports.watchJs = series(
	parallel(jsTask), 
	watchJs
);


// gulp watchImg - watches only js
exports.watchImg = series(
	parallel(imgTask), 
	watchImg
);

// single run tasks, gulp css, gulp js, gulp img
exports.scss = scssTask;
exports.less = lessTask;
exports.js = jsTask;
exports.img = imgTask;