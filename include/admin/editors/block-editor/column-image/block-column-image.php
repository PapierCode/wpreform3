<?php
$img = get_field('bloc_img_id');

if ( $img ) {

	$caption = trim($img['caption']);
	$tag = ( $caption ) ? 'figure' : 'div';

	$block_css = array( 'bloc-column-image' );
	
	if ( isset( $block['className'] ) && '' != trim( $block['className'] ) ) { $block_css[] = $block['className']; }
	
	$img_size = get_field('bloc_img_size');

	if ( '200' == $img_size ) {

		$src = $img['sizes']['thumbnail_small'];
		$width = $img['sizes']['thumbnail_small-width'];
		$height = $img['sizes']['thumbnail_small-height'];

	} else {

		$src = $img['sizes']['thumbnail'];
		$width = $img['sizes']['thumbnail-width'];
		$height = $img['sizes']['thumbnail-height'];

		if ( in_array( $img_size, array('600','800') ) ) {

			$srcset = array(
				$img['sizes']['thumbnail'].' 400w',
				$img['sizes']['medium'].' 600w'
			);
			$sizes = array( '(max-width:'.(400/16).'em) 400px' );

			if ( $img_size == '600' ) {
				$sizes[] = '(min-width:'.(401/16).'em) 600px';			
				$src = $img['sizes']['medium'];
				$width = $img['sizes']['medium-width'];
				$height = $img['sizes']['medium-height'];
			}
			if ( '800' == $img_size ) {
				$sizes[] = '(min-width:'.(401/16).'em) and (max-width:'.(600/16).'em) 600px';
				$srcset[] = $img['sizes']['medium_large'].' 800w';	
				$src = $img['sizes']['medium_large'];
				$width = $img['sizes']['medium_large-width'];
				$height = $img['sizes']['medium_large-height'];
			}

		}

	}

	$attrs = array(
		'alt' => trim($img['alt']),
		'loading' => 'lazy',
		'src' => $src,
		'width' => $width,
		'height' => $height,
	);
	if ( isset( $srcset ) ) { $attrs['srcset'] = implode( ', ', $srcset ); }
	if ( isset( $sizes ) ) { $attrs['sizes'] = implode( ', ', $sizes ); }

	$block_attrs = array( 'class="'.implode( ' ', $block_css ).'"' );
	if ( isset( $block['anchor'] ) && '' != trim( $block['anchor'] ) ) { $block_attrs[] = 'id="'.$block['anchor'].'"'; }

	echo '<div '.implode(' ',$block_attrs).'><'.$tag.'>';

		echo '<img';
			foreach ( $attrs as $key => $value ) {
				echo ' '.$key.'="'.$value.'"';
			}
		echo '/>';

		if ( $caption ) { echo '<figcaption class="has-text-align-'.get_field('_bloc_img_caption_align').'">'.$caption.'</figcaption>'; }

	echo '</'.$tag.'></div>';

} else if ( $is_preview ) {

	echo '<p class="editor-error">Erreur bloc <em>Image</em> : sélectionnez une image.</p>';

}