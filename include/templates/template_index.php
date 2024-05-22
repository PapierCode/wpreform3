<?php 
/**
 * 
 * Template : page
 * 
 ** Hooks
 ** Titre
 ** Fil d'ariane
 ** Wysiwyg
 ** Sous-pages & contenu spécifique
 ** Sous-pages, lien retour
 ** Données structurées
 * 
 */


/*=============================
=            Hooks            =
=============================*/

// main start
add_action( 'pc_action_index_main_start', 'pc_display_main_start', 10 ); // template-part_layout.php

	// header
	add_action( 'pc_action_index_main_header', 'pc_display_main_header_start', 10 ); // template-part_layout.php
		add_action( 'pc_action_index_main_header', 'pc_display_breadcrumb', 20 ); // breadcrumb
		add_action( 'pc_action_index_main_header', 'pc_display_main_title', 30 ); // titre
	add_action( 'pc_action_index_main_header', 'pc_display_main_header_end', 100 ); // template-part_layout.php

	// content
	add_action( 'pc_action_index_main_content', 'pc_display_main_content_start', 10 ); // template-part_layout.php
		add_action( 'pc_action_index_main_content', 'pc_display_page_wysiwyg', 30 ); // éditeur
	add_action( 'pc_action_index_main_content', 'pc_display_main_content_end', 100 ); // template-part_layout.php

	// footer
	add_action( 'pc_action_index_main_footer', 'pc_display_main_footer_start', 10 ); // template-part_layout.php
		add_action( 'pc_action_index_main_footer', 'pc_display_sub_page_backlink', 20 ); // lien retour
		// add_action( 'pc_action_index_main_footer', 'pc_display_share_links', 90 ); // liens de partage
	add_action( 'pc_action_index_main_footer', 'pc_display_main_footer_end', 100 ); // template-part_layout.php

// main end
add_action( 'pc_action_index_main_end', 'pc_display_main_end', 10 ); // template-part_layout.php


/*=====  FIN Hooks  =====*/


/*=====  FIN Fil d'ariane  =====*/

/*===============================
=            Wysiwyg            =
===============================*/

function pc_display_page_wysiwyg( $pc_post ) {

	// schéma Article
	// if ( apply_filters( 'pc_filter_page_schema_article_display', true, $pc_post ) ) {
	// 	echo '<script type="application/ld+json">';
	// 		echo json_encode( $pc_post->get_schema_article(), JSON_UNESCAPED_SLASHES );
	// 	echo '</script>';
	// }

	// contenu
	if ( apply_filters( 'pc_filter_page_wysiwyg_display', true, $pc_post ) ) {

		$display_container = apply_filters( 'pc_filter_page_wysiwyg_container', true, $pc_post );

		if ( $display_container ) { echo '<div class="editor">'; }
			the_content();
		if ( $display_container ) { echo '</div>'; }
		
	}

}


/*=====  FIN Wysiwyg  =====*/

/*==============================================
=            Sous-page, lien retour            =
==============================================*/

function pc_display_sub_page_backlink( $pc_post ) {

    if ( is_page() && $pc_post->parent > 0 ) {

        echo '<nav class="main-footer-prev" role="navigation" aria-label="Retour à la page parente"><a href="'.get_the_permalink($pc_post->parent).'" class="button" title="'.get_the_title($pc_post->parent).'"><span class="ico">'.pc_svg('arrow').'</span><span class="txt">Retour</span></a></nav>';

    }

}


/*=====  FIN Sous-page, lien retour  =====*/