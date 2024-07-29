<?php

$img_ids = get_field('img_ids');

if ( $img_ids && count( $img_ids ) >= 2 ) {

	$enable_js = get_field('enable_js');
	$enable_crop = get_field('enable_crop');

	$thumb = get_field('enable_crop') ? 'thumbnail_gallery' : 'thumbnail_s';

	$block_css = array(
		'bloc-gallery',
		'gallery',
		'bloc-align-h--wide'
	);
	if ( !$is_preview && $enable_js ) { $block_css[] = 'diaporama'; }
	if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }
	
	$block_attrs = array( 'class="'.implode( ' ', $block_css ).'"' );
	if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs[] = 'id="'.$block['anchor'].'"'; }

	echo '<div '.implode(' ',$block_attrs).'><ul class="gallery-list">';

	foreach ( $img_ids as $img ) {

		echo '<li class="gallery-item">';

			if ( !$is_preview && $enable_js ) { echo '<a class="diaporama-link" href="'.$img['sizes']['large'].'" data-gl-caption="'.$img['caption'].'" data-gl-responsive="'.$img['sizes']['medium'].'" title="Afficher l\'image '.$img['alt'].' en plein écran" rel="nofollow">'; }
		
				echo '<img class="gallery-img" src="'.$img['sizes'][$thumb].'" width="'.$img['sizes'][$thumb.'-width'].'" height="'.$img['sizes'][$thumb.'-height'].'" alt="'.$img['alt'].'" loading="lazy"/>';
		
			if ( !$is_preview && $enable_js ) { echo '</a>'; }
		
		echo '</li>';

	}

	if ( !$is_preview && $enable_js ) { echo '<li class="gallery-item gallery-item--play"><button type="button" class="gallery-play"><span class="ico">'.pc_svg('fullscreen').'</span><span class="txt">Diaporama</span></button></li>'; }

	echo '</ul></div>';

} else if ( $is_preview ) { echo '<div class="bloc-warning">Sélectionnez au moins deux images.</div>'; }
