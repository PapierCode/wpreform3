<?php
/**
 * 
 * Communs templates : Wysiwyg WP
 * 
 ** Excerpt
 * 
 */


/*=====  FIN Container  =====*/

/*===============================
=            Excerpt            =
===============================*/

add_filter( 'excerpt_length', function() use ( $texts_lengths ) { return $texts_lengths['excerpt']; }, 999 );
add_filter( 'excerpt_more', function() { return ''; }, 999 );


/*=====  FIN Excerpt  =====*/
