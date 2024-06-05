<?php

switch ( get_field('type') ) {
    case 'subpages':        
        global $post;
        $selection = get_posts( array(
            'post_type' => 'page',
            'nopaging' => true,
            'order' => 'ASC',
            'orderby' => 'menu_order',
            'post_parent' => $post->ID
        )); 
        break;
    case 'selection':
        $selection = get_posts( array(
            'post_type' => 'any',
            'nopaging' => true,
            'order' => 'ASC',
            'orderby' => 'post__in',
            'post__in' => get_field('selection')
        ));
        break;
}  

if ( !empty( $selection ) ) {

    $block_css = array(
        'bloc-posts',
        'card-list',
        'card-list--'.get_field('type')
    );
    if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }	
    $block_attrs = array( 'class="'.implode( ' ', $block_css ).'"' );
    if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs[] = 'id="'.$block['anchor'].'"'; }

    echo '<ul '.implode(' ',$block_attrs).'>';
    foreach ( $selection as $selected ) {
        $pc_post_selected = new PC_Post( $selected );
        echo '<li class="card-list-item">';
            $pc_post_selected->display_card(get_field('title_level'));
        echo '</li>';
    }
    echo '</ul>';
}