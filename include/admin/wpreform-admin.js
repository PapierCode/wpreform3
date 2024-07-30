
document.addEventListener( 'DOMContentLoaded', function () {
    
    /*----------  Compteurs texte  ----------*/
            
    const pcCounters = document.querySelectorAll( '.pc-txt-length-counter' );
    
    if ( pcCounters.length > 0 ) {

        const updatePcCounter = ( counter, max, length, value ) =>   {          
            value.textContent = length;
            if ( length > max ) { counter.style.color = 'red'; }
            else { counter.removeAttribute('style'); }
        };

        pcCounters.forEach( (counter) => {
            const max = counter.dataset.size;
            const value = counter.querySelector('.pc-txt-length-value');
            const field = counter.closest('.acf-input').querySelector('input,textarea');
            updatePcCounter( counter, max, field.value.length, value );
            field.addEventListener( 'input', (event) => { 
                updatePcCounter( counter, max, event.currentTarget.value.length, value );
            } );
        });

    }

});