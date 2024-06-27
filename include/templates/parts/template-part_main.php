<?php
/**
 * 
 * Template : structure main
 * 
 ** Main
 ** Header & Title
 ** Content
 ** Footer
 *  
 */

/*============================
=            Main            =
============================*/

function pc_display_main_start() {

	$tag = '<main id="main" class="main" role="main" tabindex="-1">';
	if ( apply_filters( 'pc_display_main_inner', false ) ) { $tag .= '<div class="main-inner">'; }

    echo apply_filters( 'pc_filter_main_start', $tag );

}

function pc_display_main_end() {

	$tag = '</main>';
	if ( apply_filters( 'pc_display_main_inner', false ) ) { $tag = '</div>'.$tag; }

    echo apply_filters( 'pc_filter_main_end', $tag );

}


/*=====  FIN Main  =====*/

/*======================================
=            Header & Title            =
======================================*/

function pc_display_main_header_start() {

	$tag = '<header class="main-header" aria-label="Entête du contenu principal">';
	if ( apply_filters( 'pc_display_main_header_inner', false ) ) { $tag .= '<div class="main-header-inner">'; }

	echo apply_filters( 'pc_filter_main_header_start', $tag );

}

function pc_display_main_header_end() {

	$tag = '</header>';
	if ( apply_filters( 'pc_display_main_header_inner', false ) ) { $tag .= '</div>'.$tag; }
	
	echo apply_filters( 'pc_filter_main_header_end', $tag );

}


/*=====  FIN Header & Title  =====*/

/*===============================
=            Content            =
===============================*/

function pc_display_main_content_start() {

	$tag = '';
	if ( apply_filters( 'pc_display_main_content', false ) ) { $tag .= '<div class="main-content">'; }
	if ( apply_filters( 'pc_display_main_content_inner', false ) ) { $tag .= '<div class="main-content-inner">'; }

	if ( $tag ) { echo apply_filters( 'pc_filter_main_content_start', $tag ); }

}

function pc_display_main_content_end() {

	$tag = '';
	if ( apply_filters( 'pc_display_main_content', false ) ) { $tag .= '</div>'; }
	if ( apply_filters( 'pc_display_main_content_inner', false ) ) { $tag .= '</div>'; }

	if ( $tag ) { echo apply_filters( 'pc_filter_main_content_end', $tag ); }

}


/*=====  FIN Content  =====*/

/*==============================
=            Footer            =
==============================*/

function pc_display_main_footer_start() {

	$tag = '<footer class="main-footer" aria-label="Pied de page du contenu principal">';
	if ( apply_filters( 'pc_display_main_footer_inner', false ) ) { $tag .= '<div class="main-footer-inner">'; }

    echo apply_filters( 'pc_filter_main_footer_start', $tag );

}

function pc_display_main_footer_end() {

	$tag = '</footer>';
	if ( apply_filters( 'pc_display_main_footer_inner', false ) ) { $tag .= '</div>'.$tag; }

    echo apply_filters( 'pc_filter_main_footer_end', $tag );

}


/*=====  FIN Footer  =====*/