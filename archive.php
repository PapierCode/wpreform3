<?php
global $wp_query;
$archive_settings = get_field( 'wpr_'.$wp_query->get( 'post_type' ).'_archive', 'option' ) ?? array();

get_header();

    do_action( 'pc_action_template_archive_before', $archive_settings );

	if ( have_posts() ) { 		
		while ( have_posts() ) {
			the_post();
			do_action( 'pc_action_template_archive_post', $post, $archive_settings );
		}
	} else {
		echo pc_get_message( 'Aucun résultat.' );
	}

    do_action( 'pc_action_template_archive_after', $archive_settings );

get_footer();