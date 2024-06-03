<?php
global $post;
$subpages = get_posts( array(
    'post_type' => 'page',
    'nopaging' => true,
    'order' => 'ASC',
    'orderby' => 'menu_order',
    'post_parent' => $post->ID
));   

if ( !empty( $subpages ) ) {

    $block_css = array(
        'bloc-subpages',
        'card-list',
        'card-list--subpages'
    );	
    if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }	
    $block_attrs = array( 'class="'.implode( ' ', $block_css ).'"' );
    if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs[] = 'id="'.$block['anchor'].'"'; }

    $subpages_title_level = get_field('title_level');
    		
    echo '<ul '.implode(' ',$block_attrs).'>';
        foreach ( $subpages as $subpage ) {
            echo '<li class="card-list-item">';
            if ( $is_preview ) {
                echo '<p>'.$subpage->post_title.'</p>';
            } else {
                $otro_post = new PC_Post( $subpage );
                $otro_post->display_card( $subpages_title_level, array('card--page') );
            }                
            echo '</li>';
        }
    echo '</ul>';

} else if ( $is_preview ) {

    echo '<p class="editor-error">Erreur bloc <em>Sous-pages</em> : aucune sous-page trouvée.</p>';

}