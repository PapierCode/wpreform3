<?php



add_action( 'wp', 'pc_wpreform_init', 10 );

	function pc_wpreform_init() {

		if ( is_singular() && class_exists( 'PC_Post' ) ) {

			global $post, $pc_post;
			$pc_post = new PC_Post( $post );

		}

	}

	

/*===============================
=            Excerpt            =
===============================*/

// TODO
add_filter( 'excerpt_length', function() use ( $texts_lengths ) { return $texts_lengths['excerpt']; }, 999 );
add_filter( 'excerpt_more', function() { return ''; }, 999 );


/*=====  FIN Excerpt  =====*/