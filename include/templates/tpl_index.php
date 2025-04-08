<?php 
/**
 * 
 * Template : page
 * 
 * Hooks
 * Titre
 * Éditeur
 * 
 */

/*=============================
=            Hooks            =
=============================*/

// main start
add_action( 'pc_action_template_index', 'pc_display_main_start', 10 ); // tpl-part_layout.php

	// header
	add_action( 'pc_action_template_index', 'pc_display_main_header_start', 20 ); // tpl-part_layout.php
		add_action( 'pc_action_template_index', 'pc_display_breadcrumb', 30 ); // tpl-part_navigation.php
		add_action( 'pc_action_template_index', 'pc_display_single_main_header_title', 40 );
		add_action( 'pc_action_template_index', 'pc_display_single_main_hero', 50 );
	add_action( 'pc_action_template_index', 'pc_display_main_header_end', 60 ); // tpl-part_layout.php

	// content
	add_action( 'pc_action_template_index', 'pc_display_main_content_start', 70 ); // tpl-part_layout.php
		add_action( 'pc_action_template_index', 'pc_display_single_content', 80 );
	add_action( 'pc_action_template_index', 'pc_display_main_content_end', 90 ); // tpl-part_layout.php

	// footer
	add_action( 'pc_action_template_index', 'pc_display_main_footer_start', 100 ); // tpl-part_layout.php
		add_action( 'pc_action_template_index', 'pc_display_main_footer_backlink', 110 );
		add_action( 'pc_action_template_index', 'pc_display_share_links', 120 ); // tpl-part_social.php
	add_action( 'pc_action_template_index', 'pc_display_main_footer_end', 130 ); // tpl-part_layout.php

// main end
add_action( 'pc_action_template_index', 'pc_display_main_end', 140 ); // tpl-part_layout.php


/*=====  FIN Hooks  =====*/

/*=============================
=            Entête            =
=============================*/

function pc_display_single_main_header_title( $pc_post ) {
	
	echo '<h1>'.apply_filters( 'pc_filter_single_main_title', get_the_title( $pc_post->id ) ).'</h1>';

}

function pc_display_single_main_hero( $pc_post ) {

	if ( apply_filters( 'pc_filter_single_main_hero', false ) ) {
		
		$metas = $pc_post->metas;

		if ( isset( $metas['post_header_intro'] ) && trim( $metas['post_header_intro'] ) ) {
			echo '<div class="main-header-intro">'.wpautop( trim( $metas['post_header_intro'] ) ).'</div>';
		}

		if ( isset( $metas['post_header_button_enable'] ) && $metas['post_header_button_enable'] == 1 ) {

			$btn_class = 'main-header-btn';

			switch ( $metas['post_header_button_type'] ) {
				case 'post':
					if ( get_post_status( $metas['post_header_button_post'] ) == 'publish' ) { 
						$btn_href = get_the_permalink( $metas['post_header_button_post'] );
						$btn_ico = 'arrow';
						$btn_class .= ' button--arrow';
					}
					break;
				case 'file':
					if ( $metas['post_header_button_file'] ) { 
						$btn_href = $metas['post_header_button_file'];
						$btn_ico = 'external';
					}
					break;
				case 'url':
					if ( $metas['post_header_button_url'] ) { 
						$btn_href = $metas['post_header_button_url'];
						$btn_ico = 'download';
					}
					break;
				case 'phone':
					if ( $metas['post_header_button_phone'] ) { 
						$btn_href = 'tel:'.pc_get_phone_format($metas['post_header_button_phone']);
						$btn_ico = 'phone';
					}
					break;
			}

			if ( isset( $btn_href ) ) { echo pc_get_button( $metas['post_header_button_txt'], array( 'href' => $btn_href, 'class' => $btn_class ), $btn_ico ); }

		}

		if ( isset( $metas['post_header_image_enable'] ) && $metas['post_header_image_enable'] == 1 && $pc_post->thumbnail ) {
			$img = $pc_post->thumbnail;
			$srcset = $img['sizes']['card-s'].' '.$img['sizes']['card-s-width'].'w, '.$img['sizes']['card-l'].' '.$img['sizes']['card-l-width'].'w'; 
			$sizes = 'auto, (max-width:'.($img['sizes']['card-s-width']/16).'em) '.$img['sizes']['card-s-width'].'px, '.$img['sizes']['card-l-width'].'px';
			echo '<img class="main-header-img" src="'.$img['sizes']['card-l'].'" alt="'.$img['alt'].'" width="'.$img['sizes']['card-l-width'].'" height="'.$img['sizes']['card-l-height'].'" loading="lazy" sizes="'.$sizes.'" srcset="'.$srcset.'">';
		}

	}
	
}


/*=====  FIN Entête  =====*/

/*===============================
=            Éditeur            =
===============================*/

function pc_display_single_content( $pc_post ) {

	// TODO schéma Article
	// if ( apply_filters( 'pc_filter_page_schema_article_display', true, $pc_post ) ) {
	// 	echo '<script type="application/ld+json">';
	// 		echo json_encode( $pc_post->get_schema_article(), JSON_UNESCAPED_SLASHES );
	// 	echo '</script>';
	// }

	// contenu
	if ( !is_front_page() && apply_filters( 'pc_filter_single_content_display', true, $pc_post ) ) {

		$display_container = apply_filters( 'pc_filter_display_single_content_container', true, $pc_post );

		if ( $display_container ) { echo '<section class="editor">'; }
			the_content();
		if ( $display_container ) { echo '</section>'; }
		
	}

}


/*=====  FIN Éditeur  =====*/

/*===================================
=            Lien retour            =
===================================*/

function pc_display_main_footer_backlink( $pc_post ) {

	if ( ( is_page() && $pc_post->parent > 0 ) || is_single() ) {

		$wp_referer = wp_get_referer();
		
		if ( $wp_referer ) {
			$back_link = $wp_referer;
			$back_title = __('Previous page','wpreform');
			$back_txt = __('Previous page','wpreform');
			$back_ico = 'arrow';
		} else if ( is_page() ) {
			$back_link = get_the_permalink( $pc_post->parent );
			$back_title = __('Read more','wpreform');
			$back_txt = __('Read more','wpreform');
			$back_ico = 'more';
		} else {
			$post_object = get_post_type_object( $pc_post->type );
			$back_link = get_post_type_archive_link( $pc_post->type );
			$back_title = $post_object->labels->all_items;
			$back_txt = apply_filters( 'pc_filter_backlink_text', 'd\''.$post_object->labels->name, $pc_post );
			$back_ico = 'more';
		}

		echo pc_get_button( 
			$back_txt, 
			array(
				'href' => $back_link,
				'class' => 'button--previous',
				'title' => $back_title
			), 
			$back_ico
		);

	}

}


/*=====  FIN Lien retour  =====*/