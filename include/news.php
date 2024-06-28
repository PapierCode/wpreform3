<?php
/**
 * 
 * Actualités ou Blog
 * 
 * Post
 * Taxonomy
 * Settings
 * 
 */


define( 'NEWS_POST_SLUG', 'newspost' );
define( 'NEWS_TAX_SLUG', 'newstax' );


/*=============================
=            Posts            =
=============================*/

add_action( 'init', 'pc_register_custom_types', 20 );

	function pc_register_custom_types() {

        if ( !get_option('options_news_enabled') ) { return; }

        /*----------  Post  ----------*/

        switch ( get_option('options_news_type') ) {
            case 'news':                
                $post_news_labels = array (
                    'name'                  => 'Actualités',
                    'singular_name'         => 'Actualité',
                    'menu_name'             => 'Actualités',
                    'add_new'               => 'Ajouter une actualité',
                    'add_new_item'          => 'Ajouter une actualité',
                    'new_item'              => 'Ajouter une actualité',
                    'edit_item'             => 'Modifier l\'actualité',
                    'all_items'             => 'Toutes les actualités',
                    'not_found'             => 'Aucune actualité',
                    'search_items'			=> 'Rechercher une actualité'
                );
                $post_news_rewrite = 'actualites';
                break;
            case 'blog':                
                $post_news_labels = array (
                    'name'                  => 'Blog',
                    'singular_name'         => 'Article',
                    'menu_name'             => 'Blog',
                    'add_new'               => 'Ajouter un article',
                    'add_new_item'          => 'Ajouter un article',
                    'new_item'              => 'Ajouter un article',
                    'edit_item'             => 'Modifier l\'article',
                    'all_items'             => 'Tous les articles',
                    'not_found'             => 'Aucun article',
                    'search_items'			=> 'Rechercher un article'
                );
                $post_news_rewrite = 'actualites';
                break;
        }

		$post_news_args = array(
			'has_archive'			=> true,
			'public'				=> true,
			'show_in_nav_menus'		=> false,
			'labels' 				=> $post_news_labels,
			'menu_position'     	=> 21,
			'menu_icon'         	=> 'dashicons-megaphone',
			'supports'          	=> array( 'title', 'editor', 'thumbnail', 'author' ), 
			'show_in_rest'			=> true,
			'rewrite'				=> array( 'slug' => $post_news_rewrite )
		);

		register_post_type( NEWS_POST_SLUG, $post_news_args );


        /*----------  Taxonomie  ----------*/     
        
        if ( get_option('options_news_tax') ) {

            $tax_news_labels = array(
                'name'                          => 'Catégories',
                'singular_name'                 => 'Catégorie',
                'menu_name'                     => 'Catégories',
                'all_items'                     => 'Toutes les catégories',
                'edit_item'                     => 'Modifier la catégorie',
                'view_item'                     => 'Voir la catégorie',
                'update_item'                   => 'Mettre à jour la catégorie',
                'new_item_name'                 => 'Ajouter une catégorie',
                'add_new_item'                  => 'Ajouter une catégorie',
                'search_items'                  => 'Rechercher une catégorie',
                'popular_items'                 => 'Catégories les plus utilisés',
                'separate_items_with_commas'    => 'Séparer les catégories avec une virgule',
                'add_or_remove_items'           => 'Ajouter/supprimer une catégorie',
                'choose_from_most_used'         => 'Choisir parmis les plus utilisées',
                'not_found'                     => 'Aucune catégorie définie',
                'name_field_description'        => '',
                'slug_field_description'        => 'Le "slug" est la version du nom avec uniquement des lettres, des chiffres et des traits d’union.'

            );

            $tax_news_args = array(		
                'labels'			    => $tax_news_labels,
                'hierarchical'		    => false,
                'show_in_nav_menus'     => false,
                'show_admin_column'	    => true,
                'show_in_rest'          => true,
                'publicly_queryable'    => false
            );

            register_taxonomy( NEWS_TAX_SLUG, array( NEWS_POST_SLUG ), $tax_news_args );

        }

    }


/*=====  FIN Posts  =====*/

/*================================
=            Settings            =
================================*/

if ( function_exists('acf_add_options_page') ) {
        
    if ( get_option('options_news_enabled') ) {

        switch ( get_option('options_news_type') ) {
            case 'news':
                $news_settings_title = 'Paramètres des actualités';
                break;
            case 'blog':
                $news_settings_title = 'Paramètres du blog';
                break;
        }

        acf_add_options_page( array(
            'page_title'    => $news_settings_title,
            'menu_title'    => 'Paramètres',
            'menu_slug'     => 'news-settings',
            'capability'    => 'edit_posts',
            'update_button' => 'Mettre à jour',
            'autoload'      => true,
            'parent_slug'   => 'edit.php?post_type=newspost'
        ) );

    }

}


/*=====  FIN Settings  =====*/