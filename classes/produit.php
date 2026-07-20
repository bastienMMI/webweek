<?php
/**
 * Gestion du catalogue de la boutique du refuge.
 * La vente en ligne étant interdite par le sujet, les produits sont
 * proposés en réservation gratuite : retrait et paiement au refuge.
 */
class ProduitManager {

    private $db;

    public function __construct($connection) {
        $this->db = $connection;
    }

    /** Produits actifs, pour la page boutique et l'API. */
    public function getActifs() {
        $stmt = $this->db->query(
            "SELECT id_produit, nom_produit, description, prix, stock, photo
             FROM produit
             WHERE actif = 1
             ORDER BY nom_produit ASC"
        );
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'enrichir'], $produits);
    }

    /** Tous les produits, y compris désactivés (back-office). */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM produit ORDER BY actif DESC, nom_produit ASC");
        return array_map([$this, 'enrichir'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /** Un produit par son identifiant. */
    public function getById($id_produit) {
        $stmt = $this->db->prepare("SELECT * FROM produit WHERE id_produit = ?");
        $stmt->execute([$id_produit]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);
        return $produit ? $this->enrichir($produit) : false;
    }

    /** Ajoute un produit et renvoie son identifiant. */
    public function ajouter($nom, $description, $prix, $stock, $photo = null) {
        $stmt = $this->db->prepare(
            "INSERT INTO produit (nom_produit, description, prix, stock, photo)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$nom, $description, $prix, $stock, $photo]);
        return (int)$this->db->lastInsertId();
    }

    /** Met à jour un produit ; la photo n'est remplacée que si fournie. */
    public function modifier($id_produit, $nom, $description, $prix, $stock, $photo = null) {
        if ($photo !== null) {
            $stmt = $this->db->prepare(
                "UPDATE produit
                 SET nom_produit = ?, description = ?, prix = ?, stock = ?, photo = ?
                 WHERE id_produit = ?"
            );
            return $stmt->execute([$nom, $description, $prix, $stock, $photo, $id_produit]);
        }
        $stmt = $this->db->prepare(
            "UPDATE produit
             SET nom_produit = ?, description = ?, prix = ?, stock = ?
             WHERE id_produit = ?"
        );
        return $stmt->execute([$nom, $description, $prix, $stock, $id_produit]);
    }

    /**
     * Suppression « douce » : le produit est désactivé, pas effacé,
     * pour ne pas casser l'historique des réservations.
     */
    public function desactiver($id_produit) {
        $stmt = $this->db->prepare("UPDATE produit SET actif = 0 WHERE id_produit = ?");
        return $stmt->execute([$id_produit]);
    }

    public function reactiver($id_produit) {
        $stmt = $this->db->prepare("UPDATE produit SET actif = 1 WHERE id_produit = ?");
        return $stmt->execute([$id_produit]);
    }

    /** Champs calculés utiles à l'affichage. */
    private function enrichir(array $p) {
        $p['prix_lisible'] = number_format((float)$p['prix'], 2, ',', ' ') . ' €';
        $p['disponible']   = ((int)$p['stock']) > 0;
        return $p;
    }
}
