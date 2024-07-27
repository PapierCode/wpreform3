/*=====================================
=            Blocks Editor            =
=====================================*/

( function() {

    wp.domReady( function() {

		/*----------  Options de texte  ----------*/

		wp.richText.unregisterFormatType( 'core/image' );
		wp.richText.unregisterFormatType( 'core/code' );
		wp.richText.unregisterFormatType( 'core/keyboard' );
		wp.richText.unregisterFormatType( 'core/text-color' );
		wp.richText.unregisterFormatType( 'core/language' );

		/*----------  Styles  ----------*/

		wp.blocks.unregisterBlockStyle( 'core/button', 'fill' );
		wp.blocks.unregisterBlockStyle( 'core/button', 'outline' );	
   
    });

	/*----------  Filtre blocs  ----------*/
	
	function pcRemoveOptions( settings, name ) {

		if ( ['core/heading'].includes(name)  ) {
			return lodash.assign( {}, settings, {
				supports: lodash.assign( {}, settings.supports, {
					align: false
				} )
			} );
		}
		
		return settings;

	}
	 
	wp.hooks.addFilter(
		'blocks.registerBlockType',
		'pc/pcRemoveOptions',
		pcRemoveOptions
	);
	
} )()


/*=====  FIN Blocks Editor  =====*/