<?php

/*----------  Html  ----------*/

echo '<!doctype html><html '.get_language_attributes().'>';


/*----------  Head  ----------*/

echo '<head>';

	echo '<meta charset="utf-8" />';
	echo '<meta name="viewport" content="width=device-width,initial-scale=1.0" />';

	wp_head();

echo '</head>';


/*----------  Body start  ----------*/

echo '<body class="'.implode(' ',get_body_class()).'">';


/*----------  Hook header  ----------*/

// do_action( 'pc_header' );
