<?php 
/**
 * 
 * Template : 404
 * 
 * Hooks
 * Title
 * 
 */

/*=============================
=            Hooks            =
=============================*/

add_action( 'pc_action_template_404', 'pc_display_main_start', 10 ); // tpl-part_layout.php

	add_action( 'pc_action_template_404', 'pc_display_main_header_start', 20 ); // tpl-part_layout.php
		add_action( 'pc_action_template_404', 'pc_display_breadcrumb', 30 ); // tpl-part_navigation.php
		add_action( 'pc_action_template_404', 'pc_display_404_main_title', 30 );
	add_action( 'pc_action_template_404', 'pc_display_main_header_end', 40 ); // tpl-part_layout.php

add_action( 'pc_action_template_404', 'pc_display_main_end', 50 ); // tpl-part_layout.php


/*=====  FIN Hooks  =====*/

/*=============================
=            Titre            =
=============================*/

function pc_display_404_main_title() {

	echo apply_filters( 'pc_filter_404_main_title', '<h1>'.__('This page does not exist','wpreform').'</h1>' );

} 


/*=====  FIN Titre   =====*/