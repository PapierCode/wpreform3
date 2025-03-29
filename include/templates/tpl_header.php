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
	add_action( 'pc_header', 'pc_display_header_nav_secondary', 80 );
	add_action( 'pc_header', 'pc_display_header_search', 90 );

add_action( 'pc_header', 'pc_display_header_end', 100 );


/*=====  FIN Hooks  =====*/

/*=============================================
=            Liens d'accès rapides            =
=============================================*/

function pc_display_skip_nav() {
	
	$skip_nav_list = apply_filters( 'pc_filter_skip_nav', [ array( 'href' => '#main', 'label' => __('Skip to main content','wpreform') ) ] );
	$plurial = ( count( $skip_nav_list ) > 1 ) ? 's' : '';

	echo '<nav class="skip-nav no-print" role="navigation" aria-label="'.sprintf(__('Skip link%s','wpreform'),$plurial).'">';
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
		
	echo '<a href="'.get_bloginfo('url').'" class="h-logo-link" title="'.__('Homepage','wpreform').'">';

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

	echo '<button type="button" title="'.__('Open menu','wpreform').'" id="header-nav-btn" class="h-nav-btn" aria-controls="header-nav" aria-expanded="false" data-title="'.__('Close menu','wpreform').'"><span class="h-nav-btn-ico"><span class="h-nav-btn-ico h-nav-btn-ico--inner"></span></span><span class="txt">'.__('Menu','wpreform').'</span></button>';

}


/*----------  Menu secondaire  ----------*/

function pc_display_header_nav_secondary_list() {

	$nav_args = apply_filters( 'pc_filter_header_nav_secondary_list_args', array(
		'theme_location'  	=> 'nav-header-secondary',
		'nav_prefix'		=> array('h-nav', 'h-s-nav'),
		'menu_class'      	=> 'h-nav-list h-nav-list--l1 h-s-nav-list h-s-nav-list--l1',
		'items_wrap'      	=> '<ul class="%2$s">%3$s</ul>',
		'depth'           	=> apply_filters( 'pc_filter_header_nav_secondary_depth', 1 ),
		'container'       	=> '',
		'item_spacing'		=> 'discard',
		'fallback_cb'     	=> false,
		'walker'          	=> new PC_Walker_Nav_Menu()
	) );

	wp_nav_menu( $nav_args ); // + include/navigation.php

}

function pc_display_header_nav_secondary() {

	if ( !get_option('options_nav_secondary_enabled') ) { return; }

	echo '<nav class="h-s-nav" role="navigation" aria-label="'.__('Secondary navigation','wpreform').'"><div class="h-s-nav-inner">';
	
		do_action( 'pc_header_nav_secondary_list_before' );

		pc_display_header_nav_secondary_list();
		
		do_action( 'pc_header_nav_secondary_list_after' );

	echo '</div></nav>';

}


/*----------  Menu principal  ----------*/

function pc_display_header_nav() {

	echo '<nav class="h-nav" role="navigation" aria-label="'.__('Primary navigation','wpreform').'"><div class="h-nav-inner">';
	
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

		if ( get_option('options_nav_secondary_enabled') ) {
			do_action( 'pc_header_nav_list_between' );
			pc_display_header_nav_secondary_list();
		}
		
		do_action( 'pc_header_nav_list_after' );

	echo '</div></nav>';

}


/*=====  FIN Navigation  =====*/

/*=================================
=            Recherche            =
=================================*/

function pc_display_header_search() {

	if ( get_option('options_search_enabled') ) {
		
		echo '<button type="button" class="button h-btn-toggle-search" aria-label="'.__('Display search form','wpreform').'" aria-expanded="false"><span class="ico">'.pc_svg('zoom').'</span><span class="ico ico--close">'.pc_svg('cross').'</span><span class="txt">'.__('Search','wpreform').'</span></button>';

		$box_attrs = array( 'id' => 'header-form-search-box' );
		if ( get_option('options_search_desktop_hidden') ) { $box_attrs['class'] = 'desktop-hidden'; }
		echo '<div '.pc_get_attrs_to_string( $box_attrs ).'>';
			pc_display_form_search( 'header' );
		echo '</div>';
		
	}

}


/*=====  FIN Recherche  =====*/