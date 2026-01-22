<?php
class ProduitManager {
    private $db;

    public function __construct($connection) {
        $this->db = $connection;
    }

    public function getAllProduits() {
        $stmt = $this->db->query("SELECT * FROM produit ORDER BY nom_produit ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduitById($id) {
        $stmt = $this->db->prepare("SELECT * FROM produit WHERE id_produit = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function deleteProduit($id) {
    $sql = "DELETE FROM produit WHERE id_produit = :id";
    $stmt = $this->connection->prepare($sql);
    return $stmt->execute([':id' => $id]);
}
}
?>