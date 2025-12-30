/**
*
* Gulp pour Papier Codé
*
** dépendance : package.json
** installation : commande "npm install"
*
**/


/*======================================
=            Initialisation            =
======================================*/

// Chargement des plugins

const { src, dest, watch, series } = require( 'gulp' ); // base

const sass          = require( 'gulp-sass' )( require('sass') ); // scss to css
const postcss 		= require( 'gulp-postcss' ); // package
const cssnano 		= require( 'cssnano' ); // minification css
const autoprefixer 	= require( 'autoprefixer' ); // ajout des préfixes
const mqcombine 	= require( 'postcss-sort-media-queries' ); // factorisation des medias queries
const inlinesvg		= require( 'postcss-inline-svg' ); // svg to data:URI
const rename		= require( 'gulp-rename' ); // renommage fichier

const jshint		= require( 'gulp-jshint' ); // recherche d'erreurs js
const concat		= require( 'gulp-concat' ); // empile plusieurs fichiers js en un seul
const terser		= require( 'gulp-terser' ); // minification js  

    
/*=====  FIN Initialisation  ======*/

/*=================================
=            Tâche CSS            =
=================================*/

sass.compiler = require('sass');

var postCssPlugins = [
	inlinesvg(),
	autoprefixer({ grid: 'false', flexbox: 'false' }),
	mqcombine(),
	cssnano({ preset: ['default', { calc: false }] })
];
	
function cssAdmin() {
    
    return src( ['include/admin/css/use.scss'] )
        .pipe(sass({ precision: 3 }).on('error', sass.logError))
        .pipe(postcss( postCssPlugins ))
		.pipe(rename( 'wpreform-admin.css' ))
        .pipe(dest( 'include/admin/css/' ));

}


/*=====  FIN Tâche CSS  ======*/

/*================================
=            Tâche JS            =
================================*/

function js() {

    return src( [ 'scripts/wpreform.js' ] )
        .pipe(jshint( { esnext:true, browser:true } ))
        .pipe(jshint.reporter( 'default' ))
        .pipe(concat( 'wpreform.min.js' ))
        .pipe(terser())
        .pipe(dest( 'scripts/' ));

}

function jsOldNav() {

    return src( [ 'scripts/include/oldnav.js' ] )
        .pipe(jshint( { esnext:true, browser:true } ))
        .pipe(jshint.reporter( 'default' ))
        .pipe(concat( 'oldnav.min.js' ))
        .pipe(terser())
        .pipe(dest( 'scripts/include/' ));

}


/*=====  FIN Tâche JS  =====*/

/*==================================
=            Monitoring            =
==================================*/

exports.watch = function() {

    watch( 'include/admin/css/**/*.scss', series( cssAdmin ) )
	watch( [ 'scripts/**/*.js', '!scripts/wpreform.min.js', '!scripts/include/oldnav.js', '!scripts/include/oldnav.min.js' ], series( js )  )
	watch( 'scripts/include/oldnav.js', series( jsOldNav )  )

};


/*=====  FIN Monitoring  ======*/