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
        add_action( 'pc_action_template_archive_before', 'pc_display_archive_main_header_content', 40 );
    add_action( 'pc_action_template_archive_before', 'pc_display_main_header_end', 50 ); // tpl-part_layout.php

    // content
    add_action( 'pc_action_template_archive_before', 'pc_display_archive_list_start', 60 );
        add_action( 'pc_action_template_archive_post', 'pc_display_archive_posts_list', 70 );
    add_action( 'pc_action_template_archive_after', 'pc_display_archive_list_end', 10 );

	// footer
	add_action( 'pc_action_template_archive_after', 'pc_display_archive_footer', 20 );

add_action( 'pc_action_template_archive_after', 'pc_display_main_end', 30 ); // tpl-part_layout.php


/*=====  FIN Hooks  =====*/

/*==============================
=            Entête            =
==============================*/

function pc_display_archive_main_header_content( $settings ) {

    /*----------  Titre  ----------*/    
    
    $title = isset($settings['title']) && trim($settings['title']) ? trim($settings['title']) : post_type_archive_title('',false);
	echo '<h1>'.apply_filters( 'pc_filter_archive_main_title', $title, $settings ).'</h1>';

    /*----------  Description  ----------*/
    
    if ( !get_query_var('term') && isset($settings['desc']) && trim($settings['desc']) ) {
        echo '<div class="editor">'.wpautop(trim($settings['desc'])).'</div>';
    }

    /*----------  Filtres  ----------*/
    
    // titre de post courant
    $post_type = get_query_var( 'post_type' );
    // url de la page courante
    $post_type_archive_link = get_post_type_archive_link( $post_type );
    // tous les posts 
    $post_ids = get_posts([
        'fields' => 'ids',
        'post_type' => $post_type,
        'nopaging' => true,
    ]);
    $taxonomies = get_object_taxonomies( $post_type, 'objects' );
    $terms = get_terms([
        'post_type' => $post_type,
        'taxonomy' => array_keys($taxonomies),
        'object_ids' => $post_ids
    ]);
    
    if ( is_array( $terms ) && !empty( $terms ) ) {
        echo '<nav class="archive-filters" aria-label="Filtres"><dl class="archive-filters-list">';
            echo '<dt class="archive-filters-title" aria-hidden="true"><span class="ico">'.pc_svg('tag').'</span><span class="txt">Filtres :</span></dt>';
            foreach ( $terms as $term ) {
                $link_attrs = array(
                    'class' => 'archive-filters-link button',
                    'rel' => 'nofollow'
                );
                if ( get_query_var('term') && get_query_var('term') == $term->term_id ) { 
                    $link_attrs = array_merge( $link_attrs,
                        array(
                            'href' => $post_type_archive_link,
                            'title' => 'Annuler le filtre '.$term->name,
                            'aria-label' => 'Annuler le filtre '.$term->name,
                            'aria-current' => 'page'
                        )
                    );
                } else {
                    $link_attrs = array_merge( $link_attrs,
                        array(
                            'href' => $post_type_archive_link.'?term='.$term->term_id,
                            'title' => 'Filtrer par '.$term->name,
                            'aria-label' => 'Filtrer par '.$term->name
                        )
                    );
                }
                $link_attrs = apply_filters( 'pc_filter_archive_filter_link_attributs', $link_attrs, $term, $post_type_archive_link );
                echo '<dd class="archive-filters-item"><a '.pc_get_attrs_to_string( $link_attrs ).'>';
                    if ( isset( $link_attrs['aria-current'] ) ) { echo '<span class="ico">'.pc_svg('cross').'</span>'; }
                    echo '<span class="txt">'.$term->name.'</span>';
                echo '</a></dd>';
            }
        echo '</dl></nav>';
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

/*===========================================
=            Pied de page (main)            =
===========================================*/

function pc_display_archive_footer() {
		
    pc_display_main_footer_start(); // tpl-part_layout.php
        pc_display_pager();  // tpl-part_navigation.php
        pc_display_share_links(); // tpl-part_social.php
    pc_display_main_footer_end(); // tpl-part_layout.php

}


/*=====  FIN Pied de page (main)  =====*/

/*==========================================
=            Filtres catégories            =
==========================================*/
    
add_action( 'pre_get_posts', 'pc_archive_pre_get_posts' );

    function pc_archive_pre_get_posts( $query ) {

        if ( ( !get_option('options_news_enabled') && !get_option('options_news_tax') ) || ( !get_option('options_events_enabled') && !get_option('options_events_tax') ) ) { return; }
        
        if ( !is_admin() && $query->is_main_query() && $query->is_archive(get_query_var('post_type')) && get_query_var('term') ) {
            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => NEWS_TAX_SLUG,
                    'field' => 'term_id',
                    'terms' => sanitize_key( get_query_var('term') ),
                ),
            ));
        }

    }


/*=====  FIN Filtres catégories  =====*/