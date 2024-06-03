<?php
$allowedBlocks = [ 'acf/pc-column' ];
$template = [ ['acf/pc-column' ], ['acf/pc-column' ] ];

// $h_align = get_field('position_h');

$block_css = array(
    'bloc-columns',
	'bloc-inner-position-v--'.get_field('inner_align_v'),
    'bloc-columns--'.get_field('inner_proportions')
);
if ( $block['align'] ) { $block_css[] = 'bloc-align-h--'.$block['align']; }
if ( get_field('frame_color') != 'none' ) { 
    $block_css[] = 'bloc-frame';
    $block_css[] = 'bloc-frame--'.get_field('frame_color');
}
if ( get_field('inner_reverse') ) { $block_css[] = 'bloc-inner--reverse'; }
if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }

$block_attrs = array( 'class="'.implode( ' ', $block_css ).'"' );
if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs[] = 'id="'.$block['anchor'].'"'; }
?>

<div <?= implode(' ',$block_attrs); ?>>
    <InnerBlocks
        template="<?php echo esc_attr( wp_json_encode( $template ) ) ?>" 
        allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowedBlocks ) )  ?>" 
        templateLock="insert"
    />
</div>