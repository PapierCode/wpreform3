<?php
/**
 * 
 * ACF : Foire aux questions
 * 
 */

add_action( 'acf/include_fields', 'pc_admin_faq_acf_include_fields' );

function pc_admin_faq_acf_include_fields() {

	if ( !function_exists( 'acf_add_local_field_group' ) ) { return; }

    	acf_add_local_field_group( array(
            'key' => 'group_pc_post_faq_fields',
            'title' => 'FAQ',
            'fields' => array(
                array(
                    'key' => 'field_pc_faq_answer',
                    'label' => 'Réponse',
                    'name' => 'faq_answer',
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
                    'allow_in_bindings' => 0,
                    'tabs' => 'visual',
                    'toolbar' => 'lightplus',
                    'media_upload' => 0,
                    'delay' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'faqpost',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'seamless',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );

}