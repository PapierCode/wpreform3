<?php

echo '<!doctype html><html '.get_language_attributes().'>';

	echo '<head>';
	
		echo '<meta charset="utf-8">';
		echo '<meta name="viewport" content="width=device-width,initial-scale=1.0">';

		wp_head();

	echo '</head>';

	$body_class = is_front_page() ? 'home' : implode(' ',get_body_class());
	echo '<body class="'.$body_class.'">';

		do_action( 'pc_header' );