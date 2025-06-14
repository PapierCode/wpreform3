<?php
$contact_form_fields = array(
	'prefix'        => 'contact',
	'fields'        => array(
		array(
			'type'      				=> 'text',
			'id'        				=> 'last-name',
			'label'     				=> __('Last Name','wpreform'),
			'attr'						=> 'autocomplete="family-name"',
			'notification-from-name'	=> true // pour la notification mail
		),
		array(
			'type'      				=> 'text',
			'id'        				=> 'name',
			'label'     				=> __('First Name','wpreform'),
			'attr'						=> 'autocomplete="given-name"',
		),
		array(
			'type'      				=> 'text',
			'id'        				=> 'phone',
			'label'     				=> __('Phone','wpreform'),
			'attr'						=> 'autocomplete="tel"'
		),
		array(
			'type'      				=> 'email',
			'id'        				=> 'mail',
			'label'     				=> __('E-mail','wpreform'),
			'required' 	    			=> true,
			'notification-from-email'	=> true, // pour la notification mail
			'attr'						=> 'autocomplete="email"'
		),
		array(
			'type'      				=> 'textarea',
			'id'        				=> 'message',
			'label'     				=> __('Message','wpreform'),
			'attr'						=> 'rows="5"',
			'required' 	    			=> true
		)
	)
);

$block_form_to = get_field('to');

$to = '';

if ( is_array($block_form_to) ) {
	foreach ( $block_form_to as $key => $email ) { 
		if ( is_email( $email['email'] ) ) {
			if ( $key > 0 ) { $to .= ','; }
			$to .= $email['email'];
		}
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

} else if ( $is_preview ) {

	echo '<p class="bloc-warning">Erreur bloc <em>Formulaire de contact</em> : saisissez au moins un e-mail valide.</p>';

}