<?php
/**
 * 
 * Configuration ACF TinyMCE
 * 
 * Barres d'outils
 * Plugins
 * Configuration
 * 
 */


/*=======================================
=            Barres d'outils            =
=======================================*/

add_filter( 'acf/fields/wysiwyg/toolbars' , 'pc_admin_acf_tinymce_toolbars' );

   function pc_admin_acf_tinymce_toolbars( $toolbars ) {

    unset( $toolbars['Full' ] );
    unset( $toolbars['Basic' ] );

   	$toolbars['light'] = array();
   	$toolbars['light'][1] = array( 'undo,redo,removeformat,|,bold,italic,|,link,unlink,|,charmap' );
   	$toolbars['lightplus'] = array();
   	$toolbars['lightplus'][1] = array( 'undo,redo,removeformat,|,bullist,numlist,|,bold,italic,|,link,unlink,|,charmap' );

   	return $toolbars;

   }


/*=====  FIN Barres d'outils  =====*/

/*===============================
=            Plugins            =
===============================*/

add_filter( 'mce_external_plugins', 'pc_admin_acf_tinymce_plugins' );

	function pc_admin_acf_tinymce_plugins( $plugins ) {

		$plugins['visualblocks'] = get_template_directory_uri().'/include/editors/acf-tinymce/plugins/visualblocks.js';

		return $plugins;

	}


/*=====  FIN Plugins  =====*/