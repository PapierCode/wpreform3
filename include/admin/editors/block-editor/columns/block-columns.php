<?php
$allowedBlocks = [ 'acf/pc-column' ];
$template = [ ['acf/pc-column' ], ['acf/pc-column' ] ];

$bloc_h_align = get_field('bloc_position_h');

$block_css = array(
    'bloc-columns',
    'bloc-position-h--'.$bloc_h_align, 
	'bloc-inner-position-v--'.get_field('bloc_inner_position_v'), 
);

switch ( $bloc_h_align ) {
    case 'center':
        $block_css[] = 'bloc-columns--'.get_field('bloc_inner_proportions_center');
        break;
    case 'left':
    case 'right':
        $block_css[] = 'bloc-columns--'.get_field('bloc_inner_proportions_lr');
        break;
    case 'wide':
        $block_css[] = 'bloc-columns--'.get_field('bloc_inner_proportions_wide');
        break;
}


if ( get_field('bloc_frame_color') != 'none' ) { 
    $block_css[] = 'bloc-frame';
    $block_css[] = 'bloc-frame--'.get_field('bloc_frame_color');
}
if ( get_field('bloc_inner_reverse') ) { $block_css[] = 'bloc-inner--reverse'; }
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