/**
 * Page « Adopter » — interactions AJAX :
 *  1. filtrage de la liste des animaux (api/get_animaux.php)
 *  2. fiche détaillée d'un animal (api/get_animal.php)
 *  3. demande de pré-réservation (api/reserver.php)
 *
 * Le site reste utilisable sans JavaScript : le formulaire de filtres
 * se soumet alors normalement en GET.
 */
document.addEventListener("DOMContentLoaded", () => {

    const form      = document.getElementById("filter-form");
    const grille    = document.getElementById("animal-grid");
    const compteur  = document.getElementById("resultat-compteur");
    const modale    = document.getElementById("fiche-animal");
    const corps     = document.getElementById("fiche-corps");
    const fermer    = document.getElementById("fiche-fermer");

    if (!form || !grille) return;

    // Échappe le texte venant de la base avant de l'injecter dans le HTML
    const esc = (valeur) => {
        const d = document.createElement("div");
        d.textContent = valeur ?? "";
        return d.innerHTML;
    };

    let dernierDeclencheur = null; // pour rendre le focus à la fermeture

    /* ---------------------------------------------------------------
       1. Filtrage de la liste
       --------------------------------------------------------------- */

    const gabaritCarte = (a) => `
        <article class="animal-card ${a.statut !== "disponible" ? "animal-card-indisponible" : ""}">
            <img src="images/animaux/${esc(a.photo)}" alt="${esc(a.alt)}" loading="lazy">
            <div class="animal-card-body">
                <h3>${esc(a.nom)}</h3>
                <p class="animal-meta">
                    ${esc(a.espece_label)} · ${esc(a.sexe_label)} · ${esc(a.age_lisible)}
                </p>
                <ul class="animal-badges">
                    ${a.vaccine   ? '<li class="badge badge-ok">Vacciné</li>'   : ""}
                    ${a.sterilise ? '<li class="badge badge-ok">Stérilisé</li>' : ""}
                    ${a.identifie ? '<li class="badge badge-ok">Identifié</li>' : ""}
                    ${a.statut !== "disponible" ? `<li class="badge badge-statut">${esc(a.statut_label)}</li>` : ""}
                </ul>
                <button type="button" class="btn-detail" data-id="${a.id_animal}">
                    Voir la fiche de ${esc(a.nom)}
                </button>
            </div>
        </article>`;

    const chargerAnimaux = async () => {
        const params = new URLSearchParams(new FormData(form)).toString();
        grille.setAttribute("aria-busy", "true");

        try {
            const reponse = await fetch(`api/get_animaux.php?${params}`);
            const data = await reponse.json();

            if (!data.succes) throw new Error(data.erreur);

            if (data.total === 0) {
                grille.innerHTML = '<p class="empty-msg">Aucun animal ne correspond à vos critères pour le moment.</p>';
            } else {
                grille.innerHTML = data.animaux.map(gabaritCarte).join("");
            }

            if (compteur) {
                compteur.textContent = `${data.total} animal${data.total > 1 ? "ux" : ""} à l'adoption`;
            }

            // L'URL suit les filtres : la page reste partageable et rechargeable
            history.replaceState(null, "", params ? `adopter.php?${params}` : "adopter.php");

        } catch (e) {
            grille.innerHTML = '<p class="message-erreur">Le chargement des animaux a échoué. Merci de réessayer.</p>';
        } finally {
            grille.removeAttribute("aria-busy");
        }
    };

    form.addEventListener("change", chargerAnimaux);
    form.addEventListener("submit", (e) => { e.preventDefault(); chargerAnimaux(); });

    /* ---------------------------------------------------------------
       2. Fiche détaillée
       --------------------------------------------------------------- */

    const gabaritFiche = (data) => {
        const a = data.animal;
        const infos = [
            ["Espèce", a.espece_label],
            ["Sexe", a.sexe_label],
            ["Âge", a.age_lisible],
            ["Statut", a.statut_label],
            ["Vacciné", a.vaccine ? "Oui" : "Non"],
            ["Stérilisé", a.sterilise ? "Oui" : "Non"],
            ["Identifié", a.identifie ? "Oui" : "Non"],
        ].map(([cle, val]) => `<div><dt>${cle}</dt><dd>${esc(val)}</dd></div>`).join("");

        let action = "";
        if (data.peut_reserver) {
            action = `
                <form id="form-reservation" class="form-reservation">
                    <input type="hidden" name="id_animal" value="${a.id_animal}">
                    <label for="resa-message">Votre message au refuge (facultatif)</label>
                    <textarea id="resa-message" name="message" rows="3"
                              placeholder="Présentez-vous en quelques mots..."></textarea>
                    <button type="submit" class="btn-submit">Pré-réserver ${esc(a.nom)} (gratuit)</button>
                    <p class="resa-aide">La pré-réservation est gratuite et sans engagement. Le refuge vous recontactera.</p>
                </form>`;
        } else if (data.deja_demande) {
            action = `<p class="message-info">Vous avez déjà une demande en cours pour ${esc(a.nom)}.</p>`;
        } else if (!data.connecte && a.statut === "disponible") {
            action = `<p class="message-info">
                        <a href="connexion.php">Connectez-vous</a> pour pré-réserver ${esc(a.nom)}.
                      </p>`;
        } else {
            action = `<p class="message-info">${esc(a.nom)} n'est pas disponible à l'adoption pour le moment.</p>`;
        }

        return `
            <h2 id="fiche-titre">${esc(a.nom)}</h2>
            <div class="fiche-grille">
                <img src="images/animaux/${esc(a.photo)}" alt="${esc(a.alt)}">
                <div>
                    <dl class="fiche-infos">${infos}</dl>
                    ${a.description ? `<p class="fiche-description">${esc(a.description)}</p>` : ""}
                </div>
            </div>
            ${action}
            <div id="resa-retour" role="status" aria-live="polite"></div>`;
    };

    const ouvrirFiche = async (id, declencheur) => {
        dernierDeclencheur = declencheur;
        corps.innerHTML = '<p class="chargement">Chargement de la fiche…</p>';
        modale.hidden = false;
        document.body.classList.add("modale-ouverte");

        try {
            const reponse = await fetch(`api/get_animal.php?id=${encodeURIComponent(id)}`);
            const data = await reponse.json();

            if (!data.succes) throw new Error(data.erreur);

            corps.innerHTML = gabaritFiche(data);
            fermer.focus();

            const formResa = document.getElementById("form-reservation");
            if (formResa) formResa.addEventListener("submit", envoyerReservation);

        } catch (e) {
            corps.innerHTML = '<p class="message-erreur">Impossible de charger cette fiche.</p>';
        }
    };

    const fermerFiche = () => {
        modale.hidden = true;
        document.body.classList.remove("modale-ouverte");
        if (dernierDeclencheur) dernierDeclencheur.focus(); // on rend le focus au bouton d'origine
    };

    // Délégation : fonctionne aussi sur les cartes régénérées en AJAX
    grille.addEventListener("click", (e) => {
        const bouton = e.target.closest(".btn-detail");
        if (bouton) ouvrirFiche(bouton.dataset.id, bouton);
    });

    fermer.addEventListener("click", fermerFiche);
    modale.addEventListener("click", (e) => { if (e.target === modale) fermerFiche(); });
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && !modale.hidden) fermerFiche();
    });

    /* ---------------------------------------------------------------
       3. Pré-réservation
       --------------------------------------------------------------- */

    async function envoyerReservation(e) {
        e.preventDefault();
        const formResa = e.currentTarget;
        const bouton = formResa.querySelector("button[type=submit]");
        const retour = document.getElementById("resa-retour");

        bouton.disabled = true;
        bouton.textContent = "Envoi en cours…";

        try {
            const reponse = await fetch("api/reserver.php", {
                method: "POST",
                body: new FormData(formResa),
            });
            const data = await reponse.json();

            if (data.succes) {
                formResa.remove();
                retour.innerHTML = `<p class="message-succes">${esc(data.message)}</p>`;
                chargerAnimaux(); // l'animal passe en « réservé » dans la liste
            } else {
                retour.innerHTML = `<p class="message-erreur">${esc(data.erreur)}</p>`;
                bouton.disabled = false;
                bouton.textContent = "Réessayer";
            }
        } catch (err) {
            retour.innerHTML = '<p class="message-erreur">L\'envoi a échoué. Merci de réessayer.</p>';
            bouton.disabled = false;
            bouton.textContent = "Réessayer";
        }
    }
});
