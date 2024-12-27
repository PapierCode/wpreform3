<?php
/**
 * 
 * Templates actualité/blog & événement
 * 
 */


if ( get_option('options_news_enabled') ) {

/*==================================
=            Actualités            =
==================================*/

/*----------  Card  ----------*/

// affichage date
add_filter( 'pc_filter_display_card_date', 'pc_edit_display_news_card_date', 10, 2 );

    function pc_edit_display_news_card_date( $display, $pc_post ) {

        if ( $pc_post->type == NEWS_POST_SLUG ) { $display = true; }
        return $display;

    }

// affichage catégories
add_filter( 'pc_filter_display_card_terms', 'pc_edit_display_news_card_terms',10 ,2 );

    function pc_edit_display_news_card_terms( $display, $pc_post ) {

        if ( $pc_post->type == NEWS_POST_SLUG && get_option('options_news_tax') ) { $display = true; }
        return $display;

    }


/*----------  Single  ----------*/

// affichage date & catégories
add_action( 'pc_action_template_index', 'pc_display_index_news_date_and_terms', 45 );

    function pc_display_index_news_date_and_terms( $pc_post ) {

        if ( $pc_post->type == NEWS_POST_SLUG ) { 
            $pc_post->display_date( 'date date--single' );
            if ( get_option('options_news_tax') ) { $pc_post->display_terms( 'single-terms' ); }
        }

    }


/*----------  Filtres  ----------*/

add_action( 'pre_get_posts', 'pc_archive_news_pre_get_posts' );

    function pc_archive_news_pre_get_posts( $query ) {
        
        if ( get_option('options_news_tax') && !is_admin() && $query->is_main_query() && $query->is_archive() && get_query_var('post_type') == NEWS_POST_SLUG && get_query_var('category') ) {

            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => NEWS_TAX_SLUG,
                    'field' => 'term_id',
                    'terms' => sanitize_key( get_query_var('category') ),
                )
            ));

        }

    }


/*=====  FIN Actualités  =====*/

} // FIN if get_option('options_news_enabled')

if ( get_option('options_events_enabled') ) {

/*==================================
=            Événements            =
==================================*/

/*----------  Date  ----------*/

function pc_display_event_date( $pc_post, $context = 'single' ) {

    $metas = $pc_post->metas;

    if ( $metas['event_canceled'] ) {
        echo pc_display_alert_msg(
            apply_filters( 'pc_filter_event_single_canceled_text', '<strong>Événement annulé</strong>', $pc_post ),
            'error',
            'block'
        );
    }	

    $start = $metas['event_date_start'];
    $end = $metas['event_date_end'];

    $css = [  'date', 'date--'.$context ];
    if ( $metas['event_date_txt'] ) { $css[] = 'date-custom--'.$context; }

    echo '<p class="'.implode(' ',$css).'">';
    echo '<span class="ico">'.pc_svg('calendar').'</span>';
    echo '<span class="txt">';

    if ( $metas['event_date_txt'] ) {

        // texte libre
        echo $metas['event_date_txt'];

    } else {		

        /*----------  Dates identiques  ----------*/
        
        // même jour
        if ( date_i18n('z',$start) == date_i18n('z',$end) ) {

            // même heure
            if ( $start == $end ) { 
                echo '<time datetime="'.date_i18n('c',$start).'">'.str_replace('h00','h',date_i18n( 'j F Y \à G\hi', $start)).'</time>';
            
            // heure différente
            } else {
                echo '<time datetime="'.date_i18n('Y-m-d',$start).'">'.date_i18n( 'j F Y', $start).'</time>';
                echo ' de <time datetime="'.date_i18n('H:i',$start).'">'.str_replace('h00','h',date_i18n('G\hi',$start)).'</time>';
                echo ' à <time datetime="'.date_i18n('H:i',$end).'">'.str_replace('h00','h',date_i18n('G\hi',$end)).'</time>';
            }
    
    
        /*----------  Dates différentes  ----------*/		
    
        } else {
    
            echo 'Du <time datetime="'.date_i18n('c',$start).'">'.str_replace('h00','h',date_i18n( 'j F Y \à G\hi', $start)).'</time> au <time datetime="'.date_i18n('c',$end).'">'.str_replace('h00','h',date_i18n( 'j F Y \à G\hi', $end )).'</time>';
    
        }
            
    }

    echo '</span></p>';

}

/*----------  Archive  ----------*/

add_action( 'pc_action_template_archive_after', 'pc_display_event_archive_link', 35 );

    function pc_display_event_archive_link() {

        if ( get_query_var('post_type') == EVENT_POST_SLUG ) {
            
            $href = get_post_type_archive_link(EVENT_POST_SLUG);
            $txt = 'Événements passés';       

            if ( get_query_var( 'archive' ) == 1 ) { $txt = 'Événements à venir'; }
            else {  $href .= '?archive=1'; }
            
            echo '<a href="'.$href.'" class="past-events-link button"><span class="ico">'.pc_svg('more-s').'</span><span class="txt">'.$txt.'</span></a>';
            
        }

    }

/*----------  Card  ----------*/

// affichage date
add_action( 'pc_post_card_after_title', 'pc_display_event_card_date', 10 );

    function pc_display_event_card_date( $pc_post ) {

        if ( $pc_post->type == EVENT_POST_SLUG ) { pc_display_event_date( $pc_post, 'card' ); }

    }

// affichage catégories
add_filter( 'pc_filter_display_card_terms', 'pc_edit_display_event_card_terms',10 ,2 );

    function pc_edit_display_event_card_terms( $display, $pc_post ) {

        if ( $pc_post->type == EVENT_POST_SLUG && get_option('options_events_tax') ) { $display = true; }
        return $display;

    }


/*----------  Single  ----------*/

// affichage date
add_action( 'pc_action_template_index', 'pc_display_event_single_date', 45 );

    function pc_display_event_single_date( $pc_post ) {

        if ( $pc_post->type == EVENT_POST_SLUG ) {
            pc_display_event_date( $pc_post, 'single' );
            if ( get_option('options_events_tax') ) { $pc_post->display_terms( 'single-terms' ); }
        }

    }

/*----------  Filtres  ----------*/

add_action( 'pre_get_posts', 'pc_archive_events_pre_get_posts' );

    function pc_archive_events_pre_get_posts( $query ) {

        if ( !is_admin() && $query->is_main_query() && $query->is_archive() && get_query_var('post_type') == EVENT_POST_SLUG ) {

            if ( get_query_var( 'archive' ) == 1 ) { // passés
                $meta_compare = '<=';
            } else { // à venir
                $query->set( 'order', 'ASC' );
                $meta_compare = '>=';
            }

            if ( get_query_var('category') ) {
                $taxonomy = get_option('options_events_tax_shared') ? NEWS_TAX_SLUG : EVENT_TAX_SLUG;
                $query->set( 'tax_query', array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'term_id',
                        'terms' => sanitize_key( get_query_var('category') ),
                    )
                ));
            }

            $query->set( 'meta_query', array(
                array(
                    'key' => 'event_date_end',
                    'compare' => $meta_compare,
                    'value' => date_i18n( 'Y-m-d G:i:s', strtotime('now') ),
                    'type' => 'DATETIME'
                )
            ));

            $query->set( 'orderby', 'meta_value' );
            $query->set( 'meta_key', 'event_date_end' );
            $query->set( 'meta_type', 'DATETIME' );

        }

    }


/*=====  FIN Événements  =====*/

} // FIN if get_option('options_events_enabled')


if ( get_option('options_news_enabled') || get_option('options_events_enabled') ) {

/*=========================================
=            Filtres (archive)            =
=========================================*/

/*----------  Entête  ----------*/

add_filter( 'pc_filter_archive_main_header_title', 'pc_edit_news_events_archive_main_header_title', 10, 3 );

    function pc_edit_news_events_archive_main_header_title( $title, $post_type, $settings ) {

        // événements passés
        if ( $post_type == EVENT_POST_SLUG && get_query_var( 'archive' ) == 1 ) { $title = 'Événements passés'; }

        // catégorie en cours
        if ( in_array( $post_type, [NEWS_POST_SLUG,EVENT_POST_SLUG] ) && get_query_var('category') ) {
            $category = get_term_by( 'term_taxonomy_id', get_query_var('category') );
            if ( $category ) { $title .= ', '.$category->name; }
        }

        return $title;

    }

add_filter( 'pc_filter_archive_main_header_description_display', 'pc_edit_news_events_main_header_description_display', 10, 2 );

    function pc_edit_news_events_main_header_description_display( $display, $post_type ) {

        if ( in_array( $post_type, [NEWS_POST_SLUG,EVENT_POST_SLUG] ) && ( get_query_var('category') || get_query_var( 'archive' ) == 1 ) ) { $display = false; }

        return $display;

    }

/*----------  Filtres  ----------*/

add_action( 'pc_action_template_archive_before', 'pc_display_news_events_archive_main_header_filters', 45 );

    function pc_display_news_events_archive_main_header_filters( $post_type ) {

        global $wp_query;
        if ( $wp_query->found_posts == 0 ) { return; }
        if ( !get_option('options_news_tax') || !get_option('options_events_tax') ) { return; }
        if ( !in_array( $post_type, [NEWS_POST_SLUG,EVENT_POST_SLUG] ) ) { return; }

        switch ( $post_type ) {
            case NEWS_POST_SLUG :
                $tax_slug = NEWS_TAX_SLUG;
                $modal_id = 'modal-news-filters';
                $modal_title = 'Catégories des actualités';
                break;
            case EVENT_POST_SLUG :
                $tax_slug = get_option('options_events_tax_shared') ? NEWS_TAX_SLUG : EVENT_TAX_SLUG;
                $modal_id = 'modal-events-filters';
                $modal_title = 'Catégories des événements';
                break;
        }

        $archive_url = get_post_type_archive_link( $post_type );
        $cancel_url = get_query_var( 'archive' ) == 1 ? $archive_url.'?archive=1' : $archive_url;

        $get_terms_args = array(
            'post_type' => $post_type,
            'taxonomy' => $tax_slug
        );

        if ( get_option('options_events_tax_shared') ) {
            $get_post_args = array(
                'post_type' => $post_type,
                'nopaging' => true,
                'fields' => 'ids'
            );
            if ( $post_type == EVENT_POST_SLUG ) {
                if ( get_query_var( 'archive' ) == 1 ) { // passés
                    $meta_compare = '<=';
                } else { // à venir
                    $get_post_args['order'] = 'ASC';
                    $meta_compare = '>=';
                }
                $get_post_args['meta_query'] = array(
                    array(
                        'key' => 'event_date_end',
                        'compare' => $meta_compare,
                        'value' => date_i18n( 'Y-m-d G:i:s', strtotime('now') ),
                        'type' => 'DATETIME'
                    )
                );
                $get_post_args['orderby'] = 'meta_value';
                $get_post_args['meta_key'] = 'event_date_end';
                $get_post_args['meta_type'] = 'DATETIME';
            }
            $get_terms_args['object_ids'] = get_posts( $get_post_args);
        }

        $terms = get_terms($get_terms_args);

        if ( is_array( $terms ) && !empty( $terms ) ) {

            echo '<div class="filters filters--news">';
                echo '<button type="button" class="button modal-btn-open" title="Boite de dialogue" aria-control="'.$modal_id.'"><span class="ico">'.pc_svg('tag').'</span><span class="txt">Catégories</span></button>';
                if ( get_query_var('category') ) {
                    $current_term = get_term_by( 'id', get_query_var('category'), $tax_slug );
                    echo '<a href="'.$cancel_url.'" class="button button--cancel" title="Annuler le filtre '.$current_term->name.'" aria-label="Annuler le filtre '.$current_term->name.'" rel="nofollow"><span class="ico">'.pc_svg('cross').'</span><span class="txt">'.$current_term->name.'</span></a>';
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
                                'href' => $cancel_url,
                                'title' => 'Annuler le filtre '.$term->name,
                                'aria-label' => 'Annuler le filtre '.$term->name
                            )
                        );
                    } else {
                        $href_args = array( 'category' => $term->term_id );
                        if ( get_query_var( 'archive' ) == 1 ) { $href_args['archive'] = 1; }
                        $link_attrs = array_merge( $link_attrs,
                            array(
                                'href' => $archive_url.'?'.http_build_query($href_args),
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


/*=====  FIN Filtres (archive)  =====*/

} // FIN if get_option('options_news_enabled') || get_option('options_events_enabled')