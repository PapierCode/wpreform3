<?php

/*===============================
=            Communs            =
===============================*/

/*----------  Image mise en avant  ----------*/

add_action( 'after_setup_theme', 'pc_admin_add_thumbnail_support' );

	function pc_admin_add_thumbnail_support() {

		add_theme_support( 'post-thumbnails', array( 'page', NEWS_POST_SLUG ) );

	}

add_filter( 'manage_pages_columns', 'pc_admin_post_column_thumbnail' );
add_filter( 'manage_'.NEWS_POST_SLUG.'_posts_columns', 'pc_admin_post_column_thumbnail' );

    function pc_admin_post_column_thumbnail( $columns ) {

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

add_action( 'manage_pages_custom_column', 'pc_admin_post_column_thumbnail_populate', 10, 2);
add_action( 'manage_'.NEWS_POST_SLUG.'_posts_custom_column', 'pc_admin_post_column_thumbnail_populate', 10, 2);

	function pc_admin_post_column_thumbnail_populate( $column_name, $post_id ) {

		if ( 'thumb' === $column_name ) {
			$thumb = get_the_post_thumbnail( $post_id );
			echo $thumb ? $thumb : '<img src="'.get_bloginfo('template_directory').'/images/admin-no-thumb.png" />';			
		}

	}


/*=====  FIN Communs  =====*/

/*================================
=            Articles            =
================================*/

/*----------  Gestion des menus  ----------*/

add_action( 'admin_head-nav-menus.php', 'pc_admin_nav_menu_remove_article_metaboxes' ); 

	function pc_admin_nav_menu_remove_article_metaboxes() {

		remove_meta_box( 'add-category' , 'nav-menus' , 'side' );
		remove_meta_box( 'add-post_tag' , 'nav-menus' , 'side' );
		remove_meta_box( 'add-post-type-post' , 'nav-menus' , 'side' );

	}


/*=====  FIN Articles  =====*/

/*=============================
=            Pages            =
=============================*/

/*----------  Suppresison métabxoes  ----------*/

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


/*----------  Page CGU pour les éditeurs  ----------*/

// modifiable mais pas supprimable
add_filter( 'map_meta_cap', 'pc_admin_cgu_map_meta_cap', 10, 4 );

	function pc_admin_cgu_map_meta_cap( $caps, $cap, $user_id, $args ) {

		if ( !is_user_logged_in() ) { return $caps; }

		$current_user_role = wp_get_current_user();

		if ( in_array( $current_user_role->roles[0], array( 'editor', 'shop_manager', 'administrator' ) ) ) {

			// modifier oui
			if ( 'manage_privacy_options' === $cap ) {
				$manage_name = is_multisite() ? 'manage_network' : 'manage_options';
     			$caps = array_diff( $caps, array( $manage_name ) );
			}

		}

		if ( in_array( $current_user_role->roles[0], array( 'editor', 'shop_manager' ) ) ) {
		
			// supprimer non
			if ( 'delete_post' == $cap && $args[0] == get_option( 'wp_page_for_privacy_policy' ) ) {
				$caps[] = 'do_not_allow';
			}

		}

		return $caps;
		
	}

// toujours publié
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

		if ( !is_user_logged_in() ) { return $show; }

		$current_user_role = wp_get_current_user();

		if ( 'editor' == $current_user_role->roles[0] || 'shop_manager' == $current_user_role->roles[0] ) {
			if ( $post->ID == get_option( 'wp_page_for_privacy_policy' ) ) {
				$show = false;
			}
		}

		return $show;

	}


/*=====  FIN Pages  =====*/