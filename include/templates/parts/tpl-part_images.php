<?php
/**
 * 
 * Communs templates : images & galeries
 * 
 * Tailles
 * Images par défaut des posts
 * 
 */


/*===============================
=            Tailles            =
===============================*/

/*----------  Recadrage forcé  ----------*/

add_filter( 'image_resize_dimensions', 'pc_image_resize_crop_upscale', 10, 6 );

    function pc_image_resize_crop_upscale( $default, $orig_w, $orig_h, $new_w, $new_h, $crop ){

        if ( !$crop ) return null; // si l'image ne doit pas être recadrée
    
        $aspect_ratio = $orig_w / $orig_h;
        $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);
    
        $crop_w = round($new_w / $size_ratio);
        $crop_h = round($new_h / $size_ratio);
    
        $s_x = floor( ($orig_w - $crop_w) / 2 );
        $s_y = floor( ($orig_h - $crop_h) / 2 );
    
		return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
		
    }


/*----------  Suppressions  ----------*/

add_filter( 'intermediate_image_sizes_advanced', 'pc_remove_images_sizes', 10 );

	function pc_remove_images_sizes( $sizes ) {

		$sizes_to_remove = array( '1536x1536', '2048x2048' );
		foreach ($sizes_to_remove as $size) { unset( $sizes[$size] ); }

		return $sizes;

}


/*----------  Ajouts  ----------*/

add_action( 'init', 'pc_add_images_sizes' );

	function pc_add_images_sizes() {
		
		global $images_sizes;

		// thumbnail 450, medium 750, medium_large 1000, large 1200
		$images_sizes = array(
			
			'thumbnail_s' => array( 'width'=>250, 'height'=>0, 'crop'=>false ),
			
			'card-s'	=> array( 'width'=>400, 'height'=>250, 'crop'=>true ),
			'card-m'	=> array( 'width'=>500, 'height'=>320, 'crop'=>true ),
			'card-l'	=> array( 'width'=>700, 'height'=>440, 'crop'=>true ),
			
			'thumbnail_gallery'	=> array( 'width'=>250, 'height'=>250, 'crop'=>true ),
			
		);

		$images_sizes = apply_filters( 'pc_filter_images_sizes', $images_sizes );

		foreach ( $images_sizes as $size => $datas ) {
			add_image_size( $size, $datas['width'], $datas['height'], $datas['crop'] );
		}

	}


/*=====  FIN Tailles  =====*/

/*===================================================
=            Images par défaut des posts            =
===================================================*/

function pc_get_default_card_image() {

	$directory = get_bloginfo('template_directory');

	return apply_filters( 'pc_filter_default_card_image', array(
		'400' => array(	$directory.'/images/card-default-s.jpg', 400, 250 ),
		'500' => array(	$directory.'/images/card-default-m.jpg', 500, 320 ),
		'700' => array(	$directory.'/images/card-default-l.jpg', 700, 440 )
	) );

}


/*=====  FIN Image par défaut des posts  =====*/