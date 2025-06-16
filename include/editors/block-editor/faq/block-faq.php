<?php
$error = '';
$type = get_field('type') ?? 'all';
$get_questions_args = array(
    'post_type' => FAQ_POST_SLUG,
    'nopaging' => true,
);

switch ( $type ) {
    case 'all':
        $get_questions = get_posts( $get_questions_args );
        if ( empty($get_questions) ) { 
            $error = 'aucune question trouvée'; 
        }
        break;
    case 'selection':
        $selection = get_field('selection');
        if ( $selection ) {
            $get_questions_args['post__in'] = $selection;
            $get_questions_args['orderby'] = 'post__in';
            $get_questions = get_posts( $get_questions_args );
    
        } else {
            $error = 'sélectionnez des questions';
        }
        break;
}

if ( !$error ) {

    $block_css = array(
        'bloc-faq'
    );
    if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }	

    $block_attrs = array( 'class' => implode( ' ', $block_css ) );
    if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs['id'] = $block['anchor']; }

    $block_attrs = apply_filters( 'pc_filter_acf_block_faq_attrs', $block_attrs, $block, $is_preview );

    foreach ( $get_questions as $question ) {
        echo '<details class="question">';
            echo '<summary class="question-title">'.$question->post_title.'&nbsp;?</summary>';
            echo '<div class="question-answer tiny-editor">'.wpautop(trim(get_field('faq_answer',$question->ID))).'</div>';
        echo '</details>';
    }

} else if ( $is_preview ) {

	echo '<p class="bloc-warning">Erreur bloc <em>Foire aux questions</em> : '.$error.'.</p>';

}