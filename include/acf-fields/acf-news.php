<?php
/**
 * 
 * ACF : Actualités/Blog
 * 
 * Visuel
 * Catégories
 * Relation pages
 * Paramètres
 * 
 */

add_action( 'acf/include_fields', 'pc_admin_news_acf_include_fields' );

function pc_admin_news_acf_include_fields() {

	if ( !function_exists( 'acf_add_local_field_group' ) ) { return; }

    /*============================
    =            Post            =
    ============================*/

    $args = array(
        'key' => 'group_pc_news_fields',
        'title' => 'Propriétés de la publication',
        'fields' => array(
            array(
                'key' => 'field_pc_news_thumbnail',
                'label' => 'Image à la une',
                'name' => '_thumbnail_id',
                'aria-label' => '',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'array',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_pc_news_short_title',
                'label' => 'Titre court',
                'name' => 'post_short_title',
                'aria-label' => '',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'maxlength' => '',
                'rows' => 6,
                'placeholder' => '',
                'new_lines' => '',
            ),
            array(
                'key' => 'field_pc_news_excerpt',
                'label' => 'Description courte',
                'name' => 'post_excerpt',
                'aria-label' => '',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'maxlength' => '',
                'rows' => 6,
                'placeholder' => ''
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => NEWS_POST_SLUG,
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'left',
        'instruction_placement' => 'field',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    );

    /*----------  Taxonomy  ----------*/  

    if ( get_option('options_news_tax') ) {

        $args['fields'][] = array(
            'key' => 'field_pc_news_categories',
            'label' => 'Catégorie(s)',
            'name' => '_news_categories',
            'aria-label' => '',
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'taxonomy' => NEWS_TAX_SLUG,
            'add_term' => 1,
            'save_terms' => 1,
            'load_terms' => 1,
            'return_format' => 'object',
            'field_type' => 'multi_select',
            'allow_null' => 0,
            'bidirectional' => 0,
            'multiple' => 0,
            'bidirectional_target' => array(),
        );

    }

    /*----------  Relation page  ----------*/
        
    if ( get_option('options_news_pages') ) {

        $args['fields'][] = array(
            'key' => 'field_pc_news_pages_related',
            'label' => 'Page(s) associée(s)',
            'name' => '_news_pages_related',
            'aria-label' => '',
            'type' => 'post_object',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => array(
                0 => 'page',
            ),
            'post_status' => array(
                0 => 'publish',
            ),
            'taxonomy' => '',
            'return_format' => 'id',
            'multiple' => 1,
            'allow_null' => 0,
            'bidirectional' => 0,
            'ui' => 1,
            'bidirectional_target' => array(),
        );

    }

	acf_add_local_field_group( $args );
    
    
    /*=====  FIN Post  =====*/

    /*================================
    =            Settings            =
    ================================*/

    $group_archive_title = get_option('options_news_type') == 'news' ? 'Toutes les actualités' : 'Tout le blog';
    
    acf_add_local_field_group( array(
        'key' => 'group_pc_news_settings_archive',
        'title' => $group_archive_title,
        'fields' => array(
            array(
                'key' => 'field_pc_news_archive_intro',
                'label' => 'Introduction',
                'name' => 'wpr_'.NEWS_POST_SLUG.'_archive',
                'aria-label' => '',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'row',
                'sub_fields' => array(
                    array(
                        'key' => 'field_pc_news_archive_title',
                        'label' => 'Titre',
                        'name' => 'title',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                    ),
                    array(
                        'key' => 'field_pc_news_archive_desc',
                        'label' => 'Description',
                        'name' => 'desc',
                        'aria-label' => '',
                        'type' => 'wysiwyg',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'tabs' => 'visual',
                        'toolbar' => 'lightplus',
                        'media_upload' => 0,
                        'delay' => 0,
                    ),
                ),
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'news-settings',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'left',
        'instruction_placement' => 'field',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0
    ) );

    if ( get_field('news_enabled','option') && get_field('news_pages','option') ) { 

        $page_section_label = get_option('options_news_type') == 'news' ? 'actualité' : 'blog';
        
        acf_add_local_field_group( array(
            'key' => 'group_pc_news_aside_page',
            'title' => 'Page',
            'fields' => array(
                array(
                    'key' => 'field_pc_news_aside_page_title',
                    'label' => 'Titre de la section '.$page_section_label.' dans les pages',
                    'name' => 'news_aside_page_title',
                    'aria-label' => '',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => ucfirst($page_section_label),
                    'maxlength' => '',
                    'rows' => 6,
                    'placeholder' => '',
                    'new_lines' => '',
                )
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'news-settings',
                    ),
                ),
            ),
            'menu_order' => 1,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'left',
            'instruction_placement' => 'field',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0
        ) );

    }
    
    
    /*=====  FIN Settings  =====*/

}