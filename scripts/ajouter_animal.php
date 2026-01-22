<?php
session_start();
require_once(__DIR__ . '/connection.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] === 'admin') {
    $nom = htmlspecialchars($_POST['nom']);
    $espece = $_POST['espece'];
    $age = $_POST['age'];
    $sexe = $_POST['sexe'];
    $description = htmlspecialchars($_POST['description']);

    // Chemin absolu pour les images
    $dossier_images = dirname(__DIR__) . "/images/";

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $nom_image = time() . "_" . basename($_FILES['photo']['name']);
        $destination = $dossier_images . $nom_image;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
            
            $sql = "INSERT INTO animal (nom, espece, age, sexe, description, photo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            
            if ($stmt->execute([$nom, $espece, $age, $sexe, $description, $nom_image])) {
                header("Location: ../admin.php?msg=animal_ajoute");
                exit();
            }
        } else {
            echo "Erreur : Impossible de déplacer l'image vers " . $destination;
        }
    }
}
?>