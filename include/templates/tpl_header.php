<?php 
/**
 * 
 * Template : header
 * 
 * Hooks
 * Liens d'accès rapides
 * Container
 * Logo
 * Navigation
 * 
 */


/*=============================
=            Hooks            =
=============================*/

add_action( 'pc_header', 'pc_display_skip_nav', 10 );

add_action( 'pc_header', 'pc_display_header_start', 40 );

	add_action( 'pc_header', 'pc_display_header_logo', 50 );
	add_action( 'pc_header', 'pc_display_nav_button_open_close', 60 );
	add_action( 'pc_header', 'pc_display_header_nav', 70 );
	// TODO add_action( 'pc_header', 'pc_display_header_tools', 80 );

add_action( 'pc_header', 'pc_display_header_end', 90 );


/*=====  FIN Hooks  =====*/

/*=============================================
=            Liens d'accès rapides            =
=============================================*/

function pc_display_skip_nav() {
	
	$skip_nav_list = array(
		array( 'href' => '#main', 'label' => 'Contenu' )
	);

	$skip_nav_list = apply_filters( 'pc_filter_skip_nav', $skip_nav_list );

	$plurial = ( count( $skip_nav_list ) > 1 ) ? 's' : '';
	echo '<nav class="skip-nav no-print" role="navigation" aria-label="Lien'.$plurial.' d\'accès rapide'.$plurial.'">';
		if ( count( $skip_nav_list ) > 1 ) {
			echo '<ul class="skip-nav-list">';
				foreach ( $skip_nav_list as $args ) {
					echo '<li><a class="skip-nav-link" href="'.$args['href'].'">'.$args['label'].'</a></li>';
				}
			echo '</ul>';
		} else {
			echo '<a class="skip-nav-link" href="'.$skip_nav_list[0]['href'].'">'.$skip_nav_list[0]['label'].'</a>';
		}	
	echo '</nav>';

}


/*=====  FIN Liens d'accès rapides  =====*/

/*=================================
=            Container            =
=================================*/

function pc_display_header_start() {

	$tag = apply_filters( 'pc_filter_header_start', '<header class="header" role="banner">' );
	if ( apply_filters( 'pc_filter_display_header_inner', false ) ) {
		$tag .= apply_filters( 'pc_filter_header_inner_start', '<div class="header-inner">' );
	}

	echo $tag;

}

function pc_display_header_end() {

	$tag = apply_filters( 'pc_filter_header_end', '</header>' );
	if ( apply_filters( 'pc_filter_display_header_inner', false ) ) {
		$tag_inner = apply_filters( 'pc_filter_header_inner_end', '</div>' );
		$tag = $tag_inner.$tag;
	}

	echo $tag;

}


/*=====  FIN Container  =====*/

/*============================
=            Logo            =
============================*/

function pc_display_header_logo() {
		
	echo '<a href="'.get_bloginfo('url').'" class="h-logo-link" title="Page d\'accueil">';

		$logo_datas = apply_filters( 'pc_filter_header_logo_img_datas', array(
			'url' => get_bloginfo('template_directory').'/images/logo.svg',
			'width' => 150,
			'height' => 150,
			'alt' => get_option( 'options_coord_name' )
		) );

		$logo_tag = apply_filters(
			'pc_filter_header_logo_img_tag',
			'<img class="h-logo-img" src="'.$logo_datas['url'].'" alt="'.$logo_datas['alt'].'" width="'.$logo_datas['width'].'" height="'.$logo_datas['height'].'" loading="lazy">',
			$logo_datas
		);
		
		echo $logo_tag;

	echo '</a>';

}


/*=====  FIN Logo  =====*/

/*==================================
=            Navigation            =
==================================*/

/*----------  Bouton menu caché  ----------*/

function pc_display_nav_button_open_close() {

	echo '<button type="button" title="Ouvrir le menu" id="header-nav-btn" class="h-nav-btn" aria-controls="header-nav" aria-expanded="false" data-title="Fermer le menu"><span class="txt">Menu</span><span class="h-nav-btn-ico"><span class="h-nav-btn-ico h-nav-btn-ico--inner"></span></span></button>';

}


/*----------  Menu  ----------*/

function pc_display_header_nav() {

	echo '<nav class="h-nav" role="navigation" aria-label="Navigation principale"><div class="h-nav-inner">';
	
		do_action( 'pc_header_nav_list_before' );

		$nav_args = apply_filters( 'pc_filter_header_nav_list_args', array(
			'theme_location'  	=> 'nav-header',
			'nav_prefix'		=> array('h-nav', 'h-p-nav'),
			'menu_class'      	=> 'h-nav-list h-nav-list--l1 h-p-nav-list h-p-nav-list--l1',
			'items_wrap'      	=> '<ul class="%2$s">%3$s</ul>',
			'depth'           	=> apply_filters( 'pc_filter_header_nav_depth', 1 ),
			'container'       	=> '',
			'item_spacing'		=> 'discard',
			'fallback_cb'     	=> false,
			'walker'          	=> new PC_Walker_Nav_Menu()
		) );

		wp_nav_menu( $nav_args ); // + include/navigation.php
		
		do_action( 'pc_header_nav_list_after' );

	echo '</div></nav>';

}


/*=====  FIN Navigation  =====*/

/*=============================
=            Tools            =
=============================*/

// function pc_display_header_tools() {

// 	$items = array();

// 	$search_ico = apply_filters( 'pc_filter_header_tools_search_icon', pc_svg( 'zoom' ) );
// 	$items['search'] = array(
// 		'attrs' => '',
// 		'html' => '<a href="'.get_bloginfo('url').'/?s" class="h-tools-link"><span class="txt">Recherche</span><span class="ico">'.$search_ico.'</span></a>'
// 	);

// 	$items = apply_filters( 'pc_filter_header_tools', $items );

// 	if ( count( $items ) > 0 ) {

// 		echo '<nav class="h-tools"><div class="h-tools-inner"><ul class="h-tools-list">';

// 			foreach ( $items as $id => $args ) {
// 				echo '<li class="h-tools-item h-tools-item--'.$id.'" '.$args['attrs'].'>'.$args['html'].'</li>';
// 			}

// 		echo '</ul></div></nav>';

// 	}

// }


/*=====  FIN Tools  =====*/