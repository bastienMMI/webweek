<?php
/**
 * Réservation gratuite d'un produit de la boutique.
 * Même principe que la pré-réservation d'un animal : l'utilisateur
 * met de côté, le refuge prépare, le retrait et le paiement se font
 * sur place. Aucun paiement en ligne.
 */
class ReservationProduitManager {

    private $db;

    /** Statuts autorisés, alignés sur l'ENUM de `reservation_produit`. */
    const STATUTS = ['en_attente', 'prete', 'retiree', 'annulee'];

    /** Quantité maximale par réservation, pour rester raisonnable. */
    const QUANTITE_MAX = 10;

    public function __construct($connection) {
        $this->db = $connection;
    }

    /** Libellé lisible d'un statut, pour l'affichage. */
    public static function libelleStatut($statut) {
        $libelles = [
            'en_attente' => 'En préparation',
            'prete'      => 'Prête au refuge',
            'retiree'    => 'Retirée',
            'annulee'    => 'Annulée',
        ];
        return $libelles[$statut] ?? $statut;
    }

    /**
     * Crée une réservation et décrémente le stock, dans une transaction :
     * soit tout passe, soit rien.
     * @return int|string identifiant créé, ou un code d'erreur :
     *         'stock' (stock insuffisant) / 'introuvable'
     */
    public function creer($id_utilisateur, $id_produit, $quantite) {
        $quantite = max(1, min((int)$quantite, self::QUANTITE_MAX));

        $this->db->beginTransaction();
        try {
            // Le stock est revérifié au moment de l'écriture (évite deux réservations simultanées)
            $stmt = $this->db->prepare(
                "SELECT stock FROM produit WHERE id_produit = ? AND actif = 1 FOR UPDATE"
            );
            $stmt->execute([$id_produit]);
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$produit) {
                $this->db->rollBack();
                return 'introuvable';
            }
            if ((int)$produit['stock'] < $quantite) {
                $this->db->rollBack();
                return 'stock';
            }

            $stmt = $this->db->prepare(
                "INSERT INTO reservation_produit (id_utilisateur, id_produit, quantite)
                 VALUES (?, ?, ?)"
            );
            $stmt->execute([$id_utilisateur, $id_produit, $quantite]);
            $id_reservation = (int)$this->db->lastInsertId();

            $stmt = $this->db->prepare(
                "UPDATE produit SET stock = stock - ? WHERE id_produit = ?"
            );
            $stmt->execute([$quantite, $id_produit]);

            $this->db->commit();
            return $id_reservation;

        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /** Réservations d'un utilisateur, avec les informations du produit. */
    public function getByUtilisateur($id_utilisateur) {
        $stmt = $this->db->prepare(
            "SELECT rp.id_reservation_produit, rp.quantite, rp.date_reservation, rp.statut,
                    p.id_produit, p.nom_produit, p.prix, p.photo
             FROM reservation_produit rp
             JOIN produit p ON p.id_produit = rp.id_produit
             WHERE rp.id_utilisateur = ?
             ORDER BY rp.date_reservation DESC"
        );
        $stmt->execute([$id_utilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Toutes les réservations, avec produit et demandeur (back-office). */
    public function getAll() {
        $stmt = $this->db->query(
            "SELECT rp.id_reservation_produit, rp.quantite, rp.date_reservation, rp.statut,
                    p.id_produit, p.nom_produit, p.prix, p.photo,
                    u.id_utilisateur, u.nom AS user_nom, u.prenom AS user_prenom,
                    u.email, u.telephone
             FROM reservation_produit rp
             JOIN produit p     ON p.id_produit = rp.id_produit
             JOIN utilisateur u ON u.id_utilisateur = rp.id_utilisateur
             ORDER BY FIELD(rp.statut, 'en_attente', 'prete', 'retiree', 'annulee'),
                      rp.date_reservation DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Une réservation par son identifiant. */
    public function getById($id_reservation) {
        $stmt = $this->db->prepare(
            "SELECT rp.*, p.nom_produit
             FROM reservation_produit rp
             JOIN produit p ON p.id_produit = rp.id_produit
             WHERE rp.id_reservation_produit = ?"
        );
        $stmt->execute([$id_reservation]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Nombre de réservations à préparer (chiffre clé du back-office). */
    public function compterEnAttente() {
        return (int)$this->db
            ->query("SELECT COUNT(*) FROM reservation_produit WHERE statut = 'en_attente'")
            ->fetchColumn();
    }

    /**
     * Change le statut d'une réservation.
     * Passage en « annulée » : le stock est restitué au produit
     * (dans une transaction, comme à la création).
     */
    public function changerStatut($id_reservation, $statut) {
        if (!in_array($statut, self::STATUTS, true)) {
            return false;
        }

        $reservation = $this->getById($id_reservation);
        if (!$reservation || $reservation['statut'] === $statut) {
            return false;
        }

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                "UPDATE reservation_produit SET statut = ? WHERE id_reservation_produit = ?"
            );
            $stmt->execute([$statut, $id_reservation]);

            // Une réservation annulée rend sa quantité au stock
            if ($statut === 'annulee' && $reservation['statut'] !== 'annulee') {
                $stmt = $this->db->prepare(
                    "UPDATE produit SET stock = stock + ? WHERE id_produit = ?"
                );
                $stmt->execute([(int)$reservation['quantite'], $reservation['id_produit']]);
            }

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Annulation par l'utilisateur : n'agit que sur sa propre réservation
     * et seulement tant qu'elle n'a pas été retirée.
     */
    public function annulerParUtilisateur($id_reservation, $id_utilisateur) {
        $stmt = $this->db->prepare(
            "SELECT id_reservation_produit FROM reservation_produit
             WHERE id_reservation_produit = ? AND id_utilisateur = ?
               AND statut IN ('en_attente', 'prete')"
        );
        $stmt->execute([$id_reservation, $id_utilisateur]);

        if (!$stmt->fetch()) {
            return false;
        }
        return $this->changerStatut($id_reservation, 'annulee');
    }
}
