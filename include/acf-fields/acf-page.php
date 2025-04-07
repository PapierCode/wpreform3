<?php
/**
 * 
 * ACF : Pages
 * 
 * Visuel
 * Parent
 * 
 */

add_action( 'acf/include_fields', 'pc_admin_page_acf_include_fields' );

function pc_admin_page_acf_include_fields() {

	if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }

	acf_add_local_field_group( array(
        'key' => 'group_pc_page_fields',
        'title' => 'Propriétés de la publication',
        'fields' => apply_filters( 'pc_filter_acf_page_fields', array(
            array(
                'key' => 'field_pc_page_thumbnail',
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
                'key' => 'field_pc_page_parent',
                'label' => 'Page parent',
                'name' => 'post_parent',
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
                'multiple' => 0,
                'allow_null' => 1,
                'bidirectional' => 0,
                'ui' => 1,
                'bidirectional_target' => array(
                ),
            ),
            array(
                'key' => 'field_pc_page_short_title',
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
                'key' => 'field_pc_page_excerpt',
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
                'placeholder' => '',
                'new_lines' => '',
            )
        )),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ),
                array(
                    'param' => 'page_type',
                    'operator' => '!=',
                    'value' => 'front_page',
                )
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
        'show_in_rest' => 1,
    ) );

}

