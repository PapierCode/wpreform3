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

        // cf. https://docs.gravityforms.com/role-management-guide/
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
            $form ['is_active'] = true;
            GFAPI::update_form( $form );
        } else {
            if ( is_array( $form['pagination'] ) ) {
                $form['pagination']['display_progressbar_on_confirmation'] = true; // commence à 0
                $form ['is_active'] = true;
                GFAPI::update_form( $form );
            }
        }

    }


/*=====  FIN Paramètres formulaires  =====*/

/*========================================================
=            Formulaire paramétres par défaut            =
========================================================*/

add_action( 'gform_after_save_form', 'pc_admin_gravityforms_notification_settings_default', 10, 2 );

    function pc_admin_gravityforms_notification_settings_default( $form, $is_new ) {

        if ( $is_new ) {    
            foreach ( $form['notifications'] as &$notification ) {
                $current_user = wp_get_current_user();
                $notification['to'] = $current_user->user_email;
                $notification['fromName'] = get_field( 'coord_name', 'option' ) ?? get_bloginfo('name');
                $notification['disableAutoformat'] = true;
            }   
            foreach ( $form['confirmations'] as &$confirmation ) {
                $confirmation['disableAutoformat'] = true;
            }     
            GFAPI::update_form( $form );
        }

    }


/*=====  FIN Formulaire paramétres par défaut  =====*/

/*====================================
=            Confirmation            =
====================================*/

add_filter( 'gform_confirmation_anchor', '__return_true' ); // scrollto

add_filter( 'gform_confirmation_settings_fields', 'pc_admin_gravityforms_confirmation_settings_fields' );

    function pc_admin_gravityforms_confirmation_settings_fields( $fields ) {

        if ( !current_user_can( 'administrator' ) ) { 
            foreach ( $fields[0]['fields'] as $key => $field ) {
                if ( rgar( $field, 'name' ) == 'disableAutoformat' ) { unset( $fields[0]['fields'][$key]); }
            }
        }
        return $fields;

    }

add_filter( 'gform_confirmation', 'custom_confirmation', 10, 4 );
    function custom_confirmation( $confirmation, $form, $entry, $ajax ) {
        if ( !is_array($confirmation) ) {
            $confirmation = wpautop($confirmation);
        }
        return $confirmation;
    }


/*=====  FIN Confirmation  =====*/

/*====================================
=            Notification            =
====================================*/

add_filter( 'gform_notification_settings_fields', 'pc_admin_gravityforms_notification_settings_fields' );

    function pc_admin_gravityforms_notification_settings_fields( $fields ) {

        if ( !current_user_can( 'administrator' ) ) {
            foreach ( $fields[0]['fields'] as $key => $field ) {
                if ( rgar( $field, 'name' ) == 'disableAutoformat' ) { unset( $fields[0]['fields'][$key]); }
                if ( rgar( $field, 'name' ) == 'from' ) { unset( $fields[0]['fields'][$key]); }
            }
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

        $form_id = (int) $form['id'];
        $field_id = (int) $this->id;

        $field_attrs = array(
            'name' => 'input_'.$field_id,
            'id' => 'input_'.$form_id.'_'.$field_id,
            'type' => 'date',
            'aria-invalid' => $this->failed_validation ? 'true' : 'false',
            'value' => esc_attr( $value )
        );

        if ( $this->isRequired ) { $field_attrs['aria-required'] = 'true'; }
        if ( !empty($this->description) ) { $field_attrs['aria-describedby'] = 'gfield_description_'.$form_id.'_'.$field_id; }

        if ($this->is_form_editor()) {
            $field_attrs['id'] = 'input_'.$field_id;
            $field_attrs['disabled'] = 'disabled';
        }

        return '<div class="ginput_container ginput_container_'.$this->type.'"><input '.pc_get_attrs_to_string($field_attrs).'></div>';

     }

     public function validate( $value, $form ) {

         if ( !preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $value ) ) {
             $this->failed_validation = true;
             $this->validation_message = 'Le format de la date n\'est pas valide';
         }

     }

     public function get_value_save_entry( $value, $form, $input_name, $lead_id, $lead ) {
 
        if ( preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $value ) ) {
            $explode = explode( '-', $value );
            $value = $explode[2].'-'.$explode[1].'-'.$explode[0];
        }
     
        return $value;

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

        $form_id = (int) $form['id'];
        $field_id = (int) $this->id;

        $field_attrs = array(
            'name' => 'input_'.$field_id,
            'id' => 'input_'.$form_id.'_'.$field_id,
            'type' => 'time',
            'aria-invalid' => $this->failed_validation ? 'true' : 'false',
            'value' => esc_attr( $value )
        );

        if ( $this->isRequired ) { $field_attrs['aria-required'] = 'true'; }
        if ( !empty($this->description) ) { $field_attrs['aria-describedby'] = 'gfield_description_'.$form_id.'_'.$field_id; }

        if ($this->is_form_editor()) {
            $field_attrs['id'] = 'input_'.$field_id;
            $field_attrs['disabled'] = 'disabled';
        }

        return '<div class="ginput_container ginput_container_'.$this->type.'"><input '.pc_get_attrs_to_string($field_attrs).'></div>';

    }

    public function validate( $value, $form ) {

        if ( $value !== '' && !preg_match('/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $value ) ) {
            $this->failed_validation = true;
            $this->validation_message = 'Le format de l\'heure n\'est pas valide';
        }

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

/*----------  Fichier  ----------*/

add_filter( 'gform_field_content', 'pc_gravityforms_fileupload_html', 10, 2 );

    function pc_gravityforms_fileupload_html( $field_content, $field ) {

        if ( !is_admin() && $field->type == 'fileupload' ) {
            $new = '<div class="input-file" aria-hidden="true"><button type="button" class="input-file-btn">'.pc_svg('upload').'</button><div class="input-file-msg">Aucun fichier sélectionné.</div></div>';
            $field_content = preg_replace( '/'.preg_quote('<input').'/', $new.'<input', $field_content, 1 );
        }
        return $field_content;

    }


/*=====  FIN Champs customs  =====*/

/*=========================================
=            Options de champs            =
=========================================*/

// TODO custom field condition
/* add_action( 'admin_print_scripts', function () {
 
    if ( method_exists( 'GFForms', 'is_gravity_page' ) && GFForms::is_gravity_page() ) { ?>
      <script type="text/javascript">
          gform.addFilter( 'gform_conditional_logic_fields', 'set_conditional_field' );
          function set_conditional_field( options, form, selectedFieldId ){
              console.log(options);
              console.log(form);
              console.log(selectedFieldId);
              
             return options;
          }
      </script>
    <?php }
   
  } );
*/

add_action( "gform_editor_js", "pc_admin_gravityforms_editor_js" );

    function pc_admin_gravityforms_editor_js() {

        if ( !current_user_can( 'administrator' ) ) { ?>

            <script type="text/javascript">

                // console.log(fieldSettings);

                fieldSettings['text'] = '.conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .input_mask_setting, .maxlen_setting, .rules_setting, .duplicate_setting, .default_value_setting, .description_setting, .autocomplete_setting';
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

                fieldSettings['email'] = '.conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .rules_setting, .duplicate_setting, .default_value_setting, .description_setting, .autocomplete_setting';
                // .css_class_setting, .visibility_setting, .label_placement_setting, .size_setting, .placeholder_setting, .email_confirm_setting

                fieldSettings['fileupload'] = '.conditional_logic_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .rules_setting, .file_extensions_setting, .file_size_setting, .description_setting';
                // .css_class_setting, .visibility_setting, .label_placement_setting, .multiple_files_setting

                fieldSettings['list'] = '.conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .rules_setting, .description_setting';
                // .css_class_setting, .visibility_setting, .label_placement_setting, .add_icon_url_setting, .delete_icon_url_setting, .columns_setting, .maxrows_setting

                fieldSettings['submit'] = '.conditional_logic_submit_setting, .submit_text_setting, .submit_width_setting, .submit_location_setting';
                // .submit_type_setting, .submit_image_setting

                // selecteur étape / barre de prgression
                jQuery('[name="pagination_type"]').change(function() {
                    if ( jQuery(this).val() == 'percentage' ) {
                        jQuery('#percentage_confirmation_display').prop('checked',true);
                        jQuery('.percentage_confirmation_page_name_setting').show();
                    }
                });

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

            $settings['toolbar1'] = 'undo,redo,removeformat,|,bullist,numlist,|,bold,italic,|,alignleft,aligncenter,|,link,unlink';
            $settings['toolbar2'] = '';
            $settings['visualblocks_default_state'] = true;
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


/*=====  FIN Tiny MCE  =====*/

/*=============================
=            Front            =
=============================*/

/*----------  Submit, input to button   ----------*/

// cf. https://docs.gravityforms.com/gform_submit_button/

add_filter( 'gform_next_button', 'pc_gravityforms_input_to_button', 10, 2 );
add_filter( 'gform_previous_button', 'pc_gravityforms_input_to_button', 10, 2 );
add_filter( 'gform_submit_button', 'pc_gravityforms_input_to_button', 10, 2 );

    function pc_gravityforms_input_to_button( $button, $form ) {

        $fragment = WP_HTML_Processor::create_fragment( $button );
        $fragment->next_token();
    
        $attributes = array( 'id', 'type', 'class', 'onclick' );
        $new_attributes = array();
        foreach ( $attributes as $attribute ) {
            $value = $fragment->get_attribute( $attribute );
            if ( ! empty( $value ) ) {
                $new_attributes[] = sprintf( '%s="%s"', $attribute, esc_attr( $value ) );
            }
        }
    
        return sprintf( '<button %s>%s</button>', implode( ' ', $new_attributes ), esc_html( $fragment->get_attribute( 'value' ) ) );

    }


/*----------  Validation  ----------*/

add_filter( 'gform_validation_message', 'pc_gravityform_validation_message', 10, 2 );

    function pc_gravityform_validation_message( $message, $form ) {

        if ( gf_upgrade()->get_submissions_block() ) {
            return $message;
        }
    
        $message = '<div class="ico">'.pc_svg('msg').'</div>';
        $message .= '<p>Le formulaire contient des erreurs&nbsp;:</p>';
        $message .= '<ul>';    
            foreach ( $form['fields'] as $field ) {
                if ( $field->failed_validation ) {
                    $message .= sprintf( '<li>%s - %s</li>', GFCommon::get_label( $field ), $field->validation_message );
                }
            }
        $message .= '</ul>';
    
        return $message;
        
    };




/*=====  FIN Front  =====*/