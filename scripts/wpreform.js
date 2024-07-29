/*----------  Conversion rem  ----------*/

var rem = function( size, base ) {
	if ( base == undefined ) { base = 16; }
	return size / base + 'rem';
};

/*----------  DOM chargé  ----------*/

document.addEventListener( 'DOMContentLoaded', () => {

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