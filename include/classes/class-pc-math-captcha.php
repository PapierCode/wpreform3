<?php
/**
*
* Math Captcha
*
**/


class PC_MathCaptcha {

	public $math;
	public $msg_error; 
	
	private $cipher_method;
	private $pass_phrase;
	private $iv;


    /*====================================
    =            Constructeur            =
    ====================================*/

    function __construct( $pass_phrase, $iv ) {

		$this->msg_error = apply_filters(
			'pc_filter_mathcaptcha_msg_error',
			sprintf( __('%1$sSolve the new calculation%2$s (spam protection)','wpreform'), '<strong>', '</strong>' )
		);
		
		$types = array( 'add', 'sub' );
		$this->math = array( rand( 1, 10 ), rand( 1, 10 ), $types[ rand( 0, 1 ) ] );
		
		$this->cipher_method = 'AES-128-CTR';
		$this->pass_phrase = $pass_phrase;
		$this->iv = $iv;

    }


    /*=====  FIN Constructeur  ======*/

	/*=============================
	=            Champ            =
	=============================*/
	
	public function get_field_label_text() {

		$math = $this->math;
		$operator = $math[2] == 'add' ? __('plus','wpreform') : __('minus','wpreform');

		return apply_filters(
			'pc_filter_mathcaptcha_label_text', 
			sprintf( 
				__('Antispam protection, How much is x plus/minus x?','wpreform'),
				$math[0].'&nbsp;'.$operator.'&nbsp;'.$math[1].'&nbsp;'
			),
			$math,
			$operator
		);

	}
	
	public function get_field_inputs() {

		return '<input type="number" id="form-captcha" name="form-captcha" value="" required><input type="hidden" name="captcha-math" value="'.$this->get_encode_math().'">';

	}
	
	
	/*=====  FIN Champ  =====*/

	/*===========================================
	=            Encodage / Décodage            =
	===========================================*/
	
	public function get_encode_math() {
		
		$encryption = openssl_encrypt(
			implode( '/', $this->math ),
			$this->cipher_method,
			$this->pass_phrase,
			0,
			$this->iv
		);
		
		return $encryption;

	}
	
	public function get_decode_math( $encryption ) {

		$decryption = openssl_decrypt (
			$encryption,
			$this->cipher_method,
			$this->pass_phrase,
			0,
			$this->iv
		);

		return $decryption;

	}
	
	
	/*=====  FIN Encodage / Décodage  =====*/

    /*==================================
    =            Validation            =
    ==================================*/

    public function validate() {

		$return = false;
		
		if ( isset( $_POST['captcha-math'] )) {

			$math = $this->get_decode_math( $_POST['captcha-math'] );
			$math = explode( '/', $math );

			if ( 'add' == $math[2] ) {
				$user_result = (int) $math[0] + (int) $math[1];
			} else {
				$user_result = (int) $math[0] - (int) $math[1];
			}

			if ( $user_result == (int) $_POST['form-captcha'] ) { $return = true; }

		}

		return $return;

    }

    /*=====  FIN Validation  ======*/

}
