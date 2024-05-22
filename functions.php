<?php
/**
*
** Réglages projet
** Slug custom post & tax
** Variables utiles
** Includes
** Expérimentations
*
**/


include 'include/tools.php';
include 'include/init.php';
include 'include/register.php';

/*----------  Classes  ----------*/

include 'include/classes/class-pc-walker-nav.php';
include 'include/classes/class-pc-post.php';


/*----------  Configuration projet (agence)  ----------*/

$texts_lengths = array(
	'excerpt' => 100, // mots
	'resum-title' => 40, // signes
	'resum-desc' => 150, // signes
	'seo-title' => 70, // signes
	'seo-desc' => 200 // signes
);
$texts_lengths = apply_filters( 'pc_filter_texts_lengths', $texts_lengths );


/*----------  Rôle utilisateur connecté  ----------*/

$current_user_role = ( is_user_logged_in() ) ? wp_get_current_user()->roles[0] : '';

add_filter( 'admin_body_class', 'pc_edit_admin_body_class' );

	function pc_edit_admin_body_class( $classes ) {

		global $current_user_role;
		$classes .= ' role_'.$current_user_role;

		return $classes;

	}


/*----------  Administration  ----------*/

include 'include/admin/admin.php';


/*----------  Templates  ----------*/

include 'include/templates/templates.php';
