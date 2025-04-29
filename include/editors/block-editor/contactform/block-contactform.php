<?php
$contact_form_fields = array(
	'prefix'        => 'contact',
	'fields'        => array(
		array(
			'type'      				=> 'text',
			'id'        				=> 'last-name',
			'label'     				=> 'Nom',
			'label-en'     				=> 'Last Name',
			'attr'						=> 'autocomplete="family-name"',
			'notification-from-name'	=> true // pour la notification mail
		),
		array(
			'type'      				=> 'text',
			'id'        				=> 'name',
			'label'     				=> 'Prénom',
			'label-en'     				=> 'First Name',
			'attr'						=> 'autocomplete="given-name"',
		),
		array(
			'type'      				=> 'text',
			'id'        				=> 'phone',
			'label'     				=> 'Téléphone',
			'label-en'     				=> 'Phone',
			'attr'						=> 'autocomplete="tel"'
		),
		array(
			'type'      				=> 'email',
			'id'        				=> 'mail',
			'label'     				=> 'E-mail',
			'label-en'     				=> 'E-mail',
			'required' 	    			=> true,
			'notification-from-email'	=> true, // pour la notification mail
			'attr'						=> 'autocomplete="email"'
		),
		array(
			'type'      				=> 'textarea',
			'id'        				=> 'message',
			'label'     				=> 'Message',
			'label-en'     				=> 'Message',
			'attr'						=> 'rows="5"',
			'required' 	    			=> true
		)
	)
);

$block_form_to = get_field('to');

$to = '';
foreach ( $block_form_to as $key => $email ) { 
	if ( is_email( $email['email'] ) ) {
		if ( $key > 0 ) { $to .= ','; }
		$to .= $email['email'];
	}
}

if ( $to ) {

	$block_css = array( 'bloc-contactform' );
	if ( $is_preview ) { $block_css[] = 'bloc-no-preview'; }
	if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }	
	
	$block_attrs = array( 'class' => implode( ' ', $block_css ) );
	if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs['id'] = $block['anchor']; }

	if ( class_exists( 'PC_Contact_Form' ) ) {
		$block_attrs = apply_filters( 'pc_filter_acf_block_contact_form_attrs', $block_attrs, $block, $is_preview );
		echo '<div '.pc_get_attrs_to_string( $block_attrs ).'>';

			if ( $is_preview ) { 
				echo '<p><strong>Formulaire de contact</strong></p>'; 
			} else {
				global $post;
				$contact_form = new PC_Contact_Form( $contact_form_fields, $post, $to );
				$contact_form->display_form();
			}

		echo '</div>';
	}

} else {

	echo '<p class="bloc-warning">Erreur bloc <em>Formulaire de contact</em> : saisissez au moins un e-mail valide.</p>';

}