<?php

class PC_Post {

	public $wp_post;		// objet

	public $id;				// int
	public $type;			// string
	public $author;			// int
	public $parent;			// int
	public $title;			// string
	public $content;		// string

	public $permalink;		// string
	public $metas;			// array
	
	public $has_image;		// bool


	/*=================================
	=            Construct            =
	=================================*/
	
	public function __construct ( $post ) {

		// WP_post
		$this->wp_post 		= $post;
		$this->id 			= $post->ID;
		$this->type 		= $post->post_type;
		$this->author 		= $post->post_author;
		$this->parent 		= $post->post_parent;
		$this->title 		= $post->post_title;
		$this->content 		= $post->post_content;

		// url
		$this->permalink = get_the_permalink( $post->ID );

		// métas
		$this->metas = get_post_meta( $post->ID );
		// simplification tableau métas
		foreach ( $this->metas as $key => $value) {
			$this->metas[$key] = implode('', $this->metas[$key] );
		}

		// test image associée
		$this->use_woo_product_image(); // si le post est un produit WooCommerce
		$this->has_image = ( isset( $this->metas['visual-id'] ) && is_object( get_post( $this->metas['visual-id'] ) ) ) ? true : false;

	}

	
	/*=====  FIN Construct  =====*/

	/*=================================
	=            Canonical            =
	=================================*/
	
	/**
	 * 
	 * [CANONICAL] Méta link canonical
	 * 
	 * @return string string url
	 * 
	 */

	public function get_canonical() {

		$canonical = $this->permalink;

		if ( 'page' == $this->type && get_query_var( 'paged' ) && get_query_var( 'paged' ) > 1 ) {

			$canonical = $this->permalink.'page/'.get_query_var( 'paged' ).'/';

		}

		return apply_filters( 'pc_filter_post_canonical', $canonical, $this );

	}
	
	
	/*=====  FIN Canonical  =====*/

	/*============================
	=            Date            =
	============================*/
	
	/**
	 * 
	 * [DATE] De création ou modification
	 * 
	 * @param	string	$format		Cf. php date	
	 * @param	bool	$modified	Date de modification ?
	 * 
	 * @return	string 	Date traduite
	 * 
	 */

	public function get_date( $format = 'j F Y', $modified = false ) {

		$date = ( !$modified ) ? $this->wp_post->post_date : $this->wp_post->post_modified;
		return date_i18n( $format, strtotime( $date ) );

	}
	
	
	/*=====  FIN Date  =====*/

	/*==============================
	=            Résumé            =
	==============================*/
	
	/**
	 * 
	 * [RESUMÉ] Titre
	 * 
	 * @return string Méta resum-title | titre du post | '(sans titre)'
	 * 
	 */

	public function get_card_title() {

		$metas = $this->metas;
		$title = ( isset( $metas['resum-title'] ) ) ? $metas['resum-title'] : $this->title;
		$title = apply_filters( 'pc_filter_card_title', $title, $this );

		global $texts_lengths;
		return pc_words_limit( $title, $texts_lengths['resum-title'] );

	}

	/**
	 * 
	 * [RESUMÉ] Description
	 * 
	 * @return string Méta resum-desc | wp_excerpt | empty
	 * 
	 */

	public function get_card_description() {

		$metas = $this->metas;
		$description = ( isset( $metas['resum-desc'] ) ) ? $metas['resum-desc'] : get_the_excerpt( $this->id );
		$description = apply_filters( 'pc_filter_card_description', $description, $this );
	
		global $texts_lengths;
		return pc_words_limit( $description, $texts_lengths['resum-desc'] );
	
	}

	/**
	 * 
	 * [RESUMÉ] Urls & attribut alt de la vignette
	 * 
	 * @return 	array 	array 	urls card-400/card-500/card-700	Méta visual-id | default
	 * 					string	attribut alt				Méta _wp_attachment_image_alt | get_card_title()
	 * 
	 */

	public function get_card_image_datas() {

		$metas = $this->metas;

		if ( $this->has_image ) {
			
			$image_datas['sizes'] = apply_filters( 'pc_filter_card_image_sizes', array(
				'400' => wp_get_attachment_image_src( $metas['visual-id'], 'card-400' ),
				'500' => wp_get_attachment_image_src( $metas['visual-id'], 'card-500' ),
				'700' => wp_get_attachment_image_src( $metas['visual-id'], 'card-700' )
			), $metas['visual-id'], $this );
			
			$image_alt = get_post_meta( $metas['visual-id'], '_wp_attachment_image_alt', true );
			$image_datas['alt'] = ( $image_alt ) ? $image_alt : $this->get_card_title();
		
		} else {

			$image_datas['sizes'] = pc_get_default_card_image();
			$image_datas['alt'] = $this->get_card_title();

		}
		
		return $image_datas;
	
	}
	
	/**
	 * 
	 * [RESUMÉ] Affichage vignette
	 * 
	 * @return string HTML
	 * 
	 */

	public function display_card_image( $alt = true ) {

		$image_datas = $this->get_card_image_datas();
		$sizes_count = count( $image_datas['sizes'] );
		$last_size_key = array_key_last($image_datas['sizes']);

		$image_attrs = array(
			'src' => $image_datas['sizes'][$last_size_key][0],
			'width' => $image_datas['sizes'][$last_size_key][1],
			'height' => $image_datas['sizes'][$last_size_key][2],
			'alt' => ( $alt ) ? $image_datas['alt'] : '',
			'loading' => 'lazy'
		);
	
		if ( $sizes_count > 1 ) {
			
			$attr_srcset = array();
			foreach ( $image_datas['sizes'] as $size => $attachment ) {
				$attr_srcset[] = $attachment[0].' '.$size.'w';
			}
			$image_attrs['srcset'] = implode(', ',$attr_srcset);
			$image_attrs['sizes'] = apply_filters( 'pc_filter_card_image_sizes_attribut', '(max-width:400px) 400px, (min-width:401px) and (max-width:700px) 700px, (min-width:701px) 500px', $image_datas, $this );

		}
		
		$tag = '<img';
		foreach ( $image_attrs as $attr => $attr_value ) {
			$tag .= ' '.$attr.'="'.$attr_value.'"';
		}
		$tag .= ' />';
		
		echo apply_filters( 'pc_filter_card_image', $tag, $image_datas, $this );
	
	}
	
	/**
	 * 
	 * [RESUMÉ] Affichage résumé complet
	 * 
	 * @param	int		$title_level	Niveau du titre
	 * @param	string	$classes_css	Classes css
	 * 
	 * @return	string	HTML
	 * 
	 */

	public function display_card( $title_level = 2, $classes_css = array(), $params = array() ) {

		$metas = $this->metas;
	
		// titre
		$title = $this->get_card_title();

		// lien
		$href = $this->permalink;
		$href_params = apply_filters( 'pc_filter_card_link_params', array(), $this );
		if ( !empty( $href_params ) ) {
			$href .= ( false === strpos( $href, '?' ) ) ? '?' : '&';
			$param_index = 0;
			foreach ( $href_params as $param => $value ) {
				if ( $param_index > 0 ) { $href .= '&'; }
				$href .= $param.'='.$value;
				$param_index++;
			}
		}
		$link_tag_start = '<a href="'.$href.'" class="card-link" title="Lire la suite de : '.$title.'">';
		$link_position = apply_filters( 'pc_filter_post_card_link_position', 'title', $this ); // title || card-inner

		// description
		$description = $this->get_card_description();

		// filtres call to action
		$ico_more = apply_filters( 'pc_filter_card_ico_more', pc_svg('arrow'), $this );	
		$ico_more_display = apply_filters( 'pc_filter_card_ico_more_display', true, $this );
		$read_more_display = apply_filters( 'pc_filter_card_read_more_display', false, $this );
		
		
		/*----------  Affichage  ----------*/
		
		$classes_css = array_merge( array( 'card' ), $classes_css );

		echo '<article class="'.implode(' ',$classes_css).'">';
	
			if ( 'card-inner' == $link_position ) { echo $link_tag_start; }
	
				// hook
				do_action( 'pc_post_card_after_start', $this, $params );
			
				echo '<figure class="card-figure">';
					$this->display_card_image();				
				echo '</figure>';

				// hook	
				do_action( 'pc_post_card_after_figure', $this, $params );
	
				echo '<h'.$title_level.' class="card-title">';
					if ( 'title' == $link_position ) {
						echo $link_tag_start.$title.'</a>';
					} else {
						echo $title;
					}
				echo '</h'.$title_level.'>';	
	
				// hook	
				do_action( 'pc_post_card_after_title', $this, $params );
				
				if ( $description ) {
					echo '<p class="card-desc">';
						echo $description;
						if ( $ico_more_display ) { echo ' <span class="card-desc-ico">'.$ico_more.'</span>';	}	
					echo '</p>';
				}

				// hook
				do_action( 'pc_post_card_after_desc', $this, $params );
				
				if ( $read_more_display ) {
					echo '<div class="card-read-more" aria-hidden="true"><span class="card-read-more-ico">'.$ico_more.'</span> <span class="card-read-more-txt">Lire la suite</span></a></div>';
				}
			
				// hook
				do_action( 'pc_post_card_before_end', $this, $params );
	
			if ( 'card-inner' == $link_position ) { echo '</a>'; }
		
		echo '</article>';
		
	}	
	
	
	/*=====  FIN Résumé  =====*/

	/*===================================
	=            WooCommerce            =
	===================================*/
	
	/**
	 * 
	 * [WOO] Image produit
	 * 
	 */
	
	private function use_woo_product_image() {

		if ( in_array( $this->type, array('product','product_variation') ) ) {
		
			if ( isset( $this->metas['_thumbnail_id'] ) && $this->metas['_thumbnail_id'] > 0 ) {

				$this->metas['visual-id'] = $this->metas['_thumbnail_id'];

			} else if ( 'product_variation' == $this->type ) {

				$parent_image_id = get_post_meta( $this->parent, '_thumbnail_id', true );
				if ( $parent_image_id && $parent_image_id > 0 ) { $this->metas['visual-id'] = $parent_image_id; }

			}

		}

	}
	
	
	/*=====  FIN WooCommerce  =====*/

}

