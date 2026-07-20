# Historique de commits à rejouer

Ces commandes reconstituent un historique lisible, un commit par ticket Jira.
Exécutez-les depuis la racine du dépôt, dans l'ordre.

> Remplacez `SCRUM` par la clé réelle de vos tickets si elle diffère.

```bash
git init
git branch -M main

# --- Socle ---
git add .gitignore README.md config/configuration.exemple.php
git commit -m "chore : initialiser le depot (README, gitignore, config d exemple)"

git add sql/migration_cible.sql
git commit -m "feat(SCRUM-2) : migrer la base vers le schema cible (5 tables)"

git add -A
git commit -m "feat(SCRUM-1) : retirer le module e-commerce (boutique, panier, commandes)"

# --- Corrections ---
git add classes/don.php scripts/traiter_don.php don.php
git commit -m "fix(SCRUM-3) : fiabiliser le don (statut ENUM valide, validation du montant)"

git add scripts/ajouter_animal.php scripts/update_animal.php ajouter_animal.php modifier_animaux.php
git commit -m "fix(SCRUM-4) : aligner les formulaires sur les ENUM de la base"

git add scripts/update_animal.php
git commit -m "fix(SCRUM-5) : enregistrer tous les champs a la mise a jour d un animal"

# --- Métier et API ---
git add classes/animal.php api/get_animaux.php api/get_animal.php
git commit -m "refactor(SCRUM-7) : centraliser la gestion des animaux en POO et exposer une API JSON"

git add ajouter_animal.php modifier_animaux.php admin.php scripts/supprimer_animal.php
git commit -m "feat(SCRUM-6) : CRUD animal complet avec suivi sanitaire et age calcule"

git add classes/contact.php api/contact.php js/contact.js index.php
git commit -m "feat(SCRUM-8) : rendre le formulaire de contact fonctionnel (API + back-office)"

# --- Pré-réservation ---
git add classes/reservation.php api/reserver.php
git commit -m "feat(SCRUM-9) : pre-reservation gratuite d un animal disponible"

git add admin.php scripts/traiter_reservation.php
git commit -m "feat(SCRUM-10) : gerer les reservations cote administrateur"

git add mon-compte.php scripts/annuler_reservation.php
git commit -m "feat(SCRUM-11) : suivre ses pre-reservations dans l espace personnel"

# --- Parcours visiteur ---
git add adopter.php js/filtre_ajax.js
git commit -m "feat(SCRUM-12) : filtrer les animaux en AJAX via l API interne"

git add js/filtre_ajax.js css/style.css
git commit -m "feat(SCRUM-13) : afficher la fiche animal detaillee en AJAX"

git add css/style.css
git commit -m "feat(SCRUM-14) : rendre l ensemble du site responsive"

# --- Conformité ---
git add -A
git commit -m "feat(SCRUM-15) : accessibilite (alt, titres, ARIA, focus, navigation clavier)"

git add mentions-legales.php confidentialite.php accessibilite.php "header et footer/footer.php"
git commit -m "feat(SCRUM-16) : publier les mentions legales, le RGPD et l accessibilite"

git add "header et footer/head.php"
git commit -m "feat(SCRUM-17) : titre et meta-description propres a chaque page"

git add images/
git commit -m "feat(SCRUM-18) : eco-conception (images compressees, chargement paresseux)"

# --- Publication ---
git remote add origin https://github.com/<votre-compte>/<votre-depot>.git
git push -u origin main
```
