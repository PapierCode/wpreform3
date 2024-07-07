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
add_action( 'pc_action_template_index', 'pc_display_main_start', 10 ); // template-part_layout.php

	// header
	add_action( 'pc_action_template_index', 'pc_display_main_header_start', 20 ); // template-part_layout.php
		add_action( 'pc_action_template_index', 'pc_display_breadcrumb', 30 ); // template-part_navigation.php
		add_action( 'pc_action_template_index', 'pc_display_single_main_title', 40 );
	add_action( 'pc_action_template_index', 'pc_display_main_header_end', 60 ); // template-part_layout.php

	// content
	add_action( 'pc_action_template_index', 'pc_display_main_content_start', 70 ); // template-part_layout.php
		add_action( 'pc_action_template_index', 'pc_display_single_content', 80 );
	add_action( 'pc_action_template_index', 'pc_display_main_content_end', 90 ); // template-part_layout.php

	// footer
	add_action( 'pc_action_template_index', 'pc_display_main_footer_start', 100 ); // template-part_layout.php
		add_action( 'pc_action_template_index', 'pc_display_share_links', 110 ); // template-part_social.php
	add_action( 'pc_action_template_index', 'pc_display_main_footer_end', 120 ); // template-part_layout.php

// main end
add_action( 'pc_action_template_index', 'pc_display_main_end', 130 ); // template-part_layout.php


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
	if ( apply_filters( 'pc_filter_single_content_display', true, $pc_post ) ) {

		$display_container = apply_filters( 'pc_filter_display_single_content_container', true, $pc_post );

		if ( $display_container ) { echo '<section class="editor">'; }
			the_content();
		if ( $display_container ) { echo '</section>'; }
		
	}

}


/*=====  FIN Éditeur  =====*/