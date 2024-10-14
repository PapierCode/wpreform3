/*----------  DOM chargé  ----------*/

document.addEventListener( 'DOMContentLoaded', () => {

	const rem = function( size, base ) {
		if ( base == undefined ) { base = 16; }
		return size / base + 'rem';
	};

	/*----------  Vidéo  ----------*/
	
	const blocEmbeds = document.querySelectorAll('.bloc-embed');
	
	blocEmbeds.forEach( (bloc) => {
		const iframe = bloc.querySelector('iframe');
		const src = iframe.dataset.src;
		const rgpd = bloc.querySelector('.iframe-accept');
		const btn = bloc.querySelector('button');
		btn.addEventListener( 'click', () => {
			iframe.setAttribute( 'src', src );
			rgpd.remove();
		});
	});
	

	/*----------  Toggle display  ----------*/

	const toggleDisplay = ( btn, target, targetTabIndex, btnIco ) => {
		
		if ( btn.getAttribute('aria-expanded') == 'false' ) { // si fermé > ouvrir

			target.setAttribute('aria-hidden',false);
			targetTabIndex.forEach( element => { element.removeAttribute('tabindex'); } ); // enfant accessible à la tabulation	

			btn.setAttribute('aria-expanded',true);
			btn.setAttribute('title','Masquer '+btn.dataset.txt);
			btn.setAttribute('aria-label','Masquer '+btn.dataset.txt);
			btn.querySelector('.ico').innerHTML = sprite.cross;

		} else { // si ouvert > fermer
			
			target.setAttribute('aria-hidden',true);
			targetTabIndex.forEach( element => { element.setAttribute('tabindex','-1'); } ); // enfant non accessible à la tabulation

			btn.setAttribute('aria-expanded',false);
			btn.setAttribute('title','Afficher '+btn.dataset.txt);
			btn.setAttribute('aria-label','Afficher '+btn.dataset.txt);
			btn.querySelector('.ico').innerHTML = btnIco;

		}
		
	};

	const btnToggleDisplay = document.querySelectorAll('.js-toggle-display');

	if ( btnToggleDisplay.length > 0 ) {
		
		btnToggleDisplay.forEach((btn) => {
			const target = document.querySelector('#'+btn.getAttribute('aria-control'));
			const tabindex = target.querySelectorAll('a,button,input,select,textarea');
			const btnIco = btn.querySelector('.ico').innerHTML;
			
			btn.addEventListener('click', (event) => {
				toggleDisplay( btn, target, tabindex, btnIco );
			});
		});
	}

});

/*----------  Galerie jQuery  ----------*/

jQuery(document).ready(function($){

	$('.diaporama').gallery({
		btnNextInner:sprite.arrow,
		btnPrevInner:sprite.arrow,
		btnCloseInner:sprite.cross,
		moveDuration:500,
		responsiveImg:true
	});

	$('.gallery-play').click(function() {
		$(this).parents('ul').find('>:first-child').children('a').click();
	});

});