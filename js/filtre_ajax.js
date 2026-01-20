document.addEventListener("DOMContentLoaded", () => {
    const filterForm = document.getElementById("filter-form");
    const animalGrid = document.getElementById("animal-grid");

    if (filterForm && animalGrid) {
        // Fonction qui va chercher les animaux
        const chargerAnimaux = () => {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData).toString();

            fetch(`api/get_animaux.php?${params}`)
                .then(response => response.json())
                .then(data => {
                    animalGrid.innerHTML = ""; // On vide la grille actuelle

                    if (data.length === 0) {
                        animalGrid.innerHTML = "<p>Désolé, aucun animal ne correspond à vos critères.</p>";
                        return;
                    }

                    data.forEach(animal => {
                        const cardClass = animal.statut === 'disponible' ? 'animal-card' : 'animal-card-indisponible';
                        const statusLabel = animal.statut === 'disponible' ? '' : ' (Indisponible)';
                        
                        // On reconstruit le HTML exact de tes cartes
                        const card = `
                            <article class="${cardClass}">
                                <img src="images/animaux/${animal.photo}" alt="Photo de ${animal.nom}">
                                <h3>${animal.nom}${statusLabel}</h3>
                                <p>
                                    Âge : ${animal.age} ans<br/>
                                    Sexe : ${animal.sexe}<br/>
                                    Description : ${animal.description}
                                </p>
                            </article>
                        `;
                        animalGrid.innerHTML += card;
                    });
                })
                .catch(error => console.error("Erreur AJAX :", error));
        };

        // On écoute les changements sur le formulaire
        filterForm.addEventListener("change", chargerAnimaux);
        
        // On empêche le bouton "Filtrer" de recharger la page
        filterForm.addEventListener("submit", (e) => {
            e.preventDefault();
            chargerAnimaux();
        });
    }
});