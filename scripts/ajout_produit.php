<?php
session_start();
require_once(__DIR__ . '/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] === 'admin') {
    $nom = htmlspecialchars($_POST['nom']);
    $prix = $_POST['prix'];
    $description = htmlspecialchars($_POST['description']);
    // Chemin absolu pour les images
    $dossier_images = dirname(__DIR__) . "/images/";

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $nom_image = time() . "_" . basename($_FILES['photo']['name']);
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $dossier_images . $nom_image)) {
            $sql = "INSERT INTO produit (nom_produit, prix, description, photo) VALUES (?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->execute([$nom, $prix, $description, $nom_image]);
            header("Location: ../admin.php?msg=ajoute");
            exit();
        }
    }
}
?>