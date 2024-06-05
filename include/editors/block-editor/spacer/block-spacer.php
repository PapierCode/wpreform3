<?php
$block_css = array(
    'bloc-spacer',
	'bloc-spacer--'.get_field('space'), 
);

echo '<div class="'.implode(' ',$block_css).'">';
    if ( $is_preview ) { echo 'Espace '.get_field('space'); }
echo '</div>';