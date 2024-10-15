<?php

if ( $event_ids = get_field('events') ) {
    
    $events = get_posts( array(
        'post_type' => EVENT_POST_SLUG,
        'nopaging' => true,
        'post__in' => $event_ids,
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_type' => 'DATETIME',
        'meta_key' => 'event_date_end'
    ));

    $block_css = array(
        'bloc-events',
        'card-list',
        'card-list--pages'
    );
    if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }	

    $block_attrs = array( 'class' => implode( ' ', $block_css ) );
    if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs['id'] = $block['anchor']; }

    $block_attrs = apply_filters( 'pc_filter_acf_block_events_attrs', $block_attrs, $block, $is_preview );

    echo '<ul '.pc_get_attrs_to_string( $block_attrs ).'>';
    foreach ( $events as $event ) {
        $pc_post_event = new PC_Post( $event );
        echo '<li class="card-list-item">';
            if ( $is_preview ) {
                $pc_post_event->display_card_block_editor( get_field('title_level'), ['card--event'] );
            } else {
                $pc_post_event->display_card( get_field('title_level'), ['card--event'] );
            }
        echo '</li>';
    }
    echo '</ul>';

} else if ( $is_preview ) {

	echo '<p class="bloc-warning">Erreur bloc <em>Événements</em> : aucun événement trouvé.</p>';

}