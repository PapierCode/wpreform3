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

add_action( 'pc_action_template_404', 'pc_display_main_start', 10 ); // template-part_layout.php

	add_action( 'pc_action_template_404', 'pc_display_main_header_start', 20 ); // template-part_layout.php
		add_action( 'pc_action_template_404', 'pc_display_breadcrumb', 30 ); // template-part_navigation.php
		add_action( 'pc_action_template_404', 'pc_display_404_main_title', 30 );
	add_action( 'pc_action_template_404', 'pc_display_main_header_end', 40 ); // template-part_layout.php

add_action( 'pc_action_template_404', 'pc_display_main_end', 50 ); // template-part_layout.php


/*=====  FIN Hooks  =====*/

/*========================================
=            Titre de la page            =
========================================*/

function pc_display_404_main_title() {

	echo apply_filters( 'pc_filter_404_main_title', '<h1><span>Cette page n\'existe pas.</span></h1>' );

} 


/*=====  FIN Titre de la page  =====*/