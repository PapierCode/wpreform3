<?php
/**
 * 
 * Custom administration
 * 
 * Body classes
 * Includes
 * Settings
 * SMTP
 * Map API key
 * 
 */


/*====================================
=            Body classes            =
====================================*/

add_filter( 'admin_body_class', 'pc_admin_body_class' );

	function pc_admin_body_class( $classes ) {

		$current_user_role = wp_get_current_user();
		if ( in_array( $current_user_role->roles[0], array( 'editor', 'shop_manager' ) ) ) {
			$classes .= ' user-is-editor';
		}

		if ( get_the_id() == get_option('page_on_front') ) {
			$classes .= ' page-is-front-page';
		}

		return $classes;

	}


/*=====  FIN Body classes  =====*/

/*===============================
=            Include            =
===============================*/

include 'admin_custom.php';
include 'admin_posts.php';
include 'admin_acf.php';

add_action( 'admin_enqueue_scripts', 'pc_admin_enqueue_scripts' );

	function pc_admin_enqueue_scripts() {

		$css_path = '/include/admin/css/wpreform-admin.css';
		wp_enqueue_style( 
			'wpreform', 
			get_template_directory_uri().$css_path, 
			null, 
			filemtime(get_template_directory().$css_path),
			'screen'
		);

		$js_path = '/include/admin/wpreform-admin.js';
		wp_enqueue_script( 
			'wpreform',
			get_template_directory_uri().$js_path,
			null,
			filemtime(get_template_directory().$js_path)
		);
		
	};


/*=====  FIN Include  =====*/

/*================================
=            Settings            =
================================*/

if ( function_exists('acf_add_options_page') ) {

    /*----------  Theme options (admin only)  ----------*/
     
    acf_add_options_page( array(
        'page_title'    => 'Paramètres du thème WPreform',
        'menu_title'    => 'WPreform',
        'menu_slug'     => 'wpreform-settings',
        'capability'    => 'manage_options',
        'update_button' => 'Mettre à jour',
        'autoload'      => true,
        'parent_slug'   => 'options-general.php'
    ) );


    /*----------  Theme project options  ----------*/
     
    acf_add_options_page( array(
        'page_title'    => 'Paramètres du site',
        'menu_title'    => 'Paramètres',
        'menu_slug'     => 'site-settings',
        'capability'    => 'edit_posts',
        'update_button' => 'Mettre à jour',
        'autoload'      => true,
        'position'      => 99,
        'icon_url'      => 'dashicons-admin-settings'
    ) );

}


/*=====  FIN Settings  =====*/

/*============================
=            SMTP            =
============================*/

add_action( 'phpmailer_init', 'pc_mail_smtp_settings' );

	function pc_mail_smtp_settings( $phpmailer ) {

		$smtp = get_field( 'wpr_smtp','option' ) ?? array( 'enabled' => 0 );

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
