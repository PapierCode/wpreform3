<?php

$allowedBlocks = [ 'acf/pc-column' ];
$template = [ ['acf/pc-column' ], ['acf/pc-column' ] ];

$block_css = array(
    'bloc-spacer',
	'bloc-spacer--'.get_field('bloc_spacer'), 
);

echo '<div class="'.implode(' ',$block_css).'">';
    if ( $is_preview ) { echo '<p>Espace '.get_field('bloc_spacer').'</p>'; }
echo '</div>';