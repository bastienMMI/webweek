<?php
class DonManager {
    private $db;

    public function __construct($connection) {
        $this->db = $connection;
    }

    //enregistrement  d'un nouveau don
    public function enregistrerDon($id_user, $montant) {
        try {
            // 1. On récupère d'abord les infos de l'utilisateur pour l'historique
            $stmt_user = $this->db->prepare("SELECT nom, prenom, email, telephone FROM utilisateur WHERE id_utilisateur = ?");
            $stmt_user->execute([$id_user]);
            $u = $stmt_user->fetch(PDO::FETCH_ASSOC);

            if (!$u) return false;

            // 2. On insère le don
            $sql = "INSERT INTO don (id_utilisateur, nom, prenom, email, telephone, montant, date_don, statut) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), 'Validé')";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $id_user, 
                $u['nom'], 
                $u['prenom'], 
                $u['email'], 
                $u['telephone'], 
                $montant
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    // Méthode pour récupérer les dons d'un utilisateur spécifique
    public function getDonsParUtilisateur($id_user) {
        $stmt = $this->db->prepare("SELECT * FROM don WHERE id_utilisateur = ? ORDER BY date_don DESC");
        $stmt->execute([$id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour l'admin : voir tous les dons
    public function getAllDons() {
        $stmt = $this->db->query("SELECT * FROM don ORDER BY date_don DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>