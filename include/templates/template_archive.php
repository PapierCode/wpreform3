<?php 
/**
 * 
 * Template : archive
 * 
 ** Hooks
 ** Contenu
 * 
 */

/*=============================
=            Hooks            =
=============================*/

add_action( 'pc_action_template_archive_before', 'pc_display_main_start', 10 ); // template-part_layout.php

    // header
    add_action( 'pc_action_template_archive_before', 'pc_display_main_header_start', 20 ); // template-part_layout.php
        add_action( 'pc_action_template_archive_before', 'pc_display_breadcrumb', 30 ); // breadcrumb
        add_action( 'pc_action_template_archive_before', 'pc_display_archive_title', 40 ); // titre
    add_action( 'pc_action_template_archive_before', 'pc_display_main_header_end', 50 ); // template-part_layout.php

    // content
    add_action( 'pc_action_template_archive_before', 'pc_display_archive_list_start', 60 ); // début liste
        add_action( 'pc_action_template_archive_post', 'pc_display_archive_post', 10 ); // item liste
    add_action( 'pc_action_template_archive_after', 'pc_display_archive_list_end', 10 ); // fin liste

	// footer
	add_action( 'pc_action_template_archive_after', 'pc_display_main_footer_start', 20 ); // template-part_layout.php
        add_action( 'pc_action_template_archive_after', 'pc_display_pager', 30 ); // pagination
        // add_action( 'pc_action_template_archive_after', 'pc_display_share_links', 40 ); // liens de partage
	add_action( 'pc_action_template_archive_after', 'pc_display_main_footer_end', 50 ); // template-part_layout.php

add_action( 'pc_action_template_archive_after', 'pc_display_main_end', 60 ); // template-part_layout.php


/*=====  FIN Hooks  =====*/

/*==============================
=            Entête            =
==============================*/

function pc_display_archive_title( $settings ) {
	
    $title = isset($settings['title']) && trim($settings['title']) ? trim($settings['title']) : post_type_archive_title('',false);
	echo '<h1><span>'.$title.'</span></h1>';

    if ( isset($settings['desc']) && trim($settings['desc']) ) {
        echo '<div class="editor">'.wpautop(trim($settings['desc'])).'</div>';
    }

}


/*=====  FIN Entête  =====*/

/*========================================
=            Liste d'articles            =
========================================*/

function pc_display_archive_list_start() {
    echo '<ul class="card-list card-list--news">';
}
function pc_display_archive_list_end() {
    echo '</ul>';
}

function pc_display_archive_post( $post ) {

    $pc_post = new PC_Post( $post );
    echo '<li class="card-list-item card-list-item--news">';
        $pc_post->display_card(get_field('title_level'));
    echo '</li>';

}


/*=====  FIN Liste d'articles  =====*/