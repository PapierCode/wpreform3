<?php


/*================================
=            Articles            =
================================*/

/*----------  Gestion des menus  ----------*/

add_action( 'admin_head-nav-menus.php' , function() {

	remove_meta_box( 'add-category' , 'nav-menus' , 'side' );
	remove_meta_box( 'add-post_tag' , 'nav-menus' , 'side' );
	remove_meta_box( 'add-post-type-post' , 'nav-menus' , 'side' );

});


/*=====  FIN Articles  =====*/

/*=============================
=            Pages            =
=============================*/

add_action( 'init', 'pc_admin_page_remove_metaboxes' );

function pc_admin_page_remove_metaboxes() {
		
	remove_post_type_support( 'page', 'comments' );	
	remove_post_type_support( 'page', 'revisions' );

};


/*----------  Accueil sans éditeur  ----------*/

add_filter( 'use_block_editor_for_post', 'pc_admin_homepage_disable_block_editor', 10, 2 );

    function pc_admin_homepage_disable_block_editor( $use, $post ) {
        
        return $post->post_type == 'page' && $post->ID == get_option('page_on_front') ? false : $use;

    }
	

/*----------  Actions groupées  ----------*/

// ???

add_filter( 'bulk_actions-edit-page', 'pc_admin_page_edit_bluk_actions' );

	function pc_admin_page_edit_bluk_actions( $actions ) {

		unset($actions['edit']);
		return $actions;

	}


/*----------  Labels  ----------*/
 
add_filter( 'display_post_states', 'pc_admin_edit_page_states', 99, 2 );

	function pc_admin_edit_page_states( $states, $post ) {

		if ( 'page' == $post->post_type ) {

			global $settings_project; // cf. functions.php

			// contenu supplémentaire
			$content_from = get_post_meta( $post->ID, 'content-from', true );

			if ( '' != $content_from ) {
				$states[] = $settings_project['page-content-from'][$content_from][0];
			}
		
		}

		return $states;

	}


/*----------  Colonnes  ----------*/

add_filter( 'manage_pages_columns', 'pc_admin_page_edit_manage_posts_columns' );

    function pc_admin_page_edit_manage_posts_columns( $columns ) {

        unset($columns['author']);

        // nouvelle colonne "image" en 2e position
        $new_columns = array();
        foreach($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ( $key == 'cb' ){
                $new_columns['thumb'] = 'Visuel';
            }
        }
        return $new_columns;

    }

add_action( 'manage_pages_custom_column', 'pc_admin_page_manage_posts_custom_column', 10, 2);

function pc_admin_page_manage_posts_custom_column( $column_name, $post_id ) {

	if ( 'thumb' === $column_name ) {
		
		$img_id = get_post_meta( $post_id, 'visual-id', true );
		if ( '' != $img_id && is_object( get_post( $img_id ) ) ) {
			echo pc_get_img( $img_id, 'share' );
		} else {
			echo '<img src="'.get_bloginfo('template_directory').'/images/admin-no-thumb.png" />';
		}
		
	}

}

/*----------  Page CGU pour les éditeurs  ----------*/

// modifiable mais pas supprimable
add_filter( 'map_meta_cap', 'pc_admin_cgu_map_meta_cap', 10, 4 );

	function pc_admin_cgu_map_meta_cap( $caps, $cap, $user_id, $args ) {

		global $current_user_role;

		if ( in_array( $current_user_role, array( 'editor', 'shop_manager', 'administrator' ) ) ) {

			// modifier oui
			if ( 'manage_privacy_options' === $cap ) {
				$manage_name = is_multisite() ? 'manage_network' : 'manage_options';
     			$caps = array_diff( $caps, array( $manage_name ) );
			}

		}

		if ( in_array( $current_user_role, array( 'editor', 'shop_manager' ) ) ) {
		
			// supprimer non
			if ( 'delete_post' == $cap && $args[0] == get_option( 'wp_page_for_privacy_policy' ) ) {
				$caps[] = 'do_not_allow';
			}

		}

		return $caps;
		
	}

// toujours publié
// ???
add_filter( 'wp_insert_post_data', 'pc_admin_cgu_status', 10, 2 );

	function pc_admin_cgu_status( $data, $postarr ) {
		
		if ( 'page' == $data['post_type'] && get_option( 'wp_page_for_privacy_policy' ) == $postarr['ID'] ) {
			$data['post_status'] = 'publish';
			$data['post_password'] = '';
		}

		return $data;
		
	}

// ne peut être sélectionner
add_filter( 'wp_list_table_show_post_checkbox', 'pc_admin_cgu_checkbox', 10, 2 );

	function pc_admin_cgu_checkbox( $show, $post ) {

		global $current_user_role;

		if ( 'editor' == $current_user_role || 'shop_manager' == $current_user_role ) {
			if ( $post->ID == get_option( 'wp_page_for_privacy_policy' ) ) {
				$show = false;
			}
		}

		return $show;

	}


/*=====  FIN Pages  =====*/