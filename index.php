<?php

get_header();

	if ( have_posts() ) : while ( have_posts() ) : the_post(); // Boucle WP (1/2)

		global $pc_post;
		do_action( 'pc_action_template_index', $pc_post );

	endwhile; endif; // Boucle WP (2/2)

get_footer();