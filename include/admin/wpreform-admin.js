
document.addEventListener( 'DOMContentLoaded', function () {
    
    /*----------  Compteurs texte  ----------*/
            
    const pcCounters = document.querySelectorAll( '.pc-txt-length-counter' );
    
    if ( pcCounters.length > 0 ) {

        const updatePcCounter = ( counter, max, txt, display ) => {   
            txt = txt.replace(/\[{1,}([ a-z0-9]+)\]{1,}/img, '$1');
            txt = txt.replace(/\{{1,}([ a-z0-9]+)\}{1,}/img, '$1');
            txt = txt.replace(/\{|\}|\[|\]/g, '');                                   
            display.textContent = txt.length;
            if ( length > max ) { counter.style.color = 'red'; }
            else { counter.removeAttribute('style'); }
        };

        pcCounters.forEach( (counter) => {
            const max = counter.dataset.size;
            const display = counter.querySelector('.pc-txt-length-value');
            const field = counter.closest('.acf-input').querySelector('input,textarea');
            updatePcCounter( counter, max, field.value, display );
            field.addEventListener( 'input', (event) => { 
                updatePcCounter( counter, max, event.currentTarget.value, display );
            } );
        });

        // acf.add_action('wysiwyg_tinymce_init', function( ed, id, mceInit, field ){
        //     const counter = field[0].querySelector('.pc-txt-length-counter');
        //     const value = counter.querySelector('.pc-txt-length-value');
        //     updatePcCounter( counter, 300, ed.getBody().textContent.length, value );
        //     ed.on('change', (event)=> { updatePcCounter( counter, 300, ed.getBody().textContent.length, value ); });
        // });

    }

});