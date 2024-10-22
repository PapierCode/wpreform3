<?php
$post_type = get_query_var('post_type');
$archive_settings = get_field( 'wpr_'.$post_type.'_archive', 'option' ) ?? array();

get_header();

    do_action( 'pc_action_template_archive_before', $post_type, $archive_settings );

	if ( have_posts() ) { 		
		while ( have_posts() ) {
			the_post();
			do_action( 'pc_action_template_archive_posts', $post, $post_type, $archive_settings );
		}
	} else {
		echo pc_get_message( 'Aucun résultat.' );
	}

    do_action( 'pc_action_template_archive_after', $post_type, $archive_settings );

get_footer();