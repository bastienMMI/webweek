/*--
    Déclaration des variables globales
--*/
let btnScroll;

/*--
    Déclaration des fonctions de callback
--*/

/**
 * Fonction pour remonter en haut de la page
 * Elle utilise window.scrollTo avec un comportement fluide
 */
function actionScrollTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth' // Scroll fluide
    });
}

/**
 * Fonction pour gérer l'affichage du bouton selon la position du scroll
 */
function handleScrollVisibility() {
    // Si on a scrollé plus de 200px, on affiche le bouton
    if (window.scrollY > 200) {
        btnScroll.classList.add('visible');
    } else {
        btnScroll.classList.remove('visible');
    }
}

/**
 * Fonction d'initialisation (setup) appelée au chargement du DOM
 */
function init() {
    // Récupération de l'élément dans le DOM [cite: 708, 1206]
    btnScroll = document.getElementById("scrollToTop");

    // Vérification de sécurité pour éviter les erreurs console [cite: 721, 723]
    if (btnScroll) {
        // Abonnement au clic sur le bouton [cite: 709, 1000]
        btnScroll.addEventListener("click", actionScrollTop);
        
        // Abonnement au scroll de la fenêtre pour afficher/masquer le bouton
        window.addEventListener("scroll", handleScrollVisibility);
    }
}

/*--
    Attente du chargement complet du DOM avant exécution [cite: 1170, 1432]
--*/
window.addEventListener("load", init);