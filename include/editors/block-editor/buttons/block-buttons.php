<?php

$allowedBlocks = [ 'core/button' ];
$template =  [ ['core/button'] ];

$block_css = array(
	'bloc-buttons',
	'bloc-align-h--center',
	'bloc-inner-align-h--'.get_field('inner_align_h')
);
if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }

$block_attrs = array( 'class' => implode( ' ', $block_css ) );
if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs['id'] = $block['anchor']; }

$block_attrs = apply_filters( 'pc_filter_acf_block_buttons_attrs', $block_attrs, $block, $is_preview );

echo '<div '.pc_get_attrs_to_string( $block_attrs ).'>';
	echo '<InnerBlocks template="'.esc_attr( wp_json_encode( $template ) ).'" allowedBlocks="'.esc_attr( wp_json_encode( $allowedBlocks ) ).'" templateLock="false" />';
echo '</div>';
