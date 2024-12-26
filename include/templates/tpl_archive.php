<?php 
/**
 * 
 * Template : archive
 * 
 * Hooks
 * Entête
 * Liste des posts
 * Pied de page (main)
 * 
 */


/*=============================
=            Hooks            =
=============================*/

add_action( 'pc_action_template_archive_before', 'pc_display_main_start', 10 ); // tpl-part_layout.php

    // header
    add_action( 'pc_action_template_archive_before', 'pc_display_main_header_start', 20 ); // tpl-part_layout.php
        add_action( 'pc_action_template_archive_before', 'pc_display_breadcrumb', 30 ); // tpl-part_navigation.php
        add_action( 'pc_action_template_archive_before', 'pc_display_archive_main_header_content', 40, 2 );
    add_action( 'pc_action_template_archive_before', 'pc_display_main_header_end', 50 ); // tpl-part_layout.php

    // content
    add_action( 'pc_action_template_archive_before', 'pc_display_archive_list_start', 60 );
        add_action( 'pc_action_template_archive_posts', 'pc_display_archive_posts_list', 10 );
    add_action( 'pc_action_template_archive_after', 'pc_display_archive_list_end', 10 );

	// footer
	add_action( 'pc_action_template_archive_after', 'pc_display_main_footer_start', 20 ); // tpl-part_layout.php
        add_action( 'pc_action_template_archive_after', 'pc_display_pager', 30 ); // tpl-part_navigation.php
        add_action( 'pc_action_template_archive_after', 'pc_display_share_links', 40 ); // tpl-part_social.php
    add_action( 'pc_action_template_archive_after', 'pc_display_main_footer_end', 50 ); // tpl-part_layout.php

add_action( 'pc_action_template_archive_after', 'pc_display_main_end', 60 ); // tpl-part_layout.php


/*=====  FIN Hooks  =====*/

/*==============================
=            Entête            =
==============================*/

function pc_display_archive_main_header_content( $post_type, $settings ) {

    /*----------  Titre  ----------*/    

    $title = isset($settings['title']) && trim($settings['title']) ? trim($settings['title']) : post_type_archive_title('',false);

	echo '<h1>'.apply_filters( 'pc_filter_archive_main_header_title', $title, $post_type, $settings ).'</h1>';
    

    /*----------  Description  ----------*/

    $display_desc = get_query_var('paged') > 1 ? false : true;
    
    if ( apply_filters( 'pc_filter_archive_main_header_description_display', $display_desc, $post_type ) && isset($settings['desc']) && trim($settings['desc']) ) {
        echo '<div class="tiny-editor">'.wpautop(trim($settings['desc'])).'</div>';
    }

}


/*=====  FIN Entête  =====*/

/*=======================================
=            Liste des posts            =
=======================================*/

function pc_display_archive_list_start( $post_type ) {

    echo '<ul class="card-list card-list--'.$post_type.'">';

}

function pc_display_archive_list_end() {

    echo '</ul>';

}

function pc_display_archive_posts_list( $post ) {

    $pc_post = new PC_Post( $post );
    echo '<li class="card-list-item">';
        $pc_post->display_card( 2 );
    echo '</li>';

}


/*=====  FIN Liste des posts  =====*/