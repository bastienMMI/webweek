<?php
class AnimalManager {
    private $db;

    public function __construct($connection) {
        $this->db = $connection;
    }

    // Récupérer tous les animaux par défaut
    public function getAllAnimaux() {
        $stmt = $this->db->query("SELECT * FROM animal ORDER BY id_animal DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour filtrer par espèce, sexe et âge
    public function getFiltered($espece, $sexe, $age_max) {
        $sql = "SELECT * FROM animal WHERE 1=1";
        $params = [];

        if ($espece !== 'tous' && !empty($espece)) {
            $sql .= " AND espece = ?";
            $params[] = $espece;
        }
        if ($sexe !== 'tous' && !empty($sexe)) {
            $sql .= " AND sexe = ?";
            $params[] = $sexe;
        }
        if ($age_max !== null && $age_max !== '') {
            $sql .= " AND age <= ?";
            $params[] = (int)$age_max;
        }

        $sql .= " ORDER BY id_animal DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>