# Refonte technique du site de la SPA de la Haute-Loire

Projet de rattrapage **SAE301 — Développer des parcours utilisateur au sein d'un SI**
IUT du Puy-en-Velay — Université Clermont Auvergne — juillet 2026

Ce dépôt contient la refonte technique du site de la SPA de la Haute-Loire, réalisée à
partir d'une première refonte existante. Le travail a porté sur la mise en conformité
juridique et l'accessibilité, le retrait de la vente en ligne au profit d'un système de
pré-réservation gratuite, l'enrichissement des informations sur les animaux, et la
correction de plusieurs dysfonctionnements.

## Sommaire

- [Stack technique](#stack-technique)
- [Installation](#installation)
- [Architecture](#architecture)
- [Base de données](#base-de-données)
- [API internes](#api-internes)
- [Comptes de test](#comptes-de-test)
- [Travaux réalisés](#travaux-réalisés)
- [Suivi de projet](#suivi-de-projet)

## Stack technique

| Élément | Choix |
|---|---|
| Back-end | PHP 8 orienté objet, sans framework ni CMS |
| Base de données | MySQL / MariaDB, accès via PDO en requêtes préparées |
| Front-end | HTML5 sémantique, CSS3 (responsive, 24 media queries), JavaScript natif |
| Échanges asynchrones | `fetch()` vers des API internes renvoyant du JSON |
| Cartographie | Leaflet + OpenStreetMap |
| Versionnement | Git |
| Gestion de projet | Jira (méthode AGILE, 2 sprints) |

Aucune dépendance à installer : le projet ne nécessite ni Composer, ni npm.

## Installation

### Prérequis

- PHP 8.0 ou supérieur, avec les extensions `pdo_mysql` et `fileinfo`
- MySQL 5.7+ ou MariaDB 10+
- Un serveur web (Apache, Nginx) ou le serveur intégré de PHP

### Mise en place

1. **Récupérer les sources**

   ```bash
   git clone <url-du-depot> spa-haute-loire
   cd spa-haute-loire
   ```

2. **Créer la base de données**

   ```bash
   mysql -u root -p -e "CREATE DATABASE spa43 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
   mysql -u root -p spa43 < sql/spa43.sql              # schéma initial
   mysql -u root -p spa43 < sql/migration_cible.sql    # migration vers le schéma cible
   ```

   > **Attention** : `migration_cible.sql` supprime les tables `boutique`, `produit`,
   > `commande` et `commande_produit`. Sauvegardez votre base avant de l'exécuter.

3. **Configurer la connexion**

   Copiez le fichier d'exemple et renseignez vos identifiants :

   ```bash
   cp config/configuration.exemple.php config/configuration.php
   ```

   Ce fichier n'est pas versionné : il contient les identifiants de la base.

4. **Vérifier les droits sur les photos**

   Le dossier `images/animaux/` doit être accessible en écriture par le serveur web
   (dépôt des photos depuis le back-office).

5. **Lancer le site**

   ```bash
   php -S localhost:8080
   ```

## Architecture

```
.
├── api/                    API internes (JSON)
│   ├── get_animaux.php     liste filtrable des animaux
│   ├── get_animal.php      détail d'un animal
│   ├── contact.php         envoi d'un message de contact
│   └── reserver.php        demande de pré-réservation
├── classes/                couche métier (POO)
│   ├── animal.php          AnimalManager
│   ├── reservation.php     ReservationManager
│   ├── contact.php         MessageContactManager
│   └── don.php             DonManager
├── config/                 configuration de la base (non versionnée)
├── css/                    feuille de style
├── header et footer/       éléments communs (head, header, footer)
├── images/                 médias, dont images/animaux/
├── js/                     scripts client
│   ├── filtre_ajax.js      filtrage, fiche animal et pré-réservation
│   ├── contact.js          envoi du formulaire de contact
│   └── ...                 carrousel, carte, navigation
├── scripts/                traitements serveur (POST/GET)
├── sql/                    schéma initial et migration
└── *.php                   pages du site
```

Le principe retenu : **les pages ne contiennent pas de SQL**. Toute requête passe par une
classe de `classes/`, elle-même appelée soit par une page, soit par une API de `api/`.

## Base de données

Le modèle compte cinq tables (voir le schéma entité-association joint au dossier) :

| Table | Rôle |
|---|---|
| `utilisateur` | comptes (mots de passe hachés), rôles `client` / `admin` |
| `animal` | pensionnaires, avec `vaccine`, `sterilise`, `identifie`, `date_naissance` |
| `reservation` | pré-réservations gratuites (utilisateur × animal) |
| `don` | dons enregistrés |
| `message_contact` | messages du formulaire de contact |

Les quatre tables liées à l'e-commerce (`boutique`, `produit`, `commande`,
`commande_produit`) ont été supprimées : la vente en ligne est interdite par le sujet.

L'âge des animaux n'est plus stocké mais **calculé** à partir de `date_naissance` : il
reste ainsi juste dans le temps, sans intervention manuelle.

## API internes

Toutes les réponses sont au format JSON et comportent un booléen `succes`.

| Méthode | Route | Paramètres | Réponse |
|---|---|---|---|
| `GET` | `api/get_animaux.php` | `espece`, `sexe`, `age_max`, `vaccine`, `sterilise`, `identifie` | `{ succes, total, animaux[] }` |
| `GET` | `api/get_animal.php` | `id` | `{ succes, animal, connecte, peut_reserver }` |
| `POST` | `api/contact.php` | `nom`, `email`, `telephone`, `objet`, `message` | `{ succes, message }` ou `{ succes, erreurs[] }` |
| `POST` | `api/reserver.php` | `id_animal`, `message` | `{ succes, message }` |

Exemple :

```bash
curl "http://localhost:8080/api/get_animaux.php?espece=chien&vaccine=1"
```

Les codes HTTP sont respectés : `401` si non connecté, `404` si l'animal n'existe pas,
`409` en cas de demande déjà en cours, `422` si les données sont invalides.

## Comptes de test

À créer via `inscription.php`, puis passer le rôle à `admin` en base pour tester le
back-office :

```sql
UPDATE utilisateur SET role = 'admin' WHERE email = 'votre@email.fr';
```

## Travaux réalisés

### Corrections de l'existant

- Statut de don invalide (`Validé` absent de l'énumération) → corrigé, et montant désormais validé.
- Formulaire de contact purement décoratif (aucun `name`, aucun traitement) → rendu fonctionnel.
- Valeurs des formulaires incohérentes avec les énumérations de la base (`Chien` vs `chien`, statut `indisponible` inexistant) → alignées.
- Mise à jour d'un animal n'enregistrant que le nom et le statut → tous les champs sont désormais pris en compte.
- Dépôt de photo sans contrôle du type réel → vérification du type MIME et de la taille (faille d'upload).
- Lien « Mentions légales » pointant vers une page inexistante → pages légales créées.
- Connexion PDO en `utf8` alors que les tables sont en `utf8mb4` → alignée.

### Nouvelles fonctionnalités

- Pré-réservation gratuite d'un animal, avec gestion des statuts côté administrateur.
- Fiches animaux enrichies (vacciné, stérilisé, identifié, âge calculé).
- Filtrage AJAX de la liste des animaux, avec fonctionnement préservé sans JavaScript.
- Fiche animal détaillée chargée en AJAX, utilisable au clavier.
- Back-office étendu : pensionnaires, réservations, messages, dons et indicateurs.

### Conformité

- Mentions légales, politique de confidentialité (RGPD) et déclaration d'accessibilité.
- Textes alternatifs descriptifs, hiérarchie des titres, ARIA, focus visible, navigation clavier.
- Titre et méta-description propres à chaque page.
- Images compressées et redimensionnées (−87 % sur le carrousel), chargement paresseux généralisé.

## Suivi de projet

Le projet a été mené en méthode AGILE sur deux sprints, suivis sous Jira :

| Sprint | Période | Objectif |
|---|---|---|
| Sprint 1 | 15–16 juillet 2026 | Assainir le socle : base de données, retrait de l'e-commerce, corrections, back-office |
| Sprint 2 | 17–19 juillet 2026 | Parcours visiteur, pré-réservation, conformité, mise en ligne |

Les messages de commit suivent la convention `type(TICKET) : description`, où le type vaut
`feat`, `fix`, `refactor`, `docs` ou `chore`. L'historique Git permet ainsi de retracer
l'avancement ticket par ticket.
