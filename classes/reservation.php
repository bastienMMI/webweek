<?php
/**
 * Gestion des pré-réservations gratuites d'un animal.
 * Remplace l'ancien système de panier / commande.
 */
class ReservationManager {

    private $db;

    /** Statuts autorisés, alignés sur l'ENUM de la table `reservation`. */
    const STATUTS = ['en_attente', 'confirmee', 'annulee', 'concretisee'];

    public function __construct($connection) {
        $this->db = $connection;
    }

    /** Libellé lisible d'un statut, pour l'affichage. */
    public static function libelleStatut($statut) {
        $libelles = [
            'en_attente'  => 'En attente',
            'confirmee'   => 'Confirmée',
            'annulee'     => 'Annulée',
            'concretisee' => 'Adoption réalisée',
        ];
        return $libelles[$statut] ?? $statut;
    }

    /**
     * Crée une pré-réservation et passe l'animal au statut « reserve ».
     * Les deux écritures sont faites dans une transaction : soit tout passe, soit rien.
     * @return int|false l'identifiant créé, ou false si l'animal n'est plus disponible
     */
    public function creer($id_utilisateur, $id_animal, $message = null) {
        $this->db->beginTransaction();
        try {
            // On revérifie la disponibilité au moment de l'écriture (évite deux réservations simultanées)
            $stmt = $this->db->prepare("SELECT statut FROM animal WHERE id_animal = ? FOR UPDATE");
            $stmt->execute([$id_animal]);
            $animal = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$animal || $animal['statut'] !== 'disponible') {
                $this->db->rollBack();
                return false;
            }

            $stmt = $this->db->prepare(
                "INSERT INTO reservation (id_utilisateur, id_animal, statut, message)
                 VALUES (?, ?, 'en_attente', ?)"
            );
            $stmt->execute([$id_utilisateur, $id_animal, $message]);
            $id_reservation = $this->db->lastInsertId();

            $stmt = $this->db->prepare("UPDATE animal SET statut = 'reserve' WHERE id_animal = ?");
            $stmt->execute([$id_animal]);

            $this->db->commit();
            return (int)$id_reservation;

        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /** Réservations d'un utilisateur, avec les informations de l'animal. */
    public function getByUtilisateur($id_utilisateur) {
        $stmt = $this->db->prepare(
            "SELECT r.id_reservation, r.date_reservation, r.statut, r.message,
                    a.id_animal, a.nom AS animal_nom, a.espece, a.photo
             FROM reservation r
             JOIN animal a ON a.id_animal = r.id_animal
             WHERE r.id_utilisateur = ?
             ORDER BY r.date_reservation DESC"
        );
        $stmt->execute([$id_utilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Toutes les réservations, avec animal et demandeur (back-office). */
    public function getAll() {
        $stmt = $this->db->query(
            "SELECT r.id_reservation, r.date_reservation, r.statut, r.message,
                    a.id_animal, a.nom AS animal_nom, a.espece, a.photo,
                    u.id_utilisateur, u.nom AS user_nom, u.prenom AS user_prenom,
                    u.email, u.telephone
             FROM reservation r
             JOIN animal a      ON a.id_animal = r.id_animal
             JOIN utilisateur u ON u.id_utilisateur = r.id_utilisateur
             ORDER BY FIELD(r.statut, 'en_attente', 'confirmee', 'concretisee', 'annulee'),
                      r.date_reservation DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Une réservation par son identifiant. */
    public function getById($id_reservation) {
        $stmt = $this->db->prepare(
            "SELECT r.*, a.nom AS animal_nom
             FROM reservation r
             JOIN animal a ON a.id_animal = r.id_animal
             WHERE r.id_reservation = ?"
        );
        $stmt->execute([$id_reservation]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Change le statut d'une réservation et répercute sur l'animal :
     * annulée  -> l'animal redevient disponible
     * concrétisée -> l'animal est adopté
     * confirmée -> l'animal reste réservé
     */
    public function changerStatut($id_reservation, $statut) {
        if (!in_array($statut, self::STATUTS, true)) {
            return false;
        }

        $reservation = $this->getById($id_reservation);
        if (!$reservation) {
            return false;
        }

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("UPDATE reservation SET statut = ? WHERE id_reservation = ?");
            $stmt->execute([$statut, $id_reservation]);

            $statut_animal = null;
            if ($statut === 'annulee') {
                $statut_animal = 'disponible';
            } elseif ($statut === 'concretisee') {
                $statut_animal = 'adopte';
            } elseif ($statut === 'confirmee') {
                $statut_animal = 'reserve';
            }

            if ($statut_animal !== null) {
                $stmt = $this->db->prepare("UPDATE animal SET statut = ? WHERE id_animal = ?");
                $stmt->execute([$statut_animal, $reservation['id_animal']]);
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
     * et seulement si elle est encore en attente.
     */
    public function annulerParUtilisateur($id_reservation, $id_utilisateur) {
        $stmt = $this->db->prepare(
            "SELECT id_reservation FROM reservation
             WHERE id_reservation = ? AND id_utilisateur = ? AND statut = 'en_attente'"
        );
        $stmt->execute([$id_reservation, $id_utilisateur]);

        if (!$stmt->fetch()) {
            return false;
        }
        return $this->changerStatut($id_reservation, 'annulee');
    }

    /** Vrai si l'utilisateur a déjà une demande en cours sur cet animal. */
    public function existeDemandeEnCours($id_utilisateur, $id_animal) {
        $stmt = $this->db->prepare(
            "SELECT 1 FROM reservation
             WHERE id_utilisateur = ? AND id_animal = ?
               AND statut IN ('en_attente', 'confirmee')"
        );
        $stmt->execute([$id_utilisateur, $id_animal]);
        return (bool)$stmt->fetch();
    }
}
