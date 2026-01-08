/*----------  DOM chargé  ----------*/

document.addEventListener( 'DOMContentLoaded', () => {

	const body = document.querySelector('body');
    let resizeTimer = null;

	const rem = function( size, base ) {
		if ( base == undefined ) { base = 16; }
		return size / base + 'rem';
	};

	const hNavBtn = document.querySelector('.h-nav-btn');
	const hNavModal = document.querySelector('.h-nav-modal');

	window.addEventListener( 'resize', () => {
    
        clearTimeout(resizeTimer);
    
        resizeTimer = setTimeout( () => {
    
            if ( hNavModal && getComputedStyle( hNavBtn ).display == 'none' ) {
				hNavModal.close();
				body.removeAttribute('style');
			}
    
        }, 250 );
    
    } );


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
	

	/*----------  Recherche  ----------*/

	const searchBtn = document.querySelector('.h-btn-toggle-search');

	if ( searchBtn ) {

		const searchBtnParentStyles = window.getComputedStyle(searchBtn.parentNode);
		const searchBox = document.querySelector('#header-form-search-box');
		const searchElts = searchBox.querySelectorAll('button,input');

		const searchBtnClick = () => {

			if ( searchBtn.getAttribute('aria-expanded') == 'false' ) {

				searchBtn.setAttribute( 'aria-expanded', true );
				searchBtn.setAttribute( 'aria-label', 'Masquer le formulaire de recherche' );
				searchBox.setAttribute( 'aria-hidden', 'false' );
				searchElts.forEach( elt => { elt.removeAttribute('tabindex'); } );

			} else {

				searchBtn.setAttribute( 'aria-expanded', false );
				searchBtn.setAttribute( 'aria-label', 'Afficher le formulaire de recherche' );
				searchBox.setAttribute( 'aria-hidden', 'true' );
				searchElts.forEach( elt => { elt.setAttribute('tabindex','-1'); } );

			}
			
		};

		const searchToggle = () => {

			if ( searchBtnParentStyles.display != 'none' ) {

				searchBtn.classList.add('active');
				searchBtn.addEventListener( 'click', searchBtnClick );

				searchBox.setAttribute('aria-hidden','true');
				searchElts.forEach( elt => { elt.setAttribute('tabindex','-1'); } );

			} else if ( searchBtnParentStyles.display == 'none' && searchBtn.classList.contains('active') ) {
				
				searchBtn.classList.remove('active');
				searchBtn.setAttribute( 'aria-expanded', false );
				searchBtn.setAttribute( 'aria-label', 'Afficher le formulaire de recherche' );

				searchBtn.removeEventListener( 'click', searchBtnClick );
				searchBox.removeAttribute( 'aria-hidden' );
				searchElts.forEach( elt => { elt.removeAttribute('tabindex'); } );

			}

		};

		searchToggle();
		let resizeTimer = null;
		window.addEventListener( 'resize', () => { 
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout( searchToggle, 250 );
		} );

	}


	/*----------  Modale  ----------*/

	const btnOpenModal = document.querySelectorAll('.modal-btn-open');
	const focusableElsQuery = ':is(a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], input[type="number"], input[type="date"], select):not([disabled])';

	const trapFocus = ( container, firstFocusableEl, lastFocusableEl ) => {

		container.addEventListener( 'keydown', function(event) {

			const isTabPressed = ( event.key === 'Tab' || event.keyCode === 9 );
			if ( !isTabPressed ) { return; }

			if ( event.shiftKey ) {  /* shift + tab */
				if ( document.activeElement === firstFocusableEl ) {
					lastFocusableEl.focus();
					event.preventDefault();
				}
			} else { /* tab */
				if ( document.activeElement === lastFocusableEl ) {
					firstFocusableEl.focus();
					event.preventDefault();
				}
			}

		});

	};

	if ( btnOpenModal ) {

		btnOpenModal.forEach( (btn) => {

			const modal = document.querySelector('#'+btn.getAttribute('aria-control'));
			const btnsClose = modal.querySelectorAll('.modal-btn-close');

			const focusableEls = modal.querySelectorAll(focusableElsQuery);
			const firstFocusableEl = focusableEls[0];  
			const lastFocusableEl = focusableEls[focusableEls.length - 1];
			
			btn.addEventListener( 'click', () => {
				modal.showModal();
				if ( firstFocusableEl ) { firstFocusableEl.focus(); }
				body.style.overflow = 'hidden';
			});
			
			btnsClose.forEach( (btnClose) => {
				btnClose.addEventListener( 'click', () => {
					modal.close();
					btn.focus();
					body.removeAttribute('style');
				});
			});
		
			// clic hors modale
			modal.addEventListener( 'click', (event) => {
					console.log(event.target);
				if ( event.target === modal ) { 
					modal.close();
					btn.focus();
					body.removeAttribute('style');
				}
			});

			trapFocus( modal, firstFocusableEl, lastFocusableEl );

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

			L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoicGFwaWVyY29kZSIsImEiOiJjbTZhbTMwZTkwaXNvMm1zaWU4ZnR2NmFnIn0.KKkR3402RzV80Du2Hu1gLg', {
				id: 'papiercode/cm69i1x8x001t01r5568bhc7w',
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
	

	/*----------  Formulaires  ----------*/
	
	const inputFiles = document.querySelectorAll('input[type="file"');

	if ( inputFiles.length > 0 ) {

		inputFiles.forEach( (inputFile) => {

			let btn = inputFile.parentElement.querySelector('.input-file-btn');
			let msg = inputFile.parentElement.querySelector('.input-file-msg');
			
			btn.addEventListener( 'click', () => { inputFile.click(); });

			inputFile.addEventListener( 'change', (event) => {
				let val = event.currentTarget.value;				
				if ( val.includes('fakepath') ) { val = val.substring(12); }				
				msg.textContent = val;
			});

		});

	}

});