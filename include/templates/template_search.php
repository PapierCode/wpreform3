<?php 
/**
 * 
 * Template : Search results
 * 
 * Hooks
 * Title
 * Results
 * Pager
 * 
 */


/*=============================
=            Hooks            =
=============================*/

// main start
add_action( 'pc_action_template_search', 'pc_display_main_start', 10 ); // template-part_layout.php

	// header
	add_action( 'pc_action_template_search', 'pc_display_main_header_start', 20 ); // template-part_layout.php
		add_action( 'pc_action_template_search', 'pc_display_breadcrumb', 30 ); // template-part_navigation.php
		add_action( 'pc_action_template_search', 'pc_display_search_main_title', 40 );
	add_action( 'pc_action_template_search', 'pc_display_main_header_end', 50 ); // template-part_layout.php

	// content
	add_action( 'pc_action_template_search', 'pc_display_main_content_start', 60 ); // template-part_layout.php
		add_action( 'pc_action_template_search', 'pc_display_search_results', 70 );
	add_action( 'pc_action_template_search', 'pc_display_main_content_end', 80 ); // template-part_layout.php

	// footer
	add_action( 'pc_action_template_search', 'pc_display_search_footer', 90 );

// main end
add_action( 'pc_action_template_search', 'pc_display_main_end', 100 ); // template-part_layout.php


/*=====  FIN Hooks  =====*/

/*=============================
=            Title            =
=============================*/

function pc_display_search_main_title() {
	
	global $wp_query;
	$title = ( get_search_query() && $wp_query->found_posts > 0 ) ? 'Résultats de la recherche' : "Recherche";

	echo apply_filters( 'pc_filter_search_main_title', '<h1><span>'.$title.'</span></h1>' );

}


/*=====  FIN Title  =====*/

/*===============================
=            Results            =
===============================*/

function pc_display_search_results() {

	global $wp_query;

	if ( $search_query = get_search_query() ) {

		$ico = apply_filters( 'pc_filter_search_result_ico', pc_svg('arrow') );
		$types = apply_filters( 'pc_filter_search_results_type', array( 
			'page' => 'Page',
			'news' => 'Actualité'
		) );


		/*----------  Affichage  ----------*/

		echo '<p class="s-results-infos">'.pc_get_search_count_results( $search_query ).'.</p>';		

		pc_display_form_search();

		if ( $wp_query->found_posts > 0 ) {

			echo '<ol id="search-results" class="s-results-list reset-list">';

			foreach ( $wp_query->posts as $post ) {
				
				$pc_post = new PC_Post( $post );
				$tag = ( array_key_exists( $pc_post->type, $types ) ) ? '<span>'.$types[$pc_post->type].'</span>' : '';
				$css_has_image = ( $pc_post->has_image ) ? ' has-image' : '';

				echo '<li class="s-results-item s-results-item--'.$pc_post->type.$css_has_image.'">';
					echo '<h2 class="s-results-item-title"><a class="s-results-item-link" href="'.$pc_post->permalink.'" title="Lire la suite"><span>'.$pc_post->get_card_title().'</span> '.$tag.'</a></h2>';
					echo '<p class="s-results-item-desc">'.$pc_post->get_card_description().'&nbsp;<span class="st-desc-ico">'.$ico.'</span></p>';			
					if ( $pc_post->thumb_id ) {
						echo '<figure class="s-results-item-img"><img src="'.wp_get_attachment_image_src( $pc_post->thumb_id, 'gl-th' )[0].'" alt="" width="200" height="200" /></figure>';
					}
				echo '</li>';

			}
			
			echo '</ol>';

		}

	} else {

		pc_display_form_search();
		
	}

}


/*=====  FIN Results  =====*/

/*=============================
=            Pager            =
=============================*/

function pc_display_search_footer() {

	global $wp_query;

	if ( get_search_query() && $wp_query->found_posts > get_option( 'posts_per_page' ) ) {
		
		// TODO liens de partage
		pc_display_main_footer_start(); // template-part_layout.php
			pc_display_pager();  // template-part_navigation.php
		pc_display_main_footer_end(); // template-part_layout.php

	}

}


/*=====  FIN Pager  =====*/