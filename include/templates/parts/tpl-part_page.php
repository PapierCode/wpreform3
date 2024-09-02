<?php 
/**
 * 
 * Template page
 * 
 * Date CGU
 * Lien retour sous-pages
 * Protection par mot de passe
 * Actualités associées
 * 
 */


/*================================
=            Date CGU            =
================================*/

add_action( 'pc_action_template_index', 'pc_edit_display_cgu_date', 55 );

	function pc_edit_display_cgu_date( $pc_post ) {

		if ( $pc_post->id == get_option( 'wp_page_for_privacy_policy' ) ) { $pc_post->display_date( 'single-date' ); }

	}

add_filter( 'pc_filter_display_date_modified', 'pc_edit_display_cgu_type', 10, 2 );

    function pc_edit_display_cgu_type( $modified, $pc_post ) {

        return $pc_post->id == get_option( 'wp_page_for_privacy_policy' ) ? true : false;

    }


/*=====  FIN Date CGU  =====*/

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
		$output .= '<ul class="form-list">';
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

add_action( 'pc_action_template_index', 'pc_page_aside_related', 125 );

	function pc_page_aside_related( $pc_post ) {

		if ( $pc_post->type == 'page' ) {

			$cpts = array();
			if ( get_option('options_news_enabled') && get_option('options_news_pages') ) { 
				$cpts['news'] = array(
					'slug' => NEWS_POST_SLUG,
					'title_txt' => 'Actualités associées',
					'btn_txt' => 'Toutes les actualités'
				);
			}
			if ( get_option('options_events_enabled') && get_option('options_events_pages') ) { 
				$cpts['event'] = array(
					'slug' => EVENT_POST_SLUG,
					'title_txt' => 'Événements associés',
					'btn_txt' => 'Tous les événements'
				);
			}

			if ( !empty( $cpts ) ) {

				echo '<aside class="aside aside--page">';

				$cpts = apply_filters( 'pc_filter_page_aside_related_cpts', $cpts, $pc_post );
				$metas = $pc_post->metas;
				foreach ( $cpts as $key => $cpt ) {
						
					$get_posts_args = array(
						'post_type' => $cpt['slug'],
						'post_per_page' => 2,				
						'meta_key' => '_'.$key.'_pages_related',
						'meta_value' => '"'.$pc_post->id.'"',
						'meta_compare' => 'LIKE'
					);

					$get_posts = get_posts( $get_posts_args );

					if ( !empty( $get_posts ) ) {
							echo '<div class="aside-card-title">';
								echo '<h2 class="aside-title">'.$cpt['title_txt'].'</h2>';
								echo '<a href="'.get_post_type_archive_link( $cpt['slug'] ).'" class="button"><span class="ico">'.pc_svg('more').'</span><span class="txt">'.$cpt['btn_txt'].'</span></a>';
							echo '</div>';
							echo '<ul class="card-list card-list--'.$cpt['slug'].'">';
								foreach ( $get_posts as $post ) {
									$pc_post_related = new PC_Post( $post );
									echo '<li class="card-list-item">';
										$pc_post_related->display_card(3);
									echo '</li>';
								}
							echo '</ul>';
					}

				}
				
				echo '</aside>';

			}

		}

	}


/*=====  FIN Actualités associées  =====*/