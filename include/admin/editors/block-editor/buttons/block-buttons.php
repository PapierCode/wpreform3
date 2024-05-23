<?php
$errors = array();
$buttons = array();
$fields = get_fields();

$button_1 = pc_acf_block_get_bloc_btn_attrs( $fields, 1, $is_preview );
if ( is_array( $button_1 ) ) { $buttons[] = $button_1;
} else { $errors[] = $button_1; }

if ( $fields['bloc_btn_2_display'] ) {
    $button_2 = pc_acf_block_get_bloc_btn_attrs( $fields, 2, $is_preview );
    if ( is_array( $button_2 ) ) { $buttons[] = $button_2;
    } else { $errors[] = $button_2; }

}

if ( !empty( $errors ) ) {

	if ( $is_preview ) { echo '<p class="editor-error">Erreur bloc <em>Bouton(s)</em> : '.implode( ', ', $errors ).'</p>'; }

} else {
		
	/*----------  Bloc attributs  ----------*/
	
	$block_css = array(
		'bloc-buttons',
		'bloc-text-align--'.get_field('bloc_text_align')
	);
	if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }
	
	$block_attrs = array( 'class="'.implode( ' ', $block_css ).'"' );
	if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs[] = 'id="'.$block['anchor'].'"'; }

	/*----------  Affichage  ----------*/ 

	echo '<div '.implode(' ',$block_attrs).'>';
	
		if ( count( $buttons ) > 1 ) { echo '<ul class="reset-list">'; }
		foreach ( $buttons as $button ) {

			if ( count( $buttons ) > 1 ) { echo '<li>'; }
				echo '<a '.implode( ' ', $button['attrs'] ).'><span class="ico">'.$button['ico'].'</span><span class="txt">'.$button['txt'].'</span></a>';
			if ( count( $buttons ) > 1 ) { echo '</li>'; }

		}
		if ( count( $buttons ) > 1 ) { echo '</ul>'; }

	echo '</div>';

}