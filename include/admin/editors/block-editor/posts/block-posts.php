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

    echo '<ul class="card-list">';
    foreach ( $selection as $selected ) {
        $pc_post_selected = new PC_Post( $selected );
        $pc_post_selected->display_card(get_field('title_level'));
    }
    echo '</ul>';
}