<?php 
/**
 * 
 * Template : header
 * 
 ** Hooks
 ** Footer structure & contenu
 ** Fin du container body
 * 
 */

/*=============================
=            Hooks            =
=============================*/

	add_action( 'pc_footer', 'pc_display_footer_start', 10 );

		add_action( 'pc_footer', 'pc_display_footer_contact', 20 );
		add_action( 'pc_footer', 'pc_display_footer_nav', 30 );
		
	add_action( 'pc_footer', 'pc_display_footer_end', 40 );

add_action( 'wp_footer', 'pc_display_js_variables_footer', 0 );
add_action( 'wp_enqueue_scripts', 'pc_enqueue_scripts', 666 );


/*=====  FIN Hooks  =====*/

/*==============================
=            Layout            =
==============================*/

function pc_display_footer_start() {

	$tag = '<footer class="footer" role="contentinfo">';
	if ( apply_filters( 'pc_display_footer_inner', false ) ) { $tag .= '<div class="footer-inner">'; }

	echo apply_filters( 'pc_filter_footer_start', $tag );

}

function pc_display_footer_end() {

	$tag = '</footer>';
	if ( apply_filters( 'pc_display_footer_inner', false ) ) { $tag = '</div>'.$tag; }

	echo apply_filters( 'pc_filter_footer_end', $tag );

}


/*=====  FIN Layout  =====*/

/*==========================================
=            Logo & coordonnées            =
==========================================*/

function pc_display_footer_contact() {
	
	$dd = array(
		'with-icons' => true,
		'list' => array()
	);


	/*----------  Logo  ----------*/
	
	// datas
	$logo_datas = array(
		'url' => get_bloginfo('template_directory').'/images/logo-footer.svg',
		'width' => 100,
		'height' => 25,
		'alt' => get_option( 'options_coord_name' )
	);
	// filtre
	$logo_datas = apply_filters( 'pc_filter_footer_logo_datas', $logo_datas );
	// html
	$logo_tag = '<img src="'.$logo_datas['url'].'" alt="'.$logo_datas['alt'].'" width="'.$logo_datas['width'].'" height="'.$logo_datas['height'].'" loading="lazy" />';


	/*----------  Adresse  ----------*/
	
	$address = get_option( 'options_coord_address' ).' <br/>'.get_option( 'options_coord_post_code' ).' '.get_option( 'options_coord_city' ).', '.get_option( 'options_coord_country' );
	// if ( $settings_project['coord-lat'] != '' && $settings_project['coord-long'] != '' ) {
	// 	$address .= '<br aria-hidden="true" class="no-print"/><button class="reset-btn js-button-map no-print" data-lat="'.$settings_project['coord-lat'].'" data-long="'.$settings_project['coord-long'].'">Afficher la carte</button>';
	// }
	$dd['list']['addr'] = array(
		'ico' => 'map',
		'txt' => $address
	);


	/*----------  Téléphone  ----------*/
	
	$phone = get_option( 'options_coord_phone' );
	$phone_link = '<a href="tel:'.pc_get_phone_format($phone).'" title="Téléphone '.pc_get_phone_format($phone,false).'">'.pc_get_phone_format($phone,false).'</a>';
	$dd['list']['phone'] = array(
		'ico' => 'phone',
		'txt' => $phone_link
	);
	

	/*----------  Affichage  ----------*/

	// filtres	
	$dt = apply_filters( 'pc_filter_footer_contact_dt', $logo_tag, $logo_datas );
	$dd = apply_filters( 'pc_filter_footer_contact_dd', $dd );

	echo '<address class="coord"><dl class="coord-list">';
		echo '<dt class="coord-item coord-item--logo">'.$dt.'</dt>';
		foreach ($dd['list'] as $id => $content) {
			echo '<dd class="coord-item coord-item--'.$id.'">';
				if ( $dd['with-icons'] ) { echo '<span class="coord-ico">'.pc_svg($content['ico']).'</span>'; }
				echo '<span class="coord-txt">'.$content['txt'].'</span>';
			echo '</dd>';
		}
	echo '</dl></address>';

	// données structurées
	// pc_display_schema_local_business();

}


/*=====  FIN Logo & coordonnées  =====*/

/*==================================
=            Navigation            =
==================================*/

function pc_display_footer_nav() {

	echo '<nav class="f-nav" role="navigation" aria-label="Navigation du pied de page">';
	
		do_action( 'pc_footer_nav_items_before' );
		
		$nav_footer_args = apply_filters( 'pc_filter_footer_nav_args', array(
			'theme_location'  	=> 'nav-footer',
			'nav_prefix'		=> array('f-nav','f-p-nav'),
			'menu_class'      	=> 'f-nav-list f-nav-list--l1 f-p-nav-list f-p-nav-list--l1 reset-list',
			'items_wrap'      	=> '<ul class="%2$s">%3$s</ul>',
			'depth'           	=> 1,
			'item_spacing'		=> 'discard',
			'container'       	=> '',
			'fallback_cb'     	=> false,
			'walker'          	=> new PC_Walker_Nav_Menu()
		) );
		wp_nav_menu( $nav_footer_args );

		do_action( 'pc_footer_nav_items_after' );
		
	echo '</nav>';

}


/*=====  FIN Navigation  =====*/

/*==================================
=            Javascript            =
==================================*/

function pc_display_js_variables_footer() {

	/*----------  Sprite to JS  ----------*/
	
	$sprite_selection = apply_filters( 'pc_filter_sprite_to_js', array('arrow','cross','more','less') );
	if ( !empty( $sprite_selection ) ) {
		$sprite_to_json = array();
		foreach ( $sprite_selection as $id ) {
			$sprite_to_json[$id] = pc_svg($id);
		}
	}

	echo '<script>const sprite='.json_encode( $sprite_to_json ).'</script>';

}

function pc_enqueue_scripts() {

	wp_enqueue_script( 
		'wpreform',
		get_template_directory_uri().'/scripts/wpreform-jquery.min.js',
		array(),
		filemtime(get_template_directory().'/scripts/wpreform-jquery.min.js'),
		array( 'strategy' => 'defer', 'in_footer' => true )
	);

}


/*=====  FIN Javascript  =====*/