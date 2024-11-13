<?php
/**
 * 
 * Block Editor configuration & blocs ACF
 * 
 * Configuration éditeur
 * Blocs ACF
 * Blocs disponibles
 * Rendu des blocs
 * 
 */


/*=============================================
=            Configuration éditeur            =
=============================================*/

// Autres suppressions, voir les fichiers CSS & JS

/*----------  Dépendances JS  ----------*/

add_action( 'enqueue_block_editor_assets', 'pc_block_editor_admin_enqueue_scripts' );

	function pc_block_editor_admin_enqueue_scripts() {

		$path = '/include/editors/block-editor/wpreform-block-editor.js';
		wp_enqueue_script( 
			'pc-block-editor', 
			get_template_directory_uri().$path,
			['wp-blocks', 'wp-dom', 'wp-hooks', 'wp-dom-ready', 'lodash', 'wp-edit-post'],
			filemtime(get_template_directory().$path)
		);
		
	}


/*----------  Suppressions divers  ----------*/

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


/*----------  Suppression métaboxe taxonomies  ----------*/

add_filter( 'rest_prepare_taxonomy', 'pc_block_editor_remove_default_metabox_taxonomy', 10, 3 );

	function pc_block_editor_remove_default_metabox_taxonomy( $response, $taxonomy, $request ) {
		
		if ( !empty( $request['context'] ) && $request['context'] ) {
			$data_response = $response->get_data();
			$data_response['visibility']['show_ui'] = false;
			$response->set_data( $data_response );
		}

		return $response;
		
	}


/*----------  Catégories   ----------*/

add_filter( 'block_categories_all', 'otro_edit_block_editor_categories', 10, 2 );

function otro_edit_block_editor_categories( $block_categories, $editor_context ) {

	$block_categories[] = array(
		'slug'  => 'specials',
		'title' => 'Contenus spécifiques',
		'icon'  => null,
	);
	return $block_categories;

}


/*=====  FIN Configuration éditeur  =====*/

/*=================================
=            Blocs ACF            =
=================================*/

global $pc_blocks_acf;

// id json => nom du groupe (admin)
$pc_blocks_acf = array(
	'quote',
	'frame',
	'columns',
	'subpages',
	'buttons',
	'spacer',
	'image',
	'gallery',
	'image-column',
	'image-frame',
	'embed',
	'column',
	// 'contact',
	'contactform',
	'map'
);

// événements
if ( get_option('options_events_enabled') ) { $pc_blocks_acf[] = 'events'; }

foreach ( $pc_blocks_acf as $block_id ) {
	if ( apply_filters( 'pc_filter_add_acf_'.$block_id.'_block', true ) ) { register_block_type( __DIR__.'/'.$block_id ); }
}



/*=====  FIN Blocs ACF  =====*/

/*=========================================
=            Blocs disponibles            =
=========================================*/

add_filter( 'allowed_block_types_all', 'pc_allowed_block_types_all', 10, 2 );

	function pc_allowed_block_types_all( $blocks, $context ) {

		$blocks = array(
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/list-item',
			'core/button'
		);
		
		global $pc_blocks_acf;
		foreach ( $pc_blocks_acf as $block_id ) {
			if ( apply_filters( 'pc_filter_add_acf_'.$block_id.'_block', true ) ) { $blocks[] = 'acf/pc-'.$block_id; }
		}
	
		return $blocks;
		
	}


/*=====  FIN Blocs disponibles  =====*/

/*====================================
=            Blocs natifs            =
====================================*/

/*----------  Titre  ----------*/

add_filter( 'register_block_type_args', 'pc_block_title_attributs', 10, 2 );

	function pc_block_title_attributs( $args, $block_type ) {
		
		if ( 'core/heading' !== $block_type ) { return $args; }
		$args['attributes']['levelOptions']['default'] = [ 2, 3 ];
		
		return $args;
		
	}



/*=====  FIN Blocs natifs  =====*/

/*=======================================
=            Rendu des blocs            =
=======================================*/

/*----------  Innerblock sans container  ----------*/

add_filter( 'acf/blocks/wrap_frontend_innerblocks', '__return_false' );


/*----------  Contexte parent  ----------*/

add_filter( 'render_block_data', 'pc_render_block_data_parent', 10, 3 );

	function pc_render_block_data_parent( $parsed_block, $source_block, $parent_block ) {

		if ( $parent_block ) { $parsed_block['parent'] = array( 'attributes' => $parent_block->attributes ); }
		return $parsed_block;

	}


/*----------  Rendus customs  ----------*/
	
add_filter( 'render_block', 'pc_render_block', 10, 3 );

	function pc_render_block( $content, $block, $instance ) {

		/*----------  Blocs vides  ----------*/
		
		$not_empty = [ 'core/heading', 'core/paragraph', 'core/list', 'core/list-item', 'core/button' ];
		if ( in_array($block['blockName'], $not_empty) && !trim( wp_strip_all_tags( $content ) ) ) {
			$content = '';
		}


		/*----------  Bouton  ----------*/
		
		
		if ( $block['blockName'] == 'core/button' ) {

			$button = new WP_HTML_Tag_Processor( $content );
			$button->next_tag('a');
			$btn_href = $button->get_attribute('href');
			
			if ( $btn_href ) {

				$btn_ico = 'arrow';
				$btn_attrs = array( 'href="'.$btn_href.'"' );
				
				$btn_blank = $button->get_attribute('target');
				if ( $btn_blank ) {
					$btn_attrs[] = 'target="_blank"';
					$btn_ico = 'external';
				}

				$mime_types = get_allowed_mime_types();
				foreach( $mime_types as $mime_key => $mime_name ) {
					$mime_implode = explode( '|', $mime_key );
					foreach( $mime_implode as $mime ) { 
						if ( str_contains( $btn_href, '.'.$mime ) ) { 
							$btn_attrs[] = 'download';
							$btn_ico = 'download';
							break 2;
						}
					}
				}

				$btn_attrs[] = 'class="button button--'.$btn_ico.'"';

				$content = '<a '.implode(' ',$btn_attrs).'><span class="ico">'.pc_svg($btn_ico).'</span><span class="txt">'.wp_strip_all_tags($content).'</span></a>';

			} else { $content = ''; }
			
		}
		
		
		/*----------  Citation  ----------*/
		
		if ( $block['blockName'] == 'acf/pc-quote' ) {

			$quote = trim( wp_strip_all_tags( $block['innerBlocks'][0]['innerHTML'] ));

			if ( $quote ) {
				
				$block_css = array(
					'bloc-quote',
					'bloc-align-h--'.$block['attrs']['data']['bloc_align_h']
				);
				if ( isset( $block['attrs']['className'] ) && trim( $block['attrs']['className'] ) ) { $block_css[] = $block['attrs']['className']; }
				
				$block_attrs = array( 'class="'.implode( ' ', $block_css ).'"' );
				if ( isset( $block['attrs']['anchor'] ) && trim( $block['attrs']['anchor'] ) ) { $block_attrs[] = 'id="'.$block['attrs']['anchor'].'"'; }

				$content = '<blockquote '.implode(' ',$block_attrs).'>'.$block['innerBlocks'][0]['innerHTML'];

				$cite = trim( wp_strip_all_tags( $block['innerBlocks'][1]['innerHTML'] ));

				if ( $cite ) {
					$content .= str_replace(['<p','p>'],['<cite','cite>'],$block['innerBlocks'][1]['innerHTML']);
				}

				$content .= '</blockquote>';

			} else { $content = ''; }

		}	
		

		/*=====  FIN Citation  =====*/

		return $content;

	}


/*=====  FIN Rendu des blocs  =====*/