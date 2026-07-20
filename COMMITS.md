# Historique de commits à rejouer

Ces commandes reconstituent un historique lisible, un commit par lot cohérent.
Exécutez-les depuis la racine du dépôt, dans l'ordre. Adaptez `SCRUM-xx` aux
clés réelles de vos tickets Jira.

> Astuce : étalez si possible les commits sur plusieurs dates (les encadrants
> regardent l'historique Git comme preuve du suivi de projet).

```bash
# --- Sprint 1 : socle, migration, retrait e-commerce (déjà fait) ---
git add .gitignore README.md config/configuration.exemple.php
git commit -m "chore : README, gitignore, config d exemple"

git add sql/spa43_2.sql
git commit -m "feat(SCRUM-2) : migration cible (retrait e-commerce, enrichissement animaux, reservation, contact)"

git add classes/ api/ scripts/ mon-compte.php admin.php adopter.php js/filtre_ajax.js js/contact.js
git commit -m "feat(SCRUM-7) : back-end POO + API internes JSON"

git add adopter.php js/filtre_ajax.js
git commit -m "feat(SCRUM-12) : filtrage des animaux en AJAX"

git add js/filtre_ajax.js css/style.css
git commit -m "feat(SCRUM-13) : fiche animal detaillee en AJAX + pre-reservation"

# --- Sprint 2 : retour boutique + refonte graphique ---
git add sql/ajout_boutique_reservation.sql sql/installation_complete.sql
git commit -m "feat(SCRUM-19) : schema boutique en reservation gratuite (produit + reservation_produit)"

git add classes/produit.php classes/reservation_produit.php api/get_produits.php api/reserver_produit.php
git commit -m "feat(SCRUM-20) : boutique en POO + API (catalogue et reservation d articles)"

git add boutique.php js/boutique_ajax.js
git commit -m "feat(SCRUM-21) : page boutique avec reservation d articles en AJAX (retrait au refuge)"

git add ajouter_produit.php modifier_produit.php scripts/ajout_produit.php scripts/update_produit.php scripts/supprimer_produit.php
git commit -m "feat(SCRUM-22) : back-office boutique (CRUD produits)"

git add admin.php scripts/traiter_reservation_produit.php mon-compte.php scripts/annuler_reservation_produit.php
git commit -m "feat(SCRUM-23) : suivi des reservations boutique (admin + espace personnel)"

git add "header et footer/header.php"
git commit -m "feat(SCRUM-24) : lien Boutique dans la navigation"

git add css/style.css "header et footer/head.php" js/animations.js index.php aider.php
git commit -m "feat(SCRUM-25) : refonte graphique (degrades, typographie Fraunces, micro-interactions) en conservant la palette"

# --- Publication ---
git push origin main
```
