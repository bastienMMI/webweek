# SPA de la Haute-Loire — Refonte technique 

Site du refuge de la SPA de la Haute-Loire (Polignac), refondu dans le
cadre de la SAE301 : suppression de la vente en ligne, passage en POO
avec API internes, AJAX, responsive, accessibilité, et refonte graphique.

## Fonctionnalités

- **Adoption** : catalogue filtrable en AJAX (espèce, sexe, âge, suivi
  sanitaire), fiche animal détaillée en modale, **pré-réservation gratuite**.
- **Boutique solidaire** : la vente en ligne étant interdite par le sujet,
  la boutique fonctionne en **réservation gratuite** (retrait et paiement
  au refuge), sur le même principe que l'adoption.
- **Dons** libres pour les utilisateurs connectés.
- **Espace personnel** : suivi de ses pré-réservations (animaux et boutique)
  et de ses dons.
- **Back-office admin** : gestion des animaux, des produits, des
  réservations (animaux + boutique), des messages de contact et des dons.
- **Formulaire de contact** fonctionnel (enregistré en base, visible en admin).

## Architecture

- **PHP 8 + MySQL/MariaDB**, PDO avec requêtes préparées.
- **POO** : dossier `classes/` (Animal, Reservation, Contact, Produit,
  ReservationProduit).
- **API internes JSON** : dossier `api/` (get_animaux, get_animal, reserver,
  contact, get_produits, reserver_produit).
- **AJAX** : filtrage, fiche animal, pré-réservation, réservation boutique
  (dossier `js/`). Le site reste utilisable **sans JavaScript** (repli en
  formulaires classiques).

## Installation locale (XAMPP)

1. Copier le dossier dans `C:\xampp\htdocs\spa-haute-loire`.
2. Dupliquer `config/configuration.exemple.php` en
   `config/configuration.php` et renseigner vos identifiants (valeurs
   XAMPP par défaut déjà en place).
3. Démarrer **Apache** et **MySQL** dans XAMPP.
4. Dans phpMyAdmin, créer une base `spa43` (interclassement `utf8mb4`),
   puis importer dans l'ordre :
   - `sql/spa43.sql`
   - `sql/spa43_2.sql`
   - `sql/spa43_3.sql`
5. Ouvrir `http://localhost/spa-haute-loire/index.php`.

Pour vous donner les droits admin :
UPDATE utilisateur SET role='admin' WHERE email='votre@email.fr';


## Base de données (5 tables métier + boutique)

`utilisateur`, `animal`, `reservation`, `don`, `message_contact`,
`produit`, `reservation_produit`.
