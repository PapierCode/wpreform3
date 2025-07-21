<?php
/**
 * 
 * ACF : Entête type "hero"
 * 
 */

add_action( 'acf/include_fields', 'pc_admin_post_hero_acf_include_fields' );

function pc_admin_post_hero_acf_include_fields() {

	if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }

    acf_add_local_field_group( apply_filters( 'pc_filter_acf_post_hero_fields', array(
        'key' => 'group_pc_post_hero',
        'title' => 'Entête de la page',
        'fields' => array(
            array(
                'key' => 'field_pc_post_header_intro',
                'label' => 'Introduction',
                'name' => 'post_header_intro',
                'aria-label' => '',
                'type' => 'wysiwyg',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'allow_in_bindings' => 0,
                'tabs' => 'visual',
                'toolbar' => 'hero',
                'media_upload' => 0,
                'delay' => 0,
            ),
            array(
                'key' => 'field_pc_post_header_image_enable',
                'label' => 'Afficher l\'image à la une',
                'name' => 'post_header_image_enable',
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
                'allow_in_bindings' => 0,
                'ui_on_text' => '',
                'ui_off_text' => '',
                'ui' => 1,
            ),
            array(
                'key' => 'field_pc_post_header_button_enable',
                'label' => 'Afficher un bouton',
                'name' => 'post_header_button_enable',
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
                'allow_in_bindings' => 0,
                'ui_on_text' => '',
                'ui_off_text' => '',
                'ui' => 1,
            ),
            array(
                'key' => 'field_pc_post_header_button_txt',
                'label' => 'Texte du bouton',
                'name' => 'post_header_button_txt',
                'aria-label' => '',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_pc_post_header_button_enable',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'maxlength' => '',
                'allow_in_bindings' => 0,
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
            ),
            array(
                'key' => 'field_pc_post_header_button_type',
                'label' => 'Type de lien',
                'name' => 'post_header_button_type',
                'aria-label' => '',
                'type' => 'button_group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_pc_post_header_button_enable',
                            'operator' => '==',
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
                    'page' => 'Page',
                    'file' => 'Fichier',
                    'url' => 'URL',
                    'phone' => 'Téléphone',
                ),
                'default_value' => 'page',
                'return_format' => 'value',
                'allow_null' => 0,
                'allow_in_bindings' => 1,
                'layout' => 'horizontal',
            ),
            array(
                'key' => 'field_pc_post_header_button_post',
                'label' => 'Page',
                'name' => 'post_header_button_post',
                'aria-label' => '',
                'type' => 'post_object',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_pc_post_header_button_enable',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_pc_post_header_button_type',
                            'operator' => '==',
                            'value' => 'page',
                        ),
                    ),
                ),
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
                'multiple' => 0,
                'allow_null' => 0,
                'allow_in_bindings' => 0,
                'bidirectional' => 0,
                'ui' => 1,
                'bidirectional_target' => array(
                ),
            ),
            array(
                'key' => 'field_pc_post_header_button_file',
                'label' => 'Fichier',
                'name' => 'post_header_button_file',
                'aria-label' => '',
                'type' => 'file',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_pc_post_header_button_enable',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_pc_post_header_button_type',
                            'operator' => '==',
                            'value' => 'file',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'url',
                'library' => 'all',
                'min_size' => '',
                'max_size' => '',
                'mime_types' => 'pdf',
                'allow_in_bindings' => 0,
            ),
            array(
                'key' => 'field_pc_post_header_button_url',
                'label' => 'URL',
                'name' => 'post_header_button_url',
                'aria-label' => '',
                'type' => 'url',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_pc_post_header_button_enable',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_pc_post_header_button_type',
                            'operator' => '==',
                            'value' => 'url',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'allow_in_bindings' => 0,
                'placeholder' => '',
            ),
            array(
                'key' => 'field_pc_post_header_button_phone',
                'label' => 'Téléphone',
                'name' => 'post_header_button_phone',
                'aria-label' => '',
                'type' => 'text',
                'instructions' => 'Sous la forme "00 00 00 00 00".',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_pc_post_header_button_enable',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_pc_post_header_button_type',
                            'operator' => '==',
                            'value' => 'phone',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'maxlength' => 14,
                'allow_in_bindings' => 0,
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ),
            ),
        ),
        'menu_order' => 10,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'field',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    )));

}

