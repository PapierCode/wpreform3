<?php
/**
*
* Communs templates : head
*
** Attributs balise HTML
** Metas SEO & social, CSS inline
** Favicon
** Statistiques
*
**/


add_action( 'wp_head', 'pc_head_cleaning', 0 );

	function pc_head_cleaning() {

		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'rsd_link', 10 );

		// Emoji
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles', 10 );

		// CSS Block Editor 	
		remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );

	}

	

add_action( 'wp_enqueue_scripts', 'pc_head_remove_default_block_editor_css', 666 );

	function pc_head_remove_default_block_editor_css() {

		wp_dequeue_style( 'wp-block-library' ); 

		// Rank Math
		wp_dequeue_style( 'rank-math-toc-block-style' );
		
	}


/*===============================
=            Favicon            =
===============================*/

add_action( 'wp_head', 'pc_display_favicon', 6 );

	function pc_display_favicon() {

		$file = apply_filters( 'pc_filter_favicon', array(
			'type' => 'image/jpg',
			'url' => get_bloginfo( 'template_directory' ).'/images/favicon.jpg'
		) );
		echo '<link rel="icon" type="'.$file['type'].'" href="'.$file['url'].'" />';

	};


/*=====  FIN Favicon  =====*/

/*==================================
=            CSS inline            =
==================================*/

add_action( 'wp_head', 'pc_display_css_inline', 7 );

	function pc_display_css_inline() {
		
		$css_inline = apply_filters( 'pc_filter_css_inline', '' );
		if ( $css_inline ) { echo '<style>'.$css_inline.'</style>'; }

	};


/*=====  FIN CSS inline  =====*/

/*====================================
=            Statistiques            =
====================================*/

add_action( 'wp_head', 'pc_display_matomo_tracker', 20 );

	function pc_display_matomo_tracker() {

		if ( !get_field( 'wpr_dev' ) && $matomo_seetings = get_field( 'wpr_matomo', 'option' ) ) {

			echo '<script>var _paq = window._paq || [];_paq.push(["trackPageView"]);_paq.push(["enableLinkTracking"]);(function(){var u="'.$matomo_seetings['id'].'";_paq.push(["setSecureCookie", true]);_paq.push(["setTrackerUrl", u+"matomo.php"]);_paq.push(["setSiteId", "'.$matomo_seetings['url'].'"]);_paq.push(["HeatmapSessionRecording::disable"]);var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0];g.type="text/javascript"; g.async=true; g.defer=true; g.src=u+"matomo.js"; s.parentNode.insertBefore(g,s);})();</script>';

		}

	};


/*=====  FIN Statistiques  =====*/