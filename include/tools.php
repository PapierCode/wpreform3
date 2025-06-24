<?php
/**
 * 
 * Fonctions utiles
 * 
 * pc_var
 * pc_svg
 * pc_get_text_cut
 * pc_get_textarea_to_paragraphs
 * pc_get_markdown
 * pc_get_phone_format
 * pc_get_message
 * pc_get_attrs_to_string
 * pc_display_modal
 * 
 */


/*=============================================
=            Afficher une variable            =
=============================================*/

/**
 * 
 * @param n/a		$var		Donnée à afficher : chaine de caractères, variable, tableau, object,...
 * @param boolean	$margin		Marge à gauche pour un affichage dans l'administration WP
 * 
 */

function pc_var( $var, $margin = false ) {

	$margin == true ? $style = 'style="margin-left:200px"' : $style = ''; // admin
	echo '<pre '.$style.'>'.print_r( $var, true ).'</pre>';

}


/*=====  FIN Afficher une variable  =====*/

/*===============================
=            Get SVG            =
===============================*/

/**
 * 
 * @param string	$index		Index du tableau $sprite
 * 
 */

function pc_svg( $index ) {

	global $sprite; // cf. images/sprite.php
	$svg = $sprite[$index];

	$svg = str_replace('<svg', '<svg class="no-print"', $svg);
	$svg = str_replace('<svg', '<svg aria-hidden="true" focusable="false"', $svg);

	return $svg;

}

/*=====  FIN Get SVG  =====*/

/*======================================================
=            Limite du nombre de caractères            =
======================================================*/

/**
 * 
 * @param string    $txt		Texte à couper
 * @param integer	$limit		Nombre de caractères maximum
 * 
 */

function pc_get_text_cut( $txt, $limit ) {

    if ( mb_strlen( $txt, 'utf-8' ) > $limit ) {

        $txt = mb_substr( $txt, 0, $limit, 'utf-8' );
        $last_space = mb_strripos( $txt, ' ', 0, 'utf-8' );
        $txt = mb_substr( $txt, 0, $last_space, 'utf-8' ) . '&hellip;';

    }

	return $txt;

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

function pc_get_textarea_to_paragraphs( $txt ) {

	$paragraphs = '';
	foreach ( explode( PHP_EOL, $txt ) as $p ) {
		if ( !empty( $p ) ) { $paragraphs .= '<p>'.$p.'</p>'; }
	}

	return $paragraphs;

}


/*=====  FIN Textarea to paragraphes  =====*/

/*================================
=            Markdown            =
================================*/

/**
 * 
 * @param string    $txt			Texte où remplace [] et {}
 * @param bool    	$remove_all		Supprime tous les [] et {}
 * 
 */

function pc_get_markdown( $txt, $remove_all = false ) {

	if ( !$remove_all ) {
		$txt = preg_replace( '/\[+(.*?)\]+/u', '<strong>$1</strong>', $txt );
		$txt = preg_replace( '/\{+(.*?)\}+/u', '<em>$1</em>', $txt );
	}
	$txt = preg_replace( '/\{+|\}+|\[+|\]+/u', '', $txt );  
	return $txt;

}


/*=====  FIN Markdown  =====*/

/*========================================
=            Format téléphone            =
========================================*/

/**
 * 
 * @param string	$tel				Numéro de téléphone au format "00 00 00 00 00"
 * @param boolean	$href				Distiné à l'attribut href
 * @param boolean	$prefix_display		Affichage du préfixe
 * @param string	$prefix				Préfixe international
 * 
 */

 function pc_get_phone_format( $phone, $href = true, $prefix_display = false, $prefix = '+33' ) {

	if ( $href ) {

		$phone = str_replace( ' ', '', $phone );
		$phone = $prefix.substr( $phone, 1, strlen($phone) );

	} else if ( $prefix_display ) {
		
		$phone = $prefix.' '.substr( $phone, 1, strlen($phone) );

	}

	return $phone;

}


/*=====  FIN Format téléphone  =====*/

/*===============================
=            Message            =
===============================*/

/**
 * 
 * @param string	$content		Texte à afficher
 * @param string	$type			Type de message : "error" ou "success"
 * @param string	$bloc			Format d'affichage : vide ou "block"
 * @param string	$tag			Élément HTML contenant
 * 
 */

 function pc_get_message( $content, $type = 'default', $bloc = true, $tag = 'p' ) {

	$css = 'msg';
	$css .= $bloc ? ' msg--block' : '';
	$css .= ' msg--'.$type;

	$message = '<'.$tag.' class="'.$css.'">';
		$message .= '<span class="msg-ico">'.pc_svg( 'msg', '', 'svg-block' ).'</span>';
		$message .= '<span class="msg-txt">'.$content.'</span>';
	$message .= '</'.$tag.'>';

	return $message;

}


/*=====  FIN Message  =====*/

/*===================================================================
=            Tableau d'attributs en chaine de caractères            =
===================================================================*/

/**
 * 
 * @param array		$attrs		Tableau associatif à convertir
 * 								attribut => string contenu de l'attribut
 * 
 */

function pc_get_attrs_to_string( $attrs ) {

	$attrs = array_map(
		function( $k, $v ) { return $k.'="'.$v.'"'; }, 
		array_keys( $attrs ),
		array_values( $attrs )
	);

	return implode( ' ', $attrs );

}


/*=====  FIN Tableau d'attributs en chaine de caractères  =====*/

/*==============================
=            Modale            =
==============================*/

/**
 * 
 * @param string	$modal		Tableau associatif
 * 								id => string attribut id
 * 								label => string attribut aria-label
 * 								content => string contenu de la modale
 * 
 */

function pc_display_modal( $modal ) {

    echo '<dialog id="'.$modal['id'].'" class="modal" autofocus aria-label="'.$modal['label'].'"><div class="modal-inner">';
        echo '<button type="button" class="modal-btn-close button" title="'.__('Close dialog box','wpreform').'" aria-label="'.__('Close dialog box','wpreform').'"><span class="ico">'.pc_svg('cross').'</span></button>';
        echo $modal['content'];
    echo '</div></dialog>';

}


/*=====  FIN Modale  =====*/

/*==============================
=            Bouton            =
==============================*/

/**
 * 
 * @param string	$txt		Texte du bouton
 * @param array		$attrs		Attributs nom => valeur
 * @param string	$ico		identifiant svg
 * @param string	$tag		a / button
 * 
 */

function pc_get_button( $txt, $attrs = [], $ico = '', $tag = 'a' ) {

    $attrs['class'] = !array_key_exists( 'class', $attrs ) ?  'button' : 'button '.$attrs['class'];

    $button = '<'.$tag.' '.pc_get_attrs_to_string($attrs).'>';

        if ( $ico ) { $button .= '<span class="ico">'.pc_svg($ico).'</span>'; }

        // supp texte au lieu de le masquer ?
        $txt_class = str_contains( $attrs['class'], 'button--ico' ) ? 'visually-hidden' : 'txt';
        $button .= '<span class="'.$txt_class.'">'.$txt.'</span>';

    $button .= '</'.$tag.'>';

	return $button;

}


/*=====  FIN Bouton  =====*/