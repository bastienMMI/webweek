/**
 * Page « Boutique » — réservation d'un article en AJAX
 * (api/reserver_produit.php).
 *
 * Chaque carte produit porte un petit formulaire quantité + bouton.
 * La réponse de l'API est affichée dans la zone #boutique-retour,
 * et le stock affiché sur la carte est mis à jour sans rechargement.
 */
document.addEventListener("DOMContentLoaded", () => {

    const retour = document.getElementById("boutique-retour");
    const formulaires = document.querySelectorAll(".form-resa-produit");

    if (!retour || formulaires.length === 0) return;

    const afficher = (texte, type) => {
        retour.innerHTML = "";
        const p = document.createElement("p");
        p.className = type === "ok" ? "message-succes" : "message-erreur";
        p.textContent = texte;
        retour.appendChild(p);
        retour.scrollIntoView({ behavior: "smooth", block: "nearest" });
    };

    formulaires.forEach((form) => {
        form.addEventListener("submit", async (evenement) => {
            evenement.preventDefault();

            const idProduit = form.dataset.id;
            const nom       = form.dataset.nom;
            const champQte  = form.querySelector(".qte-input");
            const bouton    = form.querySelector(".btn-reserver");
            const quantite  = Math.max(1, parseInt(champQte.value, 10) || 1);

            bouton.disabled = true;
            bouton.textContent = "Réservation…";

            const donnees = new FormData();
            donnees.append("id_produit", idProduit);
            donnees.append("quantite", quantite);

            try {
                const reponse = await fetch("api/reserver_produit.php", {
                    method: "POST",
                    body: donnees,
                });
                const data = await reponse.json();

                if (data.succes) {
                    afficher(`${nom} ×${quantite} — ${data.message}`, "ok");

                    // Mise à jour du stock affiché sans recharger la page
                    const restant = data.stock_restant;
                    champQte.max = Math.min(restant, 10);
                    if (restant <= 0) {
                        const carte = form.closest(".product-card");
                        carte.classList.add("product-epuise");
                        form.outerHTML = '<span class="product-indispo">Bientôt de retour</span>';
                    } else if (restant <= 5) {
                        const visuel = form.closest(".product-card").querySelector(".product-visuel");
                        let badge = visuel.querySelector(".badge-stock");
                        if (!badge) {
                            badge = document.createElement("span");
                            badge.className = "badge badge-stock";
                            visuel.appendChild(badge);
                        }
                        badge.textContent = `Plus que ${restant}`;
                    }
                } else if (data.connecte === false) {
                    window.location.href = "connexion.php";
                    return;
                } else {
                    afficher(data.erreur || "La réservation a échoué.", "erreur");
                }
            } catch (e) {
                afficher("Le serveur ne répond pas. Merci de réessayer.", "erreur");
            } finally {
                if (bouton.isConnected) {
                    bouton.disabled = false;
                    bouton.textContent = "Réserver";
                }
            }
        });
    });
});
