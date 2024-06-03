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


/*----------  Fonctions  ----------*/
	
function cssAdmin() {
    
    return src( ['include/admin/css/use.scss'] )
        .pipe(sass({ precision: 3 }))
        .pipe(postcss( postCssPlugins ))
		.pipe(rename( 'wpreform-admin.css' ))
        .pipe(dest( 'include/admin/css/' ));

}


/*=====  FIN Tâche CSS  ======*/

/*================================
=            Tâche JS            =
================================*/

js_src = [
	'scripts/include/jquery-gallery.js',
	'scripts/include/nav.js',
	'scripts/pc-preform.js'
];

js_src_all = [
	'scripts/include/jquery-3.6.0.min.js'
].concat(js_src);


/*----------  Fonctions  ----------*/

function js_hint() {

	return src( js_src )
		.pipe(jshint( { esnext:true, browser:true } ))
        .pipe(jshint.reporter( 'default' ));

}

function js_jquery() {

    return src( js_src_all )
        .pipe(concat( 'pc-preform-jquery.min.js' ))
        .pipe(terser())
        .pipe(dest( 'scripts/' ));

}

function js() {

    return src( js_src )
        .pipe(concat( 'pc-preform.min.js' ))
        .pipe(terser())
        .pipe(dest( 'scripts/' ));

}


/*=====  FIN Tâche JS  =====*/

/*==================================
=            Monitoring            =
==================================*/

exports.watch = function() {

    watch( 'include/admin/css/**/*.scss', series( cssAdmin ) )
	watch( [ 'scripts/**/*.js', '!scripts/pc-preform.min.js', '!scripts/pc-preform-jquery.min.js' ], series( js_hint, js_jquery, js )  )
};


/*=====  FIN Monitoring  ======*/