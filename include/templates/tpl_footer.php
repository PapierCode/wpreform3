<?php 
/**
 * 
 * Template : header
 * 
 * Hooks
 * Container
 * Logo & coordonnées
 * Navigation
 * Javascript
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

/*=================================
=            Container            =
=================================*/

function pc_display_footer_start() {

	$tag = '<footer class="footer" role="contentinfo">';
	if ( apply_filters( 'pc_filter_display_footer_inner', false ) ) { $tag .= '<div class="footer-inner">'; }

	echo apply_filters( 'pc_filter_footer_start', $tag );

}

function pc_display_footer_end() {

	$tag = '</footer>';
	if ( apply_filters( 'pc_filter_display_footer_inner', false ) ) { $tag = '</div>'.$tag; }

	echo apply_filters( 'pc_filter_footer_end', $tag );

}


/*=====  FIN Container  =====*/

/*==========================================
=            Logo & coordonnées            =
==========================================*/

function pc_display_footer_contact() {
	
	$dd = array();

	/*----------  Logo  ----------*/
	
	// datas
	$logo_datas = apply_filters( 'pc_filter_footer_logo_datas', array(
		'url' => get_bloginfo('template_directory').'/images/logo-footer.svg',
		'width' => 100,
		'height' => 25,
		'alt' => get_option( 'options_coord_name' )
	));
	$logo_tag = '<img src="'.$logo_datas['url'].'" alt="'.$logo_datas['alt'].'" width="'.$logo_datas['width'].'" height="'.$logo_datas['height'].'" loading="lazy">';

	/*----------  Adresse  ----------*/
	
	$dd['addr'] = get_option( 'options_coord_address' ).' <br>'.get_option( 'options_coord_post_code' ).' '.get_option( 'options_coord_city' ).', '.get_option( 'options_coord_country' );

	/*----------  Téléphone  ----------*/
	
	$phone = get_option( 'options_coord_phone' );

	$dd['phone'] = '<a href="tel:'.pc_get_phone_format($phone).'" class="'.apply_filters( 'pc_filter_footer_contact_phone_class', 'button button--white' ).'" title="'.__('Phone','wpreform').' '.pc_get_phone_format($phone,false).'"><span class="ico">'.pc_svg('phone').'</span><span class="txt">'.pc_get_phone_format($phone,false).'</span></a>';

	/*----------  Social  ----------*/

	if ( apply_filters( 'pc_filter_footer_contact_social_display', true ) ) {
	
		$rs_list = get_field('coord_social','option');
		
		foreach( $rs_list as $rs ) {
			
			$dd[$rs['ico']['value']] = '<a href="'.$rs['url'].'" class="'.apply_filters( 'pc_filter_footer_social_link_class', 'social-link button button--white button--ico' ).'" title="'.sprintf(__('Follow us on %s (new window)','wpreform'),$rs['ico']['label']).'" target="_blank" rel="noreferrer"><span class="ico">'.pc_svg($rs['ico']['value']).'</span><span class="txt">'.$rs['ico']['label'].'</span></a>';

		}	

	}

	/*----------  Affichage  ----------*/

	$dt = apply_filters( 'pc_filter_footer_contact_dt', $logo_tag, $logo_datas );
	$dd = apply_filters( 'pc_filter_footer_contact_dd', $dd );

	$coord_cols = isset($rs_list) ? count($rs_list) : 1;
	echo '<address class="coord"><dl class="coord-list" style="--coord-cols:'.$coord_cols.'">';
		echo '<dt class="coord-item coord-item--logo">'.$dt.'</dt>';
		foreach ( $dd as $id => $content ) {
			echo '<dd class="coord-item coord-item--'.$id.'">'.$content.'</dd>';
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

	echo '<nav class="f-nav" role="navigation" aria-label="'.__('Footer navigation','wpreform').'">';
	
		do_action( 'pc_footer_nav_items_before' );
		
		$nav_footer_args = apply_filters( 'pc_filter_footer_nav_args', array(
			'theme_location'  	=> 'nav-footer',
			'nav_prefix'		=> array('f-nav','f-p-nav'),
			'menu_class'      	=> 'f-nav-list f-nav-list--l1 f-p-nav-list f-p-nav-list--l1',
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

	if ( is_singular() ) {

		global $pc_post;

		/*----------  Sprite to JS  ----------*/
		
		if ( has_block( 'acf/pc-gallery', $pc_post->wp_post ) || apply_filters( 'pc_filter_enqueue_gallery_script', false, $pc_post )  ) { // filtre dupliqué ci-dessous
			$sprite_selection = apply_filters( 'pc_filter_sprite_to_js', array('arrow','cross','more','less') );
			if ( !empty( $sprite_selection ) ) {
				$sprite_to_json = array();
				foreach ( $sprite_selection as $id ) {
					$sprite_to_json[$id] = pc_svg($id);
				}
			}
			echo '<script>const sprite='.json_encode( $sprite_to_json ).'</script>';
		}

	}

}

function pc_enqueue_scripts() {
	
	// Jquery in footer
	if ( !is_admin() ) {
		wp_scripts()->add_data( 'jquery', 'group', 1 );
		wp_scripts()->add_data( 'jquery-core', 'group', 1 );
		wp_scripts()->add_data( 'jquery-migrate', 'group', 1 );
	}

	if ( is_singular() ) {

		global $pc_post;

		/*----------  Bloc galerie  ----------*/
		
		if ( has_block( 'acf/pc-gallery', $pc_post->wp_post ) || apply_filters( 'pc_filter_enqueue_gallery_script', false, $pc_post ) ) { // filtre dupliqué ci-dessus

			$js_gallery_path = '/scripts/include/gallery.min.js';
			wp_enqueue_script( 
				'gallery',
				get_template_directory_uri().$js_gallery_path,
				array( 'jquery' ),
				filemtime(get_template_directory().$js_gallery_path),
				array( 'strategy' => 'defer', 'in_footer' => true )
			);

		}

		/*----------  Bloc Carte  ----------*/

		if ( has_block( 'acf/pc-map', $pc_post->wp_post ) || apply_filters( 'pc_filter_enqueue_leaflet_script', false, $pc_post ) ) {
			
			$js_leaflet_path = '/scripts/include/leaflet.js';
			wp_enqueue_script( 
				'leaflet',
				get_template_directory_uri().$js_leaflet_path,
				array(),
				filemtime(get_template_directory().$js_leaflet_path),
				array( 'strategy' => 'defer', 'in_footer' => true )
			);

			$css_leaflet_path = '/scripts/include/leaflet.css';
			wp_enqueue_style( 
				'leaflet', 
				get_template_directory_uri().$css_leaflet_path, 
				NULL, 
				filemtime(get_template_directory().$css_leaflet_path), 
				'screen'
			);

		}

	}

	/*----------  Global  ----------*/

	$js_preform_path = '/scripts/wpreform.min.js';
	wp_enqueue_script( 
		'wpreform',
		get_template_directory_uri().'/scripts/wpreform.min.js',
		array(),
		filemtime(get_template_directory().$js_preform_path),
		array( 'strategy' => 'defer', 'in_footer' => true )
	);

}


/*=====  FIN Javascript  =====*/