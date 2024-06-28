<?php
/**
 * 
 * Custom administration
 * 
 * Includes
 * Settings
 * SMTP
 * Map API key
 * 
 */


/*===============================
=            Include            =
===============================*/

include 'admin_custom.php';
include 'admin_posts.php';

add_action( 'admin_enqueue_scripts', 'pc_admin_css' );

	function pc_admin_css() {		
		$file = '/include/admin/css/wpreform-admin.css';
		wp_enqueue_style( 'wpreform-admin', get_template_directory_uri().$file, null, filemtime(get_template_directory().$file) );
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

/*===================================
=            Map API key            =
===================================*/

add_filter('acf/fields/google_map/api', 'pc_admin_acf_google_map_api_key');

	function pc_admin_acf_google_map_api_key( $api ) {

		$api['key'] = get_option( 'options_wpr_google_api_map_key' );
		return $api;
		
	}


/*=====  FIN Map API key  =====*/
