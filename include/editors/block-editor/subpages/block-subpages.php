<?php
$error = false;
$type = get_field('type') ?? 'subpages';
$get_subpages_args = array(
    'post_type' => 'any',
    'nopaging' => true,
);

switch ( $type ) {
    case 'subpages':
        global $post;
        $get_subpages_args['post_parent'] = $post->ID;
        $get_subpages_args['order'] = 'ASC';
        $get_subpages_args['orderby'] = 'menu_order';
        $get_subpages = get_posts( $get_subpages_args );
        if ( empty($get_subpages) ) {
            $error = true;
            $msg_error = 'aucune sous-page trouvée';
        }
        break;
    case 'selection':
        $selection = get_field('selection');
        if ( $selection ) {
            $get_subpages_args['post__in'] = $selection;
            $get_subpages_args['orderby'] = 'post__in';
            $get_subpages = get_posts( $get_subpages_args );
            if ( empty($get_subpages) ) {
                $error = true;
                $msg_error = 'aucune page trouvée';
            }
    
        } else {
            $error = true;
            $msg_error = 'sélectionnez des pages';
        }
        break;
    case 'siblings':
        global $post;
        if ( $post->post_parent ) {
            $get_subpages_args['post_parent'] = $post->post_parent;
            $get_subpages_args['order'] = 'ASC';
            $get_subpages_args['orderby'] = 'menu_order';
            $get_subpages_args['post__not_in'] = array($post->ID);
            $get_subpages = get_posts( $get_subpages_args );
            if ( empty($get_subpages) ) {
                $error = true;
                $msg_error = 'aucune page soeur trouvée';
            }
    
        } else {
            $error = true;
            $msg_error = 'cette page n\'a pas de parent';
        }
        break;
}

if ( !$error ) {

    $block_css = array(
        'bloc-subpages',
        'card-list',
        'card-list--pages'
    );
    if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }	

    $block_attrs = array( 'class' => implode( ' ', $block_css ) );
    if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs['id'] = $block['anchor']; }

    $block_attrs = apply_filters( 'pc_filter_acf_block_subpages_attrs', $block_attrs, $block, $is_preview );

    echo '<ul '.pc_get_attrs_to_string( $block_attrs ).'>';
    foreach ( $get_subpages as $page ) {
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

	echo '<p class="bloc-warning">Erreur bloc <em>Sous-pages</em> : '.$msg_error.'.</p>';

}