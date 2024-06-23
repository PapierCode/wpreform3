<?php
/**
 * 
 * Customisation de l'administration
 * 
 ** 
 * 
 */


/*===============================
=            Include            =
===============================*/

include 'admin_custom.php';
include 'admin_posts.php';

include 'acf-fields/acf-page.php';
include 'acf-fields/acf-news.php';

add_action( 'admin_enqueue_scripts', 'pc_admin_css' );

	function pc_admin_css() {		
		$file = '/include/admin/css/wpreform-admin.css';
		wp_enqueue_style( 'wpreform-admin', get_template_directory_uri().$file, null, filemtime(get_template_directory().$file) );
	};


/*=====  FIN Include  =====*/

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

/*==========================================
=            ACF Google Map API            =
==========================================*/

add_filter('acf/fields/google_map/api', 'pc_admin_acf_google_map_api_key');

	function pc_admin_acf_google_map_api_key( $api ) {

		$api['key'] = get_option( 'options_wpr_google_api_map_key' );
		return $api;
		
	}


/*=====  FIN ACF Google Map API  =====*/
