<?php
global $wp_query;
$archive_settings = get_field( 'wpr_'.$wp_query->get( 'post_type' ).'_archive', 'option' ) ?? array();

get_header();

    do_action( 'pc_action_template_archive_before', $archive_settings );

	if ( have_posts() ) : while ( have_posts() ) : the_post(); // Boucle WP (1/2)

		do_action( 'pc_action_template_archive_post', $post, $archive_settings );

	endwhile; endif; // Boucle WP (2/2)

    do_action( 'pc_action_template_archive_after', $archive_settings );

get_footer();