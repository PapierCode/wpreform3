<?php
/**
 * 
 * Template actualité ou blog
 * 
 * Date & catégories
 * Query
 * 
 */


/*=========================================
=            Date & catégories            =
=========================================*/

add_filter( 'pc_filter_display_card_date', 'pc_edit_display_news_card_date', 10, 2 );

    function pc_edit_display_news_card_date( $display, $pc_post ) {

        if ( $pc_post->type == NEWS_POST_SLUG ) { $display = true; }
        return $display;

    }

add_filter( 'pc_filter_display_card_terms', 'pc_edit_display_news_card_terms', 10, 2 );

    function pc_edit_display_news_card_terms( $display, $pc_post ) {

        if ( $pc_post->type == NEWS_POST_SLUG && get_option('options_news_tax') ) { $display = true; }
        return $display;

    }

add_filter( 'pc_filter_post_card_taxonomy_slug', 'pc_edit_news_card_taxonomy_slug', 10, 2 );

    function pc_edit_news_card_taxonomy_slug( $slug, $pc_post ) {

        if ( $pc_post->type == NEWS_POST_SLUG && get_option('options_news_tax') ) { $slug = NEWS_TAX_SLUG; }
        return $slug;

    }

add_action( 'pc_action_template_index', 'p_display_index_news_date_and_terms', 45 );

    function p_display_index_news_date_and_terms( $pc_post ) {

        if ( $pc_post->type == NEWS_POST_SLUG ) { 
            $pc_post->display_date( 'date date--single' );
            if ( get_option('options_news_tax') ) { $pc_post->display_terms( 'single-terms' ); }
        }

    }


/*=====  FIN Date & catégories  =====*/

/*=============================
=            Query            =
=============================*/

add_action( 'pre_get_posts', 'pc_archive_news_pre_get_posts' );

    function pc_archive_news_pre_get_posts( $query ) {
        
        if ( !is_admin() && $query->is_main_query() && $query->is_archive() && get_query_var('post_type') == NEWS_POST_SLUG &&get_query_var('category') ) {

            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => NEWS_TAX_SLUG,
                    'field' => 'term_id',
                    'terms' => sanitize_key( get_query_var('category') ),
                )
            ));

        }

    }


/*=====  FIN Query  =====*/