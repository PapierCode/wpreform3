<?php
$address = get_field('address');

if ( $address ) {

    $block_css = array( 'bloc-map' );
    if ( isset( $block['className'] ) && trim( $block['className'] ) ) { $block_css[] = $block['className']; }
    if ( $is_preview ) { $block_css[] = 'bloc-no-preview'; }

    $block_attrs = array( 'class' => implode( ' ', $block_css ) );
    if ( isset( $block['anchor'] ) && trim( $block['anchor'] ) ) { $block_attrs['id'] = $block['anchor']; }

    $block_attrs['aria-hidden'] = 'true';

    $block_attrs = apply_filters( 'pc_filter_acf_block_map_attrs', $block_attrs, $block, $is_preview );
    echo '<div '.pc_get_attrs_to_string( $block_attrs ).'>';

        $map_json = array(
            'lat' => $address['lat'],
            'lng' => $address['lng'],
            'marker' => apply_filters( 'pc_filter_bloc_map_marker' , array(
                'svg' => '<svg width="22" height="32"><circle cx="11.09" cy="11.52" r="4.99" class="circle"/><path d="M11,0h0A11.5,11.5,0,0,0,0,11.9c0,3.6,1.7,6.4,3.6,9.19L11,32s7-10.26,7.41-10.9C20.3,18.3,22,15.5,22,11.9A11.5,11.5,0,0,0,11,0Zm0,16.51a5,5,0,1,1,4.9-5,4.94,4.94,0,0,1-4.9,5Z" class="pointer"/></svg>',
                'width' => 22,
                'height' => 32
            ))
        );
        echo '<script>const mapArgs_'.$block['id'].'='.json_encode( $map_json, JSON_PRETTY_PRINT ).'</script>';

        if ( $is_preview ) {
            echo '<p><strong>Carte, adresse :</strong><br>'.$address['address'].'</p>';
        } else {
            echo '<div id="'.$block['id'].'" class="map"></div>';
        }

    echo '</div>';

} else if ( $is_preview ) {

	echo '<p class="bloc-warning">Erreur bloc <em>Carte</em> : saisissez une addresse.</p>';

}