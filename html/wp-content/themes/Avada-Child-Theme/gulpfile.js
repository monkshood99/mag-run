// v 1.4.8
var gulp 				= require('gulp');
	_ 						= require('lodash' ),
	concat 				= require('gulp-concat'),
	streamqueue 	= require('streamqueue'),
	sass 					= require('gulp-sass'),
// 	del 					= require('del'),
	path 					= require('path'),
	plumber 			= require('gulp-plumber'),
	cleanCSS 			= require('gulp-clean-css')
	minify 				= require('gulp-minify')
	sourcemaps 		= require('gulp-sourcemaps'),
	autoprefixer 	= require('gulp-autoprefixer'),
	browserSync 	= require( 'browser-sync').create(),
	// settings 			= require( './settings.json'),
	fs= require ( 'fs'),
	path  = require( 'path' ),
	syncing 			= false;

	
	

//var document_root = [ '\/Applications\/MAMP\/htdocs/' ];
var mx_log = function ( $line ){
	$line = $line || false;
	if(!$line)  return false;
	console.log( "\n################")
	_.each( $line , function( $l ){
		if( typeof( $l ) === 'object' ){
			_.each( $l , function( $l_ , $k ){
				console.log( "######## " +  $k + ': '  + $l_ );
			})
		}else{
			console.log( "######## " + $l );
		}
	})	
	console.log( "\n################")
}








//############################//
// deploy  
// Run this before deployment to development or production 
//############################//

gulp.task ( 'vendor', function(){
	mx_log( [ 'Compiling Scripts from Settings.json'] ); 
	var scripts =  settings.scripts;
	var compiled = {};
	// get the scripts to compile to 
	_.each( scripts, function( script ){
		if( script.compiled == true ){
			compiled[script.location ] = {handle : script.handle, scripts : []};
		}
	})	

	// 	get the scripts that should prepend in order to the compiled scripts
	_.each( scripts, function( script ){
		if( typeof( script.active ) == 'undefined' ) script.active = true;
		if( script.prepend_to && script.active ){
			compiled[script.prepend_to].scripts.push( script.location )
		}
	})	
	// concat and minify each compile package
	// Dang this is SOOO 2014 .... 
	_.each( compiled, function( compile ){
			mx_log( ['Compiling ' + compile.handle , compile.scripts   ])
			var $return = gulp.src(compile.scripts)
				.on( 'error',  function ( $error ) {  mx_log( [$error]) })
				.on( 'end',  function () { 
					mx_log(['Compiled -> ' + compile.handle + '.js' , 'TO -> ' + './assets/js/'  + compile.handle + '.js' ])
				} )
				.pipe(plumber(function( $e ){ mx_log( [$e]) }))
				//.pipe(sourcemaps.init())
				.pipe(concat(compile.handle + '.js'))
				.pipe( minify({ ext:{ min:'.js' }, mangle:false, noSource : true }))
				//.pipe(sourcemaps.write('./'))
				.pipe(gulp.dest('./assets/js' ))
			return $return;
  });
});





//############################//
// styles 
//############################//


gulp.task ( 'styles', function(){
  mx_log( ['Building Sass']);
  return gulp
  	.src('./assets/scss/**/*.scss')
    .pipe(sourcemaps.init())
    .pipe(sass({
      outputStyle: 'compressed',
      includePaths: []
    }).on('error', sass.logError))
		.pipe(autoprefixer({ browsers: ['last 5 versions'], cascade: false }))
		.pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./assets/css'))
	.pipe(browserSync.stream());
});
	
	
	

gulp.task ( 'assets', function(){
  mx_log( ['Transferring assets ']);
  return gulp.src('./src/assets/**/*')
    .pipe(gulp.dest('./build/assets'))
   }
);
	

	
	
	
	
//############################//
// serve  + watch for changes
//############################//

var serve =  function(){
	browserSync.init({
		server : { baseDir: './build/' }	
	});
  gulp.watch( ["build/index.html","build/js*.js", "assets/**/*" ]).on('change', browserSync.reload);
};





/** 
*	delete the build directory
**/
gulp.task('clean', function (cb) {
  return del(['build'], cb);
});


/** 
*	Watch for changes and process
**/
gulp.task('default',  function () {
	browserSync.init({ proxy: 'https://mag-run.local' });
	syncing = true;
	mx_log( ['Running MX Site with Live Reload Server']);
//   gulp.watch('./src/js/**/*.js', ['deploy']);
  gulp.watch('./assets/scss/**/*.scss', ['styles']);
//   gulp.watch(['./src/files/**/*','./src/partials/**/*', './src/layouts/**/*' ]);
//   gulp.watch(['./src/assets/**/*'], ['assets']);
//   serve();
});



/** 
*	delete everythign and rebuild
**/
gulp.task('build', [ 'styles', 'vendor']);



