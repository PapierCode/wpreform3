<?php
/**
 * 
 * ACF : Actualités/BLog
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
    if ( !get_option('options_news_enabled') ) { return; }

    $short_title_length = apply_filters( 'pc_filter_post_short_title_length', 40 );
    $excerpt_length = apply_filters( 'pc_filter_post_excerpt_length', 150 );

    /*============================
    =            Post            =
    ============================*/

    $args = array(
        'key' => 'group_665d740171b6b',
        'title' => 'Propriétés de l\'actualité',
        'fields' => array(
            array(
                'key' => 'field_6677ec2ef197b',
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
                'key' => 'field_2356e4zbctam3',
                'label' => 'Titre court',
                'name' => 'post_short_title',
                'aria-label' => '',
                'type' => 'text',
                'instructions' => 'Pour le fil d\'ariane & le résumé.<br>'.$short_title_length.' signes maximum.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'maxlength' => $short_title_length,
                'rows' => 6,
                'placeholder' => '',
                'new_lines' => '',
            ),
            array(
                'key' => 'field_6137f42b4tc03',
                'label' => 'Description courte',
                'name' => 'post_excerpt',
                'aria-label' => '',
                'type' => 'textarea',
                'instructions' => 'Pour le résumé. '.$excerpt_length.' signes maximum.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'maxlength' => $excerpt_length,
                'rows' => 6,
                'placeholder' => ''
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'newspost',
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
            'key' => 'field_6677ecfed8780',
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
            'taxonomy' => 'newstax',
            'add_term' => 0,
            'save_terms' => 1,
            'load_terms' => 1,
            'return_format' => 'object',
            'field_type' => 'multi_select',
            'allow_null' => 0,
            'bidirectional' => 0,
            'multiple' => 0,
            'bidirectional_target' => array(
            ),
        );

    }

    /*----------  Relation page  ----------*/
        
    if ( get_option('options_news_pages') ) {

        $args['fields'][] = array(
            'key' => 'field_665d7401032c3',
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
            'bidirectional_target' => array(
            ),
        );

    }

	acf_add_local_field_group( $args );
    
    
    /*=====  FIN Post  =====*/

    /*================================
    =            Settings            =
    ================================*/
    
    acf_add_local_field_group( array(
        'key' => 'group_66607df2e0a0b',
        'title' => '[Paramètres] Actualités/Blog',
        'fields' => array(
            array(
                'key' => 'field_66607df3b5a18',
                'label' => 'Archive',
                'name' => 'wpr_newspost_archive',
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
                        'key' => 'field_66607e99b5a19',
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
                        'key' => 'field_66607fcb88094',
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
            ),
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
    
    
    /*=====  FIN Settings  =====*/

}