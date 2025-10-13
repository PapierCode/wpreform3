<?php

class PC_Contact_Form {

	private $from_post;				// [int] origine de la demande
	private $from_product;			// [bool] origine solution ou formation
	private $post_name_product;		// [string] nom de l'objet post
	private $captcha;				// [object] captcha
	private $captcha_type;			// [string] captcha

	private $label_required;		// [string] label champ obligatoire
	private $css;					// [array] classes css, exceptés les champs
	private $notification;			// [array] paramètres notification

	public $fields = array();		// [array] liste & paramètres des champs

	public $errors;					// [array][bool] types d'errreurs
	public $done;					// [bool] terminé


	/*=================================
	=            Construct            =
	=================================*/
	
	public function __construct ( $post_fields, $post, $to ) {

		/*----------  Post d'origine  ----------*/

		$this->from_post = $post;

		/*----------  Validation finale  ----------*/
		
		$this->done = false;

		/*----------  Types d'erreur  ----------*/
		
		$this->errors = array(
			'field' 		=> false, // erreur champ
			'captcha'		=> false, // erreur captcha
			'notification'	=> false, // erreur envoi email
		);

		/*----------  Label champ obligatoire  ----------*/

		$this->label_required = '&nbsp;<span class="form-label-required"> /&nbsp;'.__('Required','wpreform').'</span>';

		/*----------  CSS containers  ----------*/
		
		$this->css = array(
			'form-container' => array( 'form', 'form--contact', 'no-print' ),
			'button-submit'	=> array( 'form-submit button' )
		);

		/*----------  Création captcha  ----------*/

		switch ( get_field('wpr_captcha_type','option') ) {
			case 'hcaptcha':
				$this->captcha_type = 'hcaptcha';
				$this->captcha = new PC_Hcaptcha( get_field('wpr_captcha_site_key','option'), get_field('wpr_captcha_secret_key','option') );
				break;
			case 'calcul':
				$this->captcha_type = 'mathcaptcha';
				$this->captcha = new PC_MathCaptcha( get_field('wpr_captcha_password','option'), get_field('wpr_captcha_vector','option') );
				break;
		}

		/*----------  Configuration des champs  ----------*/
		
		$this->prepare_fields( $post_fields );

		/*----------  Notification  ----------*/

		$this->notification = array(
			'to-email' 		=> $to,	// email du destinataire
			'to-subject' 	=> 'Contact depuis '.$_SERVER['SERVER_NAME'], // sujet de l'email
			'from-name'		=> 'Sans nom',	// nom de l'expéditeur
			'from-email'	=> '',			// email de l'expéditeur
			'content'		=> ''			// contenu de l'email
		);

		/*----------  Validation du formulaire  ----------*/
		
		if ( isset($_POST['none-pc-contact-form']) && wp_verify_nonce( $_POST['none-pc-contact-form'], basename( __FILE__ ) ) ) {
			$this->validation_form();
		}

	}
	
	/*=====  FIN Construct  =====*/

	/*==============================================
	=            Préparation des champs            =
	==============================================*/
	
	private function prepare_fields( $post_fields ) {
	
		foreach ( $post_fields['fields'] as $field ) {

			// suivant si conditionné à une variable
			if ( isset( $field['form-in-if-query-var'] ) && get_query_var( $field['form-query-var'] ) ) { continue; }
			// attributs id/name
			$name = $post_fields['prefix'].'-'.$field['id'];

			// paramètres de base
			$params = array(
				'type' 	=> $field['type'],
				'css' 	=> array(
					'form-item',
					'form-item--'.$field['type'],
					'form-item--'.$name
				),
				'attr' => array(),
				'error' => false
			);

			// labels
			$params['label'] = $field['label'];

			// obligatoire			
			if ( isset( $field['required'] ) ) {
				$params['attr'][] = 'required';
				$params['required'] = true;
			}

			// attributs customs
			if ( isset( $field['attr'] ) ) { 
				$params['attr'] = array_merge( $params['attr'], explode( ' ' , $field['attr'] ) );
			}

			// options select
			if ( isset( $field['options'] ) ) {
				$params['options'] = $field['options'];
			}

			// description
			if ( isset( $field['form-desc'] ) ) {
				$params['desc'] = $field['form-desc'];
				$params['attr'][] = 'aria-describedby="form-item-desc-'.$name.'"';
			}

			// associé à une variable d'url
			if ( isset( $field['form-query-var'] ) ) { $params['query-var'] = $field['form-query-var']; }

			// paramètres notification
			if ( isset( $field['notification-from-email'] ) ) { $params['notification-from-email'] = true; }
			if ( isset( $field['notification-from-name'] ) ) { $params['notification-from-name'] = true; }
			if ( isset( $field['notification-not-in'] ) ) { $params['notification-not-in'] = true; }

			// ajout à la liste		
			$this->fields[$name] = $params;

		}

	}
	
	
	/*=====  FIN Préparation des champs  =====*/

	/*=============================================
	=            Validation des champs            =
	=============================================*/

	private function validation_fields() {

		foreach ( $this->fields as $name => $params ) {
							
			switch ( $params['type'] ) {
	
				case 'checkbox':

					if ( !isset( $_POST[$name] ) && isset( $params['required'] ) ) {
						$this->fields[$name]['error'] = true;

						if ( isset( $params['rgpd'] ) ) {
							$this->fields[$name]['msg-error'] = 'You must <strong>accept the General Conditions of Use</strong>.';
						}

					} else if ( isset( $_POST[$name] ) ) {
						$this->fields[$name]['attr'][] = 'checked';
						$this->fields[$name]['value'] = 1;
					}

					break;
	
				case 'email':

					if ( ( trim( $_POST[$name] ) === '' && isset( $params['required'] ) ) ) {
						$this->fields[$name]['error'] = true;

					} else if ( '' !== trim( $_POST[$name] ) ) {

						if ( !is_email( trim( $_POST[$name] ) ) ) {
							$this->fields[$name]['value'] = $_POST[$name];
							$this->fields[$name]['error'] = true;
							$this->fields[$name]['msg-error'] = sprintf( __('The format of the %s field is not valid.','wpreform'), '<strong>'.$params['label'].'</strong>' );

						} else {
							$this->fields[$name]['value'] = sanitize_email( $_POST[$name] );
							if ( isset( $params['notification-from-email'] ) ) { $this->notification['from-email'] = $this->fields[$name]['value']; }
						}	

					}

					break;
				
				case 'text':
				case 'select':

					if ( trim( $_POST[$name] ) === '' && isset( $params['required'] ) ) {
						$this->fields[$name]['error'] =  true;
					} else if ( trim( $_POST[$name] !== '' ) ) {
						$this->fields[$name]['value'] = sanitize_text_field( stripslashes($_POST[$name]) );
						if ( $name == 'contact-solution' ) { $this->fields[$name]['value'] = get_the_title( $this->fields[$name]['value'] ); }
						if ( isset( $params['notification-from-name'] ) ) { $this->notification['from-name'] = $this->fields[$name]['value']; }
					}

					break;
				
				case 'textarea':

					if ( trim( $_POST[$name] ) === '' && isset( $params['required'] ) ) {
						$this->fields[$name]['error'] =  true;
					} else if ( trim( $_POST[$name] !== '' ) ) {
						$this->fields[$name]['value'] = sanitize_textarea_field( stripslashes($_POST[$name]) );
					}

					break;
	
			}

			// si champ en erreur
			if ( $this->fields[$name]['error'] ) {

				// css erreur
				$this->fields[$name]['css'][] = 'form-item--error';
				// aria invalid
				$this->fields[$name]['attr'][] = 'aria-invalid="true"';
				// erreur formulaire
				$this->errors['field'] = true;

			}

		}

		// si captcha en erreur
		if ( is_object( $this->captcha ) && $this->captcha->validate() === false ) { $this->errors['captcha'] = true; }

	}


	/*=====  FIN Validation des champs  =====*/

	/*=======================================
	=            Affichage label            =
	=======================================*/
	
	private function display_label( $name, $params ) {

		$label = $params['label'];
		if ( isset( $params['required'] ) ) { $label .= $this->label_required; }
		
		echo '<label class="form-label" for="'.$name.'"><span>'.$label.'</span></label>';

	}
	
	
	/*=====  FIN Affichage label  =====*/

	/*============================================
	=            Affichage des champs            =
	============================================*/

	function display_fields() {

		foreach ( $this->fields as $name => $params ) {
			

			// valeur à afficher
			$value = '';

			if ( isset( $params['query-var'] ) && get_query_var( $params['query-var'] ) ) {
				$value = stripslashes( get_query_var( $params['query-var'] ) );
			} 
			if ( isset( $params['value'] ) ) { $value = $params['value']; }
		
			// affichage des champs
			echo '<li class="'.implode( ' ', $params['css'] ).'">';

				// label si
				if ( in_array( $params['type'], array( 'text', 'email', 'textarea', 'select', 'captcha' ) ) ) {
					$this->display_label( $name, $params );
				}
				
				echo '<div class="form-item-inner">';

					switch ( $params['type'] ) {

						case 'text':
						case 'email':
							echo '<input type="'.$params['type'].'" id="'.$name.'" name="'.$name.'" value="'.$value.'" '.implode( ' ', $params['attr'] ).'>';
							break;

						case 'textarea':
							echo '<textarea id="'.$name.'" name="'.$name.'" '.implode( ' ', $params['attr'] ).'>'.$value.'</textarea>';
							break;

						case 'select':
							echo '<select id="'.$name.'" name="'.$name.'" '.implode( ' ', $params['attr'] ).'>';
								echo '<option value=""></option>';
								foreach ( $params['options'] as $option_label => $option_value ) {
									echo '<option value="'.$option_value.'" '.selected($value,$option_value,false).'>'.$option_label.'</option>';
								}
							echo '</select>';
							break;
						
						case 'checkbox':
							echo '<input class="visually-hidden" type="checkbox" name="'.$name.'" id="'.$name.'" value="1" '.implode( ' ', $params['attr'] ).' >';
							$this->display_label( $name, $params );
							break;

					}

					// description/aide
					if ( isset( $params['desc'] ) ) { echo '<p id="form-item-desc-'.$name.'" class="form-item-desc">'.$params['desc'].'</p>'; }

				echo '</div>';

			echo '</li>';

		}

	}


	/*=====  FIN Affichage des champs  =====*/

	/*==============================================
	=            Notification par email            =
	==============================================*/

	private function send_notification()  {

		/*----------  Contenu  ----------*/
		
		$notification_content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body>';

		$body_content = '';
		$body_content .= '<p><strong>Page d\'origine :</strong> <a href="'.get_the_permalink($this->from_post->ID).'">'.$this->from_post->post_title.'</a></p>';
					
		foreach ( $this->fields as $name => $params ) {
			
			if ( !isset( $params['notification-not-in'] ) && isset( $params['value'] ) ) {
		
				switch ( $params['type'] ) {

					case 'checkbox':
						$body_content .= '<p><strong>'.$params['label'].' :</strong> oui</p>';
						break;
					
					default:
						$body_content .= '<p><strong>'.$params['label'].' :</strong> '.$params['value'].'</p>';
						break;

				}
		
			}
		
		}
		
		$notification_content .= $body_content.'</body></html>';


		/*----------  Entêtes  ----------*/
		
		$this->notification['headers'] = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: '.$this->notification['from-name'].' <'.$this->notification['from-email'].'>',
		);


		/*----------  Envoi  ----------*/

		$notification_sent = wp_mail(
			$this->notification['to-email'],
			html_entity_decode( $this->notification['to-subject'] ),
			$notification_content,
			$this->notification['headers']
		);

		// si erreur
		if ( !$notification_sent ) {
			$this->errors['notification'] = true;
		}

	}


	/*=====  FIN Notification par email  =====*/

	/*================================================
	=            Validation du formulaire            =
	================================================*/
	
	private function validation_form() {

		// validaton des champs
		$this->validation_fields();

		if ( !$this->errors['field'] && !$this->errors['captcha'] ) { 
			
			// envoi notification
			$this->send_notification();

			if ( !$this->errors['notification'] ) {

				// validation finale
				$this->done = true;

				// reset des valeurs
				foreach ( $this->fields as $name => $params ) {
					unset( $this->fields[$name]['value'] );
					if ( 'checkbox' == $params['type'] ) { 
						$this->fields[$name]['attr'] = array_diff( $this->fields[$name]['attr'], array('checked') );}
				}

			}

		}

	}
	
	
	/*=====  FIN Validation du formulaire  =====*/

	/*==============================================
	=            Affichage des messages            =
	==============================================*/

	private function get_formated_msg_error( $msg ) {

		return '<br>'.$msg.'.';

	}
	
	private function display_content_messages() {

		if ( in_array( true, $this->errors ) ) {

			if ( $this->errors['field'] || $this->errors['captcha'] ) {
				$message_error = '<strong>'.__('The form contains errors','wpreform').'&nbsp;:</strong>';
			}
	
			if ( $this->errors['field'] ) {
	
				foreach ( $this->fields as $params ) {
					if ( $params['error'] ) {
						$message_error .= sprintf( __('The field %1$s%2$s%3$s is required','wpreform'), '<strong>', $params['label'], '</strong>' ).'.';
					}
				}

			}			
	
			if ( $this->errors['captcha'] ) {
	
				$message_error .= '<br>'.$this->captcha->msg_error;

			}

			if ( $this->errors['notification'] ) {
	
				$message_error = sprintf( __('An error occurred, please validate the %1$s antispam%2$s and the %1$sform%2$s again','wpreform'), '<strong>', '</strong>' ).'.';
	
			}

			echo pc_get_message( $message_error, 'error', 'block' );

	
		} else if ( $this->done ) {
	
			echo pc_get_message( __('The message is sent.','wpreform'), 'success', 'block' );
	
		}
	
	
	}
	
	
	/*=====  FIN Affichage des messages  =====*/

	/*============================================
	=            Affichage formulaire            =
	============================================*/
	
	public function display_form() {

		echo '<div id="form-contact" class="'.implode( ' ', $this->css['form-container'] ).'">';
		
		$this->display_content_messages();

		$legal_page_id = get_option('wp_page_for_privacy_policy');
		echo '<p class="form-contact-rgpd-msg">';
			printf( 
				__('The %1$spersonal data%2$s collected in the form below will only be used %1$sto respond to your message%2$s. For more information, see %3$s page','wpreform'), 
				'<strong>',
				'</strong>',
				'<a href="'.get_the_permalink($legal_page_id).'">'.get_the_title($legal_page_id).'</a>'
			);
		echo '.</p>';

		echo '<form method="POST" action="#form-contact" aria-label="'.__('Contact form','wpreform').'">';

			wp_nonce_field( basename( __FILE__ ), 'none-pc-contact-form' );

			echo '<ul class="form-list">';

				/*----------  Champs  ----------*/
				
				$this->display_fields();

				/*----------  Captcha  ----------*/
				
				echo '<li class="form-item form-item--captcha form-item--'.$this->captcha_type.( $this->errors['captcha'] ? ' form-item--error' : '').'">';
				
				switch ( $this->captcha_type ) {
					case 'hcaptcha':
						echo '<span class="label-like form-label" aria-hidden="true">'.$this->captcha->get_field_label_text().$this->label_required.'</span>';
						$this->captcha->display();
						break;
					case 'mathcaptcha':
						$this->display_label( 'form-captcha', array(
							'label'		=> $this->captcha->get_field_label_text(),
							'required'	=> true
						) );
						echo '<div class="form-item-inner">'.$this->captcha->get_field_inputs().'</div>';
						break;
				}
						
				echo '</li>';				

				/*----------  Submit  ----------*/
				
				echo '<li class="form-item form-item--submit">';
					echo '<button type="submit" class="'.implode( ' ', $this->css['button-submit'] ).'"><span class="ico">'.pc_svg('mail').'</span><span class="txt">'.__('Send a message','wpreform').'</span></button>';
				echo '</li>';

			echo '</ul>';

		echo '</form>';
		echo '</div>';

	}
	
	
	/*=====  FIN Affichage formulaire  =====*/

}