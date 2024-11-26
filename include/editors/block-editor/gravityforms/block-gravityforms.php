<?php
$form_id = get_field('form_id');

if ( $form = GFAPI::get_form( $form_id ) ) {

    // attributs bloc ? css ? id ?

    if ( !$is_preview ) {
        // https://docs.gravityforms.com/adding-a-form-to-the-theme-file/
        gravity_form( $form_id, false, false );
    } else {
        // ??
        echo '<div class="bloc-no-preview"><p><strong>Formulaire</strong> :<br> '.$form['title'].'</p></div>';
    }

} else {

	echo '<p class="bloc-warning">Erreur bloc <em>Formulaire</em> : saisissez un formulaire valide.</p>';

}