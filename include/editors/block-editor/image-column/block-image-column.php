<?php
$img_args = get_field('img_args');

if ( $img_args ) {

    $img_size = get_field('img_size');
	$enable_js = get_field('enable_js');
	$enable_circle = get_field('enable_circle');
    $enable_caption = is_null( get_field('enable_legend') ) ? 1 : get_field('enable_legend');
    $caption = trim($img_args['caption']);

    $tag_attrs = array( 'class' => 'bloc-image-inner' );
    if ( $enable_caption && $caption ) {
        $tag = 'figure';
        $tag_attrs['role'] = 'figure';
        $tag_attrs['aria-label'] = $caption;
    } else {
        $tag = 'div';
    }

    $block_css = array( 
        'bloc-image',
        'bloc-image--column',
        'bloc-image--'.$img_size
    );	
    switch ( $img_size ) {
        case 'thumbnail_s':
        case 'thumbnail':
            $block_css[] = 'bloc-inner-align-h--'.get_field('inner_align_h');
            break;
        case 'medium':
        case 'large':
            $block_css[] = 'bloc-inner-align-h--center';
            break;
        // case 'medium_large_l':
        //     $block_css[] = 'bloc-inner-align-h--left';
        //     break;
        // case 'medium_large_r':
        //     $block_css[] = 'bloc-inner-align-h--right';
        //     break;
    }
    if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }
    if ( get_field( 'enable_cover' ) ) { $block_css[] = 'bloc-image--cover'; }
    if ( $enable_js ) { $block_css[] = 'diaporama'; }
    
    if ( $enable_circle ) { 
        $block_css[] = 'bloc-image--circle';
    	$img_paysage = $img_args['sizes'][$img_size.'-width'] > $img_args['sizes'][$img_size.'-height'];
	    $circle_direction = $img_paysage ? 'height' : 'width';
	    $img_container_style = ' style="max-width:'.($img_args['sizes'][$img_size.'-'.$circle_direction]/16).'rem"';
    } else { $img_container_style = ''; }

    $block_attrs = array( 'class' => implode( ' ', $block_css ) );
    if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs['id'] = $block['anchor']; }

    if ( $img_size == 'medium' ) { $img_size = 'medium_large'; }
    else { $tag_attrs['style'] = 'max-width:'.($img_args['sizes'][$img_size.'-width']/16).'rem'; }

    $block_attrs = apply_filters( 'pc_filter_acf_block_image_column_attrs', $block_attrs, $block, $is_preview );

    echo '<div '.pc_get_attrs_to_string( $block_attrs ).'><'.$tag.' '.pc_get_attrs_to_string($tag_attrs).'>';

        if ( !$is_preview && $enable_js ) { 
            echo '<a class="bloc-image-container diaporama-link" href="'.$img_args['sizes']['large'].'" data-gl-caption="'.$img_args['caption'].'" data-gl-responsive="'.$img_args['sizes']['medium'].'" title="'.__('View image in full screen','wpreform').'" rel="nofollow"'.$img_container_style.'>';
        } else {
            echo '<div class="bloc-image-container"'.$img_container_style.'>';
        }

            echo '<img src="'.$img_args['sizes'][$img_size].'" alt="'.$img_args['alt'].'" width="'.$img_args['sizes'][$img_size.'-width'].'" height="'.$img_args['sizes'][$img_size.'-height'].'">';
            
        if ( !$is_preview && $enable_js ) {
            echo '<span class="ico">'.pc_svg('fullscreen').'</span></a>';
        } else {
            echo '</div>';
        }

        if ( $enable_caption && $caption ) {
            $caption_align = $enable_circle ? 'center' : get_field('legend_align_h');
            echo '<figcaption class="has-text-align-'.$caption_align.'">'.$caption.'</figcaption>';
        }

    echo '</'.$tag.'></div>';

} else if ( $is_preview ) {
    echo '<div class="bloc-warning">Sélectionnez une image</div>';
}
