/* Déclaration variable globale */
let btnScroll;

/* Fonction pour remonter en haut de la page */
function actionScrollTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

function handleScrollVisibility() {
    // Paramétrage à 200 pixels pour le scroll avant que le bouton s'affiche
    if (window.scrollY > 200) {
        btnScroll.classList.add('visible');
    } else {
        btnScroll.classList.remove('visible');
    }
}

/* Initialisation chargement du DOM */
function init() {
    btnScroll = document.getElementById("scrollToTop");

    if (btnScroll) {

        btnScroll.addEventListener("click", actionScrollTop);
        window.addEventListener("scroll", handleScrollVisibility);
    }
}

/* Chargement du DOM complet avant l'exécution */

window.addEventListener("load", init);

