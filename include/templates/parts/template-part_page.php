<?php 

/*================================
=            Date CGU            =
================================*/

add_filter( 'pc_filter_display_main_date', 'pc_edit_display_cgu_date', 10, 2 );

    function pc_edit_display_cgu_date( $display, $pc_post ) {

        if ( $pc_post->id == get_option( 'wp_page_for_privacy_policy' ) ) { $display = true; }

        return $display;

    }

add_filter( 'pc_filter_display_date_modified', 'pc_edit_display_cgu_type', 10, 2 );

    function pc_edit_display_cgu_type( $modified, $pc_post ) {

        if ( $pc_post->id == get_option( 'wp_page_for_privacy_policy' ) ) { $modified = true; }

        return $modified;

    }


/*=====  FIN Date CGU  =====*/

/*==================================
=            Sous-pages            =
==================================*/

/*----------  Lien retour  ----------*/

add_action( 'pc_action_template_index', 'pc_display_subpage_backlink', 99 );

	function pc_display_subpage_backlink( $pc_post ) {

		if ( is_page() && $pc_post->parent > 0 ) {

			echo '<nav class="main-footer-prev" role="navigation" aria-label="Retour à la page parente"><a href="'.get_the_permalink($pc_post->parent).'" class="button" title="'.get_the_title($pc_post->parent).'"><span class="ico">'.pc_svg('arrow').'</span><span class="txt">Retour</span></a></nav>';

		}

	}


/*=====  FIN Sous-pages  =====*/

/*===================================================
=            Protection par mot de passe            =
===================================================*/

/*----------  Préfixe titre  ----------*/

add_filter( 'protected_title_format', 'pc_edit_protected_title_format' );

	function pc_edit_protected_title_format() {

		return '%s';

	}


/*----------  Formulaire  ----------*/
	
add_filter( 'the_password_form', 'pc_edit_password_form' );

	function pc_edit_password_form( $output ) {
		 
		$output = '<form action="'.esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ).'" method="post" class="form--page-protected">';
		$output .= '<p>'.__( 'This content is password protected. To view it please enter your password below:' ) . '</p>';
		$output .= '<ul class="form-list reset-list">';
			$output .= '<li class="form-item form-item--password">';
			$output .= '<label for="post-password" class="form-label">'.__( 'Password:' ).'</label>';
			$output .= '<div class="form-item-inner"><input name="post_password" id="post-password" type="password" /></div>';
			$output .= '</li>';
			$output .= '<li class="form-item form-item--submit"><button type="submit" title="'.esc_attr_x( 'Enter', 'post password form' ).'" class="form-submit button"><span class="form-submit-inner">'.esc_attr_x( 'Enter', 'post password form' ).'</span></button></li>';
		$output .= '</ul></form>';

		return $output;

	}


/*=====  FIN Protection par mot de passe  =====*/

/*============================================
=            Actualités associées            =
============================================*/

// TODO

add_action( 'pc_action_index_main_aside', 'pc_page_aside_news', 10 );

	function pc_page_aside_news( $pc_post ) {

		if ( is_page() && get_option('options_news_enabled') ) {

			$metas = $pc_post->metas;
			$get_news_args = array(
				'post_type' => NEWS_POST_SLUG,
				'post_per_page' => 4
			);

			if ( isset( $metas['_page_news_categories_related'] ) ) {

				$get_news_args = array_merge(
					array( 
						'tax_query' => array(
							array(
								'taxonomy' => NEWS_TAX_SLUG,
								'field' => 'term_id',
								'terms' => unserialize($metas['_page_news_categories_related'])
							),
						)
					),
					$get_news_args
				);

			} else {

				$get_news_args = array_merge(
					array(
						'meta_key' => '_news_pages_related',
						'meta_value' => '"'.$pc_post->id.'"',
						'meta_compare' => 'LIKE'
					),
					$get_news_args
				);

			}

			$get_news = get_posts( $get_news_args );

			if ( !empty( $get_news ) ) {
				echo '<aside class="aside aside--news">';
					echo '<h2 class="aside-title aside-title--news">'.apply_filters( 'pc_filter_aside_news_title', 'Actualités' ).'</h2>';
					echo '<ul class="card-list card-list--news">';
						foreach ( $get_news as $news ) {
							$pc_news = new PC_Post( $news );
							echo '<li class="card-list-item">';
								$pc_news->display_card(3);
							echo '</li>';
						}
					echo '</ul>';
				echo '</aside>';
			}

		}

	}


/*=====  FIN Actualités associées  =====*/