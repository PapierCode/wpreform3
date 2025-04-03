<?php

/*----------  Mise à jour nom du site  ----------*/

add_action('acf/options_page/save', 'pc_admin_acf_coordname_to_blogname', 10, 2);

	function pc_admin_acf_coordname_to_blogname( $post_id, $menu_slug ) {

		if ( 'site-settings' == $menu_slug ) {
			update_option( 'blogname', get_field( 'coord_name', $post_id) );
		}

	}


/*----------  Google Map API key  ----------*/

add_filter('acf/fields/google_map/api', 'pc_admin_acf_google_map_api_key');

	function pc_admin_acf_google_map_api_key( $api ) {

		$api['key'] = get_option( 'options_wpr_google_api_map_key' );
		return $api;
		
	}

/*----------  Validation format téléphone  ----------*/

// https://www.advancedcustomfields.com/resources/acf-validate_value/

function pc_admin_acf_validate_phone( $valid, $value, $field, $input_name ) {

    if ( ( $field['required'] || trim($value) != '' ) && !preg_match( '/^\d{2} \d{2} \d{2} \d{2} \d{2}$/', trim($value) ) ) {
		return 'Le format est incorrect.';
	}

    return $valid;
}

/*----------  Types de fichiers  ----------*/

add_filter( 'acf/load_field/type=file', 'pc_admin_acf_file_mimes' );

	function pc_admin_acf_file_mimes( $field ) {

		$field['mime_types'] = 'pdf';
		return $field;

	}

add_filter( 'acf/load_field/type=image', 'pc_admin_acf_image_mimes' );
add_filter( 'acf/load_field/type=gallery', 'pc_admin_acf_image_mimes' );

	function pc_admin_acf_image_mimes( $field ) {

		$field['mime_types'] = 'jpg,jpeg,png,webp';
		return $field;

	}

/*----------  Résumé, compteur titre & description  ----------*/

add_filter( 'acf/load_field/name=post_short_title', 'pc_admin_acf_characters_counter' );
add_filter( 'acf/load_field/name=post_excerpt', 'pc_admin_acf_characters_counter' );

	function pc_admin_acf_characters_counter( $field ) {

        switch ( $field['name'] ) {
            case 'post_short_title':
                $for = apply_filters( 'pc_filter_post_short_title_for', 'Pour le fil d\'ariane & le résumé.', $field );
                $length = apply_filters( 'pc_filter_post_short_title_length', 40, $field );
                break;
            case 'post_excerpt':
                $for = apply_filters( 'pc_filter_post_excerpt_for', 'Pour le résumé.', $field );
                $length = apply_filters( 'pc_filter_post_excerpt_length', 150, $field );
                break;
        }

		$field['instructions'] = $for.'<br><span class="pc-txt-length-counter" data-size="'.$length.'"><span class="pc-txt-length-value">0</span> / '.$length.' caractères conseillés.</span>';

		return $field;

	}

