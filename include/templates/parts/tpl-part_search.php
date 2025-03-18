<?php
/**
 * 
 * Recherche
 * 
 * Formulaire
 * Nombre de résultats
 * Recherche dans les champs ACF
 * Suppression des résultats
 * 
 */


/*==================================
=            Formulaire            =
==================================*/

function pc_display_form_search( $css_suffix, $post_type = null ) {

	$args = array(
        'form_id' => $css_suffix.'-form-search',
        'form_class' => [ 'form-search', 'form-search--'.$css_suffix, 'no-print' ],
        'submit_class' => [ 'form-search-submit', 'button' ],
		'submit_ico' => pc_svg('zoom')
    );
    $args = apply_filters( 'pc_edit_form_search_args', $args, $css_suffix, $post_type );

    echo '<form id="'.$args['form_id'].'" class="'.implode(' ',$args['form_class']).'" method="get" role="search" action="'.get_bloginfo('url').'"><div class="form-search-inner">';
        echo '<label class="visually-hidden" for="'.$args['form_id'].'-input">Recherche</label>';
        echo '<input type="search" class="form-search-input" name="s" id="'.$args['form_id'].'-input" value="'.esc_html( get_search_query() ).'" placeholder="Recherche" title="Recherche" aria-invalid="false" autocomplete="off">';
        if ( $post_type ) { echo '<input type="hidden" name="cpt" value="'.$post_type.'">'; }
        echo '<button type="submit" class="'.implode(' ',$args['submit_class']).'" title="Effectuer la recherche" aria-label="Effectuer la recherche"><span class="ico">'.$args['submit_ico'].'</span></button>';
    echo '</div></form>';

}



/*=====  FIN Formulaire  =====*/

/*===========================================
=            Nombre de résultats            =
===========================================*/

function pc_get_search_count_results( $query ) {

	global $wp_query;
	$posts_count = $wp_query->found_posts;
	$post_per_page = get_option( 'posts_per_page' );

	if ( $posts_count <= $post_per_page ) {

		$txt = ( $posts_count > 1 ) ? 'résultats' : 'résultat';
		return '<strong>'.$posts_count.' '.$txt.'</strong> pour la recherche de &quot;'.$query.'&quot;';

	} else {

		$pages_count = ceil( $posts_count / get_option( 'posts_per_page' ) );
		$page_current = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;					
		return '<strong>Page '.$page_current.' sur '.$pages_count.'</strong> pour <strong>'.$posts_count.' résultats</strong> associés à la recherche de &quot;'.$query.'&quot;';

	}

}


/*=====  FIN Nombre de résultats  =====*/

/*=====================================================
=            Recherche dans les champs ACF            =
=====================================================*/

add_filter( 'posts_join', 'pc_search_join' );

	function pc_search_join( $join ) {

		global $wpdb;
		if ( is_search() ) { $join .=' LEFT JOIN '.$wpdb->postmeta. ' '.$wpdb->prefix.'metas ON '. $wpdb->posts . '.ID = '.$wpdb->prefix.'metas.post_id '; }
		return $join;

	}

add_filter( 'posts_where', 'pc_search_where' );

	function pc_search_where( $where ) {

		global $wpdb;
		if ( is_search() ) {
			$where = preg_replace(
				"/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
				"(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->prefix."metas.meta_value LIKE $1)", 
				$where
			);
		}
		return $where;

	}

add_filter( 'posts_distinct', 'pc_search_distinct' );

	function pc_search_distinct( $where ) {

		if ( is_search() ) { return "DISTINCT";	}
		return $where;

	}


/*=====  FIN Recherche dans les champs ACF  ======*/

/*=================================================
=            Suppression des résultats            =
=================================================*/

add_action( 'pre_get_posts', 'pc_search_pre_get_posts' );

	function pc_search_pre_get_posts( $query ) {

		if ( !is_admin() && is_search() ) {
			$query->set( 'post__not_in', array( get_option('page_on_front') ) );
		}

	}


/*=====  FIN Suppression des résultats  =====*/
