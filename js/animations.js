/**
 * Micro-interactions transverses :
 *  1. Ombre portée du header quand on quitte le haut de page
 *  2. Révélation des éléments .reveal au défilement
 *  3. Verrou du défilement quand une modale est ouverte
 * Tout est désactivé si l'utilisateur préfère les mouvements réduits.
 */
document.addEventListener("DOMContentLoaded", () => {

    const reduit = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    // 1. Header : ombre à partir de quelques pixels de défilement
    const header = document.querySelector("header");
    if (header) {
        const majOmbre = () => header.classList.toggle("header-ombre", window.scrollY > 8);
        majOmbre();
        window.addEventListener("scroll", majOmbre, { passive: true });
    }

    // 2. Révélation progressive
    const cibles = document.querySelectorAll(".reveal");
    if (cibles.length && !reduit && "IntersectionObserver" in window) {
        const observateur = new IntersectionObserver((entrees) => {
            entrees.forEach((entree) => {
                if (entree.isIntersecting) {
                    entree.target.classList.add("reveal-in");
                    observateur.unobserve(entree.target);
                }
            });
        }, { threshold: 0.12 });
        cibles.forEach((c) => observateur.observe(c));
    } else {
        cibles.forEach((c) => c.classList.add("reveal-in"));
    }
});

// 3. Verrou du défilement de fond quand la modale est ouverte
const styleVerrou = document.createElement("style");
styleVerrou.textContent = ".modale-ouverte { overflow: hidden; }";
document.head.appendChild(styleVerrou);
