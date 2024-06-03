<?php

$allowedBlocks = [ 'core/button' ];
$template =  [ ['core/button'] ];

$block_css = array(
	'bloc-buttons',
	'bloc-text-align--'.get_field('inner_align_h')
);
if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }

$block_attrs = array( 'class="'.implode( ' ', $block_css ).'"' );
if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs[] = 'id="'.$block['anchor'].'"'; }

echo '<div '.implode(' ',$block_attrs).'>'; ?>

	<InnerBlocks 
		template="<?= esc_attr( wp_json_encode( $template ) ) ?>" 
		allowedBlocks="<?= esc_attr( wp_json_encode( $allowedBlocks ) )  ?>" 
		templateLock="false"
	/>

<?php echo '</div>';
