<?php
/**
 * 
 * Templates
 * 
 * Includes
 * Query vars
 * Objet custom post
 * Résumé auto
 * 
 */

/*================================
=            Includes            =
================================*/

/*----------  Communs  ----------*/

// images
include 'parts/tpl-part_images.php';
// navigation
include 'parts/tpl-part_navigation.php';
// Données structurées
include 'parts/tpl-part_schemas.php';
// liens réseaux sociaux & partage
include 'parts/tpl-part_social.php';
// layout global
include 'parts/tpl-part_main.php';
// contenu de l'entête (head)
include 'parts/tpl-part_head.php';
// recherche
include 'parts/tpl-part_search.php';
// Pages
include 'parts/tpl-part_page.php';
// Actualités
if ( get_option('options_news_enabled') ) { include 'parts/tpl-part_news.php'; }
// Événements
if ( get_option('options_events_enabled') ) { include 'parts/tpl-part_events.php'; }


/*----------  Spécifiques  ----------*/

// entête (header)
include 'tpl_header.php';
// Pied de page (footer)
include 'tpl_footer.php';
// single
include 'tpl_index.php';
// archive
include 'tpl_archive.php';
// recherche
include 'tpl_search.php';
// 404
include 'tpl_404.php';


/*=====  FIN Includes  =====*/

/*==================================
=            Query vars            =
==================================*/

add_filter( 'query_vars', 'pc_query_vars' );
	
	function pc_query_vars( $vars ) {
		$vars[] = 'archive';
		$vars[] = 'category';
		return $vars;
	}


/*=====  FIN Query vars  =====*/

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