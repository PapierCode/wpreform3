<?php
/**
 * 
 * Admin : listes de posts
 * 
 * Communs / Colonne visuel, status précédent après une restauration
 * Articles / Suppression métaboxes menus
 * Pages / Contenu champ parent & sauvegarde
 * Pages / Suppression métaboxes
 * Pages / Accueil sans éditeur
 * Pages / CGU éditeur
 * 
 */

/*===============================
=            Communs            =
===============================*/

/*----------  Visuel dans les listes  ----------*/

add_filter( 'manage_pages_columns', 'pc_admin_post_column_thumbnail' );

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

	function pc_admin_post_column_thumbnail_populate( $column_name, $post_id ) {

		if ( 'thumb' === $column_name ) {
			$thumb = get_the_post_thumbnail( $post_id, 'thumbnail_gallery' );
			echo $thumb ? $thumb : '<img src="'.get_bloginfo('template_directory').'/images/admin-no-thumb.png" />';			
		}

	}

/*----------  Status précédent après une restauration  ----------*/

add_filter( 'wp_untrash_post_status', 'pc_admin_untrash_post_status', 10, 3 );

    function pc_admin_untrash_post_status( $new_status, $post_id, $previous_status ) {

        return $previous_status;

    }

/*----------  Messages  ----------*/

add_action( 'admin_notices', 'crea_movie_imported_notice' );

    function crea_movie_imported_notice() {

        global $pagenow;

        if ( 'nav-menus.php' == $pagenow ) {
            echo '<div class="notice notice-error"><p>Attention la mise en page du site peut limiter le nombre d\'éléments dans un menu ! Vérifiez avec différentes tailles d\'écrans (responsive design).</p></div>';
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

/*----------  Sélection parent  ----------*/

add_filter('acf/fields/post_object/query/key=field_pc_page_parent', 'pc_admin_page_parent_field_query', 10, 3);

	function pc_admin_page_parent_field_query( $args, $field, $post_id ) {

		$args['post__not_in'] = array( $post_id, get_option('page_on_front') );
		$args['post_parent__not_in'] = array( $post_id );

		return $args;
	}

add_action( 'save_post', 'pc_admin_page_parent_field_save', 10, 2 );

    function pc_admin_page_parent_field_save( $post_id, $post ) {

        if ( !wp_is_post_revision( $post_id ) && $post->post_type == 'page' && isset($_POST['acf']['field_pc_page_parent']) ) {

            // prévention contre une boucle infinie 1/2
			remove_action( 'save_post', 'pc_admin_page_parent_field_save', 10, 2 );
			// mise à jour post
			wp_update_post( array(
				'ID' => $post_id,
				'post_parent' => $_POST['acf']['field_pc_page_parent']
			));
			// prévention contre une boucle infinie 2/2
			add_action( 'save_post', 'pc_admin_page_parent_field_save', 10, 2 );

        }

    }

/*----------  Suppression métaboxes  ----------*/

add_action( 'init', 'pc_admin_page_remove_metaboxes' );

	function pc_admin_page_remove_metaboxes() {
			
		remove_post_type_support( 'page', 'comments' );	
		remove_post_type_support( 'page', 'revisions' );
		remove_post_type_support( 'page', 'page-attributes' );

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