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

/*=============================================
=            Filtres par catégorie            =
=============================================*/

add_filter( 'pc_filter_archive_main_header_description_display', 'pc_edit_news_archive_main_header_description_display', 10, 2 );

    function pc_edit_news_archive_main_header_description_display( $display, $post_type ) {

        if ( $post_type == NEWS_POST_SLUG && get_query_var('category') ) { $display = false; }
        return $display;

    }

add_action( 'pc_action_template_archive_before', 'pc_display_news_archive_main_header_filters', 45, 2 );

    function pc_display_news_archive_main_header_filters( $post_type, $settings ) {

        if ( $post_type != NEWS_POST_SLUG && !get_option('options_news_tax') ) { return; }

        $modal_id = 'modal-news-filters';
        $modal_title = 'Catégories des actualités';
        $archive_url = get_post_type_archive_link(NEWS_POST_SLUG);
        $terms = get_terms([
            'post_type' => NEWS_POST_SLUG,
            'taxonomy' => NEWS_TAX_SLUG
        ]);

        if ( is_array( $terms ) && !empty( $terms ) ) {

            echo '<div class="filters">';
                echo '<button type="button" class="button modal-btn-open" title="Boite de dialogue" aria-control="'.$modal_id.'"><span class="ico">'.pc_svg('tag').'</span><span class="txt">Catégories</span></button>';
                if ( get_query_var('category') ) {
                    $current_term = get_term_by( 'id', get_query_var('category'), NEWS_TAX_SLUG );
                    echo '<a href="'.$archive_url.'" class="button button--cancel" title="Annuler le filtre '.$current_term->name.'" aria-label="Annuler le filtre '.$current_term->name.'" rel="nofollow"><span class="ico">'.pc_svg('cross').'</span><span class="txt">'.$current_term->name.'</span></a>';
                }
            echo '</div>';

            $modal_content = '<h2 class="modal-title">'.$modal_title.'</h2>';
            $modal_content .= '<ul class="news-filter-list">';
                foreach ( $terms as $term ) {

                    $is_active = get_query_var('category') && get_query_var('category') == $term->term_id;
                    $link_attrs = array(
                        'class' => 'news-filter-link button',
                        'rel' => 'nofollow'
                    );
                    if ( $is_active ) { 
                        $link_attrs['class'] .= ' button--cancel';
                        $link_attrs = array_merge( $link_attrs,
                            array(
                                'href' => $archive_url,
                                'title' => 'Annuler le filtre '.$term->name,
                                'aria-label' => 'Annuler le filtre '.$term->name
                            )
                        );
                    } else {
                        $link_attrs = array_merge( $link_attrs,
                            array(
                                'href' => $archive_url.'?category='.$term->term_id,
                                'title' => 'Filtrer par '.$term->name,
                                'aria-label' => 'Filtrer par '.$term->name
                            )
                        );
                    }
                    
                    $modal_content .= '<li class="archive-filters-item"><a '.pc_get_attrs_to_string( $link_attrs ).'>';
                        if ( $is_active ) { $modal_content .= '<span class="ico">'.pc_svg('cross').'</span>'; }
                        $modal_content .= '<span class="txt">'.$term->name.'</span>';
                    $modal_content .= '</a></li>';
                    
                }
            $modal_content .= '</ul>';

            pc_display_modal(array(
                'id' => $modal_id,
                'label' => $modal_title,
                'content' => $modal_content
            ));

        }

    }


/*=====  FIN Filtres par catégorie  =====*/

/*=============================
=            Query            =
=============================*/

add_action( 'pre_get_posts', 'pc_archive_news_pre_get_posts' );

    function pc_archive_news_pre_get_posts( $query ) {
        
        if ( !is_admin() && $query->is_main_query() && $query->is_archive() && get_query_var('post_type') == NEWS_POST_SLUG && get_query_var('category') ) {

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