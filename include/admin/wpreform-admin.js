
document.addEventListener( 'DOMContentLoaded', function () {
    
    /*----------  ACF text & textarea  ----------*/
            
    const acfFields = document.querySelectorAll( '.acf-field-text:has(.pc-txt-length-counter), .acf-field-textarea:has(.pc-txt-length-counter)' );
    
    if ( acfFields.length > 0 ) {

        const updatePcCounter = ( counter, max, txt, display ) => {   
            txt = txt.replace(/\[{1,}([ a-z0-9]+)\]{1,}/img, '$1'); // [strong]
            txt = txt.replace(/\{{1,}([ a-z0-9]+)\}{1,}/img, '$1'); // {em}
            txt = txt.replace(/\{|\}|\[|\]/g, '');                               
            display.textContent = txt.length;
            if ( txt.length > max ) { counter.style.color = 'red'; }
            else { counter.removeAttribute('style'); }
        };

        acfFields.forEach( (acfField) => {
            const counter = acfField.querySelector('.pc-txt-length-counter')
            const display = counter.querySelector('.pc-txt-length-value');
            const max = counter.dataset.size;
            const field = acfField.querySelector('input,textarea');
            updatePcCounter( counter, max, field.value, display );
            field.addEventListener( 'input', (event) => { 
                updatePcCounter( counter, max, event.currentTarget.value, display );
            } );
        });

    }

    /*----------  Tinymce settings  ----------*/
    
    if ( typeof acf !== 'undefined' ) {        

        const updatePcWysiCounter = ( counter, max, txt, display ) => {                                                          
            display.textContent = txt.length;
            if ( txt.length > max ) { counter.style.color = 'red'; }
            else { counter.removeAttribute('style'); }
        };

        acf.add_action( 'wysiwyg_tinymce_init', function( editor, id, mceInit, field ){

            // ajustement hauteur zone de saisie
            const iframe = editor.getContentAreaContainer().children[0];
            iframe.style.minHeight = '0';
            editor.settings.autoresize_min_height = 90;        

            // compteur
            const counter = field[0].querySelector('.pc-txt-length-counter');
            if ( counter ) {         
                const counterLength = counter.dataset.size;  
                const display = counter.querySelector('.pc-txt-length-value');
                updatePcWysiCounter( counter, counterLength, editor.getBody().textContent, display );
                editor.on('change', (event)=> { updatePcWysiCounter( counter, counterLength, editor.getBody().textContent, display ); });
            }

        });
        
        acf.add_filter( 'wysiwyg_tinymce_settings', function( mceInit, id ) {
            
            // Visualisation des blocs
            mceInit.plugins = mceInit.plugins + ',visualblocks';
            mceInit.visualblocks_default_state = true;
            // nettoyage texte copié
            mceInit.paste_as_text = true;
            // ajustement hauteur zone de saisie
            mceInit.wp_autoresize_on = true;
            
            return mceInit;
                    
        } );

    }

});