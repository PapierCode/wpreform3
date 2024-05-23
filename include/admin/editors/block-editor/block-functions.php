<?php

/*===============================
=            Boutons            =
===============================*/

function pc_acf_block_get_bloc_btn_attrs( $fields, $index, $is_preview ) {

	if ( !trim( $fields['bloc_btn_txt_'.$index] ) ) { 

		return 'saisissez le texte du bouton '.$index;

	} else {

		switch ( $fields['bloc_btn_type_'.$index] ) {
			case 'page' :
				$ico = 'arrow';
				break;
			case 'file' :
				$ico = 'download';
				break;
			case 'url' :
				$ico = 'external';
				break;
			case 'phone' :
				$ico = 'phone';
				break;
		}

		$txt = trim( $fields['bloc_btn_txt_'.$index] );
		$attrs = array( 'class="button button--'.$ico.'"' );

		if ( !$is_preview ) {
			switch ( $fields['bloc_btn_type_'.$index] ) {
				case 'page':
					if ( !$fields['bloc_btn_post_'.$index] ) { return 'sélectionnez une publication pour le bouton '.$index; }
					else if ( !get_post_status( $fields['bloc_btn_post_'.$index] ) ) { return 'la publication n\'existe pas pour le bouton '.$index; }
					else { 
						$attrs[] = 'href="'.get_the_permalink( $fields['bloc_btn_post_'.$index] ).'"';
					}
					break;
				case 'file':
					if ( !$fields['bloc_btn_file_'.$index] ) { return 'sélectionnez un fichier pour le bouton '.$index; }
					else if ( !get_post_status( $fields['bloc_btn_file_'.$index] ) ) { return 'le fichier n\'existe pas pour le bouton '.$index; }
					else {
						$attrs[] = 'href="'.wp_get_attachment_url( $fields['bloc_btn_file_'.$index] ).'"';			
						$attrs[] = 'download';
						$attrs[] = 'aria-label="'.$txt.' (téléchargement)"';
					}
					break;
				case 'url':
					if ( !$fields['bloc_btn_url_'.$index] ) { return 'saisissez une URL pour le bouton '.$index; }
					else if ( !filter_var( $fields['bloc_btn_url_'.$index], FILTER_VALIDATE_URL ) ) { return 'l\'url n\'est pas valide pour le bouton '.$index; }
					else {
						$attrs[] = 'href="'.$fields['bloc_btn_url_'.$index].'"';
						$attrs[] = 'target="_blank"';
						$attrs[] = 'aria-label="'.$txt.' (nouvelle fenêtre)"';
					}
					break;
			}
		}

		return array(
			'type' => $fields['bloc_btn_type_'.$index],
			'txt' => $txt,
			'ico' => pc_svg($ico),
			'attrs' => $attrs
		);

	}

}


/*=====  FIN Boutons  =====*/