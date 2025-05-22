<?php
$embed_url = get_field( 'embed_url' );

if ( filter_var( $embed_url, FILTER_VALIDATE_URL ) ) {

	$providers = array(
		'YouTube' => 'https://policies.google.com/',
		'Dailymotion' => 'https://legal.dailymotion.com',
		'Vimeo' => 'https://vimeo.com/privacy',
	);

	$wp_oembed = null;
	if ( is_null( $wp_oembed ) ) { $wp_oembed = new WP_oEmbed(); }
	$datas = $wp_oembed->get_data($embed_url);
	// pc_var( $datas );

	$img = get_field( 'embed_img' );
	$img_url = $img ? $img['sizes']['medium'] : $datas->thumbnail_url;

	if ( $datas && array_key_exists( $datas->provider_name, $providers ) ) {

		$block_css = array( 'bloc-embed' );
		if ( $is_preview ) { $block_css[] = 'bloc-no-preview'; }
		if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }

		$block_attrs = array( 'class' => implode( ' ', $block_css ) );
		if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs['id'] = $block['anchor']; }

		$block_attrs = apply_filters( 'pc_filter_acf_block_embed_attrs', $block_attrs, $block, $is_preview );
		echo '<div '.pc_get_attrs_to_string( $block_attrs ).'>';

			if ( !$is_preview ) {

				$iframe_html = $datas->html;
				$iframe_css = array( 'background-image:url('.$img_url.')' );
				$iframe_padding = ( $datas->height / $datas->width ) * 100;
				$iframe_css[] = 'padding-bottom:'.$iframe_padding.'%';

				echo '<div class="iframe" style="'.implode(';',$iframe_css).'">';
					echo str_replace( 'src', 'data-src', $iframe_html );
					echo '<div class="iframe-accept">';
						printf( __('<p class="iframe-accept--txt">En lisant cette vidéo, vous acceptez les <a class="iframe-accept-cgu-link" href="%1s" target="blank" rel="noreferrer">conditions générales d\'utilisation</a> de <strong>%2s</strong>.</p>'), $providers[$datas->provider_name], $datas->provider_name );
						echo '<button type="button" class="button button--arrow"><span class="ico">'.pc_svg('arrow').'</span><span class="txt">'.__('Play video','wpreform').'</span></button>';
					echo '</div>';
				echo '</div>';

			} else { echo '<p><strong>Vidéo '.$datas->provider_name.'</strong> :<br>'.$datas->title.'</p>'; }

		echo '</div>';

	} else { 
		echo '<p class="bloc-warning">Erreur bloc <em>Vidéo</em> : la vidéo n\'a pas été trouvé, vérifiez l\'adresse de la page.</p>';
	}

} else if ( $is_preview ) {

	echo '<p class="bloc-warning">Erreur bloc <em>Vidéo</em> : saisissez l\'adresse d\'une vidéo Youtube, Vimeo ou Dailymotion.</p>';

}