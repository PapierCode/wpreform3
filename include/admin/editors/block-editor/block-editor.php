<?php
/**
 * 
 * Block Editor configuration & blocs ACF
 * 
 */

include 'block-functions.php';


/*----------  Dépendances JS & CSS  ----------*/

add_action( 'enqueue_block_editor_assets', 'pc_block_editor_admin_enqueue_scripts' );

function pc_block_editor_admin_enqueue_scripts() {

	wp_enqueue_script( 'pc-block-editor-js-admin', get_bloginfo( 'template_directory').'/include/admin/editors/block-editor/block-editor.js', ['wp-blocks', 'wp-dom', 'wp-hooks', 'wp-dom-ready', 'lodash'] );
	
}


/*----------  Suppressions divers  ----------*/

// Autres suppressions, voir les fichiers CSS & JS

add_action( 'after_setup_theme', 'pc_block_editor_after_setup_theme' );

	function pc_block_editor_after_setup_theme() {

		// modèle de page 
		remove_theme_support( 'block-templates' );
		// ensembles de bloc prédéfinis
		remove_theme_support( 'core-block-patterns' );

	}


add_filter( 'block_editor_settings_all', 'pc_block_editor_settings_all', 10, 2 );

	function pc_block_editor_settings_all( $settings, $context ) {

		$is_administrator = current_user_can( 'activate_plugins' );

		if ( !$is_administrator ) {
			// verrouillage des block 
			$settings[ 'canLockBlocks' ] = false;
			// Éditeur de code 
			$settings[ 'codeEditingEnabled' ] = false;
		}

		return $settings;

	}

// innerblock sans container
add_filter( 'acf/blocks/wrap_frontend_innerblocks', '__return_false' );


/*----------  Blocs ACF  ----------*/

// id json => nom du groupe (admin)
$blocks_acf = array(
	'frame' => '[Bloc] Encadré',
	'columns' => '[Bloc] 2 colonnes',
	'subpages' => '[Bloc] Sous-pages',
	'quote' => '[Bloc] Citation',
	'buttons' => '[Bloc] Boutons',
	'spacer' => '[Bloc] Espace',
	'image' => '[Bloc] Image',
	'gallery' => '[Bloc] Galerie images',
	'embed' => '[Bloc] Embed',
	'column' => '[Bloc] Colonne',
	'column-image' => '[Bloc] Image pour colonne',
);

foreach ( $blocks_acf as $block_id => $block_name ) {
	if ( apply_filters( 'pc_filter_add_acf_'.$block_id.'_block', true ) ) { register_block_type( __DIR__.'/'.$block_id ); }
}

add_filter( 'allowed_block_types_all', 'pc_allowed_block_types_all', 10, 2 );

	function pc_allowed_block_types_all( $blocks, $context ) {

		$blocks = array(
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/list-item'
		);
		
		global $blocks_acf;
		foreach ( $blocks_acf as $block_id => $block_name ) {
			if ( apply_filters( 'pc_filter_add_acf_'.$block_id.'_block', true ) ) { $blocks[] = 'acf/pc-'.$block_id; }
		}
	
		return $blocks;
		
	}


/*----------  Répertoire JSON  ----------*/

add_action( 'acf/json/save_paths', 'pc_admin_acf_save_paths', 10, 2 );

	function pc_admin_acf_save_paths( $paths, $post ) {

		global $blocks_acf;
		$settings_acf = array( '[Paramètres] WPreform' );

		if ( in_array( $post['title'], $blocks_acf ) || in_array( $post['title'], $settings_acf ) ) {
			$paths = array( get_template_directory().'/include/admin/editors/block-editor/acf-json' );
		}

		return $paths;

	}

add_filter( 'acf/settings/load_json', 'pc_admin_acf_load_json' );

	function pc_admin_acf_load_json( $paths ) {

		$paths[] = get_template_directory().'/include/admin/editors/block-editor/acf-json';
		return $paths;

	};