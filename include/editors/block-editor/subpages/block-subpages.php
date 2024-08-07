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
        'card-list--pages'
    );
    if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }	
    $block_attrs = array( 'class="'.implode( ' ', $block_css ).'"' );
    if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs[] = 'id="'.$block['anchor'].'"'; }

    echo '<ul '.implode(' ',$block_attrs).'>';
    foreach ( $subpages as $page ) {
        $pc_post_page = new PC_Post( $page );
        echo '<li class="card-list-item">';
            if ( $is_preview ) {
                $pc_post_page->display_card_block_editor( get_field('title_level'), ['card--page'] );
            } else {
                $pc_post_page->display_card( get_field('title_level'), ['card--page'] );
            }
        echo '</li>';
    }
    echo '</ul>';

} else if ( $is_preview ) {

	echo '<p class="bloc-warning">Erreur bloc <em>Sous-pages</em> : aucune sous-page trouvée.</p>';

}