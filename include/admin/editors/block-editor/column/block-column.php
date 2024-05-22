<?php
$allowedBlocks = [ 'core/paragraph', 'core/heading', 'core/list', 'core/list-item', 'acf/pc-column-image', 'acf/pc-buttons' ];
$template =  [ ['core/paragraph'] ];

$block_css = array( 'bloc-column' );

$block_frame = get_field('bloc_frame_color');
if ( $block_frame && $block_frame != 'none' ) { 
    $block_css[] = 'bloc-frame';
    $block_css[] = 'bloc-frame--'.$block_frame;
}
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