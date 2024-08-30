<?php
$block_css = array(
    'bloc-spacer',
	'bloc-spacer--'.get_field('space'), 
);
if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }

$block_attrs = array( 'class' => implode( ' ', $block_css ) );
if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs['id'] = $block['anchor']; }

$block_attrs = apply_filters( 'pc_filter_acf_block_spacer_attrs', $block_attrs, $block, $is_preview );
echo '<div '.pc_get_attrs_to_string( $block_attrs ).'>';
    if ( $is_preview ) { echo 'Espace '.get_field('space'); }
echo '</div>';