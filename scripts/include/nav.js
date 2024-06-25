document.addEventListener( 'DOMContentLoaded', () => {

    /*=============================
    =            Utile            =
    =============================*/
    
    // conversion pixels en rem
    const rem = ( size, base = 16 ) => size / base + 'rem';
    
    // enfant(s) d'un même parent
    const getSiblings = ( target ) => {
        var siblings = []; 
        var sibling = target.parentNode.firstChild;
        while (sibling) {
            if ( sibling.nodeType === 1 && sibling !== target ) { siblings.push(sibling); }
            sibling = sibling.nextSibling;
        }
        return siblings;
    };
    
    
    /*=====  FIN Utile  =====*/
    
    /*=================================
    =            Fonctions            =
    =================================*/
    
    /*----------  Visibilité du bouton "burger"  ----------*/
    
    const btnBurgerVisibility = () => {
        return getComputedStyle( btnBurger ).display == 'none' ? false : true;
    };
    
    
    /*----------  Sous-menu ouvrir/fermer  ----------*/
    
    const navSubOpen = ( parent, btn, subList, siblings ) => {
    
        // autre élémént ouvert
        let open = siblings.find( ( sibling ) => { return sibling.classList.contains('is-open'); } );
        if ( open ) {
            openBtn = open.firstChild;
            openSubList = openBtn.nextSibling;
            navSubClose( open, openBtn, openSubList );
        }
    
        subList.ontransitionend = null; // ajouté à la fermeture
        subList.style.visibility = 'visible';
        parent.parentNode.ontransitionend = null; // ajouté à la fermeture
        parent.parentNode.style.overflow = 'visible';
    
        btn.setAttribute( 'aria-expanded', 'true' );
        parent.classList.add('is-open');
    
    };
    
    const navSubClose = ( parent, btn, subList ) => {
    
        // attendre la fin des transitions CSS
        parent.parentNode.ontransitionend = () => {				
            parent.parentNode.style.overflow = btnBurgerIsVisible ? 'hidden auto' : 'hidden';
        };
        subList.ontransitionend = () => {				
            subList.style.visibility = 'hidden';
            btn.setAttribute( 'aria-expanded', 'false' );
        };
    
        parent.classList.remove('is-open');
    
    };
    
    const navSubToggle = ( parent, btn, subList, siblings ) => {
    
        if ( parent.classList.contains('is-open') ) { navSubClose( parent, btn, subList ); }
        else { navSubOpen( parent, btn, subList, siblings ); }
    
    };
    
    
    /*----------  Menu ouvrir/fermer  ----------*/
    
    const navToggle = () => {
    
        if ( !navIsOpen ) { // ouvrir
    
            nav.ontransitionend = null; // ajouté à la fermeture
    
            // nav.style.visibility = 'visible';
            nav.setAttribute('aria-hidden', 'false');

            document.addEventListener( 'keydown', navKeyDown );
    
            btnBurger.setAttribute('aria-expanded','true');	
            btnBurger.setAttribute('title',btnBurgerTitleClose);

            body.classList.add('h-nav-is-open');
            navIsOpen = true;
    
        } else { // fermer
    
            if ( nav.ontransitionend == null ) {
    
                nav.ontransitionend = () => {
    
                    // nav.style.visibility = 'hidden'; 
                    document.removeEventListener( 'keydown', navKeyDown );		
                    btnBurger.setAttribute('aria-expanded','false');	
                    btnBurger.setAttribute('title',btnBurgerTitleOpen);		
                    nav.setAttribute('aria-hidden','true');
                    navIsOpen = false;

                    // fermer le sous-menu ouvert
                    let subOpen = nav.querySelector('button[aria-expanded="true"]');
                    if ( subOpen ) { subOpen.click(); }
    
                };
    
                body.classList.remove('h-nav-is-open');	
    
            }
            
        }
    
    };
    
    // zone hors menu ouvert
    const navOverlay = ( event ) => { if ( event.target == nav ) { navToggle(); } };
    // touche échap menu masqué
    const navKeyDown = ( event ) => { if ( event.key == 'Escape' ) {
        navToggle(); 
        btnBurger.focus();
    }};
    // touche échap submenu, menu visible
    const subKeyDown = ( event ) => { if ( event.key == 'Escape' ) {
        let open = [...liParents].find((li) => li.classList.contains('is-open'));
        if ( open ) {
            navSubClose( open, open.children[0], open.children[1] );
            open.children[0].focus();
        }
    } };
    
    
    /*----------  Mise à jour menu  ----------*/
    
    const navUpdate = () => {
    
        if ( liParents.length > 0 ) {
    
            /*----------  Sous-menus  ----------*/
            
            liParents.forEach( ( parent ) => {
                
                let btn = parent.firstChild;
                let subList = btn.nextSibling;  
                let siblings = getSiblings( parent );
    
                // ferme un sous-menu ouvert
                if ( parent.classList.contains( 'is-open' ) ) { navSubClose( parent, btn, subList ); }
    
                btn.onclick = () => { navSubToggle( parent, btn, subList, siblings ); };
                
                if ( !btnBurgerIsVisible ) { // menu visible
    
                    parent.onmouseenter = () => {
                        // btn.onclick = null;
                        navSubOpen( parent, btn, subList, siblings );
                    };
                    parent.onmouseleave = () => {
                        // TODO timer
                        // btn.onclick = () => { navSubToggle( parent, btn, subList, siblings ); };
                        navSubClose( parent, btn, subList );
                    };
                    document.addEventListener( 'keydown', subKeyDown );
    
                } else { // menu caché
    
                    parent.onmouseenter = null;
                    parent.onmouseleave = null;
                    document.removeEventListener( 'keydown', subKeyDown );
    
                }
    
            });
    
        } // FIN liParents foreach
    
    
        /*----------  Menu visible/caché  ----------*/    
    
        if ( btnBurgerIsVisible ) {
    
            nav.setAttribute('aria-labelledby','#header-nav-btn');
            nav.setAttribute('aria-hidden','true');
            // nav.style.visibility = 'hidden';
    
            btnBurger.onclick = () => { navToggle(); };
            nav.onclick = (event) => { navOverlay(event); }; 
    
        } else {
    
            btnBurger.onclick = null;
            nav.onclick = null; 
            nav.ontransitionend = null;
    
            btnBurger.setAttribute( 'aria-expanded', 'false' );
            btnBurger.setAttribute('title',btnBurgerTitleOpen);	
            nav.removeAttribute('aria-labelledby');
            nav.removeAttribute('aria-hidden');
            nav.removeAttribute('style');
    
            body.classList.remove('h-nav-is-open');
            navIsOpen = false;
    
        }
    
    };
    
    
    /*=====  FIN Fonctions  =====*/
    
    /*====================================================
    =            Variables/constantes de base            =
    ====================================================*/
    
    const body = document.querySelector( 'body' );
    
    const btnBurger = document.querySelector( '.h-nav-btn' );
    const btnBurgerTitleOpen = btnBurger.getAttribute('title');
    const btnBurgerTitleClose = btnBurger.dataset.title;
    
    const nav = btnBurger.nextSibling;
    const liParents = nav.querySelectorAll( '.h-p-nav-item--l1.is-parent' );
    
    let btnBurgerIsVisible = btnBurgerVisibility();
    let navIsOpen = false;
    
    // un timer pour prévenir la multiplication de l'événement
    let resizeTimer = null;

    
    /*=====  FIN Variables/constantes de base  =====*/
    
    /*==================================
    =            Responsive            =
    ==================================*/
    
    window.addEventListener( 'resize', () => {
    
        clearTimeout(resizeTimer);
    
        resizeTimer = setTimeout( () => {
    
            let currentBtnBurgerVisibility = btnBurgerVisibility();
    
            if ( btnBurgerIsVisible != currentBtnBurgerVisibility ) {
                btnBurgerIsVisible = currentBtnBurgerVisibility;
                navUpdate();
            }
    
        }, 250 );
    
    } );
    
    
    /*=====  FIN Responsive  =====*/
    
    /*=============================
    =            Start            =
    =============================*/
    
    if ( liParents.length > 0 ) {
    
        liParents.forEach( ( parent, index ) => {
    
            let btn = parent.firstChild;
            btn.setAttribute( 'id', 'h-nav-btn-submenu-' + index );
            btn.setAttribute( 'aria-controls', 'h-nav-submenu-' + index );
            btn.setAttribute( 'aria-expanded', 'false' );
    
            let subList = btn.nextSibling;
            subList.setAttribute( 'id', 'h-nav-submenu-' + index );
            subList.setAttribute( 'aria-labelledby', 'h-nav-btn-submenu-' + index );
            // if ( btnBurgerIsVisible ) { parent.parentNode.style.overflow = 'hidden auto'; }
            subList.style.visibility = 'hidden';
    
        });

        // sous-menu retour
        const btnClose = nav.querySelector( '.h-p-nav-sub-back' );
        if ( btnClose ) {
            btnClose.addEventListener( 'click', () => { 
                nav.querySelector('button[aria-expanded="true"]').click();
            } );
        }
    
    }
    
    navUpdate();
    
    
    /*=====  FIN Start  =====*/
    
    
} ); // FIN DOMContentLoaded