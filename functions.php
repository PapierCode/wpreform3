<?php

define( 'NEWS_POST_SLUG', 'newspost' );
define( 'NEWS_TAX_SLUG', 'newstax' );

include 'include/tools.php'; // fonction utiles

include 'include/register.php'; // settings, custom posts & taxonomies

include 'include/editors/block-editor/block-editor.php'; // bloc editor
include 'include/editors/acf-tinymce/acf-tinymce.php'; // tinymce ACF

include 'include/classes/class-pc-walker-nav.php'; // structure de menu
include 'include/classes/class-pc-post.php'; // custom objet post

include 'include/admin/admin.php'; // custom admin

include 'include/templates/templates.php'; // modèles & fonctions

add_action( 'wp', 'pc_wp', 10 );

	function pc_wp() {

		if ( is_singular() && class_exists( 'PC_Post' ) ) {
			global $post, $pc_post;
			$pc_post = new PC_Post( $post );
		}

	}
	

// TODO

$texts_lengths = array(
	'excerpt' => 100, // mots
	'resum-title' => 40, // signes
	'resum-desc' => 150, // signes
	'seo-title' => 70, // signes
	'seo-desc' => 200 // signes
);
$texts_lengths = apply_filters( 'pc_filter_texts_lengths', $texts_lengths );

add_filter( 'excerpt_length', function() use ( $texts_lengths ) { return $texts_lengths['excerpt']; }, 999 );
add_filter( 'excerpt_more', function() { return ''; }, 999 );
