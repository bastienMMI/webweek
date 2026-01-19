<?php
session_start();
include('../scripts/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] === 'admin') {
    $nom = $_POST['nom'];
    $espece = $_POST['espece'];
    $age = $_POST['age'];
    $sexe = $_POST['sexe'];
    $desc = $_POST['description'];
    
    // Gestion de l'image
    $imageName = time() . '_' . $_FILES['photo']['name']; // On renomme pour éviter les doublons
    $target = "../images/animaux/" . $imageName;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
        $sql = "INSERT INTO animal (nom, espece, age, sexe, description, photo, statut, date_arrivee) 
                VALUES (?, ?, ?, ?, ?, ?, 'disponible', NOW())";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$nom, $espece, $age, $sexe, $desc, $imageName]);
        header("Location: ../admin.php?msg=ajoute");
    } else {
        echo "Erreur lors de l'upload de l'image.";
    }
}