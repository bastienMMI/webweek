/**
 * Formulaire de contact — envoi en AJAX vers api/contact.php.
 * Sans JavaScript, le formulaire se soumet normalement en POST.
 */
document.addEventListener("DOMContentLoaded", () => {

    const form   = document.getElementById("monFormulaireContact");
    const retour = document.getElementById("contact-retour");

    if (!form || !retour) return;

    const esc = (valeur) => {
        const d = document.createElement("div");
        d.textContent = valeur ?? "";
        return d.innerHTML;
    };

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const bouton = form.querySelector("button[type=submit]");
        const libelle = bouton.textContent;

        bouton.disabled = true;
        bouton.textContent = "Envoi en cours…";
        retour.innerHTML = "";

        try {
            const reponse = await fetch("api/contact.php", {
                method: "POST",
                body: new FormData(form),
            });
            const data = await reponse.json();

            if (data.succes) {
                form.reset();
                retour.innerHTML = `<p class="message-succes">${esc(data.message)}</p>`;
            } else if (data.erreurs) {
                const liste = data.erreurs.map((err) => `<li>${esc(err)}</li>`).join("");
                retour.innerHTML = `<div class="message-erreur"><ul>${liste}</ul></div>`;
            } else {
                retour.innerHTML = `<p class="message-erreur">${esc(data.erreur)}</p>`;
            }
        } catch (err) {
            retour.innerHTML = '<p class="message-erreur">L\'envoi a échoué. Merci de réessayer.</p>';
        } finally {
            bouton.disabled = false;
            bouton.textContent = libelle;
        }
    });
});
