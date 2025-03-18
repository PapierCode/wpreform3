<?php
/**
 * 
 * ACF : Blocs
 * 
 * 2 Colonnes
 * Boutons
 * Citation
 * Vidéo
 * Encadré
 * Space
 * Galerie
 * Image
 * Posts
 * Formulaire de contact
 * 
 */

add_action( 'acf/include_fields', function() {
	
    if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }

    /*==================================
    =            2 colonnes            =
    ==================================*/

    if ( apply_filters( 'pc_filter_add_acf_columns_block', true ) ) {

        acf_add_local_field_group( array(
            'key' => 'group_660c2d76d2465',
            'title' => '2 colonnes',
            'fields' => apply_filters( 'pc_filter_acf_columns_block_fields', array(
                array(
                    'key' => 'field_663f9864c9661',
                    'label' => 'Attention !',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => 'acf-message-warning',
                        'id' => '',
                    ),
                    'message' => 'La largeur des colonnes varie suivant la taille d\'écran. Vérifiez l\'affichage public pour les mises en page complexes avec différentes tailles d\'écran.',
                    'new_lines' => 'wpautop',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_66472773a255b',
                    'label' => 'Style',
                    'name' => 'bloc_style',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'none' => 'Aucun'
                    ),
                    'default_value' => 'none',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_66641e06b4cf1',
                    'label' => 'Alignement horizontal du bloc',
                    'name' => 'bloc_align_h',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'left' => 'left',
                        'center' => 'center',
                        'right' => 'right',
                        'wide' => 'wide',
                    ),
                    'default_value' => 'wide',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_6666c41031a00',
                    'label' => 'Proportions des colonnes',
                    'name' => 'inner_proportions_center',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_66641e06b4cf1',
                                'operator' => '==',
                                'value' => 'center',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        '1-2' => '1-2',
                        '1-1' => '1-1',
                        '2-1' => '2-1',
                    ),
                    'default_value' => '1-1',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_664af4499be48',
                    'label' => 'Proportions des colonnes',
                    'name' => 'inner_proportions_all',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_66641e06b4cf1',
                                'operator' => '!=',
                                'value' => 'center',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        '1-3' => '1-3',
                        '1-2' => '1-2',
                        '1-1' => '1-1',
                        '2-1' => '2-1',
                        '3-1' => '3-1',
                    ),
                    'default_value' => '1-1',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_66586a61e8cac',
                    'label' => 'Alignement vertical des colonnes',
                    'name' => 'inner_align_v',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'top' => 'top',
                        'center' => 'center',
                        'bottom' => 'bottom',
                        'both' => 'both',
                    ),
                    'default_value' => 'top',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_pc_block_columns_no_gap',
                    'label' => 'Espace entre les colonnes',
                    'name' => 'inner_gap',
                    'aria-label' => '',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 1,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_6648d55d85c66',
                    'label' => 'Inverser les colonnes sur smartphone',
                    'name' => 'inner_reverse',
                    'aria-label' => '',
                    'type' => 'true_false',
                    'instructions' => 'Par défaut, sur smartphone, la colonne de droite passe dessous celle de gauche. Activez ce bouton pour la faire passer au dessus.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                    'ui' => 1,
                ),
            )),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-columns',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );

    }

    if ( apply_filters( 'pc_filter_add_acf_column_block', true ) ) {

        acf_add_local_field_group( array(
            'key' => 'group_66472fada8bcb',
            'title' => 'Colonne',
            'fields' => apply_filters( 'pc_filter_acf_column_block_fields', array(
                array(
                    'key' => 'field_66472fadbf730',
                    'label' => 'Style',
                    'name' => 'bloc_style',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => 'Non appliqué si le bloc parent "2 Colonnes" a une couleur de fond.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'none' => 'Aucun',
                        'v1' => 'Orange',
                        'v2' => 'Vert',
                    ),
                    'default_value' => 'none',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
            )),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-column',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );

    }

    if ( apply_filters( 'pc_filter_add_acf_image_column_block', true ) ) {

        acf_add_local_field_group( array(
            'key' => 'group_665b2a83ac9c5',
            'title' => 'Image colonne',
            'fields' => apply_filters( 'pc_filter_acf_image_column_block_fields', array(
                array(
                    'key' => 'field_665b2a83b7bc9',
                    'label' => 'Image',
                    'name' => 'img_args',
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
                    'preview_size' => 'thumbnail_gallery',
                ),
                array(
                    'key' => 'field_665b2a83bb59e',
                    'label' => 'Largeur de l\'image',
                    'name' => 'img_size',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'thumbnail_s' => 'thumbnail_s',
                        'thumbnail' => 'thumbnail',
                        'medium' => 'medium',
                    ),
                    'default_value' => 'medium',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_665b2a83bf1d0',
                    'label' => '250 pixels minimum conseillés.',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_665b2a83bb59e',
                                'operator' => '==',
                                'value' => 'thumbnail_small',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => '',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_665b2a83c2cb6',
                    'label' => '450 pixels minimum conseillés.',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_665b2a83bb59e',
                                'operator' => '==',
                                'value' => 'thumbnail',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => '',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_665b2a83c69b7',
                    'label' => '700 pixels minimum conseillés.',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_665b2a83bb59e',
                                'operator' => '==',
                                'value' => 'medium',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => '',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_665b2a83d1906',
                    'label' => 'Alignement de l\'image',
                    'name' => 'inner_align_h',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_665b2a83bb59e',
                                'operator' => '==',
                                'value' => 'thumbnail_s',
                            ),
                        ),
                        array(
                            array(
                                'field' => 'field_665b2a83bb59e',
                                'operator' => '==',
                                'value' => 'thumbnail',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'left' => 'left',
                        'center' => 'center',
                        'right' => 'right',
                    ),
                    'default_value' => 'center',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_666318627bd59',
                    'label' => 'Activer le bouton plein écran',
                    'name' => 'enable_js',
                    'aria-label' => '',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_6666d40bd2fe8',
                    'label' => 'Adapter sa hauteur à la colonne',
                    'name' => 'enable_cover',
                    'aria-label' => '',
                    'type' => 'true_false',
                    'instructions' => 'Uniquement si l\'image est seule dans la colonne.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_665b2a83d52f0',
                    'label' => 'Alignement de la légende',
                    'name' => 'legend_align_h',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_6666fafa8011a',
                                'operator' => '!=',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'left' => 'left',
                        'center' => 'center',
                        'right' => 'right',
                    ),
                    'default_value' => 'center',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
            )),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-image-column',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 1,
        ) );

        /*        ,
            array(
                'key' => 'field_6666fafa8011a',
                'label' => 'Afficher dans un cercle',
                'name' => 'enable_circle',
                'aria-label' => '',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui_on_text' => '',
                'ui_off_text' => '',
                'ui' => 1,
            )
        */

    }
    
    
    /*=====  FIN 2 colonnes  =====*/

    /*===============================
    =            Boutons            =
    ===============================*/

    if ( apply_filters( 'pc_filter_add_acf_buttons_block', true ) ) {

        acf_add_local_field_group( array(
            'key' => 'group_66572e5e39313',
            'title' => 'Boutons',
            'fields' => apply_filters( 'pc_filter_acf_buttons_block_fields', array(
                array(
                    'key' => 'field_66572e5e1caad',
                    'label' => 'Alignement horizontal des boutons',
                    'name' => 'inner_align_h',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'left' => 'left',
                        'center' => 'center',
                        'right' => 'right',
                    ),
                    'default_value' => 'left',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
            )),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-buttons',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 1,
        ) );
        
    }
    
    
    /*=====  FIN Boutons  =====*/

    /*================================
    =            Citation            =
    ================================*/

    if ( apply_filters( 'pc_filter_add_acf_quote_block', true ) ) {

        acf_add_local_field_group( array(
            'key' => 'group_666423b2693a3',
            'title' => 'Citation',
            'fields' => apply_filters( 'pc_filter_acf_quote_block_fields', array(
                array(
                    'key' => 'field_666423b250dd1',
                    'label' => 'Alignement horizontal du bloc',
                    'name' => 'bloc_align_h',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'left' => 'left',
                        'center' => 'center',
                        'right' => 'right',
                    ),
                    'default_value' => 'center',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
            )),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-quote',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'side',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );

    }
    
    
    /*=====  FIN Citation  =====*/

    /*=============================
    =            Vidéo            =
    =============================*/

    if ( apply_filters( 'pc_filter_add_acf_embed_block', true ) ) {

        acf_add_local_field_group( array(
            'key' => 'group_628cdbe86abd1',
            'title' => 'Embed',
            'fields' => apply_filters( 'pc_filter_acf_embed_block_fields', array(
                array(
                    'key' => 'field_628cdc01d84ae',
                    'label' => 'Adresse de la page',
                    'name' => '_bloc_embed',
                    'aria-label' => '',
                    'type' => 'url',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                ),
            )),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-embed',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => false,
            'description' => '',
            'show_in_rest' => 0,
        ) );

    }
    
    
    /*=====  FIN Vidéo  =====*/

    /*===============================
    =            Encadré            =
    ===============================*/

    if ( apply_filters( 'pc_filter_add_acf_frame_block', true ) ) {

        acf_add_local_field_group( array(
            'key' => 'group_66433b5a72788',
            'title' => 'Encadré',
            'fields' => apply_filters( 'pc_filter_acf_frame_block_fields', array(
                array(
                    'key' => 'field_66433b5a0dfcb',
                    'label' => 'Style',
                    'name' => 'bloc_style',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'v1' => 'Orange',
                        'v2' => 'Vert',
                    ),
                    'default_value' => 'v1',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_666425e50f44a',
                    'label' => 'Alignement horizontal du bloc',
                    'name' => 'bloc_align_h',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'left' => 'left',
                        'center' => 'center',
                        'right' => 'right',
                        'wide' => 'wide',
                    ),
                    'default_value' => 'center',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
            )),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-frame',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );

    }

    if ( apply_filters( 'pc_filter_add_acf_image_frame_block', true ) ) {

        acf_add_local_field_group( array(
            'key' => 'group_665db297449cd',
            'title' => 'Image encadré',
            'fields' => apply_filters( 'pc_filter_acf_image_frame_block_fields', array(
                array(
                    'key' => 'field_665db2974dabb',
                    'label' => 'Image',
                    'name' => 'img_args',
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
                    'preview_size' => 'thumbnail_gallery',
                ),
                array(
                    'key' => 'field_665db29751408',
                    'label' => 'Largeur de l\'image',
                    'name' => 'img_size',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'thumbnail_s' => 'thumbnail_s',
                        'thumbnail' => 'thumbnail',
                        'medium' => 'medium',
                    ),
                    'default_value' => 'medium',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_665db29754f68',
                    'label' => '250 pixels minimum conseillés.',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_665db29751408',
                                'operator' => '==',
                                'value' => 'thumbnail_s',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => '',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_665db29758ac4',
                    'label' => '450 pixels minimum conseillés.',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_665db29751408',
                                'operator' => '==',
                                'value' => 'thumbnail',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => '',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_665db2975c457',
                    'label' => '700 pixels minimum conseillés.',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_665db29751408',
                                'operator' => '==',
                                'value' => 'medium',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => '',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_665db2975fec7',
                    'label' => 'Alignement de l\'image',
                    'name' => 'inner_align_h',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_665db29751408',
                                'operator' => '==',
                                'value' => 'thumbnail_s',
                            ),
                        ),
                        array(
                            array(
                                'field' => 'field_665db29751408',
                                'operator' => '==',
                                'value' => 'thumbnail_s',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'left' => 'left',
                        'center' => 'center',
                        'right' => 'right',
                    ),
                    'default_value' => 'center',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_666318813c7a4',
                    'label' => 'Activer le bouton plein écran',
                    'name' => 'enable_js',
                    'aria-label' => '',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_665db29763bc4',
                    'label' => 'Alignement de la légende',
                    'name' => 'legend_align_h',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_6666fb1572ce1',
                                'operator' => '!=',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'left' => 'left',
                        'center' => 'center',
                        'right' => 'right',
                    ),
                    'default_value' => 'center',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
            )),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-image-frame',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 1,
        ) );

        /*
            array(
                'key' => 'field_6666fb1572ce1',
                'label' => 'Afficher dans un cercle',
                'name' => 'enable_circle',
                'aria-label' => '',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui_on_text' => '',
                'ui_off_text' => '',
                'ui' => 1,
            )
        */

    }
        
    
    /*=====  FIN Encadré  =====*/

    /*==============================
    =            Espace            =
    ==============================*/

    if ( apply_filters( 'pc_filter_add_acf_spacer_block', true ) ) {

        acf_add_local_field_group( array(
            'key' => 'group_663b56252a21c',
            'title' => 'Espace',
            'fields' => apply_filters( 'pc_filter_acf_spacer_block_fields', array(
                array(
                    'key' => 'field_663b56253dc02',
                    'label' => 'Espace',
                    'name' => 'space',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'x2' => 'x2',
                        'x3' => 'x3',
                        'x4' => 'x4',
                    ),
                    'default_value' => 'x2',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
            )),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-spacer',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 1,
        ) );

    }
        

    /*=====  FIN Espace  =====*/

    /*======================================
    =            Galerie images            =
    ======================================*/

    if ( apply_filters( 'pc_filter_add_acf_gallery_block', true ) ) {

        acf_add_local_field_group( array(
            'key' => 'group_627b645eb8209',
            'title' => 'Galerie images',
            'fields' => apply_filters( 'pc_filter_acf_gallery_block_fields', array(
                array(
                    'key' => 'field_627b64719f534',
                    'label' => 'Images',
                    'name' => 'img_ids',
                    'aria-label' => '',
                    'type' => 'gallery',
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
                    'min' => 2,
                    'max' => '',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    "mime_types" => '',
                    'insert' => 'append',
                    'preview_size' => 'thumbnail_gallery',
                ),
                array(
                    'key' => 'field_63d284b041e44',
                    'label' => 'Activer le bouton diaporama',
                    'name' => 'enable_js',
                    'aria-label' => '',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_6662b19e3fb71',
                    'label' => 'Recadrer les images au format carré',
                    'name' => 'enable_crop',
                    'aria-label' => '',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                    'ui' => 1,
                ),
            )),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-gallery',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 1,
        ) );

    }
    
    
    /*=====  FIN Galerie images  =====*/

    /*=============================
    =            Image            =
    =============================*/

    if ( apply_filters( 'pc_filter_add_acf_image_block', true ) ) {

        acf_add_local_field_group( array(
            'key' => 'group_665976c8baafc',
            'title' => 'Image',
            'fields' => apply_filters( 'pc_filter_acf_image_block_fields', array(
                array(
                    'key' => 'field_665976c86609c',
                    'label' => 'Image',
                    'name' => 'img_args',
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
                    'preview_size' => 'thumbnail_gallery',
                ),
                array(
                    'key' => 'field_66598895b1e8e',
                    'label' => 'Largeur de l\'image',
                    'name' => 'img_size',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'thumbnail_s' => 'thumbnail_s',
                        'thumbnail' => 'thumbnail',
                        'medium' => 'medium',
                        'medium_large_l' => 'medium_large_l',
                        'medium_large_r' => 'medium_large_r',
                        'large' => 'large',
                    ),
                    'default_value' => 'medium',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_665aea08712b9',
                    'label' => '250 pixels minimum conseillés.',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_66598895b1e8e',
                                'operator' => '==',
                                'value' => 'thumbnail_s',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => '',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_665aea7b712ba',
                    'label' => '450 pixels minimum conseillés.',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_66598895b1e8e',
                                'operator' => '==',
                                'value' => 'thumbnail',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => '',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_665aeaa2712bb',
                    'label' => '750 pixels minimum conseillés.',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_66598895b1e8e',
                                'operator' => '==',
                                'value' => 'medium',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => '',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_665aeab1712bc',
                    'label' => '1000 pixels minimum conseillés.',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_66598895b1e8e',
                                'operator' => '==',
                                'value' => 'medium_large_l',
                            ),
                        ),
                        array(
                            array(
                                'field' => 'field_66598895b1e8e',
                                'operator' => '==',
                                'value' => 'medium_large_r',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => '',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_665aeacf712be',
                    'label' => '1200 pixels minimum conseillés.',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_66598895b1e8e',
                                'operator' => '==',
                                'value' => 'large',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => '',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_66598b910e230',
                    'label' => 'Alignement de l\'image',
                    'name' => 'inner_align_h',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_66598895b1e8e',
                                'operator' => '==',
                                'value' => 'thumbnail_s',
                            ),
                        ),
                        array(
                            array(
                                'field' => 'field_66598895b1e8e',
                                'operator' => '==',
                                'value' => 'thumbnail',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'left' => 'left',
                        'center' => 'center',
                        'right' => 'right',
                    ),
                    'default_value' => 'center',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_666318976cefa',
                    'label' => 'Activer le bouton plein écran',
                    'name' => 'enable_js',
                    'aria-label' => '',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_66598ff282d92',
                    'label' => 'Alignement de la légende',
                    'name' => 'legend_align_h',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_6666fac3df1e1',
                                'operator' => '!=',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'left' => 'left',
                        'center' => 'center',
                        'right' => 'right',
                    ),
                    'default_value' => 'center',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
            )),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-image',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 1,
        ) );

        /*
            array(
                'key' => 'field_6666fac3df1e1',
                'label' => 'Afficher dans un cercle',
                'name' => 'enable_circle',
                'aria-label' => '',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui_on_text' => '',
                'ui_off_text' => '',
                'ui' => 1,
            )
        */

    }
    

    /*=====  FIN Image  =====*/

    /*=============================
    =            Posts            =
    =============================*/

    if ( apply_filters( 'pc_filter_add_acf_subpages_block', true ) ) {

        acf_add_local_field_group( array(
            'key' => 'group_664ca4062483d',
            'title' => 'Sous-pages',
            'fields' => apply_filters( 'pc_filter_acf_subpages_block_fields', array(
                array(
                    'key' => 'field_664ca406eaec7',
                    'label' => 'Niveau des titres',
                    'name' => 'title_level',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        2 => 'H2',
                        3 => 'H3',
                    ),
                    'default_value' => '2',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'layout' => 'horizontal',
                ),
            )),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-subpages',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 1,
        ) );

    }
    
    
    /*=====  FIN Posts  =====*/

    /*=============================================
    =            Formulaire de contact            =
    =============================================*/
    
    if ( apply_filters( 'pc_filter_add_acf_contact_form_block', true ) ) {

        acf_add_local_field_group( array(
            'key' => 'group_66a5f79ea51b3',
            'title' => '[Bloc] Formulaire de contact',
            'fields' => apply_filters( 'pc_filter_acf_contact_form_block_fields', array(
                array(
                    'key' => 'field_66a5f79e9a5cc',
                    'label' => 'Destinataire',
                    'name' => 'contact_form_to',
                    'aria-label' => '',
                    'type' => 'email',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
            )),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-contactform',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );

    }
    
    
    /*=====  FIN Formulaire de contact  =====*/

    /*=====================================
    =            Gravity Forms            =
    =====================================*/

    if ( apply_filters( 'pc_filter_add_acf_gravityforms_block', true ) ) {
    
        acf_add_local_field_group( array(
            'key' => 'group_673efa21aa943',
            'title' => '[Bloc] Gravity Forms',
            'fields' => array(
                array(
                    'key' => 'field_673efa21a7cf4',
                    'label' => 'Formulaire',
                    'name' => 'form_id',
                    'aria-label' => '',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => '',
                    'default_value' => false,
                    'return_format' => 'value',
                    'multiple' => 0,
                    'allow_null' => 1,
                    'allow_in_bindings' => 0,
                    'ui' => 1,
                    'ajax' => 0,
                    'placeholder' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-gravityforms',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'side',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'field',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );

    }
    
    
    /*=====  FIN Gravity Forms  =====*/

    /*=============================
    =            Vidéo            =
    =============================*/
    
    if ( apply_filters( 'pc_filter_add_acf_embed_block', true ) ) {
    
        acf_add_local_field_group( array(
            'key' => 'group_66a7572e25c80',
            'title' => '[Bloc] Embed',
            'fields' => apply_filters( 'pc_filter_acf_embed_block_fields', array(
                array(
                    'key' => 'field_66a7572ef9623',
                    'label' => 'Page de la vidéo',
                    'name' => 'embed_url',
                    'aria-label' => '',
                    'type' => 'url',
                    'instructions' => 'L\'adresse d\'une page Youtube, Dailymotion ou Vimeo.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_66a760d1f2fa6',
                    'label' => 'Aperçu',
                    'name' => 'embed_img',
                    'aria-label' => '',
                    'type' => 'image',
                    'instructions' => 'S\'affiche avec le chargement de la vidéo.',
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
                    'mime_types' => 'jpg,jpeg,png,webp',
                    'preview_size' => 'thumbnail_s',
                ),
            )),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-embed',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'side',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'field',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );

    }
    
    
    /*=====  FIN Vidéo  =====*/

    /*==================================
    =            Événements            =
    ==================================*/
    
    if ( get_option('options_events_enabled') && apply_filters( 'pc_filter_add_acf_events_block', true ) ) {
    
        acf_add_local_field_group( array(
            'key' => 'group_670ce31ba53d9',
            'title' => 'Événements',
            'fields' => array(
                array(
                    'key' => 'field_670ce31b9444d',
                    'label' => 'Dans ce bloc, Les événements passés sont toujours affichés.',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => 'wpautop',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_670ce6e55c6a4',
                    'label' => 'Niveau des titres',
                    'name' => 'title_level',
                    'aria-label' => '',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        2 => 'H2',
                        3 => 'H3',
                    ),
                    'default_value' => 2,
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'allow_in_bindings' => 0,
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_670ce48b9444e',
                    'label' => 'Sélection',
                    'name' => 'events',
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
                        0 => 'eventpost',
                    ),
                    'post_status' => array(
                        0 => 'publish',
                    ),
                    'taxonomy' => '',
                    'return_format' => 'id',
                    'multiple' => 1,
                    'allow_null' => 0,
                    'allow_in_bindings' => 1,
                    'bidirectional' => 0,
                    'ui' => 1,
                    'bidirectional_target' => array(
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-events',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'side',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );

    }
    
    
    /*=====  FIN Événements  =====*/

    /*=============================
    =            Carte            =
    =============================*/
    
    if ( apply_filters( 'pc_filter_add_acf_map_block', true ) ) {

        acf_add_local_field_group( array(
            'key' => 'group_6719f25d70453',
            'title' => 'Carte',
            'fields' => array(
                array(
                    'key' => 'field_6719f25d21b4f',
                    'label' => 'Adresse',
                    'name' => 'address',
                    'aria-label' => '',
                    'type' => 'google_map',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'center_lat' => '46.8518280627862',
                    'center_lng' => '2.4293935625',
                    'zoom' => 5,
                    'height' => 300,
                    'allow_in_bindings' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/pc-map',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'side',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );

    }
    
    
    /*=====  FIN Carte  =====*/

} );

