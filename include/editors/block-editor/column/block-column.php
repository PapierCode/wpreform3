<?php
$allowedBlocks = [ 'core/paragraph', 'core/heading', 'core/list', 'acf/pc-image-column', 'acf/pc-buttons' ];
$template =  [ ['core/paragraph'] ];

$block_css = array( 'bloc-column' );
if ( in_array( get_field('bloc_style'), ['v1','v2'] ) ) { $block_css[] = 'bloc-style--'.get_field('bloc_style'); }
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