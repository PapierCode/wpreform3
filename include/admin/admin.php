<?php
/**
 * 
 * Customisation de l'administration
 * 
 ** Include
 ** Css & js imports
 ** Formats d'images acceptés
 ** Nom des tailles d'images
 ** Liste de pages
 ** TinyMCE custom
 * 
 */


/*===============================
=            Include            =
===============================*/

include 'admin_posts.php';
include 'admin_acf.php';
include 'admin_nav.php';
include 'editors/block-editor/block-editor.php';

add_action( 'admin_enqueue_scripts', 'pc_admin_css' );

	function pc_admin_css() {		
		$file = '/include/admin/css/wpreform-admin.css';
		wp_enqueue_style( 'wpreform-admin', get_template_directory_uri().$file, null, filemtime(get_template_directory().$file) );
	};


/*=====  FIN Include  =====*/

/*============================
=            Init            =
============================*/

add_action( 'admin_init', 'pc_admin_init' ); 

	function pc_admin_init() {

		remove_action( 'welcome_panel', 'wp_welcome_panel' );
		remove_filter( 'update_footer', 'core_update_footer' ); // affichage version 

		// emoji
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script', 10 );
		remove_action( 'admin_print_styles', 'print_emoji_styles', 10 );

	}


/*=====  FIN Init  =====*/

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

});


/*=====  FIN Dashboard  =====*/


/*============================
=            SMTP            =
============================*/

add_action( 'phpmailer_init', 'pc_mail_smtp_settings' );

	function pc_mail_smtp_settings( $phpmailer ) {

		$smtp = get_option( 'options_wpr_smtp' ) ?? array();

		if ( $smtp['enabled'] ) {

			$phpmailer->isSMTP();  
			$phpmailer->Host = $smtp['server'];

			$phpmailer->SMTPAuth = true;
			$phpmailer->SMTPSecure = $smtp['authentication_type'];
			$phpmailer->Port = $smtp['authentication_port'];
			$phpmailer->Username = $smtp['authentication_user'];
			$phpmailer->Password = $smtp['authentication_password'];


			$phpmailer->From = $smtp['from_mail'];
			$phpmailer->FromName = $smtp['from_name'];

		}

	}


/*=====  FIN SMTP  =====*/

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

add_filter( 'upload_mimes', 'pc_edit_upload_mimes' );

    function pc_edit_upload_mimes( $mimes ) {

        return $mimes = apply_filters( 'pc_filter_upload_mimes', array (
            'jpg|jpeg' => 'image/jpeg',
            'pdf' => 'application/pdf',
            'webp' => 'image/webp'
        ));

		return $mimes;

    };


/*----------  Vue liste  ----------*/

add_filter( 'manage_media_columns', 'pc_edit_manage_media_columns' );

	function pc_edit_manage_media_columns( $columns ) {
		
		unset( $columns['parent'] );
		unset( $columns['comments'] );
		return $columns;

	}

add_filter( 'media_row_actions', 'pc_edit_media_row_action' );

	function pc_edit_media_row_action( $actions ) {

		unset( $actions['view'] );
		return $actions;

	}
	
/*----------  Aide  ----------*/

add_filter( 'attachment_fields_to_edit', 'pc_help_img_fields', 10, 2 );

    function pc_help_img_fields( $fields, $post ) {

		if ( str_contains( $post->post_mime_type, 'image' ) ) {

			$fields['pc_help'] = array(
				'label' => 'Aide',
				'input' => 'html',
				'html' => '<p style="margin-top:6px">Le texte alternatif pour le référencement et l\'accessibilité</strong>, décrivez l\'image en quelques mots ou laissez vide si l\'image est purement décorative.<br/><strong>La légende s\'affiche sous l\'image</strong> lorsque celle-ci est insérée dans un contenu ou dans une galerie.</p>',
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

add_action( 'admin_bar_menu', 'pc_remove_adminbar_items', 999 );

	function pc_remove_adminbar_items( $wp_admin_bar ) {

	    $wp_admin_bar->remove_node( 'wp-logo' ); // logo WP
	    $wp_admin_bar->remove_node( 'comments' ); // commentaires en attente
	    $wp_admin_bar->remove_node( 'new-content' ); // créer
	    $wp_admin_bar->remove_node( 'view' ); // créer
	    $wp_admin_bar->remove_node( 'archive' ); // voir post
	    $wp_admin_bar->remove_node( 'customize' ); // voir post

	}


/*----------  Masquer l'onget d'aide  ----------*/

add_action( 'admin_head', function() { get_current_screen()->remove_help_tabs(); });


/*----------  Masquer les mises à jour pour les non administrateur  ----------*/

add_action( 'admin_head', function() {

	if ( !current_user_can( 'administrator' ) ) { remove_action( 'admin_notices', 'update_nag', 3 ); }

}, 1 );

    
/*----------  Désactiver la mention en pied de apge  ----------*/

add_filter( 'admin_footer_text', '__return_empty_string' );


/*----------  Désactive les mots de passe d'application (profil)  ----------*/

add_filter( 'wp_is_application_passwords_available', '__return_false' );


/*=====  FIN Divers  =====*/

/*===========================
=            ACF            =
===========================*/

/*----------  Google Map API  ----------*/

add_filter('acf/fields/google_map/api', 'pc_admin_acf_google_map_api_key');

	function pc_admin_acf_google_map_api_key( $api ) {

		$api['key'] = get_option( 'options_wpr_google_api_map_key' );
		return $api;
		
	}


/*=====  FIN ACF  =====*/
