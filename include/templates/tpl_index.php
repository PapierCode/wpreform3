<?php 
/**
 * 
 * Template : page
 * 
 * Hooks
 * Titre
 * Éditeur
 * 
 */

/*=============================
=            Hooks            =
=============================*/

// main start
add_action( 'pc_action_template_index', 'pc_display_main_start', 10 ); // tpl-part_layout.php

	// header
	add_action( 'pc_action_template_index', 'pc_display_main_header_start', 20 ); // tpl-part_layout.php
		add_action( 'pc_action_template_index', 'pc_display_breadcrumb', 30 ); // tpl-part_navigation.php
		add_action( 'pc_action_template_index', 'pc_display_single_main_title', 40 );
	add_action( 'pc_action_template_index', 'pc_display_main_header_end', 50 ); // tpl-part_layout.php

	// content
	add_action( 'pc_action_template_index', 'pc_display_main_content_start', 60 ); // tpl-part_layout.php
		add_action( 'pc_action_template_index', 'pc_display_single_content', 70 );
	add_action( 'pc_action_template_index', 'pc_display_main_content_end', 80 ); // tpl-part_layout.php

	// footer
	add_action( 'pc_action_template_index', 'pc_display_main_footer_start', 90 ); // tpl-part_layout.php
		add_action( 'pc_action_template_index', 'pc_display_main_footer_backlink', 100 );
		add_action( 'pc_action_template_index', 'pc_display_share_links', 110 ); // tpl-part_social.php
	add_action( 'pc_action_template_index', 'pc_display_main_footer_end', 120 ); // tpl-part_layout.php

// main end
add_action( 'pc_action_template_index', 'pc_display_main_end', 130 ); // tpl-part_layout.php


/*=====  FIN Hooks  =====*/

/*=============================
=            Titre            =
=============================*/

function pc_display_single_main_title( $pc_post ) {
	
	echo '<h1>'.apply_filters( 'pc_filter_single_main_title', get_the_title( $pc_post->id ) ).'</h1>';

}


/*=====  FIN Titre  =====*/

/*===============================
=            Éditeur            =
===============================*/

function pc_display_single_content( $pc_post ) {

	// TODO schéma Article
	// if ( apply_filters( 'pc_filter_page_schema_article_display', true, $pc_post ) ) {
	// 	echo '<script type="application/ld+json">';
	// 		echo json_encode( $pc_post->get_schema_article(), JSON_UNESCAPED_SLASHES );
	// 	echo '</script>';
	// }

	// contenu
	if ( !is_front_page() && apply_filters( 'pc_filter_single_content_display', true, $pc_post ) ) {

		$display_container = apply_filters( 'pc_filter_display_single_content_container', true, $pc_post );

		if ( $display_container ) { echo '<section class="editor">'; }
			the_content();
		if ( $display_container ) { echo '</section>'; }
		
	}

}


/*=====  FIN Éditeur  =====*/

/*===================================
=            Lien retour            =
===================================*/

function pc_display_main_footer_backlink( $pc_post ) {

	if ( ( is_page() && $pc_post->parent > 0 ) || is_single() ) {

		$wp_referer = wp_get_referer();
		
		if ( $wp_referer ) {
			$back_link = $wp_referer;
			$back_title = 'Page précédente';
			$back_txt = 'Page précédente';
			$back_ico = 'arrow';
		} else if ( is_page() ) {
			$back_link = get_the_permalink( $pc_post->parent );
			$back_title = 'En savoir plus';
			$back_txt = 'En savoir plus';
			$back_ico = 'more';
		} else {
			$post_object = get_post_type_object( $pc_post->type );
			$back_link = get_post_type_archive_link( $pc_post->type );
			$back_title = $post_object->labels->all_items;
			$back_txt = apply_filters( 'pc_filter_backlink_text', 'd\''.$post_object->labels->name, $pc_post );
			$back_ico = 'more';
		}

		echo pc_get_button( 
			$back_txt, 
			[
				'href' => $back_link,
				'class' => 'button--previous',
				'title' => $back_title
			], 
			$back_ico
		);

	}

}


/*=====  FIN Lien retour  =====*/