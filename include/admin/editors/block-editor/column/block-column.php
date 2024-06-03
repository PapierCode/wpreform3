<?php
$allowedBlocks = [ 'core/paragraph', 'core/heading', 'core/list', 'acf/pc-image-column', 'acf/pc-buttons' ];
$template =  [ ['core/paragraph'] ];

$block_css = array( 'bloc-column' );
$block_frame = get_field('frame_color');
if ( $block_frame && $block_frame != 'none' ) { 
    $block_css[] = 'bloc-frame';
    $block_css[] = 'bloc-frame--'.$block_frame;
}
if ( get_field('inner_cover') ) { $block_css[] = 'bloc-column--cover'; }
if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }

$block_attrs = array( 'class="'.implode( ' ', $block_css ).'"' );
if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs[] = 'id="'.$block['anchor'].'"'; }

?>

<div <?= implode(' ',$block_attrs); ?>>
    <InnerBlocks 
        template="<?= esc_attr( wp_json_encode( $template ) ) ?>" 
        allowedBlocks="<?= esc_attr( wp_json_encode( $allowedBlocks ) )  ?>" 
        templateLock="false"
    />
</div>