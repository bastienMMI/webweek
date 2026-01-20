<?php
session_start();
include('../config/configuration.php');
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];

    // Gestion de l'image
    $photo_name = "default_product.jpg"; // Image par défaut si besoin
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo_name = time() . "_" . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], "../images/boutique/" . $photo_name);
    }

    // Insertion SQL (ajustez les noms de colonnes selon votre table 'produit')
    $sql = "INSERT INTO produit (nom, prix, description, stock, photo) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    
    if ($stmt->execute([$nom, $prix, $description, $stock, $photo_name])) {
        header("Location: ../admin.php?success=1");
    } else {
        echo "Erreur lors de l'ajout.";
    }
} else {
    header("Location: ../index.php");
}