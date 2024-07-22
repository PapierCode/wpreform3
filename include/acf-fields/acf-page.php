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

    $excerpt_length = apply_filters( 'pc_filter_post_excerpt_length', 150 );
    $short_title_length = apply_filters( 'pc_filter_post_short_title_length', 40 );

	acf_add_local_field_group( array(
        'key' => 'group_665c8549c226c',
        'title' => 'Propriétés de la page',
        'fields' => array(
            array(
                'key' => 'field_6672e44f112f6',
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
                'key' => 'field_6677e014751e4',
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
                'key' => 'field_6486ettbct4h6',
                'label' => 'Titre court',
                'name' => 'post_short_title',
                'aria-label' => '',
                'type' => 'text',
                'instructions' => 'Pour le fil d\'ariane & le résumé.<br>'.$short_title_length.' signes maximum conseillés.',
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
                'key' => 'field_5137f4xb4tz96',
                'label' => 'Description courte',
                'name' => 'post_excerpt',
                'aria-label' => '',
                'type' => 'textarea',
                'instructions' => 'Pour le résumé. '.$excerpt_length.' signes maximum conseillés.',
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
        ),
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

