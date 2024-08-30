<?php
$template =  [ 
	['core/paragraph', [ 'placeholder' => 'Citation...']], 
	['core/paragraph', [ 'placeholder' => 'Source...']]
];

// rendu admin, cf. pc_render_block() pour le front
$block_css = array(
    'bloc-quote',
    'bloc-align-h--'.get_field('bloc_align_h')
);
if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }

$block_attrs = array( 'class' => implode( ' ', $block_css ) );
if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs['id'] = $block['anchor']; }

$block_attrs = apply_filters( 'pc_filter_acf_block_buttons_attrs', $block_attrs, $block, $is_preview );
?>

<div <?= pc_get_attrs_to_string( $block_attrs ); ?>>
	<InnerBlocks 
		template="<?php echo esc_attr( wp_json_encode( $template ) ) ?>" 
		templateLock="all" 
	/>
</div>