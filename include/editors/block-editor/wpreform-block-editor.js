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
		wp.blocks.unregisterBlockVariation( 'core/paragraph', 'stretchy-paragraph' );
		wp.blocks.unregisterBlockVariation( 'core/heading', 'stretchy-heading' );
   
    });

	/*----------  Filtre blocs  ----------*/
	
	function pcEditCoreBlocks( settings, name ) {		

		if ( ['core/heading'].includes(name)  ) {
			return lodash.assign( {}, settings, {
				supports: lodash.assign( {}, settings.supports, {
					align: false
				} )
			} );
		}
		
		if ( ['core/button'].includes(name)  ) {
			return { ...settings, ...{ parent: [ 'acf/pc-buttons' ] } };		
		}
		
		return settings;

	}
	 
	wp.hooks.addFilter(
		'blocks.registerBlockType',
		'pc/pcEditCoreBlocks',
		pcEditCoreBlocks
	);
	
} )()


/*=====  FIN Blocks Editor  =====*/