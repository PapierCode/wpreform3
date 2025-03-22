<?php
/**
 * 
 * Communs templates : réseaux sociaux
 * 
 * Liens
 * Partage
 * 
 */


/*=============================
=            Liens            =
=============================*/

function pc_display_social_links( $css_class ) {

	$rs_list = get_field('coord_social','option');

	echo '<ul class="social-list no-print '.$css_class.'">';
	
	foreach( $rs_list as $rs ) {

		echo '<li class="social-item"><a class="social-link social-link--'.$rs['ico']['value'].'" href="'.$rs['url'].'" target="_blank" rel="noreferrer" title="Suivez-nous sur '.$rs['ico']['label'].' (nouvelle fenêtre)"><span class="ico">'.pc_svg($rs['ico']['value']).'</span></a></li>';	

	}

	echo '</ul>';	

}
 
 
/*=====  FIN Liens  =====*/

/*===============================
=            Partage            =
===============================*/

function pc_display_share_links() {

	// données page courante
	if ( is_singular() ) {
		global $pc_post;
		$url_to_share = $pc_post->permalink;
	} else {
		$url_to_share = 'https://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}

	$url_to_share = urlencode( $url_to_share );
	$hrefs = apply_filters( 'pc_filter_share_links', array(
		'Facebook' 	=> 'https://www.facebook.com/sharer/sharer.php?u='.$url_to_share,
		'X' 		=> 'https://x.com/intent/tweet?url='.$url_to_share,
		'LinkedIn' 	=> 'https://www.linkedin.com/shareArticle?mini=true&url='.$url_to_share
	) );

	$share_title = apply_filters( 'pc_filter_share_links_title', 'Partage&nbsp;:' );
	$btn_css = apply_filters( 'pc_filter_share_links_class', 'button' );


	/*----------  Affichage  ----------*/
	
	echo '<div class="social-share no-print">';

		if ( $share_title ) { echo '<p class="social-share-title">'.$share_title.'</p>'; }
		
		echo '<ul class="social-list social-list--share">';
			foreach ( $hrefs as $name => $href ) {
				echo '<li class="social-item">';
					echo pc_get_button(
						$name,
						array(
							'href' => $href,
							'class' => 'social-link '.$btn_css.' button--ico',
							'title' => 'Partager sur '.$name.' (nouvelle fenêtre)',
							'target' => '_blank',
							'rel' => 'nofollow noreferrer'
						),
						strtolower($name)
					);
				echo '</li>';
			}
		echo '</ul>';

	echo '</div>';

}


/*=====  FIN Partage  =====*/