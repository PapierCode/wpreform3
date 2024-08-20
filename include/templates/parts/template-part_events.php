<?php
/**
 * 
 * Template événement
 * 
 * Affichage date
 * 
 */


/*======================================
=            Affichage date            =
======================================*/

function pc_display_event_date( $pc_post, $context = 'single' ) {

    $metas = $pc_post->metas;

    if ( $metas['event_canceled'] ) {
        echo pc_display_alert_msg(
            apply_filters( 'pc_filter_event_single_canceled_text', '<strong>Événement annulé</strong>', $pc_post ),
            'error',
            'block'
        );
    }	

    $start = $metas['event_date_start'];
    $end = $metas['event_date_end'];

    $css = [ $context.'-date' ];
    if ( $metas['event_date_txt'] ) { $css[] = $context.'-date--custom'; }

    echo '<p class="'.implode(' ',$css).'">';
    echo '<span class="ico">'.pc_svg('calendar').'</span>';
    echo '<span class="txt">';

    if ( $metas['event_date_txt'] ) {

        // texte libre
        echo $metas['event_date_txt'];

    } else {		

        /*----------  Dates identiques  ----------*/
        
        // même jour
        if ( date_i18n('z',$start) == date_i18n('z',$end) ) {

            // même heure
            if ( $start == $end ) { 
                echo '<time datetime="'.date_i18n('c',$start).'">'.date_i18n( 'j F Y \à G\hi', $start).'</time>';
            
            // heure différente
            } else {
                echo '<time datetime="'.date_i18n('Y-m-d',$start).'">'.date_i18n( 'j F Y', $start).'</time>';
                echo ' de <time datetime="'.date_i18n('H:i',$start).'">'.date_i18n('G\hi',$start).'</time>';
                echo ' à <time datetime="'.date_i18n('H:i',$end).'">'.date_i18n('G\hi',$end).'</time>';
            }
    
    
        /*----------  Dates différentes  ----------*/		
    
        } else {
    
            echo 'Du <time datetime="'.date_i18n('c',$start).'">'.date_i18n( 'j F Y \à G\hi', $start).'</time> au <time datetime="'.date_i18n('c',$end).'">'.date_i18n( 'j F Y \à G\hi', $end ).'</time>';
    
        }
            
    }

    echo '</span></p>';

}


/*----------  Card  ----------*/

add_action( 'pc_post_card_after_title', 'pc_display_event_card_date', 10 );

    function pc_display_event_card_date( $pc_post ) {

        if ( $pc_post->type == EVENT_POST_SLUG ) { pc_display_event_date( $pc_post, 'card' ); }

    }


/*----------  Single  ----------*/

add_action( 'pc_action_template_index', 'pc_display_event_single_date', 45 );

    function pc_display_event_single_date( $pc_post ) {

        if ( $pc_post->type == EVENT_POST_SLUG ) { pc_display_event_date( $pc_post, 'single' ); }

    }


/*=====  FIN Affichage date  =====*/