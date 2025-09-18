<?php
/**
 * 
 * Admin : divers
 * 
 * Page de connexion
 * Init / droits navigation éditeur, emojis, panneau d'accueil, version WP, métaboxe archives (navigation)
 * Navigation admin
 * Dashboard
 * Médias / pas de single, formats acceptés, colonnes vue liste, tips champs alt & légende
 * Divers / Barre d'admin, onglet aide, alertes MAJ, mention pied de page
 * 
 */

/*=============================
=            Login            =
=============================*/

// Logo PC
add_action( 'login_head', 'pc_login_head' );

	function pc_login_head() {
		echo '<style>.login h1 a { background-image: url('.get_bloginfo('template_directory').'/images/admin/logo-login.png); background-size: initial; background-position: center top; width:20rem; height:7.5rem; }</style>';
	};

// logo, url du lien
add_filter( 'login_headerurl', 'pc_login_headerurl' );

	function pc_login_headerurl( $url ) { 
		return get_bloginfo( 'url' ); 
	};

// logo, texte du lien
add_filter( 'login_headertext', 'pc_login_headertext' );

	function pc_login_headertext( $message ) { 
		return get_bloginfo('name'); 
	}


// message d'erreur
add_filter( 'login_errors', 'pc_login_errors' ) ;

	function pc_login_errors() {
		return "L'identifiant ou le mot de passe est incorrect.";
	}


/*=====  FIN Login  =====*/

/*==================================
=            Admin init            =
==================================*/

add_action( 'admin_init', 'pc_admin_init' ); 

	function pc_admin_init() {

		// Menu apparence pour les éditeurs (accès sous-menu)		
		$editor = get_role( 'editor' );
		$editor->add_cap( 'edit_theme_options' );
		// emoji
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script', 10 );
		remove_action( 'admin_print_styles', 'print_emoji_styles', 10 );
		// annonce bienvenue
		remove_action( 'welcome_panel', 'wp_welcome_panel' ); 
		 // affichage version 
		remove_filter( 'update_footer', 'core_update_footer' );

		// métaboxe archive
		$menu_metabox_archive_active = get_option('options_news_enabled') || get_option('options_events_enabled') ? true : false;
		if ( apply_filters( 'pc_admin_menu_metabox_archive_active', $menu_metabox_archive_active ) ) {
			add_meta_box(
				'sol_archive_links',
				'Toutes/tous les...',
				'pc_admin_menu_metaboxes_archive_content',
				'nav-menus',
				'side',
				'low'
			);	
		}

	}

/*----------  Métaboxe archive contenu  ----------*/

function pc_admin_menu_metaboxes_archive_content() {

	$default = array();
	if ( get_option('options_news_enabled') ) {
		$default_index = empty($default) ? 1 : 2;
		$default[$default_index] = [ NEWS_POST_SLUG, get_option('options_news_type') == 'news' ? 'Actualités' : 'Articles du blog' ];
	}
	if ( get_option('options_events_enabled') ) {
		$default_index = empty($default) ? 1 : 2;
		$default[$default_index] = [ EVENT_POST_SLUG, 'Événements' ];
	}
	$archives = apply_filters( 'pc_filter_admin_menu_metaboxe_archive_list', $default );

	echo '<div id="posttype-archives" class="posttypediv"><div id="tab-posttype-archives" class="tabs-panel tabs-panel-active"><ul id ="list-posttype-archives" class="categorychecklist form-no-clear">';

		foreach ( $archives as $key => $values ) {
			
		echo '<li>';
			echo '<label class="menu-item-title"><input type="checkbox" class="menu-item-checkbox" name="menu-item['.$key.'][menu-item-object-id]" value="'.$key.'">'.$values[1].'</label>';
			echo '<input type="hidden" class="menu-item-object" name="menu-item['.$key.'][menu-item-object]" value="'.$values[0].'">';
			echo '<input type="hidden" class="menu-item-type" name="menu-item['.$key.'][menu-item-type]" value="post_type_archive">';
			echo '<input type="hidden" class="menu-item-title" name="menu-item['.$key.'][menu-item-title]" value="'.$values[1].'">';
			echo '<input type="hidden" class="menu-item-url" name="menu-item['.$key.'][menu-item-url]" value="'.get_post_type_archive_link( $values[0] ).'">';
		echo '</li>';
		}

	echo '</ul></div><p class="button-controls"><span class="add-to-menu"><input type="submit" class="button-secondary submit-add-to-menu right" value="Add to Menu" name="add-post-type-menu-item" id="submit-posttype-archives"><span class="spinner"></span></span></p></div>';

}

/*=====  FIN Admin init  =====*/

/*==================================
=            Navigation            =
==================================*/

add_action( 'admin_menu', 'pc_admin_menu', 999 );

function pc_admin_menu() {

	global $menu, $submenu;


	/*----------  Pour les utilisateurs non administrateur  ----------*/

	if ( !current_user_can( 'administrator' ) ) {

		// Apparence	
		remove_menu_page( 'themes.php' );		
		// Outils
		remove_menu_page( 'tools.php' );

		// Menus, déplacer l'item 
		$menu[59] = array(
			'Menus',				// Nom
			'edit_pages',			// droits
			'nav-menus.php',		// cible
			'',						// ??
			'menu-top menu-nav',	// classes CSS
			'menu-nav',				// id CSS
			'dashicons-menu'		// icône
		);

		// TinyPNG
		if ( is_plugin_active( 'tiny-compress-images/tiny-compress-images.php' ) ) {
			foreach( $submenu['upload.php'] as $index => $item ) {
				// sous menu "optimisation en masse"
				if ( in_array( 'tiny-bulk-optimization', $item ) ) {  unset( $submenu['upload.php'][$index] ); }
			}
		}

		// Gravity Forms
		if ( is_plugin_active( 'gravityforms/gravityforms.php' ) ) {
			remove_submenu_page("gf_edit_forms","gf_help");
		}

		// Rank Math
		if ( is_plugin_active( 'seo-by-rank-math/rank-math.php' ) && array_key_exists( 'rank-math', $submenu ) ) {
			$rm_key = array_find_key( $menu, function ( $value, $key ) {
				return str_contains( $value[0], 'SEO' );
			});
			if ( $rm_key ) { $menu[$rm_key][0] = 'SEO'; }
			$rm_help_key = array_find_key( $submenu['rank-math'], function ( $value, $key ) {
				return str_contains( $value[0], 'Aide' );
			});
			if ( $rm_help_key ) { unset( $submenu['rank-math'][$rm_help_key]); }
		}

	}


	/*----------  Tous les utilisateurs  ----------*/

	// Articles, supprimer l'item
	remove_menu_page( 'edit.php' );
	// Commentaires
	remove_menu_page( 'edit-comments.php' );

	// Médias, supprimer le sous-menu "Ajouter"
	remove_submenu_page('upload.php', 'media-new.php');
	// Médias, modifier l'icône
	$menu[10][6] = 'dashicons-format-gallery';
	
}


/*=====  FIN Navigation  =====*/

/*=================================
=            Dashboard            =
=================================*/

add_action( 'wp_dashboard_setup', function() {

    // wordpress
    remove_meta_box( 'welcome-panel', 'dashboard', 'normal' );   		// Right Now
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );   		// Right Now
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );        // Quick Press widget
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );      // Recent Drafts
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );            // WordPress.com Blog
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );          // Other WordPress News
    remove_meta_box( 'dashboard_incoming_links','dashboard', 'normal' );    // Incoming Links
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );          // Plugins
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );     // activity

    // plugins
    remove_meta_box( 'tinypng_dashboard_widget', 'dashboard', 'normal' );   // TinyPng
    remove_meta_box( 'rank_math_dashboard_widget', 'dashboard', 'normal' );   // Rankmath

});


/*=====  FIN Dashboard  =====*/

/*==============================
=            Médias            =
==============================*/

/*----------  Page media  ----------*/

add_action( 'wp', 'pc_no_attachment_page' );

	function pc_no_attachment_page() {

		if ( is_attachment() ) {
			global $wp_query;
   			$wp_query->set_404();
		}

	}

/*----------  Formats d'images acceptés  ----------*/

add_filter( 'upload_mimes', 'pc_admin_edit_upload_mimes' );

    function pc_admin_edit_upload_mimes( $mimes ) {

        return array (
            'jpg|jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf',
            'webp' => 'image/webp',
            'avif' => 'image/avif'
        );

    };


/*----------  Vue liste  ----------*/

add_filter( 'manage_media_columns', 'pc_admin_edit_manage_media_columns', 666 );

	function pc_admin_edit_manage_media_columns( $columns ) {
		
		unset( $columns['parent'] );
		unset( $columns['comments'] );
		unset( $columns['rank_math_image_title'] );
		unset( $columns['tiny-compress-images'] );
		return $columns;

	}

add_filter( 'media_row_actions', 'pc_admin_edit_media_row_action' );

	function pc_admin_edit_media_row_action( $actions ) {

		unset( $actions['view'] );
		return $actions;

	}
	
/*----------  Aide  ----------*/

add_filter( 'attachment_fields_to_edit', 'pc_admin_help_img_fields', 10, 2 );

    function pc_admin_help_img_fields( $fields, $post ) {

		if ( str_contains( $post->post_mime_type, 'image' ) ) {

			$fields['pc_help'] = array(
				'label' => 'Aide',
				'input' => 'html',
				'html' => '<p style="margin-top:6px"><strong>Le texte alternatif pour le référencement et l\'accessibilité,</strong> décrivez l\'image en quelques mots ou laissez vide si l\'image est purement décorative.<br><strong>La légende s\'affiche sous l\'image</strong> lorsque celle-ci est insérée dans un contenu ou dans une galerie.</p>',
				'show_in_edit' => true,
			);

		}

        return $fields;

    }
	
 
/*=====  FIN Médias  =====*/

/*==============================
=            Divers            =
==============================*/

/*----------  Simplication de la barre d'admin  ----------*/

add_action( 'admin_bar_menu', 'pc_admin_remove_admin_bar_items', 999 );

	function pc_admin_remove_admin_bar_items( $wp_admin_bar ) {

	    $wp_admin_bar->remove_node( 'wp-logo' ); // logo WP
	    $wp_admin_bar->remove_node( 'comments' ); // commentaires en attente
	    $wp_admin_bar->remove_node( 'new-content' ); // créer
	    $wp_admin_bar->remove_node( 'view' ); // créer
	    $wp_admin_bar->remove_node( 'archive' ); // voir post
	    $wp_admin_bar->remove_node( 'customize' ); // voir post

	}


/*----------  Masquer l'onget d'aide  ----------*/

add_action( 'admin_head', 'pc_admin_remove_help_tab' );

	function pc_admin_remove_help_tab() { 
		
		get_current_screen()->remove_help_tabs();

	}


/*----------  Masquer les mises à jour pour les non administrateur  ----------*/

add_action( 'admin_head', 'pc_admin_remove_update_notice' , 1 );

	function pc_admin_remove_update_notice() {

		if ( !current_user_can( 'administrator' ) ) { remove_action( 'admin_notices', 'update_nag', 3 ); }

	}

    
/*----------  Désactiver la mention en pied de apge  ----------*/

add_filter( 'admin_footer_text', '__return_empty_string' );


/*----------  Désactive les mots de passe d'application (profil)  ----------*/

add_filter( 'wp_is_application_passwords_available', '__return_false' );


/*=====  FIN Divers  =====*/