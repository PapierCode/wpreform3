<?php
/**
*
* Template navigations
*
* Emplacement menus
* Item parent actif (sous-page)
* Fil d'ariane
* Pagination
*
**/


/*=========================================
=            Emplacement menus            =
=========================================*/

add_action( 'after_setup_theme', 'pc_preform_register_nav_menus' );

	function pc_preform_register_nav_menus() {

		$nav_locations = array( 
			'nav-header' => 'Entête principale',
			'nav-footer' => 'Pied de page'
		);

		if ( get_option('options_nav_secondary_enabled') ) {
			$nav_locations['nav-header-secondary'] = 'Entête secondaire';
		}

		$nav_locations = apply_filters( 'pc_filter_nav_locations', $nav_locations );
		asort($nav_locations);
		register_nav_menus( $nav_locations );	

	}


/*=====  FIN Emplacement menus  =====*/

/*=========================================
=            Item parent actif            =
=========================================*/

add_filter( 'wp_nav_menu_objects', 'pc_nav_page_parent_active', NULL, 2 );

	function pc_nav_page_parent_active( $items, $args ) {

		if ( $args->theme_location == 'nav-header' ) {

			if ( is_single() ) {

				// active l'item archive
				global $ancestor;
				$ancestor = null;
				array_walk( $items, function( $item ) {
					global $pc_post, $ancestor;
					if ( $item->type == 'post_type_archive' && $item->object == $pc_post->type ) {
						$item->classes[] = 'current-'.$item->object.'-ancestor';
						$ancestor = $item->menu_item_parent;
					}
				} );
				
				// active les items parents
				while ( $ancestor ) {
					array_walk( $items, function( $item ) {
						global $ancestor;
						if ( $item->ID == $ancestor ) {
							$item->classes[] = 'current-'.$item->object.'-ancestor';
							$ancestor = $item->menu_item_parent;
						}
					} );
				}

			} // FIN is_single

		}

		return $items;

	};


/*=====  FIN Item parent actif  ======*/

/*====================================
=            Fil d'ariane            =
====================================*/

function pc_display_breadcrumb() {

	if ( is_front_page() ) { return; }

	/*----------  Lien accueil et filtre  ----------*/
	
	$links = apply_filters( 'pc_filter_breadcrumb', 
		array(
			array(
				'name' => __('Homepage','wpreform'),
				'ico' => pc_svg('house'),
				'permalink' => get_bloginfo('url'),
				'aria-label' => __('Homepage','wpreform')
			)
		)
	);

    /*----------  Archives  ----------*/
    
    if ( is_post_type_archive() ) {

        $links[] = array(
            'name' => post_type_archive_title( '', false ),
            'permalink' => get_post_type_archive_link( get_query_var('post_type') )
        );

    }
	

	/*----------  Single  ----------*/
	
	if ( is_singular() && !is_front_page() ) {

		global $pc_post;

		if ( is_page() ) {

			$parent_id = $pc_post->parent;
			$parent_links = array();

			while ( $parent_id ) {
				$pc_post_parent = new PC_Post( get_post( $parent_id ) );
				$parent_links[] = array(
					'name' => $pc_post_parent->get_card_title(),
					'permalink' => $pc_post_parent->permalink
				);
				$parent_id = $pc_post_parent->parent;
			}

			if ( !empty( $parent_links ) ) {
				$links = array_merge( $links, array_reverse( $parent_links ) );
			}

		}

		if ( is_single() ) {

			$post_object = get_post_type_object( $pc_post->type );

			if ( $post_object->has_archive ) {
				$links[] = array(
					'name' => $post_object->labels->name,
					'permalink' => get_post_type_archive_link( $pc_post->type )
				);
			}

		}

		$links[] = array(
			'name' => $pc_post->get_card_title(),
			'permalink' => $pc_post->permalink
		);

		
		if ( is_single() && get_query_var( 'paged' ) && get_query_var( 'paged' ) > 1 ) {

			$links[] = array(
				'name' => __('Page','wpreform').' '.get_query_var( 'paged' ),
				'permalink' => $pc_post->get_canonical()
			);

		}

	}


	/*----------  Taxonomy  ----------*/
	
	if ( is_tax() ) {

		global $pc_term;

		if ( $pc_term->parent > 0 ) {
			
			$term_parent_id = $pc_term->parent;
			$links_term = array();

			while ( $term_parent_id > 0 ) {

				$pc_term_parent = new PC_Term( get_term_by( 'term_taxonomy_id', $term_parent_id ) );
				array_unshift( $links_term, array(
					'name' => $pc_term_parent->name,
					'permalink' => $pc_term_parent->permalink
				));

				$term_parent_id = ( $pc_term_parent->parent > 0 ) ? $pc_term_parent->parent : 0;

			}

			$links = array_merge( $links, $links_term );

		}

		$links[] = array(
			'name' => $pc_term->name,
			'permalink' => $pc_term->permalink
		); 

		if ( get_query_var( 'paged' ) && get_query_var( 'paged' ) > 1 ) {

			$links[] = array(
				'name' => __('Page','wpreform').' '.get_query_var( 'paged' ),
				'permalink' => $pc_term->permalink.'page/'.get_query_var( 'paged' ).'/'
			); 
		}


	}


	/*----------  Recherche  ----------*/
	
	if ( is_search() ) {

		$links[] = array(
			'name' => __('Search','wpreform'),
			'permalink' => home_url().'/?s='.esc_html( get_search_query() )
		); 		

		if ( get_query_var( 'paged' ) && get_query_var( 'paged' ) > 1 ) {

			$links[] = array(
				'name' => __('Page','wpreform'),' '.get_query_var( 'paged' ),
				'permalink' => home_url().'/?s='.esc_html( get_search_query() ).'&paged='.get_query_var( 'paged' )
			); 
		}

	}

	/*----------  404  ----------*/
	
	if ( is_404() ) {
		
		$links[] = array(
			'name' => __('Page not found','wpreform'),
			'permalink' => $_SERVER['REQUEST_URI']
		); 	

	}

	
	/*----------  Séparateur  ----------*/
	
	$separator = apply_filters( 'pc_filter_breadcrumb_ico', '<span class="breadcrumb-ico" aria-hidden="true">'.pc_svg('arrow').'</span>' );


	/*----------  Filtre  ----------*/

	$links = apply_filters( 'pc_filter_breadcrumb_before_display', $links );
	

	/*----------  Données structurées  ----------*/
	
	$structured_datas = array(
		'@context' => 'https://schema.org',
		'@type' => 'BreadcrumbList',
		'itemListElement' => array()
	);


	/*----------  Affichage  ----------*/
	
	echo '<nav class="breadcrumb no-print" role="navigation" aria-label="'.__('Breadcrumbs','wpreform').'"><ol class="breadcrumb-list">';

		foreach ( $links as $key => $link ) {

			$current = ( $key == ( count($links) - 1 ) ) ? ' aria-current="page"' : '';
			$aria_label = ( isset( $link['aria-label'] ) ) ? ' aria-label="'.$link['aria-label'].'"' : '';
			echo '<li class="breadcrumb-item">'.$separator.'<a class="breadcrumb-link" href="'.$link['permalink'].'"'.$current.$aria_label.'>';
				echo isset( $link['ico'] ) && $link['ico'] ? $link['ico'] : $link['name'];
			echo '</a></li>';

			$structured_datas['itemListElement'][] = array(
				'@type' => 'ListItem',
				'position' => $key + 1,
				'name' => $link['name'],
				'item' => $link['permalink']
			);
			
		}

	echo '</ol>';
	echo '<script type="application/ld+json">'.json_encode($structured_datas,JSON_UNESCAPED_SLASHES).'</script>';
	echo '</nav>';

}


/*=====  FIN Fil d'ariane  =====*/

/*=============================
=            Pager            =
=============================*/

/**
 * 
 * @param object	$query		WP requête
 * @param integer	$current	Numéro de page courante
 * @param string	$args		Arguments pour paginate_links()
 * 
 */

function pc_display_pager( $query = null, $current = null, $args = array() ) {

	// fusion des arguments
    $args = array_merge(
		array(
			'mid_size'				=> 0,
			'end_size'				=> 0,
			'next_text' 			=> '<span class="visually-hidden">'.__('Next','wpreform').'</span>'.pc_svg( 'arrow' ),
			'prev_text' 			=> '<span class="visually-hidden">'.__('Previous','wpreform').'</span>'.pc_svg( 'arrow' ),
			'type' 					=> 'array',
			'before_page_number' 	=> '<span class="visually-hidden">'.__('Page','wpreform').' </span>',
			'format'                => '?paged=%#%',
			'ul_css'				=> 'pager-list no-print',
			'before_page_number'	=> '<span class="txt">',
			'after_page_number'		=> '</span>',
		),
		$args
	);

	// si requête custom
    if ( is_object( $query ) && $current ) {

        $args['total'] = $query->max_num_pages;
        $args['current'] = $current;

    }

    // tableau contenant chaque élément (liens et '...')
    $paginate_links = paginate_links( $args );

    // affichage
    if ( isset( $paginate_links ) && count( $paginate_links ) > 0 ) {
		
		$css_old = array( 'page-numbers', 'prev', 'current', 'dots', 'next' );
		$css_new = array( 'pager-link', 'pager-link--prev', 'is-active', 'pager-dots', 'pager-link--next' );

		$pager = '<nav class="pager" role="navigation" aria-label="'.__('Pagination','wpreform').'"><ul class="'.$args['ul_css'].'">';

        foreach ( $paginate_links as $page ) {

            $page = str_replace( $css_old, $css_new, $page );
            $page = str_replace( 'aria-is-active', 'aria-current', $page );
			$pager .= '<li class="pager-item">'.$page.'</li>';
			
		}
		
        $pager .= '</ul></nav>';

        echo $pager;

    }

}


/*=====  FIN Pager  =====*/