<?php 
/**
 * 
 * Template : résultats de recherche
 * 
 * Hooks
 * Titre
 * Résultats
 * Pagination
 * 
 */


/*=============================
=            Hooks            =
=============================*/

// main start
add_action( 'pc_action_template_search', 'pc_display_main_start', 10 ); // tpl-part_layout.php

	// header
	add_action( 'pc_action_template_search', 'pc_display_main_header_start', 20 ); // tpl-part_layout.php
		add_action( 'pc_action_template_search', 'pc_display_breadcrumb', 30 ); // tpl-part_navigation.php
		add_action( 'pc_action_template_search', 'pc_display_search_main_title', 40 );
	add_action( 'pc_action_template_search', 'pc_display_main_header_end', 50 ); // tpl-part_layout.php

	// content
	add_action( 'pc_action_template_search', 'pc_display_main_content_start', 60 ); // tpl-part_layout.php
		add_action( 'pc_action_template_search', 'pc_display_search_results_content', 70 );
	add_action( 'pc_action_template_search', 'pc_display_main_content_end', 80 ); // tpl-part_layout.php

	// footer
	add_action( 'pc_action_template_search', 'pc_display_search_footer', 90 );

// main end
add_action( 'pc_action_template_search', 'pc_display_main_end', 100 ); // tpl-part_layout.php


/*=====  FIN Hooks  =====*/

/*=============================
=            Titre            =
=============================*/

function pc_display_search_main_title() {
	
	$title = get_search_query() ? 'Résultats de la recherche' : "Recherche";
	echo apply_filters( 'pc_filter_search_main_title', '<h1>'.$title.'</h1>' );

}


/*=====  FIN Titre  =====*/

/*=================================
=            Résultats            =
=================================*/

function pc_display_search_results_content() {

	global $wp_query;

	echo '<section id="search-results" class="search-result">';

	if ( $search_query = get_search_query() ) {

		$ico = apply_filters( 'pc_filter_search_result_ico', pc_svg('arrow') );

		$types = array( 'page' => 'Page' );
		if ( get_option('options_news_enabled') ) { $types[NEWS_POST_SLUG] = 'Actualité'; }
		if ( get_option('options_events_enabled') ) { $types[EVENT_POST_SLUG] = 'Événement'; }
		$types = apply_filters( 'pc_filter_search_results_post_types', $types );

		echo '<p class="s-results-infos">'.pc_get_search_count_results( $search_query ).'.</p>';		

		pc_display_form_search( 'main' );

		if ( $wp_query->found_posts > 0 ) {

			echo '<ol class="s-results-list">';

			foreach ( $wp_query->posts as $post ) {
				
				$pc_post = new PC_Post( $post );

				echo '<li class="s-results-item s-results-item--'.$pc_post->type.'">';
					echo '<h2 class="s-results-item-title"><a class="s-results-item-link" href="'.$pc_post->permalink.'" title="Lire la suite">'.$pc_post->get_card_title().'</a></h2>';
					if ( array_key_exists( $pc_post->type, $types ) ) { echo '<p class="s-results-item-type">'.$types[$pc_post->type].'</p>'; };
					echo '<p class="s-results-item-desc">'.$pc_post->get_card_description().'<span class="card-desc-ico">&nbsp;<span class="ico">'.$ico.'</span></span></p>';
				echo '</li>';

			}
			
			echo '</ol>';

		}

	} else { pc_display_form_search( 'main' ); }

	echo '</section>';

}


/*=====  FIN Résultats  =====*/

/*==================================
=            Pagination            =
==================================*/

function pc_display_search_footer() {

	global $wp_query;

	if ( get_search_query() && $wp_query->found_posts > get_option( 'posts_per_page' ) ) {
		
		// TODO liens de partage
		pc_display_main_footer_start(); // tpl-part_layout.php
			pc_display_pager();  // tpl-part_navigation.php
		pc_display_main_footer_end(); // tpl-part_layout.php

	}

}


/*=====  FIN Pagination  =====*/