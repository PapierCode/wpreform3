<?php

add_filter( 'pc_filter_display_card_date', 'pc_edit_display_news_date', 10, 2 );
add_filter( 'pc_filter_display_single_main_date', 'pc_edit_display_news_date', 10, 2 );

    function pc_edit_display_news_date( $display, $pc_post ) {

        if ( $pc_post->type == NEWS_POST_SLUG && get_option('options_news_enabled') ) { $display = true; }

        return $display;

    }

add_filter( 'pc_filter_post_card_taxonomy_slug', 'pc_edit_news_taxonomy_slug', 10, 2 );

    function pc_edit_news_taxonomy_slug( $slug, $pc_post ) {

        if ( get_option('options_news_tax') ) { $slug = NEWS_TAX_SLUG; }

        return $slug;

    }

add_action( 'pc_action_template_index', 'pc_display_news_single_terms', 55 );

    function pc_display_news_single_terms( $pc_post ) {

        if ( $pc_post->type == NEWS_POST_SLUG ) {

            $pc_post->display_terms( 'single-terms' );

        }

    }

add_action( 'pre_get_posts', 'pc_news_pre_get_posts' );

    function pc_news_pre_get_posts( $query ) {

        if ( !is_admin() && $query->is_main_query() && $query->is_archive(NEWS_POST_SLUG) && get_query_var('term') ) {
            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => NEWS_TAX_SLUG,
                    'field' => 'term_id',
                    'terms' => sanitize_key( get_query_var('term') ),
                ),
            ));
        }

    }