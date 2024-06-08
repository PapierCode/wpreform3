<?php
$template =  [ 
	['core/paragraph', [ 'placeholder' => 'Citation...']], 
	['core/paragraph', [ 'placeholder' => 'Source...']]
];

$block_css = array(
    'bloc-quote',
    'bloc-align-h--'.get_field('bloc_align_h')
);
if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }

$block_attrs = array( 'class="'.implode( ' ', $block_css ).'"' );
if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs[] = 'id="'.$block['anchor'].'"'; }
?>

<blockquote <?= implode(' ',$block_attrs); ?>>
	<InnerBlocks 
		template="<?php echo esc_attr( wp_json_encode( $template ) ) ?>" 
		templateLock="all" 
	/>
</blockquote>