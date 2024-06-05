<?php
$template =  [ 
	['core/paragraph', [ 'placeholder' => 'Citation...']], 
	['core/paragraph', [ 'placeholder' => 'Source...']]
];

?>

<InnerBlocks 
	template="<?php echo esc_attr( wp_json_encode( $template ) ) ?>" 
	templateLock="all" 
/>