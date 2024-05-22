<?php

add_action( 'admin_menu', 'pc_admin_menu', 999 );

	function pc_admin_menu() {

		global $menu, $submenu;


		/*----------  Pour les utilisateurs non administrateur  ----------*/

		if ( !current_user_can( 'administrator' ) ) {

			// Apparence	
			remove_menu_page( 'themes.php' );

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
			
			// Outils, supprimer l'item
			remove_menu_page( 'tools.php' );

			// TinyPNG
			if ( is_plugin_active( 'tiny-compress-images/tiny-compress-images.php' ) ) {
				foreach( $submenu['upload.php'] as $index => $item ) {
					// sous menu "optimisation en masse"
					if ( in_array( 'tiny-bulk-optimization', $item ) ) {  unset( $submenu['upload.php'][$index] ); }
				}
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


/*----------  Ajout de droits  ----------*/

add_action( 'admin_init', 'pc_admin_menu_capabilities', 999 );

	function pc_admin_menu_capabilities() {
		
		// active le menu apparence pour l'accès aux menus
		$editor = get_role( 'editor' );
		$editor->add_cap( 'edit_theme_options' );

	}
