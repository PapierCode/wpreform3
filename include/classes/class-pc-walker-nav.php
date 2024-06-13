<?php

class PC_Walker_Nav_Menu extends Walker_Nav_Menu {

	// création des ul level 2 et sup
	public function start_lvl( &$output, $depth = 0, $args = null ) {

		// parceque...
		$display_depth = $depth + 2;
			
		$list_css = array();
		foreach ( $args->nav_prefix as $prefix ) {
			$list_css[] = $prefix.'-list';
			$list_css[] = $prefix.'-list--l'.$display_depth;
		}
		$output .= '<ul class="' .implode( ' ', $list_css ) . ' reset-list">';

	} // end start_lvl()

	// création des li tous level
	public function start_el( &$output, $item, $depth = 0, $args = null, $current_id = 0 ) {
		// parceque...
		$display_depth = $depth + 1;
		// classes communes et en fonction de la profondeur
		$li_class_name = '';
		$link_class_name = '';
		$span_class_name = '';
		foreach ( $args->nav_prefix as $prefix ) {
			$li_class_name .= $prefix.'-item '.$prefix.'-item--l'.$display_depth.' ';
			$link_class_name .= $prefix.'-link '.$prefix.'-link--l'.$display_depth.' ';
			$span_class_name .= $prefix.'-link-inner '.$prefix.'-link-inner--l'.$display_depth.' ';
		}
		
		// supprime tous les classes sauf celles précisées dans le tableau	
		$classes = array( 'current-menu-item' );
		if ( $args->depth > 1 ) {
			$classes = array_merge( $classes, array( 'menu-item-has-children','current-menu-parent', 'current-menu-ancestor', 'current-'.$item->object.'-ancestor' ) );
		};
		$clean_classes = is_array( $item->classes ) ? array_intersect( $item->classes, $classes ) : '';
		// remplace les classes restantes
		$new_classes = array(
			'menu-item-has-children' => 'is-parent',
			'current-menu-item' => 'is-active',
			'current-menu-parent' => 'is-parent-active',
			'current-menu-ancestor' => 'is-ancestor-active',
			'current-'.$item->object.'-ancestor' => 'is-ancestor-active'
		);
		$clean_classes = str_replace( array_keys( $new_classes ), $new_classes, $clean_classes );

		// array to string + classes ajoutées depuis l'admin
		$li_class_name .= esc_attr( implode( ' ', $clean_classes ) ).' '.$item->classes[0];
		
		// construction du li
		$output .= '<li class="'.$li_class_name.'">';

		// item parent
		if ( in_array( 'is-parent', $clean_classes ) ) {

			if ( ( $args->theme_location == 'nav-footer' && $display_depth == 1 ) || $display_depth == 2 ) {
				
				$tag = apply_filters( 'pc_filter_nav_column_title_tag', 'h2', $item, $display_depth, $args );			
				$title_class_name = str_replace( 'link', 'title', $link_class_name );
				$title_span_class_name = str_replace( 'link', 'title', $span_class_name );
				$output .= '<'.$tag.' class="'.$title_class_name.'"><span class="'.$title_span_class_name.'">'.$item->title.'</span></'.$tag.'>';

			} else {

				$output .= '<button type="button" class="'.$link_class_name.'reset-btn"><span class="'.$span_class_name.'">'.$item->title.'</span></button>';

			}

		// si ce n'est pas un parent
		} else {

			// construction du <a>
			$attributes  = ! empty( $item->attr_title ) ? ' title="'. esc_attr( $item->attr_title ).'"' : '';
			$attributes .= ! empty( $item->target ) ? ' target="'. esc_attr( $item->target ).'"' : '';
			$attributes .= ! empty( $item->url ) ? ' href="'. esc_attr( $item->url ).'"' : '';
			$attributes .= ' class="'.$link_class_name.'"';
			$attributes .= ( in_array('is-active', $clean_classes) ) ? ' aria-current="page"' : '';

			// ajout du lien
			$output .= '<a '.$attributes.'>';
			$output .= apply_filters( 'pc_filter_nav_menu_item_icon', $icon = '', $item, $args );
			$output .= '<span class="'.$span_class_name.'">'.$item->title.'</span>';
			$output .= '</a>';

		}

		// box level enfant
		if ( $args->depth > 1 && in_array( 'is-parent', $clean_classes ) ) {			
			$box_css = array(); 
			$box_level = $display_depth + 1;
			foreach ( $args->nav_prefix as $prefix ) {
				$box_css[] = $prefix.'-box';
				$box_css[] = $prefix.'-box--l'.$box_level;
			};
			$output .= '<div class="' .implode( ' ', $box_css ) . '">';
			$output .= apply_filters( 'pc_filter_nav_box_after_start', '', $item, $box_level, $args );
		}

	} // end start_el()

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		
		if ( $args->depth > 1 && in_array( 'menu-item-has-children', $item->classes ) ) {
			$output .= apply_filters( 'pc_filter_nav_box_before_end', '', $item, $depth + 2, $args );
			$output .= '</div>';
		}

		$output .= '</li>';

	}
	
	// création des ul level 2 et sup
	public function end_lvl( &$output, $depth = 0, $args = null ) {

		$output .= '</ul>';

	} // end start_lvl()

} // end Primary_Walker()