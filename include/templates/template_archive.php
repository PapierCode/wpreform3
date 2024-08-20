<?php 
/**
 * 
 * Template : archive
 * 
 * Hooks
 * Entête
 * Liste des posts
 * Pagination
 * 
 */


/*=============================
=            Hooks            =
=============================*/

add_action( 'pc_action_template_archive_before', 'pc_display_main_start', 10 ); // template-part_layout.php

    // header
    add_action( 'pc_action_template_archive_before', 'pc_display_main_header_start', 20 ); // template-part_layout.php
        add_action( 'pc_action_template_archive_before', 'pc_display_breadcrumb', 30 ); // template-part_navigation.php
        add_action( 'pc_action_template_archive_before', 'pc_display_archive_main_header_content', 40 );
    add_action( 'pc_action_template_archive_before', 'pc_display_main_header_end', 50 ); // template-part_layout.php

    // content
    add_action( 'pc_action_template_archive_before', 'pc_display_archive_list_start', 60 );
        add_action( 'pc_action_template_archive_post', 'pc_display_archive_posts_list', 70 );
    add_action( 'pc_action_template_archive_after', 'pc_display_archive_list_end', 10 );

	// footer
	add_action( 'pc_action_template_archive_after', 'pc_display_archive_footer', 20 );

add_action( 'pc_action_template_archive_after', 'pc_display_main_end', 30 ); // template-part_layout.php


/*=====  FIN Hooks  =====*/

/*===================================
=            Entête            =
===================================*/

function pc_display_archive_main_header_content( $settings ) {
	
    $title = isset($settings['title']) && trim($settings['title']) ? trim($settings['title']) : post_type_archive_title('',false);
	echo '<h1>'.apply_filters( 'pc_filter_archive_main_title', $title, $settings ).'</h1>';

    if ( !get_query_var('term') && isset($settings['desc']) && trim($settings['desc']) ) {
        echo '<div class="editor">'.wpautop(trim($settings['desc'])).'</div>';
    }

    if ( get_query_var('term') ) {
        $term = get_term( sanitize_key( get_query_var('term') ) );
        $tax_names = get_object_taxonomies( get_query_var('post_type'), 'objects' );
        echo '<p class="term-current"> ';
            echo $tax_names[$term->taxonomy]->labels->singular_name.' : ';
            echo $term->name;
            echo '&nbsp;<a class="term-current-remove" href="'.get_post_type_archive_link( get_query_var('post_type') ).'" title="Annuler le filtre">'.pc_svg('cross').'</a>';
        echo '</p>';
    }

}


/*=====  FIN Entête  =====*/

/*=======================================
=            Liste des posts            =
=======================================*/

function pc_display_archive_list_start() {

    global $wp_query;
    echo '<ul class="card-list card-list--'.$wp_query->get( 'post_type' ).'">';

}

function pc_display_archive_list_end() {

    echo '</ul>';

}

function pc_display_archive_posts_list( $post ) {

    global $wp_query;
    $pc_post = new PC_Post( $post );

    echo '<li class="card-list-item">';
        $pc_post->display_card( 2, [ 'card--'.$wp_query->get( 'post_type' ) ] );
    echo '</li>';

}


/*=====  FIN Liste des posts  =====*/

/*==================================
=            Pagination            =
==================================*/

function pc_display_archive_footer() {

	global $wp_query;

    // TODO liens de partage
	if ( $wp_query->found_posts > get_option( 'posts_per_page' ) ) {
		
		pc_display_main_footer_start(); // template-part_layout.php
			pc_display_pager();  // template-part_navigation.php
		pc_display_main_footer_end(); // template-part_layout.php

	}

}


/*=====  FIN Pagination  =====*/