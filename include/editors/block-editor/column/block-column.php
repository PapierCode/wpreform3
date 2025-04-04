<?php
$allowedBlocks = apply_filters( 'pc_filter_acf_block_column_allowed', [ 'core/paragraph', 'core/heading', 'core/list', 'acf/pc-image-column', 'acf/pc-buttons' ] );
$template =  [ ['core/paragraph'] ];

$block_css = array( 'bloc-column' );
if ( get_field('bloc_style') && get_field('bloc_style') != 'none' ) { $block_css[] = 'bloc-style--'.get_field('bloc_style'); }
if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }

$block_attrs = array( 'class' => implode( ' ', $block_css ) );
if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs['id'] = $block['anchor']; }

$block_attrs = apply_filters( 'pc_filter_acf_block_column_attrs', $block_attrs, $block, $is_preview );

echo '<div '.pc_get_attrs_to_string( $block_attrs ).'>';
    do_action( 'pc_action_acf_block_column_start', $block, $is_preview );
    echo '<InnerBlocks template="'.esc_attr( wp_json_encode( $template ) ).'" allowedBlocks="'.esc_attr( wp_json_encode( $allowedBlocks ) ).'" templateLock="false" />';
    do_action( 'pc_action_acf_block_column_end', $block, $is_preview );
echo '</div>';