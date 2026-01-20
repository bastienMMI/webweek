<?php
// Active l'affichage des erreurs pour voir exactement ce qui bloque
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../config/configuration.php');
include('../scripts/connection.php');

// On récupère l'ID
$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Version directe sans passer par la classe pour éviter les erreurs de chargement
        $sql = "DELETE FROM produit WHERE id_produit = :id";
        $stmt = $connection->prepare($sql);
        $stmt->execute([':id' => $id]);

        // Si ça marche, on retourne à l'admin
        header('Location: ../admin.php?success=suppression');
        exit();
    } catch (Exception $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
} else {
    die("ID produit manquant.");
}