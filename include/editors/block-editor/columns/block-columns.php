<?php
$allowedBlocks = [ 'acf/pc-column' ];
$template = [ ['acf/pc-column' ], ['acf/pc-column' ] ];

// $h_align = get_field('position_h');

$block_css = array(
    'bloc-columns',
	'bloc-align-h--'.get_field('bloc_align_h'),
	'bloc-inner-align-v--'.get_field('inner_align_v')
);
switch ( get_field('bloc_align_h') ) {
    case 'center':
        $block_css[] = 'bloc-columns--'.get_field('inner_proportions_center');
        break;
    default:
        $block_css[] = 'bloc-columns--'.get_field('inner_proportions_all');
        break;
}
if ( get_field('bloc_style') && get_field('bloc_style') != 'none' ) { $block_css[] = 'bloc-style--'.get_field('bloc_style'); }
if ( get_field('inner_reverse') ) { $block_css[] = 'bloc-inner--reverse'; }
if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }

$block_attrs = array( 'class' => implode( ' ', $block_css ) );
if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs['id'] = $block['anchor']; }

$block_attrs = apply_filters( 'pc_filter_acf_block_columns_attrs', $block_attrs, $block, $is_preview );
?>

<div <?= pc_get_attrs_to_string( $block_attrs ); ?>>
    <InnerBlocks
        template="<?php echo esc_attr( wp_json_encode( $template ) ) ?>" 
        allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowedBlocks ) )  ?>" 
        templateLock="insert"
    />
</div>