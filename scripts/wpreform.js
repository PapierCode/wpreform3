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

	// const toggleDisplay = ( btn, target, targetTabIndex, btnIco ) => {
		
	// 	if ( btn.getAttribute('aria-expanded') == 'false' ) { // si fermé > ouvrir

	// 		target.setAttribute('aria-hidden',false);
	// 		targetTabIndex.forEach( element => { element.removeAttribute('tabindex'); } ); // enfant accessible à la tabulation	

	// 		btn.setAttribute('aria-expanded',true);
	// 		btn.setAttribute('title','Masquer '+btn.dataset.txt);
	// 		btn.setAttribute('aria-label','Masquer '+btn.dataset.txt);
	// 		btn.querySelector('.ico').innerHTML = sprite.cross;

	// 	} else { // si ouvert > fermer
			
	// 		target.setAttribute('aria-hidden',true);
	// 		targetTabIndex.forEach( element => { element.setAttribute('tabindex','-1'); } ); // enfant non accessible à la tabulation

	// 		btn.setAttribute('aria-expanded',false);
	// 		btn.setAttribute('title','Afficher '+btn.dataset.txt);
	// 		btn.setAttribute('aria-label','Afficher '+btn.dataset.txt);
	// 		btn.querySelector('.ico').innerHTML = btnIco;

	// 	}
		
	// };

	// const btnToggleDisplay = document.querySelectorAll('.js-toggle-display');

	// if ( btnToggleDisplay.length > 0 ) {
		
	// 	btnToggleDisplay.forEach((btn) => {
	// 		const target = document.querySelector('#'+btn.getAttribute('aria-control'));
	// 		const tabindex = target.querySelectorAll('a,button,input,select,textarea');
	// 		const btnIco = btn.querySelector('.ico').innerHTML;
			
	// 		btn.addEventListener('click', (event) => {
	// 			toggleDisplay( btn, target, tabindex, btnIco );
	// 		});
	// 	});
	// }

	/*----------  Modal  ----------*/

	const btnOpenModal = document.querySelectorAll('.modal-btn-open');
	const body = document.querySelector('body');

	if ( btnOpenModal ) {

		btnOpenModal.forEach( (btn) => {

			const modal = document.querySelector('#'+btn.getAttribute('aria-control'));
			const btnsClose = modal.querySelectorAll('.modal-btn-close');
		
			btn.addEventListener( 'click', () => {
				modal.showModal();
				body.style.overflow = 'hidden';
			});
			
			btnsClose.forEach( (btnClose) => {
				btnClose.addEventListener( 'click', () => {
					modal.close();
					btn.focus();
					body.style.overflow = 'auto';
				});
			});
		
			modal.addEventListener('click', (event) => {
				if ( event.target === modal ) { 
					modal.close();
					btn.focus();
					body.style.overflow = 'auto';
				}
			});

		});

	}

	/*----------  Bloc map  ----------*/
	
	const mapBlocs = document.querySelectorAll('.bloc-map');

	if ( mapBlocs.length > 0 ) {

		mapBlocs.forEach( ( bloc ) => {

			const container = bloc.querySelector('.map');
			const args = eval('mapArgs_'+container.id);
			
			const center = [args.lat, args.lng];
			
			const map = L.map( container.id, {
				scrollWheelZoom: false,
				tap: false
			}).setView( center, 13 );

			L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoicGFwaWVyY29kZSIsImEiOiJja203a3E5N3kweXplMnhuNjBuOTV2bmQ1In0.UtKowadsitAGdxDUpMv5aA', {
				id: 'mapbox/streets-v11',
				tileSize: 512,
				zoomOffset: -1
			}).addTo( map );
		
			const marker = L.marker( center, {
				icon: L.divIcon({
					html : args.marker.svg,
					iconSize: [args.marker.width, args.marker.height],
					popupAnchor: [0, -0.5*args.height]
				})
			}).addTo( map );
		});

	}

});