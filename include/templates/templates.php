<?php
/**
 * 
 * Templates
 * 
 * Includes
 * Objet custom post
 * Résumé auto
 * 
 */

/*================================
=            Includes            =
================================*/

/*----------  Communs  ----------*/

// images
include 'parts/template-part_images.php';
// navigation
include 'parts/template-part_navigation.php';
// Données structurées
include 'parts/template-part_schemas.php';
// liens réseaux sociaux & partage
include 'parts/template-part_social.php';
// layout global
include 'parts/template-part_main.php';
// contenu de l'entête (head)
include 'parts/template-part_head.php';
// recherche
include 'parts/template-part_search.php';
// Pages
include 'parts/template-part_page.php';
// Actualités
include 'parts/template-part_news.php';


/*----------  Spécifiques  ----------*/

// entête (header)
include 'template_header.php';
// Pied de page (footer)
include 'template_footer.php';
// single
include 'template_index.php';
// archive
include 'template_archive.php';
// recherche
include 'template_search.php';
// 404
include 'template_404.php';


/*=====  FIN Includes  =====*/

/*=========================================
=            Objet post custom            =
=========================================*/

add_action( 'wp', 'pc_register_custom_post_object', 10 );

	function pc_register_custom_post_object() {
        
		if ( is_singular() && class_exists( 'PC_Post' ) ) {
			global $post, $pc_post;
			$pc_post = new PC_Post( $post );
		}

	}


/*=====  FIN Objet post custom  =====*/

/*===================================
=            Résumé auto            =
===================================*/

add_filter( 'excerpt_more', '__return_empty_string' );

add_filter( 'excerpt_length', 'pc_edit_excerpt_length' );
	
	function pc_edit_excerpt_length() {
		
		return apply_filters( 'pc_filter_excerpt_length', 150 );
	
	}


/*=====  FIN Résumé auto  =====*/