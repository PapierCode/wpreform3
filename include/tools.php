<?php

/*=======================================================
=            Afficher un tableau ou un objet            =
=======================================================*/

/**
 * 
 * @param n/a		$var		Donnée à afficher : chaine de caractères, variable, tableau, object,...
 * @param boolean	$margin		Marge à gauche pour un affichage dans l'administration WP
 * 
 */

 function pc_var( $var, $margin = false ) {

	$margin == true ? $style = 'style="margin-left:200px"' : $style = ''; // navigation admin
	echo '<pre '.$style.'>'.print_r( $var,true ).'</pre>';

}


/*=====  FIN Afficher un tableau ou un objet  =====*/

/*======================================================
=            Limite du nombre de caractères            =
======================================================*/

/**
 * 
 * @param string    $txt		Texte à couper
 * @param integer	$limit		Nombre de caratères maximum
 * 
 */

function pc_words_limit( $txt, $limit ) {

    $length = mb_strlen($txt,'utf-8');

    if ($length > $limit) {

        $temp = mb_substr( $txt,0, $limit, 'utf-8' );
        $last_space = mb_strripos( $temp, ' ', 0, 'utf-8' );

        return mb_substr( $temp, 0, $last_space, 'utf-8') . '&hellip;';

    } else {

        return $txt;

    }

}


/*=====  FIN Limite du nombre de caractères  =====*/

/*===============================================
=            Textarea to paragraphes            =
===============================================*/

/**
 * 
 * @param string    $txt		Texte à formater
 * 
 */

function pc_textearea_to_paragraphs( $txt ) {

	$txt_to_array = explode( PHP_EOL, $txt );
	$txt_formated = '';

	foreach ( $txt_to_array as $p ) {
		if ( !empty( $p ) ) { $txt_formated .= '<p>'.$p.'</p>'; }
	}

	return $txt_formated;

}


/*=====  FIN Textarea to paragraphes  =====*/

/*=========================================================
=            Téléphone au format international            =
=========================================================*/

/**
 * 
 * @param string	$tel				Numéro de téléphone au format "00 00 00 00 00"
 * @param boolean	$href				Distiné à l'attribut href
 * @param boolean	$prefix_display		Affichage du préfixe
 * @param string	$prefix				Préfixe international
 * 
 */

 function pc_phone( $tel, $href = true, $prefix_display = false, $prefix = '+33' ) {

	if ( $href ) {

		$tel = str_replace( ' ', '', $tel );
		$tel = $prefix.substr( $tel, 1, strlen($tel) );

	} else if ( $prefix_display ) {
		
		$tel = $prefix.' '.substr( $tel, 1, strlen($tel) );

	}

	return $tel;

}


/*=====  FIN Téléphone au format international  =====*/

/*===============================
=            Message            =
===============================*/

/**
 * 
 * @param string	$msg		Texte à afficher
 * @param string	$type		Type de message : "error" ou "success"
 * @param string	$format		Format d'affichage : vide ou "block"
 * @param string	$elt		Élément HTML contenant
 * 
 */

 function pc_display_alert_msg( $msg, $type = '', $format = '', $elt = 'p' ) {

	// defaut
	$css = 'msg';
	// type block
	$css .= ( $format == 'block' ) ? ' msg--block' : '';
	// erreur ou succès
	if ( $type == 'error' ) { $css .= ' msg--error'; }
	else if ( $type == 'success' ) { $css .= ' msg--success'; }

	// affichage
	$return = '<'.$elt.' class="'.$css.'">';
	$return .= '<span class="msg-ico">'.pc_svg( 'msg', '', 'svg-block' ).'</span>';
	$return .= '<span class="msg-txt">'.$msg.'</span>';
	$return .= '</'.$elt.'>';

	return $return;

}


/*=====  FIN Message  =====*/