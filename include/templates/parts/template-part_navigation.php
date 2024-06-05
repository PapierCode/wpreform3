<?php
/**
*
* Template : navigation
*
** Emplacement menus
** Item parent actif (sous-page)
** Fil d'ariane
** Pagination
*
**/


/*=========================================
=            Emplacement menus            =
=========================================*/

add_action( 'after_setup_theme', 'pc_preform_register_nav_menus' );

	function pc_preform_register_nav_menus() {

		$nav_locations = apply_filters( 'pc_filter_nav_locations', array( 
			'nav-header' => 'Entête',
			'nav-footer' => 'Pied de page'
		) );

		register_nav_menus( $nav_locations );	

	}


/*=====  FIN Emplacement menus  =====*/

/*=====================================================
=            Item parent actif (sous-page)            =
=====================================================*/

add_filter( 'wp_nav_menu_objects', 'pc_nav_page_parent_active', NULL, 2 );

	function pc_nav_page_parent_active( $items, $args ) {

		if ( $args->theme_location == 'nav-header' ) {

			global $post;
            $all_items_ancestor = array();

            $pc_items = array();
            foreach ( $items as $item ) { $pc_items[$item->ID] = $item; }

			if ( is_singular( array( 'page' ) ) ) {

				if ( $post->post_parent ) {

					$page_parent_ids = array();
					$parent_id = wp_get_post_parent_id( $post );

					if ( $parent_id ) {
						
						// toutes les pages parentes de la page courante
						$page_parent_ids[] = $parent_id;
						while ( $parent_id ) {
							$sup_parent_id = wp_get_post_parent_id( $parent_id );
							if ( $sup_parent_id ) { $page_parent_ids[] = $sup_parent_id; }
							$parent_id = $sup_parent_id;
						}
						
						// recherche des pages parentes dans le menu
						// et recherche des ancêtres des items associés à ces pages parentes
						foreach ( $pc_items as $id => $item ) {
							if ( in_array( $item->object_id, $page_parent_ids ) && !in_array( $item->menu_item_parent, $all_items_ancestor ) ) {
								$all_items_ancestor[] = $id;
								$ancestor_id = $item->menu_item_parent;
								while ( $ancestor_id ) {
									$ancestor_id = $pc_items[$ancestor_id]->menu_item_parent;
									if ( $ancestor_id && !in_array( $ancestor_id, $all_items_ancestor ) ) {
										$all_items_ancestor[] = $ancestor_id;
									}
								}
							}
						}

					}

				}

			} // FIN if parent
            
			if ( is_singular( array( 'page', NEWS_POST_SLUG ) ) ) {

                foreach ( $items as $key => $item ) {
                    if ( $item->type == 'post_type_archive' && $item->object == $post->post_type ) {
                        if ( !in_array( 'current-menu-ancestor', $item->classes ) ) {
                            $items[$key]->classes[] = 'current-menu-ancestor';
                        }
                        $item_ancestor = $item->menu_item_parent;
                        while ( $item_ancestor ) {
                            if ( !in_array( $item_ancestor, $all_items_ancestor ) ) {
                                $all_items_ancestor[] = $item_ancestor;
                                $item_ancestor = $pc_items[$item_ancestor]->menu_item_parent;
                            }
                        }
                    }
                }

            }

            // active les ancêtres
            if ( !empty( $all_items_ancestor ) ) {
                foreach ( $items as $key => $item ) {
                    if ( in_array( $item->ID, $all_items_ancestor ) && !in_array( 'current-menu-ancestor', $item->classes ) ) {
                        $items[$key]->classes[] = 'current-menu-ancestor';
                    }
                }
            }

		}

		return $items;

	};


/*=====  FIN Item parent actif (sous-page)  ======*/

/*====================================
=            Fil d'ariane            =
====================================*/

function pc_display_breadcrumb() {

	/*----------  Lien accueil et filtre  ----------*/
	
	$links = apply_filters( 'pc_filter_breadcrumb', 
		array(
			array(
				'name' => 'Accueil',
				'permalink' => get_bloginfo('url')
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
	
	if ( is_singular() ) {

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

			$links[] = array(
				'name' => $post_object->labels->name,
				'permalink' => get_post_type_archive_link( $pc_post->type )
			);

		}

		$links[] = array(
			'name' => $pc_post->get_card_title(),
			'permalink' => $pc_post->permalink
		);

		
		if ( is_single() && get_query_var( 'paged' ) && get_query_var( 'paged' ) > 1 ) {

			$links[] = array(
				'name' => 'Page '.get_query_var( 'paged' ),
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
				'name' => 'Page '.get_query_var( 'paged' ),
				'permalink' => $pc_term->permalink.'page/'.get_query_var( 'paged' ).'/'
			); 
		}


	}


	/*----------  Recherche  ----------*/
	
	if ( is_search() ) {

		$links[] = array(
			'name' => 'Recherche',
			'permalink' => home_url().'/?s='.esc_html( get_search_query() )
		); 		

		if ( get_query_var( 'paged' ) && get_query_var( 'paged' ) > 1 ) {

			$links[] = array(
				'name' => 'Page '.get_query_var( 'paged' ),
				'permalink' => home_url().'/?s='.esc_html( get_search_query() ).'&paged='.get_query_var( 'paged' )
			); 
		}

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
	
	echo '<nav class="breadcrumb no-print" role="navigation" aria-label="Fil d\'ariane"><ol class="breadcrumb-list reset-list">';

		foreach ( $links as $key => $link ) {

			$current = ( $key == ( count($links) - 1 ) ) ? ' aria-current="page"' : '';
			echo '<li class="breadcrumb-item">'.$separator.'<a href="'.$link['permalink'].'"'.$current.'>'.$link['name'].'</a></li>';

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
			'next_text' 			=> '<span class="visually-hidden">Suivant</span>'.pc_svg( 'arrow' ),
			'prev_text' 			=> '<span class="visually-hidden">Précédent</span>'.pc_svg( 'arrow' ),
			'type' 					=> 'array',
			'before_page_number' 	=> '<span class="visually-hidden">Page </span>',
			'format'                => '?paged=%#%',
			'ul_css'				=> 'pager-list reset-list no-print' // custom
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

		$pager = '<nav class="pager" role="navigation" aria-label="Pagination"><ul class="'.$args['ul_css'].'">';

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