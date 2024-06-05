<?php 
/**
 * 
 * Template : page
 * 
 ** Hooks
 ** Contenu
 * 
 */

/*=============================
=            Hooks            =
=============================*/

// main start
add_action( 'pc_action_template_index', 'pc_display_main_start', 10 ); // template-part_layout.php

	// header
	add_action( 'pc_action_template_index', 'pc_display_main_header_start', 20 ); // template-part_layout.php
		add_action( 'pc_action_template_index', 'pc_display_breadcrumb', 30 ); // breadcrumb
		add_action( 'pc_action_template_index', 'pc_display_main_title', 40 ); // titre
	add_action( 'pc_action_template_index', 'pc_display_main_header_end', 50 ); // template-part_layout.php

	// content
	add_action( 'pc_action_template_index', 'pc_display_main_content_start', 60 ); // template-part_layout.php
		add_action( 'pc_action_template_index', 'pc_display_index_content', 70 ); // éditeur
	add_action( 'pc_action_template_index', 'pc_display_main_content_end', 80 ); // template-part_layout.php

	// footer
	add_action( 'pc_action_template_index', 'pc_display_main_footer_start', 90 ); // template-part_layout.php
		// add_action( 'pc_action_index_main_footer', 'pc_display_share_links', 100 ); // liens de partage
	add_action( 'pc_action_template_index', 'pc_display_main_footer_end', 110 ); // template-part_layout.php

// main end
add_action( 'pc_action_template_index', 'pc_display_main_end', 120 ); // template-part_layout.php


/*=====  FIN Hooks  =====*/

/*===============================
=            Contenu            =
===============================*/

function pc_display_index_content( $pc_post ) {

	// TODO schéma Article
	// if ( apply_filters( 'pc_filter_page_schema_article_display', true, $pc_post ) ) {
	// 	echo '<script type="application/ld+json">';
	// 		echo json_encode( $pc_post->get_schema_article(), JSON_UNESCAPED_SLASHES );
	// 	echo '</script>';
	// }

	// contenu
	if ( apply_filters( 'pc_filter_page_content_display', true, $pc_post ) ) {

		$display_container = apply_filters( 'pc_filter_page_content_container', true, $pc_post );

		if ( $display_container ) { echo '<div class="editor">'; }
			the_content();
		if ( $display_container ) { echo '</div>'; }
		
	}

}


/*=====  FIN Contenu  =====*/