<?php
/**
 * 
 * Objet post custom
 * 
 */


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
	
	public $thumbnail;		// array


	/*=================================
	=            Construct            =
	=================================*/
	
	public function __construct ( $wp_post ) {

		$this->wp_post 		= $wp_post;
		$this->id 			= $wp_post->ID;
		$this->type 		= $wp_post->post_type;
		$this->author 		= $wp_post->post_author;
		$this->parent 		= $wp_post->post_parent;
		$this->title 		= $wp_post->post_title;
		$this->content 		= $wp_post->post_content;

		// url
		$this->permalink = get_the_permalink( $wp_post->ID );

		// metas
		$this->metas = get_fields( $wp_post->ID );

		// visual
		if ( isset( $this->metas['_thumbnail_id'] ) ) {
			// TODO $this->use_woo_product_image(); // TODO si le post est un produit WooCommerce
			$this->thumbnail = $this->metas['_thumbnail_id'];
		} else { $this->thumbnail = ''; }

	}

	
	/*=====  FIN Construct  =====*/

	/*============================
	=            Date            =
	============================*/
	
	/**
	 * 
	 * [DATE] Format & type
	 * 
	 * @param	string	$format		php date	
	 * @param	bool	$modified	get modification date ?
	 * 
	 * @return	string 	date
	 * 
	 */

	public function get_date( $format = 'j F Y', $modified = false ) {

		$date = !$modified ? $this->wp_post->post_date : $this->wp_post->post_modified;
		return date_i18n( $format, strtotime( $date ) );

	}
	
	/**
	 * 
	 * [DATE] Display
	 * 
	 * @param	string	$css
	 * 
	 * @return	string 	HTML (time)
	 * 
	 */

	public function display_date( $css ) {

		$modified = apply_filters( 'pc_filter_post_display_date_modified', false, $this );		
		$format = apply_filters( 'pc_filter_post_display_date_format', 'j F Y', $this );		
		$prefix = apply_filters( 'pc_filter_post_date_prefix', pc_svg('calendar'), $this );

		$label = !$modified ? 'publication' : 'modification';
		echo '<time class="'.$css.'" aria-label="Date de '.$label.'" datetime="'.$this->get_date( 'c', $modified ).'">'.$prefix.'<span>'.$this->get_date( $format, $modified ).'</span></time>';

	}
	
	
	/*=====  FIN Date  =====*/

	/*=================================
	=            Taxonomie            =
	=================================*/
	
	/**
	 * 
	 * [TAXONOMY] Display terms list
	 * 
	 * @return	string 	HTML (terms list)
	 * 
	 */

	public function display_terms( $css ) {

		if ( apply_filters( 'pc_filter_post_card_taxonomy_slug', '', $this ) ) {	

			$terms = wp_get_post_terms( 
				$this->id, 
				apply_filters( 'pc_filter_post_card_taxonomy_slug', '', $this )
			);
	
			if ( is_array( $terms ) && !empty( $terms ) ) {
	
				echo '<p class="'.$css.'">'.pc_svg('tag');	
					foreach ( $terms as $key => $term ) {	
						if ( $key > 0 ) { echo ', '; }
						echo '<a href="'.get_post_type_archive_link( $this->type ).'?term='.$term->term_id.'" title="Catégorie '.$term->name.'" rel="nofollow">'.$term->name.'</a>';	
					}	
				echo '</p>';
	
			}
	
		}

	}
	
	
	/*=====  FIN Taxonomie  =====*/

	/*==============================
	=            Résumé            =
	==============================*/
	
	/**
	 * 
	 * [CARD] Title
	 * 
	 * @return string meta post_short_title || post title
	 * 
	 */

	public function get_card_title() {

		$metas = $this->metas;
		$title = isset( $metas['post_short_title'] ) && trim( $metas['post_short_title'] ) ? trim( esc_html( $metas['post_short_title'] ) ) : get_the_title( $this->id );

		return pc_get_text_cut(
			apply_filters( 'pc_filter_post_card_title', $title, $this ),
			apply_filters( 'pc_filter_post_short_title_length', 40 )
		);

	}

	/**
	 * 
	 * [CARD] Description
	 * 
	 * @return string meta post_excerpt || wp_excerpt
	 * 
	 */

	public function get_card_description() {

		$metas = $this->metas;
		$description = isset( $metas['post_excerpt'] ) && trim( $metas['post_excerpt'] ) ? trim( esc_html( $metas['post_excerpt'] ) ) : get_the_excerpt( $this->id );
	
		return pc_get_text_cut( 
			apply_filters( 'pc_filter_post_card_description', $description, $this ), 
			apply_filters( 'pc_filter_post_excerpt_length', 150 )
		);
	
	}

	/**
	 * 
	 * [CARD] Visual's Urls & attribut alt
	 * 
	 * @return 	array	string	alt attribut	meta _wp_attachment_image_alt
	 * 				 	array 	urls & sizes	meta _thumbnail_id | default
	 * 
	 */

	public function get_card_image_args() {

		$image = $this->thumbnail;

		if ( $image ) {

			$args = array( 
				'alt' => $image['alt'],
				'sizes' => array()
			);
			
			$sizes_list = apply_filters( 'pc_filter_post_card_thumbnail_sizes_values', array(
				'400' => 'card-s',
				'500' => 'card-m',
				'700' => 'card-l'
			), $this->thumbnail, $this );

			$sizes = $image['sizes'];
			foreach ( $sizes_list as $size => $key ) {
				$args['sizes'][$key] = [ $sizes[$key], $sizes[$key.'-width'], $sizes[$key.'-height'] ];
			}
		
		} else {

			$args = array(
				'alt' => '',
				'sizes' => pc_get_default_card_image()
			);

		}
		
		return $args;
	
	}
	
	/**
	 * 
	 * [CARD] Display visual
	 * 
	 * @return string HTML (article)
	 * 
	 */

	public function display_card_image() {

		$args = $this->get_card_image_args();
		$last_size_key = array_key_last( $args['sizes'] );

		$attrs = array(
			'src' => $args['sizes'][$last_size_key][0],
			'width' => $args['sizes'][$last_size_key][1],
			'height' => $args['sizes'][$last_size_key][2],
			'alt' => $args['alt'],
			'loading' => 'lazy'
		);
	
		if ( count( $args['sizes'] ) > 1 ) {
			
			$attr_srcset = array();
			foreach ( $args['sizes'] as $size => $size_args ) { $attr_srcset[] = $size_args[0].' '.$size.'w'; }
			$attrs['srcset'] = implode( ', ', $attr_srcset );

			$attrs['sizes'] = apply_filters( 'pc_filter_card_thumbnail_sizes_attribut', '(max-width:400px) 400px, (min-width:401px) and (max-width:700px) 700px, (min-width:701px) 500px', $args, $this );

		}
		
		$tag = '<img';
			foreach ( $attrs as $attr => $attr_value ) { $tag .= ' '.$attr.'="'.$attr_value.'"'; }
		$tag .= '>';
		
		echo apply_filters( 'pc_filter_card_image_tag', $tag, $attrs, $this );
	
	}
	
	/**
	 * 
	 * [CARD] Display
	 * 
	 * @param	int		$title_level	Title level
	 * @param	string	$classes_css	CSS
	 * 
	 * @return	string	HTML
	 * 
	 */

	public function display_card( $title_level = 2, $classes_css = array(), $params = array() ) {

		$metas = $this->metas;
	
		// title
		$title = $this->get_card_title();

		// link
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
		
		// css
		$classes_css = array_merge( array( 'card' ), $classes_css );

		echo '<article class="'.implode( ' ', $classes_css ).'">';
	
				// hook
				do_action( 'pc_post_card_after_start', $this, $params );
			
				// visual
				echo '<figure class="card-figure">';
					$this->display_card_image();				
				echo '</figure>';

				// hook	
				do_action( 'pc_post_card_after_figure', $this, $params );
	
				// title
				echo '<h'.$title_level.' class="card-title">';
					echo apply_filters( 'pc_filter_card_title_link', true ) ? '<a href="'.$href.'" class="card-link" title="Lire la suite de : '.$title.'">'.$title.'</a>' : $title;
				echo '</h'.$title_level.'>';	
	
				// hook	
				do_action( 'pc_post_card_after_title', $this, $params );

				// date
				if ( apply_filters( 'pc_filter_display_default_card_date', false, $this ) ) {
					$this->display_date( 'card-date' );		
				}
				
				// description
				$description = $this->get_card_description();
				$ico_more = apply_filters( 'pc_filter_card_ico_more', pc_svg( 'arrow' ), $this );	
				if ( $description ) {
					echo '<p class="card-desc">';
						echo $description;
						if ( $ico_more ) { echo ' <span class="card-desc-ico">'.$ico_more.'</span>'; }	
					echo '</p>';
				}

				// hook
				do_action( 'pc_post_card_after_desc', $this, $params );

				// terms
				if ( apply_filters( 'pc_filter_display_default_card_terms', false, $this ) ) {
					$this->display_terms( 'card-terms' );
				}
			
				// hook
				do_action( 'pc_post_card_before_end', $this, $params );
		
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
	
	// private function use_woo_product_image() {

	// 	if ( in_array( $this->type, array('product','product_variation') ) ) {
		
	// 		if ( isset( $this->metas['_thumbnail_id'] ) && $this->metas['_thumbnail_id'] > 0 ) {

	// 			$this->metas['visual-id'] = $this->metas['_thumbnail_id'];

	// 		} else if ( 'product_variation' == $this->type ) {

	// 			$parent_image_id = get_post_meta( $this->parent, '_thumbnail_id', true );
	// 			if ( $parent_image_id && $parent_image_id > 0 ) { $this->metas['visual-id'] = $parent_image_id; }

	// 		}

	// 	}

	// }
	
	
	/*=====  FIN WooCommerce  =====*/

}

