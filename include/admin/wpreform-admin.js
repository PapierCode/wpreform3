
document.addEventListener( 'DOMContentLoaded', function () {
    
    /*----------  Compteurs texte  ----------*/
            
    const pCounters = document.querySelectorAll( '.pc-text-counter' );
    
    if ( pCounters.length > 0 ) {

        pCounters.forEach( (counter) => {
            const max = counter.dataset.size;
            const value = counter.querySelector('.value');
            const field = counter.closest('.acf-input').querySelector('input,textarea')
            field.addEventListener( 'input', (event) => {
                let lengthCurrent = event.currentTarget.value.length;
                value.textContent = lengthCurrent;
                if ( lengthCurrent > max ) { counter.style.color = 'red'; }
                else { counter.removeAttribute('style'); }
            });
        });

    }

});