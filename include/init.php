<?php



add_action( 'wp', 'pc_wpreform_init', 10 );

	function pc_wpreform_init() {

		if ( is_singular() && class_exists( 'PC_Post' ) ) {

			global $post, $pc_post;
			$pc_post = new PC_Post( $post );

		}

	}