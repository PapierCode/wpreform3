<?php
/**
 * 
 * Block Editor configuration & blocs ACF
 * 
 */


/*----------  Dépendances JS & CSS  ----------*/

add_action( 'enqueue_block_editor_assets', 'pc_block_editor_admin_enqueue_scripts' );

function pc_block_editor_admin_enqueue_scripts() {

	wp_enqueue_script( 'pc-block-editor-js-admin', get_bloginfo( 'template_directory').'/include/editors/block-editor/block-editor.js', ['wp-blocks', 'wp-dom', 'wp-hooks', 'wp-dom-ready', 'lodash', 'wp-edit-post'] );
	
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
	'quote' => '[Bloc] Citation',
	'frame' => '[Bloc] Encadré',
	'columns' => '[Bloc] 2 colonnes',
	'posts' => '[Bloc] Posts',
	'buttons' => '[Bloc] Boutons',
	'spacer' => '[Bloc] Espace',
	'image' => '[Bloc] Image',
	'gallery' => '[Bloc] Galerie images',
	'image-column' => '[Bloc] Image colonne',
	'image-frame' => '[Bloc] Image encadré',
	// 'embed' => '[Bloc] Embed',
	'column' => '[Bloc] Colonne'
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
			'core/list-item',
			'core/button',
			'rank-math/toc-block'
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
		$settings_acf = array(
			'group_664db0c1e77e1', // [Paramètres] WPreform
			'group_665c8549c226c', // Actualités associées / Article de blog associés
			'group_665d740171b6b', // Page associées
		);

		if ( in_array( $post['title'], $blocks_acf ) || in_array( $post['key'], $settings_acf ) ) {
			$paths = array( get_template_directory().'/include/admin/acf-json' );
		}

		return $paths;

	}

add_filter( 'acf/settings/load_json', 'pc_admin_acf_load_json' );

	function pc_admin_acf_load_json( $paths ) {

		$paths[] = get_template_directory().'/include/admin/acf-json';
		return $paths;

	};

/*======================================
=            Ajout contexte            =
======================================*/

add_filter( 'render_block_data', 'append_parent_block_data', 10, 3 );

	function append_parent_block_data( $parsed_block, $source_block, $parent_block ) {

		if ( $parent_block ) {
			$parsed_block['parent'] = array(
				'attributes' => $parent_block->attributes
			);
		}
		return $parsed_block;
	}


/*=====  FIN Ajout contexte  =====*/	

/*=======================================
=            Rendu des blocs            =
=======================================*/

add_filter( 'render_block', 'pc_render_block', 10, 3 );

	function pc_render_block( $content, $args, $block ) {

		// if ( $args['blockName'] == 'core/image' && isset($args['parent']) ) {
		// 	pc_var($args['parent']);
		// }

		// $quote = new WP_HTML_Tag_Processor( $content );

		if ( $args['blockName'] == 'acf/pc-quote' ) {

			$block_align = $args['attrs']['data']['bloc_align_h'] ?? 'center';
			$content = '<blockquote class="bloc-quote bloc-align-h--'.$block_align.'">';
				$content .= trim($args['innerBlocks'][0]['innerHTML']);
				$cite = wp_strip_all_tags( trim($args['innerBlocks'][1]['innerHTML']) );
				if ( $cite ) {
					$cite_align = $args['innerBlocks'][1]['attrs']['align'] ?? 'left';
					$content .= '<cite class="has-text-align-'.$cite_align.'">'.$cite.'</cite>';
				}
			$content .= '</blockquote>';

		}	

		return $content;

	}


/*=====  FIN Rendu des blocs  =====*/

/*=============================
=            Posts            =
=============================*/

add_filter('acf/fields/post_object/query/key=field_665c1c023d84b', 'pc_admin_filter_block_posts_selection', 10, 3);

	function pc_admin_filter_block_posts_selection( $args, $field, $post_id ) {

		$args['post_parent'] = 0;
		$args['post__not_in'] = array( $post_id, get_option('page_on_front') );

		return $args;

	}


/*=====  FIN Posts  =====*/