<?php
$img_args = get_field('img_args');

if ( $img_args ) {

    $img_size = get_field('img_size');
	$enable_js = get_field('enable_js');
    $caption = trim($img_args['caption']);
    $tag = ( $caption ) ? 'figure' : 'div';

    $block_css = array( 
        'bloc-image', 
        'bloc-image--'.$img_size 
    );	

    // alignement du bloc
    switch ( $img_size ) {
        case 'medium_large_l':
            $block_css[] = 'bloc-align-h--left';
            break;
        case 'medium_large_r':
            $block_css[] = 'bloc-align-h--right';
            break;
        case 'large':
            $block_css[] = 'bloc-align-h--wide';
            break;
    }

    // alignement du contenu
    switch ( $img_size ) {
        case 'thumbnail_s':
        case 'thumbnail':
            $block_css[] = 'bloc-inner-align-h--'.get_field('inner_align_h');
            break;
        case 'medium':
        case 'large':
            $block_css[] = 'bloc-inner-align-h--center';
            break;
        case 'medium_large_l':
            $block_css[] = 'bloc-inner-align-h--left';
            break;
        case 'medium_large_r':
            $block_css[] = 'bloc-inner-align-h--right';
            break;
    }
    if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }
    if ( $enable_js ) { $block_css[] = 'diaporama'; }


    $block_attrs = array( 'class="'.implode( ' ', $block_css ).'"' );
    if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs[] = 'id="'.$block['anchor'].'"'; }

    if ( in_array( $img_size, ['medium_large_l','medium_large_r'] ) ) { $img_size = 'medium_large'; }

    echo '<div '.implode(' ',$block_attrs).'><'.$tag.' class="bloc-image-inner" style="max-width:'.($img_args['sizes'][$img_size.'-width']/16).'rem">';

        if ( !$is_preview && $enable_js ) { echo '<a class="diaporama-link" href="'.$img_args['sizes']['large'].'" data-gl-caption="'.$img_args['caption'].'" data-gl-responsive="'.$img_args['sizes']['medium'].'" title="Afficher l\'image '.$img_args['alt'].'">'; }

            echo '<img src="'.$img_args['sizes'][$img_size].'" alt="'.$img_args['alt'].'" width="'.$img_args['sizes'][$img_size.'-width'].'" height="'.$img_args['sizes'][$img_size.'-height'].'">';
            
        if ( !$is_preview && $enable_js ) {echo '<span class="ico">'.pc_svg('fullscreen').'</span></a>'; }

        if ( $caption ) { echo '<figcaption class="has-text-align-'.get_field('legend_align_h').'">'.$caption.'</figcaption>'; }

    echo '</'.$tag.'></div>';

} else if ( $is_preview ) {
    echo '<div class="bloc-warning">Sélectionnez une image</div>';
}
