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

function pc_display_form_search( $context ) {

		$txts = apply_filters( 'pc_filter_search_form_txts', array(
			'form-aria-label' => 'Formulaire de recherche',
			'input-label' => 'Mots-clés',
			'input-placeholder' => '',
			'submit-title' => 'Rechercher ces mots-clés',
			'submit-txt' => 'Rechercher'
		), $context);

		$ico = apply_filters( 'pc_filter_search_form_submit_ico', pc_svg('zoom'), $context );

		echo '<form id="form-search-'.$context.'" class="form-search form-search--'.$context.'" method="get" role="search" aria-label="'.$txts['form-aria-label'].'" action="'.get_bloginfo('url').'">';
			echo '<label class="form-search-label" for="form-search-input">'.$txts['input-label'].'</label>';
			echo '<input type="text" class="form-search-input" name="s" id="form-search-input" value="'.esc_html( get_search_query() ).'" required  aria-invalid="false" autocomplete="off" placeholder="'.$txts['input-placeholder'].'">';
			echo '<button type="submit" class="form-search-submit button" title="'.$txts['submit-title'].'"><span class="ico">'.$ico.'</span><span class="txt">'.$txts['submit-txt'].'</span></button>';
		echo '</form>';

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
