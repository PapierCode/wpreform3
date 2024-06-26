<?php

$allowedBlocks = [ 'core/paragraph', 'core/heading', 'core/list', 'acf/pc-image-frame', 'acf/pc-buttons' ];
$template =  [ ['core/paragraph'] ];

$block_css = array(
    'bloc-frame',
    'bloc-align-h--'.get_field('bloc_align_h'),
    'bloc-style--'.get_field('bloc_style')
);
if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }

$block_attrs = array( 'class="'.implode( ' ', $block_css ).'"' );
if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs[] = 'id="'.$block['anchor'].'"'; }

?>

<div <?= implode(' ',$block_attrs); ?>><div class="bloc-frame-inner">
    <InnerBlocks 
        template="<?php echo esc_attr( wp_json_encode( $template ) ) ?>" 
        allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowedBlocks ) )  ?>" 
        lock="<?= esc_attr( wp_json_encode( ['remove'=>false,'move'=>true] ) )  ?>" 
    />
</div></div>