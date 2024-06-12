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
?>

<div class="<?= implode(' ',$block_css); ?>">
	<InnerBlocks 
		template="<?php echo esc_attr( wp_json_encode( $template ) ) ?>" 
		templateLock="all" 
	/>
</div>