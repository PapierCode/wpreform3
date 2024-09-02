<?php
/**
 * 
 * ACF : Événements
 * 
 * Visuel
 * Catégories
 * Paramètres
 * 
 */

add_action( 'acf/include_fields', 'pc_admin_avents_acf_include_fields' );

function pc_admin_avents_acf_include_fields() {

	if ( !function_exists( 'acf_add_local_field_group' ) ) { return; }
    if ( !get_option('options_events_enabled') ) { return; }

    /*============================
    =            Post            =
    ============================*/

    /*----------  Publication  ----------*/    

    $args_post = array(
        'key' => 'group_pc_post_event_fields',
        'title' => 'Propriétés de la publication',
        'fields' => array(
            array(
                'key' => 'field_pc_event_thumbnail',
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
                'key' => 'field_pc_event_short_title',
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
                'key' => 'field_pc_event_excerpt',
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
                    'value' => EVENT_POST_SLUG,
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

    if ( get_option('options_events_tax') ) {

        $args_post['fields'][] = array(
            'key' => 'field_pc_event_categories',
            'label' => 'Catégorie(s)',
            'name' => '_event_categories',
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
            'taxonomy' => get_option('options_events_tax_shared') ? NEWS_TAX_SLUG : EVENT_TAX_SLUG,
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
        
    if ( get_option('options_events_pages') ) {

        $args_post['fields'][] = array(
            'key' => 'field_pc_event_pages_related',
            'label' => 'Page(s) associée(s)',
            'name' => '_event_pages_related',
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

	acf_add_local_field_group( $args_post );


    /*----------  Dates événement  ----------*/  

    acf_add_local_field_group( array(
        'key' => 'group_pc_event_dates',
        'title' => 'Dates',
        'fields' => array(
            array(
                'key' => 'field_pc_event_date_start',
                'label' => 'Date de début',
                'name' => 'event_date_start',
                'aria-label' => '',
                'type' => 'date_time_picker',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'display_format' => 'Y-m-d H:i:s',
                'return_format' => 'U',
                'first_day' => 1,
            ),
            array(
                'key' => 'field_pc_event_date_end',
                'label' => 'Date de fin',
                'name' => 'event_date_end',
                'aria-label' => '',
                'type' => 'date_time_picker',
                'instructions' => '0:00 représente le début d\'une journée et non "minuit".',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'display_format' => 'Y-m-d H:i:s',
                'return_format' => 'U',
                'first_day' => 1,
            ),
            array(
                'key' => 'field_pc_event_date_txt',
                'label' => 'Date libre',
                'name' => 'event_date_txt',
                'aria-label' => '',
                'type' => 'text',
                'instructions' => 'Remplace l\'affichage des dates, pour autant les dates restent obligatoires pour les moteurs de recherche.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'maxlength' => 50,
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
                    'value' => 'eventpost',
                ),
            ),
        ),
        'menu_order' => 1,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'field',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    ) );


    /*----------  Lieu événement  ----------*/  

    acf_add_local_field_group( array(
        'key' => 'group_pc_event_place',
        'title' => 'Lieu',
        'fields' => array(
            array(
                'key' => 'field_pc_event_place_name',
                'label' => 'Nom',
                'name' => 'event_place_name',
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
                'key' => 'field_pc_event_place_address',
                'label' => 'Adresse',
                'name' => 'event_place_address',
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
                'key' => 'field_pc_event_place_postcode',
                'label' => 'Code postal',
                'name' => 'event_place_postcode',
                'aria-label' => '',
                'type' => 'number',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'min' => 0,
                'max' => 99999,
                'placeholder' => '',
                'step' => 1,
                'prepend' => '',
                'append' => '',
            ),
            array(
                'key' => 'field_pc_event_place_city',
                'label' => 'Ville',
                'name' => 'event_place_city',
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
                'key' => 'field_pc_event_place_gps',
                'label' => 'Latitude & longitude',
                'name' => 'coordonnees_gps',
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
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'eventpost',
                ),
            ),
        ),
        'menu_order' => 2,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'field',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    ) );


    /*----------  Divers événement  ----------*/  

    acf_add_local_field_group( array(
        'key' => 'group_pc_event_properties',
        'title' => 'Divers',
        'fields' => array(
            array(
                'key' => 'field_pc_event_canceled',
                'label' => 'Annulé',
                'name' => 'event_canceled',
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
                'key' => 'field_pc_event_online',
                'label' => 'En distanciel',
                'name' => 'event_online',
                'aria-label' => '',
                'type' => 'true_false',
                'instructions' => 'L\'adresse reste obligatoire pour les moteurs de recherche.',
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
                'key' => 'field_pc_event_price',
                'label' => 'Tarif',
                'name' => 'event_price',
                'aria-label' => '',
                'type' => 'number',
                'instructions' => 'Laissez vide pour ne rien afficher, saisissez 0 pour afficher "Gratuit".',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'min' => 0,
                'max' => '',
                'placeholder' => '',
                'step' => '0.1',
                'prepend' => '',
                'append' => '',
            ),
            array(
                'key' => 'field_pc_event_performer',
                'label' => 'Intervenant/Intervenante',
                'name' => 'event_performer',
                'aria-label' => '',
                'type' => 'text',
                'instructions' => 'Si non renseigné, votre nom est utilisé. Si plusieurs, les séparer par un caractère "|" (séparateur vertical).',
                'required' => 0,
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
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'eventpost',
                ),
            ),
        ),
        'menu_order' => 3,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'field',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    ) );
    
    
    /*=====  FIN Post  =====*/

    /*================================
    =            Settings            =
    ================================*/
    
    acf_add_local_field_group( array(
        'key' => 'group_pc_events_settings_archive',
        'title' => 'Tous les événements',
        'fields' => array(
            array(
                'key' => 'field_pc_events_archive_intro',
                'label' => 'Introduction',
                'name' => 'wpr_'.EVENT_POST_SLUG.'_archive',
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
                        'key' => 'field_pc_events_archive_title',
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
                        'key' => 'field_pc_events_archive_desc',
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
                    'value' => 'events-settings',
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