<?php
/**
 * 
 * Gravity Forms
 * 
 */


/*=======================================
=            Droits éditeurs            =
=======================================*/

add_action( 'admin_init', 'pc_admin_gravityforms_editor_capabilities' );

    function pc_admin_gravityforms_editor_capabilities() {

        // https://docs.gravityforms.com/role-management-guide/
        $role = get_role( 'editor' );
        $role->add_cap( 'gravityforms_edit_forms' );
        $role->add_cap( 'gravityforms_create_form' );
        $role->add_cap( 'gravityforms_delete_forms' );
        $role->add_cap( 'gravityforms_view_entries' );
        $role->add_cap( 'gravityforms_edit_entries' );
        $role->add_cap( 'gravityforms_delete_entries' );
        $role->add_cap( 'gravityforms_export_entries' );

    }


/*=====  FIN Droits éditeurs  =====*/

/*==============================================
=            Paramètres formulaires            =
==============================================*/

add_filter( 'gform_bypass_template_library', '__return_true' );

add_filter( 'gform_form_settings_fields', 'pc_admin_gravityforms_form_settings_fields' );

    function pc_admin_gravityforms_form_settings_fields( $fields ) {

        if ( !current_user_can( 'administrator' ) ) { 
            unset( $fields['form_layout'] ); // options de mise en forme
            unset( $fields['form_button'] ); // message bouton de validation
            unset( $fields['save_and_continue'] ); // sauvegarder et reprendre
            unset( $fields['restrictions']['fields'][2] ); // internaute connecté
        }
        return $fields;

    }


add_filter( 'gform_form_settings_initial_values', 'pc_admin_gravityforms_form_settings_initial_values' );

    function pc_admin_gravityforms_form_settings_initial_values( $initial_values ) {

        if ( !current_user_can( 'administrator' ) ) { 
            $initial_values['enableAnimation'] = true; // animation par défaut 
        }
        return $initial_values;
        
    }

add_filter( 'gform_form_settings_menu', 'pc_admin_gravityforms_form_settings_menu' );

    function pc_admin_gravityforms_form_settings_menu( $menu_items ) {
    
        if ( !current_user_can( 'administrator' ) ) { 
            unset( $menu_items[40] ); // données personnelles
        }
        return $menu_items;
        
    }


add_action( 'gform_after_save_form', 'pc_admin_gravity_forms_after_save_form', 10, 2 );

    function pc_admin_gravity_forms_after_save_form( $form, $is_new ) {

        if ( !current_user_can( 'administrator' ) && $is_new ) {
            $form['personalData']['preventIP'] = true; // ne pas sauvegarder l'ip de l'internaute
            $form['personalData']['retention']['policy'] = 'delete'; // toujours supprimer
            $form['personalData']['retention']['retain_entries_days'] = 200; // supprimer au bout de X jours
            GFAPI::update_form( $form );
        }

    }


/*=====  FIN Paramètres formulaires  =====*/

/*=============================================
=            Liste des formulaires            =
=============================================*/

add_action( 'gform_form_actions', 'pc_admin_gravityforms_form_actions' );

    function pc_admin_gravityforms_form_actions( $actions ) {

        if ( !current_user_can( 'administrator' ) ) { 
            unset( $actions['preview']); // lien aperçu
        }
        return $actions;

    }

add_filter( 'gform_form_list_columns', 'pc_admin_gravityforms_form_list_columns' );

    function pc_admin_gravityforms_form_list_columns( $columns ){

        if ( !current_user_can( 'administrator' ) ) {
            unset( $columns['conversion'] ); // colonne conversion
        }
        return $columns;

    }


/*=====  FIN Liste des formulaires  =====*/

/*====================================
=            Confirmation            =
====================================*/

add_filter( 'gform_confirmation_settings_fields', 'pc_admin_gravityforms_confirmation_settings_fields' );

function pc_admin_gravityforms_confirmation_settings_fields( $fields ) {

    if ( !current_user_can( 'administrator' ) ) { 
        unset( $fields[0]['fields'][4] ); // formatage auto
    }
    return $fields;

}


/*=====  FIN Confirmation  =====*/

/*====================================
=            Notification            =
====================================*/

add_filter( 'gform_notification_settings_fields', 'pc_admin_gravityforms_notification_settings_fields' );

function pc_admin_gravityforms_notification_settings_fields( $fields, $notification, $form ) {

    if ( !current_user_can( 'administrator' ) ) {
        unset( $fields[0]['fields'][14] ); // formatage auto
    }
    return $fields;

}


/*=====  FIN Notification  =====*/

/*==============================================
=            Champs non disponibles            =
==============================================*/

add_filter( 'gform_add_field_buttons', 'pc_admin_gravityforms_add_field_buttons' );

    function pc_admin_gravityforms_add_field_buttons( $groups ) {

        if ( !current_user_can( 'administrator' ) ) {

            foreach ( $groups as $group_key => $group ) {

                if ( in_array( $group['name'], ['pricing_fields','post_fields'] ) ) {
                    unset( $groups[$group_key] );
                }

                if ( $group['name'] == 'standard_fields' ) {

                    foreach ( $group['fields'] as $field_key => $field ) {
                        
                        if ( in_array( $field['data-type'], [ 'html', 'section', 'image_choice', 'multi_choice' ] ) ) {
                            unset($groups[$group_key]['fields'][$field_key]);
                        }
                    }

                }

                if ( $group['name'] == 'advanced_fields' ) {

                    foreach ( $group['fields'] as $field_key => $field ) {

                        if ( in_array( $field['data-type'], [ 'date', 'time', 'name', 'address', 'captcha', 'multiselect', 'consent' ] ) ) {
                            unset($groups[$group_key]['fields'][$field_key]);
                        }
                    }

                }

            }

        }

        return $groups;

	}


/*=====  FIN Champs non disponibles  =====*/

/*======================================
=            Champs customs            =
======================================*/

/*----------  PC Date  ----------*/

class GF_Field_Pc_Date extends GF_Field {
 
    public $type = 'pcdate';

    public function get_form_editor_field_title() { return 'Date'; }

    public function get_form_editor_button() {
    	return array(
    		'group' => 'advanced_fields',
    		'text'  => $this->get_form_editor_field_title(),
        );
    }

    public function get_form_editor_field_icon() { return 'gform-icon--date'; }

    function get_form_editor_field_settings() {
        return array(
            'conditional_logic_field_setting',
            'prepopulate_field_setting',
            'error_message_setting',
            'label_setting',
            'admin_label_setting',
            'rules_setting',
            'duplicate_setting',
            'default_value_setting',
            'description_setting'
        );
    }

    public function get_field_input( $form, $value = '', $entry = null ) { 

        $id = (int) $this->id;

        // TODO settings

        if ($this->is_form_editor()) {
            return '<div class="ginput_container ginput_container_text"><input name="input_'.$id.'" id="input_'.$id.'" type="text" value="" aria-invalid="false" disabled="disabled"></div>';
        }

        return '<div class="ginput_container ginput_container_'.$this->type.'"><input name="input_'.$id.'" id="input_'.$id.'" type="date" value="" aria-invalid="false"></div>';
     }
 
}

GF_Fields::register( new GF_Field_Pc_Date() );


/*----------  PC Time  ----------*/

class GF_Field_Pc_Time extends GF_Field {
 
    public $type = 'pctime';

    public function get_form_editor_field_title() { return 'Heure'; }

    public function get_form_editor_button() {
    	return array(
    		'group' => 'advanced_fields',
    		'text'  => $this->get_form_editor_field_title(),
        );
    }

    public function get_form_editor_field_icon() { return 'gform-icon--time'; }

    function get_form_editor_field_settings() {
        return array(
            'conditional_logic_field_setting',
            'prepopulate_field_setting',
            'error_message_setting',
            'label_setting',
            'admin_label_setting',
            'rules_setting',
            'duplicate_setting',
            'default_value_setting',
            'description_setting'
        );
    }

    public function get_field_input( $form, $value = '', $entry = null ) { 

        $id = (int) $this->id;

        // TODO settings

        if ($this->is_form_editor()) {
            return '<div class="ginput_container ginput_container_'.$this->type.'"><input name="input_'.$id.'" id="input_'.$id.'" type="text" value="" aria-invalid="false" disabled="disabled"></div>';
        }
        return '<div class="ginput_container ginput_container_'.$this->type.'"><input name="input_'.$id.'" id="input_'.$id.'" type="time" value="" aria-invalid="false"></div>';
    }
 
}

GF_Fields::register( new GF_Field_Pc_Time() );


/*----------  Téléphone  ----------*/

add_filter( 'gform_phone_formats', 'pc_admin_gravityforms_phone_format' );

    function pc_admin_gravityforms_phone_format( $phone_formats ) {

        return array(
            'standard'          => array(
                'label'             => '01 23 45 67 89',
                'mask'              => '99 99 99 99 99',
                'regex'             => '/^\d{2} \d{2} \d{2} \d{2} \d{2}$/',
                'instruction'       => '01 23 45 67 89',
            ),
            'international'     => array(
                'label'             => 'Sans format',
                'mask'              => false,
                'regex'             => false,
                'instruction'       => false
            )
        );

    }


/*=====  FIN Champs customs  =====*/

/*=========================================
=            Options de champs            =
=========================================*/

add_action( "gform_editor_js", "pc_admin_gravityforms_editor_js" );

    function pc_admin_gravityforms_editor_js() {

        if ( !current_user_can( 'administrator' ) ) { ?>

            <script type="text/javascript">

                console.log(fieldSettings);

                fieldSettings['text'] = '.conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .input_mask_setting, .maxlen_setting .rules_setting, .duplicate_setting, .default_value_setting, .description_setting, .autocomplete_setting';
                // .label_placement_setting, .visibility_setting, .css_class_setting, .size_setting, .placeholder_setting, .password_field_setting,

                fieldSettings['textarea'] = '.conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .maxlen_setting, .size_setting, .rules_setting, .duplicate_setting, .default_value_textarea_setting, .description_setting';
                // .label_placement_setting, .visibility_setting, .css_class_setting, .rich_text_editor_setting, .placeholder_textarea_setting

                fieldSettings['select'] = 'conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .choices_setting, .rules_setting, .default_value_setting, .duplicate_setting, .description_setting, .autocomplete_setting';
                // .label_placement_setting, .visibility_setting, .css_class_setting, .size_setting, .enable_enhanced_ui_setting, .placeholder_setting

                fieldSettings['number'] = '.conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .number_format_setting, .range_setting, .rules_setting, .duplicate_setting, .default_value_setting, .description_setting, .calculation_setting, .autocomplete_setting';
                // .label_placement_setting, .visibility_setting, .css_class_setting, .size_setting, .placeholder_setting

                fieldSettings['checkbox'] = '.conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .choices_setting, .rules_setting, .description_setting, .select_all_choices_setting';
                // .label_placement_setting, .visibility_setting, .css_class_setting

                fieldSettings['radio'] = '.conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .choices_setting, .rules_setting, .duplicate_setting, .description_setting, .other_choice_setting';
                // .label_placement_setting, .visibility_setting, .css_class_setting

                fieldSettings['page'] = '.next_button_setting, .previous_button_setting, .conditional_logic_page_setting, .conditional_logic_nextbutton_setting';
                // .css_class_setting,

                fieldSettings['phone'] = '.conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .rules_setting, .duplicate_setting, .default_value_setting, .description_setting, .phone_format_setting, .autocomplete_setting';
                // .css_class_setting, .visibility_setting, .label_placement_setting, .size_setting, .placeholder_setting

                fieldSettings['website'] = '.conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .rules_setting, .duplicate_setting, .default_value_setting, .description_setting';
                // .css_class_setting, .visibility_setting, .label_placement_setting, .size_setting, .placeholder_setting

                fieldSettings['email'] = '.conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .email_confirm_setting, .admin_label_setting, .rules_setting, .duplicate_setting, .default_value_setting, .description_setting, .autocomplete_setting';
                // .css_class_setting, .visibility_setting, .label_placement_setting, .size_setting, .placeholder_setting

                fieldSettings['fileupload'] = '.conditional_logic_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .rules_setting, .file_extensions_setting, .file_size_setting, .multiple_files_setting, .description_setting';
                // .css_class_setting, .visibility_setting, .label_placement_setting

                fieldSettings['list'] = '.conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .rules_setting, .description_setting';
                // .css_class_setting, .visibility_setting, .label_placement_setting, .add_icon_url_setting, .delete_icon_url_setting, .columns_setting, .maxrows_setting

                fieldSettings['submit'] = '.conditional_logic_submit_setting, .submit_text_setting, .submit_width_setting, .submit_location_setting';
                // .submit_type_setting, .submit_image_setting

            </script>

        <?php } 

    }


/*=====  FIN Options de champs  =====*/

/*====================================
=            Block Editor            =
====================================*/

add_filter( 'acf/load_field/key=field_673efa21a7cf4', 'pc_gravityforms_bloc_popuplate_select' );

function pc_gravityforms_bloc_popuplate_select( $field ) {

    $forms = GFAPI::get_forms();
    $field['choices'] = array();

    foreach ( $forms as $form ) {
        $field['choices'][$form['id']] = $form['title'];
    }

    return $field;

}


/*=====  FIN Block Editor  =====*/

/*================================
=            Tiny MCE            =
================================*/

// + cf. admin css

add_filter( 'media_library_show_video_playlist', '__return_false' );
add_filter( 'media_library_show_audio_playlist', '__return_false' );

add_filter( 'tiny_mce_before_init', 'pc_admin_gravityforms_tiny_mce_before_init', 10, 2 );

    function pc_admin_gravityforms_tiny_mce_before_init( $settings, $editor_id ) {

        if ( $editor_id == '_gform_setting_message' ) {

            $settings['toolbar1'] = 'fullscreen,undo,redo,removeformat,|,formatselect,bullist,numlist,|,bold,italic,strikethrough,superscript,charmap,|,alignleft,aligncenter,|,link,unlink';
            $settings['toolbar2'] = '';
            $settings['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3';
            // $settings['visualblocks_default_state'] = true;
            $settings['paste_as_text'] = true;

        }
        
        return $settings;

    }

    
add_filter( 'wp_editor_settings', 'pc_admin_gravityforms_editor_settings', 10, 2 );

    function pc_admin_gravityforms_editor_settings ( $settings, $editor_id ) {

        if ( $editor_id == '_gform_setting_message' && !current_user_can('administrator') ) {
            $settings['quicktags'] = false; // voir html
        }
        return $settings;

    }

add_action( 'after_setup_theme', 'pc_admin_gravityforms_after_setup_theme' );

    function pc_admin_gravityforms_after_setup_theme() {

        update_option( 'image_default_size', 'thumbnail_s' ); // taille d'image sélectionnée par défaut dans la modale

    }

add_filter( 'image_size_names_choose', 'pc_admin_gravityforms_image_size_name_choose' );

    function pc_admin_gravityforms_image_size_name_choose( $sizes ) {

        return array( // options select taille à insérer
            'thumbnail_s' => '1/4',
            'thumbnail' => '1/2',
            'medium'    => '1'
        );
        
    }

add_filter( 'img_caption_shortcode', 'pc_admin_gravityforms_img_caption_shortcode', 10, 3 );

    function pc_admin_gravityforms_img_caption_shortcode( $empty, $attr, $content ) {

        // html si légende
        return '<figure class="wp-caption '.$attr['align'].'">'.$content.'<figcaption style="max-width:'.$attr['width'].'px">'.$attr['caption'].'</figcaption></figure>';

    }

/*=====  FIN Tiny MCE  =====*/

/*=============================
=            Front            =
=============================*/

add_filter( 'gform_disable_form_theme_css', '__return_true' );


/*=====  FIN Front  =====*/